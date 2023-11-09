<?php
    // db_handler class
    class db_handler {

        // Class atributes
        public $db_host = '';
        public $db_user = '';
        public $db_pass = '';
        public $db_database = '';

        private $conn = '';
        
        // Construct method
        function __construct() {
            $env = parse_ini_file('./../.env');
            $this -> db_host = $env['DB_HOST'];
            $this -> db_user = $env['DB_USER'];
            $this -> db_pass = $env['DB_PASS'];
            $this -> db_database = $env['DB_DATABASE'];

            $conn = new mysqli ($this->db_host, $this->db_user, $this->db_pass, $this->db_database);
            
            if ($conn -> connect_error) {
                throw new Exception ('Error connecting to database');
            }

            $this -> conn = $conn;
        }

        // Destruct method
        function __destruct() {
            $conn = $this -> conn;
            $conn -> close();
        }

        // Secure query
        function query ($query, $datatype, $data) {
            $conn = $this -> conn;
            $stmt = $conn -> prepare($query);
            $stmt -> bind_param($datatype, ...$data);
            $stmt -> execute();
            
            $result = $stmt -> get_result();
            $resultArray = [];

            while ($row = $result->fetch_assoc()) {
                array_push($resultArray, $row);
            }

            return $resultArray;
        }
        //Consulta sin ninguna condicion
        function query2($query) {
            $conn = $this->conn;
            $result = $conn->query($query);
            $resultArray = [];
        
            while ($row = $result->fetch_assoc()) {
                array_push($resultArray, $row);
            }
        
            return $resultArray;
        }

        // Secure execution
        function execute ($query, $datatype, $data) {
            try {
                $conn = $this -> conn;
                $stmt = $conn -> prepare($query);
                $stmt -> bind_param($datatype, ...$data);
                $stmt -> execute();
                return true;
            } catch (Exception $e) {
                return false;
            }
            
        }
    }
?>