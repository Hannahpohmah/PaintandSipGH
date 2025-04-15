<?php
// Check if the first file exists
if (file_exists('classes/merchandise_class.php')) {
    require_once('classes/merchandise_class.php');
} else {
    require_once('../classes/merchandise_class.php');
}

class MerchandiseController
{
    private $merchandiseModel;

    public function __construct()
    {
        $this->merchandiseModel = new Merchandise();
    }

    // Add new merchandise
    public function addMerchandise($data, $file)
    {
        try {
            // Validate the data
            $requiredFields = ['name', 'description', 'price', 'quantity']; // Changed 'quantity' to 'quantity_available'
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Field $field is required.");
                }
            }

            // Handle the image upload
            $imagePath = $this->handleImageUpload($file);

            // Insert the merchandise into the database
            $result = $this->merchandiseModel->addMerchandise(
                $_SESSION['user_id'], // Current logged-in partner's ID
                $data['name'],
                $data['description'],
                $data['price'],
                $data['quantity'] ,
                $imagePath 
            );

            if (!$result) {
                throw new Exception("Failed to add merchandise to the database.");
            }

            return ['success' => true, 'message' => 'Merchandise added successfully.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Update an existing merchandise
    public function updateMerchandise($merchandiseId, $data)
    {
        try {
            // Handle the image upload if a new image is provided
            if (!empty($data['image'])) {
                $data['image_path'] = $this->handleImageUpload($data['image']);
            }
            
            // Update the merchandise in the database
            $result = $this->merchandiseModel->updateMerchandise($merchandiseId, $data);

            if (!$result) {
                throw new Exception("Failed to update the merchandise.");
            }

            return ['success' => true, 'message' => 'Merchandise updated successfully.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Delete a merchandise
    public function deleteMerchandise($merchandiseId)
    {
        try {
            $result = $this->merchandiseModel->deleteMerchandise($merchandiseId);

            if (!$result) {
                throw new Exception("Failed to delete the merchandise.");
            }

            return ['success' => true, 'message' => 'Merchandise deleted successfully.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // View merchandise by a partner
    public function viewMerchandise($partnerId)
    {
        return $this->merchandiseModel->viewMerchandise($partnerId);
    }

    // Fetch merchandise details by merchandise_id for updating
    public function getMerchandiseDetails($merchandiseId)
    {
        $merchandise = $this->merchandiseModel->getMerchandiseDetails($merchandiseId);

        if ($merchandise) {
            return ['success' => true, 'merchandise' => $merchandise];
        } else {
            return ['success' => false, 'message' => 'Merchandise not found.'];
        }
    }

    // Fetch all merchandise for display
    public function getAllMerchandise()
    {
        return $this->merchandiseModel->getAllMerchandise();
    }

    public function getTotalEvents() {
        return $this->merchandiseModel->getTotalEvents();
    }

    // Fetch total sales
    public function getTotalSales() {
        return $this->merchandiseModel->getTotalSales();
    }

    public function getUpcomingEventName() {
        $event = $this->merchandiseModel->getClosestUpcomingEvent();
        return $event ? $event['event_name'] : 'No upcoming events found.';
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

// Handle the different actions (getMerchandiseDetails and updateMerchandise)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getMerchandiseDetails' && isset($_GET['merchandise_id'])) {
        // Fetch merchandise details
        $merchandiseController = new MerchandiseController();
        $result = $merchandiseController->getMerchandiseDetails($_GET['merchandise_id']);
        echo json_encode($result);
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['merchandise_id'])) {
        // Update merchandise
        $merchandiseController = new MerchandiseController();
        $result = $merchandiseController->updateMerchandise($_POST['merchandise_id'], $_POST);
        echo json_encode($result);
        exit;
    }
}
?>


