document.addEventListener('DOMContentLoaded', () => {

    // ================= CLOCK & DATE =================
    const realtimeClock = document.getElementById('realtime-clock');
    const dateDisplay = document.getElementById('date-display');

    function updateClockAndDate() {
        const now = new Date();

        realtimeClock.textContent = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });

        dateDisplay.textContent = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    updateClockAndDate();
    setInterval(updateClockAndDate, 1000);


    // ================= NAVIGATION =================
    const navItems = document.querySelectorAll('.nav-item');
    const contentViews = document.querySelectorAll('.content-view');

    function showView(id) {
        contentViews.forEach(v => v.classList.remove('active-view'));
        document.getElementById(id).classList.add('active-view');
    }

    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            navItems.forEach(n => n.classList.remove('active'));

            item.classList.add('active');
            showView(item.getAttribute('data-view'));
        });
    });


    // ================= STATUS UPDATE HANDLER =================
    function handleStatusUpdate(appointmentId, newStatus, rowElement) {
        const statusText = newStatus; // Already capitalized correctly
        const containerId = rowElement.closest('.content-view').id;

        if (!confirm(`Confirm change status to "${statusText}"?`)) return;

        fetch('update_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `appointment_id=${appointmentId}&new_status=${statusText}`
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert("Error: " + data.message);
                return;
            }

            // ========== If Done or Missed from Today ==========
            if (containerId === 'schedule-today' && (newStatus === 'Done' || newStatus === 'Missed')) {
                rowElement.style.transition = 'opacity 0.5s, transform 0.5s';
                rowElement.style.opacity = '0';
                rowElement.style.transform = 'translateX(-100%)';

                setTimeout(() => {
                    rowElement.remove();

                    let todayCount = document.getElementById('today-count');
                    todayCount.textContent = Math.max(0, parseInt(todayCount.textContent) - 1);

                    if (newStatus === 'Missed') {
                        let missedCount = document.getElementById('missed-count-sidebar');
                        missedCount.textContent = parseInt(missedCount.textContent) + 1;
                    }

                }, 500);

                return;
            }

            // ========== If Pending (Reschedule) ==========
            if (newStatus === 'Pending') {
                window.location.reload();
                return;
            }

        })
        .catch(err => {
            console.error(err);
            alert("Unexpected error.");
        });
    }


    // ================= ACTION BUTTON HANDLER (GLOBAL) =================
    document.addEventListener('click', (event) => {
        const btn = event.target.closest('.action-btn');
        if (!btn) return;

        const appointmentId = btn.getAttribute('data-id');
        const action = btn.getAttribute('data-action');
        const row = btn.closest('tr');

        if (!appointmentId || !row) return;

        let status = "";
        if (action === 'done') status = 'Done';
        if (action === 'missed') status = 'Missed';
        if (action === 'pending') status = 'Pending';

        handleStatusUpdate(appointmentId, status, row);
    });


    // ================= INITIAL COUNT =================
    const todayTableBody = document.querySelector('#schedule-today tbody');
    const todayCount = document.getElementById('today-count');

    if (todayTableBody && todayCount) {
        todayCount.textContent = todayTableBody.children.length;
    }

});

document.addEventListener("DOMContentLoaded", function() {
    // Select all search inputs using the common class 'table-search'
    const searchInputs = document.querySelectorAll('.table-search');

    // Attach the search function to each input found
    searchInputs.forEach(input => {
        // Determine the target table based on the input's ID
        let targetTableId;
        if (input.id === 'searchAppointments') {
            targetTableId = 'appointmentsTable';
        } else if (input.id === 'searchMissed') {
            targetTableId = 'missedTable';
        } else if (input.id === 'searchUsers') {
            targetTableId = 'usersTable';
        } else if (input.id === 'searchMessages') {
            targetTableId = 'messagesTable';
        }

        const targetTable = document.getElementById(targetTableId);
        
        // Only proceed if the table was successfully found
        if (targetTable) {
            input.addEventListener('keyup', function() {
                filterTable(this.value.toLowerCase(), targetTable);
            });
        }
    });
});

/**
 * Filters the given HTML table based on a search term.
 * @param {string} filter - The text to search for (already lowercased).
 * @param {HTMLElement} table - The <table> element to filter.
 */
function filterTable(filter, table) {
    // Get the rows from the table body
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    // Loop through all table rows
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        
        // Get the text content of the entire row for searching
        const rowText = row.textContent.toLowerCase();
        
        // Show or hide the row based on the filter
        if (rowText.includes(filter)) {
            row.style.display = ""; // Show the row
        } else {
            row.style.display = "none"; // Hide the row
        }
    }
}
document.querySelectorAll('#user-accounts .action-btn').forEach(button => {
    button.addEventListener('click', function() {
        const row = this.closest('tr');
        const statusCell = row.querySelector('.status-cell');

        // Toggle status in front-end only
        if (statusCell.textContent.trim() === 'Active') {
            statusCell.textContent = 'Inactive';
            this.textContent = 'Set Active';
            this.classList.remove('inactive-btn');
            this.classList.add('active-btn');
        } else {
            statusCell.textContent = 'Active';
            this.textContent = 'Set Inactive';
            this.classList.remove('active-btn');
            this.classList.add('inactive-btn');
        }
    });
});
