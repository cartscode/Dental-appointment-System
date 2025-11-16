<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us | Dental+</title>
    <link rel="stylesheet" href="contact.css">
    <link rel="icon" type="image/png" href="../src/tooth.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>

    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="src/dentist.gif" alt="Logo">
                <h2>Dental<span>+</span></h2>
            </div>
            <div class="menu-toggle" id="menu-toggle">
                <i class="fa-solid fa-bars"></i>
            </div>
             <ul class="nav-links" id="nav-links">
        <li><a href="/Project in IS104/homepage/index.html">Home</a></li>
        <li><a href="/Project in IS104/About Us/AboutUs.html">About Us</a></li>
        <li><a href="/Project in IS104/Services/services.html">Services</a></li>
        <li><a href="/Project in IS104/contactUs/contact.php" class="active">Contact Us</a></li>
      </ul>
            <div class="nav-right">
                <a href="tel:+1234567891" class="phone">
                    <i class="fa-solid fa-phone"></i> 1234-567-891
                </a>
                <a href="booking.html" class="btn">Book Appointment</a>
            </div>
        </nav>
    </header>

    <main class="background">
        <div class="contact-box">
            <h2>Contact Us</h2>
            <p>We’d love to hear from you! Please fill out the form below and we’ll get back to you soon.</p>
            
            <form id="contactForm" method="POST"> 
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <textarea name="message" placeholder="Your Message" required></textarea>
    <button type="submit">Send Message</button>
</form>

<div id="messageContainer"></div>

            <div class="contact-info">
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
                    <a href="mailto:support@dentalplus.com"><i class="fa-solid fa-envelope"></i></a>
                </div>
            </div>
        </div>
        <p class="footer-bottom">© 2025 Dental+. All Rights Reserved.</p>
    </footer>

    
    <script src="contact.js"></script>
</body>
</html>