<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$filePath = 'controllers/product_controller.php';
if (!is_file($filePath)) {
    die("File not found: " . $filePath);
}
require_once($filePath);

// Retrieve all events
$productController = new ProductController();
$events = $productController->getAllEvents();

// Retrieve the user name if logged in
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;

// Retrieve the business name if the user is a partner
$business_name = ($user_type === 'Partner' && isset($_SESSION['business_name'])) ? $_SESSION['business_name'] : null;

// Check for success or error messages
$success_message = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$show_login_modal = isset($_SESSION['show_login_modal']) ? $_SESSION['show_login_modal'] : false;

// Clear the session variables after use to avoid showing them again
unset($_SESSION['success']);
unset($_SESSION['show_login_modal']);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PaintSipGH - Art, Wine & Fun</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
   /* Global Styles */
   body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: #f4e9df;
            background: linear-gradient(
                135deg,
                #3d2b1f 0%,
                #8a552f 30%,
                #5a4320 70%,
                #1a1a1d 100%
            ), 
            url('https://www.transparenttextures.com/patterns/concrete-wall.png'); /* Subtle texture */
            background-blend-mode: overlay; /* Combines gradient and texture */
            background-size: cover; /* Stretches the texture */
            background-attachment: fixed; /* Background stays fixed while scrolling */
        }
       /* Navbar Styles */
       .navbar {
            background: #1a1a1a;
            padding: 1rem 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            color: #f5a623;
            font-size: 2rem;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #f4e9df;
            margin-right: 1.5rem;
            font-weight: 100;
          
            
            
        }

        .navbar-nav .nav-link:hover {
            color: #d4a373;
            
        }

        .nav-item .dropdown-menu {
            background-color: #1a1a1a;
            border: none;
        }

        .dropdown-item {
            color: #f4e9df;
        }

        .dropdown-item:hover {
            color: #d4a373;
            background-color: #24242c;
        }

        .navbar .form-control {
            background-color: #121212;
            border: none;
            color: #f4e9df;
            margin-right: 1rem;
        }

        .navbar .form-control::placeholder {
            color: #888;
        }

        .navbar .btn-outline-light {
            color: #f4e9df;
            border-color: #d4a373;
        }

        .navbar .btn-outline-light:hover {
            background-color: #d4a373;
            color: #1a1a1a;
            border-color: #d4a373;
        }

        /* Sign In and Sign Up Buttons */
        .btn-signin {
            color: #f4e9df;
            margin-right: 1.5rem;
        }

        .btn-signup {
            background-color: #f5a623;
            color: #1a1a1a;
            font-weight: bold;
            border-radius: 50px;
        }

        .btn-signup:hover {
            background-color: #d4a373;
            color: #1a1a1a;
        }
/* Modal Container */
.modal-container {
    display: none; /* Initially hidden */
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #1a1a1d;
    border-radius: 15px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
    padding: 30px;
    z-index: 1050;
    width: 100%;
    max-width: 500px;
}


.modal-form {
    display: flex;
    flex-direction: column;
    gap: 20px; /* Add spacing between form elements */
}

.modal-header {
    font-size: 1.5rem;
    font-weight: bold;
    text-align: center;
    color: #f5a623;
    margin-bottom: 20px;
    border-bottom: 1px solid #d4a373; /* Add a subtle border for separation */
    padding-bottom: 10px;
}


        .modal-container.active {
            display: flex; /* Show when active */
        }


        .form-heading {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            text-align: center;
            color: #e4ded7;
        }

        .modal-footer {
            text-align: center;
            margin-top: 1rem;
        }

        .modal-footer p {
            color: #d4a373;
            cursor: pointer;
        }

        .password-container {
            display: flex;
            align-items: center;
            position: relative;
        }

        .password-container input {
            flex: 1;
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            cursor: pointer;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
            fill: #d4a373;
        }

       

        .modal-form label {
            color: #d4a373;
            font-weight: bold;
            margin-bottom: 0.3rem;
            display: block;
        }

        .modal-form input {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 2px solid #d4a373;
            border-radius: 5px;
            background-color: #24242c;
            color: #f4e9df;
        }

        .modal-form button {
            background-color: #f5a623;
            color: #1a1a1a;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .modal-form button:hover {
            background-color: #d4a373;
        }

        /* Hero Section */
        .hero {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 150px 20px 80px;
            position: relative;
            color: #f8f4f1;
            overflow: hidden;
            text-align: center;
            background: none; /* Remove individual background */
        }


        .hero::before {
            content: '';
            position: absolute;
            top: 10%;
            left: 5%;
            width: 400px;
            height: 300px;
            background: rgba(245, 166, 35, 0.7);
            filter: blur(100px);
            border-radius: 50%;
            z-index: 0;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: 20%;
            right: 10%;
            width: 250px;
            height: 250px;
            background: rgba(255, 227, 179, 0.8);
            filter: blur(100px);
            border-radius: 50%;
            z-index: 0;
        }

        .hero h1 {
            font-size: 3.8rem;
            font-weight: 700;
            color: #f5a623;
            line-height: 1.2;
            max-width: 600px;
            z-index: 1;
            margin: 0 auto;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
            margin-top:-100px;
        }

        .hero h1 span {
            color: #ffe3b3;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .hero img {
            width: 30%;
            border-radius: 15% 50% 50% 40%;
            z-index: 1;
            animation: float 6s ease-in-out infinite;
            position: relative; /* Allows movement using top, left, right, and bottom */
            top: 40px; 
        }


        /* Phone Mockup */
        .phone-mockup {
            position: relative;
            width: 260px;
            height: 500px;
            border: 15px solid #333;
            border-radius: 30px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            background-color: #fff;
            z-index: 1;
            margin-top: 30px;
        }

        .phone-mockup .screen {
            width: 100%;
            height: 100%;
            background-image: url('https://rawcdn.githack.com/Lesliekonlack/images/8e3b1010f1ebbdfb4dd85cc9ec91f9d061149c95/WhatsApp%20Image%202024-11-26%20at%2006.37.59.jpeg');
            background-size: cover;
            background-position: center;
            margin-left:=15px
        }

        .ig-overlay {
            position: absolute;
            bottom: 10%;
            left: 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 90%;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            z-index: 1;
            color: #e1b07e;
        }

        .ig-overlay .icons i {
            font-size: 1.5rem;
            color: #e1b07e;
            margin: 0 8px;
            cursor: pointer;
        }

        /* Paint splatter effect */
        .paint-splatter {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.8;
            mix-blend-mode: overlay;
        }

        .paint-splatter {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.7;
            mix-blend-mode: overlay;
        }

        .splatter1 {
            top: 15%;
            left: 15%;
            background: #ff9a3c;
            transform: rotate(-15deg);
        }

        .splatter1 {
            top: 15%;
            left: 20%;
            background: #d4a373;
        }

        .splatter2 {
            top: 60%;
            left: 20%;
            background: #ffd179;
            transform: rotate(20deg);
        }

        .splatter2 {
            bottom: 10%;
            right: 15%;
            background: #d2b48c;
        }

        .splatter3 {
            top: 35%;
            left: 75%;
            background: #ff5b79;
            transform: rotate(-30deg);
        }

        .splatter3 {
            top: 40%;
            left: 70%;
            background: #b9835e;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* CTA Button */
        .cta-btn {
            background-color: #f5a623;
            color: #1a1a1d;
            padding: 10px 30px;
            border-radius: 50px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .cta-btn:hover {
            background-color: #ffe3b3;
            color: #121212;
        }

        .cta-btn {
            background-color: #d4a373;
            color: #1a1a1a;
            padding: 10px 30px;
            border-radius: 50px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .cta-btn:hover {
            background-color: #f2d3b2;
            color: #1a1a1a;
        }


        /* About Section */
        .about-section {
            padding: 80px 20px;
            overflow: hidden;
            position: relative;
            background: none; /* Remove individual background */
        }

        .about-title {
            font-size: 2.3rem;
            color: #e9b678;
            margin-bottom: 15px;
        }
        .modal-content {
            background-color: #3d2b1f; /* Set modal background to black */
            color: #fff; /* Set text color to white for better visibility */
        }

        .modal-header, .modal-footer {
            border-color: #444; /* Optional: Add a subtle border for separation */
        }

        .about-text {
            font-size: 1.2rem;
            color: #e4ded7;
            max-width: 500px;
        }
        .event-card {
    background: #1a1a1d; /* Dark background to match the theme */
    border: 1px solid #d4a373; /* Subtle accent border */
    border-radius: 10px; /* Slightly rounded corners for modern design */
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    color: #f4e9df; /* Light text for contrast */
}

.event-card:hover {
    transform: scale(1.02); /* Slight zoom effect */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Stronger shadow */
}

.event-card img {
    width: 100%;
    height: 140px; /* Smaller image height for compact design */
    object-fit: cover;
    border-bottom: 1px solid #d4a373; /* Separate image from content */
}

.card-body {
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.card-title {
    font-size: 1.2rem; /* Bold but smaller for compact design */
    font-weight: 600;
    color: #d4a373; /* Warm accent color */
    text-align: center;
    margin-bottom: 10px;
}

.card-text {
    font-size: 0.9rem;
    color: #e4ded7; /* Light text color */
    line-height: 1.4;
    text-align: center;
    margin-bottom: 10px;
}

.card-details {
    display: flex;
    justify-content: space-between; /* Space out details evenly */
    font-size: 0.85rem;
    color: #bfb5a5; /* Subtle, muted text color */
    flex-wrap: wrap;
    gap: 5px;
}

.card-details span {
    flex: 1 1 45%; /* Two columns for compact design */
}

.book-btn {
    background-color: #d4a373;
    color: #1a1a1d; /* Button text contrasts with theme */
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: bold;
    text-align: center;
    margin: 15px auto 0 auto; /* Center align */
    transition: background-color 0.3s ease, transform 0.2s ease;
    width: fit-content;
}

.book-btn:hover {
    background-color: #f5a623; /* Brighter accent color */
    transform: translateY(-2px); /* Subtle lift effect */
}

.close-modal {
    position: absolute;
    top: 10px; /* Adjust for spacing */
    right: 10px; /* Adjust for spacing */
    background: none; /* No background for the button */
    border: none; /* Remove borders */
    color: #f4e9df; /* Button color to match the modal theme */
    font-size: 1.5rem; /* Larger size for better visibility */
    cursor: pointer; /* Pointer cursor for interactivity */
    z-index: 1051; /* Ensure it stays above the modal content */
}

.close-modal:hover {
    color: #f5a623; /* Highlight on hover */
}



                /* Gap Space Between Sections */
        .gap-space {
            height: 50px;
            background: transparent; /* Keeps the background consistent */
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 5%; /* Make the clickable area smaller */
            transform: translateX(-50%); /* Align with carousel edges */
        }

        .carousel-control-prev {
            left: -3%; /* Move further to the left */
        }

        .carousel-control-next {
            right: -7%; /* Move further to the right */
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 2rem; /* Adjust the size of the arrow icons */
            height: 2rem;
        }



        /* Rotated Boxes for Testimonials */
        .testimonial-box {
            background: #3c3231;
            color: #f4e9df;
            border-radius: 8px;
            max-width: 350px;
            transform: rotate(-2deg);
            transition: transform 0.3s;
        }

        .testimonial-box:hover {
            transform: rotate(0deg);
        }

        .testimonial-author {
            font-size: 0.9rem;
            color: #d4a373;
            font-style: italic;
        }

        /* CTA Section with Diagonal Background */
        .cta-section {
            background: linear-gradient(135deg, #d4a373 40%, #f4e9df 100%);
            color: #1a1a1a;
            padding: 60px 20px;
            clip-path: polygon(0 0, 100% 10%, 100% 100%, 0 90%);
        }

        .cta-title {
            font-size: 2.5rem;
            color: #1a1a1a;
            margin-bottom: 15px;
        }

        .cta-btn {
            background-color: #3e3a39;
            color: #d4a373;
            padding: 10px 30px;
            border-radius: 50px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .cta-btn:hover {
            background-color: #d4a373;
            color: #3e3a39;
        }

        /* Paint Splatter Positioning */
        .splatter4 {
            top: 20%;
            right: 10%;
            width: 120px;
            height: 120px;
            background: #d2b48c;
        }

        .splatter5 {
            bottom: 15%;
            left: 80%;
            width: 90px;
            height: 90px;
            background: #b9835e;
        }

        .splatter6 {
            top: 75%;
            left: 5%;
            width: 110px;
            height: 110px;
            background: #d4a373;
        }


        .footer {
            background: #1a1a1a;
        }

    </style>
</head>

<body>
 <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">PaintSipGH</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left Side: Links and Dropdown -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost/PaintSipGH/view/UpcomingEvents.php">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost/PaintSipGH/view/about.php">About</a>
                </li>
                <li class="nav-item">
                    <?php if ($user_type === 'Partner'): ?>
                        <a class="nav-link" href="http://localhost/PaintSipGH/view/Partner_Dashboard.php">Dashboard</a>
                    <?php else: ?>
                        <a class="nav-link" href="http://localhost/PaintSipGH/view/success.php">My Bookings</a>
                        
                    <?php endif; ?>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="modal" data-bs-target="#upcomingFeatureModal" href="#">Shop</a>
                </li>

            </ul>

         

            <!-- Right Side: Welcome Dropdown -->
            <div class="dropdown ms-auto">
            <?php if ($user_type === 'Partner' && $business_name): ?>
            <!-- Dropdown for Partner's Dashboard -->
            <div class="dropdown">
                <a class="btn btn-primary dropdown-toggle me-2" href="part" id="partnerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo htmlspecialchars($business_name); ?> Welcome
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="partnerDropdown">
        
                    <li><a class="dropdown-item" href="http://localhost/PaintSipGH/actions/logout_process.php">Logout</a></li>
                </ul>
            </div>
                <?php elseif ($user_name): ?>
                    <!-- Welcome Dropdown for Logged-In Users -->
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Welcome, <?php echo htmlspecialchars($user_name); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="http://localhost/PaintSipGH/actions/logout_process.php">Logout</a></li>
                    </ul>
                <?php else: ?>
                    <!-- Sign In and Sign Up Buttons for Non-Logged-In Users -->
                    <button class="btn btn-signin me-2" onclick="toggleModal('login')">Sign In</button>
                    <button class="btn btn-signup" onclick="toggleModal('register')">Sign Up</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

    <!-- JavaScript for Bootstrap (for dropdowns and toggler) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
  <!-- Modal -->
<div class="modal-container" id="authModal">
    <button class="close-modal" id="closeModal">&times;</button>
    <?php if ($success_message): ?>
        <div class="alert alert-success" role="alert"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <div id="loginForm" class="modal-form active">
        <div class="modal-header">Login to Your Account</div>
        <form method="post" action="actions/login_process.php">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>
            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" name="password" id="loginPassword" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
        <div class="modal-footer">
            <p onclick="switchForm('register')">Don't have an account? Register</p>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const modalContainer = document.getElementById('authModal');

        // Check if the PHP session variable is true and open the modal
        <?php if ($show_login_modal): ?>
            modalContainer.style.display = 'block'; // Open the modal
        <?php endif; ?>

        // Close modal functionality
        const closeButton = document.getElementById('closeModal');
        closeButton.addEventListener('click', () => {
            modalContainer.style.display = 'none'; // Close the modal
        });
    });
    </script>
    <!-- Register Form -->
    <div id="registerForm" class="modal-form hidden">
    <div class="modal-header">Join Us</div>
    <form method="post" action="actions/signup_process.php">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" placeholder="Enter your first name" required>
        
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" placeholder="Enter your last name" required>
        
        <label for="email">Email</label>
        <input type="email" name="email" id="registerEmail" placeholder="Enter your email" required>
        
        <label for="contact_number">Contact Number</label>
        <input type="text" name="contact_number" id="contact_number" placeholder="Enter your phone number (e.g., 024XXXXXXX)" required>
        
        <label for="password">Password</label>
        <div class="password-container">
            <input type="password" name="password" id="registerPassword" placeholder="Enter your password" required>
            <span class="toggle-password" onclick="togglePassword('registerPassword')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </span>
        </div>
        <button type="submit" name="register">Register</button>
    </form>
    <div class="modal-footer">
        <p onclick="switchForm('login')">Already have an account? Login</p>
    </div>
</div>

</div>

<style>
/* CSS for Toggling */
.hidden {
    display: none !important; /* Ensures the hidden element is not visible */
}

.active {
    display: block !important; /* Ensures the active element is visible */
}
.shop-section {
    background-color:
    #1a1a1d   ;
    }

.shop-item {
    text-align: center;
    margin-bottom: 30px;
    }

.shop-item img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin-bottom: 15px;
    }

.shop-item .product-title {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 10px;
    }

.shop-item .product-description {
    font-size: 1rem;
    margin-bottom: 15px;
    }

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    }

    .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    }
</style>

    <script>

    function switchForm(type) {
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        if (type === 'login') {
            loginForm.classList.add('active');
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            registerForm.classList.remove('active');
        } else if (type === 'register') {
            registerForm.classList.add('active');
            registerForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
            loginForm.classList.remove('active');
        }
    }

    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
         input.type = input.type === 'password' ? 'text' : 'password';
     }

    document.addEventListener("DOMContentLoaded", () => {
    const modalContainer = document.getElementById('authModal');
    const loginButton = document.querySelector('.btn-signin'); // Button to open Login form
    const signupButton = document.querySelector('.btn-signup'); // Button to open Register form
    const closeButton = document.getElementById('closeModal'); // Close modal button

    // Function to close the modal
    const closeModal = () => {
        modalContainer.style.display = 'none';
    };

    // Function to show the login form
    const showLoginForm = () => {
        document.getElementById('loginForm').classList.add('active');
        document.getElementById('loginForm').classList.remove('hidden');
        document.getElementById('registerForm').classList.add('hidden');
        document.getElementById('registerForm').classList.remove('active');
        modalContainer.style.display = 'block';
    };

    // Function to show the register form
    const showRegisterForm = () => {
        document.getElementById('registerForm').classList.add('active');
        document.getElementById('registerForm').classList.remove('hidden');
        document.getElementById('loginForm').classList.add('hidden');
        document.getElementById('loginForm').classList.remove('active');
        modalContainer.style.display = 'block';
    };

    // Event listeners for buttons
    loginButton.addEventListener('click', showLoginForm);
    signupButton.addEventListener('click', showRegisterForm);
    closeButton.addEventListener('click', closeModal);
    });

    </script>

    <!-- Hero Section with Paint Splatter Effect -->
    <section class="hero">
        <img  src="https://rawcdn.githack.com/Lesliekonlack/images/171b06359a524ca7f508d446c96437f28516ea7f/WhatsApp%20Image%202024-11-26%20at%2006.40.54.jpeg" alt="Event">
        <div class="paint-splatter splatter1"></div>
        <div class="paint-splatter splatter2"></div>
        <div class="paint-splatter splatter3"></div>

        <div class="hero-text">
            <h1>Ready to Sip and Paint Your <span>Stress Away?</span></h1>
            <p class="subtext">Find and Book the Best Paint-and-Sip Events Near You in Seconds!</p>
            <a href="http://localhost/PaintSipGH/view/UpcomingEvents.php" class="cta-btn">Reserve Your Spot</a>
        </div>

        <!-- Phone Mockup -->
        <div class="phone-mockup">
            <div class="screen"></div>
            <div class="ig-overlay">
                <span>@PaintSipGH</span>
                <div class="icons">
                    <i class="bi bi-heart"></i>
                    <i class="bi bi-chat"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section with Paint Splash Background -->
    <section class="about-section">
        <div class="container-fluid position-relative py-5">
            <div class="row">
                <div class="col-md-6 offset-md-1 d-flex flex-column justify-content-center">
                    <h2 class="about-title">Why PaintSipGH?</h2>
                    <p class="about-text">
                        PaintSipGH is more than an art session; it’s an experience of self-expression, relaxation, and joy. 
                        Join a community that blends creativity with the pleasures of fine wine in an unforgettable setting.
                    </p>
                </div>
                <div class="col-md-5 text-center position-relative">
                    <img src="https://rawcdn.githack.com/Lesliekonlack/images/8e3b1010f1ebbdfb4dd85cc9ec91f9d061149c95/WhatsApp%20Image%202024-11-26%20at%2006.30.55.jpeg" class="about-image img-fluid rounded shadow-lg" alt="PaintSip event">
                    <div class="paint-splatter splatter4"></div>
                </div>
            </div>
        
   
 <!-- Events Section -->

    <div class="container">
        <h1 class="text-left btn btn-signup" style=" font-size: 16px; font-weight: bold;">Upcoming Events</h1>
        <div class="row g-4">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="event-card">
                            <!-- Corrected Image Path -->
                            <img 
                                src="<?php echo htmlspecialchars(str_replace('../', '', $event['image_path'])); ?>" 
                                alt="Event Image" 
                                style="width: 100%; height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($event['event_name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                                <p class="card-text">Date: <?php echo htmlspecialchars($event['event_date']); ?></p>
                                <p class="card-text">Location: <?php echo htmlspecialchars($event['location']); ?></p>
                                <p class="card-text">Price: $<?php echo number_format($event['ticket_price'], 2); ?></p>
                                <p class="card-text">Available Spots: <?php echo htmlspecialchars($event['available_spots']); ?></p>
                                <a href="http://localhost/PaintSipGH/view/UpcomingEvents.php" class="book-btn">Book Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-light">No events available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    var eventCarousel = document.getElementById('eventCarousel');
    var carousel = new bootstrap.Carousel(eventCarousel, {
    interval: false // Disables automatic sliding
        });
</script>


<?php
require_once('controllers/merchandise_controller.php');
// Query to fetch products from your database
$merchandiseController = new MerchandiseController();

// Fetch all merchandise
$products = $merchandiseController->getAllMerchandise();
?>

<section class="shop-section">
    <div class="container py-5">
        <h2 class="section-title text-center">Shop Our Art Supplies and Merchandise</h2>
        <div class="row">
            <?php
            if (!empty($products)) {
                // Loop through each product in the result set
                foreach ($products as $product) {
                    // Fetch product details
                    $productId = $product['merchandise_id'];
                    $productName = $product['merchandise_name'];
                    $productDescription = $product['description'];
                    $price = $product['price'];
                   
            ?>
                    <div class="col-md-4">
                        <div class="shop-item">
                        <img 
                                src="<?php echo htmlspecialchars(str_replace('../', '', $product['image_path'])); ?>" 
                                alt="Event Image" 
                                style="width: 70%; height: 200px; object-fit: cover;">
                            <h3 class="product-title"><?php echo $productName; ?></h3>
                            <p class="product-description"><?php echo $productDescription; ?></p>
                            <p class="product-price"><?php echo $price; ?></p>
                           <!-- Shop Now Button -->
                            <a href="#" class="book-btn" data-bs-toggle="modal" data-bs-target="#upcomingFeatureModal">Shop Now</a>

                            <!-- Modal -->
                            <div class="modal fade" id="upcomingFeatureModal" tabindex="-1" aria-labelledby="upcomingFeatureModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="upcomingFeatureModalLabel">Upcoming Feature</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            The "Shop Now" feature is coming soon. Stay tuned for updates!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>
            <?php
                }
            } else {
                echo "<p>No products found</p>";
            }
            ?>
        </div>
    </div>
</section>

     <!-- Testimonials Section with Rotated Boxes 
    <section   class="testimonials-section py-5">
        <div class="container text-center">
            <h2 class="section-title">What Our Guests Say</h2>
            <div class="row justify-content-center">
                <div class="testimonial-box col-lg-3 col-md-6 mx-2 shadow p-4 rotated-box">
                    <p class="testimonial-text">"An absolute joy! PaintSipGH turned a casual evening into a memorable experience. Highly recommend it!"</p>
                    <p class="testimonial-author">- Ama, Accra</p>
                </div>
                <div class="testimonial-box col-lg-3 col-md-6 mx-2 shadow p-4 rotated-box">
                    <p class="testimonial-text">"Perfect blend of art and wine! The setup, the atmosphere, and the vibe were exceptional."</p>
                    <p class="testimonial-author">- Kwame, Kumasi</p>
                </div>
            </div>
            <div class="paint-splatter splatter6"></div>
        </div>
    </section> -->


    <!-- Adding Gap Space -->
<div class="gap-space"></div>

    <!-- Call to Action with Diagonal Split -->
    <section class="cta-section diagonal-split position-relative py-5 text-center">
    <h2 class="cta-title">Sign Up for Our Newsletter</h2>
    <p>Stay updated with our latest events, offers, and news.</p>
    <form>
        <input type="email" placeholder="Enter your email" required>
        <button type="submit" class="cta-btn">Subscribe</button>
    </form>
</section>


    <!-- Footer with Gradient Background -->
    <footer class="footer py-4">
        <div class="container text-center">
            <p>&copy; 2024 PaintSipGH. All Rights Reserved.</p>
            <div class="footer-links">
                <a href="http://169.239.251.102:4442/~hannah.nah-anzoh/view/about.php">Privacy Policy</a> |
                <a href="http://169.239.251.102:4442/~hannah.nah-anzoh/view/about.php">Terms of Service</a> |
                <a href="http://169.239.251.102:4442/~hannah.nah-anzoh/view/about.php">Contact Us</a>
            </div>
        </div>
    </footer>

   

</body>

</html>