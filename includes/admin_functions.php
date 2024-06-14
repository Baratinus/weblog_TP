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


/* - - - - - - - - - -
- Topic actions
- - - - - - - - - - -*/
else if (isset($_POST['create_topic'])) {
    createTopic($_POST);
}

else if (isset($_POST['update_topic'])) {
    updateTopic($_POST);
}

else if (isset($_GET['edit-topic'])) {
    $topic_id = $_GET['edit-topic'];
    editTopic();
}

else if (isset($_GET['delete-topic'])) {
    $topic_id = $_GET['delete-topic'];
    deleteTopic();
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
        $currentDate = date("Y-m-d H:i:s");
        $sql = "INSERT INTO users (username, email, password, role, updated_at) VALUES ('$username', '$email', '$password', '$role', '$currentDate');";//to do timestamp

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
        $currentDate = date("Y-m-d H:i:s");
        $sql = "UPDATE `users` SET `username`='$username',`email`='$email',`role`='$role',`password`='$password', `updated_at`='$currentDate' WHERE `users`.`id` = $admin_id;";

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



/**
 * Récupérer l'ensemble des topics de la BDD
 */
function getTopics() {
    global $conn;

    $topics = array();

    $sql = "SELECT * FROM `topics`";
    
    $result = mysqli_query($conn, $sql);

    while ($topic = mysqli_fetch_assoc($result)) {
        array_push($topics, $topic);
    }

    return $topics;  
}


/**
 * Générer un nouvelle id pour un topic, incrémentation de 1 par rapport au dernier id
 */
function getNewIdTopics() {
    global $conn;

    $sql = "SELECT MAX(`id`) AS max_id FROM `topics`;";

    $result = mysqli_query($conn, $sql);

    if ($id = mysqli_fetch_assoc($result)) {
        return intval($id['max_id']) + 1;
    } else {
        return 0;
    }
}


function checkSlugExist($slug) {
    global $conn;

    $sql = "SELECT COUNT(*) AS N FROM `topics` WHERE `slug` LIKE '$slug';";

    $result = mysqli_query($conn, $sql);

    if ($n = mysqli_fetch_assoc($result)) {
        if ($n['N'] != '0') {
            return true;
        }
    }

    return false;
}


function createTopic($request_values) {
    global $conn, $errors, $topic_name;

    if (empty($request_values["topic_name"])) {
        array_push($errors, "Topic name required");
    } else {
        $topic_name = $request_values["topic_name"];
    }

    $topic_slug = createSlug($topic_name);

    if (checkSlugExist($topic_slug)) {
        array_push($errors, "Topic name already exist");
    }

    if (empty($errors)) {
        $topic_id = getNewIdTopics();
        $sql = "INSERT INTO `topics`(`id`, `name`, `slug`) VALUES ('$topic_id', '$topic_name','$topic_slug');";

        $result = mysqli_query($conn, $sql);
    
        if ($result == true) {
            $_SESSION['message'] = "Topic created successfully";
        }

        header('location: topics.php');
        exit(0);
    }
}


function editTopic() {
    global $conn, $topic_id, $isEditingTopic, $topic_name;
    
    $sql = "SELECT * FROM `topics` WHERE `id` = $topic_id;";

    $result = mysqli_query($conn, $sql);
    
    if ($topic = mysqli_fetch_assoc($result)) {
        $topic_id = $topic['id'];
        $topic_name = $topic['name'];
        $isEditingTopic = true;
    }
}


function updateTopic($request_values) {
    global $conn, $errors, $topic_id, $isEditingTopic, $topic_name;

    if (empty($request_values["topic_name"])) {
        array_push($errors, "Topic name required");
    } else {
        $topic_name = $request_values["topic_name"];
    }
    if (empty($request_values["topic_id"])) {
        array_push($errors, "Topic name required");
    } else {
        $topic_id = $request_values["topic_id"];
    }

    $topic_slug = createSlug($topic_name);

    if (checkSlugExist($topic_slug)) {
        array_push($errors, "Topic name already exist");
    }

    if (empty($errors)) {
        $sql = "UPDATE `topics` SET `name`='$topic_name',`slug`='$topic_slug' WHERE `topics`.`id` = $topic_id;";

        echo $sql;

        $result = mysqli_query($conn, $sql);
    
        if ($result == true) {
            $_SESSION['message'] = "Topic updated successfully";
        }

        header('location: topics.php');
        exit(0);
    } else {
        $isEditingTopic = true;
    }
}


function deleteTopic() {
    global $conn, $topic_id;
    
    $sql = "DELETE FROM `topics` WHERE `topics`.`id` = $topic_id;";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Topic deleted successfully";
    }

    header('location: topics.php');
    exit(0);
}


function createSlug($title){
    $slug = strtolower($title);
    $slug = str_replace(" ", "-", $slug);
    return $slug;
}

function publishedpostammount(){
    global $conn, $errors;

    $result=mysqli_query($conn,"SELECT COUNT(*) as n FROM `posts` WHERE published=1;");
    if ($n = mysqli_fetch_assoc($result)) {
        return $n['n'];
    }
    else {
        return '?';
    }
}

function newuserammount(){
    global $conn, $errors;

    $result=mysqli_query($conn,"SELECT COUNT(*) as n FROM `users` WHERE created_at >= CURRENT_DATE-INTERVAL 14 DAY; ");
    if ($n = mysqli_fetch_assoc($result)) {
        return $n['n'];
    }
    else {
        return '?';
    }
}