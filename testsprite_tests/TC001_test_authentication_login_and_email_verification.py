import requests

BASE_URL = "http://localhost:8000"
TIMEOUT = 30


def test_authentication_login_and_email_verification():
    login_url = f"{BASE_URL}/api/auth/login"
    user_url = f"{BASE_URL}/api/auth/user"
    email_verify_url = f"{BASE_URL}/api/auth/email/verify"
    dashboard_url = f"{BASE_URL}/api/dashboard"  # Assuming dashboard endpoint as protected resource

    # Valid user credentials - These must be valid in the system for test
    valid_credentials = {
        "email": "testuser@example.com",
        "password": "TestPassword123"
    }

    # Step 1: Login with valid credentials
    try:
        login_resp = requests.post(login_url, json=valid_credentials, timeout=TIMEOUT)
    except requests.RequestException as e:
        assert False, f"Login request failed: {e}"
    assert login_resp.status_code == 200, f"Expected 200 OK for valid login, got {login_resp.status_code}"
    login_data = login_resp.json()
    token = login_data.get("token") or login_data.get("access_token")
    assert token, "No authentication token received after login"

    headers = {"Authorization": f"Bearer {token}"}

    # Step 2: Get authenticated user details
    try:
        user_resp = requests.get(user_url, headers=headers, timeout=TIMEOUT)
    except requests.RequestException as e:
        assert False, f"Get user request failed: {e}"
    assert user_resp.status_code == 200, f"Expected 200 OK for user info, got {user_resp.status_code}"
    user_data = user_resp.json()
    assert "email" in user_data and user_data["email"].lower() == valid_credentials["email"].lower()
    assert "roles" in user_data and isinstance(user_data["roles"], list)

    # Step 3: Email verification with valid token
    # For testing, we need a valid token. Normally, this token is received by email.
    # Here we try to simulate by using one from login_data or user_data or mock.
    # We'll try a placeholder token known to be valid for testing environment.
    valid_verification_token = login_data.get("email_verification_token") or "valid-token-for-testing"
    verify_payload = {"token": valid_verification_token}

    try:
        verify_resp = requests.post(email_verify_url, json=verify_payload, timeout=TIMEOUT)
    except requests.RequestException as e:
        assert False, f"Email verification request failed: {e}"

    # It may return 200 on success or 409 if already verified
    assert verify_resp.status_code in (200, 409), f"Email verification with valid token failed with status {verify_resp.status_code}"
    verify_data = verify_resp.json()
    assert (
        "verified" in verify_data or "message" in verify_data
    ), "Response missing verification confirmation"

    # Step 4: Access protected dashboard endpoint with verified user
    try:
        dashboard_resp = requests.get(dashboard_url, headers=headers, timeout=TIMEOUT)
    except requests.RequestException as e:
        assert False, f"Dashboard access request failed: {e}"
    assert dashboard_resp.status_code == 200, f"Expected 200 OK for dashboard access, got {dashboard_resp.status_code}"

    # Step 5: Email verification with invalid token
    invalid_token_payload = {"token": "invalid-or-expired-token-for-test"}
    try:
        invalid_verify_resp = requests.post(email_verify_url, json=invalid_token_payload, timeout=TIMEOUT)
    except requests.RequestException as e:
        assert False, f"Email verification request with invalid token failed: {e}"
    assert invalid_verify_resp.status_code in (400, 422), f"Expected 400 or 422 for invalid token, got {invalid_verify_resp.status_code}"


test_authentication_login_and_email_verification()