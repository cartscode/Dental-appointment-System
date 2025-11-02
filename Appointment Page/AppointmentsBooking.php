<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dental+ | Book Appointment</title>
    <link rel="icon" type="image/png" href="src/tooth.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="AppointmentsBooking.css"> 
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

            <ul class="nav-links" id="nav-links">
                <li><a href="#" class="active">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>

            <div class="nav-right">
                <a href="tel:+1234567891" class="phone">
                    <i class="fa-solid fa-phone"></i> 1234-567-891
                </a>
            </div>
        </nav>
    </header>

    <main class="background">
    <div class="appointment-container">
        <h1>Book a New Appointment</h1>
        
        <form id="appointment-form" action="submit_appointment.php" method="POST">
            <div class="booking-cards-wrapper">
                
                <div class="card service-selection">
                    <h2>Select a Service</h2>
                    <div class="service-list">
                        <div class="service-item active" data-service="checkup">
                            <i class="fas fa-search"></i>
                            <span class="service-name">General Check-up</span>
                        </div>
                        <div class="service-item" data-service="orthodontics">
                            <i class="fas fa-tooth"></i>
                            <span class="service-name">Orthodontics</span>
                        </div>
                        <div class="service-item" data-service="root-canal">
                            <i class="fas fa-syringe"></i>
                            <span class="service-name">Root Canal</span>
                        </div>
                        <div class="service-item" data-service="implants">
                            <i class="fas fa-user-md"></i>
                            <span class="service-name">Implants</span>
                        </div>
                        <div class="service-item" data-service="cleaning">
                            <i class="fas fa-soap"></i>
                            <span class="service-name">Dental Cleaning</span>
                        </div>
                        <div class="service-item" data-service="whitening">
                            <i class="fas fa-star"></i>
                            <span class="service-name">Teeth Whitening</span>
                        </div>
                        <div class="service-item" data-service="veneers">
                            <i class="fas fa-mask"></i>
                            <span class="service-name">Veneers</span>
                        </div>
                        <div class="service-item" data-service="crowns">
                            <i class="fas fa-gem"></i>
                            <span class="service-name">Bridges & Crowns</span>
                        </div>
                    </div>
                </div>


                <div class="card date-time-selection">
                    <h2>Select a Date & Time</h2>
                    
                    <div class="calendar-header">
                        <i class="fas fa-chevron-left" id="prev-month"></i>
                        <div class="month-year" id="month-year-display">OCTOBER 2025</div>
                        <i class="fas fa-chevron-right" id="next-month"></i>
                    </div>

                    <div class="calendar-grid" id="calendar-grid">
                        <div class="day-name">SUN</div>
                        <div class="day-name">MON</div>
                        <div class="day-name">TUE</div>
                        <div class="day-name">WED</div>
                        <div class="day-name">THU</div>
                        <div class="day-name">FRI</div>
                        <div class="day-name">SAT</div>
                        </div>
                    
                    <div class="time-slots">
                        <label class="time-slot"><input type="radio" name="time" value="08:00:00" checked> 8:00 - 8:30 AM</label>
                        <label class="time-slot"><input type="radio" name="time" value="09:00:00"> 9:00 - 9:30 AM</label>
                        <label class="time-slot"><input type="radio" name="time" value="10:00:00"> 10:00 - 11:00 AM</label>
                        <label class="time-slot"><input type="radio" name="time" value="12:00:00"> 12:00 - 1:00 PM</label>
                        <label class="time-slot"><input type="radio" name="time" value="14:00:00"> 2:00 - 2:30 PM</label>
                        <label class="time-slot"><input type="radio" name="time" value="15:00:00"> 3:00 - 4:00 PM</label>
                        <label class="time-slot"><input type="radio" name="time" value="16:00:00"> 4:00 - 4:30 PM</label>
                        <label class="time-slot"><input type="radio" name="time" value="17:00:00"> 5:00 - 6:00 PM</label>
                    </div>

                    <input type="hidden" id="selected-service" name="service">
                    <input type="hidden" id="selected-date" name="date"> 

                    <button class="submit-btn" type="submit">SUBMIT</button> 
                </div>

            </div>
        </form>
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
    
    <script src="AppointmentsBooking.js"></script>
</body>
</html>