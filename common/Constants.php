<?php


namespace app\common;


class Constants
{

    const DB_TABLE_OPTIONS = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
    const PHP_DATE_FORMAT = 'Y-m-d H:i:s';
    const APP_DATE_FORMAT = 'd M, Y H:i';
    const PHP_DATE_FORMAT_SHORT = 'Y-m-d';
    const INVOICE_DATE_FORMAT = 'Ymd';
    const SITE_DATE_TIME_FORMAT = 'd M, Y h:i A';
    const DEFAULT_PARENT_PASSWORD = '123456';


    const VIDEO_WATCHED_PERCENTAGE = 5; //percentage of duration to mark any video as watched
    const MAX_VIDEO_BEFORE_WATCHED = 30;//seconds maximum allowed not marked as watched

    const IRREGULAR_USER_DAY_LIMIT = 5;//if any users not watched any lectures for this days that user is irregular

    const BILLING_CYCLE_START_ON = 21;//date when billing cycle start
    const BILLING_CYCLE_END_ON = 20;//date when billing cycle start

    const DAY_LIMIT_FOR_BILLABLE = 15;//show 30 in billing if days are more than this
    const DAY_AFTER_SHOW_SURVEY_BOX = 7;//after how many days survey box should show

    const USER_ROLE_ADMIN = 1;
    const USER_ROLE_USER = 2;

    const SUBJECT_TYPE_NORMAL = 1;
    const SUBJECT_TYPE_CRASH_COURSE = 2;

    const SIGNED_KEY_TYPE_SYSTEM = 'system';
    const SIGNED_KEY_TYPE_USER = 'user';

    const MAX_USER_SIGNED_KEYS = 980;//1000 is limited 1 for system for secure side taking 980
    const DEMO_ACCOUNT_VALIDITY = 3; //in days

    const DEFAULT_VIEWS_ALLOWED = 3; //default views allowed on one lecture

    const USER_ADMINISTRATOR = 1;

    const USER_STATUS_ACTIVE = 1;
    const USER_STATUS_INACTIVE = 2;
    const USER_STATUS_BANNED = 3;

    const UPLOAD_PENDING = 0;
    const UPLOAD_QUEUED = 1;
    const UPLOAD_COMPLETED = 2;
    const UPLOAD_FAILED = 3;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const REQUEST_PENDING = 0;
    const REQUEST_APPROVED = 1;
    const REQUEST_CANCELLED = 2;

    const YES_FLAG = 1;
    const NO_FLAG = 0;


    const SETTINGS_FINAL_URL = 1;
    const SETTINGS_RE_CAPTCHA_SITE_KEY = 2;
    const SETTINGS_RE_CAPTCHA_SECRET_EY = 3;
    const SETTINGS_THANKS_PAGE_PIXEL_CODE = 4;

    const CURRENCY_PRECISION = 2;

    const FILE_PATH_SYSTEM = 'system';
    const FILE_PATH_SSH = 'keys';
    const ADMIN_REFERRAL_CODE = 'admin';

    const LOG_TYPE_INIT = "init";
    const LOG_TYPE_ERROR = "error";
    const LOG_TYPE_TEST_ENGINE_API_ERROR = "testing_engine_error";
    const LOG_TYPE_SOCKET = "socket";
    const LOG_TYPE_ERROR_PERSIST = "error_persist";
    const LOG_TYPE_ERROR_EXCEPTION = "error_exception";

    const LOG_TYPE_USERS = "users";

    const LOG_TYPE_USER_LOGIN = "user_login";
    const LOG_TYPE_USER_REGISTER = "user_register";
    const LOG_TYPE_USER_FAILED_LOGIN = "user_failed_login";
    const LOG_TYPE_USER_PROFILE_UPDATE = "user_profile_update";
    const LOG_TYPE_USER_FORGOT_PASSWORD = "user_forgot_password";
    const LOG_TYPE_USER_ACCOUNT_ACTIVATED = "user_account_activated";
    const LOG_TYPE_USER_PASSWORD_CHANGED = "user_password_changed";
    const LOG_TYPE_USER_INFORMED = "user_informed";
    const LOG_TYPE_USER_DELETED = "user_deleted";
    const LOG_TYPE_DELETED = "deleted";
    const LOG_TYPE_USER_TWO_FACTOR_AUTHENTICATION = "user_two_fa";

      public static $LOG_TYPES = [
        'system' => [
            self::LOG_TYPE_INIT => 'System Initialized',
            self::LOG_TYPE_ERROR => 'System Error',
            self::LOG_TYPE_ERROR_PERSIST => 'Error Persist',
            self::LOG_TYPE_ERROR_EXCEPTION => 'Error Exception',
        ],
        'user' => [
            self::LOG_TYPE_USER_LOGIN => 'User Login',
            self::LOG_TYPE_USER_REGISTER => 'User Registered',
            self::LOG_TYPE_USER_FAILED_LOGIN => 'User Failed Login',
            self::LOG_TYPE_USER_FORGOT_PASSWORD => 'User Forgot Password',
            self::LOG_TYPE_USER_ACCOUNT_ACTIVATED => 'User Account activated',
            self::LOG_TYPE_USER_PASSWORD_CHANGED => 'User Password changed',
            self::LOG_TYPE_USER_PROFILE_UPDATE => 'User Profile update',
        ],
    ];

    public static $GENDERS = [
        'male' => 'Male',
        'female' => 'Female',
    ];

    public static $ACTIVE_INACTIVE_DROPDOWN = [
        self::YES_FLAG => 'Active',
        self::NO_FLAG => 'Inactive',
    ];

    public static $YES_NO_DROPDOWN = [
        self::YES_FLAG => 'Yes',
        self::NO_FLAG => 'No',
    ];

    public static $LEAD_STATUS = [
        self::LEAD_ACTIVE => 'Active',
        self::LEAD_COMPLETED => 'Completed',
    ];

    const LEAD_ACTIVE = "active";
    const LEAD_COMPLETED = "completed";

    const ATTENDANCE_NOT_RECORDED = "not_recorded";
    const ATTENDANCE_PRESENT = "present";
    const ATTENDANCE_ABSENT = "absent";
    const ATTENDANCE_LEAVE = "leave";

    const CLOCK_IN = "in";
    const CLOCK_OUT = "out";

}

