import requests
import io

BASE_URL = "http://localhost:8000"
TIMEOUT = 30

# Replace with valid admin authentication credentials or token retrieval method
AUTH_TOKEN = "your_admin_auth_token_here"
HEADERS = {
    "Authorization": f"Bearer {AUTH_TOKEN}",
    "Accept": "application/json"
}


def test_news_management_crud_and_media_handling():
    news_endpoint = f"{BASE_URL}/api/news"
    
    # Sample valid news article data
    news_data = {
        "title": "Sample News Title",
        "content": "This is the content of the sample news article.",
        "locale": "en"
    }

    # 1. Create news story with valid data and media attachments
    try:
        # Create news without media first (assume media is separate upload)
        response_create = requests.post(
            news_endpoint,
            json=news_data,
            headers=HEADERS,
            timeout=TIMEOUT
        )
        assert response_create.status_code == 201, f"News creation failed: {response_create.text}"
        news_item = response_create.json()
        news_id = news_item.get("id")
        assert news_id is not None, "News ID not returned in creation response"

        # 2. Upload supported media to the news story
        media_endpoint = f"{news_endpoint}/{news_id}/media"
        # Simulate a valid image file (PNG)
        valid_image_content = io.BytesIO(b'\x89PNG\r\n\x1a\n\x00\x00\x00\rIHDR')
        files = {
            "file": ("valid-image.png", valid_image_content, "image/png")
        }
        response_media_upload = requests.post(
            media_endpoint,
            headers={"Authorization": HEADERS["Authorization"]},
            files=files,
            timeout=TIMEOUT
        )
        assert response_media_upload.status_code in (200, 201), f"Valid media upload failed: {response_media_upload.text}"

        # 3. Attempt to upload unsupported media type (e.g., .exe file)
        unsupported_file_content = io.BytesIO(b'MZP\x00\x02\x00\x00\x00')
        files_unsupported = {
            "file": ("malicious.exe", unsupported_file_content, "application/x-msdownload")
        }
        response_unsupported_media = requests.post(
            media_endpoint,
            headers={"Authorization": HEADERS["Authorization"]},
            files=files_unsupported,
            timeout=TIMEOUT
        )
        assert response_unsupported_media.status_code in (422, 415), "Unsupported media type should be rejected"

        # 4. Edit the news story (PATCH) - update title and content
        updated_data = {
            "title": "Updated News Title",
            "content": "Updated content for the news article."
        }
        response_edit = requests.patch(
            f"{news_endpoint}/{news_id}",
            json=updated_data,
            headers=HEADERS,
            timeout=TIMEOUT
        )
        assert response_edit.status_code == 200, f"News edit failed: {response_edit.text}"
        updated_news = response_edit.json()
        assert updated_news.get("title") == updated_data["title"], "News title not updated"
        assert updated_news.get("content") == updated_data["content"], "News content not updated"

        # 5. Publish the news story via status endpoint
        status_endpoint = f"{news_endpoint}/{news_id}/status"
        publish_payload = {"status": "published"}
        response_publish = requests.patch(
            status_endpoint,
            json=publish_payload,
            headers=HEADERS,
            timeout=TIMEOUT
        )
        assert response_publish.status_code == 200, f"Publishing news failed: {response_publish.text}"
        published_news = response_publish.json()
        assert published_news.get("status") == "published", "News status not updated to published"

        # 6. Retrieve the news story and verify data
        response_get = requests.get(
            f"{news_endpoint}/{news_id}",
            headers=HEADERS,
            timeout=TIMEOUT
        )
        assert response_get.status_code == 200, f"Failed to get news item: {response_get.text}"
        news_details = response_get.json()
        assert news_details.get("id") == news_id, "Retrieved news ID mismatch"
        assert news_details.get("title") == updated_data["title"], "Retrieved news title mismatch"
        assert news_details.get("status") == "published", "Retrieved news status mismatch"
        media_list = news_details.get("media", [])
        assert any(m.get("file_name") == "valid-image.png" for m in media_list), "Uploaded media file not listed"

        # 7. Attempt to create news with missing required fields (validation error)
        incomplete_news_data = {
            "content": "Missing title field"
        }
        response_invalid_create = requests.post(
            news_endpoint,
            json=incomplete_news_data,
            headers=HEADERS,
            timeout=TIMEOUT
        )
        assert response_invalid_create.status_code == 422, "Creating news with missing fields should fail with 422"

    finally:
        # Cleanup: delete the created news item if exists
        if 'news_id' in locals():
            del_response = requests.delete(
                f"{news_endpoint}/{news_id}",
                headers=HEADERS,
                timeout=TIMEOUT
            )
            # Accept 200 or 204 for successful delete, or 404 if already deleted
            assert del_response.status_code in (200, 204, 404), f"Failed to delete news item: {del_response.text}"


test_news_management_crud_and_media_handling()