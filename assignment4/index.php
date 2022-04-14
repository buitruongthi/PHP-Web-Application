<?php
// --------------------------------------------------------------------------------- index.php ---------------------------------------------------------------------------------
session_start();
$page = '';
if (isset($_SESSION['login']) && $_SESSION['login']) {
    if (!empty($_GET['page'])) {
        $page = $_GET['page'];
    }
    else {
        // Default page
        $page = 'viewClients';
    }
    if ($page) {
        switch($page){
            case 'addClient':
            case 'addEmployee':
            case 'addNotification':
            case 'addClientEvent':
                include 'view/add.php';
                break;
            case 'viewClients':
            case 'viewNotifications':
            case 'viewClientEvents':
            case 'viewEmployees':
            case 'viewLogs':
                include 'view/view.php'; 
                break; 
            case 'updateClient':
            case 'updateEmployee':
            case 'updateNotification':
            case 'updateClientEvent':
                include 'view/update.php'; 
                break; 
            case 'logout':
                include 'model/logout.php';
                break;
            default:
                include 'view/view.php'; 
        }
    }
    else {
        include "view/view.php";
    }
}
else {
    include "view/login.php";
}