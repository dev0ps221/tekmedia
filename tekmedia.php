<?php

    class TekMedia{
        private $rawdata;
        public $id;
        public $type;
        public $media;
        public $options;
        function remove(){
            return $this->manager->deleteuploadedfile($this->manager->getuploaddir()."/".str_replace("/uploads/","",$this->media));
        }
        function replace($tmpname){
            return  $this->manager->uploadfile($this->type,str_replace("/uploads/","",$this->media),$tmpname);
        }
        function render(){
            ?>
                <figure class="tekmedia" id='<?php echo $this->id; ?>'>
                    <figcaption>
                        upload N<?php echo $this->id?>
                    </figcaption>
                    <?php
                        if($this->type=='image'){
                            ?>
                                <img src="<?php echo $this->media."?t=".time()."" ?>" alt="<?php echo 'uploaded file '.$this->id ?>">
                            <?php
                        }
                        if($this->type=='video'){
                            ?>
                                <video controls src="<?php echo $this->media."?t=".time()."" ?>" alt="<?php echo 'uploaded file '.$this->id ?>"></video>
                            <?php
                        }

                    ?>
                </figure>

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
            $this->manager = $manager;
            $this->assign();
        }
    }

    class TekMediaRenders{
        
        function manager(){
            $uploads = $this->manager->getuploads();
            ?>
                <section id="tmmanager">
                    <div id="tmviews">
                        <div id="tmtypestab">
                            <div class="tmtab selected">
                                mix
                            </div>
                            <?php
                                foreach($this->manager->getallowedtypes() as $type){
                                    ?>  
                                        <div class="tmtab">
                                            <?php echo $type ?>
                                        </div>
                                    <?php
                                }
                            ?>  
                        </div>
                        <?php
                            $this->render('uploadlist');
                        ?>
                    </div>
                    <section id="tmactions">
                        <div id="uploadaction">
                            <h2>
                                TELEVERSER UN MEDIA
                            </h2>
                            <?php
                                $this->manager->render('uploadform')
                            ?>
                        </div>
                        <h3>
                            GERER LE MEDIA
                        </h3>
                        <div id="replaceaction">
    
                        </div>
                        <div id="deleteaction">
    
                        </div>
                    </section>
                </section>




            <?php
        }

        function preview_elem($id){
            $upload = $this->manager->getupload($id);
            if($upload != null){
                $upload->render();
            }
        }

        function select_elem($id,$ajaxpath="xaja.php"){
            $upload = $this->manager->getupload($id);
            if($upload){
                ?>
                    <form method="post" onsubmit='initupload(event,event.currentTarget)'>
                        <div class="field">
                            <input type="hidden" name="apath" value='<?php echo $ajaxpath ; ?>'>
                            <input type="hidden" name="tmaction" value='tmselectupload'>
                            <input type="hidden" name="id" value='<?php echo $upload->id ?>'>
                            <button>
                                valider la selection
                            </button>
                        </div>
                    </form>
                
                <?php
            }else{
                ?> <h2> VOUS ESSAYEZ D'ACCEDER A UNE RESSOURCE NON AUTORISEE ! </h2> <?php
            }
        }

        function selectbox(){
            $uploads = $this->manager->getuploads();
                ?>
                <section id="tmselectbox">
                    <div id="tmviews">
                        <?php
                            $this->render('uploadlist');
                        ?>
                    </div>
                    <section id="tmactions">
                        <h3>
                            MEDIAS SELECTIONNEES
                        </h3>
                        <div class="preview">

                        </div>
                        <div id="selectaction">
    
                        </div>
                    </section>
                </section>




            <?php

        }

        function uploadlist($ajaxpath = ""){
            ?>

                <section id="tmuploads">
           
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
           
           <?php
        }

        function replaceform($id,$ajaxpath=''){
            $upload = $this->manager->getupload($id);
            if($upload){
                ?>
                    <form enctype='multipart/formdata' method="post" onsubmit='initupload(event,event.currentTarget)'>
                        <div class="field">
                            <label for="type">type</label>
                            <select name="type" id="type" class="entry">
                                <option value="image">image</option>
                                <option value="video">video</option>
                            </select>
                        </div>
                        <div class="preview">
                            <?php
                                if($upload->type=='image'){
                                    ?>
                                        <img src="<?php echo $upload->media."?t=".time()."" ?>" alt="<?php echo 'uploaded file '.$upload->id ?>">
                                    <?php
                                }
                                if($upload->type=='video'){
                                    ?>
                                        <video controls src="<?php echo $upload->media."?t=".time()."" ?>" alt="<?php echo 'uploaded file '.$upload->id ?>"></video>
                                    <?php
                                }

                            ?>
                        </div>
                        <div class="field">
                            <label for="content">
                                uploader le media
                            </label>
                            <input type="file" id='content' name='content[]' multiple>
                        </div>
                        <div class="field">
                            <input type="hidden" name="tmaction" value='doreplace'>
                            <input type="hidden" name="id" value='<?php echo $upload->id ?>'>
                            <button>
                                uploader
                            </button>
                        </div>
                    </form>
                
                <?php
            }else{
                ?> <h2> VOUS ESSAYEZ D'ACCEDER A UNE RESSOURCE NON AUTORISEE ! </h2> <?php
            }
        }

        function deleteform($id,$ajaxpath=''){
            $upload = $this->manager->getupload($id);
            if($upload){
                ?>
                    <form method="post" onsubmit='initupload(event,event.currentTarget)'>
                        <div class="field">
                            <input type="hidden" name="tmaction" value='dodelete'>
                            <input type="hidden" name="id" value='<?php echo $upload->id ?>'>
                            <button>
                                supprimer
                            </button>
                        </div>
                    </form>
                
                <?php
            }else{
                ?> <h2> VOUS ESSAYEZ D'ACCEDER A UNE RESSOURCE NON AUTORISEE ! </h2> <?php
            }
        }

        function render($rendername,$args=[]){
            if(method_exists($this,$rendername)){
                return $this->{$rendername}(...$args);
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
        
        function getallowedtypes(){
            return $this->allowedtypes;
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
                if($file->remove()){
                    return $this->conn->delete_uploads_entry($file->id);
                }else{
                    return null;
                }
            }else{
                return null;
            }
        }

        function getupload($id){
            $raw = $this->conn->select_uploads_entry($id);
            if($raw and is_array($raw[0])){
                return new TekMedia($raw[0],$this);
            }else{
                return null;
            }
        }

        function ajaxrequest(){
            extract($_POST);
            extract($_FILES);
            if($tmaction == 'doupload' ){
                $this->newupload($content,$type);
            }
            if($tmaction == 'doreplace' ){
                $this->updateupload($content,$type,$id);
            }
            if($tmaction == 'dodelete' ){
                echo $this->removefile($id) ? "success" : "failed" ;
            }
            if($tmaction == 'getrender' ){
                $args = [json_decode($args)];
                $this->render($render,...$args);
            }
        }

        function newupload($content,$type){
            $filescount = count($content['name']);
            if($filescount){
                for($i = 0 ; $i < $filescount ; $i++){
                    $filesize = $content['size'][$i]; 
                    $filename = $content['name'][$i];
                    $fileerror= count($content['error']) >= $i ? $content['error'][$i] : null; 
                    $filetype = explode("/",$content['type'][$i])[0];
                    $fileext  = str_replace('x-matroska','mp4',explode("/",$content['type'][$i])[1]);
                    $tmpname  = $content['tmp_name'][$i];
                    if(!$fileerror and $type == $filetype and in_array($filetype,$this->allowedtypes)){
                        $target = "".time().".$fileext";
                        while(file_exists($this->uploaddir."/".$target)){
                            sleep(1);
                            $target = "".time().".$fileext";
                        }
                        $upladpath = $this->uploadfile($type,$target,$tmpname);
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


        function updateupload($content,$type,$id){
            $filescount = count($content['name']);
            if($filescount){
                for($i = 0 ; $i < $filescount ; $i++){
                    $filesize = $content['size'][$i]; 
                    $filename = $content['name'][$i];
                    $fileerror= count($content['error']) >= $i ? $content['error'][$i] : null; 
                    $filetype = explode("/",$content['type'][$i])[0];
                    $fileext  = explode("/",$content['type'][$i])[1];
                    $tmpname  = $content['tmp_name'][$i];
                    if(!$fileerror and $type == $filetype and in_array($filetype,$this->allowedtypes)){
                        $upload = $this->getupload($id);
                        if($upload and $upload->replace($tmpname)){
                            echo "success uploading";
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

        function render($rendername,...$args){
            $this->renders->render($rendername,$args);
        }

        function initdb(){
            if($this->conn == null){
                include_once('crudconnection.php');
                $this->conn = new CrudConnection(
                    null,       //dbhost
                    'root',     //dbuser
                    null,       //dbpassword
                    'tester'    //dbname
                );
                $this->conn->__connect();
            }
            $match = null;
            foreach($this->conn->getdatabasetables() as $table){
                if($table['name']== $this->uploadtable) $match = 1;
            }
            if(!$match){
                $this->conn->query(
                    "create table ".$this->uploadtable." (id int not null auto_increment primary key,type text not null, content text not null,options text null);"
                );
            }
        }

        function __construct($crud = null){
            $this->uploaddir = dirname(__FILE__)."/uploads";
            $this->conn = $crud; 
            $this->renders = new TekMediaRenders($this);
            $this->initdb();
        }
    }
?>