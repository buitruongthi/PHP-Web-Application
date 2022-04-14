<?php
include 'view/inc/header.inc.php';
include 'model/login.php';
?>
<!-- Page is mostly in html. No comment needed -->
<form class="create" method="POST">
    <div class="form-group">
        <?php
        $fields = ['user_name', 'password'];
        echo generateTextFields($fields, $inputs);
        ?>
    </div>
    <div style="margin-top: 10px;" class="d-flex justify-content-center">
        <button type="submit" class="btn btn-primary" name="btnLogin">Log In</button>
    </div>
</form>
<div class="title">
    <h1>Log In</h1>
    <!-- The member link and the script will be rendered under the show source code if we put it in the footer,
    so we'll leave them here -->
    <a style='font-size: large' href="member.html" target="_blank">Member HTML</a>
    <script src=https://my.gblearn.com/js/loadscript.js></script>
</div>
<?php
show_source('view/inc/header.inc.php');
show_source('model/login.php');
show_source('model/logout.php');
show_source(__FILE__);
show_source('view/inc/footer.inc.php');
include 'view/inc/footer.inc.php';
?>
