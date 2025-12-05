<?php
include('config.php');
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info from database
$query = "SELECT first_name, last_name, email, number, emergency, month, day, year FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email, $number, $emergency, $month, $day, $year);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental+ | Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    <div class="menu" id="menu">
      <ul class="nav-links">
        <li><a href="/Project in IS104/Appointment Page/AppointmentsBooking.php">Book Appointment</a></li>
        <li><a href="/Project in IS104/Appointment Page/MyAppointments.php">My Appointment</a></li>
      </ul>

      <div class="nav-right">
        <div class="profile-dropdown" id="profileDropdown">
            <div class="profile-icon">
                <i class="fa-solid fa-user-circle"></i>
            </div>

            <div class="dropdown-menu" id="dropdownMenu">
                <a href="profile.php"><i class="fa-solid fa-user"></i> Edit Profile</a>
                <a href="change_password.php"><i class="fa-solid fa-key"></i> Change Password</a>
                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>
      </div>
    </div>
  </nav>
</header>

<div class="profile-container">
    <div class="profile-card">
        <h2>My Profile</h2>
        <p class="subtitle">Update your personal details</p>

        <!-- SUCCESS / ERROR MESSAGE -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">Profile updated successfully!</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">Something went wrong.</div>
        <?php endif; ?>

        <form action="update_profile.php" method="POST">

            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?php echo $first_name; ?>" required>
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?php echo $last_name; ?>" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo $email; ?>" required>
            </div>

            <div class="form-group">
                <label>Mobile Number</label>
                <input type="text" name="number" value="<?php echo $number; ?>" required>
            </div>

            <div class="form-group">
                <label>Emergency Contact</label>
                <input type="text" name="emergency" value="<?php echo $emergency; ?>">
            </div>

            <div class="form-group">
                <label>Birthday</label>
                <div style="display:flex; gap:5px;">
                    <input type="text" name="month" value="<?php echo $month; ?>" placeholder="Month" required>
                    <input type="number" name="day" value="<?php echo $day; ?>" placeholder="Day" min="1" max="31" required>
                    <input type="number" name="year" value="<?php echo $year; ?>" placeholder="Year" min="1900" max="<?php echo date('Y'); ?>" required>
                </div>
            </div>

            <button type="submit" class="save-btn">Save Changes</button>
        </form>
    </div>
</div>

<script>
// PROFILE DROPDOWN
const profile = document.getElementById("profileDropdown");
const dropdown = document.getElementById("dropdownMenu");

profile.addEventListener("click", () => {
    dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
});
</script>

</body>
</html>
