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

// Include necessary files
require_once '../classes/merchandise_class.php';

// Create an instance of Merchandise class
$merchandise = new Merchandise();

// Fetch merchandise (products) from the database
$items = $merchandise->viewMerchandise($_SESSION['user_id']);
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
            background: linear-gradient(135deg, #3d2b1f 0%, #8a552f 30%, #5a4320 70%, #1a1a1d 100%);
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
            background: linear-gradient(135deg, #1a1a1d 0%, #5a4320 40%, #3d2b1f 100%);
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

        .form-label {
            color: #d4a373;
        }

        .form-control {
            background: #24242c;
            color: #f4e9df;
            border: 1px solid #d4a373;
        }

        .update-form-container {
            display: none;
            background: #24242c;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .update-form-container.active {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><?php echo htmlspecialchars($business_name); ?></h2>
        <a href="Partner_Dashboard.php">Dashboard Overview</a>
        <a href="http://localhost/PaintSipGH">Home</a>
        <a href="addMerchandiseView.php" class="active">Add Merchandise</a>
        <a href="#">Manage Merchandise</a>
        <a href="#">Analytics</a>
        <a href="#">Account Settings</a>
        <a href="http://localhost/PaintSipGH/actions/logout_process.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h3>Welcome, <?php echo htmlspecialchars($business_name ?? $user_name); ?>. Ready to create or manage merchandise?</h3>
            <div class="user-info">
                <img src="https://via.placeholder.com/40" alt="User">
                <span><?php echo htmlspecialchars($business_name ?? $user_name); ?></span>
            </div>
        </div>

        <!-- Manage Merchandise Section -->
        <div id="manageMerchandiseSection" class="mt-5">
            <h4>Manage Your Merchandise</h4>
            <ul class="list-group" id="merchandiseList">
                <?php foreach ($items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="merchandise-name"><?php echo htmlspecialchars($item['merchandise_name']); ?></div>
                        <div>
                            <button class="btn btn-warning btn-sm update-merchandise" data-id="<?php echo $item['merchandise_id']; ?>">Update</button>
                            <button class="btn btn-danger btn-sm delete-merchandise" data-id="<?php echo $item['merchandise_id']; ?>">Delete</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Add Merchandise Section -->
        <div id="addMerchandiseSection" class="mt-5">
            <h4>Add New Merchandise</h4>
            <form id="addMerchandiseForm" action="../actions/addMerchandise.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Merchandise Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Merchandise</button>
            </form>
        </div>

        <!-- Update Merchandise Modal (Popup) -->
        <div class="modal fade" id="updateMerchandiseModal" tabindex="-1" aria-labelledby="updateMerchandiseModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateMerchandiseModalLabel">Update Merchandise</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateMerchandiseForm" action="../actions/updateMerchandise.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="merchandise_id" name="merchandise_id">
                            <div class="mb-3">
                                <label for="merchandise_name" class="form-label">Merchandise Name</label>
                                <input type="text" class="form-control" id="merchandise_name" name="merchandise_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantity_available" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity_available" name="quantity_available" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Merchandise</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle Delete Merchandise
        document.querySelectorAll('.delete-merchandise').forEach(button => {
            button.addEventListener('click', async function () {
                const merchandiseId = this.dataset.id; // Retrieve merchandise ID from data-id attribute
                const confirmDelete = confirm("Are you sure you want to delete this merchandise?");
                
                if (confirmDelete) {
                    try {
                        // Send POST request to delete merchandise
                        const response = await fetch('../actions/deleteMerchandise.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ merchandise_id: merchandiseId })
                        });

                        const result = await response.json();
                        
                        if (result.success) {
                            alert(result.message); // Success message
                            window.location.reload(); // Reload the page
                        } else {
                            alert(result.message); // Error message
                        }
                    } catch (error) {
                        alert("An error occurred while deleting the merchandise.");
                        console.error('Error:', error);
                    }
                }
            });
        });

    document.querySelectorAll('.update-merchandise').forEach(button => {
        button.addEventListener('click', function () {
            const merchandiseId = this.dataset.id;

            // Fetch the merchandise details from the controller
            fetch('../controllers/merchandise_controller.php?action=getMerchandiseDetails&merchandise_id=' + merchandiseId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Pre-fill the modal with the merchandise data
                        document.getElementById('merchandise_id').value = data.merchandise.merchandise_id;
                        document.getElementById('merchandise_name').value = data.merchandise.merchandise_name;
                        document.getElementById('description').value = data.merchandise.description;
                        document.getElementById('price').value = data.merchandise.price;
                        document.getElementById('quantity_available').value = data.merchandise.quantity_available;
                        document.getElementById('image').src = data.merchandise.image_path;

                        // Show the modal
                        const updateMerchandiseModal = new bootstrap.Modal(document.getElementById('updateMerchandiseModal'));
                        updateMerchandiseModal.show();
                    } else {
                        alert('Merchandise details could not be retrieved.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching merchandise details:', error);
                    alert('An error occurred while fetching merchandise details.');
                });
        });
    });
    </script>

</body>
</html>
