<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('../controllers/orders_controller.php');

$userId = $_SESSION['user_id'] ?? null;
$userName = $_SESSION['user_name'] ?? 'Guest';
if (!$userId) {
    echo "<p style='color: red;'>You are not logged in.</p>";
    exit();
}

$orderController = new OrderController();
$userTickets = $orderController->getAllTicketsByUser($userId);

// Filter tickets where paid_at matches today's date
$todaysDate = date('Y-m-d');
$todaysTickets = array_filter($userTickets, function ($ticket) use ($todaysDate) {
    return date('Y-m-d', strtotime($ticket['paid_at'])) === $todaysDate;
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .container {
            margin-top: 50px;
        }

        .ticket-list {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .ticket {
            background: #fff;
            border: 2px dashed #ff6f61;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .ticket:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .ticket img {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            border: 2px solid #ffc107;
            object-fit: cover;
            margin-right: 20px;
        }

        .ticket-details {
            text-align: left;
            flex-grow: 1;
        }

        .ticket-details h3 {
            color: #ff6f61;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .ticket-details p {
            margin: 5px 0;
            color: #333;
        }

        .ticket .special-code {
            font-weight: bold;
            color: #ff6f61;
        }

        .ticket .thank-you {
            font-size: 1rem;
            color: #333;
            margin-top: 10px;
            font-style: italic;
        }

        .paid-badge {
            background: #28a745;
            color: #fff;
            padding: 5px 10px;
            font-size: 0.9rem;
            border-radius: 50px;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .navbar {
            background-color: #1a1a1d;
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
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: #ffc107;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px 0;
            background-color: #1a1a1a;
            color: #f4e9df;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">PaintSipGH</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="http://localhost/PaintSipGH/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="http://localhost/PaintSipGH/view/UpcomingEvents.php">Upcoming Events</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 class="text-center" style="color: black;">Your Tickets</h1>
        
        <!-- Today's Tickets -->
        <?php if (!empty($todaysTickets)): ?>
            <h2 class="text-center" style="color: white;">Today's Bookings</h2>
            <div class="ticket-list">
                <?php foreach ($todaysTickets as $ticket): ?>
                    <div class="ticket">
                    <span class="paid-badge">Paid</span>
                    <?php 
                        $eventImage = !empty($ticket['image_path']) ? $ticket['image_path'] : '../uploads/event-placeholder.jpg';
                        ?>
                        <img src="<?php echo htmlspecialchars($eventImage); ?>" alt="Event Image">
                        <div class="ticket-details">
                        <h3><?php echo htmlspecialchars($ticket['event_name']); ?></h3>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($ticket['event_date']); ?></p>
                            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($ticket['quantity']); ?></p>
                            <p><strong>Price:</strong> $<?php echo number_format($ticket['price'], 2); ?></p>
                            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($ticket['order_id']); ?></p>
                            <p><strong>Code:</strong> <span class="special-code">PS<?php echo htmlspecialchars($ticket['order_id']) . '-' . substr(md5($userId), 0, 8); ?></span></p>
                            <p class="thank-you">Thank you, <?php echo htmlspecialchars($userName); ?>, for your booking!</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Past Tickets -->
        <div class="text-center mt-5">
            <button class="btn btn-warning" id="toggle-past-tickets">Toggle Past Tickets</button>
        </div>

        <div id="past-tickets" style="display: none;">
            <h2 class="text-center mt-3" style="color: #fff;">Past Tickets</h2>
            <div class="ticket-list">
                <?php
                $pastTickets = array_filter($userTickets, function ($ticket) use ($todaysDate) {
                    return strtotime($ticket['event_date']) < strtotime($todaysDate);
                });
                ?>
                <?php if (!empty($pastTickets)): ?>
                    <?php foreach ($pastTickets as $ticket): ?>
                        <div class="ticket">
                        <span class="paid-badge">Paid</span>
                            <?php 
                            $eventImage = !empty($ticket['image_path']) ? $ticket['image_path'] : '../uploads/event-placeholder.jpg';
                            ?>
                            <img src="<?php echo htmlspecialchars($eventImage); ?>" alt="Event Image">
                            <div class="ticket-details">
                                <h3><?php echo htmlspecialchars($ticket['event_name']); ?></h3>
                                <p><strong>Date:</strong> <?php echo htmlspecialchars($ticket['event_date']); ?></p>
                                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($ticket['quantity']); ?></p>
                                <p><strong>Price:</strong> $<?php echo number_format($ticket['price'], 2); ?></p>
                                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($ticket['order_id']); ?></p>
                                <p><strong>Code:</strong> <span class="special-code">PS<?php echo htmlspecialchars($ticket['order_id']) . '-' . substr(md5($userId), 0, 8); ?></span></p>
                                <p class="thank-you">Thank you, <?php echo htmlspecialchars($userName); ?>, for your booking!</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center" style="color: #fff;">There are no past bookings.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 PaintSipGH. All Rights Reserved.</p>
    </footer>

    <script>
        document.getElementById('toggle-past-tickets').addEventListener('click', function () {
            const pastTicketsSection = document.getElementById('past-tickets');
            pastTicketsSection.style.display = pastTicketsSection.style.display === 'none' ? 'block' : 'none';
        });
    </script>
</body>
</html>
