<?php

function getPublishedPosts() {
    global $conn;
    $posts = array();

    $sql = "SELECT * FROM `posts` WHERE `published` = 1;";

    $result = mysqli_query($conn, $sql);
    
    while ($reg_post = mysqli_fetch_assoc($result)) {
        if ($topic = getPostTopic($reg_post["id"])) {
            $reg_post["topic"] = $topic["name"];
            $reg_post["topic_id"] = $topic["id"];
        }
        
        array_push($posts, $reg_post);
    }

    return $posts;
}


function getPostTopic($post_id){
    global $conn;
    
    $sql = "SELECT T.name AS 'name', T.id AS 'id' FROM `post_topic` AS PT INNER JOIN `topics` AS T ON PT.topic_id = T.id WHERE PT.post_id = $post_id;";

    $result = mysqli_query($conn, $sql);

    if ($reg_topic = mysqli_fetch_assoc($result)) {
        return $reg_topic;
    }

    return NULL;
}

function getPost($slug)
{
    global $conn;

    $sql = "SELECT * FROM `posts` WHERE `slug` = '$slug';";

    $result = mysqli_query($conn, $sql);

    if ($post = mysqli_fetch_assoc($result)) {
        if ($topic = getPostTopic($post["id"])) {
            $post["topic"] = $topic["name"];
        }
    }

    return $post;

}

function getAllTopics()
{
    global $conn;
    $topics = array();

    $sql = "SELECT * FROM `topics`;";

    $result = mysqli_query($conn, $sql);

    while($topic = mysqli_fetch_assoc($result)){
        array_push($topics, $topic);
    }

    return $topics;

}

/**
* This function returns the name and slug of a
* category in an array
*/
function getPublishedPostsByTopic($topic_id) {

    global $conn;
    $sql = "SELECT * FROM `post_topic` AS PT INNER JOIN `posts` AS P ON PT.post_id = P.id WHERE PT.topic_id = $topic_id AND P.published = 1;";
    
    $result = mysqli_query($conn, $sql);

    // fetch all posts as an associative array called $posts
    $final_posts = array();


    while($post = mysqli_fetch_assoc($result)){
        if ($topic = getPostTopic($post["id"])) {
            $post["topic"] = $topic["name"];
            $post["topic_id"] = $topic["id"];
        }

        array_push($final_posts, $post);
    }


    return $final_posts;
}

function getNameTopic($topic){

    global $conn;

    $sql = "SELECT `name` FROM  `topics` WHERE id = $topic;";

    $result = mysqli_query($conn, $sql);

    if($topicname = mysqli_fetch_assoc($result)){
        return $topicname['name'];
    }

    return NULL;
}