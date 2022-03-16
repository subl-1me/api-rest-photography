<?php

namespace App\Services;

class DataValidator{

    public function isImage($file){

        if(!$file) return false;

        $mimeType = [
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'png' => 'image/png',
        ];

        foreach($mimeType as $mime){
            if($mime == $file->getClientMimeType()){
                return true;
            }
        }

        return false;
    } 

    public function paramsValidator($params){
        // set null to empty parameters
        if($params != null){
            foreach($params as $param){
                if(empty($param)){
                    $param = null;
                }
            }
            return $params;
        }

        return null;
    }
    
}