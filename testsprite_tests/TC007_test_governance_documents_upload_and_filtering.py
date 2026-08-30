import requests
from io import BytesIO

BASE_URL = "http://localhost:8000"
TIMEOUT = 30
HEADERS = {
    # Assuming authentication is required and token is set here if needed
    # "Authorization": "Bearer <token>"
}

def test_governance_documents_upload_and_filtering():
    files_to_cleanup = []
    created_doc_id = None
    try:
        # 1. Upload a valid governance document with category and file upload
        upload_url = f"{BASE_URL}/api/governance-documents"
        pdf_file = BytesIO(b"%PDF-1.4 test content")
        files = {
            "file": ("test_document.pdf", pdf_file, "application/pdf"),
        }
        data = {
            "category": "policy"
        }
        response = requests.post(upload_url, headers=HEADERS, data=data, files=files, timeout=TIMEOUT)
        assert response.status_code == 201, f"Expected 201 for valid upload, got {response.status_code}"
        resp_json = response.json()
        assert "id" in resp_json, "Response body should contain 'id'"
        created_doc_id = resp_json["id"]
        files_to_cleanup.append(created_doc_id)

        # 2. Retrieve documents by category
        get_by_category_url = f"{BASE_URL}/api/governance-documents"
        params = {"category": "policy"}
        response = requests.get(get_by_category_url, headers=HEADERS, params=params, timeout=TIMEOUT)
        assert response.status_code == 200, f"Expected 200 getting documents by category, got {response.status_code}"
        resp_json = response.json()
        assert isinstance(resp_json, list), "Expected list of documents"
        # Check that uploaded doc is in the list
        assert any(doc.get("id") == created_doc_id for doc in resp_json), "Uploaded document not found by category filter"

        # 3. Upload governance document with missing file - expect 422 validation error
        data_missing_file = {
            "category": "policy"
        }
        response = requests.post(upload_url, headers=HEADERS, data=data_missing_file, timeout=TIMEOUT)
        assert response.status_code == 422, f"Expected 422 for missing file, got {response.status_code}"

        # 4. Upload governance document with missing category - expect 422 validation error
        pdf_file = BytesIO(b"%PDF-1.4 test content")
        files = {
            "file": ("test_document.pdf", pdf_file, "application/pdf"),
        }
        response = requests.post(upload_url, headers=HEADERS, files=files, timeout=TIMEOUT)
        assert response.status_code == 422, f"Expected 422 for missing category, got {response.status_code}"

        # 5. Upload governance document with disallowed file type - expect 422 or 415 error
        exe_file = BytesIO(b"MZ")  # Simple signature for an exe file
        files = {
            "file": ("test_document.exe", exe_file, "application/x-msdownload"),
        }
        data = {
            "category": "policy"
        }
        response = requests.post(upload_url, headers=HEADERS, data=data, files=files, timeout=TIMEOUT)
        assert response.status_code in (422, 415), f"Expected 422 or 415 for disallowed file type, got {response.status_code}"

    finally:
        # Cleanup created governance document(s)
        for doc_id in files_to_cleanup:
            try:
                del_url = f"{BASE_URL}/api/governance-documents/{doc_id}"
                _ = requests.delete(del_url, headers=HEADERS, timeout=TIMEOUT)
            except Exception:
                pass


test_governance_documents_upload_and_filtering()
