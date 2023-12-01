<?php

class Connection {
    /**
     * @var string
     */
    private string $db_servername;
    /**
     * @var string
     */
    private string $db_username;
    /**
     * @var string
     */
    private string $db_password;
    /**
     * @var string
     */
    private string $db_database;

    /**
     * Connection constructor.
     * Sets default values for database connection parameters.
     * @param void
     */
    public function __construct() { //local database
        $this->db_servername = "localhost";
        $this->db_username = "root";
        $this->db_password = "";
        $this->db_database = "omladinska-zadruga";

        if (false) { //server database
            $this->db_servername = "localhost";
            $this->db_username = "username";
            $this->db_password = "password";
            $this->db_database = "database";
        }
    }

    /**
     * Establishes a MySQLi database connection using the provided credentials.
     * @return mysqli|null A MySQLi object representing the connection or null on failure.
     */
    public static function connection(): ?mysqli {
        $obj = new Connection();

        // Create connection
        $conn = new mysqli($obj->db_servername, $obj->db_username, $obj->db_password, $obj->db_database);
        $conn->set_charset("utf8");

        // Check connection
        if ($conn->connect_error) {
            //die("Connection failed: " . $conn->connect_error);
            die("Connection error");
        }

        return $conn;
    }

    /**
     * Handles error logging and inserts error messages into the 'error_logs' table.
     * @param string $err The error message to log.
     * @return void
     */
    public static function errorLogin(string $err): void {
        //check if error_logs table exist
        $konekcija = Connection::connection();

        $val = $konekcija->query("SELECT * FROM error_logs");

        if ($val === false) {
            Connection::set("
            CREATE TABLE error_logs(
                error_logs_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                error_logs_date TIMESTAMP NOT NULL,
                error_logs_message VARCHAR(600) NOT NULL
            )
        ");
        }

        try {
            $konekcija_prepared = konekcija_prepared();
            $query = "INSERT INTO error_logs (error_logs_id,error_logs_date,error_logs_message) VALUES (NULL,:err_date,:message)";
            $sth = $konekcija_prepared->prepare($query);
            $date = date("Y/m/d h:i:sa");
            $sth->bindParam(':err_date', $date);
            $sth->bindParam(':message', $err);
            $sth->execute();
        } catch (PDOException $e) {}
    }

    /**
     * Performs a database backup and allows the backup file to be downloaded.
     * @return void
     */
    public static function databaseBackup(): void {
        $tables = '*';

        try {
            $link = Connection::connection();

            // Check connection
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit;
            }

            mysqli_query($link, "SET NAMES 'utf8'");

            //get all of the tables
            if ($tables == '*') {
                $tables = array();
                $result = mysqli_query($link, 'SHOW TABLES');
                while ($row = mysqli_fetch_row($result)) {
                    $tables[] = $row[0];
                }
            } else {
                $tables = is_array($tables) ? $tables : explode(',', $tables);
            }

            $return = '';
            //cycle through
            foreach ($tables as $table) {
                $result = mysqli_query($link, 'SELECT * FROM ' . $table);
                $num_fields = mysqli_num_fields($result);
                $num_rows = mysqli_num_rows($result);

                $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
                $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE ' . $table));
                $return .= "\n\n" . $row2[1] . ";\n\n";
                $counter = 1;

                //Over tables
                for ($i = 0; $i < $num_fields; $i++) {   //Over rows
                    while ($row = mysqli_fetch_row($result)) {
                        if ($counter == 1) {
                            $return .= 'INSERT INTO ' . $table . ' VALUES(';
                        } else {
                            $return .= '(';
                        }

                        //Over fields
                        for ($j = 0; $j < $num_fields; $j++) {
                            $row[$j] = addslashes($row[$j]);
                            $row[$j] = str_replace("\n", "\\n", $row[$j]);
                            if (isset($row[$j])) {
                                $return .= '"' . $row[$j] . '"';
                            } else {
                                $return .= '""';
                            }
                            if ($j < ($num_fields - 1)) {
                                $return .= ',';
                            }
                        }

                        if ($num_rows == $counter) {
                            $return .= ");\n";
                        } else {
                            $return .= "),\n";
                        }
                        ++$counter;
                    }
                }
                $return .= "\n\n\n";
            }

            //save file
            $fileName = 'db-backup-' . time() . '-' . (md5(implode(',', $tables))) . '.sql';

            $handle = fopen($fileName, 'w+');
            fwrite($handle, $return);
            if (fclose($handle)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($fileName));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($fileName));
                ob_clean();
                flush();
                readfile($fileName);
                unlink($fileName);
                exit;
            }
        } catch (Exception $e) {}
    }

    /**
     * Executes a raw SQL query and returns the result.
     * WARNING: Does not use prepared statements; suitable for static queries without user input.
     * @param string $sql The SQL query to execute.
     * @return mysqli_result|null The result of the query or null on error.
     */
    public static function get(string $sql): ?mysqli_result {
        $conn = Connection::connection();
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    /**
     * Executes a raw SQL query and returns the result.
     * WARNING: Does not use prepared statements; suitable for static queries without user input.
     * @param string $sql The SQL query to execute.
     * @return bool|null True on success, false on failure, or null on error.
     */
    public static function set(string $sql): ?bool {
        $conn = Connection::connection();
        $ans = $conn->query($sql);
        $conn->close();

        return $ans;
    }

    /**
     * Establishes a PDO database connection with UTF-8 character set.
     * @return PDO|null A PDO object representing the connection or null on failure.
     */
    public static function connectionPrepared(): ?PDO {
        $obj = new Connection();

        $dbh = new PDO('mysql:host=' . $obj->db_servername . ';dbname=' . $obj->db_database . ";charset=utf8mb4", $obj->db_username, $obj->db_password);
        /**
         * Use PDO::ERRMODE_EXCEPTION, to capture errors and write them to
         * a log file for later inspection instead of printing them to the screen.
         */
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbh;
    }

    /**
     * Executes a prepared SQL query and returns the result.
     * Uses prepared statements; suitable for dynamic queries with user input.
     * @param string $sql The SQL query to execute.
     * @param array $params An associative array of parameter values for prepared statement.
     * @return mysqli_result|null The result of the query or null on error.
     */
    public static function getP(string $sql, array $params = []): ?mysqli_result {
        $conn = Connection::connection();
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            if (!empty($params)) {
                // Bind parameters to the prepared statement
                $types = str_repeat('s', count($params)); // Assuming all parameters are strings
                $stmt->bind_param($types, ...$params);
            }

            // Execute the prepared statement
            $stmt->execute();

            // Get the result set
            $result = $stmt->get_result();

            // Close the statement and connection
            $stmt->close();
            $conn->close();

            return $result;
        } else {
            // Handle error (return an error message, log, etc.)
            return null;
        }
    }

    /**
     * Executes a prepared SQL query and returns the result.
     * Uses prepared statements; suitable for dynamic queries with user input.
     * @param string $sql The SQL query to execute.
     * @param array $params An associative array of parameter values for prepared statement.
     * @return bool|null True on success, false on failure, or null on error.
     */
    public static function setP(string $sql, array $params = []): ?bool {
        $conn = Connection::connection();
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            if (!empty($params)) {
                // Bind parameters to the prepared statement
                $types = str_repeat('s', count($params)); // Assuming all parameters are strings
                $stmt->bind_param($types, ...$params);
            }

            // Execute the prepared statement
            $success = $stmt->execute();

            // Close the statement and connection
            $stmt->close();
            $conn->close();

            return $success;
        } else {
            // Handle error (return an error message, log, etc.)
            echo $conn->error;
            return null;
        }
    }

    /**
     * @return string
     */
    public function getDbServername(): string {
        return $this->db_servername;
    }

    /**
     * @param string $db_servername
     */
    public function setDbServername(string $db_servername): void {
        $this->db_servername = $db_servername;
    }

    /**
     * @return string
     */
    public function getDbUsername(): string {
        return $this->db_username;
    }

    /**
     * @param string $db_username
     */
    public function setDbUsername(string $db_username): void {
        $this->db_username = $db_username;
    }

    /**
     * @return string
     */
    public function getDbPassword(): string {
        return $this->db_password;
    }

    /**
     * @param string $db_password
     */
    public function setDbPassword(string $db_password): void {
        $this->db_password = $db_password;
    }

    /**
     * @return string
     */
    public function getDbDatabase(): string {
        return $this->db_database;
    }

    /**
     * @param string $db_database
     */
    public function setDbDatabase(string $db_database): void {
        $this->db_database = $db_database;
    }
}