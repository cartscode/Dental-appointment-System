
//  For hamburger nav bar
 const toggle = document.getElementById('menu-toggle');
  const navLinks = document.getElementById('nav-links');
  const navRight = document.querySelector('.nav-right');

  toggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    navRight.classList.toggle('active');
  });

document.addEventListener('DOMContentLoaded', () => {
    // 1. Service Selection Logic
    const serviceItems = document.querySelectorAll('.service-item');
    serviceItems.forEach(item => {
        item.addEventListener('click', () => {
            // Remove 'active' class from all service items
            serviceItems.forEach(i => i.classList.remove('active'));
            // Add 'active' class to the clicked item
            item.classList.add('active');
        });
    });

    // 2. Date Selection Logic
    const calendarDays = document.querySelectorAll('.calendar-grid .day:not(.empty)');
    calendarDays.forEach(day => {
        day.addEventListener('click', () => {
            // Remove 'active-day' from all days
            calendarDays.forEach(d => d.classList.remove('active-day'));
            // Add 'active-day' to the clicked day
            day.classList.add('active-day');
        });
    });

    // 3. Hamburger Menu Toggle (Based on your existing HTML/CSS structure)
    const menuToggle = document.getElementById('menu-toggle');
    const navLinks = document.getElementById('nav-links');
    const navRight = document.querySelector('.nav-right'); // Assuming you want to toggle nav-right too

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            if (navRight) {
                navRight.classList.toggle('active');
            }
        });
    }

    // 4. Submit Button Placeholder
    const submitBtn = document.getElementById('submit-btn');
    if (submitBtn) {
        submitBtn.addEventListener('click', () => {
            // Get selected service
            const selectedServiceElement = document.querySelector('.service-item.active');
            const selectedService = selectedServiceElement ? selectedServiceElement.querySelector('.service-name').textContent : 'None';

            // Get selected date
            const selectedDateElement = document.querySelector('.day.active-day');
            const selectedDate = selectedDateElement ? selectedDateElement.textContent + ' October 2025' : 'None';

            // Get selected time
            const selectedTimeElement = document.querySelector('.time-slots input[name="time"]:checked');
            const selectedTime = selectedTimeElement ? selectedTimeElement.parentElement.textContent.trim() : 'None';

            alert(`Appointment Booked (Simulated):\nService: ${selectedService}\nDate: ${selectedDate}\nTime: ${selectedTime}`);
            // You would typically send this data to a server here.
        });
    }
});