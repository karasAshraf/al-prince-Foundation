import requests

BASE_URL = "http://localhost:8000"
TIMEOUT = 30

# Assuming there's an admin user with credentials for authorization
ADMIN_CREDENTIALS = {"email": "admin@example.com", "password": "StrongPassword123!"}

def get_auth_token():
    try:
        r = requests.post(f"{BASE_URL}/api/auth/login", json=ADMIN_CREDENTIALS, timeout=TIMEOUT)
        r.raise_for_status()
        token = r.json().get("token") or r.json().get("access_token")
        assert token, "Auth token not found in login response"
        return token
    except Exception as e:
        raise RuntimeError(f"Failed to authenticate admin user: {e}")

def test_service_management_crud_and_authorization():
    token = get_auth_token()
    headers_auth = {
        "Authorization": f"Bearer {token}",
        "Accept": "application/json",
        "Content-Type": "application/json"
    }
    headers_no_auth = {
        "Accept": "application/json",
        "Content-Type": "application/json"
    }

    service_data_valid = {
        "title": "Test Service",
        "description": "This is a test service description",
        "details": "Additional details about the test service"
    }

    service_data_update = {
        "title": "Updated Test Service",
        "description": "Updated description for the test service",
        "details": "Updated additional details"
    }

    service_data_invalid = {
        "title": "",  # Assuming title is required and cannot be empty
        "description": "Invalid service without a title"
    }

    created_service_id = None

    # Create Service - valid data - expect 201
    try:
        resp_create = requests.post(f"{BASE_URL}/api/services", headers=headers_auth, json=service_data_valid, timeout=TIMEOUT)
        assert resp_create.status_code == 201, f"Expected 201 Created, got {resp_create.status_code}"
        json_create = resp_create.json()
        created_service_id = json_create.get("id")
        assert created_service_id is not None, "Created service ID not returned"
        # Validate returned data matches input
        assert json_create.get("title") == service_data_valid["title"]
        assert json_create.get("description") == service_data_valid["description"]
    except Exception as e:
        raise AssertionError(f"Service creation failed: {e}")

    try:
        # Retrieve Service - expect 200 and matching data
        resp_get = requests.get(f"{BASE_URL}/api/services/{created_service_id}", headers=headers_auth, timeout=TIMEOUT)
        assert resp_get.status_code == 200, f"Expected 200 OK on get, got {resp_get.status_code}"
        json_get = resp_get.json()
        assert json_get.get("id") == created_service_id
        assert json_get.get("title") == service_data_valid["title"]

        # Update Service - patch with valid data - expect 200 and updated fields
        resp_update = requests.patch(f"{BASE_URL}/api/services/{created_service_id}", headers=headers_auth, json=service_data_update, timeout=TIMEOUT)
        assert resp_update.status_code == 200, f"Expected 200 OK on update, got {resp_update.status_code}"
        json_update = resp_update.json()
        assert json_update.get("title") == service_data_update["title"]
        assert json_update.get("description") == service_data_update["description"]

        # Retrieve after update to confirm changes
        resp_get_updated = requests.get(f"{BASE_URL}/api/services/{created_service_id}", headers=headers_auth, timeout=TIMEOUT)
        assert resp_get_updated.status_code == 200, f"Expected 200 OK on get after update, got {resp_get_updated.status_code}"
        json_get_updated = resp_get_updated.json()
        assert json_get_updated.get("title") == service_data_update["title"]

        # Try creating service with invalid payload - expect 422 validation error
        resp_invalid = requests.post(f"{BASE_URL}/api/services", headers=headers_auth, json=service_data_invalid, timeout=TIMEOUT)
        assert resp_invalid.status_code == 422, f"Expected 422 Unprocessable Entity for invalid create, got {resp_invalid.status_code}"

        # Attempt to delete service without authorization - expect 403 forbidden
        resp_delete_unauth = requests.delete(f"{BASE_URL}/api/services/{created_service_id}", headers=headers_no_auth, timeout=TIMEOUT)
        assert resp_delete_unauth.status_code == 403, f"Expected 403 Forbidden for unauth delete, got {resp_delete_unauth.status_code}"

        # Delete service with proper authorization - expect 204 No Content or 200 OK
        resp_delete_auth = requests.delete(f"{BASE_URL}/api/services/{created_service_id}", headers=headers_auth, timeout=TIMEOUT)
        assert resp_delete_auth.status_code in (200, 204), f"Expected 200 or 204 on authorized delete, got {resp_delete_auth.status_code}"

        # Confirm service no longer exists - expect 404 on get
        resp_get_deleted = requests.get(f"{BASE_URL}/api/services/{created_service_id}", headers=headers_auth, timeout=TIMEOUT)
        assert resp_get_deleted.status_code == 404, f"Expected 404 Not Found for deleted service, got {resp_get_deleted.status_code}"

        # Set created_service_id to None to prevent delete in finally since already deleted
        created_service_id = None

    finally:
        # Cleanup: delete service if still exists
        if created_service_id is not None:
            try:
                requests.delete(f"{BASE_URL}/api/services/{created_service_id}", headers=headers_auth, timeout=TIMEOUT)
            except Exception:
                pass


test_service_management_crud_and_authorization()