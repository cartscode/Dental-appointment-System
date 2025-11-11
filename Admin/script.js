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
// Function to handle status update
function handleStatusUpdate(button) {
    const appointmentId = button.getAttribute('data-id');
    const newStatus = button.getAttribute('data-action'); // 'missed' or 'done'
    const statusText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1); // 'Missed' or 'Done'
    const row = button.closest('tr'); // Get the row element

    if (!confirm(`Are you sure you want to set this appointment status to "${statusText}"?`)) {
        return; // Stop if user cancels
    }

    // Send the AJAX request
    fetch('update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `appointment_id=${appointmentId}&new_status=${statusText}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Status updated successfully to ${statusText}.`);
            
            // OPTIONAL: Visually remove the row and update the schedule count
            if (row) {
                row.style.opacity = '0.5'; // Fade out
                // Simple reload is best to reflect all changes, including the count
                window.location.reload(); 
            }
        } else {
            alert('Error updating status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert('An unexpected error occurred.');
    });
}

document.addEventListener('DOMContentLoaded', () => {

    // Function to handle the actual AJAX update
    const handleStatusUpdate = (appointmentId, newStatus, rowElement) => {
        const statusText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
        const containerId = rowElement.closest('.content-view').id;

        if (!confirm(`Are you sure you want to change this appointment status to "${statusText}"?`)) {
            return; // Stop if user cancels
        }

        // Send the AJAX request to the PHP script
        fetch('update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `appointment_id=${appointmentId}&new_status=${statusText}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // SUCCESS: Perform the smooth visual removal

                if (newStatus === 'Missed' || newStatus === 'Done') {
                    // 1. Visually fade out and remove the row from the Schedule for Today table
                    if (containerId === 'schedule-today') {
                        rowElement.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                        rowElement.style.opacity = '0';
                        rowElement.style.transform = 'translateX(-100%)';
                        
                        // Wait for the transition to finish, then remove the element
                        setTimeout(() => {
                            rowElement.remove();
                            
                            // 2. Update the counter without reloading the page
                            const todayCountSpan = document.getElementById('today-count');
                            if (todayCountSpan) {
                                let currentCount = parseInt(todayCountSpan.textContent);
                                todayCountSpan.textContent = Math.max(0, currentCount - 1);
                            }
                            
                            // 3. If the status is 'Missed', increment the Missed Appointments count
                            if (newStatus === 'Missed') {
                                const missedCountSpan = document.getElementById('missed-count-sidebar');
                                if (missedCountSpan) {
                                    let currentMissedCount = parseInt(missedCountSpan.textContent);
                                    missedCountSpan.textContent = currentMissedCount + 1;
                                }
                            }

                        }, 500); // 500ms matches the CSS transition time
                    } 
                    // Note: When Reschedule (Pending) is clicked on the Missed list, 
                    // a full reload might still be best as the row is moving to a different list (Schedule for Today).
                } 
                
                // For 'Reschedule' (Pending) action on the Missed List, a full reload is still recommended 
                // to correctly populate the 'Schedule for Today' list.
                if (newStatus === 'Pending') {
                    window.location.reload(); 
                }

            } else {
                alert('Error updating status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('An unexpected error occurred during the update.');
        });
    };

    // Attach event listeners to all action buttons using event delegation
    document.addEventListener('click', (event) => {
        const button = event.target.closest('.action-btn');
        if (button) {
            const action = button.getAttribute('data-action');
            const appointmentId = button.getAttribute('data-id');
            const row = button.closest('tr');

            // The action must be one of the expected states, and an ID must exist
            if (appointmentId && (action === 'done' || action === 'missed' || action === 'pending')) {
                // Determine the new status string (Done, Missed, or Pending)
                const newStatus = (action === 'pending') ? 'Pending' : action.charAt(0).toUpperCase() + action.slice(1);
                
                handleStatusUpdate(appointmentId, newStatus, row);
            }
        }
    });

    // NOTE: This assumes your current CSS file has transition styles for a smooth fade out.
});