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
        });
    });

    // ===== FORM SUBMISSION =====
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', (e) => {
            const selectedServiceElement = document.querySelector('.service-item.active');
            const serviceValue = selectedServiceElement ? selectedServiceElement.dataset.service : '';

            const selectedDayElement = document.querySelector('.day.active-day:not(.disabled-day)');
            const dateValue = selectedDayElement ? selectedDayElement.dataset.date : '';

            const selectedTimeRadio = document.querySelector('input[name="time"]:checked');
            const timeValue = selectedTimeRadio ? selectedTimeRadio.value : '';

            if (!serviceValue || !dateValue || !timeValue) {
                e.preventDefault();
                alert('Please ensure you have selected a Service, a Date, and a Time.');
                return;
            }

            selectedServiceInput.value = serviceValue;
            selectedDateInput.value = dateValue;

            console.log("Submitting form with data:", {
                service: selectedServiceInput.value,
                date: selectedDateInput.value,
                time: timeValue
            });
        });
    }

    // ===== HAMBURGER MENU TOGGLE (FIXED) =====
    if (menuToggle && menu) {
        menuToggle.addEventListener('click', () => {
            menu.classList.toggle('active');
        });
    }

});
