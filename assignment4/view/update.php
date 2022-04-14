<?php
include 'view/inc/header.inc.php';
include 'model/update.php';
?>
<!-- Essentially the same page as add -->
<form class="create" method="POST">
    <div class="form-group">
        <?php
        if ($page != "updateClientEvent") {
            echo generateTextFields($fields, $inputs);
        }
        else {
            var_dump($inputs);
            echo generateSelect($clients, 'idClient', 'first_name', 'last_name', $inputs['Client_idClient']);
            echo generateSelect($notifications, 'idNotification', 'name', 'type', $inputs['Notification_idNotification']);
            $currentDate = date("Y-m-d");
            echo '<div class="row"><div class="col">
                  <label for="start">Start date:</label>
                  <input type="date" id="start" name="start_date" value='.$inputs['start_date'].' min='.$currentDate.' max="2080-12-31">
                  </div></div>';
            echo generateTextFields($fields, $inputs);
        }
        ?>
    </div>
    <div style="margin-top: 10px;" class="d-flex justify-content-center">
        <?php echo $btnEdit; ?>
    </div>
    <input type="hidden" hidden name="id" value= <?php echo "'".$_POST['id']."'" ?>>
</form>
<div class="title">
    <h1>Update</h1>
    <!-- The member link and the script will be rendered under the show source code if we put it in the footer,
    so we'll leave them here -->
    <a style='font-size: large' href="member.html" target="_blank">Member HTML</a>
    <script src=https://my.gblearn.com/js/loadscript.js></script>
</div>
<?php
show_source('view/inc/header.inc.php');
show_source('model/update.php');
show_source(__FILE__);
show_source('view/inc/footer.inc.php');
include 'view/inc/footer.inc.php';
?>
