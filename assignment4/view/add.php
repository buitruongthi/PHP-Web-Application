<?php
include 'view/inc/header.inc.php';
include 'model/add.php';
?>
<form class="create" method="POST">
    <div class="form-group">
        <?php
        if ($page != "addClientEvent") {
            echo generateTextFields($fields, $inputs);
        }
        // Add client event requires a bit more attention
        else {
            echo generateSelect($clients, 'idClient', 'first_name', 'last_name');
            echo generateSelect($notifications, 'idNotification', 'name', 'type');
            $currentDate = date("Y-m-d");
            echo '<div class="row"><div class="col">
                  <label for="start">Start date:</label>
                  <input type="date" id="start" name="start_date" value='.$currentDate.' min='.$currentDate.' max="2080-12-31">
                  </div></div>';
            echo generateTextFields($fields, $inputs);
        }
        ?>
    </div>
    <div style="margin-top: 10px;" class="d-flex justify-content-center">
        <?php echo $btnAdd ?>
    </div>
</form>
<div class="title">
    <h1>Add</h1>
    <!-- The member link and the script will be rendered under the show source code if we put it in the footer,
    so we'll leave them here -->
    <a style='font-size: large' href="member.html" target="_blank">Member HTML</a>
    <script src=https://my.gblearn.com/js/loadscript.js></script>
</div>
<?php
show_source('view/inc/header.inc.php');
show_source('model/add.php');
show_source(__FILE__);
show_source('view/inc/footer.inc.php');
include 'view/inc/footer.inc.php';
?>
