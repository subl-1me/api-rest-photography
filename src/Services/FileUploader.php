<?php

namespace App\Services;

use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Services\DataValidator;

class FileUploader{

    public function upload($file){

        $slugger = new AsciiSlugger();
        $validator = new DataValidator();
        
        $isImage = $validator->isImage($file);

        if(!$isImage) return null;

        // storage image
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileExtension = substr($file->getClientOriginalName(), -4);
        $safeFileName = $slugger->slug($originalFileName);
        $fileName = time() . $safeFileName . $fileExtension;

        $storagePath = '../../portfolio-urbina/src/assets/images';

        try{
            $file->move($storagePath, $fileName);
        }catch (FileException $e){
            return null;
        }

        return $fileName;
    }

}