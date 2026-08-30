import requests

BASE_URL = "http://localhost:8000"
TIMEOUT = 30
HEADERS = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    # Add authentication header if required, e.g.:
    # 'Authorization': 'Bearer YOUR_TOKEN_HERE',
}

def test_homepage_sections_and_hero_slides_management():
    created_slide_id = None
    created_section_id = None
    try:
        # 1. Test creating a hero slide with valid data (including image)
        # As we cannot upload files easily here, we simulate image by sending image_id or media ID reference
        # Assuming `image` field expects a media asset id or URL string (not explicitly defined, so use a placeholder)
        hero_slide_payload = {
            "title": "Test Hero Slide",
            "subtitle": "Subtitle for Hero Slide",
            "content": "Some content for the hero slide.",
            "image": "test-image-id-or-url"  # Placeholder, as file upload details not provided
        }
        response = requests.post(
            f"{BASE_URL}/api/homepage/hero-slides",
            json=hero_slide_payload,
            headers=HEADERS,
            timeout=TIMEOUT
        )
        assert response.status_code == 201, f"Create hero slide failed: {response.text}"
        slide = response.json()
        created_slide_id = slide.get('id')
        assert created_slide_id is not None, "Created hero slide ID missing"

        # 2. Test creating a hero slide with missing image asset -> expect validation 422
        invalid_slide_payload = {
            "title": "Invalid Slide No Image",
            "subtitle": "Subtitle",
            "content": "Content without image",
            # no image field
        }
        response = requests.post(
            f"{BASE_URL}/api/homepage/hero-slides",
            json=invalid_slide_payload,
            headers=HEADERS,
            timeout=TIMEOUT
        )
        assert response.status_code == 422, "Expected 422 validation error for missing hero slide image"

        # 3. Test reordering hero slides with valid payload
        # Reorder payload is usually list/array of slide IDs with new order.
        # We can reorder with a single slide since only one created here
        reorder_payload = [created_slide_id]
        response = requests.patch(
            f"{BASE_URL}/api/homepage/hero-slides/reorder",
            json=reorder_payload,
            headers=HEADERS,
            timeout=TIMEOUT
        )
        assert response.status_code == 200, f"Reordering hero slides failed: {response.text}"

        # 4. Test creating a homepage section with valid data
        section_payload = {
            "title": "Test Section",
            "description": "Description for test section",
            "content": "Detailed content for the section",
            "position": 1
        }
        response = requests.post(
            f"{BASE_URL}/api/homepage/sections",
            json=section_payload,
            headers=HEADERS,
            timeout=TIMEOUT
        )
        assert response.status_code == 201, f"Create homepage section failed: {response.text}"
        section = response.json()
        created_section_id = section.get('id')
        assert created_section_id is not None, "Created section ID missing"

        # 5. Retrieve homepage sections and verify ordering
        response = requests.get(
            f"{BASE_URL}/api/homepage/sections",
            headers=HEADERS,
            timeout=TIMEOUT
        )
        assert response.status_code == 200, f"Get homepage sections failed: {response.text}"
        sections = response.json()
        assert isinstance(sections, list), "Homepage sections response is not a list"
        assert any(s.get('id') == created_section_id for s in sections), "Created section not found in list"

        # 6. Test reordering homepage sections with invalid payload (e.g. missing IDs or wrong format)
        invalid_reorder_payload = {"invalid": "payload"}
        response = requests.patch(
            f"{BASE_URL}/api/homepage/sections/reorder",
            json=invalid_reorder_payload,
            headers=HEADERS,
            timeout=TIMEOUT
        )
        assert response.status_code == 422, "Expected 422 validation error for invalid homepage sections reorder payload"

    finally:
        # Cleanup created hero slide
        if created_slide_id is not None:
            try:
                requests.delete(
                    f"{BASE_URL}/api/homepage/hero-slides/{created_slide_id}",
                    headers=HEADERS,
                    timeout=TIMEOUT
                )
            except Exception:
                pass
        # Cleanup created homepage section
        if created_section_id is not None:
            try:
                requests.delete(
                    f"{BASE_URL}/api/homepage/sections/{created_section_id}",
                    headers=HEADERS,
                    timeout=TIMEOUT
                )
            except Exception:
                pass

test_homepage_sections_and_hero_slides_management()