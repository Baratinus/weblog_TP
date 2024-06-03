<?php

function getPublishedPosts() {
    global $conn;
    $posts = array();

    $sql = "SELECT * FROM `posts` WHERE `published` = 1;";

    $result = mysqli_query($conn, $sql);
    
    while ($reg_post = mysqli_fetch_assoc($result)) {
        array_push($posts, $reg_post);
    }

    return $posts;
}