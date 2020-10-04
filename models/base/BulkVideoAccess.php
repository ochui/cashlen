<?php
/**
 * Created by PhpStorm.
 * User: prabhjyot
 * Date: 2019-07-27
 * Time: 16:00
 */

namespace app\models\base;


use app\common\Constants;
use app\models\activerecord\Users;
use yii\base\Model;
use app\common\Helper;
use Yii;

class BulkVideoAccess extends Model
{
    public $users;
    public $subjects;
    public $categories;
    public $videos;

    public $reset = false;

    const SCENARIO_SUBJECTS = 'subjects-bulk';
    const SCENARIO_NOT_ALLOW_SUBJECTS = 'not-allowed-subjects-bulk';

    const SCENARIO_CATEGORIES = 'categories-bulk';
    const SCENARIO_NOT_ALLOW_CATEGORIES = 'not-allowed-categories-bulk';

    const SCENARIO_VIDEOS = 'videos-bulk';


    public function beforeValidate()
    {
       if($this->scenario==self::SCENARIO_CATEGORIES){
            if(isset($_POST[self::SCENARIO_CATEGORIES]['users'])){
                $this->users = $_POST[self::SCENARIO_CATEGORIES]['users'];
            }
        }elseif($this->scenario==self::SCENARIO_NOT_ALLOW_CATEGORIES){
           if(isset($_POST[self::SCENARIO_NOT_ALLOW_CATEGORIES]['users'])){
               $this->users = $_POST[self::SCENARIO_NOT_ALLOW_CATEGORIES]['users'];
           }
       }elseif($this->scenario==self::SCENARIO_SUBJECTS){
           if(isset($_POST[self::SCENARIO_SUBJECTS]['users'])){
               $this->users = $_POST[self::SCENARIO_SUBJECTS]['users'];
           }
       }elseif($this->scenario==self::SCENARIO_NOT_ALLOW_SUBJECTS){
           if(isset($_POST[self::SCENARIO_NOT_ALLOW_SUBJECTS]['users'])){
               $this->users = $_POST[self::SCENARIO_NOT_ALLOW_SUBJECTS]['users'];
           }
       }else{
           if(isset($_POST[self::SCENARIO_VIDEOS]['users'])){
               $this->users = $_POST[self::SCENARIO_VIDEOS]['users'];
           }
        }


        if(is_array($this->users) && count($this->users)>0){
            //ok
        }else{
            $this->addError('users','Select user(s)');
        }
        return parent::beforeValidate();
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['users'], 'required'],

            [['subjects'], 'safe','on'=>self::SCENARIO_SUBJECTS],
            [['subjects'], 'safe','on'=>self::SCENARIO_NOT_ALLOW_SUBJECTS],

            [['categories'], 'safe','on'=>self::SCENARIO_CATEGORIES],
            [['categories'], 'safe','on'=>self::SCENARIO_NOT_ALLOW_CATEGORIES],

            [['videos'], 'safe','on'=>self::SCENARIO_VIDEOS],
        ];
    }


    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
            self::SCENARIO_SUBJECTS=>['subjects'],
            self::SCENARIO_NOT_ALLOW_SUBJECTS=>['not-allow-subjects'],
            self::SCENARIO_CATEGORIES=>['categories'],
            self::SCENARIO_NOT_ALLOW_CATEGORIES=>['not-allow-categories'],
            self::SCENARIO_VIDEOS=>['videos'],
        ]);
    }

    public function formName()
    {
        if($this->scenario == self::SCENARIO_VIDEOS)
            return 'videos-bulk';
        elseif($this->scenario == self::SCENARIO_CATEGORIES)
            return 'categories-bulk';
        elseif($this->scenario == self::SCENARIO_NOT_ALLOW_CATEGORIES)
            return 'not-allowed-categories-bulk';
        elseif($this->scenario == self::SCENARIO_SUBJECTS)
            return 'subjects-bulk';
        elseif($this->scenario == self::SCENARIO_NOT_ALLOW_SUBJECTS)
            return 'not-allowed-subjects-bulk';
    }

    public function assign()
    {
        if($this->scenario==self::SCENARIO_CATEGORIES){
            return $this->assignCategories();
        }elseif($this->scenario==self::SCENARIO_NOT_ALLOW_CATEGORIES){
            return $this->assignNotAllowedCategories();
        }elseif($this->scenario==self::SCENARIO_SUBJECTS){
            return $this->assignSubjects();
        }elseif($this->scenario==self::SCENARIO_NOT_ALLOW_SUBJECTS){
            return $this->assignNotAllowedSubjects();
        }else{
            return $this->assignVideos();
        }
    }

    public function assignSubjects(){
        if(is_array($this->subjects) && count($this->subjects)>0){
            foreach ($this->users as $userID){
                $user = Users::findOne($userID);
                if($user!=null) {
                    $user->assignAccess($this->subjects, 'subjects_allowed', $this->reset);
                }
            }
        }else{
            foreach ($this->users as $userID){
                $user = Users::findOne($userID);
                if($user!=null){
                    $user->assignAccess([],'subjects_allowed',$this->reset);
                }
            }
        }
        return true;
    }

    public function assignCategories(){
        if(is_array($this->categories) && count($this->categories)>0){
            foreach ($this->users as $userID){
                $user = Users::findOne($userID);
                if($user!=null) {
                    $user->assignAccess($this->categories, 'categories_allowed', $this->reset);
                }
            }
        }else{
            foreach ($this->users as $userID){
                $user = Users::findOne($userID);
                if($user!=null){
                    $user->assignAccess([],'categories_allowed',$this->reset);
                }
            }
        }
        return true;
    }

    public function assignNotAllowedSubjects(){
        if(is_array($this->subjects) && count($this->subjects)>0){
            foreach ($this->users as $userID){
                $user = Users::findOne($userID);
                if($user!=null) {
                    $user->assignAccess($this->subjects, 'subjects_not_allowed', $this->reset);
                }
            }
        }else{
            foreach ($this->users as $userID){
                $user = Users::findOne($userID);
                if($user!=null){
                    $user->assignAccess([],'subjects_not_allowed',$this->reset);
                }
            }
        }
        return true;
    }

    public function assignNotAllowedCategories(){
        if(is_array($this->categories) && count($this->categories)>0){
            foreach ($this->users as $userID){
                $user = Users::findOne($userID);
                if($user!=null) {
                    $user->assignAccess($this->categories, 'categories_not_allowed', $this->reset);
                }
            }
        }else{
            foreach ($this->users as $userID){
                $user = Users::findOne($userID);
                if($user!=null){
                    $user->assignAccess([],'categories_not_allowed',$this->reset);
                }
            }
        }
        return true;
    }

    public function assignVideos(){
        if(is_array($this->videos) && count($this->videos)>0){
            foreach ($this->users as $userID){
                $user = Users::findOne($userID);
                if($user!=null) {
                    $user->assignAccess($this->videos, 'videos_allowed', $this->reset);
                }
            }
        }else{
            foreach ($this->users as $userID){
                $user = Users::findOne($userID);
                if($user!=null){
                    $user->assignAccess([],'videos_allowed',$this->reset);
                }
            }
        }
        return true;
    }

}

