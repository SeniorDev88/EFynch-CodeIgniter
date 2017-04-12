<?php
  class Api_model extends CI_Model {
      public function __construct() {
          $this->load->database();
          $this->load->dbforge();
          $this->load->library(array(
              'corefunctions'
          ));

          require_once(APPPATH.'third_party/braintree/lib/Braintree.php');

          Braintree\Configuration::environment(BT_environment);
          Braintree\Configuration::merchantId(BT_merchantId);
          Braintree\Configuration::publicKey(BT_publicKey);
          Braintree\Configuration::privateKey(BT_privateKey);
      }
      public function filter($data) {
          $data = trim($data);
          return $data;
      }
      public function addLog($datad) {
          $data = array(
              'inputdata' => $datad,
              'createdate' => time()
          );
          $this->db->insert('log', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }

       public function create_bt_merchant($data) {
          $merchantAccountParams = [
              'individual' => [
                  // 'firstName' => $data['fName'],
                  'firstName' => $data['fName'], // TESTING APPROVE
                  // 'firstName' => Braintree_Error_Codes::MERCHANT_ACCOUNT_APPLICANT_DETAILS_DECLINED_OFAC, // TESTING DECLINE
                  'lastName' => $data['lName'],
                  'email' => $data['email'],
                  'phone' => $data['phone'],
                  'dateOfBirth' => $data['dob'],
                  'address' => [
                      'streetAddress' => $data['address'],
                      'locality' => $data['city'],
                      'region' => $data['state'],
                      'postalCode' => $data['zip'],
                  ]
              ],
              'business' => [
              'legalName' => $data['company'],
                     'taxId' => $data['taxId'],
                    //'taxId' => '98-7654321',
                    'address' => [
                        'streetAddress' => $data['companyAddress'],
                        'locality' => $data['companyCity'],
                        'region' => $data['companyState'],
                        'postalCode' => $data['companyZip'],
                    ]
            ],
            'funding' => [
                  'destination' => Braintree_MerchantAccount::FUNDING_DESTINATION_BANK,
                   'accountNumber' => $data['accountNumber'],
                   'routingNumber' => $data['routingNumber']
                  //'accountNumber' => '1123581321',
                  //'routingNumber' => '071101307'
              ],
              'tosAccepted' => $data['agreeTerms'],
              'masterMerchantAccountId' => BT_MASTER_MERCHANTID
          ];

          $return =  Braintree_MerchantAccount::create($merchantAccountParams);
          return $return;

    }

     public function create_bt_merchant_myprofile($data) {
          $merchantAccountParams = [
              'individual' => [
                  // 'firstName' => $data['fName'],
                  'firstName' => $data['fName'], // TESTING APPROVE
                  // 'firstName' => Braintree_Error_Codes::MERCHANT_ACCOUNT_APPLICANT_DETAILS_DECLINED_OFAC, // TESTING DECLINE
                  'lastName' => $data['lName'],
                  'email' => $data['email'],
                  'phone' => $data['phone'],
                  'dateOfBirth' => $data['dob'],
                  'address' => [
                      'streetAddress' => $data['address'],
                      'locality' => $data['city'],
                      'region' => $data['state'],
                      'postalCode' => $data['zip'],
                  ]
              ],
              
            'funding' => [
                  'destination' => Braintree_MerchantAccount::FUNDING_DESTINATION_BANK,
                   'accountNumber' => $data['accountNumber'],
                   'routingNumber' => $data['routingNumber']
                  //'accountNumber' => '1123581321',
                  //'routingNumber' => '071101307'
              ],
              'tosAccepted' => $data['agreeTerms'],
              'masterMerchantAccountId' => BT_MASTER_MERCHANTID
          ];

          $return =  Braintree_MerchantAccount::create($merchantAccountParams);
		  //print_r($return);
		  //exit;
          return $return;

    }

    public function update_bt_business($bt_merchantid,$data){
        $result = Braintree_MerchantAccount::update(
          $bt_merchantid,
          [
            'business' => [
              'legalName' => $data['company'],
                     'taxId' => $data['taxId'],
                    //'taxId' => '98-7654321',
                    'address' => [
                        'streetAddress' => $data['companyAddress'],
                        'locality' => $data['companyCity'],
                        'region' => $data['companyState'],
                        'postalCode' => $data['companyZip'],
                    ]
            ]
          ]
        );

        return $result;

    }
    public function update_bt_funding($bt_merchantid,$data){
        $result = Braintree_MerchantAccount::update(
          $bt_merchantid,
          [
            'funding' => [
                  'destination' => Braintree_MerchantAccount::FUNDING_DESTINATION_BANK,
                   'accountNumber' => $data['accountNumber'],
                   'routingNumber' => $data['routingNumber']
                  //'accountNumber' => '1123581321',
                  //'routingNumber' => '071101307'
              ]
          ]
        );

        return $result;

    }

     public function update_bt_merchant($bt_merchantid,$data) {
      //$this->load->helper('braintree');
          

          $merchantAccountParams = [
              'individual' => [
                  // 'firstName' => $data['fName'],
                  'firstName' => $data['fName'], // TESTING APPROVE
                  // 'firstName' => Braintree_Error_Codes::MERCHANT_ACCOUNT_APPLICANT_DETAILS_DECLINED_OFAC, // TESTING DECLINE
                  'lastName' => $data['lName'],
                  'email' => $data['email'],
                  'phone' => $data['phone'],
                  'dateOfBirth' => $data['dob'],
                  'address' => [
                      'streetAddress' => $data['address'],
                      'locality' => $data['city'],
                      'region' => $data['state'],
                      'postalCode' => $data['zip'],
                  ]
              ]
          ];

          $result =  Braintree_MerchantAccount::update($bt_merchantid,$merchantAccountParams);
         // print "<pre>"; print_r($btdata); print_r($result); print "</pre>";
          return $result;

    }


      public function authorizeApiAccess($info) {
          $authkey = $this -> corefunctions -> generateUniqueKey('32', 'icapiauth', 'authkey');
          $data = array(
              'userid' => $info['userId'],
              'authinfo' => serialize($info),
              'authkey' => $authkey,
              'createdate' => time()
          );
          $this->db->insert('icapiauth', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $authkey;
      }







      public function checkAuthorizeToken($authekey){
        
       // $expTime = time() -  ( 2 * 60 * 60 );
        $sql   = 'SELECT * FROM ' . $this->db->dbprefix('icapiauth') . ' WHERE authkey = ? limit 1';
        $query = $this->db->query($sql, array(
            $authekey
        ));
        //print $this->db->last_query();
        return $query->row_array();


      }
      public function getUserDetailsDetailsByObjectID($userid) {
          $sql   = 'SELECT * FROM ' . $this->db->dbprefix('users') . ' WHERE userid = ? limit 1';
          $query = $this->db->query($sql, array(
              $userid
          ));
          //print $this->db->last_query();
          return $query->row_array();
      }
      public function getDetailsBybjectIDs($table, $objectID) {
          $sql   = 'SELECT * FROM ' . $this->db->dbprefix($table) . ' WHERE icobjectid = ? limit 1';
          $query = $this->db->query($sql, array(
              $objectID
          ));
          //print $this->db->last_query();
          return $query->row_array();
      }
      public function addLoginInfo($parentid, $city, $region, $country, $loc, $org, $ip) {
          $data = array(
              'userid' => $parentid,
              'browser' => $_SERVER['HTTP_USER_AGENT'],
              'phpsessid' => session_id(),
              'ipaddress' => $ip,
              'city' => $city,
              'region' => $region,
              'country' => $country,
              'loc' => $loc,
              'org' => $org,
              'createdate' => time()
          );
          $this->db->insert('logininfo', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }

      function getUserDevice($userid, $devicekey){
        $sql   = 'SELECT * FROM ' . $this->db->dbprefix('devices') . ' WHERE userid = ? AND devicekey = ? AND `status` = ? limit 1';
        $query = $this->db->query($sql, array(
            $userid,
            $devicekey,
            '1'
        ));
        return $query->row_array();
      }

      function removeUserDevice($userid, $devicekey){
        
        $this->db->where('userid', $userid);
        $this->db->where('devicekey', $devicekey);
        return $this->db->delete('efy_devices');
      }

      function checkDeviceDets($userid) {
        $sql   = 'SELECT * FROM ' . $this->db->dbprefix('devices') . ' WHERE userid = ? limit 1';
        $query = $this->db->query($sql, array(
            $userid
        ));
        //print $this->db->last_query();
        return $query->row_array();
      }

      function getdevicedets($deviceid) {
          $sql   = 'SELECT * FROM ' . $this->db->dbprefix('devices') . ' WHERE deviceid = ? limit 1';
          $query = $this->db->query($sql, array(
              $deviceid
          ));
          //print $this->db->last_query();
          return $query->row_array();
      }


      function insertDeviceDetails($userid, $devicekey) {
          $data = array(
              'devicekey' => $devicekey,
              'userid' => $userid,
              'createdate' => time(),
              'updatedate' => time()
          );
          $this->db->insert('devices', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }
      public function create_user($userkey, $password, $input,$verificationcode) {
          $data = array(
              'firstname'     => $this->filter($input['firstname']),
              'lastname'      => $this->filter($input['lastname']),
              'city'          => $this->filter($input['city']),
              'state'         => $this->filter($input['state']),
              'zip'           => $this->filter($input['zip']),
              'phone'         => $this->filter($input['phone']),
              'address'       => $this->filter($input['address']),
              'dob'      => (isset($input['dob']) and $input['dob'] != '') ? date('Y-m-d',strtotime($input['dob'])) : '0000-00-00',
              'password'      => $password,
              'userkey'       => $userkey,
              'usertype'          => $input['usertype'],
              'email'         => $input['email'],
              'verificationcode'=>$verificationcode,
              'isagreeterms'          => '1',
              'createdate' => time()
          );
          $this->db->insert('users', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }

      public function create_contractor_details($userid,  $input) {
          $detailskey = $this->corefunctions->generateUniqueKey('6', 'contractor_details', 'detailskey');
          $data = array(
              'companyname'     => ($input['companyname'] != '') ? $this->filter($input['companyname']) : '',
              //'taxid'           => ($input['iscompany'] == '1') ? $this->filter($input['companydetails']['taxid']) : '',
              //'companycity'     => ($input['iscompany'] == '1') ? $this->filter($input['companydetails']['companycity']) : '',
              //'companystate'    => ($input['iscompany'] == '1') ? $this->filter($input['companydetails']['companystate']) : '',
              //'companyzip'      => ($input['iscompany'] == '1') ? $this->filter($input['companydetails']['companyzip']) : '',
              //'companyaddress'  => ($input['iscompany'] == '1') ? $this->filter($input['companydetails']['companyaddress']) : '',
              'license'         => ($input['licenseandbankdetails']['license'] != '') ? $this->filter($input['licenseandbankdetails']['license']) : '',
              'insurance'       => ($input['licenseandbankdetails']['insurance'] != '') ? $this->filter($input['licenseandbankdetails']['insurance']) : '',
              'userid'          => $userid,
              'detailskey'      => $detailskey,
              //'accountnumber'   => $input['licenseandbankdetails']['accountnumber'],
              'status'          => '1',
              'createdate'      => time()
          );
          $this->db->insert('contractor_details', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }

      public function create_user_expertise($userid,  $expertiseid) {
          $data = array(
              'userid'          => $userid,
              'expertiseid'     => $expertiseid,
              'status'          => '1',
              'createdate'      => time()
          );
          $this->db->insert('user_expertise', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }

      public function getuserexpertise($userid){
        $sql   = ' SELECT * FROM ' . $this->db->dbprefix('user_expertise') . ' where status = ?  and userid = ?';
          $query = $this->db->query($sql, array(
              '1',$userid
          ));
      //print $this->db->last_query(); exit;
          return $query->result_array();

      }

      public function getexpertise() {
          $sql   = ' SELECT * FROM ' . $this->db->dbprefix('expertise') . ' where status = ? order by exporder asc';
          $query = $this->db->query($sql, array(
              '1'
          ));
      //print $this->db->last_query(); exit;
          $exp = $query->result_array();
          if(!empty($exp)){
            foreach($exp as $exk=>$ex){
              $exp[$exk]['img'] = base_url('assets/images/Icons/icons-expertise/'.$ex['img']);
            }
          }
          return $exp;
      }
     
      public function getuserdetsbyids($userids) {
		   $sql   = "SELECT userkey,userid,firstname,lastname,imagekey,imageext,usertype,dob,city,bt_accountverified,bt_merchantid,state,zip FROM " . $this->db->dbprefix('users') . " WHERE userid in (".join(",",$userids).") and status = ? ";
          $query = $this->db->query($sql, array(
              '1'
          ));
		  //print $this->db->last_query(); exit;
          return $query->result_array();
      }
      
      
     
      public function user_by_key($userkey) {
          $sql   = 'SELECT userkey,firstname,lastname,email,address,city,state,zip,phone,imagekey,imageext,userid,usertype,dob,experiance,businessdescription,bt_accountverified,bt_merchantid,intro_video FROM ' . $this->db->dbprefix('users') . ' WHERE userkey = ?  limit 1';
          $query = $this->db->query($sql, array(
              $userkey
          ));
          //print $this->db->last_query();
          return $query->row_array();
      }
      public function user_by_email($email) {
          $sql   = 'SELECT usertype,userkey,firstname,lastname,email,userid FROM ' . $this->db->dbprefix('users') . ' WHERE email = ?  limit 1';
          $query = $this->db->query($sql, array(
              $email
          ));
          //print $this->db->last_query();
          return $query->row_array();
      }
     
      public function updateprofile($userdets) {
          $data = array(
              'firstname' => $userdets['firstname'],
              'lastname' => $userdets['lastname'],
              'address' => $userdets['address'],
              'phone' => $userdets['phone'],
              'city' => $userdets['city'],
              'state' => $userdets['state'],
              'zip' => $userdets['zip'],
			        'email' => $userdets['email'],
              'updatedate' => time()
          );
          $this->db->where('userkey', $this->session->userdata('userKey'));
          $this->db->update('users', $data);
          //print $this->db->last_query();
      }
      public function updateverified($userid) {
          $data = array(
              'isverified' => '1',
              'updatedate' => time()
          );
          $this->db->where('userid', $userid);
          $this->db->update('users', $data);
          //print $this->db->last_query();
      }

      public function getexpertisebyslug($slug) {
          $sql   = ' SELECT * FROM ' . $this->db->dbprefix('expertise') . ' where status = ? and slug = ? limit 1';
          $query = $this->db->query($sql, array(
              '1',$slug
          ));
          //print $this->db->last_query(); exit;
          return $query->row_array();
      }

      public function getexpertisebyids($expertiseids) {
          $sql   = ' SELECT * FROM ' . $this->db->dbprefix('expertise') . ' where expertiseid in ('.join(",",$expertiseids).')';
          $query = $this->db->query($sql);
      //print $this->db->last_query(); exit;
          return $query->result_array();
      }
      
      public function getexpertisebyexpertid($expertiseid) {
          $sql   = ' SELECT * FROM ' . $this->db->dbprefix('expertise') . ' where expertiseid = ? limit 1';
           $query = $this->db->query($sql, array(
              $expertiseid
          ));
      //print $this->db->last_query(); exit;
          return $query->row_array();
      }
      
      public function getStateDetails($stateCode) {
          $sql   = ' SELECT * FROM ' . $this->db->dbprefix('states') . ' where state_prefix = ?  limit 1';
          $query = $this->db->query($sql, array(
              $stateCode
          ));
      //print $this->db->last_query(); exit;
          return $query->row_array();
      }

      public function deleteImage($id){
          $this->db->where('docid', $id);
          $this->db->delete('efy_docs');
      }

      public function getWorkImages($userid) {
          $this->db->select('docid,dockey,docext');
          $this->db->where(array('parentid'=>$userid,'parenttype'=>'work_image'));
          $result = $this->db->get('efy_docs')->result_array();
          return $result;
      }

      public function getcontractordetails($userid) {
          $sql   = 'SELECT companyname,taxid,companyaddress,companystate,companycity,companyzip,license,insurance,routingnumber,accountnumber,overview_experience,certifications,introduction,us_veteran,notable_work,contractorLicense,contractorInsurance FROM ' . $this->db->dbprefix('contractor_details') . ' WHERE userid = ?  limit 1';
          $query = $this->db->query($sql, array(
              $userid
          ));
          //print $this->db->last_query();
          return $query->row_array();
      }

       public function update_user($userid,  $input) {
          $data = array(
              'firstname'     => $this->filter($input['firstname']),
              'lastname'      => $this->filter($input['lastname']),
              'city'          => $this->filter($input['city']),
              'email'          => $this->filter($input['email']),
              'state'         => $this->filter($input['state']),
              'zip'           => $this->filter($input['zip']),
              'phone'         => $this->filter($input['phone']),
              'address'       => $this->filter($input['address']),
              'dob'      => (isset($input['dob']) and $input['dob'] != '') ? date('Y-m-d',strtotime($input['dob'])) : '0000-00-00',
              'experiance'      => (isset($input['experiance']) and $input['experiance'] != '') ? $input['experiance'] : '',
              'businessdescription'      => (isset($input['businessdescription']) and $input['businessdescription'] != '') ? $input['businessdescription'] : '',
              'updatedate' => time()
          );
          $this->db->where('userid', $userid);
          $this->db->update('users', $data);
      }

      public function update_contractor_details($userid,  $input) {
          
          $data = array(
              'companyname'     => ($input['companydetails']['companyname'] != '') ?  $this->filter($input['companydetails']['companyname']) : '',
              'taxid'           => ($input['companydetails']['taxid'] != '') ? $this->filter($input['companydetails']['taxid']) : '' ,
              'companycity'     => ($input['companydetails']['companycity'] != '') ? $this->filter($input['companydetails']['companycity']) : '',
              'companystate'    => ($input['companydetails']['companystate'] != '') ? $this->filter($input['companydetails']['companystate']) : '',
              'companyzip'      =>  ($input['companydetails']['companyzip'] != '') ? $this->filter($input['companydetails']['companyzip'])  : '',
              'companyaddress'  => ($input['companydetails']['companyaddress'] != '') ? $this->filter($input['companydetails']['companyaddress']) : '' , 
              'license'         => ($input['licenseandbankdetails']['license'] != '') ? $this->filter($input['licenseandbankdetails']['license']) : '',
              'insurance'       => ($input['licenseandbankdetails']['insurance'] != '') ? $this->filter($input['licenseandbankdetails']['insurance']) : '',
              //'routingnumber'   => ($input['licenseandbankdetails']['routingnumber'] != '') ? $this->filter($input['licenseandbankdetails']['routingnumber']) : '',
              //'accountnumber'   => ($input['licenseandbankdetails']['accountnumber'] != '') ? $input['licenseandbankdetails']['accountnumber'] : '',
              'updatedate'      => time(),
			  
			  
			  
				
				
			  'overview_experience'     =>  $this->filter($input['overview_experience']),
			  'introduction'     =>  $this->filter($input['introduction']),
			  'certifications'     =>  $this->filter($input['certifications']),
			  'notable_work'     =>  $this->filter($input['notable_work']),
			  'us_veteran'     =>  $this->filter($input['us_veteran']),
              'contractorLicense'         => ($input['contractorLicense'] != '') ? $this->filter($input['contractorLicense']) : '',
              'contractorInsurance'       => ($input['contractorInsurance'] != '') ? $this->filter($input['contractorInsurance']) : '',
				
			  
			  
          );
         
          $this->db->where('userid', $userid);
          $this->db->update('contractor_details', $data);
      }

      public function updateuser_expertise($userid,$expertiseid) {
          $data = array(
              'status' => '0',
              'updatedate' => time()
          );
          $this->db->where(array('userid'=> $userid,'expertiseid'=>$expertiseid));
          $this->db->update('user_expertise', $data);
          //print $this->db->last_query();
      }

      public function getStates() {
          $sql   = ' SELECT * FROM ' . $this->db->dbprefix('states') ;
          $query = $this->db->query($sql);
      //print $this->db->last_query(); exit;
          return $query->result_array();
      }
      public function getCity($state_code) {
          $sql   = ' SELECT * FROM ' . $this->db->dbprefix('city').' where state_code = ?' ;
          $query = $this->db->query($sql,array($state_code));
      //print $this->db->last_query(); exit;
          return $query->result_array();
      }

      public function updatebtmerchantid($userid,$bt_merchantid) {
          $data = array(
              'bt_merchantid' => $bt_merchantid,
              'updatedate' => time()
          );
          $this->db->where('userid', $userid);
          $this->db->update('users', $data);
          //print $this->db->last_query();
      }

      public function getnotification($touserid){
        $sql   = " SELECT * FROM " . $this->db->dbprefix('notification')." where touserid = ? and read = '0' and status = '1'" ;
          $query = $this->db->query($sql,array($touserid));
      //print $this->db->last_query(); exit;
          return $query->result_array();
      }

      function removepushnotification($userid, $deviceid){

        $this->db->where('userid', $userid);
        $this->db->where('deviceid', $deviceid);
        return $this->db->delete('efy_user_pushnotifications');

      }

      function addpushnotification($input) {
        $notif  = $this->getnot_devicetoken($input['devicekey']);
        if(!empty($notif)){
          $pushid = $notif['pushid'];
          //$this->updatepushnotification($notif['pushid']);
          $data = array(
             // 'deviceid' => $input['deviceid'],
              'devicetoken' => $input['devicetoken'],
              'userid' => $this->session->userdata('userId'),
              'tokenstatus' => $input['tokenstatus'],
              'updatedate' => time()
          );
          if(isset($input['devicetype'])){
              $data['devicetype'] = $input['devicetype'];
          }else{
            $data['devicetype'] = "IOS";
          }
          $this->db->where('pushid',$pushid);
          $this->db->update('user_pushnotifications', $data);
          $insert_id = $pushid;
        }else{

          $data = array(
              'deviceid' => $input['devicekey'],
              'devicetoken' => $input['devicetoken'],
              'userid' => $this->session->userdata('userId'),
              'tokenstatus' => $input['tokenstatus'],
              'status' =>'1',
              'createdate'=>time()
          );
            if(isset($input['devicetype'])){
                $data['devicetype'] = $input['devicetype'];
            }else{
              $data['devicetype'] = "IOS";
            }

            $this->db->insert('user_pushnotifications', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();

        }
          
          //print $this->db->last_query();
          return $insert_id;
      }  

      public function getnot_devicetoken($devicetoken){
        $sql   = " SELECT * FROM " . $this->db->dbprefix('user_pushnotifications')." where deviceid = ? and status = '1' limit 1" ;
          $query = $this->db->query($sql,array($devicetoken));
      //print $this->db->last_query(); exit;
          return $query->row_array();

      }

      public function updatepushnotification($pushid) {
          $data = array(
              'status' => '0',
              'updatedate' => time()
          );
          $this->db->where(array('pushid'=> $pushid,'status'=>'1'));
          $this->db->update('user_pushnotifications', $data);
          //print $this->db->last_query();
      }

      public function getuser_pushnot($devicetoken,$userid){
        $sql   = " SELECT * FROM " . $this->db->dbprefix('user_pushnotifications')." where deviceid = ? and userid = ? and status = '1' limit 1" ;
          $query = $this->db->query($sql,array($devicetoken,$userid));
      //print $this->db->last_query(); exit;
          return $query->row_array();

      }

      public function update_btaccountverified($userid) {
          $data = array(
              'bt_accountverified' => '1',
              'updatedate' => time()
          );
          $this->db->where('userid', $userid);
          $this->db->update('users', $data);
          //print $this->db->last_query();
      }

     
  }
?>
