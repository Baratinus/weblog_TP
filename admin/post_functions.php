<?php
// Post variables
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$post_slug = "";
$body = "";
$featured_image = "";
$file_temp = "";
$post_topic = "";

/* - - - - - - - - - -
- Post actions
- - - - - - - - - - -*/

if (isset($_POST['create_post'])) {
    createPost($_POST);
}

else if (isset($_POST['update_post'])) {
    updatePost($_POST);
}

else if (isset($_GET["edit-posts"])) {
    $post_id = $_GET["edit-posts"];
    editPost();
}

else if (isset($_GET['publish-posts'])) {
    $post_id = $_GET['publish-posts'];
    togglePublishPost($post_id, "Change publish post");
}

/* - - - - - - - - - -
- Post functions
- - - - - - - - - - -*/
// get all posts from WEBLOG DATABSE
function getAllPosts() {
    global $conn ;

    $posts = array();

    $sql = "SELECT * FROM `posts`;";

    $result = mysqli_query($conn, $sql);

    while($post = mysqli_fetch_assoc($result)){
        if ($username = getPostAuthorById($post["user_id"])) {
            $post["author"] = $username;
            array_push($posts, $post);
        }
    }

    // fonction a besoin de la fonction getPostAuthorById
    // le code de getPostAuthorById est KDO

    return $posts ;
}

function checkFormPost($request_values){
    global $errors, $title, $featured_image, $topic_id, $body, $file_temp;

    if (empty($request_values["title"])) {
        array_push($errors, "Title required");
    }else{
        $title = $request_values["title"];
    }
    $image = $_FILES['featured_image'];
    if (empty($image)) {
        array_push($errors, "Image required");
    } else{
        $featured_image = $image['name'];
        $file_temp = $image['tmp_name'];
    }
    if (empty($request_values["body"])) {
        array_push($errors, "Body required");
    } else{
        $body = $request_values["body"];
    }
    if (empty($request_values["topic_id"])) {
        array_push($errors, "Topic required");
    } else {
        $topic_id = $request_values["topic_id"];
    }
}

function createPost($request_values) {
    global $conn, $errors, $title, $featured_image, $file_temp, $topic_id, $body, $published;

    checkFormPost($request_values);

    if (empty($errors)) {
        $slug = createSlug($title);
        $currentDate = date("Y-m-d h:i:s");
        move_uploaded_file($file_temp, ROOT_PATH."/static/images/".$featured_image);

        $user_id = getUserId();

        $sql_post = "INSERT INTO `posts`(`user_id`, `title`, `slug`, `views`, `image`, `body`, `published`, `updated_at`) VALUES ($user_id, '$title', '$slug', 0, '$featured_image', '$body', '$published', '$currentDate');";

        if ($result = mysqli_query($conn, $sql_post)) {
            $post_id = mysqli_insert_id($conn);
            $new_id = getNewIdPostsTopics();
            
            $sql_topic = "INSERT INTO `post_topic`(`id`,`post_id`, `topic_id`) VALUES ($new_id,$post_id,$topic_id);";
            
            if ($result = mysqli_query($conn, $sql_topic)) {
                $_SESSION['message'] = "Article created succesfully";
            }
            
        }

        header('location: posts.php');
        exit(0);
    }
    
}
    // get the author/username of a post
function getPostAuthorById($user_id){
    global $conn ;
    $sql = "SELECT `username` FROM `users` WHERE id=$user_id";
    $result = mysqli_query($conn, $sql) ;
    if ($result) {
        // return username
        return mysqli_fetch_assoc($result)['username'] ;
    } 
    else {
        return null ;
    }
}

function editPost(){
    global $conn, $title, $post_slug, $body, $post_id, $isEditingPost;

    $sql = "SELECT `id`, `title`, `slug`, `body` FROM `posts` WHERE id=$post_id;";

    $result = mysqli_query($conn, $sql);

    if($post = mysqli_fetch_assoc($result)){
        $title = $post['title'];
        $post_slug = $post['slug'];
        $body = $post['body'];
        $isEditingPost = true;
    }




}
function updatePost($request_values){
    global $conn, $errors, $post_id, $title, $featured_image, $topic_id, $body, $published;

    checkFormPost($request_values);

    if(empty($errors)){
        $slug = createSlug($title);
        $sql = "UPDATE `posts` SET `title`=$title, `featured-image`=$featured_image, `topic_id`=$topic_id, `body`=$body WHERE id=$post_id;";

        if ($result = mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Article updated succesfully";
        }

        header('location: posts.php');
        exit(0);
    }

    
}

// delete blog post
function deletePost($post_id){
    global $conn;
    $sql = "DELETE FROM posts WHERE id=$post_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Post successfully deleted";
        header("location: posts.php");
    exit(0);
    }
}
// toggle blog post : published→unpublished
function togglePublishPost($post_id, $message){
    global $conn;
    $sql = "UPDATE posts SET published=!published WHERE id=$post_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = $message;
        header("location: posts.php");
        exit(0);
    }
}

function getNewIdPostsTopics() {
    global $conn;

    $sql = "SELECT MAX(`id`) AS max_id FROM `post_topic`;";

    $result = mysqli_query($conn, $sql);

    if ($id = mysqli_fetch_assoc($result)) {
        return intval($id['max_id']) + 1;
    } else {
        return 0;
    }
}

function getUserId(){
    global $conn;

    $user_name = $_SESSION['user']['username'];

    $sql = "SELECT `id` FROM `users` WHERE `username`='$user_name';";

    $result = mysqli_query($conn, $sql);

    $userid = mysqli_fetch_assoc($result);

    return $userid['id'];
}
