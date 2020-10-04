<?php


namespace app\common;


use app\background\EmailSender;
use app\models\activerecord\Countries;
use app\models\activerecord\Leads;
use app\models\activerecord\Settings;
use app\models\activerecord\Submissions;
use app\models\activerecord\UserAttendance;
use app\models\activerecord\Users;
use DateInterval;
use DatePeriod;
use DateTime;
use finfo;
use IP2Location\Database;
use Mailgun\Mailgun;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class Helper
{
    const RECAPTCHA_VERIFY_URL = "https://www.google.com/recaptcha/api/siteverify";

    /**
     * @param $code
     * @return Users
     */
    public static function getSponsor($code){
        /**
         * @var Users $user
         */
        $user = Users::find()->where(["referral_code"=>$code])
            ->andWhere(" status_id = :active",
                [':active'=>Constants::USER_STATUS_ACTIVE])
            ->one();
        if($user!=null){
            return $user;
        }
        return null;
    }

    public static function printTime($time){
        if($time!=null) {
            return date(Constants::SITE_DATE_TIME_FORMAT, strtotime($time));
        }else{
            return '-';
        }
    }

    public static function saveFilePathDirect($type)
    {
        $timestamp=time();
        $mainUploadPath = Yii::$app->params['uploadPath'];
        $lastString = substr($mainUploadPath, -1); // returns last string
        $followString = "";
        if($lastString!="/"){
            $followString = "/";
        }
        $filePath = $mainUploadPath.$followString.$type."/".date('Y',$timestamp)."/".date('M',$timestamp)."/".date('dD',$timestamp);
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
        return $filePath."/";
    }

    public static function getCountryFromCode($code,$return_model = false){
        $country = Countries::find()->where(['iso'=>$code])->one();
        if($country) {
            if($return_model)
                return $country;
            else
                return $country->id;
        }
        return null;
    }

    public static function getGoogleSecretKey(){
        $identity = Yii::$app->user->identity;
        $session = Yii::$app->session;
        if ($session->has('google_secret')){
            return $session->get('google_secret');
        }

        $ga = new \PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl(urlencode($identity->username." at ".Yii::$app->name), $secret);

        $googleSecret = array(
            'code'=>$secret,
            'qr_code'=>$qrCodeUrl
        );

        $session->set('google_secret',$googleSecret);
        return $googleSecret;
    }

    public static function generateRandomKey($prefix = "K",$length = 16){
        return $prefix.'.'. Yii::$app->security->generateRandomString($length);
    }

    public static function generateRandomString($length = 32){
        return Yii::$app->security->generateRandomString($length);
    }

    /**
     * @param $prefix
     * @param int $length
     * @param BaseActiveRecord $class
     * @param string $property
     * @return string
     */
    public static function generateUniqueKey($prefix = 'S',  $class, $property = 'identifier',$length = 16){
        $identifier = self::generateRandomKey($prefix,$length);

        $duplicate = $class::find()->where([$property=>$identifier])->one();

        if($duplicate != null)
            return self::generateUniqueKey($prefix,$class,$property,$length);

        return $identifier;
    }
    public static function decodeBase58($input) {
        $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";

        $out = array_fill(0, 25, 0);
        for($i=0;$i<strlen($input);$i++){
            if(($p=strpos($alphabet, $input[$i]))===false){
                return false;
            }
            $c = $p;
            for ($j = 25; $j--; ) {
                $c += (int)(58 * $out[$j]);
                $out[$j] = (int)($c % 256);
                $c /= 256;
                $c = (int)$c;
            }
            if($c != 0){
                return false;
            }
        }

        $result = "";
        foreach($out as $val){
            $result .= chr($val);
        }

        return $result;
    }
    public static function getSetting($id){
        /**
         * @var Settings $setting
         */
        $setting =  Settings::findOne($id);
        return $setting;
    }


    public static function getCountryIDFromIP($ip,$ipv6 = false){
        $country = self::getCountryFromIP($ip,$ipv6);
        if($country == null)
            $country = self::getCountryFromIP($ip,!$ipv6);

        if(array_key_exists('countryCode',$country)){
            return self::getCountryFromCode($country['countryCode']);
        }

        return false;
    }

    public static function getCountryFromIP($ip,$ipv6 = false){
        $db = null;
        if($ipv6)
            try {
                $db = new Database(Yii::getAlias("@app") . '/data/ip2location_ipv6.bin',
                    Database::FILE_IO);
            } catch (\Exception $e) {
            }
        else
            try {
                $db = new Database(Yii::getAlias("@app") . '/data/ip2location.bin',
                    Database::FILE_IO);
            } catch (\Exception $e) {
            }

        if($db)
            return $db->lookup($ip, Database::ALL);
        else
            return false;

    }

    public static function verifyCaptcha($response=null){
        if($response==null) {
            if (isset($_POST['g-recaptcha-response'])) {
                $response = $_POST['g-recaptcha-response'];
            }
        }
        $captcha_secret = Settings::findOne(Constants::SETTINGS_RECAPTCHA_SECRET);

        $data = [
            'secret'=>$captcha_secret->value,
            'response'=>$response
        ];

        $ch = curl_init(self::RECAPTCHA_VERIFY_URL);
        curl_setopt_array($ch,[
            CURLOPT_FOLLOWLOCATION=>true,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_SSL_VERIFYHOST=>false,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_POST=>true,
            CURLOPT_POSTFIELDS=>http_build_query($data),
            CURLOPT_TIMEOUT=>5,
        ]);
        $response = curl_exec($ch);

        if($response) {
            $response = json_decode($response);
            if($response){
                if($response->success){
                    return true;
                }
            }
        }

        return false;
    }

    public static function unlinkFile($file)
    {
        if (Yii::$app instanceof Yii\console\Application) {
            $file = __DIR__."/../web/".$file;
        }
        if(file_exists($file)){
            unlink($file);
        }else{
            SystemLog::log(Constants::USER_ADMINISTRATOR,'File path not exist for deleting '.$file,Constants::LOG_TYPE_ERROR);
        }
    }

    public static function withBaseUrl($path)
    {
        return $_ENV['APP_URL'].$path;
    }

    public static function getWebsiteTitle()
    {
        $pageTitle = Yii::$app->controller->view->title;
        if($pageTitle!=null){
            $pageTitle = $pageTitle." | ".Yii::$app->name;
        }else{
            $pageTitle = Yii::$app->name;
        }
        return Html::encode($pageTitle);
    }

    public static function generateRandomInteger(){
        return rand(1000,9999).rand(5000,9999);
    }


    public static function formFieldTemplate($icon)
    {
        return '<span class="input-group-addon bg-white"><i class="'.$icon.'"></i></span>{input}<div class="form-help-blocks">{error}{hint}</div>';
    }

    public static function isAdmin()
    {
        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            if ($identity->role_id == Constants::USER_ROLE_ADMIN) {
                return true;
            }
        }
        return false;
    }

    public static function isOutlookEmail($provider)
    {
        switch ($provider){
            case 'hotmail.com':
            case 'live.com':
            case 'msn.com':
            case 'passport.com':
            case 'outlook.com':
                return true;
                break;
        }
        return false;
    }

    /**
     * @param $to
     * @param $from
     * @param $subject
     * @param $message
     * @return bool
     */
    public static function sendEmail($to,$subject,$message,$from=null){

        $provider = '';
        $exploded = explode('@',$to);

        if(is_array($exploded) && array_key_exists(1,$exploded)){
            $provider = $exploded[1];
        }

        $isOutlook = Helper::isOutlookEmail($provider);

        $message = Yii::$app->controller->renderFile(__DIR__."/../mail/layouts/email_template.php",compact(
            'message','provider', 'isOutlook'
        ));

        if($from==null){
            $from = $_ENV['EMAIL_FROM'];
        }

        //echo $message;die;
        return self::sendEmailCall($to, $from, $subject,$message);
      /*  Yii::$app->queue->push(new EmailSender([
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
            'from' => $from,
        ]));*/
    }

    /**
     * @param $to
     * @param $from
     * @param $subject
     * @param $message
     * @return bool
     */
    public static function sendEmailCall($from, $to, $subject, $message){
        $mailGunKey = Helper::getSetting(Constants::SETTINGS_MAILGUN_KEY);
        $mailDomain = Helper::getSetting(Constants::SETTINGS_MAILGUN_DOMAIN);
        $mg = Mailgun::create($mailGunKey->value); // For US servers
        try{
            if($mg->messages()->send($mailDomain->value, [
                'from'    => $from,
                'to'      => $to,
                'subject' => $subject,
                'html'    => $message,
            ]))
            {
                return true;
            }else{
                return false;
            }
        }catch(\Exception $e){
            return false;
        }
    }


    public static function shortenUA($useragent)
    {
        $ua = $useragent;
        $ismobile = 'no';

        $os = 'default';
        if(stristr($ua, 'windows') != '') $os = 'win';
        if(stristr($ua, 'mac') != '') $os = 'mac';
        if(stristr($ua, 'linux') != '') $os = 'linux';
        if(stristr($ua, 'android') != '') $os = 'android';
        if(stristr($ua, 'iphone') != '') $os = 'iphone';
        if(stristr($ua, 'ipad') != '') $os = 'ipad';

        $mobile = ($ismobile) ? ' mobile' : '';
        switch($ua)
        {
            case stristr($ua, 'firefox') == true:
                $ver = explode('/',$ua);
                $ver = explode('.',$ver[count($ver)-1]);
                $o = 'firefox-'.$ver[0].' firefox-'.$os;
                break;
            case stristr($ua, 'opr') == true:
                $ver = explode('/',$ua);
                $ver = explode('.',$ver[count($ver)-1]);
                $o = 'opera-'.$ver[0].' opera-'.$os;
                break;
            case stristr($ua, 'safari') != '' && stristr($ua, 'chrome') === false:
                $o = 'safari-'.$os;
                break;
            case stristr($ua, 'chrome') == true:
                $ver = explode('Chrome/',$ua);
                $ver = explode('.',$ver[1]);
                $o = 'chrome-'.$ver[0].' chrome-'.$os;
                break;
            case stristr($ua, 'msie') == true:
                $ver = explode('MSIE ', $ua);
                $ver = explode('.', $ver[1]);
                $o = 'iexplore ie-'.$ver[0];
                break;
            default:
                $o = 'Unknown Browser';
                break;
        }
        return ucwords($o);
    }

    public static function getSettingValue($settingId)
    {
        $setting = self::getSetting($settingId);
        if($setting!=null){
            return $setting->value;
        }
        return '';
    }

    public static function roman2number($roman){
        $conv = array(
            array("letter" => 'I', "number" => 1),
            array("letter" => 'V', "number" => 5),
            array("letter" => 'X', "number" => 10),
            array("letter" => 'L', "number" => 50),
            array("letter" => 'C', "number" => 100),
            array("letter" => 'D', "number" => 500),
            array("letter" => 'M', "number" => 1000),
            array("letter" => 0, "number" => 0)
        );
        $arabic = 0;
        $state = 0;
        $sidx = 0;
        $len = strlen($roman);

        while ($len >= 0) {
            $i = 0;
            $sidx = $len;
            while ($conv[$i]['number'] > 0) {
                if (strtoupper(@$roman[$sidx]) == $conv[$i]['letter']) {
                    if ($state > $conv[$i]['number']) {
                        $arabic -= $conv[$i]['number'];
                    } else {
                        $arabic += $conv[$i]['number'];
                        $state = $conv[$i]['number'];
                    }
                }
                $i++;
            }
            $len--;
        }
        return($arabic);
    }


    public static function slugifyString($text)
    {
        $text = preg_replace_callback('/\b[0IVXLCDM]+\b/', function($m) {
            return self::roman2number($m[0]);
        },$text);

        $text = str_replace('+2','12',$text);
        $text = str_replace('+1','11',$text);

        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    public static function getActiveInactive($value)
    {
        if($value==Constants::YES_FLAG){
            return '<span class="badge badge-success">Yes</span>';
        }
        return '<span class="badge badge-danger">No</span>';
    }



    public static function emptyOrValue($value,$number=false){
        if($value==null){
            if($number){
                return 0;
            }
            return 'N/A';
        }
        return $value;
    }

    public static function getReadableStorageUnits($bytes)
    {
        if($bytes==''){
            return 'N/A';
        }
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public static function getReadableTime($seconds)
    {
        if($seconds==''){
            return 'N/A';
        }
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;
        $hourTxt = 'hr';
        if($hours>1){
            $hourTxt = 'hrs';
        }
        $minTxt = 'min';
        if($minutes>1){
            $minTxt = 'mins';
        }
        $secondTxt = 'sec';
        if($seconds>1){
            $secondTxt = 'secs';
        }
        return $hours > 0 ? "$hours $hourTxt, $minutes $minTxt" : ($minutes > 0 ? "$minutes $minTxt, $seconds $secondTxt" : "$seconds $secondTxt");
    }

    public static function printNumber($number)
    {
        return $number;
    }

    public static function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    public static function get_http_response_code($url) {
        try{$headers = get_headers($url);
            return substr($headers[0], 9, 3);
        }
        catch(\Exception $e){
            return false;
        }
    }

    public static function cleanArray($array)
    {
        if(is_array($array) && count($array)>0){
            foreach ($array as $key=>$value){
                if(trim($value)==''){
                    unset($array[$key]);
                }
            }
        }
        return $array;
    }

    public static function printMinutes($seconds)
    {
        return gmdate("H", $seconds)." hrs ".gmdate("i", $seconds)." mins";
    }

    public static function maxMessageToShow($count)
    {
        if($count>99){
            $count = "99+";
        }
        return $count;
    }

    public static function datesBetween($start, $end, $format = Constants::PHP_DATE_FORMAT_SHORT){
        $array = array();
        $interval = new DateInterval('P1D');
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
        foreach($period as $date) {
            $array[] = $date->format($format);
        }
        return $array;
    }

    public static function getChartAttendanceDate($startDate, $endDate, Users $user)
    {
        $todayDate = date(Constants::PHP_DATE_FORMAT_SHORT);
        $chartData = [];
        $daysBetween = self::datesBetween($startDate, $endDate);
        if($daysBetween!=null){
            foreach ($daysBetween as $day){
                if($day<=$todayDate) {
                    $findAttendance = $user->getAttendanceOfDay($day, false);
                    $startTime = $endTime = null;
                    if($findAttendance!=null) {
                        $startTime = $findAttendance->start;
                        $endTime = $findAttendance->end;
                    }
                    if ($startTime != null) {
                        $startTime = strtotime($startTime);
                    }
                    if ($endTime != null) {
                        $endTime = strtotime($endTime);
                    }
                    $chartData[] = [
                        'x' => $day,
                        'start' => $startTime,
                        'end' => $endTime,
                    ];
                }
            }
        }
        return $chartData;
    }

    public static function getAdminStats()
    {
        $stats = [
            'total_submissions' => Submissions::find()->count(),
            'final_url' => Helper::getSettingValue(Constants::SETTINGS_FINAL_URL)
        ];
        return $stats;
    }

    public static function sendAsApi(\yii\data\ActiveDataProvider $dataProvider, $page, $perPage)
    {
        if($page==null){
            $page = 1;
        }
        if($perPage==null){
            $perPage = $dataProvider->pagination->defaultPageSize;
        }
        return [
            'items' => $dataProvider->getModels(),
            '_meta' => [
                'currentPage' => (int)$page,
                'pageCount' => $dataProvider->getCount(),
                'perPage' => $perPage,
                'totalCount' => $dataProvider->getTotalCount(),
            ]
        ];
    }

    public static function getBirthYears()
    {
        $years = [];
        $lastPreviousYear = 1904;
        $maxLatestYear = date('Y')-17;
        while ($lastPreviousYear<=$maxLatestYear){
            $years[$lastPreviousYear] = $lastPreviousYear;
            $lastPreviousYear++;
        }
        return $years;
    }

    public static function getUsStates()
    {
        $statesReturn = [];
        $states = ['AK','AL','AR','AZ','CA','CO','CT','DC','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY'];
        foreach ($states as $state){
            $statesReturn[strtolower($state)] = strtoupper($state);
        }
        return $statesReturn;
    }

    public static function fixUnderScores($val)
    {
        return ucwords(str_replace('_',' ',$val));
    }

    public static function convertToYears($data)
    {
        switch ($data){
            case '1':
            case '0-1':
                return '1 Year or Less';
                break;
            case '2':
                return '2 Years';
                break;
            case '3':
                return '3 years';
                break;
            case '4+':
                return 'More than 4 Years';
                break;
            default:
                return $data;
                break;
        }
    }

    public static function YesOrNoContent($val)
    {
        if($val==Constants::YES_FLAG){
            return 'Yes';
        }
        return 'No';
    }

}
