<?php
    class Post {
        private $db;

        public function __construct(){
            //as we will deal with database, we need to instantiate the database in the constructor
            $this->db = new Database;
        }

        public function getPosts(){
            $sql = 'SELECT * FROM posts';
            $this->db->query($sql);

            $results = $this->db->resultSet(); // return more than one row

            return $results;
        }
    }