<?php

function getPublishedPosts() {
    global $conn;
    $posts = array();

    $sql = "SELECT * FROM `posts` WHERE `published` = 1;";

    $result = mysqli_query($conn, $sql);
    
    while ($reg_post = mysqli_fetch_assoc($result)) {
        if ($topic = getPostTopic($reg_post["id"])) {
            $reg_post["topic"] = $topic;
        }
        
        array_push($posts, $reg_post);
    }

    return $posts;
}


function getPostTopic($post_id){
    global $conn;
    
    $sql = "SELECT T.name AS 'name' FROM `post_topic` AS PT INNER JOIN `topics` AS T ON PT.topic_id = T.id WHERE PT.post_id = $post_id;";

    $result = mysqli_query($conn, $sql);

    if ($reg_topic = mysqli_fetch_assoc($result)) {
        return $reg_topic["name"];
    }

    return NULL;
   }