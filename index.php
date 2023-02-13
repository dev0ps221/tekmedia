<?php
    include_once('crudconnection.php');
    $conn = new CrudConnection(null,'root',null,$dbname='tekmedia');
    $conn->__connect();
    include_once('tekmedia.php');
    $tm = new TekMedia($conn);
    print_r($tm->getuploads());
?>
<form enctype='multipart/formdata' method="post">
    <div class="field">
        <label for="type">type</label>
        <select name="type" id="type" class="entry">
            <option value="photo">photo</option>
            <option value="video">video</option>
        </select>
    </div>
    <div class="field">
        <label for="content">
            uploader le media
        </label>
    </div>
</form>
<script>
    function initupload(){

    }
</script>