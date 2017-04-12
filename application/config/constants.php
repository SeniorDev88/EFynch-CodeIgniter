<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
define('TITLE','Efynch');
define('PWD','efynchpwd*');
define('ADMINEMAIL','admin@efynch.com');
define('REG_MAIL','service@efynch.com');
define('ADMIN','efynchadmin');//for admin url
define('DEVICESECRET','efynch&%$#@14788');
define('APIKEY','efynch&^%$$#@4567');
define('VERSION','1.0');
define('LIMIT','10');
define('PAGE_LIMIT','10');
define('DROPBOXKEY','6e08sj9fxmqaaif');
define('GOOGLE_API','AIzaSyB4N_gnrB8SbnnwxUw8Zig4FtqzPX23iGs');
define('GOOGLE_CLIENTID','334111085159-cted2o44potjiok5uuhk4b9lcv169n6v');
define('GOOGLESITEKEY','6Le9ThYTAAAAAJ1RWHA0kAgi7C9fpQNPUr49_WPk');
define('GOOGLESECRETEKEY','6Le9ThYTAAAAAGRmUuJUkhAPvaBMk_0Nq-Ei1W-B');
/* define('DROPBOXKEY','vt45w8tgcii5pum');
define('GOOGLE_API','AIzaSyCA1_WzVUA7OGjNwaUVf8AuGDCkdP7Hx6E');
define('GOOGLE_CLIENTID','672660891019-0sadjkfmagj630kttkhe7mb95vcduk6t');
define('GOOGLESITEKEY','6LcuKSATAAAAAOmrrSzn8vyhULOWKO4VZi7GY3mE');
define('GOOGLESECRETEKEY','6LcuKSATAAAAAOgKhbrEALQtF0rHfK25feQLUwdK'); */



define('SERVICEAMNT','0.00');
define('SERVICEFEE','5');
define("PercentageAboveFiveHundred",3);
/*Demo
define('BT_MASTER_MERCHANTID','innovativeconsultants');
define('BT_environment','sandbox');
define('BT_merchantId','vmhphgj545ygqxj2');
define('BT_publicKey','gy6ym2tzrxchbxgw');
define('BT_privateKey','6c59a9118bb3b28059311ef090a32023');
*/



/*define('BT_MASTER_MERCHANTID','EfynchcomINC_marketplace');
define('BT_environment','production');
define('BT_merchantId','4n89y2t923nt6k24');
define('BT_publicKey','vrh9c2pbjtrkn399');
define('BT_privateKey','476b22c4d0b2cbf7386e5213e77a3edd');*/ 


/*define('BT_MASTER_MERCHANTID','prismsports');
define('BT_environment','sandbox');
define('BT_merchantId','6yrpbbsbvzk2k6jy');
define('BT_publicKey','yzp57qmb8d7bwy79');
define('BT_privateKey','fb1e30cf09fc5935e8e64547159b9ba4');*/



define('GOOGLE_CAPTCHA_KEY','6Lcn7ykTAAAAAEqJjr2NwHThjI_SufIxVGbWntqO');
define('GOOGLE_CAPTCHA_SECRET','6Lcn7ykTAAAAAHJCncoe6-D2obdJZPdcQYfW7BB6');

/**************
 * Api constants
 */
define("API_KEY",'Asd12!8D');
define("INVALID_ACCESS",'Invalid Access');
define("INVALID_ACCESS_MESS",'Invalid Access Key Found');
define("SERVER_ERROR",'Server Issue');
define("SERVER_ERROR_MESS",'Its seems that server is not responding');
define('MISSING_PARAMS','Listed parameter(s) is/are either empty or not sent in query!');
define("NO_ERROR",'No Error');
/*
 *
 *
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

/*define('USER_IMAGE','https://efynch.com/assets/profImgs/original/00000/');
define('USER_IMAGE_CROP','https://efynch.com/assets/profImgs/crop/00000/');
define("DEFAULT_USER","http://demo.icwares.com/clients/dev/efynch/599/vx1/images/defaultimg.jpg");
define("JOB_IMG","https://efynch.com/assets/docs/00000/");*/
define('USER_IMAGE','https://efynch.com/assets/profImgs/original/00000/');
define('USER_IMAGE_CROP','https://efynch.com/assets/profImgs/crop/00000/');
define("DEFAULT_USER","http://demo.icwares.com/clients/dev/efynch/599/vx1/images/defaultimg.jpg");
define("JOB_IMG","https://efynch.com/assets/docs/00000/");


//define("GOOGLE_API_KEY", "AIzaSyBv3TBaC19Xb7YXraugHUpQRjI7pWQm4ak");
define("GOOGLE_API_KEY", "AIzaSyBnO6ANEeHIonipqq9reIB4qUy0KnAyW9w");
//AIzaSyBS4AAzbj3si1U0F2qw8TKmW3_Pn90p7j8
define("GOOGLE_GCM_URL", "https://fcm.googleapis.com/fcm/send");





define('BT_MASTER_MERCHANTID','EfynchcomINC_marketplace');
define('BT_environment','production');
define('BT_merchantId','4n89y2t923nt6k24');
define('BT_publicKey','vrh9c2pbjtrkn399');
define('BT_privateKey','476b22c4d0b2cbf7386e5213e77a3edd');

/* End of file constants.php */
/* Location: ./application/config/constants.php */
function getContent($key){
    $ci = &get_instance();
    return $ci->lang->line($key);
}

