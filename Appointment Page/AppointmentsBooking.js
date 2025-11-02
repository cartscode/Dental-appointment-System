document.addEventListener('DOMContentLoaded', () => {

    // Helper to get today's date normalized to midnight for accurate comparisons
    const today = new Date();
    const normalizedToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());
    
    let currentMonth = today.getMonth(); // 0 (Jan) - 11 (Dec)
    let currentYear = today.getFullYear();
    let selectedDate = new Date(today.setHours(0, 0, 0, 0)); // Initialize selection to today

    // DOM Elements
    const calendarGrid = document.getElementById('calendar-grid');
    const monthYearDisplay = document.getElementById('month-year-display');
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');
    const serviceItems = document.querySelectorAll('.service-item');
    
    // ⭐ NEW/CORRECTED DOM elements for submission
    const appointmentForm = document.getElementById('appointment-form'); // Target the whole form
    const selectedServiceInput = document.getElementById('selected-service');
    const selectedDateInput = document.getElementById('selected-date');
    
    const menuToggle = document.getElementById('menu-toggle');
    const navLinks = document.getElementById('nav-links');
    const navRight = document.querySelector('.nav-right'); 


    // 1. DYNAMIC CALENDAR & DATE SELECTION LOGIC

    function renderCalendar() {
        // Clear previous dates, but keep the day names (the first 7 children)
        while (calendarGrid.children.length > 7) {
            calendarGrid.removeChild(calendarGrid.lastChild);
        }

        const date = new Date(currentYear, currentMonth);
        const monthName = date.toLocaleString('default', { month: 'long' }).toUpperCase();
        monthYearDisplay.textContent = `${monthName} ${currentYear}`;

        // Get the day of the week for the 1st of the month (0=Sun, 6=Sat)
        let firstDayIndex = date.getDay(); 
        
        // Get number of days in the month
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        // 1. Add empty leading days
        for (let i = 0; i < firstDayIndex; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.classList.add('day', 'empty');
            calendarGrid.appendChild(emptyDay);
        }

        // 2. Add actual days
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.classList.add('day');
            dayElement.textContent = day;
            
            // Format the date as YYYY-MM-DD for MySQL DATE type (CRITICAL for PHP)
            const paddedMonth = String(currentMonth + 1).padStart(2, '0');
            const paddedDay = String(day).padStart(2, '0');
            dayElement.dataset.date = `${currentYear}-${paddedMonth}-${paddedDay}`;

            const currentDate = new Date(currentYear, currentMonth, day);
            
            // Check if it's today
            const isToday = currentDate.toDateString() === normalizedToday.toDateString();
            if (isToday) {
                dayElement.classList.add('current-day');
            }

            // Check if it's the selected day
            const isSelected = selectedDate && currentDate.toDateString() === selectedDate.toDateString();
            if (isSelected) {
                 dayElement.classList.add('active-day');
            }

            // Disable dates strictly older than today
            if (currentDate < normalizedToday) {
                dayElement.classList.add('disabled-day'); 
                dayElement.style.cursor = 'default';
            } else {
                // Attach click listener only to current and future dates
                dayElement.addEventListener('click', () => {
                    // Remove 'active-day' from all days first
                    document.querySelectorAll('.day').forEach(d => d.classList.remove('active-day'));
                    // Add 'active-day' to the clicked day
                    dayElement.classList.add('active-day');
                    // Store the newly selected date object
                    selectedDate = currentDate; 
                });
            }
            
            calendarGrid.appendChild(dayElement);
        }
    }

    // Handlers for month navigation
    prevMonthBtn.addEventListener('click', () => {
        // Prevent going back to a past month from the current month/year
        const oneMonthAgo = new Date(currentYear, currentMonth - 1);
        if (oneMonthAgo < new Date(today.getFullYear(), today.getMonth())) {
             alert("Cannot navigate to a past month for booking.");
             return;
        }

        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    });

    // Initialize the calendar on load
    renderCalendar();


    // 2. SERVICE SELECTION LOGIC

    serviceItems.forEach(item => {
        item.addEventListener('click', () => {
            // Remove 'active' class from all service items
            serviceItems.forEach(i => i.classList.remove('active'));
            // Add 'active' class to the clicked item
            item.classList.add('active');
        });
    });


    // 3. ⭐ CRITICAL FIX: FORM SUBMISSION LOGIC ⭐
    
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', (e) => {
            
            // 1. Get the values from the active elements
            const selectedServiceElement = document.querySelector('.service-item.active');
            // We use .dataset.service because this gives the simple key (e.g., 'checkup')
            const serviceValue = selectedServiceElement ? selectedServiceElement.dataset.service : '';

            const selectedDayElement = document.querySelector('.day.active-day:not(.disabled-day)');
            // We use .dataset.date because this is formatted as YYYY-MM-DD (e.g., '2025-10-31')
            const dateValue = selectedDayElement ? selectedDayElement.dataset.date : '';

            // The time is sent automatically by the radio button, but we check if one is selected.
            const selectedTimeRadio = document.querySelector('input[name="time"]:checked');
            const timeValue = selectedTimeRadio ? selectedTimeRadio.value : ''; // e.g., '08:00:00'

            // 2. Validation Check
            if (!serviceValue || !dateValue || !timeValue) {
                e.preventDefault(); // Stop the form from submitting
                alert('Please ensure you have selected a Service, a Date, and a Time.'); 
                return;
            }

            // 3. ⭐ Inject values into the hidden input fields for form submission ⭐
            // This is the step that was missing or incorrect previously!
            selectedServiceInput.value = serviceValue;
            selectedDateInput.value = dateValue;

            // Note: The time value (timeValue) is sent via the radio button input 
            // named "time", so the hidden input for time is not strictly needed.
            
            console.log("Submitting form with data:", { service: selectedServiceInput.value, date: selectedDateInput.value, time: timeValue });

            // If we reach this point, the hidden inputs are populated, and the form will submit successfully
            // to submit_appointment.php with all required data.

        });
    }

    // 4. HAMBURGER MENU TOGGLE

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            if (navRight) {
                navRight.classList.toggle('active');
            }
        });
    }

});