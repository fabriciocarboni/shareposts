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
            //That's why we set it here in the constructor. Wew can use it everywhere.
            //We define a variable postModel saying that to use the Post.php inside models folder
            $this->postModel = $this->Model('Post');
            $this->userModel = $this->Model('User');
        }

        public function index(){

            //grab posts
            $posts = $this->postModel->getPosts();
            $data = [
                'posts' => $posts //passing into this array the posts
            ];
            
            //load the view passing $data as argument
            $this->view('posts/index', $data);
        }

        public function add(){

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Sanitize POST
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'title' => trim($_POST['title']),
                    'body' => trim($_POST['body']),
                    'user_id' => $_SESSION['user_id'],
                    'title_err' => '',
                    'body_err' => ''
                ];

                //validate data
                if (empty($data['title'])) {
                    $data['title_err'] = 'Please enter title';
                }

                if (empty($data['body'])) {
                    $data['body_err'] = 'Please body text';
                }

                //Make sure no errors
                if (empty($data['title_err']) && empty($data['body_err'])) {
                    //validated
                    if ($this->postModel->addPost($data)) {
                        flash('post_message', 'Post Added');
                        redirect('posts');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    //load the view with errors
                    $this->view('posts/add', $data);
                }
            } else {
                $data = [
                    'title' => '',
                    'body' => ''
                ];
            }
                
            
            //load the view passing $data as argument
            $this->view('posts/add', $data);
        }

        public function edit($id){

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Sanitize POST
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'id'        => $id,
                    'title'     => trim($_POST['title']),
                    'body'      => trim($_POST['body']),
                    'user_id'   => $_SESSION['user_id'],
                    'title_err' => '',
                    'body_err'  => ''
                ];

                //validate data
                if (empty($data['title'])) {
                    $data['title_err'] = 'Please enter title';
                }

                if (empty($data['body'])) {
                    $data['body_err'] = 'Please body text';
                }

                //Make sure no errors
                if (empty($data['title_err']) && empty($data['body_err'])) {
                    //validated
                    if ($this->postModel->updatePost($data)) {
                        flash('post_message', 'Post Updated');
                        redirect('posts');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    //load the view with errors
                    $this->view('posts/edit', $data);
                }
            } else {
                //get existing post from model
                $post = $this->postModel->getPostById($id);

                //check for owner
                if ($post->user_id != $_SESSION['user_id']) {
                    redirect('posts');
                }

                $data = [
                    'id' => $id,
                    'title' => $post->title, //we just fetched that above in $post
                    'body' => $post->body
                ];
            }
            //load the view passing $data as argument
            $this->view('posts/edit', $data);
        }        

        //posts/show/id
        //controller/method/parameter
        public function show($id){
            $post = $this->postModel->getPostById($id);
            $user = $this->userModel->getUserById($post->user_id);
            
            $data = [
                'post' => $post,
                'user' => $user,
                
            ];

            $this->view('posts/show', $data);
        }

        public function delete($id){
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //get existing post from model
                $post = $this->postModel->getPostById($id);

                //check for owner
                if ($post->user_id != $_SESSION['user_id']) {
                    redirect('posts');
                }

                if ($this->postModel->deletePost($id)) {
                    flash('post_message', 'Post Removed');
                    redirect('posts');
                } else {
                    die('Something went wrong');
                }
            } else {
                redirect('posts');
            }
        }
    }