<!-- --------------------------------------------------------------------------------- view/inc/header.inc.php --------------------------------------------------------------------------------- -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assignment 4</title>
    <meta name="description" content="Assignment4">
    <meta name="author" content="Truong Thi and co-conspirator Fred Pedersen">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <style>
      table.table {
        margin-top: 30px;
        margin-bottom: 100px;
      }
      h1 {
        text-align: right;
      }
      div.title {
        margin: 150px 20px 0px 5px;
      }
      td form {
        display: inline;
      }
      form.create {
        width: 50%;
        margin: auto;
        margin-top: 50px;
      }
      div.output, .alert {
        width: 50%;
        margin: 50px auto;
        text-align: center;
      }
      td, th {
        padding: 10px;
      }
      th {
        text-transform: capitalize;
      }
      li.nav-item > a.nav-link {
        color: black;
        font-weight: 550;
      }
      a.active, .gradient_bg {
        background-image: radial-gradient( circle farthest-corner at 10% 20%,  rgba(90,92,106,1) 0%, rgba(32,45,58,1) 81.3% )!important;
      }
      
    </style>
    <!-- JavaScript Bundle with Popper -->
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
<!-- A sidebar navigation bar would look better here -->
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
  <div class="container-fluid">
    <span class="navbar-brand">
      <?php
       if ($page) {
         echo ucfirst($page);
       }
       else {
         echo "";
       } 
       ?>
    </span>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    $pages = [
      'addClient' => 'Add Client', 'addEmployee' => 'Add Employee', 'addNotification' => 'Add Notification', 
      'addClientEvent' => 'Add Client Event', 'viewClients' => 'View Clients', 'viewEmployees' => 'View Employees',
      'viewNotifications' => 'View Notifications', 'viewClientEvents' => 'View Client Events', 'viewLogs' => 'Log', 
      'logout' => 'Log out'
    ]; 
    $listItems = '';
    foreach ($pages as $href => $actualPageName) {
      // Not sure if there's a need to sanitize input here
      // Set the current page to active
      $active = ($href == filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING)) ? "active" : '';
      $listItems .= 
      "<li class=\"nav-item\">
        <a class=\"nav-link $active\" href=\"?page=$href\">$actualPageName</a>
      </li>";
    }
    ?>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="nav nav-pills me-auto mb-2 mb-lg-0">
        <?php
        echo $listItems;
        ?>
      </ul>
    </div>
  </div>
</nav>
