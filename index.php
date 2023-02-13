<?php
    include_once('tekmedia.php');
    $tm = new TekMediaManager();
    if($_POST['tmaction']){
        $tm->ajaxrequest();
    }else{
        $tm->render('manager');
    }
?>