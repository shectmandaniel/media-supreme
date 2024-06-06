// Wait until the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function () {
  // Get references to the form and other elements on the page
  const form = document.getElementById('leadForm');
  const message = document.getElementById('message');
  const thankYouModal = document.getElementById('thankYouModal');
  const thankYouMessage = document.getElementById('thankYouMessage');
  const closeButton = document.querySelector('.close-button');

  // Add an event listener to the form's submit event
  form.addEventListener('submit', function (e) {
    // Prevent the default form submission behavior
    e.preventDefault();

    // Clear previous messages
    message.textContent = '';
    document
      .querySelectorAll('.error-message')
      .forEach((el) => (el.textContent = ''));

    // Validate fields
    const firstName = form.first_name.value.trim();
    const lastName = form.last_name.value.trim();
    const email = form.email.value.trim();
    const phoneNumber = form.phone_number.value.trim();

    let valid = true;

    // Check if the first name is provided
    if (!firstName) {
      document.getElementById('error-first_name').textContent =
        'First name is required.';
      valid = false;
    }

    // Check if the last name is provided
    if (!lastName) {
      document.getElementById('error-last_name').textContent =
        'Last name is required.';
      valid = false;
    }

    // Check if the email is provided and valid
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!email) {
      document.getElementById('error-email').textContent = 'Email is required.';
      valid = false;
    } else if (!emailPattern.test(email)) {
      document.getElementById('error-email').textContent =
        'Please enter a valid email address.';
      valid = false;
    }

    // Check if the phone number is provided and valid
    const phonePattern = /^[0-9]{10,15}$/;
    if (!phoneNumber) {
      document.getElementById('error-phone_number').textContent =
        'Phone number is required.';
      valid = false;
    } else if (!phonePattern.test(phoneNumber)) {
      document.getElementById('error-phone_number').textContent =
        'Please enter a valid phone number.';
      valid = false;
    }

    // If the form is not valid, stop the form submission
    if (!valid) {
      return;
    }

    // Prepare form data
    const formData = new FormData(form);

    // Add current URL to form data
    const fullUrl = new URL(window.location.href);
    formData.append('url', fullUrl.href);

    // Submit form data
    fetch('leads.php', {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        // If there are errors, display them
        if (data.status === 'error') {
          if (data.errors) {
            for (const [key, value] of Object.entries(data.errors)) {
              document.getElementById(`error-${key}`).textContent = value;
            }
          } else {
            message.textContent = data.message;
          }
        } else {
          // If there are no errors, display the thank you message and show the thank you modal
          thankYouMessage.textContent = data.message;
          thankYouModal.style.display = 'block';
        }
      });
  });

  // Add an event listener to the close button of the thank you modal
  // When the button is clicked, hide the modal
  closeButton.addEventListener('click', function () {
    thankYouModal.style.display = 'none';
  });
});