<?php
require 'config/database.php';
class DB
{
    private static $instance = null;
    public static function connection($conn)
    {
        $params = db($conn);
        $driver = $params[$conn]['driver'];
        $host = $params[$conn]['host'];
        $port = $params[$conn]['port'];
        $database = $params[$conn]['database'];
        $username = $params[$conn]['username'];
        $password = $params[$conn]['password'];

        switch ($driver) {
            case "sqlsrv":
                $auth = array("Database" => $database, "UID" => $username, "PWD" => $password);
                $cred = sqlsrv_connect($host . "," . $port, $auth);
                $connection = new Connection($cred);
                break;
            case "mysql":
                $connection = mysqli_connect($host, $username, $password, $database, $port);
                break;
        }

        return $connection;
    }
}

class Connection
{
    private $connection;
    public function __construct($connection)
    {
        $this->connection = $connection;
    }
    public function query($sql)
    {
        // Execute the SQL query
        $stmt = sqlsrv_query($this->connection, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

        // Check if query execution is successful
        // if ($stmt === false) {
        //     return array('error' => print_r(sqlsrv_errors(), true));
        // }

        return $stmt;
    }
    public function fetch_array($stmt)
    {
        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }
    public function num_rows($stmt)
    {
        return sqlsrv_num_rows($stmt);
    }
    public function free_stmt($stmt)
    {
        return sqlsrv_free_stmt($stmt);
    }
    public function close_connection($conn)
    {
        return sqlsrv_close($conn);
    }
}
