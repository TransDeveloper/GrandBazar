<?php
class Database{

    private static $host;
    private static $username;
    private static $password;
    private static $database;

    /**
     * @param $host
     * @param $username
     * @param $password
     * @param $database
     */
    public function __construct($host, $username, $password, $database){
        self::$host = $host;
        self::$username = $username;
        self::$password = $password;
        self::$database = $database;
    }

    /**
     * @return mysqli|void
     */
    public static function connect(){
        $db = new mysqli(self::getHost(), self::getUsername(), self::getPassword(), self::getDatabase());
        $db->set_charset('utf8');
        if($db->connect_errno){
            echo $db->connect_error;
            exit(0);
        }
        return $db;
    }

    /**
     * @param $sql
     * @return false|mysqli_stmt
     */
    public static function prepare($sql){
        $db = Database::connect();
        $stmt = $db->prepare($sql);
        return $stmt;
    }

    /**
     * @param $table
     * @param $row
     * @param $where
     * @return array|mixed
     */
    public static function getData($table, $row, $where = null){
        $db = Database::connect();
        $sql = "SELECT $row FROM $table";
        if($where != null){
            $sql .= " WHERE $where";
        }
        $result = $db->query($sql);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    /**
     * @param $query
     * @return bool|mysqli_result
     */
    public static function query($query){
        $db = Database::connect();
        $result = $db->query($query);
        $db->close();
        return $result;
    }

    /**
     * @param $string
     * @return string
     */
    public static function strip($string){
        $db = Database::connect();
        $string = $db->real_escape_string($string);
        $db->close();
        return $string;
    }

    /**
     * @param $db
     * @return void
     */
    public static function close_db($db){
        $db->close();
    }

    public static function getHost(): String { return self::$host; }
    public static function getDatabase(): String { return self::$database; }
    private static function getUsername(): String { return self::$username; }
    private static function getPassword(): String { return self::$password; }
}
