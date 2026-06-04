<?php

namespace App\Service;

class ImageService {

    public function createFile($model, $file , $collectionName){

        return $model->addMedia($file)->toMediaCollection($collectionName);
    }

    public function updateFile($model, $file , $collectionName, $clear ){

        if($model->getMedia($collectionName)->isNotEmpty() && $clear == true){
            return $model->clearMediaCollection($collectionName);
        }
           return $this->createFile($model, $file , $collectionName);
    }

    public function deleteFile($model, $collectionName, $clear = true){
          if($model->getMedia($collectionName)->isNotEmpty() && $clear == true){
            return $model->clearMediaCollection($collectionName);
        }
    }

}
