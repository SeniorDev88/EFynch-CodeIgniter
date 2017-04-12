<?php
  if (!defined('BASEPATH'))
      exit('No direct script access allowed');
	class JOBS extends CI_Controller {
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
		  
		  $funcName = $this->router->fetch_method();
		if( $funcName != 'getcity' ){
			if( !( $this->session->userdata('userKey') ) ){
				redirect( base_url() );
				exit;
			}
		}
		//error_reporting(E_ALL);
		
		  require_once(APPPATH.'third_party/braintree/lib/Braintree.php');

          Braintree\Configuration::environment(BT_environment);
          Braintree\Configuration::merchantId(BT_merchantId);
          Braintree\Configuration::publicKey(BT_publicKey);
          Braintree\Configuration::privateKey(BT_privateKey);
          //ini_set('display_errors', 1);
		
          
    }

		public function postjob(){
			if( $this->session->userdata('usertype') == 'contractor' ){
				redirect( base_url('dashboard') );
				exit;
			}
			$user_videos = $this->session->userdata('videos');
      $post_video = '';
      if (!empty($user_videos['post_job'])){
      	$job_post_video = $user_videos['post_job'];
      }
		  $states = $this->Api_model->getStates();
		  $expertises = $this->Api_model->getexpertise();
		  if($this->input->post('act') == 1){
		  	  $input         = $this->input->post();
	          //$this->load->helper('geolocation_helper');
	          $address['address']   = $input['address'];
	          $address['city']      = $input['city'];
	          $address['state']     = $input['state'];
	          $address['zip']       = $input['zip'];

	          // Get geolocation
	          //$geolocal = getGoogleGeoLocation($address);
	          $geolocal = array();
	          //echo '<pre>';print_r($input); exit;
	          //$time = str_replace('.', ':', $input['starttime']);
	          $datetime = $input['startdate'];
	          $input['startdate']      = date_format(date_create($datetime), 'Y-m-d H:i');
	          $input['geoLocation']    = $geolocal;
	          $jobkey        = $this->corefunctions->generateUniqueKey('6', 'jobs', 'jobkey');
	          //$expertise     = $this->Api_model->getexpertisebyslug(trim($input['category']));
            
	          $jobid = $this->Job_model->create_job($jobkey, $input['expertiseid'], $input);

	          $contractors = $this->Job_model->searchContractors($address['state'],$input['expertiseid']);

            if(!empty($contractors)){
            	foreach($contractors as $contractor){
            		$this->Job_model->sendJobPostNotification($input,$contractor,$this->session->userdata,$jobid);
            	}
            }
	          $postedJobData = $input;
	          $postedJobData['toname'] = $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname');
	          $userEmail = $this->session->userdata('userEmail');

	          $userId = $this->session->userdata('userId');

                //  echo $jobid;exit;
	          if(!empty( $this->input->post('tempdocs') )){
	          	foreach($this->input->post('tempdocs') as $temp){
	          		  $tempdoc = $this->Job_model->gettempdocbykey($temp);
	          		  $dockey         = $this->corefunctions->generateUniqueKey('6', 'docs', 'dockey');
			          $originalname   = $tempdoc["originalname"];
			          $docext = $tempdoc['tempimgext'];
			          $docid = $this->Job_model->createdoc($dockey,$docext,'job',$jobid,$originalname);
			          $uploadTo       = $this->corefunctions->getMyPath($jobid,$dockey,$docext,'assets/docs/');
			         // $uploadfrom       = $this->corefunctions->getMyPath($docid,$dockey,$docext,'assets/tempdocs/');
					  $uploadfrom	 = "assets/tempImgs/crop/" . $temp . '.' . $tempdoc['tempimgext'];
					  //chmod('assets/docs/', 0777);
			          copy($uploadfrom,$uploadTo);
					  //chmod('assets/docs/', 0755);
	          	}
	          }
				  
				  redirect( base_url('owner/bidding') );
				  exit;
		          
			  }

			  $userDets = $this->Api_model->user_by_key($this->session->userdata('userKey'));
			  $data['userDets'] = $userDets;
			  $headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			  $data['states'] = $states;
			  $data['expertises'] = $expertises;

			   $myjobs = $this->Job_model->getmyjobs($this->session->userdata('userId'));
				if(!empty($myjobs)){
					$activities = $this->hedercontroller->getActivity($myjobs);
				}
				$data['job_post_video'] = $job_post_video;
				$data['right'] = 'dashboard';
				$data['activities'] = $activities;
			  $data['subhead'] 	= 'createjob';
			  $data['allcounts'] = $this->hedercontroller->allCounts();
			  $this->load->view('headers/header',$headerdata);
			  $this->load->view('headers/left-menu');
			  $this->load->view('jobs/post-job',$data);
			  $this->load->view('headers/footer');
		}

		public function getcity(){
			$state_prefix = $this->input->post('state');
			$city = $this->Api_model->getCity($state_prefix);
			$return['city'] = $city;
			print json_encode($return);
    		exit();
		}

		public function docupload(){
			//print "<pre>"; print_r( $_FILES );  print "</pre>"; exit;
			$location        = "assets/tempdocs/";
			$ext                     = pathinfo($_FILES['formData']['name'], PATHINFO_EXTENSION);
			$docKey                  = $this->corefunctions->generateUniqueKey('6', 'tempdocs', 'dockey');
			$config['upload_path']   = $location;
			$config['allowed_types'] = 'gif|jpg|png|JPG|jpeg';
			$config['file_name']     =  $docKey . "." . $ext;
            $this->Job_model->create_tempdocs($docKey,$ext,$_FILES['formData']['name']);
            $this->load->library('upload', $config);
	        if (!$this->upload->do_upload('formData')) {
				$this->upload->display_errors();
	             $return['error'] = 1;
	        } else {
				$return['success'] = 1;
				$return['dockey']  = $docKey;
				$return['docpth']  = base_url($location.$docKey . "." . $ext);
				$return['docname']  = $_FILES['formData']['name'];
			}
			//print "<pre>"; print_r( $_FILES ); print_r($return); print "</pre>"; exit;

			print json_encode($return);
    		exit();
		}
		
		public function editJobPost( $jobkey ){
			if( $this->session->userdata('usertype') == 'contractor' ){
				redirect( base_url('dashboard') );
				exit;
			}
			if( !$jobkey ){
				redirect( base_url('owner/bidding') );
				exit;
			}

			$jobDets = $this -> Job_model -> jobByKey( $jobkey );
			if( empty($jobDets) ){
				redirect( base_url('owner/bidding') );
				exit;
			}
			if($this->session->userdata('userId') != $jobDets['createdby']){
				redirect( base_url('owner/bidding') );
				exit;
			}

			$jobImages = $this -> Job_model -> getdocs($jobDets['jobid'],'job','1');
			$DBImgs = $formImgs = $toDelete = array();
			if( !empty( $jobImages ) ){
				foreach( $jobImages as $ji => $jv ){
					$DBImgs[] = $jv['dockey'];
					$jobImages[$ji]['Img'] = base_url( $this->getBidImage($jv['docid'],$jv['dockey'],$jv['docext']) );
				}
			}

			$expertises = $this->Api_model->getexpertise();
			$expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
			$states = $this->Api_model->getStates();
			$city = $this->Api_model->getCity($jobDets['state']);
			
			if($this->input->post('act') == 1){
				$input         = $this->input->post();
				//$this->load->helper('geolocation_helper');
				$address['address']   = $input['address'];
				$address['city']      = $input['city'];
				$address['state']     = $input['state'];
				$address['zip']       = $input['zip'];

				// Get geolocation
				//$geolocal = getGoogleGeoLocation($address);
				$geolocal = array();
				$time = str_replace('.', ':', $input['starttime']);
				$datetime = $input['startdate'] . ' ' . $time;
				$input['startdate']      = date_format(date_create($datetime), 'Y-m-d H:i');
				$input['geoLocation']    = $geolocal;
				$this->Job_model->updatejobs($input,$jobDets['jobid'],$input['expertiseid']);
				$jobid = $jobDets['jobid'];

				if(!empty( $this->input->post('tempdocs') )){
					foreach($this->input->post('tempdocs') as $temp){
					  $tempdoc = $this->Job_model->gettempdocbykey($temp);
					  $dockey         = $this->corefunctions->generateUniqueKey('6', 'docs', 'dockey');
					  $originalname   = $tempdoc["originalname"];
					  $docext 		  = $tempdoc['tempimgext'];
					  $docid 		  = $this->Job_model->createdoc($dockey,$docext,'job',$jobid,$originalname);
					  $uploadTo       = $this->corefunctions->getMyPath($jobid,$dockey,$docext,'assets/docs/');
					 // $uploadfrom       = $this->corefunctions->getMyPath($docid,$dockey,$docext,'assets/tempdocs/');
					  $uploadfrom	 = "assets/tempImgs/crop/" . $temp . '.' . $tempdoc['tempimgext'];
					  //chmod('assets/docs/', 0777);
					  copy($uploadfrom,$uploadTo);
					  //chmod('assets/docs/', 0755);
					}
				}
				
				if(!empty( $this->input->post('extempdocs') )){
					foreach($this->input->post('extempdocs') as $extemp){
						$formImgs[] = $extemp;
					}
				}
				
				if( !empty( $DBImgs ) ){
					$toDelete = array_diff($DBImgs,$formImgs);
				}

				if( !empty( $toDelete ) ){
					foreach( $toDelete as $di ){
						$this -> Job_model -> updateDocStat($di,'0');
					}
				}
				//print "<pre>"; print_r($toDelete); print "</pre>";
				redirect( base_url('worklist/'.$jobkey) );
				exit;
			}
			$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			$data['allcounts'] = $this->hedercontroller->allCounts();
			//$data['right'] = 'postjob';
			$data['jobkey'] = $jobkey;
			$data['jobDets'] = $jobDets;
			$data['jobImages'] = $jobImages;
			$data['expertises'] = $expertises;
			$data['states'] = $states;
			$data['cities'] = $city;

			$myjobs = $this->Job_model->getmyjobs($this->session->userdata('userId'));
			if(!empty($myjobs)){
				$activities = $this->hedercontroller->getActivity($myjobs);
			}
			$data['right'] = 'dashboard';
			$data['activities'] = $activities;
			$data['subhead'] 	= 'jobpost';

			$this->load->view('headers/header',$headerdata);
			$this->load->view('headers/left-menu');
			$this->load->view('jobs/editjobpost',$data);
			$this->load->view('headers/footer');
		}
		
		public function deleteJobPost(){
			$jobkey = $this->input->post('key');
			$jobDets = $this -> Job_model -> jobByKey( $jobkey );
			if( empty($jobDets) ){
				print "error";
				exit;
			}
			if( $this->input->post('act') == 'statchange' ){
				$this-> Job_model -> deletejob( $jobDets['jobid'] );
				print "success";
				exit;
			}
		}
		
		public function jobBids( $slug_expertise ){
			if( !$slug_expertise ){
				redirect( base_url('dashboard') );
				exit;
			}
			
			$expertises = $this->Api_model->getexpertise();
			$expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
			
			/* Expertise Details from slug */
			$expertiseDetails     = $this -> Api_model -> getexpertisebyslug(trim($slug_expertise));
			
			/* Get Jobs from expertise ID */
			$jobs = $this->Job_model-> getExpertiseJobs($expertiseDetails['expertiseid'],'new');
			$mybids = $this->Job_model->getallmybid($this->session->userdata('userId'));
			if(!empty($jobs) and !empty($mybids )){
				foreach($jobs as $jk=>$j){
					$bidplaced = 0;
					foreach($mybids as $mk=>$m){
						if($m['jobid'] == $j['jobid']){
							$bidplaced = 1;
						}
					}
					$jobs[$jk]['bidplaced'] = $bidplaced;
					if(isset($jobs[$jk])){
						$cont = $this->Job_model->getContractforjob($j['jobid']);
						if(!empty($cont) and $cont['homeowneragree'] == '1'){
							unset($jobs[$jk]);
						}
					}
				}
			}
			if( !empty( $jobs ) ){
				foreach( $jobs as $bd => $bt ){
					$getBidImg = $this -> Job_model -> getBidImage( 'job',$bt['jobid'] );
					$jobs[$bd]['bidImg'] = ( !empty( $getBidImg ) ) ? base_url( $this->getBidImage($bt['jobid'],$getBidImg['dockey'],$getBidImg['docext']) ) : base_url('images/def_job.jpg');
					$bids =  $this->Job_model->getbids($bt['jobid']);
					$total = $highest = $lowest = $average = $tcnt =0;
					$jobs[$bd]['numberofbids'] = $tcnt;
					$jobs[$bd]['highestbid'] = $highest;
					$jobs[$bd]['lowestbid'] = $lowest;
					$jobs[$bd]['averagebid'] = $average;
					if(!empty($bids)){
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
						$jobs[$bd]['numberofbids'] = $tcnt;
						$jobs[$bd]['highestbid'] = round($highest,2);
						$jobs[$bd]['lowestbid'] = round($lowest,2);
						$jobs[$bd]['averagebid'] = round($average,2);
					}
				}
			}
			$data['jobbids'] = 1;
			$data['jobs'] = $jobs;
			$data['expertises'] = $expertises;

			$data['frompage'] = 'board';
			$data['subhead'] 	= 'jobpost';

			$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			$data['allcounts'] = $this->hedercontroller->allCounts();
			$this->load->view('headers/header',$headerdata);
			$this->load->view('headers/left-menu');
			$this->load->view('contractor/contractor-bids',$data);
			$this->load->view('headers/footer');
		}
		
		public function myBids(){
			
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
			
			$expertises = $this->Api_model->getexpertise();
			$expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
          
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
            	$mybids = $this->corefunctions->getArrayIndexed($mybids,'jobid');
				foreach ($jobs as $key => $value) {
					$jobs[$key]['contractorsetails'] = $contractorsetails[$value['createdby']];
					$getBidImg = $this -> Job_model -> getBidImage( 'job',$value['jobid'] );
					$jobs[$key]['bidImg'] = ( !empty( $getBidImg ) ) ? base_url( $this->getBidImage($value['jobid'],$getBidImg['dockey'],$getBidImg['docext']) ) : base_url('images/def_job.jpg');
					$bids =  $this->Job_model->getbids($value['jobid']);
					$total = $highest = $lowest = $average = $tcnt =0;
					$jobs[$key]['numberofbids'] = $tcnt;
					$jobs[$key]['highestbid'] = round($highest,2);
					$jobs[$key]['lowestbid'] = round($lowest,2);
					$jobs[$key]['averagebid'] = round($average,2);
					$jobs[$key]['bidamount'] = $mybids[$value['jobid']]['bidamount'];
					if(!empty($bids)){
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
						$jobs[$key]['numberofbids'] = $this->Job_model->getbidcount($value['jobid']);
						$jobs[$key]['highestbid'] = round($highest,2);
						$jobs[$key]['lowestbid'] = round($lowest,2);
						$jobs[$key]['averagebid'] = round($average,2);
					}
				}
			}
			
			$data['jobs'] = $jobs;
			$data['expertises'] = $expertises;
			$data['jobbids'] = 0;

			$data['frompage'] = 'mybid';
			$data['subhead'] 	= 'mybid';
			$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			$data['allcounts'] = $this->hedercontroller->allCounts();
			$this->load->view('headers/header',$headerdata);
			$this->load->view('headers/left-menu');
			$this->load->view('contractor/contractor-bids',$data);
			$this->load->view('headers/footer');
		}
		
		public function bidJob( $jobkey ){
			if( !$jobkey ){
				redirect( base_url('dashboard') );
				exit;
			}
			$expertises = $this->Api_model->getexpertise();
			$expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
			
			$jobDets = $this -> Job_model -> jobByKey( $jobkey );
			if( empty($jobDets) ){
				redirect( base_url('owner/bidding') );
				exit;
			}
			$jobImages = $this -> Job_model -> getdocs($jobDets['jobid'],'job','1');
			if( !empty( $jobImages ) ){
				foreach( $jobImages as $ji => $jv ){
					$jobImages[$ji]['Img'] = base_url( $this->getBidImage($jobDets['jobid'],$jv['dockey'],$jv['docext']) );
				}
			}
			$bidDets = $this->Job_model->getmybid($jobDets['jobid'],$this->session->userdata('userId'));
			$contract = $this->Job_model->getContract($jobDets['jobid'],$this->session->userdata('userId'));
			$homenowneragree = '0';
			
			if(!empty($contract) ){
				if(($contract['homeowneragree'] == "1" or $contract['homeowneragree'] == 1) and ($contract['workeragree'] == "0" or $contract['workeragree'] == 0)){
					$homenowneragree = '1';
				}
			}

			$data['deleteedit'] = ($contract['homeowneragree'] == "1" or $contract['homeowneragree'] == 1) ? '0' : '1';
			
			$bids =  $this->Job_model->getbids($jobDets['jobid']);
			$bidCountDets = $cuserids = array();
			$total = $highest = $lowest = $average = $tcnt =0;
			$bidCountDets['numberofbids'] = $tcnt;
			$bidCountDets['highestbid'] = $highest;
			$bidCountDets['lowestbid'] = $lowest;
			$bidCountDets['averagebid'] = $average;
			if(!empty($bids)){
				$lowest = $bids[0]['bidamount'];
				foreach($bids as $bi){
					$cuserids[] = $bi['userid'];
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
				$bidCountDets['numberofbids'] = $this->Job_model->getbidcount($jobDets['jobid']);
				$bidCountDets['highestbid'] = round($highest,2);
				$bidCountDets['lowestbid'] = round($lowest,2);
				$bidCountDets['averagebid'] = round($average,2);
				
				$cdetails =  $this->Api_model->getuserdetsbyids(array_unique($cuserids)) ;
				if(!empty($cdetails)){
					$cdetails = $this->corefunctions->getArrayIndexed($cdetails,'userid');
					foreach($cdetails as $uk=>$user){
						$cdetails[$uk]['imageurl'] = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
						$cdetails[$uk]['staring'] = $this->userrating($user['userid']);;
					}
				}
			}

			$typearr = array('h'=>'Hour(s)','w'=>'Week(s)','d'=>'Day(s)');
			$bid['amount'] = number_format($bidDets['bidamount'],2);
			$bid['startingtime'] = ($bidDets['startdate'] != '0000-00-00') ? date('m/d/Y',strtotime($bidDets['startdate'])) : '--';
			$bid['addamount'] = number_format($bidDets['additionalamount'],2);
			$bid['expectedtime'] = ($bidDets['exptime'] != '' and $bidDets['exptime'] != '0') ?  $bidDets['exptime']." ".$typearr[$bidDets['exptype']] : '--';
			$bid['maximumtime'] = ($bidDets['maxtime'] != '' and $bidDets['maxtime'] != '0') ?  $bidDets['maxtime']." ".$typearr[$bidDets['maxtype']] : '--';
			$bid['description'] = $bidDets['description'];
			$data['userDets'] = $bid;
			

			$userDets = $this -> User_model -> user_by_key( $this->session->userdata('userKey') );

			$jobcontracts = $this->Job_model->getContractforjob($jobDets['jobid']);
			$data['showbidnow'] = (empty($jobcontracts ) or $jobcontracts['contractorid'] == $this->session->userdata('userId') or (!empty($jobcontracts ) and $jobcontracts['homeowneragree'] == '0')) ? "1" : "0";

			$user_videos = $this->session->userdata('videos');
			$respond_bid_video = '';
      if (!empty($user_videos['respond_bid'])){
      	$respond_bid_video = $user_videos['respond_bid'];
      }

	    $data['respond_bid_video'] = $respond_bid_video;
			$data['userDets'] = $userDets;
			$data['right'] = 'bidnow';
			$data['expertises'] = $expertises;
			$data['jobDets'] = $jobDets;
			$data['jobImages'] = $jobImages;
			$data['bidDets'] = $bidDets;
			$data['homenowneragree'] = $homenowneragree;
			$data['contract'] = $contract;
			$data['bidCountDets'] = $bidCountDets;
			$data['bidUsers'] = $cdetails;
			$data['showalert'] = '1';
			$data['homeOwner']  = $this -> Api_model->getUserDetailsDetailsByObjectID( $jobDets['createdby'] );
			$data['allcounts'] = $this->hedercontroller->allCounts();
			$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			$data['subhead'] 	= 'jobpost';
			$this->load->view('headers/header',$headerdata);
			$this->load->view('headers/left-menu');
			$this->load->view('contractor/contractor-bid-now',$data);
			$this->load->view('headers/footer');
		}

		public function biddedJob( $jobkey ){
			if( !$jobkey ){
				redirect( base_url('dashboard') );
				exit;
			}
			$expertises = $this->Api_model->getexpertise();
			$expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
			
			$jobDets = $this -> Job_model -> jobByKey( $jobkey );
			if( empty($jobDets) ){
				redirect( base_url('owner/bidding') );
				exit;
			}
			$jobImages = $this -> Job_model -> getdocs($jobDets['jobid'],'job','1');
			if( !empty( $jobImages ) ){
				foreach( $jobImages as $ji => $jv ){
					$jobImages[$ji]['Img'] = base_url( $this->getBidImage($jobDets['jobid'],$jv['dockey'],$jv['docext']) );
				}
			}
			$bidDets = $this->Job_model->getmybid($jobDets['jobid'],$this->session->userdata('userId'));
			$contract = $this->Job_model->getContract($jobDets['jobid'],$this->session->userdata('userId'));
			$homenowneragree = '0';
			if(!empty($contract) ){
				if(($contract['homeowneragree'] == "1" or $contract['homeowneragree'] == 1) and ($contract['workeragree'] == "0" or $contract['workeragree'] == 0)){
					$homenowneragree = '1';
				}
			}
			$data['deleteedit'] = ($contract['homeowneragree'] == "1" or $contract['homeowneragree'] == 1) ? '0' : '1';
			$bids =  $this->Job_model->getbids($jobDets['jobid']);
			$bidCountDets = $cuserids = array();
			$total = $highest = $lowest = $average = $tcnt =0;
			$bidCountDets['numberofbids'] = $tcnt;
			$bidCountDets['highestbid'] = $highest;
			$bidCountDets['lowestbid'] = $lowest;
			$bidCountDets['averagebid'] = $average;
			if(!empty($bids)){
				$lowest = $bids[0]['bidamount'];
				foreach($bids as $bi){
					$cuserids[] = $bi['userid'];
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
				$bidCountDets['numberofbids'] = $this->Job_model->getbidcount($jobDets['jobid']);
				$bidCountDets['highestbid'] = round($highest,2);
				$bidCountDets['lowestbid'] = round($lowest,2);
				$bidCountDets['averagebid'] = round($average,2);
				
				$cdetails =  $this->Api_model->getuserdetsbyids(array_unique($cuserids)) ;
				if(!empty($cdetails)){
					$cdetails = $this->corefunctions->getArrayIndexed($cdetails,'userid');
					foreach($cdetails as $uk=>$user){
						$cdetails[$uk]['imageurl'] = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
						$cdetails[$uk]['staring'] = $this->userrating($user['userid']);;
					}
				}
			}

			$userDets = $this -> User_model -> user_by_key( $this->session->userdata('userKey') );

			$typearr = array('h'=>'Hour(s)','w'=>'Week(s)','d'=>'Day(s)');
			$bid['amount'] = number_format($bidDets['bidamount'],2);
			$bid['startingtime'] = ($bidDets['startdate'] != '0000-00-00') ? date('m/d/Y',strtotime($bidDets['startdate'])) : '--';
			$bid['addamount'] = number_format($bidDets['additionalamount'],2);
			$bid['expectedtime'] = ($bidDets['exptime'] != '' and $bidDets['exptime'] != '0') ?  $bidDets['exptime']." ".$typearr[$bidDets['exptype']] : '--';
			$bid['maximumtime'] = ($bidDets['maxtime'] != '' and $bidDets['maxtime'] != '0') ?  $bidDets['maxtime']." ".$typearr[$bidDets['maxtype']] : '--';
			$bid['description'] = $bidDets['description'];
			$data['bid'] = $bid;

			$data['userDets'] = $userDets;
			$data['right'] = 'bidnow';
			$data['expertises'] = $expertises;
			$data['jobDets'] = $jobDets;
			$data['jobImages'] = $jobImages;
			$data['bidDets'] = $bidDets;
			$data['homenowneragree'] = $homenowneragree;
			$data['contract'] = $contract;
			$data['bidCountDets'] = $bidCountDets;
			$data['bidUsers'] = $cdetails;

			$data['showalert'] = '0';

			$jobcontracts = $this->Job_model->getContractforjob($jobDets['jobid']);
			$data['showbidnow'] = (empty($jobcontracts ) or $jobcontracts['contractorid'] == $this->session->userdata('userId')) ? "1" : "0";

			$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			$data['allcounts'] = $this->hedercontroller->allCounts();
			$data['homeOwner']  = $this -> Api_model->getUserDetailsDetailsByObjectID( $jobDets['createdby'] );
			$data['subhead'] 	= 'jobpost';
			$this->load->view('headers/header',$headerdata);
			$this->load->view('headers/left-menu');
			$this->load->view('contractor/contractor-bid-now',$data);
			$this->load->view('headers/footer');
		}
		
		public function createBid( $jobkey ){
			if( !$jobkey ){
				redirect( base_url('dashboard') );
				exit;
			}
			$expertises = $this->Api_model->getexpertise();
			$expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
			
			$jobDets = $this -> Job_model -> jobByKey( $jobkey );
			if( empty($jobDets) ){
				redirect( base_url('bidjob/'.$jobkey) );
				exit;
			}
			
			if( $this->input->post('act') == 1 ){
				//$this->form_validation->set_rules('description', 'Description', 'required');
	            $this->form_validation->set_rules('bidamount', 'Bid Amount', 'required');
	            /*$this->form_validation->set_rules('additionalamount', 'Additional Amount', 'required');
	            $this->form_validation->set_rules('startdate', 'Start Date', 'required');
	            $this->form_validation->set_rules('starttime', 'Start Time', 'required');
	            $this->form_validation->set_rules('exptime', 'Expected Time', 'required');
	            $this->form_validation->set_rules('exptype', 'Expected Time', 'required');
	            $this->form_validation->set_rules('maxtime', 'Maximum Time', 'required');
	            $this->form_validation->set_rules('maxtype', 'Maximum Time', 'required'); */
	            if ($this->form_validation->run() === TRUE) {
					
					$data['bidkey'] 			= $this->corefunctions->generateUniqueKey('10', 'bids', 'bidkey');
					$data['jobid'] 				= $jobDets['jobid'];
					$data['userid'] 			= $this->session->userdata('userId');
					$data['bidamount'] 			= $this->input->post('bidamount');
					$data['additionalamount']	= ($this->input->post('additionalamount') != '') ? $this->input->post('additionalamount') : '';
					$data['description']		= ($this->input->post('description') != '') ? $this->input->post('description') : '';
					$data['exptime'] 			= ($this->input->post('exptime') != '') ? $this->input->post('exptime') : '';
					$data['exptype'] 			= ($this->input->post('exptype') != '') ? $this->input->post('exptype') : '';
					$data['maxtime'] 			= ($this->input->post('maxtime') != '') ? $this->input->post('maxtime') : '';
					$data['maxtype'] 			= ($this->input->post('maxtype') != '') ? $this->input->post('maxtype') : '';
					$data['startdate'] 			= ($this->input->post('startdate') != "") ? date('Y-m-d',strtotime($this->input->post('startdate'))) : '0000-00-00';
					$data['starttime']    		= ($this->input->post('starttime') != "") ? date("H:i", strtotime(str_replace(".",":",$this->input->post('starttime')))) : '';
					
					$bidid = $this->Job_model->addBid( $data );


					$this->Job_model->create_notifications($this->session->userdata('userId'),$jobDets['createdby'],$jobDets['jobid'],'bid',$bidid);
  				$this->Job_model->create_history($this->session->userdata('userId'),1,$jobDets['jobid'],$jobDets['createdby']);

  				$userDets = $this -> Api_model->getUserDetailsDetailsByObjectID( $jobDets['createdby'] );
  				$data['jobname'] = $jobDets['jobname'];
  				$data['bidDets'] = $data;
  				$data['expertise'] = $expertises[$jobDets['expertiseid']]['name'];
  				$data['toname'] = $userDets['firstname'] ." ".$userDets['lastname'];
  				$data['fromname'] = $this->session->userdata('userFirstname')." ".$this->session->userdata('userLastname');
  				
  				$userData['toname'] = $this->session->userdata('userFirstname')." ".$this->session->userdata('userLastname');
  				$email = $this->session->userdata('userEmail');
  				$msg   = $this->load->view('mail/contractor_bid', $userData, true);
					$this->corefunctions->sendmail(ADMINEMAIL, $email, TITLE . ' :: Bid Placed', $msg);

					/*$this->load->library('email');
					$config['mailtype'] = 'html';

					$this->email->initialize($config);

					$this->email->from(ADMINEMAIL, 'Efynch.com');
					$this->email->to($userDets['email']);
					
					$this->email->subject(TITLE . ' :: Bid');
					$this->email->message($msg);

					$this->email->send(); */
					
					redirect( base_url('mybids') );
					exit;
				}else{
					$data['haserror']       = TRUE;
                    $data['errormsg']       = "Please enter required fields.";
				}
			}

			
			
			$data['jobkey'] = $jobkey;
			$data['jobDets'] = $jobDets;
			$data['allcounts'] = $this->hedercontroller->allCounts();
			$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			$data['subhead'] 	= 'jobpost';
			$this->load->view('headers/header',$headerdata);
			$this->load->view('headers/left-menu');
			$this->load->view('contractor/createbid',$data);
			$this->load->view('headers/footer');
		}
		
		public function editBid( $bidkey ){
			if( !$bidkey ){
				redirect( base_url('mybids') );
				exit;
			}

			$bidDets = $this -> Job_model -> bidByKey( $bidkey );
			if( empty($bidDets) ){
				redirect( base_url('mybids') );
				exit;
			}
			if($this->session->userdata('userId') != $bidDets['userid']){
				redirect( base_url('mybids') );
				exit;
			}
			$jobDets = $this -> Job_model -> getjobbyid( $bidDets['jobid'] );
			
			if( $this->input->post('act') == 1 ){
				//$this->form_validation->set_rules('description', 'Description', 'required');
	            $this->form_validation->set_rules('bidamount', 'Bid Amount', 'required');
	           // $this->form_validation->set_rules('additionalamount', 'Additional Amount', 'required');
	            //$this->form_validation->set_rules('startdate', 'Start Date', 'required');
	            //$this->form_validation->set_rules('starttime', 'Start Time', 'required');
	            //$this->form_validation->set_rules('exptime', 'Expected Time', 'required');
	            //$this->form_validation->set_rules('exptype', 'Expected Time', 'required');
	            //$this->form_validation->set_rules('maxtime', 'Maximum Time', 'required');
	            //$this->form_validation->set_rules('maxtype', 'Maximum Time', 'required');
	            if ($this->form_validation->run() === TRUE) {
					
					$data['bidamount']    = $this->input->post('bidamount');
					$data['additionalamount']	= ($this->input->post('additionalamount') != '') ? $this->input->post('additionalamount') : '';
					$data['description']		= ($this->input->post('description') != '') ? $this->input->post('description') : '';
					$data['exptime'] 			= ($this->input->post('exptime') != '') ? $this->input->post('exptime') : '';
					$data['exptype'] 			= ($this->input->post('exptype') != '') ? $this->input->post('exptype') : '';
					$data['maxtime'] 			= ($this->input->post('maxtime') != '') ? $this->input->post('maxtime') : '';
					$data['maxtype'] 			= ($this->input->post('maxtype') != '') ? $this->input->post('maxtype') : '';
					$data['startdate'] 			= ($this->input->post('startdate') != "") ? date('Y-m-d',strtotime($this->input->post('startdate'))) : '0000-00-00';
					$data['starttime']    		= ($this->input->post('starttime') != "") ? date("H:i", strtotime(str_replace(".",":",$this->input->post('starttime')))) : '';
					

					$this->Job_model->editBidByKey($data,$bidDets['bidkey']);
					
					redirect( base_url('biddedjob/'.$jobDets['jobkey']) );
					exit;
				}else{
					$data['haserror']       = TRUE;
                    $data['errormsg']       = "Please enter required fields.";
				}
			}

			$data['bidkey'] = $bidkey;
			$data['bidDets'] = $bidDets;

			$servicefee = $this->hedercontroller->getserviceamount($bidDets['bidamount']);
			$amountreceive = round( $bidDets['bidamount'] - $servicefee ,2);

			$data['servicefee'] = number_format($servicefee,2);
			$data['amountreceive'] = number_format($amountreceive,2);

			$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			$data['allcounts'] = $this->hedercontroller->allCounts();
			$data['subhead'] 	= 'mybid';
			$this->load->view('headers/header',$headerdata);
			$this->load->view('headers/left-menu');
			$this->load->view('contractor/editbid',$data);
			$this->load->view('headers/footer');
		}
		
		public function deleteBid(){
			$bidkey = $this->input->post('key');
			$bidDets = $this -> Job_model -> bidByKey( $bidkey );
			if( empty($bidDets) ){
				print "error";
				exit;
			}
			if( $this->input->post('act') == 'statchange' ){
				$this->Job_model->deleteBidByKey($bidDets['bidkey']);
				print "success";
				exit;
			}
		}
		
		public function favouriteBid(){
			$bidkey = $this->input->post('key');
			$tofav = $this->input->post('tofav');
			$bidDets = $this -> Job_model -> bidByKey( $bidkey );
			if( empty($bidDets) ){
				print "error";
				exit;
			}
			if( $this->input->post('act') == 'fav' ){
				$this->Job_model->bidFavourite($bidDets['bidkey'],$tofav);
				print "success";
				exit;
			}
		}
		
		public function awardWork($userkey,$jobkey){
			if( !$userkey or !$jobkey ){
				redirect( base_url('worklist/'.$jobkey) );
				exit;
			}
			
			$expertises = $this->Api_model->getexpertise();
			$expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
			
			$jobDets = $this -> Job_model -> jobByKey( $jobkey );
			if( empty($jobDets) ){
				redirect( base_url('worklist/'.$jobkey) );
				exit;
			}
			$userDets = $this -> User_model -> user_by_key( $userkey );
			if( empty($jobDets) ){
				redirect( base_url('worklist/'.$jobkey) );
				exit;
			}
			if($this->session->userdata('userId') != $jobDets['createdby']){
				redirect( base_url('worklist/'.$jobkey) );
				exit;
			}

			$userDets['Img'] = ( $userDets['imagekey'] != '' ) ? base_url($this->corefunctions->getMyPath($userDets['userid'], $userDets['imagekey'], $userDets['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
			$jobImages = $this -> Job_model -> getdocs($jobDets['jobid'],'job','1');
			if( !empty( $jobImages ) ){
				foreach( $jobImages as $ji => $jv ){
					$jobImages[$ji]['Img'] = base_url( $this->getBidImage($jobDets['jobid'],$jv['dockey'],$jv['docext']) );
				}
			}
			$userDets['bidDets'] = $this->Job_model->getmybid($jobDets['jobid'],$userDets['userid']);
			$userDets['staring'] = $this->userrating($userDets['userid']);
			
			if( $this->input->post('act') == 1 ){
				$mybid = $this->Job_model->getmybid($jobDets['jobid'],$userDets['userid']);
				$contract = $this->Job_model->getContract($jobDets['jobid'],$userDets['userid']);
				if(empty($contract)){
				  $this->Job_model->updateothercontracts($jobDets['jobid']);
				  $contractkey = $this->corefunctions->generateUniqueKey('6', 'contracts', 'contractkey');
				  $contractid = $this->Job_model->create_contracts($contractkey,$this->session->userdata('userId'),$userDets['userid'],$mybid['bidid'],$jobDets['jobid'],$mybid['bidamount']);
				  $message = "You have received the contract.";
				  $this->Job_model->create_message($message,$this->session->userdata('userId'),$userDets['userid'],$jobDets['jobid']);
				}else{
				  $contractkey = $contract['contractkey'];
				}
				
				redirect( base_url('agreecontract/'.$contractkey) );
				exit;
			}

			$user_videos = $this->session->userdata('videos');
      $hire_video = '';
      if (!empty($user_videos['hire'])){
      	$hire_video = $user_videos['hire'];
      }

      $data['hire_video'] 	= $hire_video;
			$data['subhead'] 	= 'jobpost';
			$data['right'] = 'contractinfo';
			$data['awardwork'] = '1';
			$data['userDets'] = $userDets;
			$data['jobDets'] = $jobDets;
			$data['jobImages'] = $jobImages;
			$data['expertises'] = $expertises;
			$data['allcounts'] = $this->hedercontroller->allCounts();
			$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			
			$this->load->view('headers/header',$headerdata);
			$this->load->view('headers/left-menu');
			$this->load->view('jobs/awardwork',$data);
			$this->load->view('headers/footer');
		}
		
		public function agreeContract($contractkey){
			if( !$contractkey ){
				if( $this->session->userdata('usertype') == 'homeowner' ){
					redirect( base_url('owner/bidding') );
					exit;
				}else{
					redirect( base_url('mybids') );
					exit;
				}
			}
			
			$expertises = $this->Api_model->getexpertise();
			$expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
			
			$contractDets = $this -> Job_model -> getContractbykey( $contractkey );
			if( empty($contractDets) ){
				redirect( base_url('owner/bidding') );
				exit;
			}
			
			$jobDets = $this -> Job_model -> getjobbyid( $contractDets['jobid'] );
			if( empty($jobDets) ){
				if( $this->session->userdata('usertype') == 'homeowner' ){
					redirect( base_url('owner/bidding') );
					exit;
				}else{
					redirect( base_url('mybids') );
					exit;
				}
			}
			$userDets = $this -> User_model -> getuserdetsbyid( $contractDets['contractorid'] );
			if( empty($userDets) ){
				if( $this->session->userdata('usertype') == 'homeowner' ){
					redirect( base_url('owner/bidding') );
					exit;
				}else{
					redirect( base_url('mybids') );
					exit;
				}
			}
			if(($this->session->userdata('userId') != $jobDets['createdby'] and $this->session->userdata('usertype') == 'homeowner' ) or ( $this->session->userdata('userId') != $contractDets['ownerid'] and $this->session->userdata('usertype') == 'homeowner') or ( $this->session->userdata('userId') != $contractDets['contractorid'] and $this->session->userdata('usertype') == 'contractor')){
				if( $this->session->userdata('usertype') == 'homeowner' ){
					redirect( base_url('owner/bidding') );
					exit;
				}else{
					redirect( base_url('mybids') );
					exit;
				}
			}

				$user_videos = $this->session->userdata('videos');
	      $accpet_contract_video = '';
	      if (!empty($user_videos['accpet_contract'])){
	      	$accpet_contract_video = $user_videos['accpet_contract'];
	      }

			//if($userDets['bt_merchantid'] != ""){
				$ownerDets = $this -> User_model -> getuserdetsbyid( $contractDets['ownerid'] );
				$userDets['Img'] = ( $userDets['imagekey'] != '' ) ? base_url($this->corefunctions->getMyPath($userDets['userid'], $userDets['imagekey'], $userDets['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
				$bid = $this->Job_model->getmybid($contractDets['jobid'],$contractDets['contractorid']);
				$data['accpet_contract_video'] = $accpet_contract_video;
				$data['userDets'] = $userDets;
				$data['ownerDets'] = $ownerDets;
				$data['jobDets'] = $jobDets;
				$data['bid'] = $bid;
				$data['contractDets'] = $contractDets;
				
				$this->load->view('apipayment/homeownercontract',$data);

			/*}else{
				$data['headerdata'] = $this->hedercontroller->headerCounts();
				$data['from'] = 'web';
				$this->load->view('apipayment/contractfail',$data);
			} */
			
		}
		
		public function reviewContract($contractkey){
			if( !$contractkey ){
				redirect( base_url('owner/bidding') );
				exit;
			}
			
			$expertises = $this->Api_model->getexpertise();
			$expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
			
			$contractDets = $this -> Job_model -> getContractbykey( $contractkey );
			if( empty($contractDets) ){
				redirect( base_url('owner/bidding') );
				exit;
			}
			$jobDets = $this -> Job_model -> getjobbyid( $contractDets['jobid'] );
			if( empty($jobDets) ){
				redirect( base_url('owner/bidding') );
				exit;
			}
			$userDets = $this -> User_model -> getuserdetsbyid( $contractDets['contractorid'] );
			if( empty($userDets) ){
				redirect( base_url('owner/bidding') );
				exit;
			}

			if($this->session->userdata('userId') != $jobDets['createdby'] or ( $this->session->userdata('userId') != $contractDets['ownerid'] )){
				redirect( base_url('owner/bidding') );
				exit;
			}
			$userDets['Img'] = ( $userDets['imagekey'] != '' ) ? base_url($this->corefunctions->getMyPath($userDets['userid'], $userDets['imagekey'], $userDets['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
			$userDets['bidDets'] = $this->Job_model->getmybid($jobDets['jobid'],$userDets['userid']);
			
			$data['userDets'] = $userDets;
			$data['jobDets'] = $jobDets;
			$data['contractDets'] = $contractDets;
			
			$this->load->view('apipayment/reviewcontract',$data);
		}
		
		public function agreeTerms(){
			$jobkey = $this->input->post('key');
			$jobDets = $this -> Job_model -> jobByKey( $jobkey );
			if( empty($jobDets) ){
				print "error";
				exit;
			}
			if( $this->input->post('act') == 'agree' ){
				$contract = $this->Job_model->getContractforjob($jobDets['jobid']);
				if(empty($contract)){
					print "error";
					exit;
				}
				if($this->session->userdata('usertype') == 'homeowner'){

					$this->Job_model->updateContractorfields('homeowneragree',$contract['contractkey']);
					$params['jobid'] = $contract['jobid'];
        	$params['jobtype'] = "bidplaced";
       		$this->Job_model->create_notifications($contract['ownerid'],$contract['contractorid'],$contract['jobid'],'contract',$contract['contractid'],$params);

	       		$job_data['toname'] = $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname');
	          $userEmail = $this->session->userdata('userEmail');
	          $msg   = $this->load->view('mail/hire', $job_data, true);
	          $this->corefunctions->sendmail(ADMINEMAIL, $userEmail, TITLE . ' :: Job Post', $msg);
       		print "success";
					exit;
				}else{
					$this->Job_model->updateContractorfields('workeragree',$contract['contractkey']);
					$this->Job_model->updateJobfields('jobstatus','inprogress',$contract['jobid']);

					$this->Job_model->create_notifications($this->session->userdata('userId'),$contract['ownerid'],$contract['jobid'],'jstart',$contract['contractid']);
					$this->Job_model->create_history($this->session->userdata('userId'),3,$jobDets['jobid'],$contract['ownerid']);
					$userDets = $this -> User_model -> getuserdetsbyid( $contract['contractorid'] );
					if($userDets['bt_accountverified'] == '1'){
						print "success";
					}else{
						print "account";
					}
					
					exit;
				}
				
				
			}
		}
		
		public function completeJob(){
			$jobkey = $this->input->post('key');
			$jobDets = $this -> Job_model -> jobByKey( $jobkey );
			if( empty($jobDets) ){
				print "error";
				exit;
			}
			if( $this->input->post('act') == 'complete' ){
				$this->Job_model->updateJobfields('jobstatus','completed',$jobDets['jobid']);
				$this->Job_model->updateJobfields('completeddate',date('Y-m-d'),$jobDets["jobid"]);
				$contract = $this->Job_model->getContract($jobDets["jobid"],$this->session->userdata('userId'));
				$this->Job_model->updateContractorfields('completed',$contract['contractkey']);
				$this->Job_model->create_notifications($contract['contractorid'],$contract['ownerid'],$contract['jobid'],'jcomplete',$contract['contractid']);
				$this->Job_model->create_history($this->session->userdata('userId'),4,$jobDets['jobid'],$contract['ownerid']);
				parse_str($this->input->post('formdata'),$input); 
				//print_r($input);
				$message = $input['comment'];
				$this->Job_model->create_message($message,$this->session->userdata('userId'),$contract['ownerid'],$contract['jobid'],'0','1');
				if(!empty($input['tempdocs'])){
					foreach($input['tempdocs'] as $temp){
						  $messageid = $this->Job_model->create_message('',$this->session->userdata('userId'),$contract['ownerid'],$contract['jobid'],'1','1');
						  $tempdoc = $this->Job_model->gettemperorydocbykey($temp);
						  $dockey         = $this->corefunctions->generateUniqueKey('6', 'docs', 'dockey');
				          $originalname   = $tempdoc["originalname"];
				          $docext = $tempdoc['docext'];
				          $docid = $this->Job_model->createdoc($dockey,$docext,'message',$messageid,$originalname);
				          $uploadTo       = $this->corefunctions->getMyPath($docid,$dockey,$docext,'assets/docs/');
						  $uploadfrom	 = "assets/tempdocs/" . $temp . '.' . $tempdoc['docext'];
						  
				          copy($uploadfrom,$uploadTo);
					}
				}
	
				print "success";
				exit;
			}
			
			if( $this->input->post('act') == 'verify' ){
				$userkey = $this->input->post('userkey');
				$star = $this->input->post('star');
				$userDets = $this->User_model->user_by_key($userkey);
				$contract = $this->Job_model->getContract($jobDets['jobid'],$userDets['userid']);
				
				$escresult = array();
				//$transaction = Braintree_Transaction::find($contract['bt_transaction_id']);
				//$result = Braintree_Transaction::submitForSettlement($contract['bt_transaction_id']);
				
				$escresult = Braintree_Transaction::releaseFromEscrow($contract['bt_transaction_id']);
				$bresponse = json_encode($escresult);
				$this->Job_model->create_braintree_response($contract['ownerid'],$contract['contractorid'],$contract['jobid'],$escresult,'release'); 

				//print "<pre>"; print_r($transaction); print_r($escresult); print "</pre>";exit;
				$input['type'] = 'release from web';
				$input['contract'] = $contract;
				$input['bt_result'] = $escresult;
				$input['date'] = date('m/d/Y H:i:s A');
				$this->Job_model-> braintreeresponse($input);
				 
				 //$result = Braintree_Transaction::releaseFromEscrow($contract['bt_transaction_id']);
				 if($escresult->success){
					 $this->Job_model->updateContractorfields('escrow_released',$contract['contractkey']);
					 $this->Job_model->updateJobfields('jobstatus','verified',$jobDets['jobid']);
					 $this->Job_model->create_rating($star,$this->session->userdata('userId'),$userDets['userid'],$jobDets['jobid']);
					 $this->Job_model->create_notifications($contract['ownerid'],$contract['contractorid'],$contract['jobid'],'payment',$contract['contractid']);
					 $this->Job_model->create_notifications($contract['ownerid'],$contract['ownerid'],$contract['jobid'],'payconfirm',$contract['contractid']);
					 print "success";
				}else{
					print "error";
				}
	
				
				exit;
			}
		}

		private function getBidImage($parentid,$key,$ext){
			return ( $key != '' ) ? $this->corefunctions->getMyPath($parentid,$key,$ext,'assets/docs/','download') : base_url('images/def_job.jpg');
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
			return $rating;
		}

		public function showbidinfo(){
			$bidkey = $this->input->post('bidkey');
			$bidDets = $this -> Job_model -> bidByKey( $bidkey );
			$userDetails = $this->Api_model->getUserDetailsDetailsByObjectID($bidDets['userid']);
			$data = $bidDets;
			//$data['description'] = $bidDets['description'];
			$typearr = array('h'=>'Hour(s)','w'=>'Week(s)','d'=>'Day(s)');
			$data['amount'] = number_format($bidDets['bidamount'],2);
			$data['startingtime'] = ($bidDets['startdate'] != '0000-00-00') ? date('m/d/Y',strtotime($bidDets['startdate'])) : '--';
			$data['addamount'] = number_format($bidDets['additionalamount'],2);
			$data['username'] = $userDetails['firstname']." ".$userDetails['lastname'];
			$data['location'] = $userDetails['city'].", ".$userDetails['state']." ".$userDetails['zip'];
			$data['expectedtime'] = ($bidDets['exptime'] != '' and $bidDets['exptime'] != '0') ?  $bidDets['exptime']." ".$typearr[$bidDets['exptype']] : '--';
			$data['maximumtime'] = ($bidDets['maxtime'] != '' and $bidDets['maxtime'] != '0') ?  $bidDets['maxtime']." ".$typearr[$bidDets['maxtype']] : '--';
			$data['image'] = ($userDetails['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($userDetails['userid'], $userDetails['imagekey'], $userDetails['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
			$data['success'] = 1;
			print json_encode($data);
			exit;
		}

		public function cancelcontract(){
			$contractkey = $this->input->post('contractkey');
			$contract = $this->Job_model->getContractbykey($contractkey);
			$this -> Job_model -> cancelcontract( $contractkey );
			$this -> Job_model ->editBid(array('status'=>'-1'),$contract['jobid'],$contract['contractorid']) ;
			$this -> Job_model ->updateJobfields('jobstatus','new',$contract['jobid']);
			//print "<pre>"; print_r($contract); print "</pre>";
			if($contract['bt_transaction_id'] != ''){
				$result = Braintree_Transaction::refund($contract['bt_transaction_id']);
				$input['type'] = 'cancel from web';
				$input['contract'] = $contract;
				$input['bt_result'] = $result;
				$input['date'] = date('m/d/Y H:i:s A');
				$this->Job_model-> braintreeresponse($input);
				//print "<pre>"; print_r($result); print "</pre>";
				$this->Job_model->create_braintree_response($contract['ownerid'],$contract['contractorid'],$contract['jobid'],$result,'cancel');
			}
			

			print "success";
			exit;
		}

		public function download($dockey,$type="org"){
			$this->load->helper('download');
			if($type == "temp"){
				$doc = $this->Job_model->gettempdocbykey($dockey);
				$path	 = "assets/tempImgs/crop/" . $dockey . '.' . $doc['tempimgext'];
			}else{
				$doc = $this->Job_model->getdocbykey($dockey);
				$path = $this->corefunctions->getMyPath($doc['docid'],$doc['dockey'],$doc['docext'],'assets/docs/');
			}
			

			force_download( $path,NULL);
		}

		 public function search(){
	      	if( !( $this->session->userdata('userKey') ) ){
				redirect( base_url() );
				exit;
			}
			$jobname = $_GET['search'];
			$userExpertises = $this->Job_model->getUserExpertise($this->session->userdata('userId'));
		    $finalExpertises = $expertiseids = array();
		    if( !empty(  $userExpertises) ){
				foreach( $userExpertises as $ue ){
				    $expertiseids[] = $ue["expertiseid"];
				}
	    	}
			/* Get Jobs from expertise ID */
			$jobs = $this->Job_model-> getjobbyname(join(",",$expertiseids),$jobname);
			$mybids = $this->Job_model->getallmybid($this->session->userdata('userId'));
			if(!empty($jobs) and !empty($mybids )){
				foreach($jobs as $jk=>$j){
					$bidplaced = 0;
					foreach($mybids as $mk=>$m){
						if($m['jobid'] == $j['jobid']){
							$bidplaced = 1;
						}
					}
					$jobs[$jk]['bidplaced'] = $bidplaced;
					if(isset($jobs[$jk])){
						$cont = $this->Job_model->getContractforjob($j['jobid']);
						if(!empty($cont) and $cont['homeowneragree'] == '1'){
							unset($jobs[$jk]);
						}
					}
				}
			}
			if( !empty( $jobs ) ){
				foreach( $jobs as $bd => $bt ){
					$getBidImg = $this -> Job_model -> getBidImage( 'job',$bt['jobid'] );
					$jobs[$bd]['bidImg'] = ( !empty( $getBidImg ) ) ? base_url( $this->getBidImage($bt['jobid'],$getBidImg['dockey'],$getBidImg['docext']) ) : base_url('images/def_job.jpg');
					$bids =  $this->Job_model->getbids($bt['jobid']);
					$total = $highest = $lowest = $average = $tcnt =0;
					$jobs[$bd]['numberofbids'] = $tcnt;
					$jobs[$bd]['highestbid'] = $highest;
					$jobs[$bd]['lowestbid'] = $lowest;
					$jobs[$bd]['averagebid'] = $average;
					if(!empty($bids)){
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
						$jobs[$bd]['numberofbids'] = $tcnt;
						$jobs[$bd]['highestbid'] = round($highest,2);
						$jobs[$bd]['lowestbid'] = round($lowest,2);
						$jobs[$bd]['averagebid'] = round($average,2);
					}
				}
			}
			$expertises = $this->Api_model->getexpertise();
			  $expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
			$data['jobs'] = $jobs;
			$data['expertises'] = $expertises;

			$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			$data['allcounts'] = $this->hedercontroller->allCounts();
			$this->load->view('headers/header',$headerdata);
			$this->load->view('headers/left-menu');
			$this->load->view('contractor/search',$data);
			$this->load->view('headers/footer');
      }
	  
	  
	}

?>
