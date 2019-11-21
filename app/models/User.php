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

        //Login user
        public function login($email, $password){
            $sql = 'SELECT * FROM users WHERE email = :email';
            $this->db->query($sql);
            $this->db->bind(':email', $email);

            $row = $this->db->single(); //call single function inside Database.php (retturn single row)

            $hashed_password = $row->password; //give us the hash password
            
            //use this function to verify the password written in the form against hashed password found in db
            if (password_verify($password, $hashed_password)) { 
                # if match
                return $row;
            } else {
                return false;
            }
        }
    

        //Find user by email
        public function findUserByEmail($email){
            $sql = 'SELECT * FROM users WHERE email = :email';
            $this->db->query($sql); //Calling function/method query that comes from Database.php
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