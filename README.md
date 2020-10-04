<h1 >Yii 2 Project Template</h1>
<h5>by teamOxio Technologies Private Limited</h5>
  
Includes:
- Yii2 queue with amqp-lib
- Ip2location with database for ipv4 and ipv6
- 2amigos/2fa-library
- Google recaptcha support
- User login and register flow with unique sessions, system logs and referrals
- RESTful API support and API module
- MySQL tables: settings, user_roles, user_status, countries, users, user_login_history, logs, user_sessions and background_tasks
- Support for RabbitMQ background workers using configuration in config/queue.php
- Admin account: username: backoffice, password: test@123
- Theme support, put your theme in themes/backend/views, place your theme web accessible assets under web/backend and configure app\BackendAsset with css and js files.

Helper Methods available:

- Helper::getCountryFromIP, getCountryFromCode and getCountryIDFromIP
- Helper::getCryptoPrice
- Helper::verifyCaptcha
- Helper::validateBTCAddress
- app\common\Files class to write, read and download files
- Class LoggableException - logs to the mysql logs table and PersistException which can be used when a database row fails to save, it automatically logs the error to logs table.
- Traits GetSet, Singleton
- Abstract Classes BaseWorker - RabbitMQ background tasks can extend this, BaseController - for Web Controllers, BaseActiveRecord - for ActiveRecord models, RetryableWorker - for Background Tasks that should be retried.

Pending:

- Email support from MailGun or SES
