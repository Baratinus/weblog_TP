<?php
// variable declaration
$username = "";
$email = "";
$errors = array();
$password ="";
$password_confirmation="";
//REGISTER part of the programm
if (isset($_POST['register_btn'])) {
    $username = esc($_POST['username']);
    $password = esc($_POST['password']);
    $email =esc($_POST['email']);
    $password_confirmation =esc($_POST['password_confirmation']);
 
    if (empty($username)) {
         array_push($errors, "Username required");
    }
    if (empty($email)) {
         array_push($errors, "Email required");
    }
    if (empty($password)) {
         array_push($errors, "Password required");
    }
    if (empty($password_confirmation)) {
         array_push($errors, "Password confirmation required");
    }
    if($password!=$password_confirmation){
         array_push($errors, "the passwords do not match please try again");
    }
    if (empty($errors)) {

        //test existe déja
        $sql= "SELECT * FROM `users` WHERE username = '$username' AND email = '$email';";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)>=1){
            array_push($errors,"user already exists");
        }
        else{
            //insert new user
            $password = md5($password); // encrypt password
            $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'Author');";//to do timestamp
            $result = mysqli_query($conn, $sql);
        
            if ($result ==true){
                //$_SESSION
                header('location: index.php');
            }
            else{
                array_push($errors, "failed to create user");
            }
        }
    }
}

// LOG USER IN
if (isset($_POST['login_btn'])) {
    $username = esc($_POST['username']);
    $password = esc($_POST['password']);

    if (empty($username)) {
        array_push($errors, "Username required");
    }

    if (empty($password)) {
        array_push($errors, "Password required");
    }

    if (empty($errors)) {
        $password = md5($password); // encrypt password
        $sql = "SELECT * FROM users WHERE username='$username' and password='$password' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // get id of created user
            $reg_user_id = mysqli_fetch_assoc($result)['id'];

            //var_dump(getUserById($reg_user_id)); die();
            // put logged in user into session array
            $_SESSION['user'] = getUserById($reg_user_id);

            // if user is admin, redirect to admin area
            if (in_array($_SESSION['user']['role'], ["Admin", "Author"])) {
                $_SESSION['message'] = "You are now logged in";

                // redirect to admin area
                header('location: ' . BASE_URL . '/admin/dashboard.php');
                exit(0);
            } else {
                $_SESSION['message'] = "You are now logged in";

                // redirect to public area
                header('location: index.php');
                exit(0);
            }

        } else {
            array_push($errors, 'Wrong credentials');
        }
    }
}

// Get user info from user id
function getUserById($id)
{
    global $conn; //rendre disponible, à cette fonction, la variable de connexion $conn

    // requête qui récupère le user et son rôle
    $sql = "SELECT * FROM `users` WHERE id = $id";

    // la fonction php-mysql
    $result = mysqli_query($conn, $sql);

    // je met $result au format associatif
    $user = mysqli_fetch_assoc($result);

    return $user;
}

// escape value from form
function esc(String $value)
{
    // bring the global db connect object into function
    global $conn;
    $val = trim($value); // remove empty space sorrounding string
    $val = mysqli_real_escape_string($conn, $value);
    return $val;
}



