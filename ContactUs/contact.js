  const toggle = document.getElementById('menu-toggle');
  const navLinks = document.getElementById('nav-links');
  const navRight = document.querySelector('.nav-right');

  toggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    navRight.classList.toggle('active');
  });

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const messageContainer = document.getElementById('messageContainer');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Stop the standard form submission

            // 1. Collect form data
            const formData = new FormData(form);

            // 2. Submit data via AJAX to your PHP processor
            fetch('contact_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // We expect JSON back from the PHP file
            .then(data => {
                if (data.success) {
                    // 3. Display the clean success message
                    messageContainer.innerHTML = `
                        <div class="success-message-container">
                            <p><i class="fas fa-check-circle"></i> ${data.message}</p>
                        </div>
                    `;
                    form.reset(); // Clear the form fields
                } else {
                    // Display error message
                    messageContainer.innerHTML = `
                        <div class="error-message-container">
                            <p><i class="fas fa-times-circle"></i> ${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageContainer.innerHTML = `
                    <div class="error-message-container">
                        <p><i class="fas fa-times-circle"></i> A network error occurred. Please try again.</p>
                    </div>
                `;
            });
        });
    }
});