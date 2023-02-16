<?php
    if(isset($_POST['tmaction']) and $_POST['tmaction'] == 'tmselectupload'){
        echo $_POST['id'];
    }else{
        include_once('index.php');
    }
    
?>