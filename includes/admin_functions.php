<?php

// Admin user variables
$admin_id = 0;
$isEditingUser = false;
$username = "";
$email = "";
$role = "";

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

// Si l'utilisateur clique sur "update"
else if (isset($_POST['update_admin'])) {
    updateAdmin($_POST);
}

// Si l'utilisateur clique sur edit d'un admin
else if (isset($_GET['edit-admin'])) {
    $admin_id = $_GET['edit-admin'];
    editAdmin();
}

// Si l'utilisateur supprime un admin
else if (isset($_GET['delete-admin'])) {
    $admin_id = $_GET['delete-admin'];
    deleteAdmin();
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


/**
 * Checker la bonne présence de chaque élément du form
 * Si ok, rempli la variable global correspondante
 * Sinon, rempli $errors
 */
function checkForm($request_values) {
    global $errors, $username, $email, $role;
    
    if (empty($request_values["username"])) {
        array_push($errors, "Username required");
    } else {
        $username = $request_values["username"];
    }
    if (empty($request_values["email"])) {
        array_push($errors, "Email required");
    } else {
        $email = $request_values["email"];
    }
    if (empty($request_values["role_id"])) {
        array_push($errors, "Role required");
    } else {
        $role = $request_values["role_id"];
    }
}


/* * * * * * * * * * * * * * * * * * * * * * *
* - Receives new admin data from form
* - Create new admin user
* - Returns all admin users with their roles
* * * * * * * * * * * * * * * * * * * * * * */
function createAdmin($request_values){
    global $conn, $errors, $username, $email, $role;

    checkForm($request_values);

    // vérification ressemblence des mots de passe
    if ($request_values["password"] != $request_values["passwordConfirmation"]) {
        array_push($errors, "Same password required");
    }

    if (empty($errors)) {
        //insert new user
        $password = md5($request_values["password"]); // encrypt password
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role');";//to do timestamp

        $result = mysqli_query($conn, $sql);
    
        if ($result == true) {
            $_SESSION['message'] = "Admin user created successfully";
        }

        header('location: users.php');
        exit(0);
    }
}


/**
 * Suppression d'un admin à l'aide d'admin_id
 */
function deleteAdmin(){
    global $conn, $admin_id;
    
    $sql = "DELETE FROM users WHERE `users`.`id` = $admin_id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Admin user deleted successfully";
    }

    header('location: users.php');
    exit(0);
}


/* * * * * * * * * * * * * * * * * * * * *
* - Takes admin id as parameter
* - Fetches the admin from database
* - sets admin fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editAdmin(){
    global $conn, $username, $isEditingUser, $admin_id, $email, $role;
    
    $sql = "SELECT `username`, `email`, `role` FROM `users` WHERE `id` = $admin_id;";

    $result = mysqli_query($conn, $sql);
    
    if ($user = mysqli_fetch_assoc($result)) {
        $username = $user['username'];
        $email = $user['email'];
        $role = $user['role'];
        $isEditingUser = true;
    }
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* - Receives admin request from form and updates in database
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function updateAdmin($request_values){
    global $conn, $errors, $username, $isEditingUser, $admin_id, $email, $role;

    checkForm($request_values);
    
    if (empty($request_values["admin_id"])) {
        array_push($errors, "Erreur");
    } else {
        $admin_id = $request_values["admin_id"];
    }

    // vérification ressemblence des mots de passe
    if ($request_values["password"] != $request_values["passwordConfirmation"]) {
        array_push($errors, "Same password required");
    }

    if (empty($errors)) {
        $password = md5($request_values["password"]); // encrypt password
        $sql = "UPDATE `users` SET `username`='$username',`email`='$email',`role`='$role',`password`='$password' WHERE `users`.`id` = $admin_id;";

        echo $sql;

        $result = mysqli_query($conn, $sql);
    
        if ($result == true) {
            $_SESSION['message'] = "Admin user updated successfully";
        }

        header('location: users.php');
        exit(0);
    } else {
        $isEditingUser = true;
    }
}



function createTopic($request_values) {
    global $conn, $errors, $topic_name;

    if (empty($request_values["topic_name"])) {
        array_push($errors, "Topic name required");
    } else {
        $topic_name = $request_values["topic_name"];
    }

    if (empty($errors)) {
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role');";

        $result = mysqli_query($conn, $sql);
    
        if ($result == true) {
            $_SESSION['message'] = "Admin user created successfully";
        }

        header('location: users.php');
        exit(0);
    }
}


function editTopicr() {}


function updateTopic() {}


function deleteTopic() {}