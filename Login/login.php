<?php
include('config.php');
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from DB
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];     // ✅ added

            echo "<script>
                    alert('Login successful!');
                    window.location.href='http://localhost/Project%20in%20IS104/Appointment%20Page/AppointmentsBooking.php';
                  </script>";
        } else {
            echo "<script>alert('Incorrect password! Please try again.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Username not found!'); window.history.back();</script>";
    }

    $conn->close();
}
?>
