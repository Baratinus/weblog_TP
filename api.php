<?php 

include('config.php');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');


// Exemple de traitement de différentes actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_number_post':
            getNumberPost();
            break;
        case 'get_number_user':
            getNumberUser();
            break;
        default:
            echo json_encode(['error' => 'Action non reconnue']);
    }
} else {
    echo json_encode(['error' => 'Aucune action spécifiée']);
}


function getNumberPost() {
    global $conn;

    $number_post = '';

    $result = mysqli_query($conn,"SELECT COUNT(*) as n FROM `posts` WHERE published=1;");

    if ($n = mysqli_fetch_assoc($result)) {
        $number_post = $n['n'];
    }
    else {
        $number_post = '?';
    }

    $data = [
        'number' => $number_post
    ];

    echo json_encode($data);
}


function getNumberUser() {
    global $conn;

    $number_user = '';

    $result = mysqli_query($conn,"SELECT COUNT(*) as n FROM `users`;");

    if ($n = mysqli_fetch_assoc($result)) {
        $number_user = $n['n'];
    }
    else {
        $number_user = '?';
    }

    $response = [
        'number' => $number_user
    ];
    
    echo json_encode($response);
}