<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../controllers/product_controller.php');

// Initialize the ProductController
$productController = new ProductController();

// Check if the form is submitted (search functionality)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    // Collect search filters
    $filters = [
        'event_name' => $_POST['event_name'] ?? '',
        'location' => $_POST['location'] ?? '',
        'date' => $_POST['date'] ?? '',
        'theme' => $_POST['theme'] ?? '',
    ];
    // Fetch filtered events
    $events = $productController->searchEvents($filters);
} else {
    // Fetch events based on user type
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Partner') {
        // For partners, fetch only their events
        $partnerId = $_SESSION['user_id'];
        $events = $productController->getPartnerEvents($partnerId);
    } else {
        // For customers, fetch all available events
        $events = $productController->getAllEvents();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events | PaintSipGH</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
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
            background-color: #1a1a1a;
            padding: 1rem 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            color: #f5a623;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #f4e9df;
            margin-right: 1.5rem;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: #d4a373;
        }

        /* Search Bar */
        .search-bar-placeholder {
            display: none; /* Initially hidden */
        }

        .search-bar-container {
            position: relative;
            margin: 20px auto;
            padding: 20px 30px;
            background: #1a1a1d;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            max-width: 1200px;
            transition: all 0.3s ease-out;
        }

        .search-bar-container.sticky {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            margin: 0 auto;
            padding: 5px 10px; /* Reduce padding to make it smaller */
            width: calc(100% - 40px); /* Narrower width */
            max-width: 900px; /* Limit the maximum width */
            z-index: 1001;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* Subtle shadow */
            border-radius: 25px; /* Keeps edges rounded */
        }

        .search-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            text-align: center;
        }

        .search-bar-item {
            display: flex;
            flex-direction: column;
            align-items: center; /* Centers label and input */
            flex: 1;
            min-width: 150px;
        }

        .search-bar-item label {
            font-size: 0.9rem;
            font-weight: bold;
            color: #d4a373;
            margin-bottom: 5px;
        }

        .search-bar-item input,
        .search-bar-item select {
            width: 100%;
            max-width: 300px; /* Prevents inputs from stretching */
            padding: 10px 15px;
            border: 2px solid #d4a373;
            border-radius: 25px;
            background: #24242c;
            color: #f4e9df;
            font-size: 1rem;
            outline: none;
        }

        .search-bar-item button {
            padding: 10px 20px;
            border-radius: 25px;
            background-color: #d4a373;
            color: #1a1a1d;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 150px;
        }

        .search-bar-item button:hover {
            background-color: #f5a623;
        }

        /* Events Section */
        .events-section {
            padding: 50px 20px;
        }

        .events-section h2 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 30px;
            text-align: center;
        }

        .event-card {
            background-color: #1a1a1d;
            border: 1px solid #d4a373;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }

        .event-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .event-card-body {
            padding: 15px;
        }

        .event-card-title {
            color: #f5a623;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .event-card-details {
            color: #f4e9df;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .event-card-button {
            background-color: #f5a623;
            color: #1a1a1a;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .event-card-button:hover {
            background-color: #d4a373;
        }

        footer {
            text-align: center;
            background: #1a1a1a;
            color: #f4e9df;
            padding: 20px 0;
        }

        @media (max-width: 768px) {
            .search-bar {
                flex-direction: column;
            }

            .event-card img {
                height: 150px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="#">PaintSipGH</a>
        <div class="navbar-text mx-auto text-center" style="color: #f5a623; font-size: 1.2rem; font-weight: bold;">
            Find and Explore Paint and Sip Events Near You
        </div>
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="http://localhost/PaintSipGH/">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="http://localhost/PaintSipGH/view/success.php">My Bookings</a></li>
            <!-- <li class="nav-item"><a class="nav-link" href="#">Partner with Us</a></li> -->
            <!-- Cart View Button with Item Count Badge -->
            <li class="nav-item">
                <a class="nav-link" href="cart_view.php">
                    <i class="bi bi-cart"></i> Cart
                    <span class="badge bg-warning text-dark" id="cart-count" style="position: absolute; top: -5px; right: -10px; font-size: 12px;">
                        0
                    </span>
                </a>
            </li>
        </ul>
    </div>
</nav>


    <!-- Placeholder for Search Bar -->
    <div class="search-bar-placeholder" id="searchBarPlaceholder"></div>

    <!-- Search Bar Section -->
    <div class="search-bar-container">
    <form method="POST" action="">
        <div class="search-bar">
            <!-- Event Name -->
            <div class="search-bar-item">
                <label for="event-name">Event Name</label>
                <input type="text" name="event_name" id="event-name" placeholder="Search by event name">
            </div>

            <!-- Location -->
            <div class="search-bar-item">
                <label for="location">Location</label>
                <select name="location" id="location">
                    <option value="">Select Location</option>
                    <?php
                    // Fetch all locations from the controller
                    $locations = $productController->getAllLocations();
                    foreach ($locations as $location) {
                        echo "<option value='{$location['city_name']}'>{$location['city_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Date -->
            <div class="search-bar-item">
                <label for="date">Date</label>
                <input type="date" name="date" id="date">
            </div>

            <!-- Theme -->
            <div class="search-bar-item">
                <label for="theme">Theme</label>
                <select name="theme" id="theme">
                    <option value="">Select Theme</option>
                    <?php
                    // Fetch all themes from the controller
                    $themes = $productController->getAllThemes();
                    foreach ($themes as $theme) {
                        echo "<option value='{$theme['theme_name']}'>{$theme['theme_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Search Button -->
            <div class="search-bar-item">
                <label>&nbsp;</label>
                <button type="submit" name="search" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </div>
    </form>
</div>


  <!-- Events Section -->
  <section class="events-section">
        <div class="container">
            <h2>Explore Our Events</h2>
            <div class="row row-cols-1 row-cols-md-4 g-4">
                <?php foreach ($events as $event): ?>
                    <?php 
                        // Resolve image path
                        $imagePath = str_replace('../', '/PaintSipGH/', $event['image_path']);
                    ?>
                    <div class="col">
                        <div class="event-card">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                 alt="<?php echo htmlspecialchars($event['event_name']); ?>" 
                                 onerror="this.src='/PaintSipGH/uploads/default-image.jpg';">
                                 <div class="event-card-body">
                            <div class="event-card-title"><?php echo htmlspecialchars($event['event_name']); ?></div>
                            <div class="event-card-details">
                                Description: <?php echo htmlspecialchars($event['description']); ?> <br>
                                Date: <?php echo htmlspecialchars($event['event_date']); ?> <br>
                                Location: <?php echo htmlspecialchars($event['location']); ?>
                            </div>

                            <!-- Add to Cart Form -->
                            <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
                                <form action="../actions/addToCart.php" method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>"> <!-- User ID from session -->
                                    <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>"> <!-- Event ID -->
                                    <input type="number" name="quantity" value="1" min="1" max="10" required>
                                    
                                    <button type="submit" class="event-card-button">Add to Cart</button>
                               
                                </form>
                            <?php else: ?>
                                <button class="event-card-button" onclick="redirectToLogin()">Add to Cart</button>
                                <script>
                                    function redirectToLogin() {
                                        alert("You need to be logged in to add items to the cart. Redirecting to the login page...");
                                        window.location.href = "http://localhost/PaintSipGH/";
                                    }
                                </script>
                            <?php endif; ?>
                        </div>
                    </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>




    <!-- Footer -->
    <footer>
        <p>&copy; 2024 PaintSipGH. All Rights Reserved.</p>
    </footer>

    <!-- JavaScript -->
    <script>
        const searchBarContainer = document.getElementById('searchBarContainer');
        const searchBarPlaceholder = document.getElementById('searchBarPlaceholder');
        const searchBarHeight = searchBarContainer.offsetHeight;

        searchBarPlaceholder.style.height = `${searchBarHeight}px`;

        window.addEventListener('scroll', () => {
            if (window.scrollY > searchBarContainer.offsetTop) {
                searchBarPlaceholder.style.display = 'block';
                searchBarContainer.classList.add('sticky');
            } else {
                searchBarPlaceholder.style.display = 'none';
                searchBarContainer.classList.remove('sticky');
            }
        });
    </script>
</body>

</html>
