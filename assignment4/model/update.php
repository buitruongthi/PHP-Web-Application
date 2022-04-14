<?php
// --------------------------------------------------------------------------------- model/update.php ---------------------------------------------------------------------------------
include "model/functions.php";
$id = "'".$_POST['id']."'";
// Append values based on what page user clicked
if ($page) {
    switch($page){
        case 'updateClient':
            $inputs = $clientInputs;
            $fields = $clientFields;
            $btnEdit = '<button type="submit" class="btn btn-primary" name="btnEditClient">Edit Client</button>';
            $table = 'client';
            $idType = 'idClient';
            $btnName = 'btnEditClient';
            break;
        case 'updateEmployee':
            $inputs = [
                'first_name' => '', 'last_name' => '', 'email' => '', 'cell_number' => '', 'position' => '',
            ];
            $fields = [
                ['first_name', 'last_name'], ['email'], ['cell_number', 'position'],
            ];
            $btnEdit = '<button type="submit" class="btn btn-primary" name="btnEditEmployee">Edit Employee</button>';
            $table = 'employee';
            $idType = 'idEmployee';
            $btnName = 'btnEditEmployee';
            break; 
        case 'updateNotification':
            $idType = 'idNotification';
            $table = 'notification';
            $inputs = $notificationInputs;
            $fields = $notificationFields;
            $btnName = 'btnEditNotification';
            $btnEdit = "<button type='submit' class='btn btn-primary' name=$btnName>Edit Notification</button>";
            break; 
        case 'updateClientEvent':
            $idType = 'idClientEvent';
            $table = 'client_event';
            $inputs = $clientEventInputs;
            $fields = $clientEventFields;
            $btnName = 'btnEditClientEvent';
            $btnEdit = "<button type='submit' class='btn btn-primary' name=$btnName>Edit Client Event</button>";
            $prepare = $db->prepare("SELECT * from client");
            $clients = ($prepare->execute()) ? $prepare->fetchAll(PDO::FETCH_ASSOC) : [];
            $prepare = $db->prepare("SELECT * from notification");
            $notifications = ($result = $prepare->execute()) ? $prepare->fetchAll(PDO::FETCH_ASSOC) : [];
            break;
    }
}
// Populate the input fields by using the $id that get sent from other view pages
$query = $db->prepare("SELECT * from $table where $idType=$id");
if ($query->execute() && $result = $query->fetch(PDO::FETCH_ASSOC)) {
    foreach ($inputs as $key => $value) {
        $inputs[$key] = $result[$key];
    }
}

// Edit button is clicked
if (isset($_POST[$btnName])) {
    $id = "'".$_POST['id']."'";
    validate($inputs, [], []);
    $querry = "UPDATE $table set ";
    foreach($inputs as $key=>$value) {
        $querry .= $key. "='$value', ";
    }
    // Remove space and comma after the last value
    $querry = substr($querry, 0, -2). " where $idType=$id";
    $querry = $db->prepare($querry);
    if($querry->execute()) {
        $logInfo['module'] = $table;
        $logInfo['action'] = 'update '. $table;
        databaseLog($logInfo);
        // updateClient -> viewClients, etc.
        $redirectPage = str_replace('update', 'view', $page). "s";
        echo '<meta http-equiv="refresh" content="0; URL=index.php?page='.$redirectPage.'"/>';
    }
}
?>
