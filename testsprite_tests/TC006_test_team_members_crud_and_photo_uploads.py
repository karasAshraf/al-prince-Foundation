import requests
import io

BASE_URL = "http://localhost:8000"
TIMEOUT = 30

# Example admin credentials for authentication (adjust as needed)
ADMIN_CREDENTIALS = {
    "email": "admin@example.com",
    "password": "adminpassword"
}

HEADERS_JSON = {
    "Accept": "application/json",
    "Content-Type": "application/json"
}

def authenticate_admin():
    url = f"{BASE_URL}/api/auth/login"
    resp = requests.post(url, json=ADMIN_CREDENTIALS, headers=HEADERS_JSON, timeout=TIMEOUT)
    assert resp.status_code == 200, f"Admin login failed: {resp.text}"
    data = resp.json()
    token = data.get("token") or data.get("access_token")
    assert token, "No token received after login"
    return token

def test_team_members_crud_and_photo_uploads():
    token = authenticate_admin()
    headers_auth = {
        "Authorization": f"Bearer {token}"
    }
    member_id = None

    # Prepare a valid image byte stream (small PNG 1x1 transparent pixel)
    valid_image_data = (
        b"\x89PNG\r\n\x1a\n\x00\x00\x00\rIHDR\x00\x00\x00\x01"
        b"\x00\x00\x00\x01\x08\x06\x00\x00\x00\x1f\x15\xc4\x89"
        b"\x00\x00\x00\nIDATx\x9cc`\x00\x00\x00\x02\x00\x01"
        b"\xe2!\xbc3\x00\x00\x00\x00IEND\xaeB`\x82"
    )
    valid_image_file = ("photo.png", io.BytesIO(valid_image_data), "image/png")

    # Prepare an unsupported image format byte stream (e.g. .txt pretending to be image)
    unsupported_image_data = b"This is not an image file."
    unsupported_image_file = ("photo.txt", io.BytesIO(unsupported_image_data), "text/plain")

    try:
        # 1) Create a team member with photo upload (valid image)
        create_url = f"{BASE_URL}/api/team-members"
        create_data = {
            "name": "Test Member",
            "role": "Board Member",
            "bio": "Initial bio"
        }
        files = {"photo": valid_image_file}
        resp_create = requests.post(create_url, headers=headers_auth, data=create_data, files=files, timeout=TIMEOUT)
        assert resp_create.status_code == 201, f"Failed to create team member: {resp_create.text}"
        created_member = resp_create.json()
        member_id = created_member.get("id")
        assert member_id, "Created member ID missing"
        assert created_member.get("name") == "Test Member"
        assert "photo_url" in created_member or "photo" in created_member, "Photo not returned upon creation"

        # 2) Retrieve the created member and verify details
        get_url = f"{BASE_URL}/api/team-members/{member_id}"
        resp_get = requests.get(get_url, headers=headers_auth, timeout=TIMEOUT)
        assert resp_get.status_code == 200, f"Failed to retrieve member: {resp_get.text}"
        member_data = resp_get.json()
        assert member_data.get("id") == member_id
        assert member_data.get("name") == "Test Member"
        assert "photo_url" in member_data or "photo" in member_data

        # 3) Update team member: change role/title and upload a new valid photo
        update_url = f"{BASE_URL}/api/team-members/{member_id}"
        update_data = {
            "role": "Executive Director",
            "bio": "Updated bio information"
        }
        # Re-using same valid image for update
        update_files = {"photo": valid_image_file}
        resp_update = requests.patch(update_url, headers=headers_auth, data=update_data, files=update_files, timeout=TIMEOUT)
        assert resp_update.status_code == 200, f"Failed to update team member: {resp_update.text}"
        updated_member = resp_update.json()
        assert updated_member.get("role") == "Executive Director"
        assert updated_member.get("bio") == "Updated bio information"
        assert "photo_url" in updated_member or "photo" in updated_member

        # 4) Attempt to create a team member with unsupported image format -> expect 422 or 415 error
        bad_create_data = {
            "name": "Bad Image Member",
            "role": "Member",
            "bio": "This should fail"
        }
        files_bad = {"photo": unsupported_image_file}
        resp_bad = requests.post(create_url, headers=headers_auth, data=bad_create_data, files=files_bad, timeout=TIMEOUT)
        assert resp_bad.status_code in (415, 422), f"Unsupported image upload did not fail as expected: {resp_bad.status_code} {resp_bad.text}"

        # 5) Attempt to delete the created team member with no or insufficient authorization
        # We'll try without token and with a dummy token to simulate insufficient permission
        delete_url = f"{BASE_URL}/api/team-members/{member_id}"

        # Without authorization header
        resp_del_no_auth = requests.delete(delete_url, timeout=TIMEOUT)
        assert resp_del_no_auth.status_code in (403, 401), f"Delete without auth should be forbidden: {resp_del_no_auth.status_code}"

        # With invalid/insufficient token
        headers_bad_auth = {"Authorization": "Bearer invalid_or_insufficient_token"}
        resp_del_bad_auth = requests.delete(delete_url, headers=headers_bad_auth, timeout=TIMEOUT)
        assert resp_del_bad_auth.status_code in (403, 401), f"Delete with bad auth should be forbidden: {resp_del_bad_auth.status_code}"

        # 6) Finally, delete the resource with proper authorization to clean up
        resp_del = requests.delete(delete_url, headers=headers_auth, timeout=TIMEOUT)
        assert resp_del.status_code in (200, 204), f"Failed to delete team member with proper auth: {resp_del.status_code} {resp_del.text}"
        member_id = None  # Mark as deleted

    finally:
        # Cleanup: if member was created and not deleted, delete now with admin auth
        if member_id is not None:
            try:
                requests.delete(f"{BASE_URL}/api/team-members/{member_id}", headers=headers_auth, timeout=TIMEOUT)
            except Exception:
                pass

test_team_members_crud_and_photo_uploads()
