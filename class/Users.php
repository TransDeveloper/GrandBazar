<?php
class Users{

    /*
     *
     *  Data is sanitized before being inserted into the database.
     *
     */

    /**
     * @var string 
     */
    protected static $key = "2bc0506c4434b1d530c2";
    /**
     * @var string 
     */
    public $salt = "25@%TET";

    /**
     * @param $username
     * @return bool
     */
    public static function user_exists($username){
        $db = Database::connect();

        $username = $db->real_escape_string($username);

        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $db->query($sql);
        $db->close();
        if($result->num_rows > 0){
            return true;
        }
        return false;
    }

    /**
     * @param $username
     * @param $password
     * @param $first_name
     * @param $last_name
     * @param $email
     * @return bool
     */
    public static function register($username, $password, $first_name, $last_name, $email){
        if(self::user_exists($username)){
            return false;
        }else{
            $db = Database::connect();
            $key = self::$key;

            $username = $db->real_escape_string($username);
            $password = $db->real_escape_string($password);
            $first_name = $db->real_escape_string($first_name);
            $last_name = $db->real_escape_string($last_name);
            $email = $db->real_escape_string($email);
            $password = $db->real_escape_string($password);

            $sql = "INSERT INTO users (username, password, firstname, lastname, email) VALUES ('$username', AES_ENCRYPT('$password','$key'), '$first_name', '$last_name', '$email')";
            $result = $db->query($sql);
            $db->close();
            if($result){
                return true;
            }
            return false;
        }
    }
    
    /**
     * @param $user
     * @param $row
     * @return false|mixed
     */
    public static function getUserData($user, $row){
        $db = Database::connect();

        $user = $db->real_escape_string($user);

        $sql = "SELECT * FROM users WHERE username = '$user'";
        $result = $db->query($sql);
        $db->close();
        if($result->num_rows > 0){
            $rows = $result->fetch_assoc();
            return $rows[$row];
        }
        return false;
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function login($username, $password){
        if(self::user_exists($username)){
            $db = Database::connect();
            $key = self::$key;

            $username = $db->real_escape_string($username);
            $password = $db->real_escape_string($password);

            $sql = "SELECT * FROM users WHERE username = '$username' AND AES_DECRYPT(password,'$key') = '$password';";
            $result = $db->query($sql);
            $db->close();
            $row  = $result->fetch_assoc();
            if(!is_null($row)){
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function updatePassword($username, $password){
        if(self::user_exists($username)){
            $db = Database::connect();
            $key = self::$key;

            $username = $db->real_escape_string($username);
            $password = $db->real_escape_string($password);

            $sql = "UPDATE users SET password = AES_ENCRYPT('$password','$key') WHERE username = '$username'";
            $result = $db->query($sql);
            $db->close();
            if($result){
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @param $username
     * @param $email
     * @return bool
     */
    public function updateEmail($username, $email){
        if(self::user_exists($username)){
            $db = Database::connect();

            $username = $db->real_escape_string($username);
            $email = $db->real_escape_string($email);

            $sql = "UPDATE users SET email = '$email' WHERE username = '$username'";
            $result = $db->query($sql);
            $db->close();
            if($result){
                return true;
            }
            return false;
        }
        return false;
    }
}
