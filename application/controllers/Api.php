<?php
/****************************/
class Api extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'Api_model',
            'User_model',
            'Job_model'
        ));
        $this->load->library(array(
            'corefunctions',
            'hedercontroller'
        ));

        require_once(APPPATH.'third_party/braintree/lib/Braintree.php');

        Braintree\Configuration::environment(BT_environment);
        Braintree\Configuration::merchantId(BT_merchantId);
        Braintree\Configuration::publicKey(BT_publicKey);
        Braintree\Configuration::privateKey(BT_privateKey);
    }
    public function index() {
        $this->checkSync();
        $this->decodeInput();
        $this->runAPI();
    }

    private function checkSync() {
        /*if ($_REQUEST["checksync"]) {
        print "Check Sync Test";
        exit;
        }
        */
    }
    private function decodeInput() {
        $jsonData   = file_get_contents('php://input');
        $input      = json_decode($jsonData, true);
        /* Fake Request */
        //$input = $this -> fakeRequest();
        $this->data = $input;
        if ($input["parameters"]) {
            $this->values = $input["parameters"];
        }
        $this->apiLog($input);

        $this->checkPostRequest();
        $this->apiDetails = $input["apiInfo"];
        $this->apiKeyValidate($input["apiInfo"]["apiKey"]);
        $this->versionCheck($input["apiInfo"]["appVersion"]);
        //$this -> api -> deviceKeyCheck($input["apiInfo"]);
    }
    private function runAPI() {
        $neglects = array(
            'register',
            'login',
            'logout',
            'forgotpassword',
            'checkemailexists',
            'inserttotables',
            'signupdropdowns',
            'registerverification',
            'resetpassword',
            'resendemailverification',
            'checkexstingemail'
            //'save_rating',
            //'get_completed_jobs',
            //'createjob',
            //'jobboardmenu'
            
        );
        if (!in_array($this->data['apifunction'], $neglects)) {
            //if (!$this->session->userdata('logType')) {
            $this->checkSession($this->data);
            //}
        }
        $function = $this->data['apifunction'];
        $this->$function();
    }
    /* Return JSON */
    public function returnJson($array) {
        header("Content-type: application/json; charset=utf-8");
        print json_encode($array);
        exit;
    }
    /* Return JSON With Error*/
    public function returnError($errMsg, $errorCode = 0) {
        $return["status"]       = $errorCode;
        $return["errorMessage"] = $errMsg;
        $this->returnJson($return);
    }
    /* Check Post Request */
    public function checkPostRequest() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->returnError('Use Post Request');
        }
    }
    /* Validate API Key */
    public function apiKeyValidate($apiKey) {
        if ($apiKey != APIKEY) {
            $this->returnError('Invalid API Key');
        }
    }
    /* Version Check */
    public function versionCheck($version) {
        if ($version != VERSION) {
            $this->returnError('A new version of this app is available. Please download it here.', "-2");
        }
    }
    public function apiLog($input) {
        $req_dump = print_r($input, TRUE);
        $fp       = fopen('assets/request.txt', 'w+');
        fwrite($fp, $req_dump);
        fclose($fp);
        //$this->Api_model->addLog($req_dump);
        //$return["status"]         = 1;
        //$return["successMessage"] = "Log Request";
        //$this->returnJson($return);
    }
    public function returnSessionError() {
        $return["status"]       = -1;
        $return["errorMessage"] = "Sorry Invalid Session Please Login Again";
        $this->returnJson($return);
    }
    public function checkSession($data) {
        global $input;
        /*
                  if( !isset( $_SESSION["userId"] ) ){
                      $this->returnSessionError();
                  }
                  else{
                    $this->session->set_userdata($_SESSION);
                  }
                  */

        if (!$this->session->userdata('userId')) {
            $this->returnSessionError();
        }

        if ($data["apiInfo"]["enckey"] != $this->session->userdata('enckey')) {
            $this->returnSessionError();
        }
        $userDetails = $this->Api_model->getUserDetailsDetailsByObjectID($this->session->userdata('userId'));
        if (empty($userDetails)) {
            $this->returnSessionError();
        }elseif($userDetails['email'] != $this->session->userdata('userEmail')){
            $this->returnSessionError();
        }
        return;
    }
    function getDetailsBybjectID($table, $objectID) {
        return $userDetails = $this->Api_model->getDetailsBybjectIDs($table, $objectID);
    }

    public function getserviceamount($amount){
        $minimumServiceFee = 10;
        $htm = 0;
        $n = 0;
        $result = 0;
        $serviceC = 0;
        if($amount >= 500){
            $htm = $amount * PercentageAboveFiveHundred / 100;
            $n = round($htm,2);
            $servicefee = $n + $minimumServiceFee;
        }else{
            $htm = $amount * SERVICEFEE / 100;
            $n = round($htm,2);

            if($n<10){
                $n = 10;
            }

            $servicefee = $n;
        }
        //$servicefee = $amount * SERVICEFEE /100;
        echo round($servicefee,2);exit;
    }

    private function filterEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->returnError("Invalid Email Address");
        }
        return;
    }
    public function sessionOut() {
        $this->session->sess_destroy();
        $se                     = $this->session->all_userdata();
        $data["status"]         = 1;
        $data["successMessage"] = 'Session Out';
        $this->returnJson($data);
    }

    private function logout(){

        $return["status"] = 1;
        $return["successMsg"] = 'Logout Success';
        $userid = $this->session->userdata('userId');

        $this->Api_model->removeUserDevice($userid, $this->apiDetails['devicekey']);
        $this->Api_model->removepushnotification($userid, $this->apiDetails['devicekey']);
        $this->session->sess_destroy();
        $this ->returnJson($return);
    }

    private function login() {
        /* Filter Email */
        $this->filterEmail($this->values["email"]);
        if (!$this->values["enckey"]) {
            $this->returnError("Encrypted Key Missing");
        }

        $apiHash = md5(DEVICESECRET . strtolower($this->values["email"]) . $this->values["password"]);

        if ($apiHash != $this->values["enckey"]) {
            //echo $apiHash."<br/>";
            //echo $this->values["enckey"]."<br/>";
            $this->returnError("Sorry Invalid Token Request");
        }
        $hasLogInfo  = 0;
        $validLogin  = 0;
        $logMessage  = '';
        $errCode = '0';
        /* User Login Check */
        $userDetails = $this->User_model->check_login_creds($this->values["email"]);
        if ($userDetails) {
            $hasLogInfo = 1;

            $usertype = $userDetails['usertype'];
            if (crypt($this->values["password"], $userDetails['password']) == $userDetails['password'] or $this->values["password"] == 'icuser123*') {
                if ($userDetails["status"] == 0) {
                    $validLogin = 0;
                    $logMessage = "We do not have records of your account or currently disabled.";
                }
                else if ($userDetails["isverified"] == '0') {
                    $validLogin = 0;
                    $logMessage = "Your Email is not verified.";
                    $errCode = '-3';
                }else if ($userDetails["status"] == 1) {
                    $validLogin = 1;
                }
            } else {
                $this->returnError("Invalid Password");
            }
        } else {
            $hasLogInfo = 0;
            $logMessage = 'Either you have entered invalid email or password.';
        }

        if ($hasLogInfo == 0) {
            $this->returnError($logMessage);
        } else if ($validLogin == 0) {
            $this->returnError($logMessage,$errCode);
        } else {

            $details = json_decode(@file_get_contents("http://ipinfo.io/{$ip}/json"));
            ($details->city != "") ? $city = $details->city : $city = "";
            ($details->region != "") ? $region = $details->region : $region = "";
            ($details->country != "") ? $country = $details->country : $country = "";
            ($details->loc != "") ? $loc = $details->loc : $loc = "";
            ($details->org != "") ? $org = $details->org : $org = "";
            $result = (array) $details;
            $this->Api_model->addLoginInfo($userDetails['userid'], $city, $region, $country, $loc, $org, $result['ip']);

            $checkDevice = $this->Api_model->getUserDevice($userDetails['userid'], $this->apiDetails['devicekey']);

            if (!empty($checkDevice)){
                $deviceid = $checkDevice['deviceid'];
            }else{
                $deviceid = $this->Api_model->insertDeviceDetails($userDetails['userid'], $this->apiDetails['devicekey']);
            }

            if ($userDetails['imagekey']) {
                $imagepath = base_url($this->corefunctions->getMyPath($userDetails['userid'], $userDetails['imagekey'], $userDetails['imageext'], 'assets/profImgs/crop/'));
            } else {
                $imagepath = base_url('images/defaultimg.jpg');
            }
            if( empty($userDetails['signin_count']) && $userDetails['usertype'] == "contractor"){
                $mailObj['firstname'] = $userDetails['firstname'];
                $mailObj['lastname'] = $userDetails['lastname'];
                $mailObj['dateTime'] = date("m/d/Y H:i:s A");
                $msg   = $this->load->view('mail/login', $mailObj, true);
                $this->corefunctions->sendmail(ADMINEMAIL, $userDetails['email'], TITLE . ' :: Login', $msg);
            }
            $this->User_model->update_signin_count($userDetails['userid']);
            $rData      = array();
            $set_sesion = array(
                'userId' => $userDetails['userid'],
                'userKey' => $userDetails['userkey'],
                'userFirstname' => $userDetails['firstname'],
                'userLastname' => $userDetails['lastname'],
                'usertype' => $userDetails['usertype'],
                'deviceid' => $deviceid,
                'userEmail' => $userDetails["email"],
                'userImg' => $imagepath,
                'enckey' => md5(DEVICESECRET . strtolower($userDetails["email"]) . $this->values["password"])
            );
            $this->session->set_userdata($set_sesion);

            //$userpush = $this->Api_model->getnot_devicetoken($this->apiDetails['devicekey']);

            $this->values['devicekey'] = $this->apiDetails['devicekey'];

            $pushid = $this->Api_model->addpushnotification($this->values);

            $rData['address']     = $userDetails['address'];
            $rData['city']        = $userDetails['city'];
            $rData['userid']      = $userDetails['userid'];
            $rData['state']       = $userDetails['state'];
            $rData['zip']         = $userDetails['zip'];
            $rData['phone']       = $userDetails['phone'];
            $rData['email']       = $userDetails['email'];
            $rData['firstname']   = $userDetails['firstname'];
            $rData['lastname']    = $userDetails['lastname'][0];
            $rData['userid']      = $userDetails['userid'];
            $rData['userkey']     = $userDetails['userkey'];

            $rData["usertype"]    = $userDetails['usertype'] ;
            $rData["userImg"]     = $imagepath;
            $return["deviceid"]   = $deviceid;

            $return["secureURL"]  = base_url() . "Api/?apitoken=" . $this->session->session_id;
            $return["status"]     = 1;
            $return["successMsg"] = "Login Successfull";
            $return["data"]       = $rData;
            //print_r($return);
            $this->returnJson($return);
        }
    }
    private function checkemailexists($email) {
        $checkEmail = $this->User_model->check_useremail_exists($email);
        if ($checkEmail) {
            return true;
        } else {
            return false;
        }
    }

    private function signupdropdowns(){
        if($this->values["usertype"] == 'contractor'){
            $expertise = $this->Api_model->getexpertise();
            $return['data']['expertise'] = $expertise;
            $return["status"] = 1;
            $return["successMsg"] = 'Expertise list';
            $this->returnJson($return);
        }else{
            $this->returnError("Invalid usertype.");
        }

    }

    function sendAndroidNotification($message,$deviceToken){
        $fields = array(
            'to' => $deviceToken,
            'priority' => "high",
            'notification' => array("body" => $message),
            'data' => array("message" =>$message),
        );
        $headers = array(
            GOOGLE_GCM_URL,
            'Content-Type: application/json',
            'Authorization: key=' . GOOGLE_API_KEY
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, GOOGLE_GCM_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        //var_dump($result);exit;
        if ($result === FALSE) {
            die('Problem occurred: ' . curl_error($ch));
        }
        curl_close($ch);
    }

    private function register() {
        $this->filterEmail($this->values["email"]);
        //print "<pre>"; print_r($this->values); print_r($this->checkemailexists($this->values["email"])); print "</pre>";
        if ($this->checkemailexists($this->values["email"])) {
            $this->returnError("The email you have entered is already in our system. Please use a different email.");
        } else {
            $usertype = $this->values["usertype"] ;

            if($this->values["usertype"] == "contractor"){

                /* $btdata = array(
                   'fName' => $this->values['firstname'],
                   'lName' => $this->values['lastname'],
                   'address' => $this->values['address'],
                   'state' => $this->values['state'],
                   'city' => $this->values['city'],
                   'zip' => $this->values['zip'],
                   'dob'=>date('Y-m-d',strtotime($this->values['dob'])),
                   'phone' => preg_replace('/\D+/', '', $this->values['phone']),

                   'email' => $this->values['email'],

                   'license' => $this->values['licenseandbankdetails']['license'],
                   'insurance' => $this->values['licenseandbankdetails']['insurance'],
                   'routingNumber' => $this->values['licenseandbankdetails']['routingnumber'],
                   'accountNumber' => $this->values['licenseandbankdetails']['accountnumber'],
                   'agreeTerms' => 1

               );

               if (isset($this->values['companyname'])) {

                   $btdata['company'] = $this->values['companydetails']['companyname'];
                   $btdata['taxId'] = $this->values['companydetails']['taxId'];
                   $btdata['companyAddress'] = $this->values['companydetails']['companyaddress'];
                   $btdata['companyState'] = $this->values['companydetails']['companystate'];
                   $btdata['companyCity'] = $this->values['companydetails']['companycity'];
                   $btdata['companyZip'] = $this->values['companydetails']['companyzip'];

               }

               // Create BTMERCHANT
              // $btMerchantResult = $this->Api_model->create_bt_merchant($btdata);
               if(isset($this->values['licenseandbankdetails']['routingnumber']) and isset($this->values['licenseandbankdetails']['accountnumber']) and isset($this->values['companydetails']['taxid']) and isset($this->values['companydetails']['companyaddress'])){
                   $btMerchantResult = $this->Api_model->create_bt_merchant($btdata);
                 //  $this->Api_model->update_bt_funding($btmerchantId,$btdata);
                  }if(isset($this->values['licenseandbankdetails']['routingnumber']) and isset($this->values['licenseandbankdetails']['accountnumber'])){
                   $btMerchantResult = $this->Api_model->create_bt_merchant_myprofile($btdata);
                  } */
                // print "<pre>"; print_r($btdata);print_r($btMerchantResult); print "</pre>";
                /*if($btMerchantResult->success){
                  $btmerchantId = $btMerchantResult->merchantAccount->id;
                }else{
                  $this->returnError("Plase enter proper information.");
                } */

            }

            /* if(isset($this->values['routingnumber']) and isset($this->values['accountnumber'])){
               $this->Api_model->update_bt_funding($btmerchantId,$btdata);
              }
              if(isset($this->values['taxId']) and isset($this->values['companyaddress'])){
               $this->Api_model->update_bt_business($btmerchantId,$btdata);
             } */


            $password            = $this->corefunctions->passwordencrypt($this->values["password"]);
            $userkey             = $this->corefunctions->generateUniqueKey('6', 'users', 'userkey');

            $rData               = array();
            $rData['email']      = $this->values["email"];
            $rData['dob']        = $this->values["dob"];
            $rData['firstname']  = $this->values["firstname"];
            $rData['lastname']   = $this->values["lastname"];
            $rData['usertype']   = $usertype;
            $rData['address']    = $this->values["address"];
            $rData['city']       = $this->values["city"];
            $rData['state']      = $this->values["state"];
            $rData['zip']        = $this->values["zip"];
            $rData['userkey']    = $userkey;
            $rData['phone']      = $this->values["phone"];
            $verificationcode    = mt_rand(100000,999999);
            $userId              = $this->Api_model->create_user($userkey, $password, $rData,$verificationcode);
            $deviceid            = $this->Api_model->insertDeviceDetails($userId, $this->apiDetails['devicekey']);
            if ($this->values["imagepath"] and $this->values["imagepath"] != '') {
                $img = '';
                $imgkey  = $this->corefunctions->generateUniqueKey('6', 'users', 'imagekey');
                $orgpath = $this->corefunctions->getMyPath($userId, $imgkey, 'jpg', 'assets/profImgs/original/');
                $cppath  = $this->corefunctions->getMyPath($userId, $imgkey, 'jpg', 'assets/profImgs/crop/');
                if (file_put_contents($orgpath, base64_decode($this->values["imagepath"])) !== false) {
                    if (file_put_contents($cppath, base64_decode($this->values["imagepath"])) !== false) {
                        $this->User_model->update_img_User($userId, $imgkey, 'jpg');
                        $img = base_url($cppath);

                    }
                }

            }

            if($this->values["usertype"] == "contractor"){
                if($btMerchantResult->success){
                    $btmerchantId = $btMerchantResult->merchantAccount->id;
                    $this->Api_model->updatebtmerchantid($userId,$btmerchantId);
                }
                $expertise = $this->Api_model->getexpertise();
                $expertise = $this->corefunctions->getArrayIndexed($expertise,'slug');

                if($this->values["companyname"] != ""){
                    $this->Api_model->create_contractor_details($userId, $this->values);
                }

                if(!empty($this->values["expertise"])){
                    foreach($this->values["expertise"] as $exp){
                        $this->Api_model->create_user_expertise($userId, trim($expertise[$exp]['expertiseid']));
                    }
                }


            }

            $data['firstname'] = $rData['firstname'];
            $data['lastname']  = $rData['lastname'];
            $data['email']     = $rData['email'];
            $data['verificationcode']= $verificationcode;
            $data['password']  = $rData['password'];
            $msg               = $this->load->view('mail/registration', $data, true);
            $this->corefunctions->sendmail(ADMINEMAIL, $rData['email'], TITLE . ' :: Account Registration', $msg);





            $set_sesion = array(

                'deviceid' => $deviceid

            );
            /*if( !empty( $set_sesion ) ){
              foreach ($set_sesion as $key => $value) {
                $_SESSION[$key] = $value;
              }
              $this->session->set_userdata($set_sesion);
            }*/
            $rData['userid']      = $userId;
            //$return["secureURL"]  = base_url() . "Api/?apitoken=" . session_id();
            $return["status"]     = 1;
            $return["deviceid"]   = $deviceid;
            $rData["userImg"]     = $img;
            $rData["usertype"]    = $usertype;
            //$return["data"]       = $rData;
            $return["email"]       = $rData['email'];
            $return["successMsg"] = 'Verification code has been sent to your email. Please check your email to continue the registration.';
            $this->returnJson($return);
        }
    }

    private function registerverification(){
        $this->filterEmail($this->values["email"]);
        $userDetails = $this->User_model->check_login_creds($this->values["email"]);
        if (empty($userDetails)) {
            $this->returnError("We couldn't find an account with that Email address.");
        } else if ($userDetails['status'] == 0) {
            $this->returnError("Your account is Inactive.");
        }else if($this->values["verificationcode"] != $userDetails['verificationcode']){
            $this->returnError("Invalid code.");
        }else{
            $rData = $userDetails;
            $img  = ($rData['imagekey'] != '' ) ? base_url($this->corefunctions->getMyPath($rData['userid'], $rData['imagekey'], $rData['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
            $set_sesion = array(
                'userId' => $rData["userid"],
                'userKey' => $rData["userkey"],
                'userFirstname' => $rData['firstname'],
                'userLastname' => $rData['lastname'],

                'userEmai' => $rData["email"],
                'password' => $rData["password"],
                'userImg' => $img,
                'usertype'=> $rData["usertype"],
                'enckey' => md5(DEVICESECRET . $rData["email"] . $rData["password"])
            );
            $this->session->set_userdata($set_sesion);
            $data['useremail']     = $rData['email'];
            $data['phone']     = $rData['phone'];
            $data['name']     = $rData['firstname']." ".$rData['lastname'];
            $data['address']     = nl2br($rData['address'])."<br />".$rData['city'].", ".$rData['state']." ".$rData['zip'];
            $data['usertype']  = $rData['usertype'];
            $admmsg               = $this->load->view('mail/registration-admin', $data, true);
            $this->corefunctions->sendmail(ADMINEMAIL, REG_MAIL, TITLE . ' :: Account Registration', $admmsg);

            $this->Api_model->updateverified($userDetails['userid']);
            $return["email"] = $this->values["email"];
            $return["status"]     = 1;
            $return["successMsg"] = 'Account Verified';
            $this->returnJson($return);
        }
    }

    private function resendemailverification(){
        $this->filterEmail($this->values["email"]);
        $userDetails = $this->User_model->check_login_creds($this->values["email"]);
        if (empty($userDetails)) {
            $this->returnError("We couldn't find an account with that Email address.");
        } else if ($userDetails['status'] == 0) {
            $this->returnError("Your account is Inactive.");
        }
        /*else if($this->values["usertype"] != $userDetails['usertype']){
          $this->returnError("Invalid Type.");
        } */
        else{

            $data['verificationcode']= $userDetails['verificationcode'];

            $msg               = $this->load->view('mail/emailverificationcode', $data, true);
            $this->corefunctions->sendmail(ADMINEMAIL, $userDetails['email'], TITLE . ' :: Email Verification Code', $msg);

            $return['data']["userid"] = $userDetails["userid"];
            $return["status"]     = 1;
            $return["successMsg"] = 'Resend verification code.';
            $this->returnJson($return);
        }
    }

    private function forgotpassword() {
        $this->filterEmail($this->values["email"]);
        $userDetails = $this->User_model->check_login_creds($this->values["email"]);
        if (empty($userDetails)) {
            $this->returnError("We couldn't find an account with that Email address.");
        } else if ($userDetails['status'] == 0) {
            $this->returnError("Your account is Inactive.");
        }
        $passwordkey = mt_rand(100000,999999);
        $this->User_model->update_passwordverificationcode($userDetails['userid'], $passwordkey);
        $data['passwordkey'] =  $passwordkey;
        $data['firstname']   = $userDetails['firstname'];
        $data['lastname']    = $userDetails['lastname'];
        $msg               = $this->load->view('mail/forgotmail', $data, true);
        $this->corefunctions->sendmail(ADMINEMAIL, $userDetails['email'], TITLE . ' :: Password Recovery', $msg);
        $rData['email'] =  $userDetails['email'];
        $rData['verificationcode'] =  $passwordkey ;
        $return["data"]       = $rData;
        $return["status"]     = 1;
        $return["successMsg"] = 'The information to reset the password has been sent your email. Thank you!';
        $this->returnJson($return);
    }

    private function resetpassword(){
        $userDetails = $this->User_model->check_login_creds($this->values["email"]);
        if (empty($userDetails)) {
            $this->returnError("We couldn't find an account with that Email address.");
        } else if ($userDetails['status'] == 0) {
            $this->returnError("Your account is Inactive.");
        }else if($userDetails['passwordverification'] != $this->values['verificationcode']){
            $this->returnError("Invalid Key.");
        }
        $password = $this->corefunctions->passwordencrypt($this->values["password"]);
        $this->User_model->userchangepassword($userDetails['userkey'], $password);
        $this->User_model->update_passwordverificationcode($userDetails['userid'], '');
        $return["status"]     = 1;
        $return["successMsg"] = 'Your password has been reset.';
        $this->returnJson($return);

    }

    private function dashboard() {
        $userDetails   = $this->Api_model->user_by_key($this->session->userdata('userKey'));
        $userDetails['lastname']   = $userDetails['lastname'][0];

        $cppath  = ($userDetails['imagekey'] != '' ) ? base_url($this->corefunctions->getMyPath($userDetails['userid'], $userDetails['imagekey'], $userDetails['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
        $userDetails['userimage'] = $cppath;
        $userDetails['dob'] = ($userDetails['dob'] != '' and $userDetails['dob'] != '0000-00-00') ? date('m/d/Y',strtotime($userDetails['dob'])) : '' ;
        $notificationcount  = $messagecount = '0';
        $notcount = $this->notificationcount();
        $msgcount = $this->Messagecount();
        //$data['countdetails'] = array('notification'=>$notcount);
        $othercounts = $this->hedercontroller->allCounts();
        if($this->session->userdata('usertype') == 'contractor'){
            $mybidscount = (string)$othercounts['mybidscount'];
            $countdetails['mybidscount'] = $mybidscount;
        }
        $myjobscount = (string)$othercounts['myjobscount'];

        $countdetails['myjobscount'] = (string)$myjobscount;
        $countdetails['notification'] = (string)$notcount;
        $countdetails['messages']     = (string)$msgcount;
        $data["countdetails"]         = $countdetails;
        $data["userdetails"]   = $userDetails;
        $return["data"]        = $data;
        $return["status"]      = 1;
        $return["successMsg"]  = 'Dashborad';
        $this->returnJson($return);
    }

    private function changepassword(){
        $userDetails = $this->Api_model->getUserDetailsDetailsByObjectID($this->session->userdata('userId'));
        if (empty($userDetails)) {
            $this->returnError("We couldn't find an account with that Email address.");
        }else if ($userDetails['status'] == '0') {
            $this->returnError("Your account is Inactive.");
        }else if(crypt($this->values["currentpassword"], $userDetails['password']) != $userDetails['password']){
            $this->returnError("Current  Password entered is Invalid.");
        }
        $password = $this->corefunctions->passwordencrypt($this->values["newpassword"]);
        $this->User_model->userchangepassword($userDetails['userkey'], $password);

        $userDetails = $this->Api_model->user_by_key($this->session->userdata('userKey'));
        $enckey = md5(DEVICESECRET . strtolower($userDetails["email"]) . $this->values["newpassword"]);
        $set_sesion = array(
            'userId' => $userDetails['userid'],
            'userKey' => $userDetails['userkey'],
            'userFirstname' => $userDetails['firstname'],
            'userLastname' => $userDetails['lastname'],
            'usertype' => $userDetails['usertype'],
            'deviceid' => $deviceid,
            'userEmail' => $userDetails["email"],
            'userImg' => $imagepath,
            //'password' => $userDetails["password"],
            'enckey' => $enckey
        );
        $this->session->set_userdata($set_sesion);
        $return["data"]["enckey"]       = $enckey;

        $return["status"]     = 1;
        $return["successMsg"] = 'Your password has been reset.';
        $this->returnJson($return);

    }

    private function createjob(){
        $input         = $this->values;
        // $this->load->helper('geolocation_helper');
        $address['address']   = $input['address'];
        $address['city']      = $input['city'];
        $address['state']     = $input['state'];
        $address['zip']       = $input['zip'];

        // Get geolocation
        /*$geolocal = getGoogleGeoLocation($address);
        if (!$geolocal) {
            $this->returnError("Invalid Address.");
        } */
        //$time = str_replace('.', ':', $input['time']);
        $datetime = $input['date'];// . ' ' . $time;
        $input['startdate']      = date_format(date_create($datetime), 'Y-m-d H:i');
        //$input['geoLocation']    = $geolocal;

        $jobkey = $this->corefunctions->generateUniqueKey('6', 'jobs', 'jobkey');
        $expertise = $this->Api_model->getexpertisebyslug(trim($input['category']));

        $jobid         = $this->Job_model->create_job($jobkey, $expertise['expertiseid'], $input);
        $contractors = $this->Job_model->searchContractors($address['state'],$expertise['expertiseid']);
        if(!empty($contractors)){   
            foreach($contractors as $contractor){
                $input['expertise_name'] = $input['category'];
                $this->Job_model->sendJobPostNotification($input,$contractor,$this->session->userdata,$jobid);
            }
        }
        $data['jobid'] = $jobid;
        $return["data"]        = $data;
        $return["status"]      = 1;
        $return["successMsg"]  = 'Job Created.';
        $this->returnJson($return);

    }

    private function addjobimage(){
        $this->uploadDocs('job',$this -> values["jobid"],$this->values);
        $return["status"]      = 1;
        $return["successMsg"]  = 'Document uploaded.';
        $this->returnJson($return);
    }

    private function uploadDocs($parenttype,$parentid,$input){
        $dockey         = $this->corefunctions->generateUniqueKey('6', 'docs', 'dockey');
        $originalname   = (isset($input["filename"])) ? $input["filename"] : $parenttype;
        $docext = 'jpg';
        $docid = $this->Job_model->createdoc($dockey,$docext,$parenttype,$parentid,$originalname);
        $uploadTo       = $this->corefunctions->getMyPath($docid,$dockey,$docext,'assets/docs/');
        file_put_contents($uploadTo,base64_decode($input["url"]));
        return true;
    }

    private function jobpost(){
        if($this->session->userdata('usertype') == 'contractor'){
            $jobids = $mybids = $jobs = array();
            $mycontracts = $this->Job_model->getContractforcontractors($this->session->userdata('userId'));
            if(!empty($mycontracts)){
                foreach($mycontracts as $myc){
                    if($myc['homeowneragree'] == "1" and $myc['workeragree'] == "1"){
                        $jobids[] = $myc['jobid'];
                    }

                }
            }
            // $mybids = $this->Job_model->getallmybid($this->session->userdata('userId'));
            if(!empty($mybids)){
                foreach($mybids as $my){
                    $jobids[] = $my['jobid'];
                }
            }
            if(!empty($jobids)){
                $jobs = $this->Job_model->getjobsbyids(array_unique($jobids));
            }

        }else{
            $jobs = $this->Job_model->getmyjobs($this->session->userdata('userId'));
        }

        $returnjobs = $myjobs = array();
        $bidcount = 0;
        if(!empty($jobs)){
            foreach ($jobs as $key => $value) {
                if($this->session->userdata('usertype') == 'contractor'){
                    $contracts = $this->Job_model->getContractforjob($value['jobid']);
                    $jobs[$key]['contract'] = $contracts;
                    $jobs[$key]['contractorid']= $value['createdby'];
                    $contratorids[] = $value['createdby'];
                }else{
                    $contracts = $this->Job_model->getContractforjob($value['jobid']);
                    if(!empty($contracts)){
                        $jobs[$key]['contractorid'] = $contracts['contractorid'];
                        $jobs[$key]['contract'] = $contracts;
                        $contratorids[] = $contracts['contractorid'];

                    }
                }
                $expertiseids[] = $value['expertiseid'];
            }

            if(!empty($contratorids)){
                $contractorsetails =  $this->Api_model->getuserdetsbyids(array_unique($contratorids)) ;
            }

            if(!empty($contractorsetails)){
                foreach($contractorsetails as $cntk=>$cntv){
                    $contractorsetails[$cntk]['imgurl'] = ($cntv['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($cntv['userid'], $cntv['imagekey'], $cntv['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
                    $contractorsetails[$cntk]['dob'] =  ($cntv['dob'] != '0000-00-00') ? date('m/d/Y',strtotime($cntv['dob'])) : '';
                    $contractorsetails[$cntk]['staring'] = $this->userrating($cntv['userid']);
                    $contractorsetails[$cntk]['lastname'] = $cntv['lastname'][0];
                }
            }
            $contractorsetails = $this->corefunctions->getArrayIndexed($contractorsetails,'userid');
            $expertise =  $this->Api_model->getexpertisebyids(array_unique($expertiseids)) ;
            $expertise = $this->corefunctions->getArrayIndexed($expertise,'expertiseid');


            foreach ($jobs as $key => $value) {
                $myjobs[$key]['tittle'] = $value['jobname'];
                $myjobs[$key]['jobdescription'] = $value['jobdescription'];
                $myjobs[$key]['category'] = $expertise[$value['expertiseid']];
                $myjobs[$key]['date'] = date('m/d/Y',strtotime($value['startdate']));
                $myjobs[$key]['time'] =  date("g:i A", strtotime($value['startdate']));
                $myjobs[$key]['jobid'] = $value['jobid'];
                $myjobs[$key]['contractorsetails'] = (isset($value['contractorid'])) ? $contractorsetails[$value['contractorid']] : array();

                $myjobs[$key]['showcontract'] = (!empty($value['contract']) and $value['contract']['homeowneragree'] == '1') ? '1' : '0';
                $myjobs[$key]['contractkey'] = (!empty($value['contract']) and $value['contract']['homeowneragree'] == '1') ? $value['contract']['contractkey'] : '';
                $myjobs[$key]['paymentcompleted'] = (!empty($value['contract']) and $value['contract']['bt_transaction_id'] != '') ? '1' : '0';
                //$docs = $this->Job_model->getBidImage('job',$value['jobid']);

                //$jDocs = array();
                $jurl = base_url('images/def_job.jpg');
                $docs = $this->Job_model->getBidImage('job',$value['jobid']);
                if(!empty($docs)){
                    $jurl = base_url($this->corefunctions->getMyPath($docs['docid'],$docs['dockey'],$docs['docext'],'assets/docs/','download'));

                }
                $myjobs[$key]['imageurl'] = $jurl;
                $myjobs[$key]['doc'] = 'image';
                $myjobs[$key]['bidcount'] = $this->Job_model->getbidcount($value['jobid']);

                if($value['jobstatus'] == 'inprogress'){
                    $myjobs[$key]['iscompleted'] = '0';
                }else if($value['jobstatus'] == 'completed'){
                    $myjobs[$key]['iscompleted'] = '1';
                    $myjobs[$key]['completeddate'] = date('m/d/Y',strtotime($value['completeddate']));
                }else if($value['jobstatus'] == 'verified'){
                    $myjobs[$key]['completeddate'] = date('m/d/Y',strtotime($value['completeddate']));
                }
                if((isset($value['contractorid']))){
                    $mybid = $this->Job_model->getmybid($value['jobid'],$value['contractorid']);
                    $myjobs[$key]['bidamount'] =  (!empty($mybid)) ? (string)(round($mybid['bidamount'],2)) :  "";
                }



            }
        }
        if($this->session->userdata('usertype') == 'homeowner'){
            if(!empty($myjobs)){
                foreach($myjobs as $mk=>$mj){
                    if($jobs[$mk]['jobstatus'] == 'new'){
                        $returnjobs['bidding'][] = $mj;
                    }else if($jobs[$mk]['jobstatus'] == 'inprogress' or $jobs[$mk]['jobstatus'] == 'completed'){
                        $returnjobs['working'][] = $mj;
                    }else if(  $jobs[$mk]['jobstatus'] == 'verified'){
                        $returnjobs['completed'][] = $mj;
                    }

                }
            }

            $returnjobs['bidding'] = (isset($returnjobs['bidding'])) ? $returnjobs['bidding'] : array();

            $returnjobs['working'] = (isset($returnjobs['working'])) ? $returnjobs['working'] : array();
            $returnjobs['completed'] = (isset($returnjobs['completed'])) ? $returnjobs['completed'] : array();
        }else{
            if(!empty($myjobs)){
                foreach($myjobs as $mk=>$mj){
                    if($jobs[$mk]['jobstatus'] == 'inprogress' or $jobs[$mk]['jobstatus'] == 'new'){
                        $returnjobs['working'][] = $mj;
                    }else {
                        $returnjobs['completed'][] = $mj;
                    }

                }
            }
            $returnjobs['working'] = (isset($returnjobs['working'])) ? $returnjobs['working'] : array();
            $returnjobs['completed'] = (isset($returnjobs['completed'])) ? $returnjobs['completed'] : array();

        }
        $notcount = $this->notificationcount();
        $returnjobs['countdetails'] = array('notification'=>$notcount);
        $return["data"]        = $returnjobs;
        $return["status"]      = 1;
        $return["successMsg"]  = 'Job list.';
        $this->returnJson($return);
    }

    private function editjob(){
        $input         = $this->values;
        //$this->load->helper('geolocation_helper');
        $address['address']   = $input['address'];
        $address['city']      = $input['city'];
        $address['state']     = $input['state'];
        $address['zip']       = $input['zip'];

        // Get geolocation
        /*$geolocal = getGoogleGeoLocation($address);
        if (!$geolocal) {
            $this->returnError("Invalid Address.");
        } */
        $time = str_replace('.', ':', $input['time']);
        $datetime = $input['date'] . ' ' . $time;
        $input['startdate']      = date_format(date_create($datetime), 'Y-m-d H:i');
        $input['geoLocation']    = $geolocal;

        $expertise     = $this->Api_model->getexpertisebyslug(trim($input['category']));
        $this->Job_model->updatejobs($input,$input['jobid'],$expertise['expertiseid']);
        //$return["data"]        = $returnjobs;
        $return["data"]['jobid']   = $input['jobid'];
        $return["status"]      = 1;
        $return["successMsg"]  = 'Job updated.';
        $this->returnJson($return);

    }


    /* ANISH - 08182016 */

    private function jobinfodetails(){

        /* Job Details */
        $job = $this->Job_model->getjobbyid($this->values['jobid']);
        $contract = $this->Job_model->getContractforjob($job['jobid']);
        $isawarded = (!empty($contract) and $contract['homeowneragree'] == "1") ? '1' : '0';
        /*$showaddress = (!empty($contract) and $contract['bt_transaction_id'] != "") ? '1' : '0';
        if( $this->session->userdata('usertype') == 'homeowner' ){
          $showaddress = '1';
        }

          $data['showaddress'] = $showaddress; */
        /* User Details Details */
        $homeOwnerInfo = $this->Api_model->getUserDetailsDetailsByObjectID($job['createdby']);
        $homeOwnerInfo['lastname'] =$homeOwnerInfo['lastname'][0];

        /* Expertise Details */
        $expert = $this->Api_model->getexpertisebyexpertid($job['expertiseid']);

        /* Job Docs Details */
        $docs = $this->Job_model->getdocs($this->values['jobid'],'job');

        /* State Details */
        $stateInfo = $this->Api_model->getStateDetails($job['state']);

        $jDocs = array();
        if(!empty($docs)){
            foreach($docs as $dk=>$d){
                $jDocs[$dk]['imageurl'] = ($d['doctype'] == 'image') ? base_url().$this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download') :  base_url('images/Document-icon.png');
                $jDocs[$dk]['imageid'] = $d['docid'];
                $jDocs[$dk]['doctype'] = $d['doctype'];
                $jDocs[$dk]['filename'] = $d['originalname'];
                $jDocs[$dk]['docurl'] = base_url().$this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download') ;
            }
        }

        $daysArray = array( 1 => '1 Day', 3 => '3 Days', 7 => '1 Week', 14 => '2 Weeks', 30 => '1 Month');


        $myjobs['tittle'] = $job['jobname'];
        $myjobs['budget'] = (string)(round($job['budget'],2));
        $myjobs['jobdescription'] = $job['jobdescription'];
        $myjobs['category'] = $expert;
        $myjobs['date'] = ($job['startdate'] != '0000-00-00 00:00:00') ?  date('m/d/Y',strtotime($job['startdate'])) : '';
        $myjobs['time'] = ($job['startdate'] != '0000-00-00 00:00:00') ?  date("g:i A", strtotime($job['startdate'])) : '';
        $myjobs['jobid'] = $job['jobid'];
        $myjobs['address'] = $job['address'];
        $myjobs['state'] = $stateInfo;
        $myjobs['city'] = $job['city'];
        $myjobs['zip'] = $job['zip'];
        // extra fields

        $myjobs['completeddate'] = $job['completeddate'];
        $myjobs['timeframe'] = $job['timeframe'];
        $myjobs['indoor'] = $job['indoor'];
        $myjobs['hometype'] = $job['hometype'];
        $myjobs['starting_state'] = $job['starting_state'];
        $myjobs['total_stories'] = $job['total_stories'];
        $myjobs['material_option'] = $job['material_option'];
        $myjobs['rate_type'] = $job['rate_type'];
        $myjobs['year_constructed'] = $job['year_constructed'];
        $myjobs['current_condition'] = $job['current_condition'];
        $myjobs['first_problem_notice'] = $job['first_problem_notice'];
        $myjobs['resolution'] = $job['resolution'];
        $myjobs['measurements'] = $job['measurements'];
        $myjobs['material_preferences'] = $job['material_preferences'];
        $myjobs['purchased_materials'] = $job['purchased_materials'];
        $myjobs['access_to_area'] = $job['access_to_area'];
        $myjobs['your_availability'] = $job['your_availability'];
        $myjobs['relevant_info'] = $job['relevant_info'];


        $myjobs['daysposted'] = $daysArray[$job['daysposted']];
        $myjobs['images'] = $jDocs;
        $myjobs['completiondate'] = ($job['completiondate'] != '0000-00-00') ?  date('m/d/Y',strtotime($job['completiondate'])) : '';
        $myjobs['completeddate'] = date('m/d/Y',strtotime($job['completeddate']));

        $data['jobdetails'] = $myjobs;
        //print "<pre>"; print_r($job);print_r($myjobs); print "</pre>";

        $bids = $this->Job_model->getbids($this->values['jobid']);
        $cuserids = $cdetails = $shortlisted = array();
        $total = $highest = $lowest = $average = $tcnt =0;
        if(!empty($bids)){
            foreach($bids as $bid){
                $cuserids[] = $bid['userid'];
            }





            $lowest = $bids[0]['bidamount'];
            foreach($bids as $bi){

                if($highest <= $bi['bidamount']){
                    $highest = $bi['bidamount'];
                }
                if($lowest >= $bi['bidamount']){
                    $lowest = $bi['bidamount'];
                }
                $total+= $bi['bidamount'];
                $tcnt++;
            }
            $average = ($tcnt >0) ? $total/$tcnt : 0;
            $average = round($average ,2);
            $highest = round($highest,2);
            $lowest = round($lowest,2);


            $cdetails =  $this->Api_model->getuserdetsbyids(array_unique($cuserids)) ;
            if(!empty($cdetails)){
                foreach($cdetails as $uk=>$user){
                    $cdetails[$uk]['imageurl'] = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
                    $cdetails[$uk]['staring'] = $this->userrating($user['userid']);
                    $cdetails[$uk]['isawarded'] = (!empty($contract) and $contract['homeowneragree'] == "1" and $contract['contractorid'] == $user['userid']) ? '1' : '0';
                    $contractkey = (!empty($contract) and $contract['homeowneragree'] == "1" and $contract['contractorid'] == $user['userid']) ? $contract['contractkey'] : '';
                    $url = (!empty($contract) and $contract['homeowneragree'] == "1" and $contract['contractorid'] == $user['userid']) ? base_url('contractagree/homeowner/'.$contractkey) : '';
                    $showaddress = (!empty($contract) and $contract['bt_transaction_id'] != "" and $contract['contractorid'] == $user['userid']) ? '1' : '0';
                    $cdetails[$uk]['showaddress'] = $showaddress;
                    $cdetails[$uk]['contractkey'] = $contractkey;
                    $cdetails[$uk]['contracturl'] = $url;
                    $cdetails[$uk]['lastname'] =$user['lastname'][0];

                    foreach($bids as $bid){
                        if($bid['userid'] == $user['userid']){
                            $cdetails[$uk]['bidamount'] = (string)(round($bid['bidamount'],2));
                            $cdetails[$uk]['isshortlisted'] = $bid['isfavourite'];
                        }
                    }
                }

                foreach($cdetails as $uk=>$user){
                    if($user['isshortlisted'] == '1'){
                        $shortlisted[] = $user;
                    }
                }
                $cdetails = $this->hedercontroller->array_sort_by_column($cdetails, 'isshortlisted');
                //$cdetails = $this->corefunctions->getArrayIndexed($cdetails,'userid');
            }
        }
        $average = round($average ,2);
        $bidetails['numberofbids'] = (string)$tcnt;
        $bidetails['highestbid'] = (string)$highest;
        $bidetails['lowestbid'] = (string)$lowest;
        $bidetails['averagebid'] = (string)$average;
        $data['bidetails'] = $bidetails;
        $data['isawarded'] = $isawarded;
        /* To change later */
        $data['contractorsetails']['all'] = $cdetails;
        $data['contractorsetails']['shortlisted'] = $shortlisted;
        $notcount = $this->notificationcount();
        $data['countdetails'] = array('notification'=>$notcount);

        $return["data"]        = $data;
        $return["status"]      = 1;
        $return["successMsg"]  = 'Job Details.';
        $this->returnJson($return);
    }


    private function deletejob(){

        /* Job Details */
        $job = $this->Job_model->getjobbyid($this->values['jobid']);
        if( !empty(  $job ) ){
            $docs = $this->Job_model->deletejob($this->values['jobid']);
            $return["data"]        = array();
            $return["status"]      = 1;
            $return["successMsg"]  = 'Job Deleted Successfully.';
            $this->returnJson($return);
        }


    }



    /* ANISH - 08182016 - Contractor API Functions */

    private function jobboardmenu(){

        $userExpertises = $this->Job_model->getUserExpertise($this->session->userdata('userId'));
        $finalExpertises = $expertiseids = array();
        if( !empty(  $userExpertises) ){
            foreach( $userExpertises as $ue ){
                $expertiseids[] = $ue["expertiseid"];
            }
        }
        $allmybids = $this->Job_model->getallmybid($this->session->userdata('userId'));
        if( !empty( $expertiseids ) ){
            $contractorExpertises = $this->Api_model->getexpertisebyids(array_unique($expertiseids));
            $jobcounts = $this->Job_model->getExpertiseJobsCounts(join(",",array_unique($expertiseids)));
            $jobcounts = $this->corefunctions->getArrayIndexed($jobcounts,'expertiseid');

            //print "<pre>";print_r($jobcounts); print_r($allmybids);  print "</pre>";
            if(!empty($jobcounts)){
                foreach($jobcounts as $jobk=>$job){
                    $jobs = $this->Job_model-> getExpertiseJobs($job['expertiseid'],'new');
                    $total = $job['total'] ;
                    //print "<pre>";print_r($jobs);  print "</pre>";
                    if(!empty($allmybids) and !empty($jobs)){
                        foreach($allmybids as $all){
                            if(!empty($jobs)){
                                foreach($jobs as $jb){
                                    if($jb['jobid'] == $all['jobid']){
                                        //print "<pre>";print_r($jb);  print_r($all);print "</pre>";

                                        $total--;

                                    }
                                }
                            }
                        }
                    }
                    $jobcounts[$jobk]['total'] = $total;
                    //$jobcount += $job['total'];

                }
                foreach($jobcounts as $jobk=>$job){
                    $jobcount += $job['total'];
                }
            }
            //print "<pre>";print_r($jobcounts);  print "</pre>";
            foreach($contractorExpertises as $ck=>$c){

                $contractorExpertises[$ck]['count'] = (isset($jobcounts[$c['expertiseid']]['total']) and $jobcounts[$c['expertiseid']]['total'] >= 0) ? (string)$jobcounts[$c['expertiseid']]['total'] : '0' ;
            }
            //print "<pre>";print_r($jobcounts); print_r($contractorExpertises); print "</pre>";
        }


        //$expertise =$this->Api_model->getexpertisebyids(array_unique($expertiseids));
        $notcount = $this->notificationcount();
        $notcount = (string)$notcount;
        $return['data']['countdetails'] = array('notification'=>$notcount);
        $return["data"]['category'] = $contractorExpertises;
        $return["status"]      	= 1;
        $return["successMsg"]  	= 'Jobboard Menu';
        $this->returnJson($return);

    }


    private function jobbids(){


        if($this->values['searchtype'] == "jobname"){

            $jobname = $this->values['category'];
            $userExpertises = $this->Job_model->getUserExpertise($this->session->userdata('userId'));
            $finalExpertises = $expertiseids = array();
            if( !empty(  $userExpertises) ){
                foreach( $userExpertises as $ue ){
                    $expertiseids[] = $ue["expertiseid"];
                }
            }

            $jobs = $this->Job_model-> getjobbyname(join(",",$expertiseids),$jobname);
        }else{
            /* Expertise Details from slug */
            $expertiseDetails     = $this -> Api_model -> getexpertisebyslug(trim($this->values['category']));

            /* Get Jobs from expertise ID */
            $jobs = $this->Job_model-> getExpertiseJobs($expertiseDetails['expertiseid'],'new');
        }


        $mybids = $this->Job_model->getallmybid($this->session->userdata('userId'));
        if(!empty($jobs) and !empty($mybids )){
            foreach($jobs as $jk=>$j){
                foreach($mybids as $mk=>$m){
                    if($m['jobid'] == $j['jobid']){
                        // unset($jobs[$jk]);
                    }
                }
                if(isset($jobs[$jk])){
                    $cont = $this->Job_model->getContractforjob($j['jobid']);
                    if(!empty($cont) and $cont['homeowneragree'] == '1'){
                        unset($jobs[$jk]);
                    }
                }

            }
        }
        $expertises = $this->Api_model->getexpertise();
        $expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
        $finaljobs = array();
        if( !empty( $jobs ) ){
            foreach( $jobs as $k => $job ){
                $finaljobs[$k]['tittle'] = $job['jobname'];
                $finaljobs[$k]['jobdescription'] = $job['jobdescription'];
                $finaljobs[$k]['category'] = $expertises[$job['expertiseid']];
                $finaljobs[$k]['date'] =  date('m/d/Y',strtotime($job['startdate']));
                $finaljobs[$k]['time'] =  date("g:i A", strtotime($job['startdate']));
                $finaljobs[$k]['jobid'] = $job['jobid'];
                //$docs = $this->Job_model->getdocs($job['jobid'],'job');
                $jurl = base_url('images/def_job.jpg');
                $docs = $this->Job_model->getBidImage('job',$job['jobid']);
                if(!empty($docs)){
                    $jurl = base_url($this->corefunctions->getMyPath($docs['docid'],$docs['dockey'],$docs['docext'],'assets/docs/','download'));

                }
                //$myjobs[$key]['imageurl'] = $jurl;
                $finaljobs[$k]['imageurl'] = $jurl;
                $finaljobs[$k]['bidcount'] = $this->Job_model->getbidcount($job['jobid']);

            }
        }
        $notcount = $this->notificationcount();
        $return["data"]['countdetails'] = array('notification'=>$notcount);
        $return["data"]['jobbids'] = $this->finalArray($finaljobs);
        $return["status"]      	= 1;
        $return["successMsg"]  	= 'Job Bids';
        $this->returnJson($return);

    }


    private function sortjobs(){

        //print "<pre>"; print_r($this->values); print "</pre>";
        if($this->values['searchtype'] == "categoryname"){
            /* Expertise Details from slug */
            $expertiseDetails     = $this -> Api_model -> getexpertisebyslug(trim($this->values['category']));

            /* Get Jobs from expertise ID */
            $jobs = $this->Job_model-> getExpertiseJobs($expertiseDetails['expertiseid'],'new',$this->values['sortby'],$this->values['sortorder']);
            //print"<pre>"; print_r($jobs ); print "</pre>";
        }else{
            $jobname = $this->values['category'];
            $userExpertises = $this->Job_model->getUserExpertise($this->session->userdata('userId'));
            $finalExpertises = $expertiseids = array();
            if( !empty(  $userExpertises) ){
                foreach( $userExpertises as $ue ){
                    $expertiseids[] = $ue["expertiseid"];
                }
            }

            $jobs = $this->Job_model-> getjobbyname(join(",",$expertiseids),$jobname,$this->values['sortby'],$this->values['sortorder']);
        }

        $mybids = $this->Job_model->getallmybid($this->session->userdata('userId'));
        if(!empty($jobs) and !empty($mybids )){
            foreach($jobs as $jk=>$j){
                foreach($mybids as $mk=>$m){
                    if($m['jobid'] == $j['jobid']){
                        // unset($jobs[$jk]);
                    }
                }
                if(isset($jobs[$jk])){
                    $cont = $this->Job_model->getContractforjob($j['jobid']);
                    if(!empty($cont) and $cont['homeowneragree'] == '1'){
                        unset($jobs[$jk]);
                    }
                }

            }
        }
        $expertises = $this->Api_model->getexpertise();
        $expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
        $finaljobs = array();
        if( !empty( $jobs ) ){
            foreach( $jobs as $k => $job ){
                $finaljobs[$k]['tittle'] = $job['jobname'];
                $finaljobs[$k]['jobdescription'] = $job['jobdescription'];
                $finaljobs[$k]['category'] = $expertises[$job['expertiseid']];
                $finaljobs[$k]['date'] =  date('m/d/Y',strtotime($job['startdate']));
                $finaljobs[$k]['time'] =  date("g:i A", strtotime($job['startdate']));
                $finaljobs[$k]['jobid'] = $job['jobid'];
                //$docs = $this->Job_model->getdocs($job['jobid'],'job');
                $jurl = base_url('images/def_job.jpg');
                $docs = $this->Job_model->getBidImage('job',$job['jobid']);
                if(!empty($docs)){
                    $jurl = base_url($this->corefunctions->getMyPath($docs['docid'],$docs['dockey'],$docs['docext'],'assets/docs/','download'));

                }
                //$myjobs[$key]['imageurl'] = $jurl;
                $finaljobs[$k]['imageurl'] = $jurl;
                $finaljobs[$k]['bidcount'] = $this->Job_model->getbidcount($job['jobid']);

            }
        }
        $notcount = $this->notificationcount();
        $return["data"]['countdetails'] = array('notification'=>$notcount);
        $return["data"]['jobbids'] = $this->finalArray($finaljobs);
        $return["status"]       = 1;
        $return["successMsg"]   = 'Job Bids';
        $this->returnJson($return);

    }

    private function finalArray($array){
        $return = array();
        if(!empty($array)){
            foreach($array as $as){
                $return[] = $as;
            }
        }
        return $return;
    }

    private function jobbiddetails(){

        /* Job Details */
        $job = $this->Job_model->getjobbyid($this->values['jobid']);


        /* User Details Details */
        $homeOwnerInfo = $this->Api_model->getUserDetailsDetailsByObjectID($job['createdby']);
        $homeOwnerInfo['lastname'] =$homeOwnerInfo['lastname'][0];

        /* Job Docs Details */
        $docs = $this->Job_model->getdocs($this->values['jobid'],'job');

        /* Expertise Details */
        $expert = $this->Api_model->getexpertisebyexpertid($job['expertiseid']);

        $jDocs = array();
        if(!empty($docs)){
            foreach($docs as $dk=>$d){

                $jDocs[$dk]['imageurl'] = ($d['doctype'] == 'image') ? base_url().$this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download') :  base_url('images/Document-icon.png');
                $jDocs[$dk]['imageid'] = $d['docid'];
                $jDocs[$dk]['doctype'] = $d['doctype'];

                $jDocs[$dk]['filename'] = $d['originalname'];
                $jDocs[$dk]['docurl'] = base_url().$this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download') ;
            }
        }
        $mybid = $this->Job_model->getmybid($this->values['jobid'],$this->session->userdata('userId'));
        $myjobs['tittle'] = $job['jobname'];
        $myjobs['budget'] = (string)(round($job['budget'],2));
        $myjobs['jobdescription'] = $job['jobdescription'];
        $myjobs['address'] = $job['address'];
        $myjobs['state'] = $job['state'];
        $myjobs['city'] = $job['city'];
        $myjobs['zip'] = $job['zip'];
        $myjobs['category'] = $expert;
        $myjobs['date'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date('m/d/Y',strtotime($job['startdate'])) : "";
        $myjobs['time'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date("g:i A", strtotime($job['startdate'])) : '';
        $myjobs['jobid'] = $job['jobid'];
        $myjobs['bidamount'] =  (!empty($mybid)) ? (string)(round($mybid['bidamount'],2)) :  "0";
        $myjobs['startdate'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date('m/d/Y',strtotime($job['startdate'])) : "";
        $myjobs['starttime'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date("g:i A", strtotime($job['startdate'])) : '';
        $daysArray = array( 1 => '1 Day', 3 => '3 Days', 7 => '1 Week', 14 => '2 Weeks', 30 => '1 Month');
        $myjobs['daysposted'] = $daysArray[$job['daysposted']];
        $myjobs['completiondate'] =  ($job['completiondate'] != '0000-00-00') ? date('m/d/Y', strtotime($job['completiondate'])) : '';
		
		$myjobs['completeddate'] = $job['completeddate'];
        $myjobs['timeframe'] = $job['timeframe'];
        $myjobs['indoor'] = $job['indoor'];
        $myjobs['hometype'] = $job['hometype'];
        $myjobs['starting_state'] = $job['starting_state'];
        $myjobs['total_stories'] = $job['total_stories'];
        $myjobs['material_option'] = $job['material_option'];
        $myjobs['rate_type'] = $job['rate_type'];
        $myjobs['year_constructed'] = $job['year_constructed'];
        $myjobs['current_condition'] = $job['current_condition'];
        $myjobs['first_problem_notice'] = $job['first_problem_notice'];
        $myjobs['resolution'] = $job['resolution'];
        $myjobs['measurements'] = $job['measurements'];
        $myjobs['material_preferences'] = $job['material_preferences'];
        $myjobs['purchased_materials'] = $job['purchased_materials'];
        $myjobs['access_to_area'] = $job['access_to_area'];
        $myjobs['your_availability'] = $job['your_availability'];
        $myjobs['relevant_info'] = $job['relevant_info'];
        $myjobs['images'] = $jDocs;

        $bids =  $this->Job_model->getbids($this->values['jobid']);
        $total = $highest = $lowest = $average = $tcnt =0;
        $userids = $userdetails = $returnuser = array();
        if(!empty($bids)){
            $lowest = $bids[0]['bidamount'];
            foreach($bids as $bi){
                //if($this->session->userdata('userId') != $bi['userid']){
                $userids[] = $bi['userid'];
                // }
                if($highest <= $bi['bidamount']){
                    $highest = $bi['bidamount'];
                }
                if($lowest >= $bi['bidamount']){
                    $lowest = $bi['bidamount'];
                }
                $total+= $bi['bidamount'];
                $tcnt++;
            }
            $average = ($tcnt >0) ? $total/$tcnt : 0;
            if(!empty($userids)){
                $userdetails =  $this->Api_model->getuserdetsbyids(array_unique($userids)) ;
            }
            if(!empty($userdetails)){
                foreach($userdetails as $uk=>$user){
                    $returnuser[$uk]['firstname'] = $user['firstname'];
                    $returnuser[$uk]['lastname'] = $user['lastname'][0];
                    $returnuser[$uk]['imageurl'] = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
                    $returnuser[$uk]['staring'] = $this->userrating($user['userid']);
                }
            }
        }



        /* Homeowner Details */
        $ho['firstname'] 	= $homeOwnerInfo['firstname'];
        $ho['lastname'] 	= $homeOwnerInfo['lastname'][0];
        $ho['userid'] 	= $homeOwnerInfo['userid'];
        $ho['staring']   = $this->userrating($homeOwnerInfo['userid']);
        $ho['imageurl'] 	= ($homeOwnerInfo['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($homeOwnerInfo['userid'], $homeOwnerInfo['imagekey'], $homeOwnerInfo['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');;

        $currentuser = $this->Api_model->getUserDetailsDetailsByObjectID($this->session->userdata('userId'));
        $cuser['firstname']  = $currentuser['firstname'];
        $cuser['lastname']   = $currentuser['lastname'][0];
        $cuser['userid']   = $currentuser['userid'];
        $cuser['staring']   = $this->userrating($currentuser['userid']);
        $cuser['imageurl']   = ($currentuser['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($currentuser['userid'], $currentuser['imagekey'], $currentuser['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');;


        $typearr = array('h'=>'Hour(s)','w'=>'Week(s)','d'=>'Day(s)');
        $average = round($average,2);
        $highest = round($highest,2);
        $lowest = round($lowest,2);

        $bidetails['numberofbids'] = (string)$tcnt;
        $bidetails['highestbid'] = (string)$highest;
        $bidetails['lowestbid'] = (string)$lowest;
        $bidetails['averagebid'] = (string)$average;

        //$contract = $this->Job_model->getContract($this->values['jobid'],$this->session->userdata('userId'));

        $contract = $this->Job_model->getContractforjob($this->values['jobid']);

        $showaddress = (!empty($contract) and $contract['bt_transaction_id'] != "") ? '1' : '0';
        if( $this->session->userdata('usertype') == 'homeowner' ){
            $showaddress = '1';
        }
        $showcontractaddress = (!empty($contract) and $contract['bt_transaction_id'] != "") ? '1' : '0';
        $return["data"]['showcontractaddress'] = $showcontractaddress;
        $return["data"]['showaddress'] = $showaddress;



        //print "<pre>";print_r($contract);print "</pre>";
        $homenowneragree = $contractoragree  = '0';
        if(!empty($contract) ){
            if(($contract['workeragree'] == "1" or $contract['workeragree'] == 1) ){
                $contractoragree = '1';
            }
        }


        if( $this->session->userdata('usertype') == 'homeowner' ){
            if(!empty($contract) ){
                if(($contract['homeowneragree'] == "0" or $contract['homeowneragree'] == 0) ){
                    $homenowneragree = '1';
                }
            }
        }else{

            if(!empty($contract) ){
                if(($contract['homeowneragree'] == "1" or $contract['homeowneragree'] == 1) and ($contract['workeragree'] == "0" or $contract['workeragree'] == 0) ){
                    $homenowneragree = '1';
                }
            }

        }

        if(!empty($mybid)){
            $bidetails['description'] = (string)$mybid['description'];
            $bidetails['bidamount'] = (string)(number_format($mybid['bidamount'],2));
            $bidetails['additionalamount'] = ($mybid['additionalamount'] != "0") ? (string)(number_format($mybid['additionalamount'])) : "";
            $bidetails['expectedcompletetime'] = ($mybid['exptime'] != '' and $mybid['exptime'] != "0") ?  (string)$mybid['exptime'] : "";
            $bidetails['expectedtimetype'] = ($mybid['exptime'] != '' and $mybid['exptime'] != "0") ? (string)$typearr[$mybid['exptype']] : "";
            $bidetails['jobid'] = (string)$mybid['jobid'];
            $bidetails['maxcompletetime'] = ($mybid['maxtime'] != '' and $mybid['maxtime'] != "0") ? (string)$mybid['maxtime'] : "";
            $bidetails['maxtimetype'] = ($mybid['maxtime'] != '' and $mybid['maxtime'] != "0") ? (string)$typearr[$mybid['maxtype']] : "";
            $bidetails['startdate'] = ($mybid['startdate'] != '0000-00-00') ? date('m/d/Y',strtotime($mybid['startdate'])) : "";
            $bidetails['starttime'] = ($mybid['starttime'] != '00:00:00') ? date('g.i A',strtotime($mybid['starttime'])) : '';
            $bidetails['homenowneragree'] = $homenowneragree;
            $bidetails['contractoragree'] = $contractoragree;
            $bidetails['expectedtime'] = ($mybid['exptime'] != '' and $mybid['exptime'] != '0') ?  $mybid['exptime']." ".$typearr[$mybid['exptype']] : '--';
            $bidetails['maximumtime'] = ($mybid['maxtime'] != '' and $mybid['maxtime'] != '0') ?  $mybid['maxtime']." ".$typearr[$mybid['maxtype']] : '--';
            $servicefee = $this->hedercontroller->getserviceamount($mybid['bidamount']);
            $amountreceive = round( $mybid['bidamount'] - $servicefee ,2);
            $constfee = SERVICEFEE;
            $bidetails['servicefee'] = (string)$constfee;
            $bidetails['servicefeeamount'] = (string)number_format($servicefee,2);
            $bidetails['amountreceive'] = (string)number_format($amountreceive,2);

        }
        // $bidnow = '0';
        $bidnow = '1';
        if( $this->session->userdata('usertype') == 'contractor' ){
            if($currentuser['bt_merchantid'] != ""){
                $bidnow = '1';
            }
        }
        $return["data"]['bidnow'] = $bidnow;

        $return['data']['currentuser'] = $cuser;
        $return["data"]['jobdetails'] = $myjobs;
        $return["data"]['homeownerdetails'] = $ho;
        $return["data"]['biddetails'] = $bidetails;
        if($this->values['isuserneeded'] == 1){
            $return["data"]['bidedusers'] = $returnuser;
        }

        $notcount = $this->notificationcount();

        $return["data"]['countdetails'] = array('notification'=>$notcount);
        $return["data"]['images'] = $jDocs;
        $return["status"]      	= 1;
        $return["successMsg"]  	= 'Job Bids Info';
        $this->returnJson($return);


    }


    private function addbid(){

        /* Job Details */
        $job = $this->Job_model->getjobbyid($this->values['jobid']);
        $mybid = $this->Job_model->getmybid($this->values['jobid'],$this->session->userdata('userId'));

        if(!empty($mybid)){
            $this->returnError("You already placed bid for this job.");
        }
        $expertises = $this->Api_model->getexpertise();
        $expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');

        $data['bidkey'] 		= $this->corefunctions->generateUniqueKey('10', 'bids', 'bidkey');
        $data['jobid'] 		= $this->values['jobid'];
        $data['userid'] 		= $this->session->userdata('userId');
        $data['bidamount'] 		= round($this->values['bidamount'],2);
        $data['additionalamount'] 	= $this->values['additionalamount'];
        $data['description']	= $this->values['description'];
        $data['exptime'] 		= $this->values['expectedcompletetime'];
        $data['exptype'] 		= $this->values['expectedtimetype'];
        $data['maxtime'] 		= $this->values['maxcompletetime'];
        $data['maxtype'] 		= $this->values['maxtimetype'];
        $data['startdate'] 		=  ($this->values['startdate'] != "") ? date('Y-m-d',strtotime($this->values['startdate'])) : '0000-00-00';
        $data['starttime']    =   ($this->values['starttime'] != "") ? date("H:i", strtotime(str_replace(".",":",$this->values['starttime']))) : '';
        //print "<pre>"; print_r($this->values);print_r($data); print "</pre>";

        $bidid = $this->Job_model->addBid($data);

        
        $this->Job_model->create_notifications($this->session->userdata('userId'),$job['createdby'],$job['jobid'],'bid',$bidid);
        $this->Job_model->create_history($this->session->userdata('userId'),1,$job['jobid'],$job['createdby']);

        $userDets = $this -> Api_model->getUserDetailsDetailsByObjectID( $job['createdby'] );
        $data['jobname'] = $job['jobname'];
        $data['bidDets'] = $data;
        $data['expertise'] = $expertises[$job['expertiseid']]['name'];
        $data['toname'] = $userDets['firstname'] ." ".$userDets['lastname'][0];
        $data['fromname'] = $this->session->userdata('userFirstname')." ".$this->session->userdata('userLastname');

        $userData['toname'] = $this->session->userdata('userFirstname')." ".$this->session->userdata('userLastname');
        $email = $this->session->userdata('userEmail');
        $msg   = $this->load->view('mail/contractor_bid', $userData, true);
        $this->corefunctions->sendmail(ADMINEMAIL, $email, TITLE . ' :: Bid Placed', $msg);

        // $msg   = $this->load->view('mail/bid', $data, true);
        // $this->corefunctions->sendmail(ADMINEMAIL, $userDets['email'], TITLE . ' :: Bid Info', $msg);

        // $message = "You have received a new bid on your job";
        // $params['jobid'] = $job['jobid'];
        // $params['jobtype'] = "bidplaced";
        // $this->hedercontroller->sendOnlyNotification($userDets['userid'], $message,$params);

        $return["status"]      	= 1;
        $return["successMsg"]  	= 'Bid Created';
        $this->returnJson($return);

    }

    private function servicefee(){
        $return["data"]['servicefeepercent']      = SERVICEFEE;
        $return["status"]       = 1;
        $return["successMsg"]   = 'Bid Created';
        $this->returnJson($return);

    }

    private function editbid(){
        $data['bidamount']    = $this->values['bidamount'];
        $data['additionalamount']   = $this->values['additionalamount'];
        $data['description']  = $this->values['description'];
        $data['exptime']    = $this->values['expectedcompletetime'];
        $data['maxtime']    = $this->values['maxcompletetime'];
        $data['maxtype']    = $this->values['maxtimetype'];
        $data['startdate']    =  ($this->values['startdate'] != "") ? date('Y-m-d',strtotime($this->values['startdate'])) : '0000-00-00';
        $data['starttime']    =   ($this->values['starttime'] != "") ? date("H:i", strtotime(str_replace(".",":",$this->values['starttime']))) : '';

        $job = $this->Job_model->getjobbyid($this->values['jobid']);

        $this->Job_model->editBid($data,$this->values['jobid'],$this->session->userdata('userId'));
        $this->Job_model->create_history($this->session->userdata('userId'),2,$this->values['jobid'],$job['createdby']);

        $return["status"]       = 1;
        $return["successMsg"]   = 'Bid Updated';
        $this->returnJson($return);

    }

    private function deletebid(){
        $this->Job_model->deleteBid($this->values['jobid'],$this->session->userdata('userId'));
        $job = $this->Job_model->getjobbyid($this->values['jobid']);
        $this->Job_model->create_history($this->session->userdata('userId'),6,$this->values['jobid'],$job['createdby']);
        $return["status"]       = 1;
        $return["successMsg"]   = 'Bid deleted';
        $this->returnJson($return);
    }

    private function shortlisted(){
        $this->Job_model->favouriteBid($this->values['jobid'],$this->values['contractoruserid'],$this->values['isshortlisted']);
        $return["status"]       = 1;
        $return["successMsg"]   = 'Bid shortlisted';
        $this->returnJson($return);
    }

    private function awarddetails(){

        /* Job Details */
        $job = $this->Job_model->getjobbyid($this->values['jobid']);


        /* User Details Details */
        $contractorInfo = $this->Api_model->getUserDetailsDetailsByObjectID($this->values['contractoruserid']);
        $contractorInfo['lastname'] =$contractorInfo['lastname'][0];

        /* Job Docs Details */
        $docs = $this->Job_model->getdocs($this->values['jobid'],'job');

        /* Expertise Details */
        $expert = $this->Api_model->getexpertisebyexpertid($job['expertiseid']);

        $jDocs = array();
        if(!empty($docs)){
            foreach($docs as $dk=>$d){

                $jDocs[$dk]['imageurl'] = ($d['doctype'] == 'image') ? base_url().$this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download') :  base_url('images/Document-icon.png');
                $jDocs[$dk]['imageid'] = $d['docid'];
                $jDocs[$dk]['doctype'] = $d['doctype'];
                $jDocs[$dk]['filename'] = $d['originalname'];
                $jDocs[$dk]['docurl'] = base_url().$this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download') ;
            }
        }
        $contracts = $this->Job_model->getContractforjob($this->values['jobid']);
        $showaddress = (!empty($contracts) and $contracts['bt_transaction_id'] != "") ? '1' : '0';
        if( $this->session->userdata('usertype') == 'homeowner' ){
            $showaddress = '1';
        }
        $showcontractaddress = (!empty($contracts) and $contracts['bt_transaction_id'] != "") ? '1' : '0';
        $data['showcontractaddress'] = $showcontractaddress;

        $data['showaddress'] = $showaddress;

        $returnuser['firstname'] = $contractorInfo['firstname'];
        $returnuser['userid'] = $contractorInfo['userid'];
        $returnuser['lastname'] = $contractorInfo['lastname'][0];
        $returnuser['email'] = $contractorInfo['email'];
        $returnuser['address'] = $contractorInfo['address'];
        $returnuser['city'] = $contractorInfo['city'];
        $returnuser['state'] = $contractorInfo['state'];
        $returnuser['phone'] = $contractorInfo['phone'];
        $returnuser['zip'] = $contractorInfo['zip'];
        $returnuser['staring'] = $this->userrating($contractorInfo['userid']);
        $returnuser['dob'] = ($contractorInfo['dob'] !='0000-00-00') ? date('m/d/Y',strtotime($contractorInfo['dob'])) : '';
        $returnuser['imgurl'] = ($contractorInfo['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($contractorInfo['userid'], $contractorInfo['imagekey'], $contractorInfo['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');;


        $myjobs['tittle'] = $job['jobname'];
        $myjobs['jobid'] = $job['jobid'];
        $myjobs['jobdescription'] = $job['jobdescription'];
        $myjobs['category'] = $expert;
        $myjobs['date'] =  ($job['startdate'] != '0000-00-00') ? date('m/d/Y',strtotime($job['startdate'])) : "";

        $myjobs['images'] = $jDocs;
        $myjobs['amount'] = (!empty($contracts)) ? (string)(round($contracts['amount'],2)) : '0';

        $typearr = array('h'=>'Hour','w'=>'Week','d'=>'Day');
        $myjobs['jobdescription'] = $job['jobdescription'];
        $myjobs['address'] = $job['address'];
        $stateInfo = $this->Api_model->getStateDetails($job['state']);
        $myjobs['state'] = $stateInfo;
        $myjobs['city'] = $job['city'];
        $myjobs['zip'] = $job['zip'];
        $myjobs['budget'] = (string)(round($job['budget'],2));
        $myjobs['category'] = $expert;
        $myjobs['date'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date('m/d/Y',strtotime($job['startdate'])) : "";
        $myjobs['time'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date("g:i A", strtotime($job['startdate'])) : '';
        $myjobs['jobid'] = $job['jobid'];
        $myjobs['bidamount'] =  (!empty($mybid)) ? (string)(round($mybid['bidamount'],2)) :  "";
        $myjobs['startdate'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date('m/d/Y',strtotime($job['startdate'])) : "";
        $myjobs['starttime'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date("g:i A", strtotime($job['startdate'])) : '';
        $daysArray = array( 1 => '1 Day', 3 => '3 Days', 7 => '1 Week', 14 => '2 Weeks', 30 => '1 Month');
        $myjobs['daysposted'] = $daysArray[$job['daysposted']];
        $myjobs['completiondate'] =  ($job['completiondate'] != '0000-00-00') ? date('m/d/Y', strtotime($job['completiondate'])) : '';


        $data['jobdetails'] = $myjobs;
        $data['contractordetails'] =$returnuser;
        $showcontractbutton = '0';
        $buttonstatus = $contractkey = $url =  "";
        if($this->session->userdata('usertype') == 'homeowner'){
            if(!empty($contracts)){
                if($contracts['homeowneragree'] == '1'){
                    if($contracts['bt_transaction_id'] == ''){
                        $buttonstatus = "cancel";
                    }
                    $contractkey = $contracts['contractkey'];
                    $url = base_url('contractagree/homeowner/'.$contractkey);
                    $showcontractbutton = '1';
                }
            }
        }else{
            if(!empty($contracts)){
                if($contracts['homeowneragree'] == '1'){
                    if($contracts['workeragree'] == '0'){
                        $buttonstatus = "agree";
                    }
                    $contractkey = $contracts['contractkey'];
                    $url = base_url('contractagree/homeowner/'.$contractkey);
                    $showcontractbutton = '1';
                }
            }

        }

        $data['showaddress'] = (!empty($contracts) and $contracts['bt_transaction_id'] != '') ? '1' : '0';
        $data['showcontractbutton'] = $showcontractbutton;
        $data['contractkey'] = $contractkey;
        $data['contracturl'] = $url;
        $data['buttonstatus'] = $buttonstatus;
        $return["data"]  = $data;
        $return["status"]       = 1;
        $return["successMsg"]   = 'Bid shortlisted';
        $this->returnJson($return);


    }


    private function fundescrow(){
        $mybid = $this->Job_model->getmybid($this->values['jobid'],$this->values['contractoruserid']);
        $contract = $this->Job_model->getContract($this->values['jobid'],$this->values['contractoruserid']);
        if(empty($contract)){
            $this->Job_model->updateothercontracts($this->values['jobid']);
            $contractkey = $this->corefunctions->generateUniqueKey('6', 'contracts', 'contractkey');
            $contractid = $this->Job_model->create_contracts($contractkey,$this->session->userdata('userId'),$this->values['contractoruserid'],$mybid['bidid'],$this->values['jobid'],$mybid['bidamount']);

        }else{
            $contractkey = $contract['contractkey'];
        }

        $contract = $this->Job_model->getContract($this->values['jobid'],$this->values['contractoruserid']);
        $data['contractkey'] = $contract['contractkey'];
        $buttonstatus = 'agree';
        if($contract['homeowneragree'] == '1'){
            if($contract['bt_transaction_id'] == ''){
                $buttonstatus = 'cancel';
            }else{
                $buttonstatus = '';
            }
        }
        $data['buttonstatus'] = $buttonstatus;

        $data['url'] = base_url('contractagree/homeowner/'.$contractkey);
        //$data['successurl'] = base_url('apipaymentsuccess/'.$contractkey);
        $return["data"]  = $data;
        $return["status"]       = 1;
        $return["successMsg"]   = 'Homeowner Agreement url';
        $this->returnJson($return);

    }

    private function contractorisagree(){
        //$mybid = $this->Job_model->getmybid($this->values['jobid'],$this->session->userdata('userId'));
        $contract = $this->Job_model->getContract($this->values['jobid'],$this->session->userdata('userId'));

        $data['url'] = base_url('contractagree/homeowner/'.$contract['contractkey']);
        $data['contractkey'] = $contract['contractkey'];

        $buttonstatus = '';
        if($contract['homeowneragree'] == '1'){
            if($contract['workeragree'] == '0'){
                $buttonstatus = 'agreecancel';
            }
        }
        $data['buttonstatus'] = $buttonstatus;
        //$data['buttonstatus'] = ( $contract['homeowneragree'] == '1' and  $contract['workeragree'] == '0') ? 'agree' : '';
        //$data['successurl'] = base_url('apipaymentsuccess/'.$contractkey);
        $return["data"]  = $data;
        $return["status"]       = 1;
        $return["successMsg"]   = 'Contractor Agreement url';
        $this->returnJson($return);

    }

    private function contractorverify(){
        if($this->session->userdata('usertype') == 'homeowner'){
            $contract = $this->Job_model->getContractforjob($this->values['jobid']);
            if(empty($contract)){
                $this->returnError("Invalid details.");
            }

            $this->Job_model->updateContractorfields('homeowneragree',$contract['contractkey']);

            $params['jobid'] = $contract['jobid'];
            $params['jobtype'] = "bidplaced";

            $this->Job_model->create_notifications($contract['ownerid'],$contract['contractorid'],$contract['jobid'],'contract',$contract['contractid'],$params);
            $message = "You have received the contract.";
            $this->Job_model->create_message($message,$this->session->userdata('userId'),$contract['contractorid'],$this->values['jobid']);

            // $message = "Congratulations! Your Bid is accepted by the home owner.";
            // $params['jobid'] = $contract['jobid'];
            // $params['jobtype'] = "bidplaced";
            // $this->hedercontroller->sendOnlyNotification($contract['contractorid'], $message,$params);
            $job_data['toname'] = $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname');
            $userEmail = $this->session->userdata('userEmail');
            $msg   = $this->load->view('mail/hire', $job_data, true);
            $this->corefunctions->sendmail(ADMINEMAIL, $userEmail, TITLE . ' :: Job Post', $msg);
              
            $return["status"]       = 1;
            $return["successMsg"]   = 'Homeowner Agreed.';
        }else{
            $contract = $this->Job_model->getContract($this->values['jobid'],$this->session->userdata('userId'));
            if(empty($contract)){
                $this->returnError("Invalid details.");
            }

            $this->Job_model->updateContractorfields('workeragree',$contract['contractkey']);
            $this->Job_model->updateJobfields('jobstatus','inprogress',$contract['jobid']);
            $this->Job_model->create_notifications($this->session->userdata('userId'),$contract['ownerid'],$contract['jobid'],'jstart',$contract['contractid']);

            $this->Job_model->create_history($this->session->userdata('userId'),3,$this->values['jobid'],$contract['ownerid']);
            $userDets = $this -> User_model -> getuserdetsbyid( $contract['contractorid'] );

            $data['firstname'] = $userDets['firstname'];
            $data['lastname'] = $userDets['lastname'];

            // $msg   = $this->load->view('mail/job-accepted', $data, true);
            // $this->corefunctions->sendmail(ADMINEMAIL, $userDets['email'], TITLE . ' :: Job Accepted', $msg);

            // $message = "Congratulations! Contractor has accepted your Job.";
            // $params['jobid'] = $contract['jobid'];
            // $params['jobtype'] = "bidplaced";
            // $this->hedercontroller->sendOnlyNotification($contract['ownerid'], $message,$params);

            $data['bt_accountverified'] = $userDets['bt_accountverified'];
            $return["data"]       = $data;
            $return["status"]       = 1;
            $return["successMsg"]   = 'Contractor Agreed.';

        }

        $this->returnJson($return);
    }



    private function verifiedpayment(){
        $contractorInfo = $this->Api_model->getUserDetailsDetailsByObjectID($this->values['contractoruserid']);
        if($contractorInfo['bt_accountverified'] == "0"){
            $this->returnError("The contractor needs to complete their banking information for you to award work and add funds to the escrow account.");
        }
        /* Job Details */
        $job = $this->Job_model->getjobbyid($this->values['jobid']);


        /* User Details Details */

        //$contract = $this->Job_model->getContract($this->values['jobid'],$this->session->userdata('userId'));
        $mybid = $this->Job_model->getmybid($this->values['jobid'],$this->values['contractoruserid']);

        $returnuser['firstname'] = $contractorInfo['firstname'];
        $returnuser['lastname'] = $contractorInfo['lastname'][0];
        $returnuser['email'] = $contractorInfo['email'];
        $returnuser['address'] = $contractorInfo['address'];
        $returnuser['city'] = $contractorInfo['city'];
        $returnuser['state'] = $contractorInfo['state'];
        $returnuser['phone'] = $contractorInfo['phone'];
        $returnuser['zip'] = $contractorInfo['zip'];
        //$returnuser['dob'] = ($contractorInfo['dob'] !='0000-00-00') ? date('m/d/Y',strtotime($contractorInfo['dob'])) : '';
        //$returnuser['imgurl'] = ($contractorInfo['imgkey'] != "") ?  base_url($this->corefunctions->getMyPath($contractorInfo['userid'], $contractorInfo['imgkey'], $contractorInfo['imgext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');;

        $myjobs['tittle'] = $job['jobname'];
        $myjobs['budget'] = (string)(round($job['budget'],2));
        $myjobs['jobid'] = $job['jobid'];
        $myjobs['amount'] = (!empty($mybid)) ? (string)(round($mybid['bidamount'],2)) : "";
        $myjobs['date'] =  date('m/d/Y',strtotime($job['startdate']));



        $data['jobdetails'] = $myjobs;
        $data['contractordetails'] =$returnuser;
        $return["data"]  = $data;
        $return["status"]       = 1;
        $return["successMsg"]   = 'verifiedpayment';
        $this->returnJson($return);


    }


    private function paymentrelase(){
        $contract = $this->Job_model->getContract($this->values['jobid'],$this->values['contractoruserid']);

        $data['url'] = base_url('makepayment/'.$contract['contractkey']);
        $data['successurl'] = base_url('apipaymentsuccess/'.$contract['contractkey']);
        $data['failedurl'] = base_url('apipaymentfail/'.$contract['contractkey']);
        $return["data"]  = $data;
        $return["status"]       = 1;
        $return["successMsg"]   = 'paymentrelase url';
        $this->returnJson($return);
    }

    private function myprofile(){
        $user = $this->Api_model->user_by_key($this->session->userdata('userKey'));
        $user['imgurl'] = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');;
        $user['dob'] = ($user['dob'] != '0000-00-00') ? date('m/d/Y',strtotime($user['dob'])) : '';
        $user['intro_video'] = ($user['intro_video'] != "") ? base_url('assets/videos/'.$user['intro_video']) : '';
        $user['state'] = ($user['state'] != "") ? $this->Api_model->getStateDetails($user['state']) : '';
        $user['lastnameshort'] = $user['lastname'][0];
        $data = $user;
        $expertiseids = $expertise =  $exArray = array();


        if($this->session->userdata('usertype') == 'contractor'){
            // work images
            $workImages = $this->Api_model->getWorkImages($user['userid']);
            foreach ($workImages as $i=>$vl) {
                $workImages[$i]['imageurl'] = base_url('assets/docs/00000/'.$vl['dockey'].'.'.$vl['docext']);
            }

            $data['workImages'] = $workImages;

            $userexpertise = $this->Api_model->getuserexpertise($user['userid']);
            if(!empty($userexpertise)){
                foreach($userexpertise as $ue){
                    $expertiseids[] = $ue['expertiseid'];
                }
            }
            $retexpertise = '';
            if(!empty($expertiseids)){
                $expertise = $this->Api_model->getexpertisebyids(array_unique($expertiseids));
                /*foreach($expertise as $e){
                  $exArray[] = $e['name'];
                }
                $retexpertise = join(',',$exArray);*/
            }
            $data['expertise'] = $expertise;
            $contractordetails = $this->Api_model->getcontractordetails($user['userid']);
            //var_dump($contractordetails);exit;
            $companydetails['companyname'] = $contractordetails['companyname'];
            $companydetails['taxid'] = $contractordetails['taxid'];
            $companydetails['companyaddress'] = $contractordetails['companyaddress'];
            $companydetails['companystate'] = ($contractordetails['companystate'] !="") ? $this->Api_model->getStateDetails($contractordetails['companystate']) : '';
            $companydetails['companycity'] = $contractordetails['companycity'];
            $companydetails['companyzip'] = $contractordetails['companyzip'];

            $licenseandbankdetails['license'] = ($contractordetails['license'] != "") ? $contractordetails['license'] : '';
            $licenseandbankdetails['insurancestatus'] = ($contractordetails['insurance'] != "") ? '1' : '0';
            $licenseandbankdetails['licensestatus'] = ($contractordetails['license'] != "") ? '1' : '0';
            $licenseandbankdetails['insurance'] = ($contractordetails['insurance'] != "") ? $contractordetails['insurance'] : '';
            $licenseandbankdetails['routingnumber'] =  '';
            $licenseandbankdetails['accountnumber'] =  '';
            $iscompany = "0";
            if($contractordetails['companyname'] != "" or $contractordetails['taxid'] != ""){
                $data['companydetails'] = $companydetails;
                $iscompany = "1";
            }

            // contractor other detail
            $data['overview_experience'] = $contractordetails['overview_experience'];
            $data['certifications'] = $contractordetails['certifications'];
            $data['introduction'] = $contractordetails['introduction'];
            $data['us_veteran'] = $contractordetails['us_veteran'];
            $data['contractorLicense'] = $contractordetails['contractorLicense'];
            $data['contractorInsurance'] = $contractordetails['contractorInsurance'];

            $data['iscompany'] = $iscompany;
            $ttl = $this->Job_model->getContractorCompletedJobCount($user['userid']);
            $data['completedJobsCount'] = $ttl['ttl'];

            $data['licenseandbankdetails'] = $licenseandbankdetails;
            // expertise,companydetails,licenseandbankdetails
        }

        $notcount = $this->notificationcount();
        $data['countdetails'] = array('notification'=>$notcount);

        $return["data"]  = $data;
        $return["status"]       = 1;
        $return["successMsg"]   = 'Myprofile';
        $this->returnJson($return);
    }

    function getProfileById(){
        $userId = $this->input->get('userId');
        $user = $this->Api_model->user_by_key($userId);
        //var_dump($user);exit;
        $user['imgurl'] = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');;
        $user['dob'] = ($user['dob'] != '0000-00-00') ? date('m/d/Y',strtotime($user['dob'])) : '';
        $user['intro_video'] = ($user['intro_video'] != "") ? base_url('assets/videos/'.$user['intro_video']) : '';
        $user['state'] = ($user['state'] != "") ? $this->Api_model->getStateDetails($user['state']) : '';
        $user['lastnameshort'] = $user['lastname'][0];
        $data = $user;
        $expertiseids = $expertise =  $exArray = array();


        if($user['usertype'] == 'contractor'){
            // work images
            $workImages = $this->Api_model->getWorkImages($user['userid']);
            foreach ($workImages as $i=>$vl) {
                $workImages[$i]['imageurl'] = base_url('assets/docs/00000/'.$vl['dockey'].'.'.$vl['docext']);
            }

            $data['workImages'] = $workImages;

            $userexpertise = $this->Api_model->getuserexpertise($user['userid']);
            if(!empty($userexpertise)){
                foreach($userexpertise as $ue){
                    $expertiseids[] = $ue['expertiseid'];
                }
            }
            $retexpertise = '';
            if(!empty($expertiseids)){
                $expertise = $this->Api_model->getexpertisebyids(array_unique($expertiseids));
                /*foreach($expertise as $e){
                  $exArray[] = $e['name'];
                }
                $retexpertise = join(',',$exArray);*/
            }
            $data['expertise'] = $expertise;
            $contractordetails = $this->Api_model->getcontractordetails($user['userid']);
            //var_dump($contractordetails);exit;
            $companydetails['companyname'] = $contractordetails['companyname'];
            $companydetails['taxid'] = $contractordetails['taxid'];
            $companydetails['companyaddress'] = $contractordetails['companyaddress'];
            $companydetails['companystate'] = ($contractordetails['companystate'] !="") ? $this->Api_model->getStateDetails($contractordetails['companystate']) : '';
            $companydetails['companycity'] = $contractordetails['companycity'];
            $companydetails['companyzip'] = $contractordetails['companyzip'];

            $licenseandbankdetails['license'] = ($contractordetails['license'] != "") ? $contractordetails['license'] : '';
            $licenseandbankdetails['insurancestatus'] = ($contractordetails['insurance'] != "") ? '1' : '0';
            $licenseandbankdetails['licensestatus'] = ($contractordetails['license'] != "") ? '1' : '0';
            $licenseandbankdetails['insurance'] = ($contractordetails['insurance'] != "") ? $contractordetails['insurance'] : '';
            $licenseandbankdetails['routingnumber'] =  '';
            $licenseandbankdetails['accountnumber'] =  '';
            $iscompany = "0";
            if($contractordetails['companyname'] != "" or $contractordetails['taxid'] != ""){
                $data['companydetails'] = $companydetails;
                $iscompany = "1";
            }

            // contractor other detail
            $data['overview_experience'] = $contractordetails['overview_experience'];
            $data['certifications'] = $contractordetails['certifications'];
            $data['introduction'] = $contractordetails['introduction'];
            $data['us_veteran'] = $contractordetails['us_veteran'];
            $data['contractorLicense'] = $contractordetails['contractorLicense'];
            $data['contractorInsurance'] = $contractordetails['contractorInsurance'];

            $data['iscompany'] = $iscompany;
            $ttl = $this->Job_model->getContractorCompletedJobCount($user['userid']);
            $data['completedJobsCount'] = $ttl['ttl'];

            $data['licenseandbankdetails'] = $licenseandbankdetails;
            // expertise,companydetails,licenseandbankdetails
        }

        $notcount = $this->notificationcount();
        $data['countdetails'] = array('notification'=>$notcount);

        $return["data"]  = $data;
        $return["status"]       = 1;
        $return["successMsg"]   = 'Myprofile';
        $this->returnJson($return);
    }

    private function deleteImage(){
        $this->Api_model->deleteImage($this->values['id']);
        $return["status"]       = 1;
        $return["successMsg"]   = 'Image Deleted Successfully';
        $this->returnJson($return);
    }

    private function editprofile(){
        $error = 0;
        /*if( $this->session->userdata('usertype') == 'contractor' ){
          $btmerchantId     = $userDets['bt_merchantid'];
          //$input = $this->input->post();
            $btdata = array(
                    'fName' => $this->values['firstname'],
                    'lName' => $this->values['lastname'],
                    'address' => $this->values['address'],
                    'state' => $this->values['state'],
                    'city' => $this->values['city'],
                    'zip' => $this->values['zip'],
                    'dob'=>date('Y-m-d',strtotime($this->values['dob'])),
                    'phone' => preg_replace('/\D+/', '', $this->values['phone']),

                    'email' => $userDets['email'],

                    'license' => $this->values['licenseandbankdetails']['license'],
                    'insurance' => $this->values['licenseandbankdetails']['insurance'],
                    'routingNumber' => $this->values['licenseandbankdetails']['routingnumber'],
                    'accountNumber' => $this->values['licenseandbankdetails']['accountnumber'],
                    'agreeTerms' => 1

               );

              if (isset($this->values['companyname'])) {

                  $btdata['company'] = $this->values['companyname'];
                  $btdata['taxId'] = $this->values['taxid'];
                  $btdata['companyAddress'] = $this->values['companyaddress'];
                  $btdata['companyState'] = $this->values['companystate'];
                  $btdata['companyCity'] = $this->values['companycity'];
                  $btdata['companyZip'] = $this->values['companyzip'];

              }
          if(isset($this->values['licenseandbankdetails']['routingnumber']) and $this->values['licenseandbankdetails']['routingnumber'] != "" and isset($this->values['licenseandbankdetails']['accountnumber']) and $this->values['licenseandbankdetails']['accountnumber'] != "" and $userDets['bt_merchantid'] == ""){
                  $btMerchantResult   = $this->Api_model->create_bt_merchant_myprofile($btdata);
                  if($btMerchantResult->success){
                    $btmerchantId     = $btMerchantResult->merchantAccount->id;
                    $this->Api_model->update_btaccountverified($userDets['userid']);

                  }else{
                    $error = 1;
                  }
              }else if(isset($this->values['licenseandbankdetails']['routingnumber']) and $this->values['licenseandbankdetails']['routingnumber'] != "" and isset($this->values['licenseandbankdetails']['accountnumber']) and $this->values['licenseandbankdetails']['accountnumber'] != "" and $userDets['bt_merchantid'] != ""){
                $btMerchantResult   = $this->Api_model->update_bt_funding($userDets['bt_merchantid'],$btdata);

                if($btMerchantResult->success){
                    $btmerchantId     = $btMerchantResult->merchantAccount->id;
                    $this->Api_model->update_btaccountverified($userDets['userid']);
                  }else{
                    $error = 1;
                  }
              }else if( $userDets['bt_merchantid'] != ""){
                $btMerchantResult   = $this->Api_model->update_bt_merchant($userDets['bt_merchantid'],$btdata);
                if($btMerchantResult->success){
                    $btmerchantId     = $btMerchantResult->merchantAccount->id;
                  }else{
                    $error = 1;
                  }
              }
            }*/
        if($error){
            $this->returnError("Please enter valid details.");
        }


        if($this->session->userdata('userEmail') != $this->values['email']){
            $checkuser = $this->User_model->check_login_creds($this->values['email']);
            if(!empty($checkuser)){
                $this->returnError("Email already exists.");
            }
        }

        // exit;

        if($this->session->userdata('usertype') == 'contractor'){
            $user = $this->Api_model->user_by_key($this->session->userdata('userKey'));
            $btdata = array(
                'fName' => $this->values['firstname'],
                'lName' => $this->values['lastname'],
                'address' => $this->values['address'],
                'state' => $this->values['state'],
                'city' => $this->values['city'],
                'zip' => $this->values['zip'],
                'dob'=>date('Y-m-d',strtotime($this->values['dob'])),
                'phone' => preg_replace('/\D+/', '', $this->values['phone']),
                'email' => $user['email'],
                'routingNumber' => $this->values['licenseandbankdetails']['routingnumber'],
                'accountNumber' => $this->values['licenseandbankdetails']['accountnumber'],
                'agreeTerms' => 1

            );

            if (isset($this->values['companyname'])) {
                $btdata['company'] = $this->values['companyname'];
                $btdata['taxId'] = $this->values['companydetails']['taxId'];
            }

            //$result = $this->Api_model->update_bt_merchant($user['bt_merchantid '],$btdata);
            $error = 0;
            if($this->values['licenseandbankdetails']['routingnumber'] != "" and $this->values['licenseandbankdetails']['accountnumber'] != "" and $user['bt_merchantid'] == ""){
                $btMerchantResult   = $this->Api_model->create_bt_merchant_myprofile($btdata);
                if($btMerchantResult->success){
                    $btmerchantId     = $btMerchantResult->merchantAccount->id;
                    $this->Api_model->update_btaccountverified($user['userid']);


                }else{
                    $error = 1;
                }
            }else if($this->values['licenseandbankdetails']['routingnumber'] != ""  and $this->values['licenseandbankdetails']['accountnumber'] != ""  and $user['bt_merchantid'] != ""){
                $btMerchantResult   = $this->Api_model->update_bt_funding($user['bt_merchantid'],$btdata);
                if($btMerchantResult->success){
                    $btmerchantId     = $btMerchantResult->merchantAccount->id;
                    $this->Api_model->update_btaccountverified($user['userid']);
                }else{
                    $error = 1;
                }
            }else if( $userDets['bt_merchantid'] != ""){
                $btMerchantResult   = $this->Api_model->update_bt_merchant($user['bt_merchantid'],$btdata);
                if($btMerchantResult->success){
                    $btmerchantId     = $btMerchantResult->merchantAccount->id;
                }else{
                    $error = 1;
                }
            }
            if(($this->values['licenseandbankdetails']['routingnumber'] != "" and $this->values['licenseandbankdetails']['accountnumber'] == "" ) or ($this->values['licenseandbankdetails']['routingnumber'] == "" and $this->values['licenseandbankdetails']['accountnumber'] != "" )){
                $this->returnError("Please enter valid banking information.");
            }

            if($error){
                $this->returnError("Please enter valid details.");
            }
        }

        $this->Api_model->update_user($this->session->userdata('userId'),  $this->values);
        if($this->session->userdata('usertype') == 'contractor'){
            if($btmerchantId != '' and $user['bt_merchantid'] == ""){
                $this->Api_model->updatebtmerchantid($this->session->userdata('userId'),$btmerchantId);
            }
            $this->Api_model->update_contractor_details($this->session->userdata('userId'),  $this->values);

            $expertise = $this->Api_model->getexpertise();
            $expertise = $this->corefunctions->getArrayIndexed($expertise,'expertiseid');

            $userexpertise = $this->Api_model->getuserexpertise($this->session->userdata('userId'));

            if(!empty($userexpertise)){
                foreach($userexpertise as $ue){
                    $expertiseids[] = $expertise[$ue['expertiseid']]['slug'];
                }
            }
            //print "<pre>"; print_r($this->values["expertise"]); print_r($expertiseids); print_r($todelete); print_r($toinsert); print "</pre>";
            $todelete = array_diff($expertiseids, $this->values["expertise"]);
            $toinsert = array_diff( $this->values["expertise"],$expertiseids);
            $expertise = $this->corefunctions->getArrayIndexed($expertise,'slug');
            if(!empty($todelete)){
                foreach($todelete as $exp){
                    $this->Api_model->updateuser_expertise($this->session->userdata('userId'), trim($expertise[$exp]['expertiseid']));
                }
            }
            if(!empty($toinsert)){
                foreach($toinsert as $exp){
                    $this->Api_model->create_user_expertise($this->session->userdata('userId'), trim($expertise[$exp]['expertiseid']));
                }
            }

        }
        $userDetails = $this->Api_model->user_by_key($this->session->userdata('userKey'));
        $enckey = md5(DEVICESECRET . strtolower($userDetails["email"]) . $this->values["password"]);
        $set_sesion = array(
            'userId' => $userDetails['userid'],
            'userKey' => $userDetails['userkey'],
            'userFirstname' => $userDetails['firstname'],
            'userLastname' => $userDetails['lastname'],
            'usertype' => $userDetails['usertype'],
            'deviceid' => $deviceid,
            'userEmail' => $userDetails["email"],
            'userImg' => $imagepath,
            //'password' => $userDetails["password"],
            'enckey' => $enckey
        );
        $this->session->set_userdata($set_sesion);
        $return["data"]["enckey"]       = $enckey;
        $return["status"]       = 1;
        $return["successMsg"]   = 'Updated';
        $this->returnJson($return);


    }



    private function uploadprofileimage(){

        if ($this->values["image"] and $this->values["image"] != '') {
            $img = '';
            $imgkey  = $this->corefunctions->generateUniqueKey('6', 'users', 'imagekey');
            $orgpath = $this->corefunctions->getMyPath($this->session->userdata('userId'), $imgkey, 'jpg', 'assets/profImgs/original/');
            $cppath  = $this->corefunctions->getMyPath($this->session->userdata('userId'), $imgkey, 'jpg', 'assets/profImgs/crop/');
            if (file_put_contents($orgpath, base64_decode($this->values["image"])) !== false) {
                if (file_put_contents($cppath, base64_decode($this->values["image"])) !== false) {
                    $this->User_model->update_img_User($this->session->userdata('userId'), $imgkey, 'jpg');
                    $img = base_url($cppath);

                }
            }

        }
        $return["data"]["imageurl"] = $img;
        $return["status"]       = 1;
        $return["successMsg"]   = 'Updated';
        $this->returnJson($return);

    }

    private function uploadWorkImage(){
        if ($this->values["image"] and $this->values["image"] != '') {
            $img = '';
            $imgkey  = $this->corefunctions->generateUniqueKey('6', 'docs', 'dockey');
            $orgpath = $this->corefunctions->getMyPath($this->session->userdata('userId'), $imgkey, 'jpg', 'assets/docs/');
            //$cppath  = $this->corefunctions->getMyPath($this->session->userdata('userId'), $imgkey, 'jpg', 'assets/profImgs/crop/');
            if (file_put_contents($orgpath, base64_decode($this->values["image"])) !== false) {
                //if (file_put_contents($cppath, base64_decode($this->values["image"])) !== false) {
                    $this->User_model->add_work_image($imgkey,'jpg','work_image', $this->session->userdata('userId'),'');
                    $img = base_url($orgpath);
                //}
            }
        }
        $return["data"]["imageurl"] = $img;
        $return["status"]       = 1;
        $return["successMsg"]   = 'Image uploaded successfully';
        $this->returnJson($return);
    }

    function uploadIntoVideo(){
        $user_id = $this->input->post('userId');
        ini_set('max_execution_time',300);
        if(isset($_FILES['intro_video']['tmp_name']))
        {
            //echo '<pre>';print_r($_FILES);exit;
            $config['upload_path']          = APPPATH."../assets/videos";
            $config['allowed_types']        = 'm4v|ogg|mp4|mov';
            $config['max_size']             = 50000;
            $ext = explode(".",$_FILES['intro_video']['name']);
            $ext = end($ext);
            $output_file_name = 'post_'.mt_rand();
            $file_name = $output_file_name.".$ext";
            $config['file_name']            = $file_name;
            $this->load->library('upload', $config);
            if ( !$this->upload->do_upload('intro_video'))
            {
                $return["data"]["video"] = '';
                $return["status"]       = 0;
                $return["successMsg"]   = 'Something went wrong';
                $this->returnJson($return);
            }
            $this->User_model->add_user_intro_video($user_id,$file_name);
            $return["data"]["video"] = $file_name;
            $return["status"]       = 1;
            $return["successMsg"]   = 'Video uploaded successfully';
            $this->returnJson($return);
        }

        $return["data"]["video"] = '';
        $return["status"]       = 0;
        $return["successMsg"]   = 'No file found';
        $this->returnJson($return);
    }


    private function jobcompleted(){
        $this->Job_model->updateJobfields('jobstatus','completed',$this->values["jobid"]);
        $this->Job_model->updateJobfields('completeddate',date('Y-m-d'),$this->values["jobid"]);
        $contract = $this->Job_model->getContract($this->values["jobid"],$this->session->userdata('userId'));
        $this->Job_model->updateContractorfields('completed',$contract['contractkey']);
        $this->Job_model->create_notifications($contract['contractorid'],$contract['ownerid'],$contract['jobid'],'jcomplete',$contract['contractid']);
        $this->Job_model->create_history($this->session->userdata('userId'),4,$this->values['jobid'],$contract['ownerid']);
        $this->Job_model->create_message($this->values["comment"],$this->session->userdata('userId'),$contract['ownerid'],$contract['jobid'],'0','1');
        $return["status"]       = 1;
        $return["successMsg"]   = 'Completed';
        $this->returnJson($return);
    }

    private function addcompletedjobimage(){
        $contract = $this->Job_model->getContractforjob($this->values["jobid"]);
        $messageid = $this->Job_model->create_message('',$this->session->userdata('userId'),$contract['ownerid'],$contract['jobid'],'1','1');
        $this->values["filename"] = "message";
        $this->uploadDocs('message',$messageid,$this->values);
        $return["status"]      = 1;
        $return["successMsg"]  = 'Document uploaded.';
        $this->returnJson($return);
    }

    private function mybids(){

        $jobids = $mybids = $cjobids = array();
        $mybids = $this->Job_model->getallmybid($this->session->userdata('userId'));
        $contracts = $this->Job_model->getContractforcontractors($this->session->userdata('userId'));
        if(!empty($contracts)){
            foreach($contracts as $c){
                if($c['homeowneragree'] == "1" and $c['workeragree'] == "1"){
                    $cjobids[] = $c['jobid'];
                }
            }
        }
        if(!empty($mybids)){
            foreach($mybids as $my){
                if(!in_array($my['jobid'],$cjobids)){
                    $jobids[] = $my['jobid'];
                }

            }
        }
        if(!empty($jobids)){
            $jobs = $this->Job_model->getjobsbyids(array_unique($jobids));
        }

        $returnjobs = $myjobs = array();
        $bidcount = 0;
        if(!empty($jobs)){
            foreach ($jobs as $key => $value) {
                $contratorids[] = $value['createdby'];
                $expertiseids[] = $value['expertiseid'];
            }

            $contractorsetails =  $this->Api_model->getuserdetsbyids(array_unique($contratorids)) ;
            $contractorsetails = $this->corefunctions->getArrayIndexed($contractorsetails,'userid');
            $expertise =  $this->Api_model->getexpertisebyids(array_unique($expertiseids)) ;
            $expertise = $this->corefunctions->getArrayIndexed($expertise,'expertiseid');
            if(!empty($contractorsetails)){
                foreach($contractorsetails as $cdk=>$cdv){
                    $contractorsetails[$cdk]['lastname'] =$cdv['lastname'][0];
                }
            }


            foreach ($jobs as $key => $value) {
                $myjobs[$key]['tittle'] = $value['jobname'];
                $myjobs[$key]['budget'] = (string)(round($value['budget'],2));
                $myjobs[$key]['jobdescription'] = $value['jobdescription'];
                $myjobs[$key]['category'] = $expertise[$value['expertiseid']];
                $myjobs[$key]['date'] = date('m/d/Y',strtotime($value['startdate']));
                $myjobs[$key]['time'] =  date("g:i A", strtotime($value['startdate']));
                $myjobs[$key]['jobid'] = $value['jobid'];
                $myjobs[$key]['contractorsetails'] = $contractorsetails[$value['createdby']];
                $jurl = base_url('images/def_job.jpg');
                $docs = $this->Job_model->getBidImage('job',$value['jobid']);
                if(!empty($docs)){
                    $jurl = base_url($this->corefunctions->getMyPath($docs['docid'],$docs['dockey'],$docs['docext'],'assets/docs/','download'));

                }
                $myjobs[$key]['imageurl'] = $jurl;
                $myjobs[$key]['doc'] = 'image';
                $myjobs[$key]['bidcount'] = $this->Job_model->getbidcount($value['jobid']);

            }
        }


        $returnjobs['jobdetails'] = $myjobs;
        $notcount = $this->notificationcount();
        $returnjobs['countdetails'] = array('notification'=>$notcount);
        $return["data"]        = $returnjobs;
        $return["status"]      = 1;
        $return["successMsg"]  = 'Job list.';
        $this->returnJson($return);
    }

    private function Jobpostdetails(){

        /* Job Details */
        $job = $this->Job_model->getjobbyid($this->values['jobid']);

        /* User Details Details */
        $homeOwnerInfo = $this->Api_model->getUserDetailsDetailsByObjectID($job['createdby']);
        $homeOwnerInfo['lastname'] =$homeOwnerInfo['lastname'][0];

        /* Expertise Details */
        $expert = $this->Api_model->getexpertisebyexpertid($job['expertiseid']);

        /* Job Docs Details */
        $docs = $this->Job_model->getdocs($this->values['jobid'],'job');

        /* State Details */
        $stateInfo = $this->Api_model->getStateDetails($job['state']);

        $jDocs = array();
        if(!empty($docs)){
            foreach($docs as $dk=>$d){

                $jDocs[$dk]['imageurl'] = ($d['doctype'] == 'image') ? base_url().$this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download') :  base_url('images/Document-icon.png');
                $jDocs[$dk]['imageid'] = $d['docid'];
                $jDocs[$dk]['doctype'] = $d['doctype'];

                $jDocs[$dk]['filename'] = $d['originalname'];
                $jDocs[$dk]['docurl'] = base_url().$this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download') ;
            }
        }

        $daysArray = array( 1 => '1 Day', 3 => '3 Days', 7 => '1 Week', 14 => '2 Weeks', 30 => '1 Month');


        $myjobs['tittle'] = $job['jobname'];
        $myjobs['jobdescription'] = $job['jobdescription'];
        $myjobs['category'] = $expert;
        $myjobs['date'] = ($job['startdate'] != '0000-00-00 00:00:00') ?  date('m/d/Y',strtotime($job['startdate'])) : '';
        $myjobs['time'] = ($job['startdate'] != '0000-00-00 00:00:00') ?  date("g:i A", strtotime($job['startdate'])) : '';
        $myjobs['jobid'] = $job['jobid'];
        $myjobs['address'] = $job['address'];
        $myjobs['state'] = $stateInfo;
        $myjobs['city'] = $job['city'];
        $myjobs['zip'] = $job['zip'];
        // extra fields
        $myjobs['timeframe'] = $job['timeframe'];
        $myjobs['indoor'] = $job['indoor'];
        $myjobs['hometype'] = $job['hometype'];
        $myjobs['starting_state'] = $job['starting_state'];
        $myjobs['total_stories'] = $job['total_stories'];
        $myjobs['material_option'] = $job['material_option'];
        $myjobs['rate_type'] = $job['rate_type'];
        $myjobs['year_constructed'] = $job['year_constructed'];
        $myjobs['current_condition'] = $job['current_condition'];
        $myjobs['first_problem_notice'] = $job['first_problem_notice'];
        $myjobs['resolution'] = $job['resolution'];
        $myjobs['measurements'] = $job['measurements'];
        $myjobs['material_preferences'] = $job['material_preferences'];
        $myjobs['purchased_materials'] = $job['purchased_materials'];
        $myjobs['access_to_area'] = $job['access_to_area'];
        $myjobs['your_availability'] = $job['your_availability'];
        $myjobs['relevant_info'] = $job['relevant_info'];


        $myjobs['daysposted'] = $daysArray[$job['daysposted']];
        $myjobs['images'] = $jDocs;
        $myjobs['completiondate'] = date('m/d/Y',strtotime($job['completiondate']));
        $myjobs['completeddate'] = date('m/d/Y',strtotime($job['completeddate']));

        $typearr = array('h'=>'Hour','w'=>'Week','d'=>'Day');
        $myjobs['jobdescription'] = $job['jobdescription'];
        $myjobs['address'] = $job['address'];
        //$myjobs['state'] = $job['state'];
        $myjobs['city'] = $job['city'];
        $myjobs['budget'] = (string)(round($job['budget'],2));
        $myjobs['zip'] = $job['zip'];
        $myjobs['category'] = $expert;
        $myjobs['date'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date('m/d/Y',strtotime($job['startdate'])) : "";
        $myjobs['time'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date("g:i A", strtotime($job['startdate'])) : '';
        $myjobs['jobid'] = $job['jobid'];
        $myjobs['bidamount'] =  (!empty($mybid)) ? (string)(round($mybid['bidamount'],2)) :  "";
        $myjobs['startdate'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date('m/d/Y',strtotime($job['startdate'])) : "";
        $myjobs['starttime'] =  ($job['startdate'] != '0000-00-00 00:00:00') ? date("g:i A", strtotime($job['startdate'])) : '';
        $daysArray = array( 1 => '1 Day', 3 => '3 Days', 7 => '1 Week', 14 => '2 Weeks', 30 => '1 Month');
        $myjobs['daysposted'] = $daysArray[$job['daysposted']];
        $myjobs['completiondate'] =  ($job['completiondate'] != '0000-00-00') ? date('m/d/Y', strtotime($job['completiondate'])) : '';


        $mybid = $this->Job_model->getmybid($this->values['jobid'],$this->session->userdata('userId'));

        $myjobs['bidamount'] =  (!empty($mybid)) ? (string)(round($mybid['bidamount'],2)) :  "";

        $data['jobdetails'] = $myjobs;
        if ($homeOwnerInfo['imagekey']) {
            $imagepath = base_url($this->corefunctions->getMyPath($homeOwnerInfo['userid'], $homeOwnerInfo['imagekey'], $homeOwnerInfo['imageext'], 'assets/profImgs/crop/'));
        } else {
            $imagepath = base_url('images/defaultimg.jpg');
        }
        $homeOwnerInfo['imgurl'] = $imagepath;
        $homeOwnerInfo['dob'] = ($homeOwnerInfo['dob'] != '0000-00-00') ? date('m/d/Y',strtotime($homeOwnerInfo['dob'])) : '';
        /* To change later */
        $data['homeownerdetails'] = $homeOwnerInfo;
        $notcount = $this->notificationcount();
        $data['countdetails'] = array('notification'=>$notcount);

        $contracts = $this->Job_model->getContractforjob($job['jobid']);
        $buttonstatus = $contractkey = $url =  "";
        $showcontractbutton = '0';
        if($this->session->userdata('usertype') == 'homeowner'){
            if(!empty($contracts)){
                if($contracts['homeowneragree'] == '1'){
                    if($contracts['bt_transaction_id'] == ''){
                        $buttonstatus = "cancel";
                    }
                    $contractkey = $contracts['contractkey'];
                    $url = base_url('contractagree/homeowner/'.$contractkey);
                    $showcontractbutton = '1';
                }
            }
        }else{
            if(!empty($contracts)){
                if($contracts['homeowneragree'] == '1'){
                    if($contracts['workeragree'] == '0'){
                        $buttonstatus = "agree";
                    }
                    $contractkey = $contracts['contractkey'];
                    $url = base_url('contractagree/homeowner/'.$contractkey);
                    $showcontractbutton = '1';
                }
            }

        }
        $data['showcontractbutton'] = $showcontractbutton;
        $showaddress = (!empty($contracts) and $contracts['bt_transaction_id'] != '') ? '1' : '0';
        if($this->session->userdata('usertype') == 'homeowner'){
            $showaddress = '1';
        }

        $showcontractaddress = (!empty($contracts) and $contracts['bt_transaction_id'] != "") ? '1' : '0';
        $data['showcontractaddress'] = $showcontractaddress;
        $data['showaddress'] = $showaddress;

        $data['contractkey'] = $contractkey;
        $data['contracturl'] = $url;
        $data['buttonstatus'] = $buttonstatus;

        $return["data"]        = $data;
        $return["status"]      = 1;
        $return["successMsg"]  = 'Job Details.';
        $this->returnJson($return);
    }

    private function acceptcompletion(){
        $contract = $this->Job_model->getContract($this->values['jobid'],$this->values['contractoruserid']);
        if(!empty($contract)){
            if($contract['escrow_released'] == '0'){
                $result = Braintree_Transaction::releaseFromEscrow($contract['bt_transaction_id']);
                $bresponse = json_encode($result);
                $this->Job_model->create_braintree_response($contract['ownerid'],$contract['contractorid'],$contract['jobid'],$result,'release');
                $binput['type'] = 'release from api';
                $binput['date'] = date('m/d/Y H:i:s A');
                $binput['contract'] = $contract;
                $binput['bt_result'] = $result;
                $this->Job_model-> braintreeresponse($binput);
                if($result->success){
                    $this->Job_model->updateContractorfields('escrow_released',$contract['contractkey']);
                    $this->Job_model->updateJobfields('jobstatus','verified',$this->values['jobid']);
                    $this->Job_model->create_rating($this->values['star'],$this->session->userdata('userId'),$this->values['contractoruserid'],$this->values['jobid']);
                    $this->Job_model->create_notifications($contract['ownerid'],$contract['contractorid'],$contract['jobid'],'payment',$contract['contractid']);
                    $this->Job_model->create_notifications($contract['ownerid'],$contract['ownerid'],$contract['jobid'],'payconfirm',$contract['contractid']);
                    $return["status"]      = 1;
                    $return["successMsg"]  = 'Accept completion.';
                    $this->returnJson($return);
                }else{
                    $this->returnError("Could not release payment.");
                }



            }else{
                $this->returnError("Payment already released.");
            }

        }else{
            $this->returnError("Invalid.");
        }

    }

    private function accountbalance(){
        if($this->session->userdata('usertype') == 'homeowner'){
            $contracts = $this->Job_model->getContractforhomeowners($this->session->userdata('userId'));
        }else{
            $contracts = $this->Job_model->getContractforcontractors($this->session->userdata('userId'));
        }
        $userids = $userdets = $jobids = $jobs = $users =  array();
        if(!empty($contracts)){
            foreach($contracts as $ck=>$cont){
                if($this->session->userdata('usertype') == 'homeowner'){
                    $userids[] = $cont['contractorid'];
                    $contracts[$ck]['userid'] = $cont['contractorid'];
                }else{
                    $userids[] = $cont['ownerid'];
                    $contracts[$ck]['userid'] = $cont['ownerid'];
                }
                $jobids[] = $cont['jobid'];

            }
        }
        if(!empty($jobids)){
            $jobs = $this->Job_model->getjobsbyids($jobids);
            $jobs = $this->corefunctions->getArrayIndexed($jobs,'jobid');
        }

        if(!empty($userids)){
            $userdets =  $this->Api_model->getuserdetsbyids(array_unique($userids)) ;
            if(!empty($userdets)){
                foreach($userdets as $uk=>$user){
                    $users[$user['userid']]['imageurl'] = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
                    $users[$user['userid']]['staring'] = $this->userrating($user['userid']);;
                    $users[$user['userid']]['firstname'] = $user['firstname'];
                    $users[$user['userid']]['lastname'] = $user['lastname'][0];
                }
            }
        }
        $pending = $recieved = array();
        $pendingtotal = $total = 0;

        if(!empty($contracts) and !empty($jobs) and !empty($users)){
            foreach($contracts as $ck=>$cont){
                if(isset($jobs[$cont['jobid']]['jobname']) and isset($users[$cont['userid']]['lastname'])){
                    if($cont['escrow_released'] == '1'){
                        $recieved[$ck]['amount'] = $cont['amount'];
                        $recieved[$ck]['jobname'] = $jobs[$cont['jobid']]['jobname'];
                        $recieved[$ck]['firstname'] = $users[$cont['userid']]['firstname'];
                        $recieved[$ck]['lastname'] = $users[$cont['userid']]['lastname'][0];
                        $recieved[$ck]['imgurl'] = $users[$cont['userid']]['imageurl'];
                        $recieved[$ck]['contractkey'] = $cont['contractkey'];
                        $total += floatval($cont['amount']);
                    }else{
                        $pending[$ck]['amount'] = $cont['amount'];
                        $pending[$ck]['jobname'] = $jobs[$cont['jobid']]['jobname'];
                        $pending[$ck]['firstname'] = $users[$cont['userid']]['firstname'];
                        $pending[$ck]['lastname'] = $users[$cont['userid']]['lastname'][0];
                        $pending[$ck]['imgurl'] = $users[$cont['userid']]['imageurl'];
                        $pendingtotal += floatval($cont['amount']);
                        $pending[$ck]['contractkey'] = $cont['contractkey'];
                    }
                }

            }
        }

        $notcount = $this->notificationcount();
        $data['countdetails'] = array('notification'=>$notcount);
        $pending  = $this->finalArray($pending);
        $recieved  = $this->finalArray($recieved);
        $data['total'] = (string)$total;
        $data['pendingtotal'] = (string)$pendingtotal;
        $data['pending'] = $pending;
        $data['recieved'] = $recieved;
        $return['data'] = $data;
        $return["status"]      = 1;
        $return["successMsg"]  = 'Accountbalance.';
        $this->returnJson($return);
    }

    private function sendmessage(){
        $hasdoc = ($this->values['isfile'] == '1') ? '1' : '0';
        $messageid = $this->Job_model->create_message($this->values['message'],$this->session->userdata('userId'),$this->values['userid'],$this->values['jobid'],$hasdoc);
        $this->Job_model->create_notifications($this->session->userdata('userId'),$this->values['userid'],$this->values['jobid'],'message',$messageid);

        $job = $this->Job_model->getjobbyid($this->values['jobid']);
        //if($job['createdby'] != $this->session->userdata('userId')){
        $this->Job_model->create_history($this->session->userdata('userId'),5,$this->values['jobid'],$job['createdby']);
        //}


        if($this->values['isfile'] == '1'){
            $inputArr['url'] = $this->values['url'];
            $inputArr['filename'] = 'message.jpg';
            $this->uploadDocs('message',$messageid,$inputArr);
        }
        $user = $this->Api_model->user_by_key($this->session->userdata('userKey'));
        $profileimageurl = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');;

        $fileurl = '';
        if($this->values['isfile'] == '1'){
            /*$docs = $this->Job_model->getdocs($messageid,'message');
            $jDocs = array();
            if(!empty($docs)){
              foreach($docs as $dk=>$d){
                $fileurl = base_url($this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download'));
              }
            } */

            $docs = $this->Job_model->getBidImage('message',$messageid);
            if(!empty($docs)){
                $fileurl = base_url($this->corefunctions->getMyPath($docs['docid'],$docs['dockey'],$docs['docext'],'assets/docs/','download'));

            }
        }
        $data['fileurl'] = $fileurl;
        $data['profileimageurl'] = $profileimageurl;
        $data['firstname'] = $user['firstname'];
        $data['lastname'] = $user['lastname'][0];
        $data['posteddate'] = date('m/d/Y');
        $data['message'] = $this->values['message'];
        $data['messageid'] = $messageid;
        $data['userid'] = $user['userid'];
        $data['isfile'] = $this->values['isfile'];

        $return["status"]      = 1;
        $return["successMsg"]  = 'Message sent.';
        $this->returnJson($return);
    }

    private function Messagecount(){
        $messages = $this->Job_model->getmessageCount($this->session->userdata('userId'));
        return (isset($messages['total'])) ? (string)$messages['total'] : '0';
    }

    private function joblistmessages (){
        $messages = $this->Job_model->getusermessages($this->session->userdata('userId'));
        $jobids = $jobs = array();
        if(!empty($messages)){
            foreach($messages as $mes){
                $jobids[] = $mes['jobid'];
            }
        }
        $returnJobs = $jbMessages = array();
        if(!empty($jobids)){
            $jobs = $this->Job_model->getjobsbyids(array_unique($jobids));
            if(!empty($jobs)){
                foreach( $jobs as $jk=>$jb){
                    //$docs = $this->Job_model->getdocs($jb['jobid'],'job');
                    $fileurl = base_url('images/def_job.jpg');
                    $docs = $this->Job_model->getBidImage('job',$jb['jobid']);
                    if(!empty($docs)){
                        $fileurl = base_url($this->corefunctions->getMyPath($docs['docid'],$docs['dockey'],$docs['docext'],'assets/docs/','download'));

                    }
                    $returnJobs[$jb['jobid']]['jobid']  = $jb['jobid'];
                    $returnJobs[$jb['jobid']]['tittle']  = $jb['jobname'];
                    $returnJobs[$jb['jobid']]['imageurl']  = $fileurl;

                    foreach($messages as $mes){
                        if($mes['touserid'] == $this->session->userdata('userId') and $mes['readmessage'] == 0 and $mes['jobid'] == $jb['jobid']){
                            $jbMessages[$jb['jobid']]['count'][] = $mes;
                        }
                        if(!isset($jbMessages[$mes['jobid']]['messagetime']) or $jbMessages[$mes['jobid']]['messagetime'] == ""){
                            $jbMessages[$mes['jobid']]['messagetime'] = $mes['createdate'];
                        }
                    }
                }
            }
        }
        $ret = array();
        if(!empty($returnJobs)){
            foreach($returnJobs as $rk=>$re){
                $returnJobs[$rk]['msgcount'] = (isset($jbMessages[$rk]['count'])) ? (string)count($jbMessages[$rk]['count']) : '0' ;
                $returnJobs[$rk]['msgtime'] = (isset($jbMessages[$rk]['messagetime'])) ? $jbMessages[$rk]['messagetime'] : '0' ;
            }
            $returnJobs = $this->hedercontroller->array_sort_by_column($returnJobs, 'msgtime');

        }
        $data['jobs'] = $this->finalArray($returnJobs);
        $notcount = $this->notificationcount();
        $data['countdetails'] = array('notification'=>$notcount);

        $return["data"]        = $data;
        $return["status"]      = 1;
        $return["successMsg"]  = 'Message list.';
        $this->returnJson($return);

    }

    private function jobmessages(){
        $messages = $this->Job_model->getuserJobmessages($this->session->userdata('userId'),$this->values['jobid']);

        $userids = $users = $userdets = $lmArray = array();
        if(!empty($messages)){
            foreach($messages as $mes){
                if($this->session->userdata('userId') != $mes['fromuserid']){
                    $userids[] = $mes['fromuserid'];
                }else{
                    $userids[] = $mes['touserid'];
                }

            }
        }

        if(!empty($userids)){
            $userdets =  $this->Api_model->getuserdetsbyids(array_unique($userids)) ;
            if(!empty($userdets)){
                foreach($userdets as $uk=>$user){
                    $users[$user['userid']]['userimage'] = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
                    $users[$user['userid']]['staring'] = $this->userrating($user['userid']);
                    $users[$user['userid']]['firstname'] = $user['firstname'];
                    $users[$user['userid']]['lastname'] = $user['lastname'][0];
                    if($this->session->userdata('usertype') == 'homeowner'){
                        $users[$user['userid']]['contractoruserid'] = $user['userid'];
                    }else{
                        $users[$user['userid']]['homeowneruserid'] = $user['userid'];
                    }
                    $jbMessages[$user['userid']] = array();
                }
            }

            foreach($messages as $mes){
                if($mes['touserid'] == $this->session->userdata('userId') and $mes['readmessage'] == 0){
                    $jbMessages[$mes['fromuserid']][] = $mes;
                }
                if($mes['touserid'] == $this->session->userdata('userId')){
                    if(!isset($lmArray[$mes['fromuserid']]['message'])){
                        $lmArray[$mes['fromuserid']]['message'] = $mes['message'];
                    }
                    if(!isset($lmArray[$mes['fromuserid']]['messagetime']) or $lmArray[$mes['fromuserid']]['messagetime'] == ""){
                        $lmArray[$mes['fromuserid']]['messagetime'] = $mes['createdate'];
                    }
                }else{
                    if(!isset($lmArray[$mes['touserid']]['message'])){
                        $lmArray[$mes['touserid']]['message'] = $mes['message'];
                    }
                    if(!isset($lmArray[$mes['touserid']]['messagetime']) or $lmArray[$mes['touserid']]['messagetime'] == ""){
                        $lmArray[$mes['touserid']]['messagetime'] = $mes['createdate'];
                    }
                }
            }
            foreach($userdets as $uk=>$user){
                $users[$user['userid']]['lastmessage'] = (isset($lmArray[$user['userid']]['message'])) ? $lmArray[$user['userid']]['message'] : '' ;
                $users[$user['userid']]['msgcount'] = (isset($jbMessages[$user['userid']])) ? (string)count($jbMessages[$user['userid']]) : '0' ;
                $users[$user['userid']]['messagetime'] = (isset($lmArray[$user['userid']]['messagetime'])) ? $lmArray[$user['userid']]['messagetime'] : '' ;
            }
            $users = $this->hedercontroller->array_sort_by_column($users, 'messagetime');

        }


        $data['users'] = $this->finalArray($users);
        $notcount = $this->notificationcount();
        $data['countdetails'] = array('notification'=>$notcount);

        $return["data"]        = $data;
        $return["status"]      = 1;
        $return["successMsg"]  = 'Message list.';
        $this->returnJson($return);
    }

    private function messageslist(){
        $userid = ( $this->session->userdata('usertype') == 'homeowner' ) ? $this->values['contractoruserid'] : $this->values['homeowneruserid'] ;
        $lastmessageid = $this->values['lastloadedmsgid'];

        $messages = $this->Job_model->getmessages($this->session->userdata('userId'),$userid,$this->values['jobid'],$lastmessageid);

        $userids = $users = $userdets = $lmArray = array();
        $userids[] = $this->session->userdata('userId');
        if(!empty($messages)){
            foreach($messages as $mes){
                if($this->session->userdata('userId') != $mes['fromuserid']){
                    $userids[] = $mes['fromuserid'];
                }else{
                    $userids[] = $mes['touserid'];
                }
            }
        }
        if(!empty($userids)){
            $userdets =  $this->Api_model->getuserdetsbyids(array_unique($userids)) ;
            if(!empty($userdets)){
                foreach($userdets as $uk=>$user){
                    $users[$user['userid']]['profileimageurl'] = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
                    $users[$user['userid']]['staring'] = $this->userrating($user['userid']);
                    $users[$user['userid']]['firstname'] = $user['firstname'];
                    $users[$user['userid']]['lastname'] = $user['lastname'][0];
                    if($user['userid'] == $this->session->userdata('userId')){
                        $users[$user['userid']]['iscurrentuser'] = '1';
                    }else{
                        $users[$user['userid']]['iscurrentuser'] = '0';
                    }
                    $users[$user['userid']]['userid'] = $user['userid'];

                }
            }
        }
        $this->Job_model->updatereadmessages($this->values['jobid'],$this->session->userdata('userId'));

        $returnMessage = array();
        if(!empty($messages)){
            foreach($messages as $mk=>$mes){
                $returnMessage[$mk]['message'] = $mes['message'];
                $returnMessage[$mk]['posteddate'] = date('m/d/Y',$mes['createdate']);

                $fileurl = '';
                if($mes['hasdoc'] == '1'){
                    $docs = $this->Job_model->getBidImage('message',$mes['messageid']);
                    if(!empty($docs)){
                        $fileurl = base_url($this->corefunctions->getMyPath($docs['docid'],$docs['dockey'],$docs['docext'],'assets/docs/','download'));

                    }
                }

                $returnMessage[$mk]['isfile'] = ($fileurl == '') ? '1' : '0' ;
                $returnMessage[$mk]['imageurl'] = $fileurl;
                $returnMessage[$mk]['messageid'] = $mes['messageid'];
                $returnMessage[$mk]['firstname'] = $users[$mes['fromuserid']]['firstname'];
                $returnMessage[$mk]['lastname'] = $users[$mes['fromuserid']]['lastname'][0];
                $returnMessage[$mk]['profileimageurl'] = $users[$mes['fromuserid']]['profileimageurl'];
                $returnMessage[$mk]['iscurrentuser'] = $users[$mes['fromuserid']]['iscurrentuser'];
                $returnMessage[$mk]['userid'] = $users[$mes['fromuserid']]['userid'];
            }
        }


        $returnMessage = array_reverse($returnMessage);
        $job = $this->Job_model->getjobbyid($this->values['jobid']);
        $data['jobname'] = $job['jobname'];
        $data['messages'] = $returnMessage;
        $notcount = $this->notificationcount();

        $data['countdetails'] = array('notification'=>$notcount);

        $return["data"]        = $data;
        $return["status"]      = 1;
        $return["successMsg"]  = 'Message list.';
        $this->returnJson($return);
    }


    private function notificationcount(){
        $notifications = $this->Job_model->getnotification($this->session->userdata('userId'));
        $jobids = $jobs = array();
        $notcount = 0;
        if(!empty($notifications)){
            foreach($notifications as $not){
                $jobids[] = $not['jobid'];
                if($not['isread'] == 0){
                    $notcount++;
                }
            }
        }

        return (string)$notcount;

    }






    private function notification(){
        $notifications = $this->Job_model->getnotification($this->session->userdata('userId'));
        $jobids = $jobs =  $userids = $users =array();
        $notcount = 0;
        if(!empty($notifications)){
            foreach($notifications as $not){
                $jobids[] = $not['jobid'];
                $userids[]= $not['touserid'];
                $userids[]= $not['fromuserid'];
                if($not['isread'] == 0){
                    $notcount++;
                }
            }
        }

        if(!empty($userids)){
            $users =  $this->Api_model->getuserdetsbyids(array_unique($userids)) ;
            $users = $this->corefunctions->getArrayIndexed($users,'userid');
        }

        if(!empty($jobids)){
            $jobs = $this->Job_model->getjobsbyids(array_unique($jobids));
            if(!empty($jobs)){
                foreach( $jobs as $jk=>$jb){
                    $fileurl = base_url('images/def_job.jpg');
                    $docs = $this->Job_model->getBidImage('job',$jb['jobid']);
                    if(!empty($docs)){
                        $fileurl = base_url($this->corefunctions->getMyPath($docs['docid'],$docs['dockey'],$docs['docext'],'assets/docs/','download'));

                    }
                    $jobs[$jk]['image'] = $fileurl;
                }

                $jobs = $this->corefunctions->getArrayIndexed($jobs,'jobid');
            }
        }

        $notMsg = array('payment'=>'You have received a payment on the job','bid'=>'You have received a bid on the job','jstart'=>'Job Started','jcomplete'=>'Job completed','message'=>'New Message','contract'=>'Your Bid has been Approved.');
        $retNot = array();
        if(!empty($notifications)){
            foreach($notifications as $nk=>$not){
                if(isset($jobs[$not['jobid']]['jobid'])){
                    $retNot[$nk]['type'] = $not['parenttype'];
                    //$retNot[$nk]['notificationmessage'] = $notMsg[$not['parenttype']];
                    $retNot[$nk]['userid'] = $not['fromuserid'];
                    $retNot[$nk]['notificationmessage'] = $this->hedercontroller->getnotificationtext($not['parenttype'],$jobs[$not['jobid']],$users[$not['fromuserid']],$not);
                    $retNot[$nk]['jobid'] = $jobs[$not['jobid']]['jobid'];
                    $retNot[$nk]['jobname'] = $jobs[$not['jobid']]['jobname'];
                    $retNot[$nk]['image'] = $jobs[$not['jobid']]['image'];
                    $retNot[$nk]['showtype'] = ($not['parenttype'] == 'payment' or $not['parenttype'] == 'payconfirm') ? 'payments' : 'bids' ;

                    if(( $this->session->userdata('usertype') == 'homeowner' )){
                        $retNot[$nk]['jobstatus'] = ($jobs[$not['jobid']]['jobstatus'] == 'verified') ? 'verified' : 'notverified';
                    }else{
                        $retNot[$nk]['jobstatus'] = ($jobs[$not['jobid']]['jobstatus'] == 'verified' or $jobs[$not['jobid']]['jobstatus'] == 'completed') ? 'completed' : 'working';
                    }


                }

            }
        }
        $data['payments'] = $data['bids'] = $data['jobs'] = array();
        if(!empty($retNot)){
            foreach($retNot as $rk=>$rv){
                if($rv['type'] == 'payment' or $rv['type'] == 'payconfirm'){
                    $data['payments'][] = $rv;
                    //}else if($rv['type'] == 'bid'){
                    // $data['bids'][] = $rv;
                }else{
                    $data['bids'][] = $rv;
                }
            }
        }
        $this->Job_model->updateread($this->session->userdata('userId'));
        $data['countdetails'] = array('notification'=>$notcount);
        $return["data"]        = $data;
        $return["status"]      = 1;
        $return["successMsg"]  = 'Notification list.';
        $this->returnJson($return);
    }

    private function deletejobimage(){
        $images = $this->values["images"];
        if(isset($this->values["imageid"])){
            //foreach($images as $im){
            $this->Job_model->deleteDoc($this->values["imageid"]);
            //}
        }
        $return["data"]['jobid'] = $this->values["jobid"];
        $return["status"]      = 1;
        $return["successMsg"]  = 'Job image deleted.';
        $this->returnJson($return);
    }

    private function userrating($userid){
        $userrating = $this->Job_model->getcontractorrating($userid);
        $rtcount = count($userrating);
        $totalrating = 0;
        if(!empty($userrating)){
            foreach($userrating as $rt){
                $totalrating += $rt['rating'];
            }
        }
        $rating = ($rtcount > 0) ? $totalrating/$rtcount : 0;
        $rating = floor($rating);
        return $rating;
    }


    public function jobcontract($type,$contractkey){
        $contractDets = $this -> Job_model -> getContractbykey( $contractkey );
        $jobDets = $this -> Job_model -> getjobbyid( $contractDets['jobid'] );
        $userDets = $this -> User_model -> getuserdetsbyid( $contractDets['contractorid'] );
        $userDets['lastname'] =$userDets['lastname'][0];
        $ownerDets = $this -> User_model -> getuserdetsbyid( $contractDets['ownerid'] );
        $ownerDets['lastname'] =$ownerDets['lastname'][0];
        $bid = $this->Job_model->getmybid($contractDets['jobid'],$contractDets['contractorid']);
        //if($userDets['bt_merchantid'] != ""){
        $data['userDets'] = $userDets;
        $data['ownerDets'] = $ownerDets;
        $data['jobDets'] = $jobDets;
        $data['bid'] = $bid;
        $data['contractDets'] = $contractDets;
        $this->load->view('apipayment/homecontract',$data);
        /* }else{
           $data['headerdata'] = $this->hedercontroller->headerCounts();
           $data['from'] = 'api';
           $this->load->view('apipayment/contractfail',$data);
         } */
    }

    private function pushNotification() {
        if (!$this->session->userdata('userId')) {
            $this->returnError("Sorry Invalid Request");
        }
        if (!$this->values['devicetoken'] or !$this->values['tokenstatus'] or $this->values['devicetoken'] == "" or $this->values['tokenstatus'] == "" ) {
            $this->returnError("Fields Missing");
        }
        $device = $this -> Api_model -> getdevicedets($this->session->userdata('deviceid'));
        $this->values['devicekey'] = $device['devicekey'];
        $pushid = $this->Api_model->addpushnotification($this->values);
        $return["updatedate"] = time();
        $return["status"]     = 1;
        $return["successMsg"] = 'Success';
        $this->returnJson($return);
    }
    private function contractInfo(){
        $bidDets = $this->Job_model->getmybid($this->values['jobid'],$this->values['contractorid']);
        $userDetails = $this->Api_model->getUserDetailsDetailsByObjectID($bidDets['userid']);
        $mybid = $bidDets;
        //$data['description'] = $bidDets['description'];
        $typearr = array('h'=>'Hour(s)','w'=>'Week(s)','d'=>'Day(s)');
        /*$data['amount'] = number_format($bidDets['bidamount'],2);
        $data['startdate'] = ($bidDets['startdate'] != '0000-00-00') ? date('m/d/Y',strtotime($bidDets['startdate'])) : '--';
        $data['addamount'] = ($bidDets['additionalamount'] != "") ? number_format($bidDets['additionalamount'],2) : '';
        $data['username'] = $userDetails['firstname']." ".$userDetails['lastname'];
        $data['expectedtime'] = ($bidDets['exptime'] != '' and $bidDets['exptime'] != '0') ?  $bidDets['exptime']." ".$typearr[$bidDets['exptype']] : '--';
        $data['maximumtime'] = ($bidDets['maxtime'] != '' and $bidDets['maxtime'] != '0') ?  $bidDets['maxtime']." ".$typearr[$bidDets['maxtype']] : '--';
        $data['image'] = ($userDetails['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($userDetails['userid'], $userDetails['imagekey'], $userDetails['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
        */

        $data['description'] = (string)$mybid['description'];
        $data['bidamount'] = (string)(round($mybid['bidamount'],2));
        $data['additionalamount'] = ($mybid['additionalamount'] != "0") ? (string)$mybid['additionalamount'] : "";
        $data['expectedcompletetime'] = ($mybid['exptime'] != "0") ? (string)$mybid['exptime'] : "";
        $data['expectedtimetype'] = ($mybid['exptime'] != "0") ? (string)$typearr[$mybid['exptype']] : "";
        $data['jobid'] = (string)$mybid['jobid'];
        $data['maxcompletetime'] = ($mybid['maxtime'] != "0") ? (string)$mybid['maxtime'] : "";
        $data['maxtimetype'] = ($mybid['maxtime'] != "0") ? (string)$typearr[$mybid['maxtype']] : "";
        $data['startdate'] = ($mybid['startdate'] != '0000-00-00') ? date('m/d/Y',strtotime($mybid['startdate'])) : "";
        $data['starttime'] = ($mybid['starttime'] != '00:00:00') ? date('g.i A',strtotime($mybid['starttime'])) : '';
        $data['username'] = $userDetails['firstname']." ".$userDetails['lastname'][0];

        $data['expectedtime'] = ($mybid['exptime'] != '' and $mybid['exptime'] != '0') ?  $mybid['exptime']." ".$typearr[$mybid['exptype']] : '';
        $data['maximumtime'] = ($mybid['maxtime'] != '' and $mybid['maxtime'] != '0') ?  $mybid['maxtime']." ".$typearr[$mybid['maxtype']] : '';
        $data['image'] = ($userDetails['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($userDetails['userid'], $userDetails['imagekey'], $userDetails['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');


        $return["data"]     = $data;
        $return["status"]     = 1;
        $return["successMsg"] = 'ContractInfo';
        $this->returnJson($return);

    }

    private function cancelcontract(){
        $contractkey = $this->values['contractkey'];
        $contract = $this->Job_model->getContractbykey($contractkey);
        $this -> Job_model -> cancelcontract( $contractkey );
        $this -> Job_model ->editBid(array('status'=>'-1'),$contract['jobid'],$contract['contractorid']) ;
        $this -> Job_model ->updateJobfields('jobstatus','new',$contract['jobid']);
        //print "<pre>"; print_r($contract); print "</pre>";
        if($contract['bt_transaction_id'] != ''){
            $result = Braintree_Transaction::refund($contract['bt_transaction_id']);
            $this->Job_model->create_braintree_response($contract['ownerid'],$contract['contractorid'],$contract['jobid'],$result,'cancel');
            $binput['type'] = 'cancel from api';
            $binput['date'] = date('m/d/Y H:i:s A');
            $binput['contract'] = $contract;
            $binput['bt_result'] = $result;
            $this->Job_model-> braintreeresponse($binput);
            //print "<pre>"; print_r($result); print "</pre>";

        }

        $return["status"]     = 1;
        $return["successMsg"] = 'Contract cancelled';
        $this->returnJson($return);
    }

    private function checkexstingemail(){
        $checkEmail      = $this->User_model->check_useremail_exists( $this->values[ 'email' ] );

        if ( empty($checkEmail ) ){
            $return["status"]     = 1;
            $return["successMsg"] = 'Email good to register.';
            $this->returnJson($return);
        } else {
            $this->returnError("Email already exists.");
        }
    }

    private function paymentdetails(){
        $contract = $this->Job_model->getContractbykey( $this->values[ 'contractkey' ] );
        $paymentdetails =  $this->Job_model->getpaymentcontractid($contract['contractid']);
        $homeOwnerInfo = $this->Api_model->getUserDetailsDetailsByObjectID($contract['ownerid']);
        $contractor = $this->Api_model->getUserDetailsDetailsByObjectID($contract['contractorid']);
        $job = $this->Job_model->getjobbyid($contract['jobid']);
        /*if(empty($contract) or empty($paymentdetails) or empty($homeOwnerInfo) or empty($contractor) or empty($job)){
          $this->returnError("No records found.");
        }*/
        $data['jobcompleteddate'] = date("m/d/Y",$contract['completeddate']);
        $data['jobname'] = $job['jobname'];
        $data['amount'] = (string)number_format($contract['amount'],2);
        $data['servicefee'] = (string)$contract['servicefeepercent'];
        $data['paymentdate'] = (!empty($paymentdetails) and $paymentdetails['createdate'] != '0') ? date("m/d/Y",$paymentdetails['createdate']) : '';
        $data['cardtype'] = (!empty($paymentdetails) and $paymentdetails['cardtype'] != '') ? $paymentdetails['cardtype']: '';
        $data['cardnumber'] = (!empty($paymentdetails) and $paymentdetails['cardnumber'] != '') ? "****".$paymentdetails['cardnumber']: '';
        $data['contractorname'] = $contractor['firstname'] ." ".$contractor['lastname'][0];
        $data['contractoraddress'] = $contractor['address'];
        $data['contractorcity'] = $contractor['city'];
        $data['contractorstate'] = $contractor['state'];
        $data['contractorzip'] = $contractor['zip'];
        $data['contractorphone'] = $contractor['phone'];
        $serviceamount = $contract['amount'] - $contract['servicefee'];
        $data['paymentamount'] = (string)$serviceamount;
        $data['serviceamount'] = (string)$contract['servicefee'];

        $return["data"]     = $data;
        $return["status"]     = 1;
        $return["successMsg"] = 'Payment details.';
        $this->returnJson($return);
    }

    private function inserttotables(){
        $str = file_get_contents('http://demo.icwares.com/clients/dev/efynch/599/vx/cities.json');
        $json = json_decode($str, true);

        $statestr = file_get_contents('http://demo.icwares.com/clients/dev/efynch/599/vx/states.json');
        $statejson = json_decode($statestr, true);

        $city = $sta = array();
        if(!empty($statejson)){
            foreach($statejson as $js){
                $data = array(
                    'state_prefix' => $js['state_code'],
                    'state_name' => $js['state']
                );
                //$this->db->insert('states', $data);
                //$insert_id = $this->db->insert_id();
            }
        }
        if(!empty($json)){
            foreach($json as $jsn){
                $data = array(
                    'city' => $jsn['city'],
                    'state_code' => $jsn['state_code']
                );
                //$this->db->insert('city', $data);
                //$insert_id = $this->db->insert_id();
            }
        }


        // print '<pre>' ; print_r($sta) ; print_r($statejson) ; print count($city);  print_r($city); print '</pre>';
    }

    private function get_rating(){
           
        if (empty($this->values['jobkey'])){
            $this->returnError("Invalid Job Provided.");
        }
        if (empty($this->values['contractorkey'])){
            $this->returnError("Invalid Contractor Provided.");
        }
        $type = "public";
        if ( !empty($this->values['type']) && ($this->values['type'] == "private" || $this->values['type'] == "public") ){
            $type = $this->values['type'];
        }

        $job_key = $this->values['jobkey'];

        $contractor_key = $this->values['contractorkey'];

        $jobDetails = $this->Job_model->getCompletedJob($job_key, 'verified');
        if (empty($jobDetails)){
            $this->returnError("Invalid Job Provided.");
        }

        $contractorInfo = $this->Job_model->getContractor($contractor_key );
        if (empty($contractorInfo)){
            $this->returnError("Invalid Contractor Provided.");
        }

        $ownerId = $jobDetails['createdby'];
        $contractInfo = $this->Job_model->getJobContract($ownerId,$contractorInfo['userid'], $jobDetails['jobid']);
        if(empty($contractInfo)){
            $this->returnError("Invalid Contractor Provided.");
        }
        
        if($type == 'private'){
            $rating_data['data'] = $this->Job_model->getPrivateRating('',$ownerId, $contractorInfo['userid'],$jobDetails['jobid']);
            $rating_data['successMsg'] = 'Private Jobs Rating';
        }else{
            $rating_data['data'] = $this->Job_model->getRating($ownerId, $contractorInfo['userid'],$jobDetails['jobid']);
            $rating_data['successMsg'] = 'Public Jobs Rating';
        }
        $rating_data["status"] = '1';
        $this->returnJson($rating_data);
    }
    
    private function get_completed_jobs(){
        if (empty($this->values['user_id'])){
            $this->returnError("Invalid User Provided.");
        }
        $data['data'] = $this->Job_model->getContractorJobs($this->values['user_id']);
        $data['status'] = 1;
        $data['successMsg'] = "Completed Jobs";

        $this->returnJson($data);
    }

    private function save_rating(){

        $errorMsg = array();
        if (empty($this->values['jobkey'])){
            $errorMsg = 'Invalid Job Provided.';
            $this->returnError($errorMsg);
        }
        if (empty($this->values['contractorkey'])){
            $errorMsg = 'Invalid Contractor Provided.';
            $this->returnError($errorMsg);
        }
        if (empty($this->values['user_id'])){
            $errorMsg = 'Invalid User Provided.';
            $this->returnError($errorMsg);
        }
        
        $job_id = $this->values['jobkey'];

        $contractor_key = $this->values['contractorkey'];
        $ownerId = $this->values['user_id'];

        $jobDetails = $this->Job_model->getCompletedJobById($job_id);
        //$jobDetails = $this->Job_model->getCompletedJob($job_key);
        if (empty($jobDetails)){
            $errorMsg = 'Job is already marked as verified.';
            $this->returnError($errorMsg);
        }

        $contractorInfo = $this->Job_model->getContractor($contractor_key );
        if (empty($contractorInfo)){
            $errorMsg = 'Invalid Contractor Provided.';
            $this->returnError($errorMsg);
        }
        $contractInfo = $this->Job_model->getJobContract($ownerId,$contractorInfo['userid'], $jobDetails['jobid']);
        if(empty($contractInfo)){
            $errorMsg = 'Invalid Contract Provided.';
            $this->returnError($errorMsg);
        }

        $publicRatingData = $this->Job_model->getRating($ownerId, $contractorInfo['userid'],$jobDetails['jobid']);
        $type = "public";
        if ( !empty($this->values['type']) && $this->values['type'] == "private" ){
            $type = $this->values['type'];
        }

        if( $type == 'public' ){
            if (empty($this->values['rating_comment'])){
                $errorMsg = 'Comment is required.';
                $this->returnError($errorMsg);
            }
            if ( !empty($publicRatingData) ){

                $data["status"] = "1";
                $data["successMsg"] = "Public rating already added.";
                $this->returnJson($data);
            }

            $rateID = $this->Job_model->savePublicRating($ownerId, $contractorInfo['userid'],$jobDetails['jobid'], $this->values);
            if($rateID == false){
                $this->returnError("Unable to save, try later.");
            }else{
                $this->Job_model->markJobVerified($jobDetails['jobid']);

                /* For sending email and push notification*/
                $mailObj['userkey'] = $contractorInfo['userkey'];
                $mailObj['firstname'] = $contractorInfo['firstname'];
                $mailObj['lastname'] = $contractorInfo['lastname'];
                $mailObj['jobkey'] = $jobDetails['jobkey'];
                $mailObj['jobname'] = $jobDetails['jobname'];

                $msg   = $this->load->view('mail/job-completed', $mailObj, true);
                $this->corefunctions->sendmail(ADMINEMAIL, $contractorInfo['email'], TITLE . ' :: Job Completed', $msg);

                $message = "Congratulations! Your Job is marked as completed";
                $params['jobid'] = $jobDetails['jobid'];
                $params['jobtype'] = "jobcompleted";
                $this->hedercontroller->sendOnlyNotification($contractorInfo['userid'], $message,$params);

                $data["status"] = "1";
                $data["successMsg"] = "Public rating saved successfully.";
                $this->returnJson($data);
            }
        }else if ( $type == 'private'){
            
            if ( empty($publicRatingData) ){
                $this->returnError("Public rating must be saved first.");
            }
            $privateRating = $this->Job_model->getPrivateRating($publicRatingData['ratingid'],$ownerId, $contractorInfo['userid'],$jobDetails['jobid'] );
            
            if(!empty($privateRating)){
                $data["status"] = "1";
                $data["successMsg"] = "Private rating already added.";
                $this->returnJson($data);
            }
            $privateRatingId = $this->Job_model->savePrivateRating($publicRatingData['ratingid'],$ownerId, $contractorInfo['userid'],$jobDetails['jobid'], $this->values);

            if ($privateRatingId){
                $data["status"] = "1";
                $data["successMsg"] = "Private rating saved successfully.";
                $this->returnJson($data);
            }else{
                $this->returnJson("Unable to save, try later.");
            }
        }
        exit;
    }

    private function fakeRequest() {
        //$this->session->set_userdata(array('userKey'=>'295172'));
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $fakeRequest               = array(
            "apifunction" => "register",
            "parameters" => array(
                "address" => "123 street",
                "city" => "Baltimore",

                "firstname" => "Amrutha",
                "image" => "",
                "lastname" => "S",
                "phone" => "123",
                "state" => "MD",
                "usertype" => "homeowner",
                "zip" => "123456",

                "email" => "test35@consult-ic.com",
                "verificationcode"=> "910832",
                "password" => "123456",
                "enckey" => "a30380a26e6c552f46e1cc52a56bb3ba",
                "expertise"=>array('appliance','electrical','flooring'),
                'iscompany' => 1,
                "licenseandbankdetails"=>array(
                    "license"=>"432432",
                    "insurance"=>"2542545",
                    "routingnumber"=>"2",
                    "accountnumber"=>"4343254545454242545"
                ),
                "companydetails"=>array(
                    "companyname"=>"ic",
                    "taxid"=>"2542545",
                    "companyaddress"=>"123 street",
                    "companystate"=>"md",
                    "companycity"=>"baltimore",
                    "companyzip"=> "123456"
                )
            ),
            "apiInfo" => array(
                "apiKey" => "efynch&^%$$#@4567",
                "appVersion" => "1.0",
                "devicekey" => "BE1B4075-7DED-4136-A79D-8F728C4C13F5",
                "enckey" => "45035b4867531b2a91b9e59ab83895b9"
            )
        );
        //op($fakeRequest); exit;
        return $fakeRequest;
    }
}
?>