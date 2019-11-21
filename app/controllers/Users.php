<?php
    class Users extends Controller {

        public function __construct(){
            //load model that handle users stuff in db
            //by doing this, it's gonna check the models folder
            //for a file called User.php
            $this->userModel = $this->model('User');
        }

        public function register(){
            //check for post
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //process the form

                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                //init
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];
                // Validate email
                if(empty($data['email'])){
                    $data['email_err'] = 'Please enter email';
                } else {
                    //check email
                    if($this->userModel->findUserByEmail($data['email'])){ //check from the model
                        $data['email_err'] = 'Email is already taken';
                    }
                }

                // Validate Name
                if(empty($data['name'])){
                    $data['name_err'] = 'Please enter name';
                } 

                // Validate password
                if(empty($data['password'])){
                    $data['password_err'] = 'Please enter password';
                } elseif(strlen($data['password']) < 6) {
                    $data['password_err'] = 'Password must be ate least 6 characters';
                }

                // Validate confirm password
                if(empty($data['confirm_password'])){
                    $data['confirm_password_err'] = 'Please confirm password';
                } else {
                    if($data['password'] != $data['confirm_password']){
                        $data['confirm_password_err'] = 'Password do not match';
                    }
                }

                // Make sure errors are empty
                if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && 
                empty($data['confirm_passwowrd_err'])){
                    // Validate
                    //Hash the password to be stored in db
                    $data['password'] = password_hash($data['password'], PASSWORD_ARGON2ID);

                    //Register user
                    if($this->userModel->register($data)){
                        flash('register_success','You are registered and can log in');
                        redirect('users/login'); //after registration
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    // Load view with errors
                    $this->view('users/register', $data);
                }
                
            } else {
                //init data
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                //load view
                $this->view('users/register', $data);
            }
        }

        public function login(){
             //check for post
             if($_SERVER['REQUEST_METHOD'] == 'POST'){

                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                //init
                //saving $_POST values into this variable to be hadled thrgough all business rules in this controller
                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'email_err' => '',
                    'password_err' => '',
                ];

                // Validate email
                if(empty($data['email'])){
                    $data['email_err'] = 'Please enter email';
                }

                // Validate password
                if(empty($data['password'])){
                    $data['password_err'] = 'Please enter password';
                }
                
                // Check for user/email
                if ($this->userModel->findUserByEmail($data['email'])) {
                    //user found
                } else {
                    //User not found
                    $data['email_err'] = 'No user found';
                }
                
                //Make sure errors are empty
                if(empty($data['email_err']) && empty($data['password_err'])){
                    // Validate
                    // Check and set logged user
                    $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                    if ($loggedInUser) {
                        # create session
                        $this->createUserSession($loggedInUser);
                    } else {
                        //re render the form with error
                        $data['password_err'] = 'Password incorrect';

                        //load the view
                        $this->view('users/login', $data);
                    }
                } else {
                    // Load view with errors
                    $this->view('users/login', $data);
                }
                               

                //process the form
            } else {
                //init data
                $data = [
                    'email' => '',
                    'password' => '',
                    'email_err' => '',
                    'password_err' => '',
                ];

                //load view
                $this->view('users/login', $data);
            }
        }

        private function createUserSession($user){
            $_SESSION['user_id'] = $user->id; //This id commes from $row from the model User.php, function login()
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_name'] = $user->name;
            redirect('posts');

        }

        public function logout(){
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_name']);
            session_destroy();
            redirect('users/login');
        }
    }