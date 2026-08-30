import requests
import uuid

BASE_URL = "http://localhost:8000"
TIMEOUT = 30

# Step 0: Obtain a valid admin token by logging in
LOGIN_PAYLOAD = {
    "email": "admin@example.com",
    "password": "adminpassword"
}

resp = requests.post(f"{BASE_URL}/api/auth/login", json=LOGIN_PAYLOAD, timeout=TIMEOUT)
assert resp.status_code == 200, f"Login failed: {resp.text}"
login_data = resp.json()
token = login_data.get("token") or login_data.get("access_token")
assert token, "Token not found in login response"

HEADERS = {
    "Content-Type": "application/json",
    "Authorization": f"Bearer {token}"
}

def test_project_management_slug_generation_and_relationships():
    program_id = None
    project_id = None
    created_project_slug = None

    try:
        # Step 1: Create a program to link the project to
        program_payload = {
            "name": f"Test Program {uuid.uuid4()}",
            "description": "Program for linking projects in slug test",
            "status": True
        }
        resp = requests.post(f"{BASE_URL}/api/programs", json=program_payload, headers=HEADERS, timeout=TIMEOUT)
        assert resp.status_code == 201, f"Program creation failed: {resp.text}"
        program_data = resp.json()
        program_id = program_data.get("id")
        assert program_id is not None, "Program ID not returned"

        # Step 2: Create a project linked to the created program to test slug generation
        project_payload = {
            "title": "My First Test Project",
            "description": "Description for project slug generation test",
            "program_id": program_id
        }
        resp = requests.post(f"{BASE_URL}/api/projects", json=project_payload, headers=HEADERS, timeout=TIMEOUT)
        assert resp.status_code == 201, f"Project creation failed: {resp.text}"
        project_data = resp.json()
        created_project_slug = project_data.get("slug")
        project_id = project_data.get("id")
        assert created_project_slug, "Slug not generated on project creation"
        assert project_data.get("program_id") == program_id, "Project-program relationship not set"

        # Step 3: Retrieve project by slug and validate data and program relationship
        resp = requests.get(f"{BASE_URL}/api/projects/{created_project_slug}", headers=HEADERS, timeout=TIMEOUT)
        assert resp.status_code == 200, f"Failed to get project by slug: {resp.text}"
        retrieved = resp.json()
        assert retrieved.get("slug") == created_project_slug, "Slug mismatch on retrieval"
        assert retrieved.get("program_id") == program_id, "Program relationship missing on retrieval"

        # Step 4: Update project title (which might regenerate or preserve slug)
        updated_title = "My First Test Project Updated"
        patch_payload = {
            "title": updated_title,
        }
        resp = requests.patch(f"{BASE_URL}/api/projects/{project_id}", json=patch_payload, headers=HEADERS, timeout=TIMEOUT)
        assert resp.status_code == 200, f"Project update failed: {resp.text}"
        updated_project = resp.json()
        assert updated_project.get("title") == updated_title, "Title not updated"

        # Slug preservation or regeneration - either slug remains same or changes logically based on new title
        updated_slug = updated_project.get("slug")
        assert updated_slug, "Slug missing after update"
        # It's valid that slug either changed or stayed same but must be a valid slug string
        assert isinstance(updated_slug, str) and updated_slug.strip() != "", "Invalid slug after update"

        # Step 5: Attempt to create a project with duplicate slug (should error)
        duplicate_slug_payload = {
            "title": "Some Other Project",
            "slug": created_project_slug,
            "program_id": program_id
        }
        resp = requests.post(f"{BASE_URL}/api/projects", json=duplicate_slug_payload, headers=HEADERS, timeout=TIMEOUT)
        assert resp.status_code == 422, f"Duplicate slug creation did not fail as expected: {resp.text}"

        # Step 6: Attempt to create a project without program relationship (should error)
        no_program_payload = {
            "title": "Project Without Program"
        }
        resp = requests.post(f"{BASE_URL}/api/projects", json=no_program_payload, headers=HEADERS, timeout=TIMEOUT)
        assert resp.status_code == 422, f"Project creation without program did not fail as expected: {resp.text}"

    finally:
        # Cleanup: Delete created project if present
        if project_id:
            try:
                requests.delete(f"{BASE_URL}/api/projects/{project_id}", headers=HEADERS, timeout=TIMEOUT)
            except Exception:
                pass
        # Cleanup: Delete created program if present
        if program_id:
            try:
                requests.delete(f"{BASE_URL}/api/programs/{program_id}", headers=HEADERS, timeout=TIMEOUT)
            except Exception:
                pass

test_project_management_slug_generation_and_relationships()
