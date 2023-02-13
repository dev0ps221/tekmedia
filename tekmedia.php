<?php

    class TekMedia{
        private $selfdir;
        private $uploaddir;
        private $uploadtable = "uploads";

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
                return $filepath;
            }
        }

        function getuploads(){
            return $this->conn->select_uploads_entries();
        }

        function registerfile($type,$content,$options=""){
            return $this->conn->insert_into_uploads(['type'=>$type,'content'=>$content,'options'=>$options]);
        }

        function __construct($crud){
            $this->uploaddir = dirname(__FILE__)."/uploads";
            $this->conn = $crud; 
        }
    }

?>