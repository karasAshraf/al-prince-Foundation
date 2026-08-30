import requests

BASE_URL = "http://localhost:8000"
TIMEOUT = 30

# Credentials for an admin user - adjust as needed for real testing environment
ADMIN_CREDENTIALS = {
    "email": "admin@example.com",
    "password": "adminpassword"
}

def authenticate_admin():
    login_url = f"{BASE_URL}/api/auth/login"
    resp = requests.post(login_url, json=ADMIN_CREDENTIALS, timeout=TIMEOUT)
    assert resp.status_code == 200, f"Admin login failed: {resp.text}"
    token = resp.json().get("token") or resp.json().get("access_token") or resp.json().get("data", {}).get("token")
    assert token, "No token in login response"
    return token

def test_program_management_crud_and_status_toggle():
    token = authenticate_admin()
    headers = {
        "Authorization": f"Bearer {token}",
        "Accept": "application/json",
        "Content-Type": "application/json"
    }

    # 1. Test creating a program with valid data
    create_url = f"{BASE_URL}/api/programs"
    valid_payload = {
        "title": "Test Program",
        "description": "This is a test program description.",
        "visibility": True
    }
    created_program_id = None
    try:
        create_resp = requests.post(create_url, json=valid_payload, headers=headers, timeout=TIMEOUT)
        assert create_resp.status_code == 201, f"Failed to create program: {create_resp.text}"
        program_data = create_resp.json()
        created_program_id = program_data.get("id") or program_data.get("data", {}).get("id")
        assert created_program_id, "Created program response missing 'id'"

        # 2. Retrieve the created program
        get_url = f"{BASE_URL}/api/programs/{created_program_id}"
        get_resp = requests.get(get_url, headers=headers, timeout=TIMEOUT)
        assert get_resp.status_code == 200, f"Failed to retrieve program: {get_resp.text}"
        get_data = get_resp.json()
        # Check some expected fields match posted data
        returned_title = get_data.get("title") or get_data.get("data", {}).get("title")
        returned_description = get_data.get("description") or get_data.get("data", {}).get("description")
        assert returned_title == valid_payload["title"], "Program title mismatch"
        assert returned_description == valid_payload["description"], "Program description mismatch"
        returned_visibility = get_data.get("visibility") if "visibility" in get_data else get_data.get("data", {}).get("visibility")
        assert returned_visibility is True, "Program visibility mismatch"

        # 3. Toggle the status (visibility) of the program
        patch_url = f"{BASE_URL}/api/programs/{created_program_id}/status"
        toggle_payload = {"visibility": False}
        patch_resp = requests.patch(patch_url, json=toggle_payload, headers=headers, timeout=TIMEOUT)
        assert patch_resp.status_code == 200, f"Failed to toggle program status: {patch_resp.text}"
        patch_data = patch_resp.json()
        toggled_visibility = patch_data.get("visibility") if "visibility" in patch_data else patch_data.get("data", {}).get("visibility")
        assert toggled_visibility is False, "Program status toggle did not update visibility"

        # 4. Confirm visibility update with GET
        get_resp2 = requests.get(get_url, headers=headers, timeout=TIMEOUT)
        assert get_resp2.status_code == 200, f"Failed to retrieve program after status toggle: {get_resp2.text}"
        get_data2 = get_resp2.json()
        visibility_after_toggle = get_data2.get("visibility") if "visibility" in get_data2 else get_data2.get("data", {}).get("visibility")
        assert visibility_after_toggle is False, "Program visibility not updated after toggle"

        # 5. Test creating program with invalid payload (missing required fields)
        invalid_payload = {
            "description": "Missing title field"
        }
        invalid_resp = requests.post(create_url, json=invalid_payload, headers=headers, timeout=TIMEOUT)
        assert invalid_resp.status_code == 422, f"Expected 422 for invalid payload, got {invalid_resp.status_code}"

        # 6. Test creating program with empty payload
        empty_resp = requests.post(create_url, json={}, headers=headers, timeout=TIMEOUT)
        assert empty_resp.status_code == 422, f"Expected 422 for empty payload, got {empty_resp.status_code}"

    finally:
        # Cleanup: Delete the program if it was created
        if created_program_id:
            del_url = f"{BASE_URL}/api/programs/{created_program_id}"
            del_resp = requests.delete(del_url, headers=headers, timeout=TIMEOUT)
            # Accept both 200 and 204 for delete success
            assert del_resp.status_code in (200, 204), f"Failed to delete program: {del_resp.text}"

test_program_management_crud_and_status_toggle()