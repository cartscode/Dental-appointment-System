document.addEventListener('DOMContentLoaded', () => {
    const realtimeClock = document.getElementById('realtime-clock');
    const dateDisplay = document.getElementById('date-display');
    const todayCount = document.getElementById('today-count');
    const navItems = document.querySelectorAll('.nav-item');
    const contentViews = document.querySelectorAll('.content-view');

    // --- Clock and Date Logic ---
    function updateClockAndDate() {
        const now = new Date();
        
        // Time
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        realtimeClock.textContent = timeString;

        // Date
        const dateString = now.toLocaleDateString('en-US', {
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric'
        });
        dateDisplay.textContent = dateString;
    }

    updateClockAndDate();
    setInterval(updateClockAndDate, 1000);

    // --- Dynamic Content Switching Logic ---

    // Find the initially active nav item and content view
    const initialActiveNav = document.querySelector('.nav-item.active');
    const initialActiveView = initialActiveNav ? document.getElementById(initialActiveNav.getAttribute('data-view')) : null;

    // Set initial view state based on HTML classes (important for page load)
    contentViews.forEach(view => view.classList.remove('active-view'));
    if (initialActiveView) {
        initialActiveView.classList.add('active-view');
    } else {
        // Fallback: If no initial active class, set the first content view as active
        if (contentViews.length > 0) {
            contentViews[0].classList.add('active-view');
            navItems[0].classList.add('active');
        }
    }


    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault(); 

            // Deactivate all nav items and content views
            navItems.forEach(nav => nav.classList.remove('active'));
            contentViews.forEach(view => view.classList.remove('active-view'));
            
            // Activate the clicked nav item
            item.classList.add('active');

            // Find and activate the corresponding content view
            const targetViewId = item.getAttribute('data-view');
            const targetView = document.getElementById(targetViewId);
            if (targetView) {
                targetView.classList.add('active-view');
            }
        });
    });

    // --- Table Logic ---
    // Example for 'Schedule for Today' count
    const scheduleTodayTableBody = document.querySelector('#schedule-today tbody');
    if (scheduleTodayTableBody) {
        const rowCount = scheduleTodayTableBody.children.length;
        todayCount.textContent = rowCount;
    }

    // Action button handlers (for logging/future API calls)
    document.querySelectorAll('.done-btn, .missed-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const action = e.currentTarget.getAttribute('data-action');
            const row = e.currentTarget.closest('tr');
            const patientName = row.children[0].textContent; 
            console.log(`Action: ${action} triggered for patient: ${patientName}`);

            // Example of updating status in the UI (for 'Schedule for Today')
            const statusCell = row.querySelector('td:nth-child(5)'); // 5th cell is status
            if (statusCell) {
                statusCell.classList.remove('status-pending', 'status-done', 'status-missed');
                if (action === 'done') {
                    statusCell.textContent = 'Done';
                    statusCell.classList.add('status-done');
                } else if (action === 'missed') {
                    statusCell.textContent = 'Missed';
                    statusCell.classList.add('status-missed'); // Requires new CSS class
                }
                // Optional: Disable action buttons after click
                row.querySelectorAll('.action-btn').forEach(aBtn => aBtn.disabled = true);
            }
        });
    });
});