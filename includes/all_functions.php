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
    
    $sql = "SELECT T.name AS 'name', T.id AS 'id' FROM `post_topic` AS PT INNER JOIN `topics` AS T ON PT.topic_id = T.id WHERE PT.post_id = ?;";

    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result,'i',$post_id);
    mysqli_stmt_execute($result);

    if ($reg_topic = mysqli_fetch_assoc(mysqli_stmt_get_result($result))) {
        return $reg_topic;
    }

    return NULL;
}

function getPost($slug)
{
    global $conn;

    $sql = "SELECT * FROM `posts` WHERE `slug` = ?;";

    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result,'s',$slug);
    mysqli_stmt_execute($result);

    if ($post = mysqli_fetch_assoc(mysqli_stmt_get_result($result))) {
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
    $sql = "SELECT * FROM `post_topic` AS PT INNER JOIN `posts` AS P ON PT.post_id = P.id WHERE PT.topic_id = ? AND P.published = 1;";
    
    // fetch all posts as an associative array called $posts
    $final_posts = array();
    
    
    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result,'i',$topic_id);
    mysqli_stmt_execute($result);

    $temp = mysqli_stmt_get_result($result);

    while($post = mysqli_fetch_assoc($temp)){
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

    $sql = "SELECT `name` FROM  `topics` WHERE id = ?;";

    $result = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($result,'i',$topic);
    mysqli_stmt_execute($result);

    if($topicname = mysqli_fetch_assoc(mysqli_stmt_get_result($result))){
        return $topicname['name'];
    }

    return NULL;
}