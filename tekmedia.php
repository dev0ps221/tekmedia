<?php

    class TekMedia{
        private $rawdata;
        public $id;
        public $type;
        public $media;
        public $options;
        function assign(){
            if(is_array($this->rawdata)){
                if($this->rawdata['id']){
                    $this->id = $this->rawdata['id'];
                }
                if($this->rawdata['type']){
                    $this->type = $this->rawdata['type'];
                }
                if($this->rawdata['content']){
                    $this->content = $this->rawdata['content'];
                }
                if($this->rawdata['options']){
                    $this->options = $this->rawdata['options'];
                }
            }
        }
        function __construct($raw){
            $this->rawdata = $raw;
        }
    }

    class TekMediaManager{
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
            return array_map(function ($raw){
                return new TekMedia($raw);
            },$this->conn->select_uploads_entries());
        }

        function registerfile($type,$content,$options=""){
            return $this->conn->insert_into_uploads(['type'=>$type,'content'=>$content,'options'=>$options]);
        }

        function removefile($id){

        }

        function getupload($id){
            return new TekMedia($this->conn->select_file_entry($id));
        }

        function __construct($crud){
            $this->uploaddir = dirname(__FILE__)."/uploads";
            $this->conn = $crud; 
        }
    }

?>