<?php
require_once '../settings/db_class.php';

class Category {
    private $db;

    public function __construct() {
        $this->db = new db_connection();
    }

    // Add a new category (theme)
    public function addCategory($name) {
        $name = mysqli_real_escape_string($this->db->db_conn(), $name);
        $query = "INSERT INTO themes (theme_name) VALUES ('$name')";
        return $this->db->db_query($query);
    }

    // Retrieve all categories (themes)
    public function listCategories() {
        $query = "SELECT * FROM themes";
        return $this->db->db_fetch_all($query);
    }

    // Delete a category (theme)
    public function deleteCategory($themeId) {
        $themeId = intval($themeId);
        $query = "DELETE FROM themes WHERE theme_id = $themeId";
        return $this->db->db_query($query);
    }
}
?>
