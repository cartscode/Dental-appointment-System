<?php
session_start();

require_once 'db_connect.php';

$today_date = date('Y-m-d');

$sql_today = "
    SELECT id, first_name, last_name, email, service_name, appointment_date, appointment_time, status 
    FROM appointments 
    WHERE appointment_date = ?
      AND status = 'Pending'
    ORDER BY appointment_time ASC
";
$stmt_today = mysqli_prepare($conn, $sql_today);
mysqli_stmt_bind_param($stmt_today, "s", $today_date);
mysqli_stmt_execute($stmt_today);
$result_today = mysqli_stmt_get_result($stmt_today);
$today_count = mysqli_num_rows($result_today);

$sql_all_appointments = "
    SELECT id, first_name, last_name, email, service_name, appointment_date, status 
    FROM appointments 
    ORDER BY appointment_date DESC, appointment_time DESC
";
$result_all_appointments = mysqli_query($conn, $sql_all_appointments);

$sql_users = "
    SELECT id, first_name, last_name, email, status 
    FROM users 
    ORDER BY id DESC
";
$result_users = mysqli_query($conn, $sql_users);

$sql_missed = "
    SELECT id, first_name, last_name, email, service_name, appointment_date, appointment_time
    FROM appointments 
    WHERE status = 'Missed' 
    ORDER BY appointment_date DESC, appointment_time DESC
";
$result_missed = mysqli_query($conn, $sql_missed);
$missed_count = mysqli_num_rows($result_missed);

$sql_messages = "SELECT * FROM contacts ORDER BY submission_date DESC";
$result_messages = mysqli_query($conn, $sql_messages);

$sql_email_sent = "SELECT * FROM email_sent_records ORDER BY sent_at DESC";
$result_email_sent = mysqli_query($conn, $sql_email_sent);
$email_sent_count = mysqli_num_rows($result_email_sent);

$sql_cancelled = "
    SELECT id, first_name, last_name, email, service_name, appointment_date, appointment_time
    FROM appointments 
    WHERE status = 'Cancelled' 
    ORDER BY appointment_date DESC, appointment_time DESC
";
$result_cancelled = mysqli_query($conn, $sql_cancelled);
$cancelled_count = mysqli_num_rows($result_cancelled);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dental Plus</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</head>
<body>
    <div class="header">
        <div class="header-left">
            <span id="realtime-clock"></span>
            <span id="date-display" class="date-display"></span>
        </div>
        <div class="header-right">
            <span class="hi-admin">Hi Admin</span>
            <button class="icon-btn" title="Home"><i class="fa-solid fa-house"></i></button>
            <button class="icon-btn" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></button>
        </div>
    </div>

    <div class="main-container">
        <aside class="sidebar">
            <nav>
                <a href="?view=user-accounts" class="nav-item" data-view="user-accounts"><i class="fa-solid fa-users"></i> User Accounts <span class="arrow">&rarr;</span></a>
                <a href="?view=schedule-today" class="nav-item active" data-view="schedule-today"><i class="fa-solid fa-calendar-day"></i> Schedule for Today <span class="arrow">&rarr;</span></a> 
                <a href="?view=patients-schedule" class="nav-item" data-view="patients-schedule"><i class="fa-solid fa-calendar"></i> Patients Schedule <span class="arrow">&rarr;</span></a>
                <a href="?view=missed-appointments" class="nav-item" data-view="missed-appointments"><i class="fa-solid fa-calendar-times"></i> Missed Appointments (<span id="missed-count-sidebar"><?php echo $missed_count; ?></span>) <span class="arrow">&rarr;</span></a>
                <a href="?view=cancelled-appointments" class="nav-item" data-view="cancelled-appointments"><i class="fa-solid fa-ban"></i> Cancelled Appointments (<span><?php echo $cancelled_count; ?></span>) <span class="arrow">&rarr;</span></a>
                <a href="?view=patients-message" class="nav-item" data-view="patients-message"><i class="fa-solid fa-message"></i> Patient's Message <span class="arrow">&rarr;</span></a>
                <a href="?view=email-sent-records" class="nav-item" data-view="email-sent-records"><i class="fa-solid fa-envelope"></i> Email Sent Records <span class="arrow">&rarr;</span></a>
            </nav>
        </aside>

        <main class="content">

            <div id="schedule-today" class="content-view active-view"> 
                <h2><i class="fa-solid fa-calendar-day"></i> Schedule for Today (<span id="today-count"><?php echo $today_count; ?></span>)</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Service Name</th>
                            <th>Appointment Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_today)): ?>
                        <tr data-appointment-id="<?php echo $row['id']; ?>">
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['service_name']); ?></td> 
                            <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                            <td class="status-<?php echo strtolower($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <button class="action-btn done-btn" data-action="done" data-id="<?php echo $row['id']; ?>"><i class="fa-solid fa-check"></i> Done</button>
                                <button class="action-btn missed-btn" data-action="missed" data-id="<?php echo $row['id']; ?>"><i class="fa-solid fa-xmark"></i> Missed</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div id="patients-schedule" class="content-view">
                <h2><i class="fa-solid fa-calendar"></i> Patients Schedule</h2>
                <div class="table-controls">
                    <input type="text" id="searchAppointments" class="search-input table-search" placeholder="Search by Name/Email/Service...">
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Service Name</th>
                            <th>Appointment Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_all_appointments)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                            <td><?php echo date('m/d/Y', strtotime($row['appointment_date'])); ?></td>
                            <td class="status-<?php echo strtolower($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div id="missed-appointments" class="content-view">
                <h2><i class="fa-solid fa-calendar-times"></i> Missed Appointments List (<span id="missed-count-display"><?php echo $missed_count; ?></span>)</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Service Name</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_missed)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                            <td><?php echo date('m/d/Y', strtotime($row['appointment_date'])); ?></td>
                            <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                            <td>
                                <a href="delete_appointment.php?id=<?php echo $row['id']; ?>" 
                                   class="action-btn delete-btn"
                                   onclick="return confirm('Delete this missed appointment?');">
                                   <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div id="cancelled-appointments" class="content-view">
                <h2><i class="fa-solid fa-ban"></i> Cancelled Appointments (<?php echo $cancelled_count; ?>)</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Service Name</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Penalty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_cancelled)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                            <td><?php echo date('m/d/Y', strtotime($row['appointment_date'])); ?></td>
                            <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                            <td><span class="status-cancelled">Penalty Applied</span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div id="user-accounts" class="content-view">
                <h2><i class="fa-solid fa-users"></i> User Accounts</h2>
                <div class="table-controls">
                    <input type="text" id="searchUsers" class="search-input table-search" placeholder="Search by Name or Email...">
                </div>
                <table id="usersTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_users)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="status-<?php echo strtolower($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <?php if ($row['status'] === 'Active'): ?>
                                    <button class="action-btn toggle-status-btn" data-id="<?php echo $row['id']; ?>" data-status="Inactive">Deactivate</button>
                                <?php else: ?>
                                    <button class="action-btn toggle-status-btn" data-id="<?php echo $row['id']; ?>" data-status="Active">Activate</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

<script>
const searchInput = document.getElementById('searchUsers');
searchInput.addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');
    rows.forEach(row => {
        const name = row.cells[0].textContent.toLowerCase();
        const email = row.cells[1].textContent.toLowerCase();
        row.style.display = (name.includes(filter) || email.includes(filter)) ? '' : 'none';
    });
});
</script>

<script src="script.js"></script>
</body>
</html>