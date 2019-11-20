<?php
    class User {
        private $db;

        public function __construct(){
            //initiate db
            $this->db = new Database;
        }

        //Register user
        public function register($data){
            //prepare query
            $sql = 'INSERT INTO users (name, email, password) VALUES(:name, :email, :password)';
            $this->db->query($sql);
            //Bind values
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);

            //Execute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }

        //Find user by email
        public function findUserByEmail($email){
            //Calling function/method query that comes from Database.php
            $sql = 'SELECT * FROM users WHERE email = :email';
            $this->db->query($sql);
            //bind values
            $this->db->bind(':email', $email);

            $row = $this->db->single();

            //Check row (check if the email)
            if($this->db->rowCount() > 0){
                return true;
            } else {
                return false;
            }
        }
    }