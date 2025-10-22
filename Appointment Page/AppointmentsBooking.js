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
    const submitBtn = document.getElementById('submit-btn');
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
            dayElement.dataset.date = `${currentYear}-${currentMonth + 1}-${day}`;

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
                    // Store the newly selected date
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


    // 3. SUBMIT BUTTON LOGIC (ALERT REMOVED)

    if (submitBtn) {
        submitBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default form submission if it were a form

            // Get selected service
            const selectedServiceElement = document.querySelector('.service-item.active');
            const selectedService = selectedServiceElement ? selectedServiceElement.querySelector('.service-name').textContent : null;

            // Get selected date
            const selectedDateElement = document.querySelector('.day.active-day');
            const dateText = selectedDateElement ? selectedDateElement.textContent : null;
            const monthYearText = monthYearDisplay.textContent;
            const finalDate = selectedDateElement ? `${dateText} ${monthYearText}` : null;

            // Get selected time
            const selectedTimeElement = document.querySelector('.time-slots input[name="time"]:checked');
            const selectedTime = selectedTimeElement ? selectedTimeElement.parentElement.textContent.trim() : null;

            // Validation Check
            if (!selectedService || !finalDate || !selectedTime) {
                 alert('Please select a Service, Date, and Time before submitting.'); 
                 return;
            }

            // Log data (where you would send it to a server)
            console.log("--- Appointment Submission Data ---");
            console.log(`Service: ${selectedService}`);
            console.log(`Date: ${finalDate}`);
            console.log(`Time: ${selectedTime}`);
            console.log("-----------------------------------");
            
            // Implement your actual booking confirmation logic below (e.g., fetch, redirect, or inline message) ⭐
            
            // Example of a simple placeholder for success:
            // submitBtn.textContent = 'BOOKING SUCCESSFUL!';
            // submitBtn.disabled = true;

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
