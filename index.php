<?php
    include_once('tekmedia.php');
    $tm = new TekMediaManager();
    if(isset($_POST['tmaction'])){
        $tm->ajaxrequest();
    }else{
        $tm->render('uploadlist');
        $tm->render('uploadform');
    }
?>