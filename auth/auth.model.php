<?php 
    $CONFIG = [
        'servername' => "localhost",
        'username' => "root",
        'password' => '',
        'db' => 'storyweb'
    ];

    $conn = new mysqli($CONFIG["servername"], $CONFIG["username"], $CONFIG["password"], $CONFIG["db"]);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    function register($nume, $prenume, $username, $email, $password) {
        GLOBAL $conn;

        $userCheckStmt = $conn -> prepare('SELECT * FROM users WHERE username = ? or email = ?');
        $userCheckStmt -> bind_param('ss', $username, $email);

        $userCheckStmt -> execute();
        $results = $userCheckStmt -> get_result();
        $userCheckStmt -> close();

        
        if($results -> num_rows  > 0) {
            $firstRow = $results -> fetch_assoc();
            if($firstRow['username'] == $username) {
                return 0;
            }
            if($firstRow['email'] == $email) {
                return -1;
            }
        } 


        $hashedPassword = md5($password);
        $registerStmt = $conn -> prepare('INSERT into users(nume, prenume, username, email, password) VALUES(?,?,?,?,?);');
        $registerStmt -> bind_param('sssss', $nume, $prenume, $username, $email, $hashedPassword);
    
        $success = $registerStmt -> execute();

        $registerStmt -> close();

        return 1;
    }

    function login($username, $password) {
        GLOBAL $conn;

        $hashedPassword = md5($password);
        $loginStmt = $conn -> prepare('SELECT * FROM users WHERE username = ? AND password = ?');
        $loginStmt -> bind_param('ss', $username, $hashedPassword);

        $loginStmt -> execute();
        $results = $loginStmt -> get_result();
        $loginStmt -> close();

        
        if($results -> num_rows  === 1) {
            $firstRow = $results -> fetch_assoc();

            return new User($firstRow['id'], $firstRow['nume'], $firstRow['prenume'], $firstRow['username'],$firstRow['email'],
            $firstRow['password']);
        } 

        return NULL;
    }

    function getLoggedUser($username, $hashedPassword) {
        GLOBAL $conn;

        $loginStmt = $conn -> prepare('SELECT * FROM users WHERE username = ? AND password = ?');
        $loginStmt -> bind_param('ss', $username, $hashedPassword);

        $loginStmt -> execute();
        $results = $loginStmt -> get_result();
        $loginStmt -> close();

        
        if($results -> num_rows  === 1) {
            $firstRow = $results -> fetch_assoc();

            return new User($firstRow['id'], $firstRow['nume'], $firstRow['prenume'], $firstRow['username'],$firstRow['email'], $firstRow['password']);
        } 

        return NULL;
    }

    class User {
        public $id;
        public $nume;
        public $prenume;
        public $username;
        public $email;
        public $hashedPassword;

        function __construct($id, $nume, $prenume, $username, $email, $hashedPassword) {
            $this -> id = $id;
            $this -> nume = $nume;
            $this -> prenume = $prenume;
            $this -> username = $username;
            $this -> email = $email;
            $this -> hashedPassword = $hashedPassword;
        }
    }
?>