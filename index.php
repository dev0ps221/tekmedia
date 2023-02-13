<?php
    extract($_POST);
    extract($_FILES);
    if(isset($action)){
        include_once('crudconnection.php');
        $conn = new CrudConnection(null,'root',null,$dbname='tekmedia');
        $conn->__connect();
        include_once('tekmedia.php');
        $tm = new TekMedia($conn);
        print_r($tm->getuploads());
        if($action == 'doupload' ){
            $filescount = count($content['name']);
            if($filescount){
                for($i = 0 ; $i < $filescount ; $i++){
                    $filetype = $content['type'][$i]; 
                    $filename = $content['name'][$i];
                    $fileerror= $content['error'][$i];
                    $filesize = $content['size'][$i];
                    $tmpname  = $content['tmp_name'][$i];
                }
            }else{
                echo "error: no file to upload";
            }
        }
    }else{
        ?>
            <form enctype='multipart/formdata' method="post" onsubmit='initupload(event,event.currentTarget)'>
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
                    <input type="file" id='content' name='content[]' multiple>
                </div>
                <div class="field">
                    <input type="hidden" name="action" value='doupload'>
                    <button>
                        uploader
                    </button>
                </div>
            </form>
            <script>
                function initupload(e,form){
                    e.preventDefault()
                    const formdata = new FormData(form)
                    const req = new XMLHttpRequest()
                    req.open('post','')
                    req.onload = function (event){
                        console.log(req.response)
                    }
                    req.send(formdata)
                }
            </script>

        <?php
    }
?>