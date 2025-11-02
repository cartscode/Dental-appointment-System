<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dental+ | Book Appointment</title>
    <link rel="icon" type="image/png" href="src/tooth.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="MyAppointments.css"> 
</head>

<body>
 <header>
  <nav class="navbar">
    <div class="logo">
      <img src="dentist.gif" alt="Logo">
      <h2>Dental<span>+</span></h2>
    </div>

    <div class="menu-toggle" id="menu-toggle">
      <i class="fa-solid fa-bars"></i>
    </div>

    <!-- ✅ New wrapper -->
    <div class="menu" id="menu">
      <ul class="nav-links">
        <li><a href="/Project in IS104/Appointment Page/AppointmentsBooking.php">AppointmentsBooking</a></li>
        <li><a href="/Project in IS104/Appointment Page/MyAppointments.php" class="active">My Appointment</a></li>
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

    <main class="main-content">
        <div class="welcome-container">
            <h1 class="welcome-title">Welcome, (User)!</h1>
            <p class="subtitle">Your Upcoming Visits</p>

            <div class="dashboard-grid">
                <div class="upcoming-visit-card">
                    <div class="card-item">
                        <span class="label">STATUS</span>
                        <div class="content-row">
                            <span class="value pending">Pending</span>
                            <button class="cancel-btn">CANCEL</button>
                        </div>
                    </div>

                    <div class="card-item">
                        <span class="label">TREATMENT / SERVICE</span>
                        <div class="content-row">
                            <span class="value">Root Canal</span>
                        </div>
                    </div>

                    <div class="card-item">
                        <span class="label">DATE APPOINTED</span>
                        <div class="content-row">
                            <span class="value date-time">Friday, October 10th at 2:30 PM</span>
                            <button class="cancel-btn">CANCEL</button>
                        </div>
                    </div>
                </div>

                <div class="new-appointment-card">
                    <h2 class="card-title">Book a New Appointment</h2>
                    <div class="icon-grid">
                        <div class="icon-circle">
                            <i class="fa-solid fa-tooth"></i> </div>
                        <div class="icon-circle">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="icon-circle">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <div class="icon-circle">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                    </div>
                <a href="AppointmentsBooking.php" class="book-now-btn">Book an Appointment Now!</a>
            </div>
        </div>
    </main>

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
    
    <script src="MyAppointments.js"></script>
</body>
</html>