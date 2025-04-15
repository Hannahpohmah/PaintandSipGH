<?php
// Check if the first file exists
if (file_exists('settings/db_class.php')) {
    require_once('settings/db_class.php');
} else {
    require_once('../settings/db_class.php');
}

class Merchandise extends db_connection
{
    // Add a new merchandise item
    public function addMerchandise($userId, $name, $description, $price, $quantity, $imagePath)
    {
        $sql = "INSERT INTO merchandise (user_id, merchandise_name, description, price, quantity_available, image_path) 
                VALUES ('$userId', '$name', '$description', '$price', '$quantity', '$imagePath')";
        return $this->db_query($sql);
    }

    // Update an existing merchandise item
    public function updateMerchandise($merchandiseId, $fields)
    {
        $setClause = [];
        foreach ($fields as $key => $value) {
            $setClause[] = "$key = '$value'";
        }
        $setClause = implode(', ', $setClause);

        $sql = "UPDATE merchandise SET $setClause WHERE merchandise_id = '$merchandiseId'";
        return $this->db_query($sql);
    }

    // Delete a merchandise item
    public function deleteMerchandise($merchandiseId)
    {
        $sql = "DELETE FROM merchandise WHERE merchandise_id = '$merchandiseId'";
        return $this->db_query($sql);
    }

    // View all merchandise items for a specific partner
    public function viewMerchandise($partnerId)
    {
        $sql = "SELECT * FROM merchandise WHERE user_id = '$partnerId'";
        return $this->db_fetch_all($sql);
    }

    // Fetch details of a specific merchandise item for updating
    public function getMerchandiseDetails($merchandiseId)
    {
        $sql = "SELECT * FROM merchandise WHERE merchandise_id = '$merchandiseId'";
        return $this->db_fetch_one($sql); // Assuming db_fetch_one fetches a single row
    }

    // View all available merchandise (if needed)
    public function getAllMerchandise()
    {
        $sql = "SELECT * FROM merchandise ORDER BY merchandise_name ASC";
        return $this->db_fetch_all($sql);
    }
    public function getTotalEvents()
    {
        $sql = "SELECT COUNT(*) AS total_events FROM events";
        return $this->db_fetch_one($sql); // Assuming db_fetch_one fetches a single row with a result
    }

    // Get total sales
    public function getTotalSales()
    {
        $sql = "SELECT SUM(total_amount) AS total_sales FROM `orders` WHERE status = 'Completed'";
        return $this->db_fetch_one($sql); // Assuming db_fetch_one fetches a single row with the result
    }

    public function getClosestUpcomingEvent() {
        $sql = "SELECT event_name FROM events WHERE event_date >= NOW() AND user_id = user_id  ORDER BY event_date ASC 
                  LIMIT 1";
        return $this->db_fetch_one($sql);
    }

   
}
?>
