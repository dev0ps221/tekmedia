<?php
    extract($_POST);
    extract($_FILES);
    if(isset($action)){
        include_once('crudconnection.php');
        $conn = new CrudConnection(null,'root',null,$dbname='tekmedia');
        $conn->__connect();
        include_once('tekmedia.php');
        $tm = new TekMediaManager($conn);
        print_r($tm->getuploads());
        if($action == 'doupload' ){
            $filescount = count($content['name']);
            $allowedtypes = ['image','video']; 
            if($filescount){
                for($i = 0 ; $i < $filescount ; $i++){
                    print_r($content);
                    $filesize = $content['size'][$i]; 
                    $filename = $content['name'][$i];
                    $fileerror= count($content['error']) >= $i ? $content['error'][$i] : null; 
                    $filetype = explode("/",$content['type'][$i])[0];
                    $fileext  = explode("/",$content['type'][$i])[1];
                    $tmpname  = $content['tmp_name'][$i];
                    if(!$fileerror and $type == $filetype and in_array($filetype,$allowedtypes)){
                        echo "we can upload";
                        $upladpath = $tm->uploadfile($type,"".time().".$fileext",$tmpname);
                        if($upladpath){
                            if($tm->registerfile($type,$upladpath,isset($options)?$options:'')){
                                echo "success uploading";
                            }else{
                                echo "error: failed registering $uploadpath";
                            }
                        }else{
                            echo "error: failed uploading $filename";
                        }
                    }else{
                        echo "error: type $type  doesnt match the filetype ". $filetype;
                    }
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
                        <option value="image">image</option>
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