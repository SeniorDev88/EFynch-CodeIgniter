<?php
  if ( !defined( 'BASEPATH' ) )
      exit( 'No direct script access allowed' );
  class Hedercontroller extends CI_Model
  {
      function __construct( )
      {
          parent::__construct();
          $this->load->database();  
          $this->load->model(array(
              'Api_model',
              'User_model',
              'Job_model'
          )); 
             
      }


      public function headerCounts(){
      	  $return['messagecount'] = $this->Messagecount();
      	  $return['notifycount']  = $this->notificationcount();
      	  return $return;
      }

      public function allCounts(){
          $return['messagecount'] = $this->Messagecount();
          $return['notifycount']  = $this->notificationcount();
          $return['myjobscount']  = $this->myjobscount();
          if($this->session->userdata('usertype') == 'contractor'){
            $return['mybidscount']  = $this->mybidscount();
          }
          return $return;
      }

      public function mybidscount(){
        $sql   = "SELECT count(a.bidid) as total FROM " . $this->db->dbprefix('bids') . " as a JOIN " . $this->db->dbprefix('jobs') . " as b ON a.jobid = b.jobid  WHERE  a.userid = ? and a.status = '1' and b.status = '1' and ( b.jobstatus = 'new' ) group by b.jobid";
        $query = $this->db->query($sql, array(
             $this->session->userdata('userId')
          ));
          //print $this->db->last_query();
          //exit;
          $array =   $query->row_array();
          $count = $array['total'];
          return $count;
      }

      public function myjobscount(){
        $count = 0;
        if($this->session->userdata('usertype') == 'contractor'){
          $sql   = "SELECT count(a.contractid) as total FROM " . $this->db->dbprefix('contracts') . " as a JOIN " . $this->db->dbprefix('jobs') . " as b ON a.jobid = b.jobid WHERE  a.contractorid = ? and a.status = '1' and b.status = '1' and b.jobstatus = 'inprogress'";
          $query = $this->db->query($sql, array(
               $this->session->userdata('userId')
            ));
            //print $this->db->last_query();
            $array =   $query->row_array();
            $count = $array['total'];
        }else{
          $sql   = "SELECT count(*) as total FROM  " . $this->db->dbprefix('jobs') . " WHERE createdby = ? and  status = '1' and jobstatus != 'verified'";
          $query = $this->db->query($sql, array(
                $this->session->userdata('userId')
            ));
            //print $this->db->last_query();
            $array =   $query->row_array();
            $count = $array['total'];
        }
        return $count;
      }

      public function getserviceamount($amount){
        /*$servicefee = 0;
        $servicefee = $amount * SERVICEFEE /100;*/
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
          //echo round($servicefee,2);exit;
        return round($servicefee,2);
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

      private function Messagecount(){
        $messages = $this->Job_model->getmessageCount($this->session->userdata('userId'));
        return (isset($messages['total'])) ? (string)$messages['total'] : '0';
      }

      public function getnotificationtext($type,$job,$user,$not=''){
        switch ($type) {
          case 'message':
            $message = "A message has been posted by  ".$user['firstname']." on the job ".$job['jobname'];
            break;
          case 'bid':
            $message = "You have received a new bid on ".$job['jobname'];
            break;
          case 'contract':
            if($not != '' && $not == 'email'){

              $message = '<p style="line-height:24px;font-size:15px;color:#747373; padding:10px 20px 0px 20px;">You did it. You were hired for a job and now we can get to work</p>';
              
              $message .= '<p style="line-height:24px;font-size:15px;color:#747373; padding:10px 20px 0px 20px;">As you accept the contract, make sure your banking information and address are accurate. This is very important to get paid. If you have a problem with this- let us know right away so we can remedy it.</p>';

              $message .= '<p style="line-height:24px;font-size:15px;color:#747373; padding:10px 20px 0px 20px;">Keep us posted of the progress and when you are done- take a picture- send it in- post it online! We love that stuff!</p>';

              $message .= '<p style="line-height:24px;font-size:15px;color:#747373; padding:10px 20px 0px 20px;">Make sure you keep in regular communication with the homeowner. Customer Service is our #1 priority and as long as you keep them up to date- we think most problems can be avoided.</p>';

              $message .= '<p style="line-height:24px;font-size:15px;color:#747373; padding:10px 20px 0px 20px;">Make sure you keep in regular communication with the homeowner. Customer Service is our #1 priority and as long as you keep them up to date- we think most problems can be avoided.</p>';               
            }else{
              $message = 'You did it. You were hired for a job and now we can get to work';
            }
            //$message = "Your bid has been accepted by  ".$user['firstname']." for ".$job['jobname']." job. Please sign the contract and begin your work";
            break;
          case 'jstart':
            $message = $user['firstname']." has signed the contract for  ".$job['jobname']." job and should be starting the work soon";
            break;
          case 'jcomplete':
            $message = $user['firstname']." has completed the job ".$job['jobname'];
            break;
          case 'payment':
            $message = "Payment has released for job ".$job['jobname']." on ".date('m/d/Y');
            break;
          case 'payconfirm':
            $message = "Payment has released for job ".$job['jobname']." on ".date('m/d/Y');
            break;
          case 'jobposted':
            $message = "Your Job is posted successfully.";
            break;
          
          default:
            $message = "";
            break;
        }
        return $message;
  }

  public function getnotificationlink($type,$job,$user,$not=''){
        switch ($type) {
          case 'message':
            $link = base_url('messages/'.$job['jobkey'].'/'.$user['userkey']);
            break;
          case 'bid':
            $link = base_url('worklist/'.$job['jobkey']);
            break;
          case 'contract':
            $link = base_url('biddedjob/'.$job['jobkey']);
            break;
          case 'jstart':
            $link = ($job['jobstatus'] == 'verified') ? base_url('owner/completed') : base_url('owner/working');
            break;
          case 'jcomplete':
             $link = ($job['jobstatus'] == 'completed') ? base_url('owner/working') : base_url('owner/completed');
            break;
          case 'payment':
            $link = base_url('accountbalance');
            break;
          case 'payconfirm':
             $link = base_url('accountbalance');
            break;
          
          default:
            
            $link = "";
            break;
        }
       
        return $link;
  }


      public function sendpushnotification($fromuserid,$touserid,$jobid,$type,$notificationid=0, $params = array() ){
            $job = $this->Job_model->getjobbyid($jobid);
            $user = $this->Api_model->getUserDetailsDetailsByObjectID($fromuserid);
            $touser = $this->Api_model->getUserDetailsDetailsByObjectID($touserid);
            $imageattached = 0;
            $notmsg = '';
            $message = $this->getnotificationtext($type,$job,$user,'email');
            if($notificationid != 0){
              $sql   = "SELECT * FROM " . $this->db->dbprefix('notification') . " WHERE notificationid = ? and status = '1' limit 1";
              $query = $this->db->query($sql, array(
                $notificationid
              ));
              //print $this->db->last_query();
              $notific =  $query->row_array();
              if($notific['parenttype'] == 'message'){
                  $sql   = "SELECT * FROM " . $this->db->dbprefix('messages') . " WHERE messageid = ? and status = '1' limit 1";
                  $query = $this->db->query($sql, array(
                    $notific['parentid']
                  ));
                  //print $this->db->last_query();
                  $messg =  $query->row_array();
                  $imageattached =  $messg['hasdoc'];
                  $notmsg =  $messg['message'];
              }

              if($notific['parenttype'] == "bid"){
                $data['jobname'] = $job['jobname'];
                $data['bidDets'] = $this->Job_model->bidByID($notific['parentid']);
                $exper = $this->Api_model->getexpertisebyexpertid([$job['expertiseid']]);
                $data['expertise'] = $exper['name'];
                $data['fromname'] = $user['firstname'] ." ".$user['lastname'];
              }
            }
            $data['message'] = $notmsg;
            $data['imageattached'] = $imageattached;
            $data['type'] = $type;
            $data['notification'] = $message;
            
            $data['name'] = $touser['firstname'];
            $admmsg  = $this->load->view('mail/notification', $data, true);
            //print "<pre>"; print_r($notific);  print_r($data); print "</pre>"; exit;
                      
            //$this->corefunctions->sendmail(ADMINEMAIL, $touser['email'], TITLE . ' :: Notification', $admmsg);

            $this->load->library('email');
            $config['mailtype'] = 'html';
            $config['newline'] = '\r\n';
            $config['crlf'] = '\r\n';
            $config['charset'] = 'UTF-8';
            $this->email->initialize($config);

            $this->email->from(ADMINEMAIL);
            $this->email->to($touser['email']);
            
            $this->email->subject(TITLE . ' :: Notification');
            $this->email->message($admmsg);
            
            //$this->email->send();
            if ( ! $this->email->send()){
                //    echo "Not sent";
            }else{
             // echo "sent";
            }
            $message = $this->getnotificationtext($type,$job,$user,'cell');
            $sql = "select deviceid,userid,devicetoken,devicetype from ".$this->db->dbprefix('user_pushnotifications')." where userid = ? /*AND tokenstatus = '1'*/ AND status = '1'";
            $query = $this->db->query($sql, array(  
              $touserid
             ));

             // print $this->db->last_query();
            $devicetoks =  $query->result_array();
         // echo $touserid;
          //echo "<pre>"; print_r($devicetoks);exit;
            if(!empty($devicetoks)){
              foreach ($devicetoks as $key => $value) {
                //print "<pre>"; print_r($value); print $message; print "</pre>";
                  if($value['devicetype'] == 'Android'){
                      $this->sendAndroidNotification($message, $value['devicetoken'],$params);
                  }else {
                      $this->sendnotification($message, $value['devicetoken'],$params);
                  }
              }
            }
           // exit;

      }

      public function sendOnlyNotification($userid,$message, $params = array() ){

        $sql = "select deviceid,userid,devicetoken,devicetype from ".$this->db->dbprefix('user_pushnotifications')." where userid = ? AND status = '1'";

        $query = $this->db->query($sql, array(  
          $userid
        ));

        $devicetoks =  $query->result_array();
        if(!empty($devicetoks)){
          foreach ($devicetoks as $key => $value) {
              if($value['devicetype'] == 'Android'){
                $this->hedercontroller->sendAndroidNotification($message, $value['devicetoken'],$params);
              }else {
                $this->hedercontroller->sendnotification($message, $value['devicetoken'],$params);
              }
          }
        }
      }

      private function sendAndroidNotification($message,$deviceToken, $params = array() ){
          $fields = array(
              'to' => $deviceToken,
              'priority' => "high",
              'notification' => array("body" => $message),
              'data' => array("message" =>$message),
          );
          if(!empty($params)){
            $data = array_merge($fields['data'],$params);
            $fields['data'] = $data;
          }
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
          //echo "<pre>"; print_r($result);exit;
          if ($result === FALSE) {
              die('Problem occurred: ' . curl_error($ch));
          }
          curl_close($ch);
      }
        
      private function sendnotification($message,$deviceToken, $params = array() ){
          
          //$message = "John updated the file cabinet";
          //$deviceToken = "5b822ebc720de2dc4304252bb85826b03e02183a79ff478f294bd89de47a5883";       
              // My private key's passphrase here:
              $passphrase = 'icwares';       
              // My alert message here:        
              //badge
              $badge = 1;        
          $ctx = stream_context_create();
           // stream_context_set_option($ctx, 'ssl', 'local_cert', 'certs/EFDCertificates-1.pem');
          //stream_context_set_option($ctx, 'ssl', 'local_cert', 'certs/efdevcertificate.pem');
         //live 
           //  stream_context_set_option($ctx, 'ssl', 'local_cert', 'certs/EFSCertificates-2.pem');
          //stream_context_set_option($ctx, 'ssl', 'local_cert', 'certs/efdiscertificate.pem');
          stream_context_set_option($ctx, 'ssl', 'local_cert', 'certs/efdiscertificate.pem');
          stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);              
          // Open a connection to the APNS server
           /*$fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);*/
           $fp = stream_socket_client(
            'ssl://gateway.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
                     
          if (!$fp)
          exit("Failed to connect: $err $errstr" . PHP_EOL);
          
          //echo 'Connected to APNS' . PHP_EOL;
          
          // Create the payload body
          $body['aps'] = array(
            'alert' => $message,
            'badge' => $badge,
            'sound' => 'default'
          );  
          if(!empty($params)){
            $body['aps'] = array_merge($body['aps'],$params);
          }
          // Encode the payload as JSON
          $payload = json_encode($body);
          
          // Build the binary notification
          $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
          
          // Send it to the server
          $result = fwrite($fp, $msg, strlen($msg));
          
    /*if (!$result)
            echo 'Error, notification not sent' . PHP_EOL;
          else
            echo 'notification sent!' . PHP_EOL; */

          //print "<pre>";  print_r($body); echo " AM1 "; print_r($msg);echo " AM2 "; print_r($result);  echo " deviceToken "; print $deviceToken;  echo " payload "; print $payload;print "</pre>"; exit;
          //exit;  
            
          
          // Close the connection to the server
          fclose($fp);
    
    } 
  
 
	
	  public function getemail(){
	   
	  $sql = "select email from ".$this->db->dbprefix('users')." where userid = ? limit 1 "; 
	  $query = $this->db->query($sql, array(  
	   $this->session->userdata('userId')));
		 // print $this->db->last_query();
		return $query->row_array();  
	   
	  }
 
      public function headerdata( )
      {
		 $hasUser = $this->getemail();
		 if($hasUser['email'] != $this->session->userdata('userEmail')){
			  $this->session->sess_destroy();
              $se = $this->session->all_userdata();
              redirect(base_url());
			  exit;
		  }
		$usermail = $this->getuseremail($this->session->userdata('userId'));		
        $invcount = $this->getinvitationnum($usermail['email']);
        $accepted = $this->getacceptedcount($this->session->userdata('userId'));
		$total = $invcount['total'] + $accepted['total'];
        return  $total;	  
         
      }
  public function getArrayIndexed( $array, $index ) {
          $finalArray = array( );
          if ( !empty( $array ) ) {
              foreach ( $array as $a ) {
                  $finalArray[ $a[ $index ] ] = $a;
              }
          }
          return $finalArray;
      }

  public function getActivity($myjobs){
    $activities = $jobids = $userids = $users = array();
    if(!empty($myjobs)){
      foreach($myjobs as $my){
        $jobids[] = $my['jobid'];
      }
      $myjobs = $this->getArrayIndexed($myjobs,'jobid');
    }
    if(!empty($jobids)){
      $activities = $this->User_model->getuseractivities(join(",",array_unique($jobids)),$recent="new");
    }
    if(!empty($activities)){
      foreach($activities as $act){
        $userids[] = $act['userid'];
      } 
      if(!empty($userids)){
        $users =  $this->Api_model->getuserdetsbyids(array_unique($userids)) ;
        if(!empty($users)){
          foreach($users as $uk=>$us){
            $users[$uk]['image'] = ($us['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($us['userid'], $us['imagekey'], $us['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
            $users[$uk]['userkey'] = $us['userkey'];
          }
        }
        $users =  $this->getArrayIndexed($users,'userid');
      }
      
    }
    $returnActivity = array();
    if(!empty($users) and !empty($activities) and !empty($myjobs)){
      foreach($activities as $ak=>$act){
        $returnActivity[$ak]['text'] = $users[$act['userid']]['firstname']." ".$users[$act['userid']]['lastname'][0]." " .$act['activitytext']." " .$myjobs[$act['jobid']]['jobname'];
        //$returnActivity[$ak]['jobid'] =  $myjobs[$act['jobid']]['jobid'];
        $returnActivity[$ak]['userimege'] = $users[$act['userid']]['image'];
        $returnActivity[$ak]['createdate'] = $act['createdate'];
        $returnActivity[$ak]['userkey'] = $users[$act['userid']]['userkey'];;//$act['createdate'];
      }
    }

    return $returnActivity;

  }

  public function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
      $sort_col = array();
      foreach ($arr as $key=> $row) {
          $sort_col[$key] = $row[$col];
      }
       array_multisort($sort_col, $dir, $arr);
       return $arr;
  }
  }
?>