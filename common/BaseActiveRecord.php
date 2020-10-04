<?php


namespace app\common;


use app\common\exceptions\PersistException;
use app\models\activerecord\Files;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

abstract class BaseActiveRecord extends ActiveRecord
{

    public function beforeValidate()
    {
        if($this->isNewRecord) {

            if ($this->hasAttribute('identifier')) {
                $this->identifier = $this->generateIdentifier();
            }

            $country = null;

            if($this->hasAttribute('ip')) {
                if(Yii::$app->request->isConsoleRequest) {
                    $this->setAttribute('ip', "::1");
                }
                else {
                    $this->setAttribute('ip', Yii::$app->request->getUserIP());
                    $country = Helper::getCountryIDFromIP($this->getAttribute('ip'));
                }

            }

            if($this->hasAttribute('useragent')){
                if (!Yii::$app instanceof Yii\console\Application) {
                    $this->setAttribute('useragent', Yii::$app->request->getUserAgent());
                }
            }

            if($this->hasAttribute('ip_country_id'))
                $this->setAttribute('ip_country_id',$country);

        }

        if($this->hasAttribute('time')){
            if($this->time==null){
                $this->time = date(Constants::PHP_DATE_FORMAT);
            }
        }

        if($this->hasAttribute('updated_on')){
            $this->updated_on = date(Constants::PHP_DATE_FORMAT);
        }

        if($this->hasAttribute('slug')){
            $this->slug = $this->generateSlug();
        }

        return parent::beforeValidate();
    }


    public function afterSave($insert, $changedAttributes)
    {
        if($insert){
            $this->insertTempFileID();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function generateSlug($suffix=0){
        $slug = '';
        if($this->hasAttribute('name')){
            $slug = Helper::slugifyString($this->name);
        }
        if($this->hasAttribute('title')){
            $slug = Helper::slugifyString($this->title);
        }
        if($suffix!=0){
            $slug .= "_".$suffix;
        }
        //check if unique
        if($this->isNewRecord) {
            $user = self::find()->where(['slug' => $slug])->one();
        }else{
            $user = self::find()->where(['slug' => $slug])->andWhere(['!=','id',$this->id])->one();
        }
        if($user != null){
            $suffix++;
            return $this->generateSlug($suffix);
        }
        return $slug;
    }

    public function generateIdentifier(){
        $prefix = 'SYS'; //system prefix

        $table = $this->normalTableName();

        $exploded = explode('_',$table);

        if(is_array($exploded) && count($exploded)>0){
            $prefix = "";
            foreach ($exploded as $word){
                $prefix .= substr($word,0,1);
            }
            $prefix = strtoupper($prefix);
        }

        $identifier = Helper::generateRandomKey($prefix);
        $identifier = str_replace(array('&','?','#','@'),'',$identifier);

        //check if unique
        $user = self::find()->where(['identifier'=>$identifier])->one();
        if($user != null){
            return $this->generateIdentifier();
        }
        return $identifier;
    }

    public static function get($id){
        $instance = self::findOne($id);

        return $instance;
    }

    public static function create($config){
        $class = get_called_class();
        $instance = new $class($config);

        if($instance->save())
            return $instance;
        else {
            throw new PersistException($instance);

        }

    }

    public function upload($model,$attr,$type, $returnString=true,$defaultExt=false)
    {
        $model->$attr = UploadedFile::getInstance($model,$attr);
        $allowedExtensions = Yii::$app->params['allowedExtensions'];

        if(!$defaultExt) {
            if ($model->$attr == null) {
                $message = 'File not allowed.';
                if($returnString){
                    return $message;
                }
                return $model->addError($attr,$message);
            }
        }else{
            $allowedExtensions = $defaultExt;
        }

        if (!in_array($model->$attr->extension, $allowedExtensions))
        {
            $message = "Extension (".$model->$attr->extension.") not allowed. Allowed extensions are ".implode(', ', $allowedExtensions).".";
            if($returnString){
                return $message;
            }
            return $model->addError($attr,$message);
        }

        $imageName = $type.'_'.time().".".$model->$attr->extension;
        $getSavePath = Helper::saveFilePathDirect($type);

        $finalPath = $getSavePath.$imageName;

        if($model->$attr->saveAs($finalPath))
        {
            $model->$attr = $finalPath;
            if(!$model->save()){
                $message = 'Failed to upload file.';
                if($returnString){
                    return $message;
                }
                return $model->addError($attr,$message);
            }
            return true;
        }else{
            $message = "Failed to upload ".$attr.", Please try again later";
            if($returnString){
                return $message;
            }
            return $this->addError($attr,$message);
        }

        $message = "Failed to upload ".$attr;
        if($returnString){
            return $message;
        }
        return $this->addError($attr,$message);
    }

    public function afterDelete()
    {
        $this->deleteTempFile();
        $tableName = $this->normalTableName();

        $notSavedAttr = ['description', 'meta'];

        $data = '';
        $params = $this->getAttributes();

        foreach ($notSavedAttr as $savedAttr){
            if($this->hasAttribute($savedAttr)) {
                if (array_key_exists($savedAttr, $params)) {
                    unset($params[$savedAttr]);
                }
            }
        }

        $data = json_encode($params);

        SystemLog::log(
            Constants::USER_ADMINISTRATOR,
            'Deleted record from ['.$tableName.']',
            Constants::LOG_TYPE_DELETED,
            $this->id,
            $data
        );

        parent::afterDelete();
    }

    public function normalTableName(){
        $table = self::tableName();
        $table = str_replace('{{%','',$table);
        $table = str_replace('}}','',$table);
        return $table;
    }

    private function insertTempFileID()
    {
        $tableName = $this->normalTableName();
        if(isset($_POST['tmp_file_id'])){
            $findTempFile = Files::findOne($_POST['tmp_file_id']);
            if($findTempFile!=null){
                $findTempFile->parent_id = $this->id;
                $findTempFile->table = $tableName;
                $findTempFile->save();
            }
        }
    }

    public function deleteTempFile($tempFileId=null)
    {

    }


}
