<?php
// Include database credentials
require('db_cred.php');

/**
 * Database Connection Class for PaintSipGH
 * Handles database operations such as querying, fetching, and counting.
 * @version 2.0
 */
class db_connection
{
    // Properties
    private $db = null; // Database connection
    private $results = null; // Query results

    // Establish a database connection
    /**
     * Connects to the database
     * @return bool True if connection succeeds, false otherwise
     */
    function db_connect()
    {
        $this->db = mysqli_connect(SERVER, USERNAME, PASSWD, DATABASE);

        // Check the connection
        if (mysqli_connect_errno()) {
            die("Database connection failed: " . mysqli_connect_error());
            return false;
        } else {
            return true;
        }
    }

    // Get database connection object
    /**
     * Provides the active database connection
     * @return object|bool The connection object if successful, false otherwise
     */
    function db_conn()
    {
        if ($this->db_connect()) {
            return $this->db;
        } else {
            return false;
        }
    }

    // Execute a SQL query
    /**
     * Executes a SQL query
     * @param string $sqlQuery The SQL query to execute
     * @return bool True if query succeeds, false otherwise
     */
    function db_query($sqlQuery)
    {
        if (!$this->db_connect()) {
            return false;
        }

        $this->results = mysqli_query($this->db, $sqlQuery);

        if ($this->results === false) {
            return false;
        } else {
            return true;
        }
    }

    // Safely execute a SQL query with escaping to prevent SQL injection
    /**
     * Executes a query with real escape string to prevent SQL injection
     * @param string $sqlQuery The SQL query to execute
     * @return bool True if query succeeds, false otherwise
     */
    function db_query_escape_string($sqlQuery)
    {
        if (!$this->db_connect()) {
            return false;
        }

        $safeQuery = mysqli_real_escape_string($this->db, $sqlQuery);
        $this->results = mysqli_query($this->db, $safeQuery);

        if ($this->results === false) {
            return false;
        } else {
            return true;
        }
    }

    // Fetch one record from the database
    /**
     * Fetches a single record from the database
     * @param string $sql The SQL SELECT query
     * @return array|bool Associative array of the record or false if no record is found
     */
    function db_fetch_one($sql)
    {
        if (!$this->db_query($sql)) {
            return false;
        }

        return mysqli_fetch_assoc($this->results);
    }

    // Fetch all records from the database
    /**
     * Fetches all records from the database
     * @param string $sql The SQL SELECT query
     * @return array|bool Array of records or false if no records are found
     */
    function db_fetch_all($sql)
    {
        if (!$this->db_query($sql)) {
            return false;
        }

        return mysqli_fetch_all($this->results, MYSQLI_ASSOC);
    }

    // Count rows returned by the query
    /**
     * Counts the number of rows in the result set
     * @return int|bool Number of rows or false if the query fails
     */
    function db_count()
    {
        if ($this->results === null || $this->results === false) {
            return false;
        }

        return mysqli_num_rows($this->results);
    }

    // Get the last inserted ID
    /**
     * Returns the ID of the last inserted record
     * @return int|bool Last inserted ID or false if no ID exists
     */
    function db_last_insert_id()
    {
        if ($this->db !== null) {
            return mysqli_insert_id($this->db);
        }

        return false;
    }

    // Close the database connection
    /**
     * Closes the database connection
     * @return void
     */
    function db_close()
    {
        if ($this->db !== null) {
            mysqli_close($this->db);
        }
    }
}
