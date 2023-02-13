<?php

    class TekMedia{
        private $rawdata;
        public $id;
        public $type;
        public $media;
        public $options;
        function remove(){
            if($this->manager->deleteuploadedfile(getuploaddir()."/".preg_replace("#/uploads/#","",$this->content))){
                return $this->manager->conn->delete_uploads_entry($this->id);
            }else{
                return null;
            }
        }
        function render(){
            ?>
                <div class="tekmedia" id='<?php echo $this->id; ?>'>
                    <?php
                        if($this->type=='image'){
                            ?>
                                <img src="<?php echo $this->media ?>" alt="<?php echo 'uploaded file '.$this->id ?>">

                            <?php
                        }
                        if($this->type=='video'){
                            ?>
                                <video src="<?php echo $this->media ?>" alt="<?php echo 'uploaded file '.$this->id ?>">

                            <?php
                        }

                    ?>
                </div>

            <?php
        }
        function assign(){
            if(is_array($this->rawdata)){
                if($this->rawdata['id']){
                    $this->id = $this->rawdata['id'];
                }
                if($this->rawdata['type']){
                    $this->type = $this->rawdata['type'];
                }
                if($this->rawdata['content']){
                    $this->media = $this->rawdata['content'];
                }
                if($this->rawdata['options']){
                    $this->options = $this->rawdata['options'];
                }
            }
        }
        function __construct($raw,$manager){
            $this->rawdata = $raw;
            $this->assign();
        }
    }

    class TekMediaRenders{
        
        function uploadlist(){
            ?>
                <section id="tmuploads">
                    <h2>
                        uploads
                    </h2>
                    <section class="liste_uploads">
                        <?php
                            foreach($this->manager->getuploads() as $tekmedia){
                                $tekmedia->render();
                            }
                        ?>
                    </section>
                </section>

            <?php
        }

        function uploadform($ajaxpath=''){
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
                        <input type="hidden" name="tmaction" value='doupload'>
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
                        req.open('post',`<?php echo $ajaxpath?>`);
                        req.onload = function (event){
                            console.log(req.response)
                        }
                        req.send(formdata)
                    }
                </script>
            <?php
        }

        function render($rendername){
            if(method_exists($this,$rendername)){
                return $this->{$rendername}();
            }
        }

        function __construct($manager){
            $this->manager = $manager;
        }

    }

    class TekMediaManager{
        private $selfdir;
        private $uploaddir;
        private $uploadtable = "uploads";
        private $allowedtypes = ['image','video']; 
        
        function getuploaddir(){
            return $this->uploaddir;
        }

        function uploadfile($type,$target,$tmpname){
            $uploaddir = $this->uploaddir."/".$target;
            $targetdir = "/uploads/".$target;
            if(move_uploaded_file($tmpname,$uploaddir)){
                return $targetdir;
            }else{
                return false;
            }
        }

        function deleteuploadedfile($filepath){
            if(unlink($filepath)){
                return true;
            }else{
                return null;
            }
        }

        function getuploads(){
            return array_map(function ($raw){
                return new TekMedia($raw,$this);
            },$this->conn->select_uploads_entries());
        }

        function registerfile($type,$content,$options=""){
            return $this->conn->insert_into_uploads(['type'=>$type,'content'=>$content,'options'=>$options]);
        }

        function removefile($id){
            $file = $this->getupload($id);
            if($file){
                $file->remove();
            }
        }

        function getupload($id){
            return new TekMedia($this->conn->select_file_entry($id),$this);
        }

        function ajaxrequest(){
            extract($_POST);
            extract($_FILES);
            if($tmaction == 'doupload' ){
                $this->newupload($content,$type);
            }


        }

        function newupload($content,$type){
            $filescount = count($content['name']);
            if($filescount){
                for($i = 0 ; $i < $filescount ; $i++){
                    print_r($content);
                    $filesize = $content['size'][$i]; 
                    $filename = $content['name'][$i];
                    $fileerror= count($content['error']) >= $i ? $content['error'][$i] : null; 
                    $filetype = explode("/",$content['type'][$i])[0];
                    $fileext  = explode("/",$content['type'][$i])[1];
                    $tmpname  = $content['tmp_name'][$i];
                    if(!$fileerror and $type == $filetype and in_array($filetype,$this->allowedtypes)){
                        echo "we can upload";
                        $upladpath = $this->uploadfile($type,"".time().".$fileext",$tmpname);
                        if($upladpath){
                            if($this->registerfile($type,$upladpath,isset($options)?$options:'')){
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

        function setDbInfo($infoname,$infoval,$reconnect=false){
            $this->conn->{$infoname} = $infoval;
            if($reconnect){
                $this->conn->connect();
            }
        }

        function render($rendername){
            $this->renders->render($rendername);
        }

        function __construct($crud = null){
            $this->uploaddir = dirname(__FILE__)."/uploads";
            $this->conn = $crud; 
            $this->renders = new TekMediaRenders($this);
            if($this->conn == null){
                include_once('crudconnection.php');
                $this->conn = new CrudConnection(null,'root',null,$dbname='tekmedia');
                $this->conn->__connect();
            }
        }
    }

?>