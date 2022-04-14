<?php
// --------------------------------------------------------------------------------- functions.php ---------------------------------------------------------------------------------
// Members:
// Fred Pedersen - 101378456
// Truong Thi Bui - 101300750
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("America/Toronto");

// Database connection setup
$db_info = 'mysql:host=127.0.0.1;dbname=f1300750_test';
$username = 'f1300750';
$password = 'Coinagb1';
try {
    $db = new PDO($db_info, $username, $password);
} catch (PDOException $e) {
    $error_msg = $e->getMessage();
    echo "PDO Database not connect. Error: " . $error_msg . "<br>";
}

// The associative arrays contain keys correspond with columns in the tables in the database
// Their values are from user and are filtered with validate() function
// Client
$clientInputs = [
    'company_name' => '', 'business_number' => '', 'first_name' => '', 'last_name' => '',
    'phone_number' => '', 'cell_number' => '', 'carrier' => '', 'HST' => '', 'website' => ''
];
$clientFields = [
    ['company_name', 'business_number'], ['first_name', 'last_name'], ['phone_number', 'cell_number'],
    ['carrier', 'HST'], ['website']
];
$optionalFields = ['HST', 'website'];
$numericFields = ['business_number', 'phone_number', 'cell_number', 'HST', 'frequency'];
// Notification type
$notificationInputs = ['name' => '', 'type' => ''];
$notificationFields = [['name'], ['type']];
// Employee
$employeeInputs = [
    'first_name' => '', 'last_name' => '', 'email' => '', 'cell_number' => '', 'position' => '',
    'user_name' => '', 'password' => ''
];
$employeeFields = [
    ['first_name', 'last_name'], ['email'], ['cell_number', 'position'],
    ['user_name'], ['password'] // picture not included :(
];
// Client Event
$clientEventInputs = ['start_date' => '', 'frequency' => '', 'Client_idClient' => '', 'Notification_idNotification' => ''];
$clientEventFields = [['frequency']];
// Log
$logInfo = [
    // Request time is not the exact time user make a change to database but it's passable
    'module' => '', 'action' => '', 'date_time' => date("Y/m/d h:i:s a", $_SERVER['REQUEST_TIME']),
    'ip' => $_SERVER['REMOTE_ADDR'], 'employee_idEmployee' => ''
];
$logInfo['employee_idEmployee'] = (isset($_SESSION['idEmployee'])) ? $_SESSION['idEmployee'] : '';
function generateTextFields($fields, $valuesFromUser)
{
    $fieldRows = "";
    // These variable names are really confusing but we couldn't think of any better names...
    foreach ($fields as $fieldGroup) {
        $type = 'text';
        // Array means multiple columns
        if (is_array($fieldGroup)) {
            $fieldColumn = "";
            foreach ($fieldGroup as $field) {
                // Make shift solution for changing text to password
                $type = ($field == 'password') ? "password" : "text";
                $value = (empty($valuesFromUser[$field])) ? '' : "value='".$valuesFromUser[$field]."'";
                $fieldColumn .=
                    "<div class='col'>
                        <label for='$field'>" . ucfirst($field) . "</label>
                        <input type=$type class='form-control' id='$field' name='$field' $value>
                    </div>";
            }
            $fieldRows .= "<div class='row'>$fieldColumn</div>";
        } else {
            $type = ($fieldGroup == 'password') ? "password" : "text";
            $value = (empty($valuesFromUser[$fieldGroup])) ? '' : "value='".$valuesFromUser[$fieldGroup]."'";
            $fieldRows .= "<div class='row'><div class='col'>
                <label for='$fieldGroup'>$fieldGroup</label>
                <input type=$type class='form-control' id='$fieldGroup' name='$fieldGroup' $value>
                </div></div>";
        }
    }
    return $fieldRows;
}

function generateSelect($rows, $idType, $key1, $key2, $selectedId="") {
    $options = "";
    foreach ($rows as $row) {
        // Only show active row
        if ($row['status'] == '1') {
            // select the option if the row id matches the selected id
            $selected = ($row[$idType] == $selectedId) ? "selected" : "";
            $options .= "<option value=".$row[$idType]." $selected>#". $row[$idType]. " " .$row[$key1]. " " .$row[$key2] ."</option>";
        } 
    }
    if ($idType == "idClient") {
        $idType = "Client_idClient";
        $label = "Client Id";
    }
    else {
        $label = "Notification Id";
        $idType = "Notification_idNotification";
    }
    return "<div class='row'><div class='col'>
            <label for=$idType>$label</label>  
            <select class=\"form-control\" id=$idType name=$idType>$options</select>
            </div></div>";
}

function validateNumber($num, $maxLength)
{
    return (is_numeric($num) && (strlen($num) <= $maxLength));
}

// Inefficient and dumb but it works...
// inputs array is passed by refercence so its values will updated inside the function
function validate(&$inputs, $optionalInputs, $numericInputs)
{
    $maxNumDigits = 10;
    $error_msg = "";
    foreach ($inputs as $key => $val) {
        $val = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
        // Value is optional
        if (in_array($key, $optionalInputs)) {
            // Value is optional + numeric. Will generate an error if field is not empty and is an invalid number
            if (in_array($key, $numericInputs) && !empty($val) && !validateNumber($val, $maxNumDigits)) {
                $error_msg .= "Value entered in ". $key . " is not a number<br>";
            // else just assign value
            } else {
                $inputs[$key] = $val;
            }
        // Value is required from this point foward
        // Numeric + empty or invalid number -> error
        } else if (in_array($key, $numericInputs) && (empty($val) || !validateNumber($val, $maxNumDigits))) {
            $error_msg .= "Value entered in ". $key . " is not a number<br>";
        // Empty required value? -> error
        } else if (empty($val)) {
            $error_msg .= "The $key is required<br>";
        // Valid input!
        } else {
            $inputs[$key] = $val;
        }
    }
    return $error_msg;
}

// Wrap values in quote for inserting into database
function arrayToValues($array) {
    $vals = "";
    for ($i=0; $i < count($array); $i++) { 
        if ($i == (count($array) - 1)) {
            $vals .= "'".$array[$i]."'";
        }
        else {
            $vals .= "'".$array[$i]."', ";
        }
    }
    return $vals;
}

// Cells - td, th
function generateCells($row, $openTag, $endTag) {
    $cells = "";
    foreach ($row as $cell) {
        $cells .= $openTag.$cell.$endTag;
    }
    return "<tr>".$cells."</tr>";
}

// Take in rows from table, a header, id type (employee id, client id...), page where the form will be submit to
function resultsToTable($results, $header, $idType, $updatePage, $currentPage) {
    $tableContent = "";
    // Hold value to update the status. If the current status is false then the update value is true
    $tableContent .= generateCells($header, "<th scope=\"col\">", "</th>");
    // Not the log page
    if (!empty($updatePage) && !empty($idType)) {
        foreach ($results as $row) {
            // Convert 1/0 to Active/Inactive for displaying
            $row['status'] = ($row['status'] == "1") ? "Active" : "Inactive"; 
            // Add action column to the row
            // Update status sends user to same page
            $row[] = '<form action="index.php?page='.$currentPage.'" method="POST">
            <button type="submit" class="btn btn-secondary" name="updateStatus">Enable/Disable</button>
            <input type="hidden" hidden name="id" value='.$row[$idType].'>
            <input type="hidden" hidden name="status" value='.$row['status'].'>
            </form>'.
            // Update sends user to update page
            '<form action="index.php?page='.$updatePage.'" method="POST">
            <button type="submit" class="btn btn-secondary" name="update">Update</button>
            <input type="hidden" hidden name="id" value='.$row[$idType].'>
            </form>';
            $tableContent .= generateCells($row, "<td>", "</td>");
        }
    }
    // It's the log page ditch status, action columns
    else {
        foreach ($results as $row) {
            $tableContent .= generateCells($row, "<td>", "</td>");
        }
    }
    return "<table class=\"table\">$tableContent</table>";

}

function confirmForm() {
    // Current status is active -> the update value ($statusInt) will be inactive (0)
    if ($_POST['status'] == "Active") {
        $statusInt = 0;
        $statusString = "Inactive";
    }
    else {
        $statusInt = 1;
        $statusString = "Active";
    }
    return  '<form method="POST" class="create">
        <div style="margin-top: 10px;" class="d-flex justify-content-center p-3 mb-2 bg-dark text-white border border-info rounded">
            <div class="form-group">
                Are you sure you want to change record #'.$_POST['id'].' status to '.$statusString.'?
                <input type="hidden" name="idConfirm" value='.$_POST['id'].'>
                <input type="hidden" name="statusConfirm" value='.$statusInt.'>
                <button type="submit" class="btn btn-info" name="btnYes">Yes</button>
                <button type="submit" class="btn btn-info" name="bntNo">No</button>
            </div>
        </div>
    </form>';
}

function updateStatus($table, $idType) {
    global $db, $logInfo;
    $id = "'".$_POST['idConfirm']."'";
    $status = "'".$_POST['statusConfirm']."'";
    // Result is always true for other that is not client
    $res = true;
    if ($idType == 'idClient') {
        $querry = $db->prepare("UPDATE client_event set status = $status where Client_idClient = $id");
        $res = $querry->execute();
        // This one is updated as a bonus with client so it wasn't logged
    }
    // Only update client status if their events are successfully disabled
    if ($res) {
        $querry = $db->prepare("UPDATE $table set status = $status where $idType=$id");
        if ($querry->execute()) {
            displaySuccess('Status Updated');
            $logInfo['module'] = $table;
            $logInfo['action'] = ($_POST['statusConfirm'] == '1') ? "enable $table" : "disable $table";
            databaseLog($logInfo);
        }
        else {
            displayError('Error: status was not updated');
        }
    }
}
function databaseLog($logInfo) {
    global $db;
    $values = arrayToValues(array_values($logInfo));
    $columns = implode(", ", array_keys($logInfo));
    $querry = $db->prepare("INSERT INTO log ($columns) VALUES ($values)");
    $querry->execute();
}

// Some functions for displaying output
function displayError($msg) {
    echo "<div class=\"alert alert-danger\" role=\"alert\">$msg</div>";
}
function displaySuccess($msg) {
    echo "<div class=\"output p-3 mb-2 bg-success text-white border rounded\" role=\"alert\">$msg</div>";
}
