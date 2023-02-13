<?php

    class TekMedia{
        private $selfdir;
        private $uploaddir;
        private $uploadtable = "uploads";

        function uploadfile($type,$target,$image){
            $uploaddir = $this->uploaddir."/".$target;
            if(move_uploaded_file($image['tmp_name'],$uploaddir)){
                return $target;
            }else{
                return false;
            }
        }

        function deleteuploadedfile($filepath){
            if(unlink($filepath)){
                return true;
            }else{
                return $filepath;
            }
        }

        function getuploads(){
            return $this->conn->select_uploads_entries();
        }

        function registerfile($type,$content,$options=""){

        }

        function __construct($crud){
            $this->conn = $crud; 
        }
    }

?>