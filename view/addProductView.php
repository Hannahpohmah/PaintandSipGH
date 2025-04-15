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
require_once '../classes/category_class.php';
require_once '../classes/product_class.php';


// Fetch themes and events from the database
$category = new Category();
$themes = $category->listCategories();

$product = new Product();
$events = $product->viewEvents($_SESSION['user_id']);
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
        <a href="addProductView.php" class="active">Add Event</a>
        <a href="addCategoryView.php">Add Categories</a>
        <a href="#">Manage Events</a>
        <a href="#">Analytics</a>
        <a href="#">Account Settings</a>
        <a href="http://localhost/PaintSipGH/actions/logout_process.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h3>Welcome, <?php echo htmlspecialchars($business_name ?? $user_name); ?>. Ready to create or manage events?</h3>
            <div class="user-info">
                <img src="https://via.placeholder.com/40" alt="User">
                <span><?php echo htmlspecialchars($business_name ?? $user_name); ?></span>
            </div>
        </div>

        <!-- Manage Events Section -->
        <div id="manageEventsSection" class="mt-5">
            <h4>Manage Your Events</h4>
            <ul class="list-group" id="eventList">
                <?php foreach ($events as $event): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="event-name"><?php echo htmlspecialchars($event['event_name']); ?></div>
                        <div>
                            <button class="btn btn-warning btn-sm update-event" data-id="<?php echo $event['event_id']; ?>">Update</button>
                            <button class="btn btn-danger btn-sm delete-event" data-id="<?php echo $event['event_id']; ?>">Delete</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Add Event Section -->
        <div id="addEventSection" class="mt-5">
            <h4>Add New Event</h4>
            <form id="addEventForm" action="../actions/addProduct.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Event Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Event Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Ticket Price</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
                <div class="mb-3">
                    <label for="total_spots" class="form-label">Total Spots</label>
                    <input type="number" class="form-control" id="total_spots" name="total_spots" required>
                </div>
                <div class="mb-3">
                    <label for="theme_id" class="form-label">Theme</label>
                    <select class="form-control" id="theme_id" name="theme_id" required>
                        <?php foreach ($themes as $theme): ?>
                            <option value="<?php echo $theme['theme_id']; ?>">
                                <?php echo htmlspecialchars($theme['theme_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Event Image</label>
                    <input type="file" class="form-control" id="image" name="image" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Event</button>
            </form>
        </div>

 <!-- Update Event Modal (Popup) -->
<div class="modal fade" id="updateEventModal" tabindex="-1" aria-labelledby="updateEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateEventModalLabel">Update Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateEventForm" action="../actions/updateProduct.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="event_id" name="event_id">
                    <div class="mb-3">
                        <label for="event_name" class="form-label">Event Name</label>
                        <input type="text" class="form-control" id="event_name" name="event_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="event_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="event_location" name="location" required>
                    </div>
                    <div class="mb-3">
                        <label for="event_date" class="form-label">Event Date</label>
                        <input type="date" class="form-control" id="event_date" name="event_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="ticket_price" class="form-label">Ticket Price</label>
                        <input type="number" step="0.01" class="form-control" id="event_price" name="ticket_price" required>
                    </div>
                    <div class="mb-3">
                        <label for="event_total_spots" class="form-label">Total Spots</label>
                        <input type="number" class="form-control" id="event_total_spots" name="total_spots" required>
                    </div>
                    <div class="mb-3">
                        <label for="event_theme" class="form-label">Theme</label>
                        <select class="form-control" id="event_theme" name="theme_id" required>
                            <?php foreach ($themes as $theme): ?>
                                <option value="<?php echo $theme['theme_id']; ?>"><?php echo htmlspecialchars($theme['theme_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="event_image" class="form-label">Event Image</label>
                        <input type="file" class="form-control" id="event_image" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Event</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <script>
        // Handle Delete Event
        document.querySelectorAll('.delete-event').forEach(button => {
            button.addEventListener('click', async function () {
                const eventId = this.dataset.id;
                const confirmDelete = confirm("Are you sure you want to delete this event?");
                
                if (confirmDelete) {
                    try {
                        const response = await fetch('../actions/deleteProduct.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ event_id: eventId })
                        });

                        const result = await response.json();
                        if (result.success) {
                            alert(result.message);
                            window.location.reload(); // Reload to update the list
                        } else {
                            alert(result.message);
                        }
                    } catch (error) {
                        alert("An error occurred while deleting the event.");
                    }
                }
            });
        });

        document.querySelectorAll('.update-event').forEach(button => {
    button.addEventListener('click', function () {
        const eventId = this.dataset.id;

        // Fetch the event details from the controller (now integrated into product_controller.php)
        fetch('../controllers/product_controller.php?action=getEventDetails&event_id=' + eventId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Pre-fill the modal with the event data
                    document.getElementById('event_id').value = data.event.event_id;
                    document.getElementById('event_name').value = data.event.event_name;
                    document.getElementById('event_description').value = data.event.description;
                    document.getElementById('event_location').value = data.event.location;
                    document.getElementById('event_date').value = data.event.event_date;
                    document.getElementById('event_price').value = data.event.ticket_price;
                    document.getElementById('event_total_spots').value = data.event.total_spots;
                    document.getElementById('event_theme').value = data.event.theme_id;

                    // Show the modal
                    const updateEventModal = new bootstrap.Modal(document.getElementById('updateEventModal'));
                    updateEventModal.show();
                } else {
                    alert('Event details could not be retrieved.');
                }
            })
            .catch(error => {
                console.error('Error fetching event details:', error);
                alert('An error occurred while fetching event details.');
            });
    });
});
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
