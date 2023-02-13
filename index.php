<?php
    include_once('tm/tekmedia.php');
    $tm = new TekMediaManager();
    if(isset($_POST['tmaction'])){
        $tm->ajaxrequest();
    }else{
        ?>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
            </head>
            <body>
                <link rel="stylesheet" href="/tekmedia.css">
                <?php
                $tm->render('selectbox');
                ?>
                <script src="/tekmedia.js"></script>
            </body>
            </html>
        <?php 
    }
?>
    