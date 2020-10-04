<?php
namespace app\components;

use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
        $file = "mis_translation.json";
        $readContent= file_get_contents($file);

        $misLanguages = json_decode($readContent,true);
        $myfile = fopen($file, "w") or die("Unable to open file!");

        //print_r($misLanguages);die;

        if(is_array($misLanguages)) {
            if (!in_array($event->message, $misLanguages)) {
                $misLanguages[] = $event->message;
            }
        }else{
            $misLanguages[] = $event->message;
        }

        $updatedContent = json_encode($misLanguages);
        fwrite($myfile, $updatedContent);

        fclose($myfile);
        $event->translatedMessage = $event->message;
        //$event->translatedMessage = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @";
    }
}