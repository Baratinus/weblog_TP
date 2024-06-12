<?php
// Post variables
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$post_slug = "";
$body = "";
$featured_image = "";
$post_topic = "";

/* - - - - - - - - - -
- Post actions
- - - - - - - - - - -*/

if (isset($_POST['update_post'])) {
    updatePost($_POST);
}

// Si l'utilisateur clique sur "update"
else if (isset($_POST['create_post'])) {
    createPost($_POST);
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
function createPost($request_values) {
    global $conn, $errors, $title, $featured_image, $topic_id, $body, $published;

    if (empty($request_values["title"])) {
        array_push($errors, "Title required");
    }
    $image = $_FILES;
    if (empty($image)) {
        array_push($errors, "Image required");
    }
    if (empty($request_values["body"])) {
        array_push($errors, "Body required");
    }
    if (empty($request_values["topic_id"])) {
        array_push($errors, "Topic required");
    }

    if (empty($errors)) {
        $title = $request_values["title"];
        $featured_image = $image["featured_image"]['name'];
        $body = $request_values["body"];
        $topic_id = $request_values["topic_id"];
        $slug = createSlug($title);
        $currentDate = date("Y-m-d h:i:s");

        $sql = "INSERT INTO `posts`(`user_id`, `title`, `slug`, `views`, `image`, `body`, `published`, `updated_at`) VALUES (1, '$title', '$slug', 0, '$featured_image', '$body', '$published', '$currentDate');";

        echo $sql;
        
    
        if ($result = mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Article created succesfully";
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

function editPost($role_id){
    global $conn, $title, $post_slug, $body, $isEditingPost, $post_id;
}
function updatePost($request_values){
    global $conn, $errors, $post_id, $title, $featured_image, $topic_id, $body, $published;
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

function createSlug($title){
    $slug = strtolower($title);
    $slug = str_replace(" ", "-", $slug);
    return $slug;
}


