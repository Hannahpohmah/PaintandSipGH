<?php
session_start();

// Check if the user is logged in and is a partner
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Partner') {
    // Redirect to login page or an error page if the user is not a partner
    header("Location: http://localhost/PaintSipGH/index.php");
    exit();
}

// Retrieve session data
$business_name = isset($_SESSION['business_name']) ? $_SESSION['business_name'] : "Partner";
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Partner";

$organizerId = $_SESSION['user_id'];
require_once('../controllers/merchandise_controller.php');

// Initialize the MerchandiseController
$merchandiseController = new MerchandiseController();
$upcomingEventName = $merchandiseController->getUpcomingEventName();
// Fetch the data
$totalEvents = $merchandiseController->getTotalEvents();
$totalSales = $merchandiseController->getTotalSales();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($business_name); ?> Dashboard</title>
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
            );
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #1a1a1d;
            color: #f4e9df;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .sidebar h2 {
            text-align: center;
            color: #f5a623;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .sidebar a {
            color: #f4e9df;
            text-decoration: none;
            font-size: 1.1rem;
            margin: 10px 0;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background: #5a4320;
            color: #fff;
        }

        .sidebar a.active {
            background: #d4a373;
            color: #1a1a1d;
            font-weight: bold;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background: linear-gradient(
                135deg,
                #1a1a1d 0%,
                #5a4320 40%,
                #3d2b1f 100%
            );
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #5a4320;
            color: #f4e9df;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .top-bar .user-info {
            display: flex;
            align-items: center;
        }

        .top-bar .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            background: #1a1a1d;
            color: #f4e9df;
            text-align: center;
            padding: 20px;
        }

        .card h5 {
            color: #f5a623;
        }

        .add-event-btn {
            background: #f5a623;
            color: #1a1a1d;
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .add-event-btn:hover {
            background: #d4a373;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><?php echo htmlspecialchars($business_name); ?></h2>
        <a href="#" class="active">Dashboard Overview</a>
        <a href="http://localhost/PaintSipGH">Home</a>
        <a href="addProductView.php">Add Event</a>
        <a href="addmarchandiseView.php">Add Marchandise</a>
        <a href="addCategoryView.php">Add Categories</a>
        <a href="#">Manage Events</a>
        
        <a href="http://localhost/PaintSipGH/actions/logout_process.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
    <!-- Top Bar -->
        <div class="top-bar">
            <h3>Welcome, <?php echo htmlspecialchars($business_name ?? $user_name); ?></h3>
            <div class="user-info">
                <img src="https://via.placeholder.com/40" alt="User">
                <span><?php echo htmlspecialchars($business_name ?? $user_name); ?></span>
            </div>
        </div>
        <!-- Dashboard Content -->
        <div class="row g-4">
            <div class="col-md-12 mb-3">
                <a href="addProductView.php" class="add-event-btn">+ Add New Event</a>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h5>Total Events</h5>
                    <p><?php echo $totalEvents['total_events']; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h5>Total Sales</h5>
                    <p><?php echo 'GHS ' . number_format($totalSales['total_sales'] ?? 0, 2); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h5>Upcoming Event</h5>
                    <p><?php echo htmlspecialchars($upcomingEventName); ?></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
