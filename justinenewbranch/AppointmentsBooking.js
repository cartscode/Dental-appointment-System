document.addEventListener('DOMContentLoaded', () => {

    // ===== DATE LOGIC =====
    const today = new Date();
    const normalizedToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();
    let selectedDate = new Date(today.setHours(0, 0, 0, 0));

    // ===== DOM ELEMENTS =====
    const calendarGrid = document.getElementById('calendar-grid');
    const monthYearDisplay = document.getElementById('month-year-display');
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');
    const serviceItems = document.querySelectorAll('.service-item');

    // For form submission
    const appointmentForm = document.getElementById('appointment-form');
    const selectedServiceInput = document.getElementById('selected-service');
    const selectedDateInput = document.getElementById('selected-date');

    // For navigation
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');
    
    // Set initial date input value for today's date
    if (selectedDate) {
        selectedDateInput.value = selectedDate.toISOString().split('T')[0];
    }


    // ===== CALENDAR RENDERING =====
    function renderCalendar() {
        while (calendarGrid.children.length > 7) {
            calendarGrid.removeChild(calendarGrid.lastChild);
        }

        const date = new Date(currentYear, currentMonth);
        const monthName = date.toLocaleString('default', { month: 'long' }).toUpperCase();
        monthYearDisplay.textContent = `${monthName} ${currentYear}`;

        let firstDayIndex = date.getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        for (let i = 0; i < firstDayIndex; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.classList.add('day', 'empty');
            calendarGrid.appendChild(emptyDay);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.classList.add('day');
            dayElement.textContent = day;

            const paddedMonth = String(currentMonth + 1).padStart(2, '0');
            const paddedDay = String(day).padStart(2, '0');
            dayElement.dataset.date = `${currentYear}-${paddedMonth}-${paddedDay}`;

            const currentDate = new Date(currentYear, currentMonth, day);
            const isToday = currentDate.toDateString() === normalizedToday.toDateString();
            if (isToday) dayElement.classList.add('current-day');

            const isSelected = selectedDate && currentDate.toDateString() === selectedDate.toDateString();
            if (isSelected) dayElement.classList.add('active-day');

            if (currentDate < normalizedToday) {
                dayElement.classList.add('disabled-day');
                dayElement.style.cursor = 'default';
            } else {
                dayElement.addEventListener('click', () => {
                    document.querySelectorAll('.day').forEach(d => d.classList.remove('active-day'));
                    dayElement.classList.add('active-day');
                    selectedDate = currentDate;
                    // Update the hidden date input immediately on selection
                    selectedDateInput.value = dayElement.dataset.date;
                });
            }

            calendarGrid.appendChild(dayElement);
        }
    }

    // ===== MONTH NAVIGATION =====
    prevMonthBtn.addEventListener('click', () => {
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

    renderCalendar();

    // ===== SERVICE SELECTION =====
    serviceItems.forEach(item => {
        item.addEventListener('click', () => {
            serviceItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
            // Update the hidden service input immediately on selection
            selectedServiceInput.value = item.dataset.service; 
        });
    });

    // =======================================================
    // ===== FORM SUBMISSION (MODIFIED TO INCLUDE PROMPT) =====
    // =======================================================
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Stop the default form submission immediately

            const selectedServiceElement = document.querySelector('.service-item.active');
            const serviceValue = selectedServiceElement ? selectedServiceElement.dataset.service : '';

            const selectedDayElement = document.querySelector('.day.active-day:not(.disabled-day)');
            const dateValue = selectedDayElement ? selectedDayElement.dataset.date : '';

            const selectedTimeRadio = document.querySelector('input[name="time"]:checked');
            const timeValue = selectedTimeRadio ? selectedTimeRadio.value : '';

            // 1. Validation Check
            if (!serviceValue || !dateValue || !timeValue) {
                alert('🚨 Please ensure you have selected a Service, a Date, and a Time before submitting.');
                return;
            }

            // Ensure hidden fields are updated (though they should be via click handlers)
            selectedServiceInput.value = serviceValue;
            selectedDateInput.value = dateValue;

            // 2. Terms and Conditions Prompt
            const termsAndConditions = `
                🩺 Dental+ Appointment Terms and Conditions:

                1. Arrival Time: Please arrive 15 minutes before your scheduled appointment.
                2. Cancellation Policy: Notify us at least 24 hours in advance for cancellations.
                3. No-Show Fee: A fee may apply for missed appointments without proper notice.

                Do you agree to these Terms and Conditions?
                (Click 'OK' to submit your appointment, or 'Cancel' to stop.)
            `;

            if (confirm(termsAndConditions)) {
                // 3. User clicked 'OK' (Yes) - Proceed with submission
                console.log("Submitting form with data:", {
                    service: selectedServiceInput.value,
                    date: selectedDateInput.value,
                    time: timeValue
                });
                
                // Programmatically submit the form
                appointmentForm.submit(); 
            } else {
                // 4. User clicked 'Cancel' (No) - Submission is cancelled
                alert('Appointment booking cancelled. Submission halted.');
            }
        });
    }

    // ===== HAMBURGER MENU TOGGLE (FIXED) =====
    if (menuToggle && menu) {
        menuToggle.addEventListener('click', () => {
            menu.classList.toggle('active');
        });
    }

});

// Force reload on back navigation
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});