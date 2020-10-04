<?php


namespace app\common;


use Exception;
use Yii;

class Files
{
    public static function write($type, $content){
        $file_path = Constants::FILE_PATH_SYSTEM;
        $prefix = "sys-";
        $extension = ".bin";

        switch($type){
            case Constants::FILE_TYPE_SSH_KEY:
                $file_path = Constants::FILE_PATH_SSH;
                $prefix = "key-";
                $extension = ".pem";
                break;
        }

        $name = $prefix.time()."-".rand(0,10000)."-".md5($content).$extension;

        $path = Yii::getAlias("@app/files/".$file_path)."/".$name;

        $result = file_put_contents($path,$content);

        if($result)
            return $path;
        else
            throw new Exception('Unable to write key file',500);
    }

    public static function download($type, $name =  'datafile', $path){
        $mime = 'application/binary';

        switch($type){
            case Constants::FILE_TYPE_SSH_KEY:
                $mime = 'application/x-pem-file';
                break;
        }

        header("Content-disposition: attachment; filename=".$name);
        header("Content-type: ".$mime);
        readfile($path);
    }

    public static function delete($paths){
        if(is_array($paths)){
            foreach($paths as $path){
                @unlink($path);
            }
        }
        else{
            @unlink($paths);
        }
    }
}
