<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Fetch latest appointment
$appt_query = "SELECT * FROM appointments WHERE user_id = '$user_id' ORDER BY appointment_date DESC LIMIT 1";
$appt_result = mysqli_query($conn, $appt_query);
$appt = mysqli_fetch_assoc($appt_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Appointment | Dental+</title>
  <link rel="icon" href="src/tooth.png" type="image/png">
  <link rel="stylesheet" href="MyAppointments.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
  <!-- ===== NAVBAR ===== -->
  <header>
    <nav class="navbar">
      <div class="logo">
        <img src="dentist.gif" alt="Logo">
        <h2>Dental<span>+</span></h2>
      </div>

      <div class="menu-toggle" id="menu-toggle">
        <i class="fa-solid fa-bars"></i>
      </div>

      <div class="menu" id="menu">
        <ul class="nav-links">
          <li><a href="AppointmentsBooking.php">AppointmentsBooking</a></li>
          <li><a href="MyAppointments.php" class="active">My Appointment</a></li>
        </ul>

        <div class="nav-right">
          <div class="profile">
            <i class="fa-solid fa-user-circle"></i>
          </div>
          <a href="logout.php" class="btn logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
          </a>
        </div>
      </div>
    </nav>
  </header>

  <!-- ===== MAIN CONTENT ===== -->
  <section class="main-content">
    <div class="welcome-container">
      <h1 class="welcome-title">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
      <p class="subtitle">Here’s your appointment summary and quick booking access.</p>

      <div class="dashboard-grid">
        <!-- LEFT SIDE: Upcoming Appointment -->
        <div class="upcoming-visit-card">
          <?php if ($appt): ?>
            <div class="card-item">
              <span class="label">Service</span>
              <div class="content-row">
                <span class="value"><?php echo htmlspecialchars($appt['service_name']); ?></span>
              </div>
            </div>

            <div class="card-item">
              <span class="label">Date</span>
              <div class="content-row">
                <span class="value"><?php echo date("F j, Y", strtotime($appt['appointment_date'])); ?></span>
              </div>
            </div>

            <div class="card-item">
              <span class="label">Time</span>
              <div class="content-row">
                <span class="value"><?php echo date("g:i A", strtotime($appt['appointment_time'])); ?></span>
              </div>
            </div>

            <div class="card-item">
              <span class="label">Status</span>
              <div class="content-row">
                <span class="value pending"><?php echo ucfirst($appt['status']); ?></span>
                <?php if (strtolower($appt['status']) === 'pending'): ?>
                  <button class="cancel-btn" onclick="confirmCancel()">Cancel</button>
                <?php endif; ?>
              </div>
            </div>
          <?php else: ?>
            <div class="card-item">
              <span class="label">No Appointment</span>
              <div class="content-row">
                <span class="value pending">You don’t have any upcoming appointments.</span>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- RIGHT SIDE: Book New Appointment -->
        <div class="new-appointment-card">
          <h2 class="card-title">Book a New Appointment</h2>

          <div class="icon-grid">
            <div class="icon-circle"><i class="fa-solid fa-tooth"></i></div>
            <div class="icon-circle"><i class="fa-solid fa-clock"></i></div>
            <div class="icon-circle"><i class="fa-solid fa-briefcase-medical"></i></div>
            <div class="icon-circle"><i class="fa-solid fa-calendar-check"></i></div>
          </div>

          <button class="book-now-btn" onclick="window.location.href='AppointmentsBooking.php'">
            Book Now
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  <footer>
    <div class="footer-container">
      <div class="footer-about">  
        <h3>Dental<span>+</span></h3>
        <p>Your trusted dental partner for a healthy, confident smile.</p>
      </div>
      <div class="footer-socials">
        <h4>Follow Us</h4>
        <div class="social-icons">
          <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#"><i class="fa-brands fa-instagram"></i></a>
          <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
    <p class="footer-bottom">© 2025 Dental+. All Rights Reserved.</p>
  </footer>

  <script>
    function confirmCancel() {
      if (confirm("Are you sure you want to cancel this appointment?")) {
        window.location.href = "cancel_appointment.php";
      }
    }

    const toggle = document.getElementById("menu-toggle");
    const menu = document.getElementById("menu");
    toggle.addEventListener("click", () => menu.classList.toggle("active"));
  </script>
</body>
</html>
