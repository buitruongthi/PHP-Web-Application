<?php
// --------------------------------------------------------------------------------- model/logout.php ---------------------------------------------------------------------------------
// Initialize the session
// Unset all of the session variables
$_SESSION = array();
// Destroy the session.
session_destroy();
// Redirect to login page
header("location: index.php");
?>