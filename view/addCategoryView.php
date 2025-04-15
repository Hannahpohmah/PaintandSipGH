<?php

session_start();

// Check if the user is logged in and is a partner
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Partner') {
    // Redirect to login page or an error page if the user is not a partner
    header("Location: http://localhost/PaintSipGH/index.php");
    exit();
}



// Retrieve session data
$business_name = $_SESSION['business_name'] ?? "Partner";
$user_name = $_SESSION['user_name'] ?? "Partner";

// Fetch existing themes from the database
require_once '../classes/category_class.php';
$category = new Category();
$themes = $category->listCategories();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($business_name); ?> - Manage Themes</title>
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

        .form-label {
            color: #d4a373;
        }

        .form-control {
            background: #24242c;
            color: #f4e9df;
            border: 1px solid #d4a373;
        }

        .btn-primary, .btn-danger {
            border: none;
            border-radius: 50px;
            font-weight: bold;
            color: #1a1a1d;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #f5a623;
        }

        .btn-primary:hover {
            background: #d4a373;
        }

        .btn-danger {
            background: #ff4d4d;
        }

        .btn-danger:hover {
            background: #e60000;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><?php echo htmlspecialchars($business_name); ?></h2>
        <a href="Partner_Dashboard.php">Dashboard Overview</a>
        <a href="http://localhost/PaintSipGH">Home</a>
        <a href="addProductView.php">Add Event</a>
        <a href="addCategoryView.php" class="active">Manage Themes</a>
        <a href="#">Manage Events</a>
        <a href="#">Analytics</a>
        <a href="#">Account Settings</a>
        <a href="http://localhost/PaintSipGH/actions/logout_process.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h3>Welcome, <?php echo htmlspecialchars($business_name ?? $user_name); ?>. Manage your themes here.</h3>
            <div class="user-info">
                <img src="https://via.placeholder.com/40" alt="User">
                <span><?php echo htmlspecialchars($business_name ?? $user_name); ?></span>
            </div>
        </div>

        <!-- Add Theme Section -->
        <div id="addThemeSection" class="mt-5">
            <h4>Add New Theme</h4>
            <form id="themeForm">
                <div class="mb-3">
                    <label for="theme_name" class="form-label">Theme Name</label>
                    <input type="text" class="form-control" id="theme_name" name="theme_name" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Theme</button>
            </form>
        </div>

        <!-- List Themes Section -->
<div id="themeList" class="mt-5">
    <h4>Existing Themes</h4>
    <ul class="list-group">
        <?php foreach ($themes as $theme): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo htmlspecialchars($theme['theme_name']); ?>
                <form method="POST" action="../actions/deleteCategory.php" style="margin: 0;">
                    <input type="hidden" name="theme_id" value="<?php echo $theme['theme_id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm">
                        Delete
                    </button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById("themeForm").addEventListener("submit", async function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const response = await fetch('../actions/addCategory.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        alert(result.message);
        if (result.success) {
            window.location.reload();
        }
    });
    
    </script>
</body>
</html>
