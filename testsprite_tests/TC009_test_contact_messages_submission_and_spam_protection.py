import requests

BASE_URL = "http://localhost:8000"
TIMEOUT = 30

def test_contact_messages_submission_and_spam_protection():
    # Public: Submit valid contact message with empty honeypot (should succeed)
    contact_payload_valid = {
        "name": "Test User",
        "email": "testuser@example.com",
        "message": "This is a test message for contact form.",
        "honeypot": ""  # Honeypot must be empty
    }

    resp_submit = requests.post(f"{BASE_URL}/api/contact-messages", json=contact_payload_valid, timeout=TIMEOUT)
    assert resp_submit.status_code == 201, f"Expected 201, got {resp_submit.status_code}"
    json_submit = resp_submit.json()
    assert "id" in json_submit, "Response missing 'id' field for created message"
    message_id = json_submit["id"]

    # Public: Submit contact message with honeypot filled (should fail 422 spam validation)
    contact_payload_spam = {
        "name": "Spam Bot",
        "email": "spam@example.com",
        "message": "Spam message attempting to bypass honeypot.",
        "honeypot": "I am a bot"  # Filled honeypot triggers spam validation error
    }
    resp_spam = requests.post(f"{BASE_URL}/api/contact-messages", json=contact_payload_spam, timeout=TIMEOUT)
    assert resp_spam.status_code == 422, f"Expected 422, got {resp_spam.status_code}"

    # Admin credentials - assuming predefined valid admin user for test
    admin_auth = {
        "email": "admin@example.com",
        "password": "adminpass"
    }
    # Login admin to get token
    resp_login = requests.post(f"{BASE_URL}/api/auth/login", json=admin_auth, timeout=TIMEOUT)
    assert resp_login.status_code == 200, f"Admin login failed with status {resp_login.status_code}"
    token = resp_login.json().get("token")
    assert token, "No token received on admin login"
    headers_auth = {"Authorization": f"Bearer {token}"}

    try:
        # Admin: Retrieve messages list (including recently created)
        resp_list = requests.get(f"{BASE_URL}/api/contact-messages", headers=headers_auth, timeout=TIMEOUT)
        assert resp_list.status_code == 200, f"Expected 200 on GET messages, got {resp_list.status_code}"
        messages = resp_list.json()
        assert any(m.get("id") == message_id for m in messages), "Created message not found in admin contact messages list"

        # Admin: Retrieve specific contact message by ID
        resp_get = requests.get(f"{BASE_URL}/api/contact-messages/{message_id}", headers=headers_auth, timeout=TIMEOUT)
        assert resp_get.status_code == 200, f"Expected 200 on GET message by ID, got {resp_get.status_code}"
        message_data = resp_get.json()
        assert message_data.get("id") == message_id, "Retrieved message ID does not match"

        # Unauthorized deletion attempt (no auth header)
        resp_delete_unauth = requests.delete(f"{BASE_URL}/api/contact-messages/{message_id}", timeout=TIMEOUT)
        assert resp_delete_unauth.status_code == 403, f"Expected 403 on unauthorized DELETE, got {resp_delete_unauth.status_code}"

        # Authorized deletion of contact message by admin
        resp_delete_auth = requests.delete(f"{BASE_URL}/api/contact-messages/{message_id}", headers=headers_auth, timeout=TIMEOUT)
        assert resp_delete_auth.status_code == 204, f"Expected 204 on authorized DELETE, got {resp_delete_auth.status_code}"

        # Confirm deletion by fetching the message again (expect 404)
        resp_get_deleted = requests.get(f"{BASE_URL}/api/contact-messages/{message_id}", headers=headers_auth, timeout=TIMEOUT)
        assert resp_get_deleted.status_code == 404, f"Expected 404 for deleted message, got {resp_get_deleted.status_code}"

    finally:
        # Cleanup: If message still exists, attempt deletion with admin auth
        resp_check = requests.get(f"{BASE_URL}/api/contact-messages/{message_id}", headers=headers_auth, timeout=TIMEOUT)
        if resp_check.status_code == 200:
            requests.delete(f"{BASE_URL}/api/contact-messages/{message_id}", headers=headers_auth, timeout=TIMEOUT)

test_contact_messages_submission_and_spam_protection()