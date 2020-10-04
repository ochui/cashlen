<?php

namespace app\models\activerecord;

use app\common\BaseActiveRecord;
use app\common\Helper;
use Yii;

/**
 * This is the model class for table "submissions".
 *
 * @property int $id
 * @property string|null $reason_for_loan
 * @property string $loan_amount_required
 * @property string $first_name
 * @property string $last_name
 * @property string|null $last_four_ssn
 * @property string|null $birth_year
 * @property string $zip_code
 * @property string $email
 * @property string $phone_number
 * @property string $dob
 * @property int|null $active_military
 * @property string $street_address
 * @property string|null $years_living_from
 * @property int|null $home_owner
 * @property string|null $employment_status
 * @property string|null $years_with_employer
 * @property string|null $how_often_paid
 * @property string|null $monthly_income
 * @property string|null $next_pay_date
 * @property string|null $employer_name
 * @property string|null $occupation
 * @property string|null $employer_phone_number
 * @property string|null $drivers_license
 * @property string|null $state
 * @property int|null $ssn
 * @property int|null $bank_routing_number
 * @property string|null $account_number
 * @property string|null $bank_name
 * @property string|null $how_get_paid
 * @property string|null $time_with_account
 * @property string|null $account_type
 * @property int|null $unsecured_debt
 * @property string $useragent
 * @property string|null $time
 * @property string $ip
 */
class Submissions extends BaseActiveRecord
{
    public $exportableColumns = [
        'loan_amount_required',
        'first_name',
        'last_name',
        'email',
        'dob',
        'phone_number',
        'street_address',
        'useragent',
        'time',
        'ip'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'submissions';
    }

    public static function eligibleForNextSubmission($ip)
    {
        $lastSubmission = self::find()->where(['ip'=>$ip])->orderBy(['id'=>SORT_DESC])->one();
        if($lastSubmission==null){
            return true;
        }
        $date = strtotime($lastSubmission->time);
        $TwentyFourHourTime = strtotime("-24 hours");
        if($date > $TwentyFourHourTime){
            return false;
        }else{
            return true;
        }
    }


    public function afterFind()
    {
        $this->dob = date('d M Y', strtotime($this->dob));
        $this->employment_status = ucwords($this->employment_status);
        $this->how_often_paid = Helper::fixUnderScores($this->how_often_paid);
        $this->next_pay_date = date('d M', strtotime($this->next_pay_date));
        $this->how_get_paid = Helper::fixUnderScores($this->how_get_paid);
        $this->account_type = ucwords($this->account_type);

        $this->state = strtoupper($this->state);

        $this->years_living_from = Helper::convertToYears($this->years_living_from);
        $this->years_with_employer = Helper::convertToYears($this->years_with_employer);
        $this->time_with_account = Helper::convertToYears($this->time_with_account);

        $this->active_military = Helper::YesOrNoContent($this->active_military);
        $this->home_owner = Helper::YesOrNoContent($this->home_owner);
        $this->unsecured_debt = Helper::YesOrNoContent($this->unsecured_debt);

        if($this->reason_for_loan==null){
            $this->reason_for_loan = '-';
        }

        parent::afterFind();
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reason_for_loan'], 'string'],
            [['loan_amount_required', 'first_name', 'last_name', 'email', 'phone_number', 'dob', 'street_address', 'useragent', 'ip'], 'required'],
            [['active_military', 'home_owner', 'ssn', 'bank_routing_number', 'unsecured_debt'], 'integer'],
            [['time'], 'safe'],
            [['loan_amount_required', 'first_name', 'last_name', 'last_four_ssn', 'birth_year', 'zip_code', 'email', 'phone_number', 'dob', 'street_address', 'years_living_from', 'employment_status', 'years_with_employer', 'how_often_paid', 'monthly_income', 'next_pay_date', 'employer_name', 'occupation', 'employer_phone_number', 'drivers_license', 'state', 'account_number', 'bank_name', 'how_get_paid', 'time_with_account', 'account_type'], 'string', 'max' => 255],
            [['useragent'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 50],

            [['email'],'email'],
            [['account_number'],'string', 'min'=>4, 'max'=> 20],
            [['drivers_license'],'string', 'min'=>6, 'max'=> 20],

            //[['phone_number'],'match','pattern'=>'/^[0-9]{10}$/','message' => 'Phone number must be 10 digits long'],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reason_for_loan' => 'Explain reason for loan',
            'loan_amount_required' => 'Loan Amount Required',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'last_four_ssn' => 'Last 4 digits of SSN#',
            'birth_year' => 'Birth Year',
            'zip_code' => 'Zip Code',
            'email' => 'Email Address',
            'phone_number' => 'Phone Number',
            'dob' => 'D.O.B',
            'active_military' => 'Active Military',
            'street_address' => 'Full Address',
            'years_living_from' => 'How long have you lived at your current address',
            'home_owner' => 'Home Owner',
            'employment_status' => 'Employment Status',
            'years_with_employer' => 'How long have you been with your employer',
            'how_often_paid' => 'How often are you paid',
            'monthly_income' => 'Monthly Income',
            'next_pay_date' => 'Next Pay Date',
            'employer_name' => 'Employer Name',
            'occupation' => 'Occupation',
            'employer_phone_number' => 'Employer Phone Number',
            'drivers_license' => 'Drivers License',
            'state' => 'State ID',
            'ssn' => 'Ssn',
            'bank_routing_number' => 'Bank Routing Number',
            'account_number' => 'Account Number',
            'bank_name' => 'Bank Name',
            'how_get_paid' => 'How do you get paid',
            'time_with_account' => 'How long have you had this bank account',
            'account_type' => 'Account Type',
            'unsecured_debt' => 'Unsecured Debt',
            'useragent' => 'Useragent',
            'time' => 'Time',
            'ip' => 'Ip',
        ];
    }
}
