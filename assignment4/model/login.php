<?php
// --------------------------------------------------------------------------------- model/login.php ---------------------------------------------------------------------------------
include "model/functions.php";
$inputs = ['user_name' => '', 'password' => ''];

// Check if instructor account is already in the database
$prepare = $db->prepare("SELECT user_name FROM employee where user_name='Instructor'");
if($prepare->execute() && ($prepare->fetchColumn())) {
    displaySuccess('Please log in using username: Instructor Password: Instructor');
}
// Else Insert a new instructor account
else {
    $instructorPassword = "'".password_hash("Instructor", PASSWORD_DEFAULT)."'";
    $prepare = $db->prepare("INSERT INTO employee
    (first_name, last_name, email, cell_number, position, user_name, password, status) 
    VALUES ('First_name', 'Last_name', 'Email', '2021', 'Instructor', 'Instructor', $instructorPassword, 1)");
    $prepare->execute();
    displaySuccess('Please log in using username: Instructor Password: Instructor');
}

if (isset($_POST['btnLogin'])) {
    if (!empty($error_msg = validate($inputs, [], []))) {
        displayError($error_msg);
    }
    else {
        $username = "'".$inputs['user_name']."'";
        $querry = "SELECT password, status, idEmployee FROM employee where user_name=$username";
        $prepare = $db->prepare($querry);
        if($prepare->execute() && ($user = $prepare->fetch(PDO::FETCH_ASSOC))) {
            if ($user['status'] == '1') {
                // Password is correct
                if (password_verify($inputs['password'], $user['password'])){
                    $_SESSION['login'] = true;
                    // Save employee id for generating logs
                    $_SESSION['idEmployee'] = $user['idEmployee'];
                    echo '<meta http-equiv="refresh" content="0; URL=index.php"/>';
                }
                else {
                    displayError("Incorrect username/password");
                }
            }
            else {
                displayError("User is inactive");
            }
        }
        else {
            displayError('Database error or incorrect username/password');
        }
    }
}
?>