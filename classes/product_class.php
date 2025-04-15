<?php
// Check if the first file exists
if (file_exists('settings/db_class.php')) {
    require_once('settings/db_class.php');
} else {
    require_once('../settings/db_class.php');
}

class Product extends db_connection
{

    public function db_escape_string($string)
{
    return mysqli_real_escape_string($this->db_conn(), $string);
}

    // Add a new event
    public function addEvent($userId, $name, $description, $location, $date, $price, $themeId, $imagePath, $totalSpots)
    {
        $sql = "INSERT INTO events (user_id, event_name, description, location, event_date, ticket_price, theme_id, image_path, total_spots, available_spots) 
                VALUES ('$userId', '$name', '$description', '$location', '$date', '$price', '$themeId', '$imagePath', '$totalSpots', '$totalSpots')";
        return $this->db_query($sql);
    }

    // Update an existing event
    public function updateEvent($eventId, $fields)
    {
        $setClause = [];
        foreach ($fields as $key => $value) {
            $setClause[] = "$key = '$value'";
        }
        $setClause = implode(', ', $setClause);

        $sql = "UPDATE events SET $setClause WHERE event_id = '$eventId'";
        return $this->db_query($sql);
    }

    // Delete an event
    public function deleteEvent($eventId)
    {
        $sql = "DELETE FROM events WHERE event_id = '$eventId'";
        return $this->db_query($sql);
    }

    // View events by a partner
    public function viewEvents($partnerId)
    {
        $sql = "SELECT * FROM events WHERE user_id = '$partnerId'";
        return $this->db_fetch_all($sql);
    }

    // Fetch a specific event's details for updating
    public function getEventDetails($eventId)
    {
        $sql = "SELECT * FROM events WHERE event_id = '$eventId'";
        return $this->db_fetch_one($sql); // Assuming db_fetch_one fetches a single row
    }

    public function getAllEvents()
{
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Retrieve user type and user ID from the session
    $userType = $_SESSION['userType'] ?? null;
    $userId = $_SESSION['user_id'] ?? null;

    // Base SQL query for all events
    $sql = "SELECT e.*, t.theme_name 
            FROM events e
            LEFT JOIN themes t ON e.theme_id = t.theme_id
            WHERE e.available_spots > 0";

    // Check if the user is a partner
    if ($userType === 'partner') {
        // Retrieve partner ID from the database
        $partnerId = $userId;
        if ($partnerId === null) {
            throw new Exception("No partner ID found for the given user.");
        }

        // Modify query to filter events for the partner
        $sql .= " AND e.partner_id = $partnerId";
    }

    // Default: Fetch all events for customers or other user types
    $sql .= " ORDER BY e.event_date ASC";

    return $this->db_fetch_all($sql);
}
    // Get partner-specific events with total bookings
    public function getPartnerEvents($partnerId)
    {
        $sql = "
            SELECT 
                e.event_id,
                e.event_name,
                e.description,
                e.location,
                e.event_date,
                e.ticket_price,
                e.total_spots,
                e.available_spots,
                e.image_path,
                COALESCE(SUM(od.quantity), 0) AS total_bookings
            FROM events e
            LEFT JOIN order_details od ON e.event_id = od.event_id
            WHERE e.user_id = ?
            GROUP BY e.event_id
        ";

        $stmt = $this->db_conn()->prepare($sql); // Prepared statement
        if (!$stmt) {
            throw new Exception("Failed to prepare query: " . $this->db_conn()->error);
        }

        $stmt->bind_param("i", $partnerId); // Bind partner ID
        $stmt->execute();
        $result = $stmt->get_result();

        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        return $events;
    }

    public function searchEvents($filters)
{
    $conditions = [];
    if (!empty($filters['event_name'])) {
        $conditions[] = "e.event_name LIKE '%" . $this->db_escape_string($filters['event_name']) . "%'";
    }
    if (!empty($filters['location'])) {
        $conditions[] = "e.location LIKE '%" . $this->db_escape_string($filters['location']) . "%'";
    }
    if (!empty($filters['date'])) {
        $conditions[] = "e.event_date = '" . $this->db_escape_string($filters['date']) . "'";
    }
    if (!empty($filters['theme'])) {
        $conditions[] = "t.theme_name LIKE '%" . $this->db_escape_string($filters['theme']) . "%'";
    }

    $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

    $sql = "SELECT e.*, t.theme_name 
            FROM events e
            LEFT JOIN themes t ON e.theme_id = t.theme_id
            $whereClause
            ORDER BY e.event_date ASC";

    return $this->db_fetch_all($sql);
}


public function getAllLocations()
{
    $sql = "SELECT * FROM localization ORDER BY region_name ASC";
    return $this->db_fetch_all($sql);
}

public function getAllThemes()
{
    $sql = "SELECT * FROM themes ORDER BY theme_name ASC";
    return $this->db_fetch_all($sql);
}


}
?>
?>
