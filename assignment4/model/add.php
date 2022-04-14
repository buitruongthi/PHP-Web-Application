<?php
// --------------------------------------------------------------------------------- model/add.php ---------------------------------------------------------------------------------
include "model/functions.php";
// Append values based on what page user clicked
if ($page == 'addClient') {
    $table = 'client';
    $inputs = $clientInputs;
    $fields = $clientFields;
    $btnAddName = 'btnAddClient';
    $btnAdd = "<button type='submit' class='btn btn-primary' name=$btnAddName>Add Client</button>";
}
if ($page == 'addEmployee') {
    $table = 'employee';
    $inputs = $employeeInputs;
    $fields = $employeeFields;
    $btnAddName = 'btnAddEmployee';
    $btnAdd = "<button type='submit' class='btn btn-primary' name=$btnAddName>Add Employee</button>";
}
if ($page == 'addNotification') {
    $table = 'notification';
    $inputs = $notificationInputs;
    $fields = $notificationFields;
    $btnAddName = 'btnAddNotification';
    $btnAdd = "<button type='submit' class='btn btn-primary' name=$btnAddName>Add Notification</button>";
}
if ($page == 'addClientEvent') {
    $table = 'client_event';
    $inputs = $clientEventInputs;
    $fields = $clientEventFields;
    $btnAddName = 'btnAddClientEvent';
    $btnAdd = "<button type='submit' class='btn btn-primary' name=$btnAddName>Add Client Event</button>";
    $prepare = $db->prepare("SELECT * from client");
    $clients = ($prepare->execute()) ? $prepare->fetchAll(PDO::FETCH_ASSOC) : [];
    $prepare = $db->prepare("SELECT * from notification");
    $notifications = ($result = $prepare->execute()) ? $prepare->fetchAll(PDO::FETCH_ASSOC) : [];
}

if (isset($_POST[$btnAddName])) {
    if(!empty($error_msg = validate($inputs, $optionalFields, $numericFields))) {
        displayError($error_msg);
    }
    else {
        $insertOk = true;
        if ($page == 'addEmployee') {
            $inputs['password'] = password_hash($inputs['password'], PASSWORD_DEFAULT);
        }
        else if ($page == 'addClientEvent') {
            $id = $inputs['Client_idClient'];
            $prepare = $db->prepare("SELECT status from client where idClient = $id");
            if ($prepare->execute() && ($result = $prepare->fetchColumn())) {
                // Inactive user
                if ($result == '0') {
                    $insertOk = false;
                }
            }
        }
        if ($insertOk) {
            // Notification is inactive (false/0) by default 
            $status = ($page == "addNotification") ? '0' : '1';
            $values = arrayToValues(array_values($inputs));
            $columns = implode(", ", array_keys($inputs));
            $querry = "INSERT INTO $table
            ($columns, status) 
            VALUES ($values, $status)";
            $prepare = $db->prepare($querry);
            if($prepare->execute()) {
                $logInfo['module'] = $table;
                $logInfo['action'] = 'add ' . $table;
                databaseLog($logInfo);
                $redirectPage = str_replace('add', 'view', $page). "s";
                echo '<meta http-equiv="refresh" content="0; URL=index.php?page='.$redirectPage.'"/>';
            }
            else {
                displayError('Error');
            }
        }
        else {
            displayError('Client is inactive');
        }
    }
}
