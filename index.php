<?php
    include_once('crudconnection.php');
    $conn = new CrudConnection(null,null,null,$dbname='tekmedia');
    print_r($conn);
    include_once('tekmedia.php');

?>