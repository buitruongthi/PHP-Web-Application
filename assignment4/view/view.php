<?php
include 'view/inc/header.inc.php';
include 'model/view.php';
?>
<div style="margin: 5px;">
    <h1>View and Update</h1>
    <!-- The member link and the script will be rendered under the show source code if we put it in the footer,
    so we'll leave them here -->
    <a style='font-size: large' href="member.html" target="_blank">Member HTML</a>
    <script src=https://my.gblearn.com/js/loadscript.js></script>
</div>   
<?php
show_source('view/inc/header.inc.php');
show_source('model/view.php');
show_source(__FILE__);
show_source('view/inc/footer.inc.php');
include 'view/inc/footer.inc.php';
?>

