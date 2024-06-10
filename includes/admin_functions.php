<?php

// Admin user variables
$admin_id = 0;
$isEditingUser = false;
$username = "";
$email = "";

// Topics variables
$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";

// general variables
$errors = [];

/* - - - - - - - - - -
- Admin users actions
- - - - - - - - - - -*/

// if user clicks the create admin button
if (isset($_POST['create_admin'])) {
    createAdmin($_POST);
}



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* - Returns all admin users and their corresponding roles
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function getAdminUsers(){
    global $conn, $roles;

    $users = array();

    $sql = "SELECT * FROM `users`";
    
    $result = mysqli_query($conn, $sql);

    while ($user = mysqli_fetch_assoc($result)) {
        array_push($users, $user);
    }

    return $users;   
}


/* * * * * * * * * * * * * *
* - Returns all admin roles
* * * * * * * * * * * * * */
function getAdminRoles(){
    global $conn;
    
    $roles = array();

    $sql = "SELECT id, name AS role FROM `roles`;";
    
    $result = mysqli_query($conn, $sql);

    while ($role = mysqli_fetch_assoc($result)) {
        array_push($roles, $role);
    }

    return $roles;
}


/* * * * * * * * * * * * * * * * * * * * * * *
* - Receives new admin data from form
* - Create new admin user
* - Returns all admin users with their roles
* * * * * * * * * * * * * * * * * * * * * * */
function createAdmin($request_values){
    global $conn, $errors, $username, $email;
    
    if (empty($request_values["username"])) {
        array_push($errors, "Username required");
    }
    if (empty($request_values["email"])) {
        array_push($errors, "Email required");
    }
    if (empty($request_values["role_id"])) {
        array_push($errors, "Role required");
    }

    // vérification ressemblence des mots de passe
    if ($request_values["password"] != $request_values["passwordConfirmation"]) {
        array_push($errors, "Same password required");
    }

    if (empty($errors)) {
        //insert new user
        $username = $request_values["username"];
        $email = $request_values["email"];
        $role = $request_values["role_id"];
        $password = md5($request_values["password"]); // encrypt password
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role');";//to do timestamp

        $result = mysqli_query($conn, $sql);
    
        if ($result == true) {
            $_SESSION['message'] = "Admin user created successfully";
        }
    }

    header('location: users.php');
    exit(0);
}