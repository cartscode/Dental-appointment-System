<?php
include('config.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    
    // ⭐️ FIX: Use the secure password_hash()
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    // ⭐️ FIX: Use prepared statements for security (checking for existing user)
    $stmt_check = $conn->prepare("SELECT id FROM admin WHERE username = ? OR email = ?");
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Username or Email already exists!');</script>";
    } else {
        // ⭐️ FIX: Use prepared statements for security (inserting new user)
        $stmt_insert = $conn->prepare("INSERT INTO admin (fullname, email, username, password) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("ssss", $fullname, $email, $username, $hashed_password);

        if ($stmt_insert->execute()) {
            echo "<script>alert('Admin account created successfully!'); window.location.href='adminlogin.php';</script>";
        } else {
            // Note: If you get an error here, check your database connection or column types.
            echo "<script>alert('Error creating account: " . $conn->error . "');</script>";
        }
        $stmt_insert->close();
    }
    $stmt_check->close();
    $conn->close();
}
// The HTML code is fine and does not need changes.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dental+</title>
  <link rel="stylesheet" href="adminsignupstyle.css">
  <link rel="icon" type="image/png" href="src/tooth.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>

  <!-- Header / Navbar -->
  <header>
    <nav class="navbar">
      <div class="logo">
        <img src="dentist.gif" alt="Logo">
        <h2>Dental<span>+</span></h2>
      </div>
    </nav>
  </header>

  <!--background for login -->
  <main class="background">
    <div class="login-box">
      <h2>SIGN UP</h2>
    <form action="" method="POST">
  <input type="text" name="fullname" placeholder="Full Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="text" name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit" name="signup">SIGN UP</button>

  <p class="signup-text">
    Already have an account? <a href="adminlogin.php">LOGIN</a>
  </p>
</form>

    </div>
  </main>

  <!-- Footer -->
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

</body>
</html>
