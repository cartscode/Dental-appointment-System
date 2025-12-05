document.addEventListener('DOMContentLoaded', () => {

    // =========================
    // LOCAL STORAGE DATA
    // =========================
    function getAppointments() {
        return JSON.parse(localStorage.getItem('appointments')) || {};
    }

    function saveAppointment(date, service, time) {
        const data = getAppointments();
        if (!data[date]) data[date] = [];
        data[date].push({ service, time });
        localStorage.setItem('appointments', JSON.stringify(data));
    }

    function countAppointments(date) {
        const data = getAppointments();
        return data[date] ? data[date].length : 0;
    }

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

    const appointmentForm = document.getElementById('appointment-form');
    const selectedServiceInput = document.getElementById('selected-service');
    const selectedDateInput = document.getElementById('selected-date');

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

        const paddedMonth = String(currentMonth + 1).padStart(2, '0');
        const paddedDay = String(day).padStart(2, '0');
        const dateKey = `${currentYear}-${paddedMonth}-${paddedDay}`;

        const currentDate = new Date(currentYear, currentMonth, day);

        // Get slot count
        const slotCount = countAppointments(dateKey);

        // ======================
        // INSERT NUMBER + SLOT LABEL
        // ======================
        dayElement.innerHTML = `
            <div style="font-weight:600">${day}</div>
            <div style="font-size:0.70rem; margin-top:2px;">
                ${slotCount >= 10 ? "<span style='color:red;'>FULL</span>" : `${slotCount}/10`}
            </div>
        `;

        dayElement.dataset.date = dateKey;

        // Disable past days
        if (currentDate < normalizedToday) {
            dayElement.classList.add('disabled-day');
            calendarGrid.appendChild(dayElement);
            continue;
        }

        // Fully booked days
        if (slotCount >= 10) {
            dayElement.classList.add('disabled-day');
        }

        // Active day selection
        if (selectedDate && currentDate.toDateString() === selectedDate.toDateString()) {
            dayElement.classList.add('active-day');
        }

        dayElement.addEventListener('click', () => {
            if (slotCount >= 10) {
                alert("This day is FULL (10/10). Please choose another date.");
                return;
            }

            document.querySelectorAll('.day').forEach(d => d.classList.remove('active-day'));
            dayElement.classList.add('active-day');

            selectedDate = currentDate;
            selectedDateInput.value = dateKey;
        });

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

    // ===== SERVICE SELECT =====
    serviceItems.forEach(item => {
        item.addEventListener('click', () => {
            serviceItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
            selectedServiceInput.value = item.dataset.service;
        });
    });

    // ===== FORM SUBMISSION =====
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const selectedServiceElement = document.querySelector('.service-item.active');
            const selectedDayElement = document.querySelector('.day.active-day:not(.disabled-day)');
            const selectedTimeRadio = document.querySelector('input[name="time"]:checked');

            const serviceValue = selectedServiceElement ? selectedServiceElement.dataset.service : '';
            const dateValue = selectedDayElement ? selectedDayElement.dataset.date : '';
            const timeValue = selectedTimeRadio ? selectedTimeRadio.value : '';

            if (!serviceValue || !dateValue || !timeValue) {
                alert('Please select a Service, Date, and Time.');
                return;
            }

            // Prevent submission if fully booked
            if (countAppointments(dateValue) >= 10) {
                alert("This day already reached the 10-patient limit. Please choose another date.");
                renderCalendar();
                return;
            }

            // Save to LocalStorage
            saveAppointment(dateValue, serviceValue, timeValue);

            alert("Appointment successfully booked!");
            appointmentForm.submit();
        });
    }
});

// === MENU TOGGLE ===
const toggle = document.getElementById('menu-toggle');
const navLinks = document.getElementById('nav-links');
const navRight = document.querySelector('.nav-right');

toggle.addEventListener('click', () => {
  navLinks.classList.toggle('active');
  navRight.classList.toggle('active');
});