<?php
    class Posts extends Controller {

        public function __construct(){

            //check if there is a session created. 
            //we do not want to anybody not logged in see posts page
            //that's why we create this check inside the constructor
            if (!isLoggedIn()) { //the user is verified in session_helper.php
                //if not logged in
                redirect('users/login');
            }

            //When user is logged in, we need to load the page with the posts of that user
            //That's why we set it here in the constructor.
            //We define a variable postModel saying that to use the Post.php inside models folder
            $this->postModel = $this->Model('Post');
        }

        public function index(){
            $data = [];
            
            
            //load the view
            $this->view('posts/index');
        }
    }