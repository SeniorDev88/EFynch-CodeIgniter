<?php
  if (!defined('BASEPATH'))
      exit('No direct script access allowed');
	class Messages extends CI_Controller {
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
          //error_reporting(E_ALL);
          //ini_set('display_errors','1');

          if( !( $this->session->userdata('userKey') ) ){
				redirect( base_url() );
				exit;
			}
    }

    public function messages(){
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
                $returnJobs[$jb['jobid']]['jobkey']  = $jb['jobkey'];
                $returnJobs[$jb['jobid']]['jobname']  = $jb['jobname'];
                $returnJobs[$jb['jobid']]['imageurl']  = $fileurl;

                foreach($messages as $mes){
                  if($mes['touserid'] == $this->session->userdata('userId') and $mes['readmessage'] == 0 and $mes['jobid'] == $jb['jobid']){
                    $jbMessages[$jb['jobid']]['count'][] = $mes;
                  }
                  if(!isset($jbMessages[$mes['jobid']]['messagetime']) or $jbMessages[$mes['jobid']]['messagetime'] == ""){
                    $jbMessages[$mes['jobid']]['messagetime'] = $mes['createdate'];
                  }
                  //print "<pre>";print_r($mes); print "</pre>";
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
          $data['jobs'] = $returnJobs;
          $data['allcounts'] = $this->hedercontroller->allCounts();
          $data['subhead']  = 'message';
          
          $headerdata['headerdata'] = $this->hedercontroller->headerCounts();
          $this->load->view('headers/header',$headerdata);
		  $this->load->view('headers/left-menu');
		  $this->load->view('messages/message-inbox',$data);
		  $this->load->view('headers/footer');
    }
    public function messagedetails($jobkey,$userkey=""){

    	  $jobDets = $this -> Job_model -> jobByKey( $jobkey );

    	  $messages = $this->Job_model->getuserJobmessages($this->session->userdata('userId'),$jobDets['jobid']);

        $user_videos = $this->session->userdata('videos');
        $write_message = '';
        if (!empty($user_videos['write_message'])){
          $write_message = $user_videos['write_message'];

          $videos = $this->session->userdata('videos');
          unset($videos['login']);
          $this->session->set_userdata('videos',$videos);
        }

    	  $userids = $users = $userdets = $lmArray = array();
    	  if($userkey != ""){
          		//$userkey = $user['userkey'];
          		$usDet = $this->Api_model->user_by_key($userkey);
          		$userids[] = $usDet['userid'];
          }


           
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
                $users[$user['userid']]['userkey'] = $user['userkey'];
                $users[$user['userid']]['firstname'] = $user['firstname'];
                $users[$user['userid']]['lastname'] = $user['lastname'];
                if($this->values['usertype'] == 'homeowner'){
                    $users[$user['userid']]['contractoruserid'] = $user['userid'];
                }else{
                    $users[$user['userid']]['homeowneruserid'] = $user['userid'];
                }
                
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
              $users[$user['userid']]['messagetime'] = (isset($lmArray[$user['userid']]['messagetime'])) ? $lmArray[$user['userid']]['messagetime'] : '' ;
              $users[$user['userid']]['msgcount'] = (isset($jbMessages[$user['userid']])) ? (string)count($jbMessages[$user['userid']]) : '0' ;
            }
            //print "<pre>"; print_r($users); print "</pre>";
            $users = $this->hedercontroller->array_sort_by_column($users, 'messagetime');
            foreach($users as $uk=>$user){
              if($userkey == ""){
                  $userkey = $user['userkey'];
              }
            }
            //print "<pre>";print_r($users); print "</pre>";
            //$users = $this->corefunctions->getArrayIndexed($users,'userid');
          }
          $data['users'] = $users;
          //print "<pre>";print_r($users); print "</pre>";
          $messages =  array();
          $userDetails   = $this->Api_model->user_by_key($userkey);
          $messages = $this->Job_model->getmessages($this->session->userdata('userId'),$userDetails['userid'],$jobDets['jobid'],'0');

           $msguserids = $msgusers = $msguserdets = $lmArray = array();
           $msguserids[] = $this->session->userdata('userId');
           $msguserids[] = $userDetails['userid'];
           
           if(!empty($msguserids)){
              $msguserdets =  $this->Api_model->getuserdetsbyids(array_unique($msguserids)) ;
              if(!empty($msguserdets)){
                foreach($msguserdets as $uk=>$msguser){
                  $msgusers[$msguser['userid']]['profileimageurl'] = ($msguser['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($msguser['userid'], $msguser['imagekey'], $msguser['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
                  
                  $msgusers[$msguser['userid']]['firstname'] = $msguser['firstname'];
                  $msgusers[$msguser['userid']]['lastname'] = $msguser['lastname'];
                  if($msguser['userid'] == $this->session->userdata('userId')){
                      $msgusers[$msguser['userid']]['iscurrentuser'] = '1';
                  }else{
                      $msgusers[$msguser['userid']]['iscurrentuser'] = '0';
                  }
                  $msgusers[$msguser['userid']]['userkey'] = $msguser['userkey'];
                  
                }
              }
            }
            $this->Job_model->updatereadmessages($jobDets['jobid'],$this->session->userdata('userId'));
            
            $returnMessage = array();
            if(!empty($messages)){
              foreach($messages as $mk=>$mes){
                $returnMessage[$mk]['message'] = $mes['message'];
                $returnMessage[$mk]['posteddate'] = date('m/d/Y',$mes['createdate']);

                $fileurl = '';
                if($mes['hasdoc'] == '1'){
                  $docs = $this->Job_model->getdocs($mes['messageid'],'message');
                  $jDocs = array();
                  if(!empty($docs)){
                    foreach($docs as $dk=>$d){
                      $fileurl = base_url($this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download'));
                    }
                  }
                }
                $returnMessage[$mk]['completionnote'] = $mes['completionnote'] ;
                $returnMessage[$mk]['hasdoc'] = $mes['hasdoc'] ;
                $returnMessage[$mk]['doc'] = $fileurl;
                $returnMessage[$mk]['messagekey'] = $mes['messagekey'];
                $returnMessage[$mk]['firstname'] = $msgusers[$mes['fromuserid']]['firstname'];
                $returnMessage[$mk]['lastname'] = $msgusers[$mes['fromuserid']]['lastname'];
                $returnMessage[$mk]['profileimageurl'] = $msgusers[$mes['fromuserid']]['profileimageurl'];
                $returnMessage[$mk]['iscurrentuser'] = $msgusers[$mes['fromuserid']]['iscurrentuser'];
                $returnMessage[$mk]['userkey'] = $msgusers[$mes['fromuserid']]['userkey'];
              }
            }
          $returnMessage = array_reverse($returnMessage);
          $data['userkey'] = $userkey;
          $data['jobkey'] = $jobkey;
          
          $data['messages'] = $returnMessage;
          $data['subhead']  = 'message';
          $data['write_message'] = $write_message;
         // print "<pre>"; print_r($data); print "</pre>";
      $data['allcounts'] = $this->hedercontroller->allCounts();
      $headerdata['headerdata'] = $this->hedercontroller->headerCounts();
		  $this->load->view('headers/header',$headerdata);
		  $this->load->view('headers/left-menu');
		  $this->load->view('messages/message-details',$data);
		  $this->load->view('headers/footer');
	}

	public function sendmessage(){
		  $userkey = $this->input->post('userkey');
		  $jobkey = $this->input->post('jobkey');
		  $message = $this->input->post('message');
		  $job = $this -> Job_model -> jobByKey( $jobkey );
		  $touser = $this->Api_model->user_by_key($userkey);
      if($this->input->post('act') == 'message'){
        $messageid = $this->Job_model->create_message($message,$this->session->userdata('userId'),$touser['userid'],$job['jobid'],'0');
      }else{
        $messageid = $this->Job_model->create_message('',$this->session->userdata('userId'),$touser['userid'],$job['jobid'],'1');
      }
      if($this->input->post('act') == 'upload'){
          //$location        = "assets/docs/";
          $ext                     = pathinfo($_FILES['formData']['name'], PATHINFO_EXTENSION);
          $docKey                  = $this->corefunctions->generateUniqueKey('6', 'docs', 'dockey');
          $originalname = $_FILES['formData']['name'];

          $docid = $this->Job_model->createdoc($docKey,$ext,'message',$messageid,$originalname);
          $uploadTo       = $this->corefunctions->getMyPath($docid,$docKey,$ext,'assets/docs/');
          $location = implode('/',explode('/', $uploadTo,-1));
          //print "<pre>";print_r($uploadTo); print_r($location); print "</pre>";
          $config['upload_path']   = $location;
          $config['allowed_types'] = 'gif|jpg|png|JPG|jpeg';
          $config['file_name']     =  $docKey . "." . $ext;
          
          $this->load->library('upload', $config);
          //$this->upload->do_upload('formData');
          if (!$this->upload->do_upload('formData')) {
              $this->upload->display_errors();
          }else{

          } 

      }
		  
          $this->Job_model->create_notifications($this->session->userdata('userId'),$touser['userid'],$job['jobid'],'message',$messageid);
          $mseg = $this->Job_model->msgByid($messageid);
          $messagekey = $mseg['messagekey'];
          
          //if($job['createdby'] != $this->session->userdata('userId')){
            $this->Job_model->create_history($this->session->userdata('userId'),5,$job['jobid'],$touser['userid']);
          //}
          

          /*if($this->values['isfile'] == '1'){
            $inputArr['url'] = $this->values['file'];
            $inputArr['filename'] = 'message.jpg';
            $this->uploadDocs('message',$messageid,$inputArr);
          }
          $user = $this->Api_model->user_by_key($this->session->userdata('userKey'));
          $profileimageurl = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');;
          
          $fileurl = '';
          $docs = $this->Job_model->getdocs($messageid,'message');
          $jDocs = array();
          if(!empty($docs)){
            foreach($docs as $dk=>$d){
              $fileurl = base_url($this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download'));
            }
          } */
          $return['success']= 1;
          $return['messagekey']= $messagekey;
          print json_encode($return);
          exit;
          
	}

	public function checknewmessage(){
		$userkey = $this->input->post('userkey');
		$jobkey = $this->input->post('jobkey');
		$lastkey = $this->input->post('lastkey');
		$message = $this->Job_model->msgBykey($lastkey);
		$job = $this -> Job_model -> jobByKey( $jobkey );
		$touser = $this->Api_model->user_by_key($userkey);
		$newmessages = $this->Job_model->getnewmessages($this->session->userdata('userId'),$touser['userid'],$job['jobid'],$message['messageid']);

		  $msguserids = $msgusers = $msguserdets = $lmArray = array();
           $msguserids[] = $this->session->userdata('userId');
           $msguserids[] = $touser['userid'];
           $isnew = 0;
           if(!empty($msguserids)){
              $msguserdets =  $this->Api_model->getuserdetsbyids(array_unique($msguserids)) ;
              if(!empty($msguserdets)){
                foreach($msguserdets as $uk=>$msguser){
                  $msgusers[$msguser['userid']]['profileimageurl'] = ($msguser['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($msguser['userid'], $msguser['imagekey'], $msguser['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
                  
                  $msgusers[$msguser['userid']]['firstname'] = $msguser['firstname'];
                  $msgusers[$msguser['userid']]['lastname'] = $msguser['lastname'];
                  if($msguser['userid'] == $this->session->userdata('userId')){
                      $msgusers[$msguser['userid']]['iscurrentuser'] = '1';
                      $msgusers[$msguser['userid']]['msgclass'] = 'message-right';
                  }else{
                      $msgusers[$msguser['userid']]['iscurrentuser'] = '0';
                      $msgusers[$msguser['userid']]['msgclass'] = 'message-left';
                  }
                  $msgusers[$msguser['userid']]['userkey'] = $msguser['userkey'];

                  
                }
              }
            }
            $this->Job_model->updatereadmessages($jobDets['jobid'],$this->session->userdata('userId'));
            
            $returnMessage = array();
            if(!empty($newmessages)){
            	$isnew = 1;
              foreach($newmessages as $mk=>$mes){
                $returnMessage[$mk]['message'] = $mes['message'];
                $returnMessage[$mk]['posteddate'] = date('m/d/Y',$mes['createdate']);

                $fileurl = '';
                if($mes['hasdoc'] == '1'){
                  $docs = $this->Job_model->getdocs($mes['messageid'],'message');
                  $jDocs = array();
                  if(!empty($docs)){
                    foreach($docs as $dk=>$d){
                      $fileurl = base_url($this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download'));
                    }
                  }
                }
                $returnMessage[$mk]['completionnote'] = $mes['completionnote'] ;
                $returnMessage[$mk]['hasdoc'] = $mes['hasdoc'] ;
                $returnMessage[$mk]['doc'] = $fileurl;
                $returnMessage[$mk]['messagekey'] = $mes['messagekey'];
                $returnMessage[$mk]['firstname'] = $msgusers[$mes['fromuserid']]['firstname'];
                $returnMessage[$mk]['lastname'] = $msgusers[$mes['fromuserid']]['lastname'];
                $returnMessage[$mk]['profileimageurl'] = $msgusers[$mes['fromuserid']]['profileimageurl'];
                $returnMessage[$mk]['iscurrentuser'] = $msgusers[$mes['fromuserid']]['iscurrentuser'];
                $returnMessage[$mk]['msgclass'] = $msgusers[$mes['fromuserid']]['msgclass'];
                $returnMessage[$mk]['userkey'] = $msgusers[$mes['fromuserid']]['userkey'];
                $lastkey = $mes['messagekey'];
              }
            }

          $return['success']= 1;
          $return['isnew']= 1;
          $return['lastkey']= $lastkey;
          $return['messages']= $returnMessage;
          print json_encode($return);
          exit;
		

	}

	public function getmessage(){

		$userkey = $this->input->post('userkey');
		$jobkey = $this->input->post('jobkey');
		
		
		$job = $this -> Job_model -> jobByKey( $jobkey );
		$touser = $this->Api_model->user_by_key($userkey);
    $newmessages = array();
		//$newmessages = $this->Job_model->getnewmessages($this->session->userdata('userId'),$touser['userid'],$job['jobid'],$message['messageid']);
    //$newmessages = $this->Job_model->getuserJobmessages($touser['userid'],$job['jobid']);
    $newmessages = $this->Job_model->getmessages($this->session->userdata('userId'),$touser['userid'],$job['jobid'],'0');
    //print "<pre>"; print_r($newmessages); print "</pre>";

		  $msguserids = $msgusers = $msguserdets = $lmArray = array();
           $msguserids[] = $this->session->userdata('userId');
           $msguserids[] = $touser['userid'];
           $isnew = 0;
           if(!empty($msguserids)){
              $msguserdets =  $this->Api_model->getuserdetsbyids(array_unique($msguserids)) ;
              if(!empty($msguserdets)){
                foreach($msguserdets as $uk=>$msguser){
                  $msgusers[$msguser['userid']]['profileimageurl'] = ($msguser['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($msguser['userid'], $msguser['imagekey'], $msguser['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
                  
                  $msgusers[$msguser['userid']]['firstname'] = $msguser['firstname'];
                  $msgusers[$msguser['userid']]['lastname'] = $msguser['lastname'];
                  if($msguser['userid'] == $this->session->userdata('userId')){
                      $msgusers[$msguser['userid']]['iscurrentuser'] = '1';
                      $msgusers[$msguser['userid']]['msgclass'] = 'message-right';
                  }else{
                      $msgusers[$msguser['userid']]['iscurrentuser'] = '0';
                      $msgusers[$msguser['userid']]['msgclass'] = 'message-left';
                  }
                  $msgusers[$msguser['userid']]['userkey'] = $msguser['userkey'];

                  
                }
              }
            }
            $this->Job_model->updatereadmessages($jobDets['jobid'],$this->session->userdata('userId'));
            
            $returnMessage = array();
            if(!empty($newmessages)){
            	$isnew = 1;
              foreach($newmessages as $mk=>$mes){
                $returnMessage[$mk]['message'] = $mes['message'];
                $returnMessage[$mk]['posteddate'] = date('m/d/Y',$mes['createdate']);

                
                $fileurl = '';
                if($mes['hasdoc'] == '1'){
                  $docs = $this->Job_model->getdocs($mes['messageid'],'message');
                  $jDocs = array();
                  if(!empty($docs)){
                    foreach($docs as $dk=>$d){
                      $fileurl = base_url($this->corefunctions->getMyPath($d['docid'],$d['dockey'],$d['docext'],'assets/docs/','download'));
                    }
                  }
                }
                
                $returnMessage[$mk]['completionnote'] = $mes['completionnote'] ;
                $returnMessage[$mk]['hasdoc'] = $mes['hasdoc'] ;
                $returnMessage[$mk]['doc'] = $fileurl;
                $returnMessage[$mk]['messagekey'] = $mes['messagekey'];
                $returnMessage[$mk]['firstname'] = $msgusers[$mes['fromuserid']]['firstname'];
                $returnMessage[$mk]['lastname'] = $msgusers[$mes['fromuserid']]['lastname'];
                $returnMessage[$mk]['profileimageurl'] = $msgusers[$mes['fromuserid']]['profileimageurl'];
                $returnMessage[$mk]['iscurrentuser'] = $msgusers[$mes['fromuserid']]['iscurrentuser'];
                $returnMessage[$mk]['msgclass'] = $msgusers[$mes['fromuserid']]['msgclass'];
                $returnMessage[$mk]['userkey'] = $msgusers[$mes['fromuserid']]['userkey'];
                if($lastkey == ''){
                  $lastkey = $mes['messagekey'];
                }
                
              }
            }
          $returnMessage = array_reverse($returnMessage);
          $return['success']= 1;
          $return['isnew']= 1;
          $return['lastkey']= $lastkey;
          $return['messages']= $returnMessage;
          print json_encode($return);
          exit;

	}

    public function notification($type="all"){

    	  $notifications = $this->Job_model->getnotification($this->session->userdata('userId'));

            $jobids = $jobs = $userids = $users =  array();
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

            $notMsg = array('payment'=>'You have received a payment on the job','bid'=>'You have received a bid on the job','jstart'=>'Job Started','jcompleted'=>'Job completed','message'=>'New Message','contract'=>'Your Bid has been Approved.');
            $retNot = array();
            if(!empty($notifications) and !empty($jobs) and !empty($users)){
              foreach($notifications as $nk=>$not){
                if(isset($jobs[$not['jobid']]['jobid'])){
                  $retNot[$nk]['type'] = $not['parenttype'];
                  //$retNot[$nk]['notificationmessage'] = $notMsg[$not['parenttype']];
                  $notify = $this->hedercontroller->getnotificationtext($not['parenttype'],$jobs[$not['jobid']],$users[$not['fromuserid']],$not);
                  $retNot[$nk]['notificationmessage'] = $notify;
                  $retNot[$nk]['link'] = $this->hedercontroller->getnotificationlink($not['parenttype'],$jobs[$not['jobid']],$users[$not['fromuserid']],$not);
                  $retNot[$nk]['jobid'] = $jobs[$not['jobid']]['jobid'];
                  $retNot[$nk]['jobname'] = $jobs[$not['jobid']]['jobname'];
                  $retNot[$nk]['image'] = $jobs[$not['jobid']]['image'];
                  $retNot[$nk]['createdate'] = $not['createdate'];
               }
              }
            }
            $payments = $bids =  array();
            if(!empty($retNot)){
              foreach($retNot as $rk=>$rv){
                if($rv['type'] == 'payment' or $rv['type'] == 'payconfirm'){
                  $payments[] = $rv;
                //}else if($rv['type'] == 'bid'){
                 // $data['bids'][] = $rv;
                }else{
                  $bids[] = $rv;
                }
              }
            }

          if($type == "bids"){
          	$return = $bids;
          }else if($type == "payment"){
          	$return = $payments;
          }else{
          	$return = $retNot;
          }
          $this->Job_model->updateread($this->session->userdata('userId'));
          $data['notification'] = $return;
          $data['type'] = $type;
          $data['subhead']  = 'notification';
      $data['allcounts'] = $this->hedercontroller->allCounts();
		  $headerdata['headerdata'] = $this->hedercontroller->headerCounts();
		  $this->load->view('headers/header',$headerdata);
		  $this->load->view('headers/left-menu');
		  $this->load->view('messages/notification',$data);
		  $this->load->view('headers/footer');
	}

	
	public function accountbalance($type="received"){
		if($this->session->userdata('usertype') == 'homeowner'){
              $contracts = $this->Job_model->getContractforhomeowners($this->session->userdata('userId'));
          }else{
             $contracts = $this->Job_model->getContractforcontractors($this->session->userdata('userId'));
          }
          $userids = $userdets = $jobids = $jobs = $users =  array();
          if(!empty($contracts)){
            foreach($contracts as $ck=>$cont){
              if($this->session->userdata('usertype') == 'homeowner'){
              	//print "<pre>"; print_r($cont);  print "</pre>";

                $userids[] = $cont['contractorid'];
                $contracts[$ck]['userid'] = $cont['contractorid'];
              }else{
                $userids[] = $cont['ownerid'];
                $contracts[$ck]['userid'] = $cont['ownerid'];
              }
              $jobids[] = $cont['jobid'];

            }
          }
          //print "<pre>"; print_r($contracts); print_r($userids); print "</pre>";
          if(!empty($jobids)){
            $jobs = $this->Job_model->getjobsbyids($jobids);
            $jobs = $this->corefunctions->getArrayIndexed($jobs,'jobid');
          }

          if(!empty($userids)){
            $userdets =  $this->Api_model->getuserdetsbyids(array_unique($userids)) ;
            if(!empty($userdets)){
              foreach($userdets as $uk=>$user){
                $users[$user['userid']]['imageurl'] = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
                
                $users[$user['userid']]['firstname'] = $user['firstname'];
                $users[$user['userid']]['lastname'] = $user['lastname'];
              }
            }
          }
          $pending = $recieved = array();

          if(!empty($contracts) and !empty($jobs) and !empty($users)){
            foreach($contracts as $ck=>$cont){
              if($cont['escrow_released'] == '1'){
                $recieved[$ck]['amount'] = $cont['amount'];
                $recieved[$ck]['contractkey'] = $cont['contractkey'];
                $recieved[$ck]['jobname'] = $jobs[$cont['jobid']]['jobname'];
                $recieved[$ck]['firstname'] = $users[$cont['userid']]['firstname'];
                $recieved[$ck]['lastname'] = $users[$cont['userid']]['lastname'];
                $recieved[$ck]['imgurl'] = $users[$cont['userid']]['imageurl'];
              }else{
                $pending[$ck]['amount'] = $cont['amount'];
                $pending[$ck]['jobname'] = $jobs[$cont['jobid']]['jobname'];
                $pending[$ck]['firstname'] = $users[$cont['userid']]['firstname'];
                $pending[$ck]['lastname'] = $users[$cont['userid']]['lastname'];
                $pending[$ck]['imgurl'] = $users[$cont['userid']]['imageurl'];

              }
            }
          }
          if($type=="received"){
          	$data['payments'] = $recieved;
          }else{
          	$data['payments'] = $pending;
          }
          $data['type'] = $type;
          $data['subhead']  = 'payment';
          $data['allcounts'] = $this->hedercontroller->allCounts();
          $headerdata['headerdata'] = $this->hedercontroller->headerCounts();
          $this->load->view('headers/header',$headerdata);
		  $this->load->view('headers/left-menu');
		  $this->load->view('messages/accountbalance',$data);
		  $this->load->view('headers/footer');
	}

  public function paymentdetails(){
      $contract = $this->Job_model->getContractbykey( $this->input->post('contractkey')   );
      $paymentdetails =  $this->Job_model->getpaymentcontractid($contract['contractid']);
      $homeOwnerInfo = $this->Api_model->getUserDetailsDetailsByObjectID($contract['ownerid']);
      $contractor = $this->Api_model->getUserDetailsDetailsByObjectID($contract['contractorid']);
      $job = $this->Job_model->getjobbyid($contract['jobid']);
      /*if(empty($contract) or empty($paymentdetails) or empty($homeOwnerInfo) or empty($contractor) or empty($job)){
        $this->returnError("No records found.");
      }*/
      $serviceamount = $contract['amount'] - $contract['servicefee'];
      $data['jobcompleteddate'] = date("m/d/Y",$contract['completeddate']);
      $data['jobname'] = $job['jobname'];
      $data['amount'] = "$".number_format($contract['amount'],2);
      $data['servicefee'] = "$".number_format($contract['servicefee'],2);
      $data['paymentamount'] = "$".number_format($serviceamount,2);
      $data['paymentdate'] = (!empty($paymentdetails) and $paymentdetails['createdate'] != '0') ? date("m/d/Y",$paymentdetails['createdate']) : '';
     // $data['cardtype'] = (!empty($paymentdetails) and $paymentdetails['cardtype'] != '') ? $paymentdetails['cardtype']: '';
     // $data['cardnumber'] = (!empty($paymentdetails) and $paymentdetails['cardnumber'] != '') ? $paymentdetails['cardnumber']: '';
      $data['contractorname'] = $contractor['firstname'] ." ".$contractor['lastname'][0];
     
      $data['contractorphone'] = $contractor['phone'];
      $data['contractoraddress'] = nl2br($contractor['address'])." <br />".$contractor['city'].", ".$contractor['state']." ".$contractor['zip'];
      $data['carddetails'] = (!empty($paymentdetails) ) ? $paymentdetails['cardtype']. " : ****".$paymentdetails['cardnumber'] : '';
      print json_encode($data,true);
      exit();
      
    }
	
	

	
	  
	}

?>
