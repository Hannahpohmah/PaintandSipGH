<?php
// Check if the first file exists
if (file_exists('classes/product_class.php')) {
    require_once('classes/product_class.php');
} else {
    require_once('../classes/product_class.php');
}

class ProductController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    // Add a new event
    public function addEvent($data, $file)
    {
        try {
            // Validate the data
            $requiredFields = ['name', 'description', 'location', 'date', 'price', 'theme_id', 'total_spots'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Field $field is required.");
                }
            }

            // Handle the image upload
            $imagePath = $this->handleImageUpload($file);

            // Insert the event into the database
            $result = $this->productModel->addEvent(
                $_SESSION['user_id'], // Current logged-in partner's ID
                $data['name'],
                $data['description'],
                $data['location'],
                $data['date'],
                $data['price'],
                $data['theme_id'],
                $imagePath,
                $data['total_spots']
            );

            if (!$result) {
                throw new Exception("Failed to add event to the database.");
            }

            return ['success' => true, 'message' => 'Event added successfully.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Update an existing event
    public function updateEvent($eventId, $data)
    {
        try {
            // Handle the image upload if a new image is provided
            if (!empty($data['image'])) {
                $data['image_path'] = $this->handleImageUpload($data['image']);
            }

            // Update the event in the database
            $result = $this->productModel->updateEvent($eventId, $data);

            if (!$result) {
                throw new Exception("Failed to update the event.");
            }

            return ['success' => true, 'message' => 'Event updated successfully.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Delete an event
    public function deleteEvent($eventId)
    {
        try {
            $result = $this->productModel->deleteEvent($eventId);

            if (!$result) {
                throw new Exception("Failed to delete the event.");
            }

            return ['success' => true, 'message' => 'Event deleted successfully.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function searchEvents($filters)
{
    return $this->productModel->searchEvents($filters);
}

public function getAllLocations()
{
    return $this->productModel->getAllLocations();
}

public function getAllThemes()
{
    return $this->productModel->getAllThemes();
}


    // View events by a partner
    public function viewEvents($partnerId)
    {
        return $this->productModel->viewEvents($partnerId);
    }

    // Fetch event details by event_id for updating
    public function getEventDetails($eventId)
    {
        $event = $this->productModel->getEventDetails($eventId);

        if ($event) {
            return ['success' => true, 'event' => $event];
        } else {
            return ['success' => false, 'message' => 'Event not found.'];
        }
    }

    // Fetch all events for display
    public function getAllEvents()
    {
        return $this->productModel->getAllEvents();
    }

    // Fetch partner events with booking details
    public function getPartnerEvents($partnerId)
    {
        return $this->productModel->getPartnerEvents($partnerId);
    }

    // Handle image upload
    private function handleImageUpload($file)
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception("Invalid file upload.");
        }

        $uploadDir = '../uploads/';
        $fileName = basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        // Ensure the upload directory exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move the uploaded file
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception("Failed to upload image.");
        }

        return $targetPath;
    }
}

// Handle the different actions (getEventDetails and updateEvent)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getEventDetails' && isset($_GET['event_id'])) {
        // Fetch event details
        $productController = new ProductController();
        $result = $productController->getEventDetails($_GET['event_id']);
        echo json_encode($result);
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['event_id'])) {
        // Update event
        $productController = new ProductController();
        $result = $productController->updateEvent($_POST['event_id'], $_POST);
        echo json_encode($result);
        exit;
    }   
}




// Handle the different actions (getEventDetails and updateEvent)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getEventDetails' && isset($_GET['event_id'])) {
        // Fetch event details
        $productController = new ProductController();
        $result = $productController->getEventDetails($_GET['event_id']);
        echo json_encode($result);
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['event_id'])) {
        // Update event
        $productController = new ProductController();
        $result = $productController->updateEvent($_POST['event_id'], $_POST);
        echo json_encode($result);
        exit;
    }
}
?>
