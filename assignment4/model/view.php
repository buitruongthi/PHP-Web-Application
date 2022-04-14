<?php
// --------------------------------------------------------------------------------- model/view.php ---------------------------------------------------------------------------------
include "model/functions.php";
if (isset($_POST['updateStatus']) && !isset($_POST['btnNo'])) {
    echo confirmForm();
}
// Append values based on what page user clicked
if ($page == "viewClients") {
    $table = 'client';
    $idType = 'idClient';
    $submitPage = 'updateClient';
    $header = [
        'id', 'company name', 'business number', 'first name', 'last name',
        'phone number', 'cell number', 'carriers', 'HST number', 'website', 'active', 'action'
    ];
    $querry = "SELECT * from $table";
}
if ($page == "viewEmployees") {
    $table = 'employee';
    $idType = 'idEmployee';
    $submitPage = 'updateEmployee';
    $header = [
        'id', 'first name', 'last name', 'email',
        'cell number', 'position', 'picture', 'status', 'action'
    ];
    $querry = "SELECT idEmployee, first_name, last_name, email, cell_number, position, picture, status
    from $table";
}
if ($page == "viewNotifications") {
    $table = 'notification';
    $idType = 'idNotification';
    $submitPage = 'updateNotification';
    $header = ['id', 'name', 'type', 'status', 'action'];
    $querry = "SELECT * from $table";
}
if ($page == "viewClientEvents") {
    $table = 'client_event';
    $idType = 'idClientEvent';
    $submitPage = 'updateClientEvent';
    $header = ['id', 'start date', 'frequency', 'status', 'client Id', 'notification id', 'action'];
    $querry = "SELECT * from $table";
}
if ($page == "viewLogs") {
    $table = 'log';
    $idType = '';
    $submitPage = '';
    $header = ['id', 'module', 'action', 'date time', 'IP Address', 'Employee Id'];
    $querry = "SELECT * from $table";
}

echo '<form class="create" method="POST">
<!-- The form for searching a record -->
<div style="margin-top: 10px;" class="gradient_bg d-flex justify-content-center p-3 mb-2 bg-dark text-white border  rounded">
    <div class="form-group">
        <div class="row">
            <div class="col">
                <input type="text" class="form-control" name="recordId" placeholder="Record Id">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary" name="btnSearch">Search</button>
            </div>
        </div>
    </div>
</div>
</form>';

// User clicks Yes button confirming they want to update the status
if (isset($_POST['btnYes'])) {
    updateStatus($table, $idType);
}
// User clicks search
if (isset($_POST['btnSearch'])) {
    $id = "'".filter_input(INPUT_POST, 'recordId', FILTER_SANITIZE_STRING)."'";
    $querry .= " where $idType=$id";
}

//$querry = "SELECT * FROM $table";
$prepare = $db->prepare($querry);
if ($prepare->execute()) {
    $logInfo['module'] = $table;
    $logInfo['action'] = 'view '. $table;
    databaseLog($logInfo);
    $results = $prepare->fetchAll(PDO::FETCH_ASSOC);
    if ($page == "viewClientEvents") {
        foreach ($results as $rowNum=>$row) {
            $results[$rowNum]['frequency'] = "Every ".$row['frequency']. " days";
        }
    }
    echo resultsToTable($results, $header, $idType, $submitPage, $page);
}

