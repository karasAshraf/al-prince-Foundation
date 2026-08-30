import requests
import time

BASE_URL = "http://localhost:8000"
TIMEOUT = 30


def get_admin_token():
    login_payload = {
        "email": "admin@example.com",
        "password": "adminpassword"
    }
    resp = requests.post(
        f"{BASE_URL}/api/auth/login",
        json=login_payload,
        headers={"Content-Type": "application/json", "Accept": "application/json"},
        timeout=TIMEOUT
    )
    assert resp.status_code == 200, f"Admin login failed: {resp.text}"
    data = resp.json()
    token = data.get("token") or data.get("access_token")
    assert token is not None, "No token returned from admin login"
    return token


def test_survey_system_submission_and_throttling():
    admin_token = get_admin_token()
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
    }

    admin_headers = headers.copy()
    admin_headers["Authorization"] = f"Bearer {admin_token}"

    survey_id = None

    # Create a survey
    survey_payload = {
        "title": "Customer Satisfaction Survey",
        "description": "A short survey on customer satisfaction",
        "questions": [
            {
                "question": "How satisfied are you with our product?",
                "type": "multiple_choice",
                "options": ["Very Satisfied", "Satisfied", "Neutral", "Dissatisfied", "Very Dissatisfied"],
                "required": True
            },
            {
                "question": "What is your age group?",
                "type": "single_choice",
                "options": ["Under 18", "18-24", "25-34", "35-44", "45+"],
                "required": False
            },
            {
                "question": "Please provide additional comments",
                "type": "text",
                "required": False
            }
        ],
        "throttle_limit": 5  # Assuming throttle limit field configures max submissions per IP/timeframe
    }

    try:
        # Create survey
        create_resp = requests.post(
            f"{BASE_URL}/api/surveys",
            json=survey_payload,
            headers=admin_headers,
            timeout=TIMEOUT
        )
        assert create_resp.status_code == 201, f"Failed to create survey: {create_resp.text}"
        survey_data = create_resp.json()
        survey_id = survey_data.get("id")
        assert survey_id is not None, "Survey ID not returned on creation"

        # Retrieve survey to confirm creation
        get_resp = requests.get(f"{BASE_URL}/api/surveys/{survey_id}", headers=admin_headers, timeout=TIMEOUT)
        assert get_resp.status_code == 200, f"Failed to retrieve created survey: {get_resp.text}"
        retrieved_survey = get_resp.json()
        assert retrieved_survey.get("id") == survey_id

        # Submit a valid response
        valid_response_payload = {
            "answers": [
                {"question": "How satisfied are you with our product?", "answer": "Satisfied"},
                {"question": "What is your age group?", "answer": "25-34"},
                {"question": "Please provide additional comments", "answer": "Great service!"}
            ]
        }
        submit_resp = requests.post(
            f"{BASE_URL}/api/surveys/{survey_id}/responses",
            json=valid_response_payload,
            headers=headers,
            timeout=TIMEOUT
        )
        assert submit_resp.status_code == 201, f"Failed valid survey response submission: {submit_resp.text}"

        # Submit an invalid response (missing required answer)
        invalid_response_payload = {
            "answers": [
                {"question": "What is your age group?", "answer": "25-34"},
                {"question": "Please provide additional comments", "answer": "No answer to required question"}
            ]
        }
        invalid_resp = requests.post(
            f"{BASE_URL}/api/surveys/{survey_id}/responses",
            json=invalid_response_payload,
            headers=headers,
            timeout=TIMEOUT
        )
        assert invalid_resp.status_code == 422, f"Invalid response accepted or wrong error: {invalid_resp.text}"

        # Enforce throttling limits - submit up to throttle limit successfully
        for i in range(4):  # already submitted 1 valid response, throttle limit = 5
            thr_resp = requests.post(
                f"{BASE_URL}/api/surveys/{survey_id}/responses",
                json=valid_response_payload,
                headers=headers,
                timeout=TIMEOUT
            )
            assert thr_resp.status_code == 201, f"Request {i+2} within throttle limit failed: {thr_resp.text}"

        # Next submission must fail with 429 Too Many Requests due to throttling
        throttle_exceeded_resp = requests.post(
            f"{BASE_URL}/api/surveys/{survey_id}/responses",
            json=valid_response_payload,
            headers=headers,
            timeout=TIMEOUT
        )
        assert throttle_exceeded_resp.status_code == 429, f"Throttling not enforced: {throttle_exceeded_resp.text}"

        # Get survey analytics
        analytics_resp = requests.get(f"{BASE_URL}/api/surveys/{survey_id}/analytics", headers=headers, timeout=TIMEOUT)
        assert analytics_resp.status_code == 200, f"Failed to get survey analytics: {analytics_resp.text}"
        analytics_data = analytics_resp.json()
        assert "response_count" in analytics_data
        assert analytics_data["response_count"] >= 5

    finally:
        # Cleanup: delete the created survey to avoid test pollution
        if survey_id:
            try:
                del_resp = requests.delete(f"{BASE_URL}/api/surveys/{survey_id}", headers=admin_headers, timeout=TIMEOUT)
                # Accept 200 or 204 as successful deletion
                assert del_resp.status_code in (200, 204), f"Failed to delete survey in cleanup: {del_resp.text}"
            except Exception:
                pass


test_survey_system_submission_and_throttling()
