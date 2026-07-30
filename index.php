
<?php
include("config/database.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Disaster Relief & Resource Management System</title>

    <!-- CSS -->

    <link rel="stylesheet" href="css/style.css">

    <!-- Font Awesome -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

    <!-- ================= NAVBAR ================= -->

    <header>

        <nav class="navbar">

            <div class="logo">
                <i class="fa-solid fa-shield-heart"></i>
                <span>DRRMS</span>
            </div>

            <ul class="nav-links">

                <li><a href="#home">Home</a></li>

                <li><a href="#about">About</a></li>

                <li><a href="#services">Services</a></li>

                <li><a href="#contact">Contact</a></li>

            </ul>

        </nav>

    </header>

    <!-- ================= HERO SECTION ================= -->

    <section class="hero" id="home">

        <div class="hero-content">

            <h1>
                Disaster Relief &
                <br>
                Resource Management System
            </h1>

            <p>

                A Smart Platform for Reporting Disasters,
                Requesting Resources and Coordinating Relief Operations.

            </p>

            <div class="hero-buttons">

                <a href="login.php" class="primary-btn">
                    Login
                </a>

                <a href="register.php" class="secondary-btn">
                    Register
                </a>

            </div>

        </div>

    </section>
    <!-- ================= ABOUT SECTION ================= -->

<section class="about" id="about">

    <div class="about-container">

        <div class="about-text">

            <h2>About Our System</h2>

            <p>

                The Disaster Relief & Resource Management System is a web-based platform
                designed to improve disaster response by connecting victims,
                volunteers and administrators in one centralized system.

            </p>

            <p>

                The platform enables quick disaster reporting, efficient resource
                management, volunteer coordination and transparent delivery tracking,
                ensuring that relief reaches affected people faster.

            </p>

        </div>

        <div class="about-image">

            <i class="fa-solid fa-hand-holding-heart"></i>

        </div>

    </div>

</section>
<!-- ================= SERVICES SECTION ================= -->

<section class="services" id="services">

    <h2>Our Services</h2>

    <div class="service-container">

        <div class="service-card">

            <i class="fa-solid fa-triangle-exclamation"></i>

            <h3>Disaster Reporting</h3>

            <p>
                Quickly report disaster incidents and notify authorities for immediate action.
            </p>

        </div>

        <div class="service-card">

            <i class="fa-solid fa-box-open"></i>

            <h3>Resource Request</h3>

            <p>
                Victims can request essential resources like food, water and medicine.
            </p>

        </div>

        <div class="service-card">

            <i class="fa-solid fa-handshake-angle"></i>

            <h3>Volunteer Management</h3>

            <p>
                Volunteers can participate in relief operations and help affected communities.
            </p>

        </div>

        <div class="service-card">

            <i class="fa-solid fa-truck-fast"></i>

            <h3>Delivery Tracking</h3>

            <p>
                Monitor resource delivery status from dispatch to successful completion.
            </p>

        </div>

    </div>

</section>
<!-- ================= STATISTICS SECTION ================= -->

<section class="statistics">

    <h2>System Statistics</h2>

    <div class="stats-container">

        <div class="stat-card">
            <i class="fa-solid fa-users"></i>
            <h3>250+</h3>
            <p>Registered Users</p>
        </div>

        <div class="stat-card">
            <i class="fa-solid fa-hand-holding-medical"></i>
            <h3>500+</h3>
            <p>Resources Delivered</p>
        </div>

        <div class="stat-card">
            <i class="fa-solid fa-user-group"></i>
            <h3>120+</h3>
            <p>Active Volunteers</p>
        </div>

        <div class="stat-card">
            <i class="fa-solid fa-house-flood-water"></i>
            <h3>80+</h3>
            <p>Disaster Reports</p>
        </div>

    </div>

</section>
<!-- ================= CONTACT SECTION ================= -->

<section class="contact" id="contact">

    <h2>Contact Us</h2>

    <div class="contact-container">

        <div class="contact-card">

            <i class="fa-solid fa-envelope"></i>

            <h3>Email</h3>

            <p>support@drrms.com</p>

        </div>

        <div class="contact-card">

            <i class="fa-solid fa-phone"></i>

            <h3>Phone</h3>

            <p>+880 1700-000000</p>

        </div>

        <div class="contact-card">

            <i class="fa-solid fa-location-dot"></i>

            <h3>Location</h3>

            <p>Chattogram, Bangladesh</p>

        </div>

    </div>

</section>

    <!-- JavaScript -->

    <script src="js/script.js"></script>

    <!-- ================= FOOTER ================= -->

<footer>

    <p>

        © 2026 Disaster Relief & Resource Management System |
        Developed for Academic Project

    </p>

</footer>
</body>

</html>