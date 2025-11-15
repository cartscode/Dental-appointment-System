<?php
// ====================================================================
// 0. SESSION AND SECURITY CHECK (CRITICAL - ADD THIS LINE IF YOU HAVEN'T)
// session_start();
// if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
//     header("Location: admin_login.php");
//     exit;
// }
// ====================================================================

// ====================================================================
// 1. DATABASE CONNECTION AND DATA FETCHING LOGIC
// ====================================================================
require_once 'db_connect.php';

// --- Data Fetching for "Schedule for Today" ---
$today_date = date('Y-m-d'); // Ensures the format is YYYY-MM-DD for SQL comparison

// 1. Prepare the statement
$sql_today = "
    SELECT id, name, email, service_name, appointment_date, appointment_time, status 
    FROM appointments 
    WHERE appointment_date = ?
      AND status = 'Pending'
    ORDER BY appointment_time ASC
";
$stmt_today = mysqli_prepare($conn, $sql_today);

if (!$stmt_today) {
    die('Preparation Error for Today\'s Schedule: ' . mysqli_error($conn));
}

// 2. Bind the parameter (the date)
// 's' means the parameter is a string
mysqli_stmt_bind_param($stmt_today, "s", $today_date); 

// 3. Execute the statement
mysqli_stmt_execute($stmt_today);

// 4. Get the result
$result_today = mysqli_stmt_get_result($stmt_today);

if (!$result_today) {
    die('Execution Error for Today\'s Schedule: ' . mysqli_error($conn));
}

$today_count = mysqli_num_rows($result_today);

// Note: You must now close the statement object when done with it.
// We'll leave it open for now as it's needed for the loop later in the HTML, 
// but in a production script, you'd close it after fetching all data.

// ... rest of your data fetching logic
// --- Data Fetching for "Patients Schedule" ---
$sql_all_appointments = "SELECT id, name, email, service_name, appointment_date, status 
                         FROM appointments 
                         ORDER BY appointment_date DESC, appointment_time DESC";
$result_all_appointments = mysqli_query($conn, $sql_all_appointments);
if (!$result_all_appointments) {
    die("Error in All Appointments Query: " . mysqli_error($conn));
}

// --- Data Fetching for "User Accounts" ---
$sql_users = "SELECT id, name, number, email, emergency_contact, birth_month, birth_day, birth_year, gender 
              FROM users 
              ORDER BY id DESC";
$result_users = mysqli_query($conn, $sql_users);
if (!$result_users) {
    die("Error in User Accounts Query: " . mysqli_error($conn));
}

// -------------------------------------------------------------------
// --- NEW: Data Fetching for "Missed Appointments List" ---
// -------------------------------------------------------------------
$sql_missed = "SELECT id, name, email, service_name, appointment_date, appointment_time
               FROM appointments 
               WHERE status = 'Missed' 
               ORDER BY appointment_date DESC, appointment_time DESC";
$result_missed = mysqli_query($conn, $sql_missed);
if (!$result_missed) {
    die("Error fetching missed appointments: " . mysqli_error($conn));
}
$missed_count = mysqli_num_rows($result_missed);
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
                
                <a href="?view=patients-message" class="nav-item" data-view="patients-message"><i class="fa-solid fa-message"></i> Patient's Message <span class="arrow">&rarr;</span></a>
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
                        <?php 
                        while ($row = mysqli_fetch_assoc($result_today)): 
                            $status_class = strtolower($row['status']); 
                        ?>
                        <tr data-appointment-id="<?php echo $row['id']; ?>">
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['service_name']); ?></td> 
                            <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                            <td class="status-<?php echo $status_class; ?>"><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <button class="action-btn done-btn" data-action="done" data-id="<?php echo $row['id']; ?>"><i class="fa-solid fa-check"></i> Done</button>
                                <button class="action-btn missed-btn" data-action="missed" data-id="<?php echo $row['id']; ?>"><i class="fa-solid fa-xmark"></i> Missed</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php if (isset($result_today)) mysqli_free_result($result_today); ?>
            </div>

            <div id="patients-schedule" class="content-view">
                <h2><i class="fa-solid fa-calendar"></i> Patients Schedule</h2>
                <div class="table-controls">
                    <input type="text" id="searchAppointments" class="search-input table-search" placeholder="Search by Name/Email/Service..." class="search-input full-width">
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
                        <?php 
                        while ($row = mysqli_fetch_assoc($result_all_appointments)):
                            $status_class = strtolower($row['status']); 
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                            <td><?php echo date('m/d/Y', strtotime($row['appointment_date'])); ?></td>
                            <td class="status-<?php echo $status_class; ?>"><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php if (isset($result_all_appointments)) mysqli_free_result($result_all_appointments); ?>
            </div>
            
            <div id="missed-appointments" class="content-view">
                <h2><i class="fa-solid fa-calendar-times"></i> Missed Appointments List (<span id="missed-count-display"><?php echo $missed_count; ?></span>)</h2>
                <div class="table-controls">
                    <input type="text" id="searchMissed" placeholder="Search by Name/Email/Service..." class="search-input full-width">
                </div>
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
    <?php 
    while ($row = mysqli_fetch_assoc($result_missed)):
    ?>
    <tr data-appointment-id="<?php echo $row['id']; ?>">
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['service_name']); ?></td>
        <td><?php echo date('m/d/Y', strtotime($row['appointment_date'])); ?></td>
        <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
        
        <!-- ACTION CELL: Contains the Delete Link -->
        <td>
            <a href="delete_appointment.php?id=<?php echo $row['id']; ?>"
               class="action-btn edit-btn"
               onclick="return confirm('Are you sure you want to permanently DELETE this missed appointment record?');">
                <i class="fas fa-trash"></i> Delete
            </a>
        </td>
        <!-- END ACTION CELL -->
    </tr>
    <?php endwhile; ?>
</tbody>
                </table>
                <?php if (isset($result_missed)) mysqli_free_result($result_missed); ?>
            </div>
            <div id="user-accounts" class="content-view">
                <h2><i class="fa-solid fa-users"></i> User Accounts</h2>
                <div class="table-controls">
                    <input type="text" id="searchMissed" placeholder="Search by Name/Email/ID..." class="search-input full-width">
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID No:</th>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Email</th>
                            <th>Emergency No.</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while ($row = mysqli_fetch_assoc($result_users)): 
                            $birthday = "N/A";
                            if (!empty($row['birth_month']) && !empty($row['birth_day']) && !empty($row['birth_year'])) {
                                $birthday = htmlspecialchars($row['birth_month'] . '/' . $row['birth_day'] . '/' . $row['birth_year']);
                            }
                        ?>
                        <tr data-user-id="<?php echo $row['id']; ?>">
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['number']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['emergency_contact']); ?></td>
                            <td><?php echo $birthday; ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td>
                                <!-- this part (work on this btn dlete and update)-->
                                <button class="action-btn edit-btn" data-action="edit" data-id="<?php echo $row['id']; ?>"><i class="fa-solid fa-edit"></i> Edit</button>
                                <button class="action-btn delete-btn" data-action="delete" data-id="<?php echo $row['id']; ?>"><i class="fa-solid fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php if (isset($result_users)) mysqli_free_result($result_users); ?>
            </div>

            <div id="patients-message" class="content-view">
                <h2><i class="fa-solid fa-message"></i> Patient's Message</h2>
                <div class="table-controls">
                    <input type="text" id="searchMessages" placeholder="Search by Name/Email/Message..." class="search-input full-width">
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date Received</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Michael Johnson</td>
                            <td>michael@example.com</td>
                            <td class="message-preview">I need to reschedule my appointment...</td>
                            <td>11/05/2025 09:30 AM</td>
                          <td>
    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">
        <i class="fa-solid fa-edit"></i> Edit
    </a>

    <a href="delete_user.php?id=<?php echo $row['id']; ?>" 
       class="action-btn delete-btn"
       onclick="return confirm('Are you sure you want to DELETE this user?');">
       <i class="fa-solid fa-trash"></i> Delete
    </a>
</td>

                        </tr>
                    </tbody>
                </table>
            </div>
            
        </main>
    </div>

    <script src="script.js"></script> 
    
</body>
</html>