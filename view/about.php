<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - PaintSipGH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: #f4e9df;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #1a1a1d;
            padding: 1rem 2rem;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: #f5a623;
        }

        .navbar-nav .nav-link {
            color: #f4e9df;
            margin-right: 1.5rem;
        }

        .navbar-nav .nav-link:hover {
            color: #d4a373;
        }

        .about-hero {
            background: linear-gradient(135deg, #3d2b1f 0%, #8a552f 50%, #5a4320 100%);
            color: #f5a623;
            text-align: center;
            padding: 120px 20px;
            position: relative;
            overflow: hidden;
        }

        .about-hero::before {
            content: '';
            position: absolute;
            top: 20%;
            left: 10%;
            width: 300px;
            height: 300px;
            background: rgba(245, 166, 35, 0.3);
            border-radius: 50%;
            filter: blur(100px);
        }

        .about-hero h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
        }

        .about-hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .hero-btn {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            background-color: #f5a623;
            color: #1a1a1d;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
        }

        .hero-btn:hover {
            background-color: #d4a373;
            color: #121212;
        }

        .about-section {
            padding: 60px 20px;
        }

        .about-section h2 {
            font-size: 2.5rem;
            text-align: center;
            color: #ffd700;
            margin-bottom: 20px;
        }

        .about-text {
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 40px;
        }

        .about-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .values-section {
            padding: 60px 20px;
            background-color: #1a1a1d;
        }

        .values-title {
            font-size: 2rem;
            text-align: center;
            color: #f5a623;
            margin-bottom: 30px;
        }
        .about-image {
            max-width: 70%;
            height: auto;
            display: block;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .value-card {
            background: #242424;
            color: #f4e9df;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .value-card h3 {
            font-size: 1.5rem;
            color: #ffd700;
            margin-bottom: 10px;
        }

        .team-section {
            padding: 60px 20px;
        }

        .team-title {
            font-size: 2rem;
            text-align: center;
            color: #ffd700;
            margin-bottom: 30px;
        }

        .team-member {
            text-align: center;
            background: #242424;
            color: #f4e9df;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .team-member img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .footer {
            background: #1a1a1d;
            color: #f4e9df;
            text-align: center;
            padding: 20px;
        }

        .footer a {
            color: #f5a623;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
        .modal-content {
            background-color: #000; /* Set modal background to black */
            color: #fff; /* Set text color to white for better visibility */
        }

        .modal-header, .modal-footer {
            border-color: #444; /* Optional: Add a subtle border for separation */
        }

    </style>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="http://localhost/PaintSipGH">PaintSipGH</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="about-hero">
        <h1>About PaintSipGH</h1>
        <p>Discover the joy of painting, sipping, and creating memories with us.</p>
        <a href="http://localhost/PaintSipGH/view/UpcomingEvents.php" class="hero-btn">reserve your spot</a>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <h2>Our Story</h2>
            <p class="about-text">
                PaintSipGH was born from a passion for art and community. We create a space where everyone—from seasoned
                artists to complete beginners—can enjoy the simple pleasures of painting and sipping on their favorite
                beverages. With expert instructors and a welcoming atmosphere, every session is a chance to unwind, 
                connect, and let your creativity shine.
            </p>
            <img src="https://rawcdn.githack.com/Lesliekonlack/images/8e3b1010f1ebbdfb4dd85cc9ec91f9d061149c95/WhatsApp%20Image%202024-11-26%20at%2006.30.55.jpeg" alt="PaintSip Event" class="about-image">
        </div>
    </section>

    <!-- Core Values -->
    <section class="values-section">
        <div class="container">
            <h2 class="values-title">Our Core Values</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="value-card">
                        <h3>Creativity</h3>
                        <p>Unleash your inner artist and let your imagination flow.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-card">
                        <h3>Connection</h3>
                        <p>Build lasting relationships through shared experiences.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-card">
                        <h3>Fun</h3>
                        <p>Create joyful memories that last a lifetime.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-section">
        <div class="container">
            <h2 class="contact-title">Contact Us</h2>
            <form class="contact-form">
                <input type="text" class="form-control" placeholder="Your Name" required>
                <input type="email" class="form-control" placeholder="Your Email" required>
                <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                <button type="submit" class="btn btn-warning w-100">Send Message</button>
            </form>
        </div>
    </section>
    <!-- Terms and Conditions Button -->
    <div class="text-center my-4">
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#termsModal">
            Terms and Conditions
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Welcome to PaintSipGH! By using our services, you agree to the following terms and conditions:</p>
                    <ul>
                        <li>All sessions must be booked in advance and are non-refundable.</li>
                        <li>Participants must be 18 years or older, or accompanied by an adult.</li>
                        <li>PaintSipGH is not responsible for damages to personal belongings during events.</li>
                        <li>Our content is for personal use only and cannot be reproduced without permission.</li>
                        <li>Terms may be updated periodically; please review them regularly.</li>
                    </ul>
                    <p>Thank you for being part of our community!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 PaintSipGH. All rights reserved. | <a href="#">Privacy Policy</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
