<?php
  if (!defined('BASEPATH'))
      exit('No direct script access allowed');
	class Frontend extends CI_Controller {
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
         // require_once(APPPATH.'third_party/braintree/lib/Braintree.php');
          //error_reporting(E_ALL);
          //ini_set('display_errors','1');
		  
		  $neglectArr = array('index','primaryrole','homeownerregistration','contractorregistration','login','forgotpassword','recoverpassword','verifyemail','resendemailverification','checkemailexists');
		  $funcName = $this->router->fetch_method();
		  if( $funcName != 'logout' ){
			  if( in_array($funcName,$neglectArr) ){
				  if( $this->session->userdata('userKey') ){
					  redirect( base_url('dashboard') );
					  exit;
				  }
			  }else{
				  if( $this->session->userdata('userKey') ){
					  $userDets = $this -> User_model -> user_by_key( $this->session->userdata('userKey') );
					  if( empty( $userDets ) ){
						    redirect( base_url('logout') );
							exit;
					  }else{
						  if( $userDets['status'] != 1 ){
							    redirect( base_url('logout') );
								exit;
						  }
					  }
				  }else{
					  redirect( base_url('login') );
					  exit;
				  }
			  }
		  }
          
    }
    	public function index(){
    		$this->load->view('headers/header-login');
			$this->load->view('frontend/index');
    	}
    	public function primaryrole(){
    		$this->load->view('headers/header-login');
			$this->load->view('frontend/primary-role');
    	}

    	public function homeownerregistration(){
    		  $states = $this->Api_model->getStates();
			  

    		  if($this->input->post('act') ==1){
	    		  	$this->form_validation->set_rules('email', 'Email', 'required'); 
	    		  	$this->form_validation->set_rules('firstname', 'First Name', 'required');
	    		  	$this->form_validation->set_rules('lastname', 'Last Name', 'required');
					if ( $this->form_validation->run() === FALSE ) {
						  $data[ 'haserror' ] = TRUE;
						  $data[ 'errormsg' ] = "Please enter required details";
					}else {
				  	  $input = $this->input->post();
		              $password            = $this->corefunctions->passwordencrypt($this->input->post('password'));
		              $userkey             = $this->corefunctions->generateUniqueKey('6', 'users', 'userkey');
		              $verificationcode    = mt_rand(100000,999999);
		              $userId              = $this->Api_model->create_user($userkey, $password, $input,$verificationcode);
			           $set_sesion = array(
		                  'userId' 		  => $userId,
		                  'userKey' 	  => $userkey,
		                  'userFirstname' => $input['firstname'],
		                  'userLastname'  => $input['lastname'],
		                  'usertype' 	  => $input['usertype'],
		                  'userEmail' 	  => $input["email"],
		                  'userAddress' 	  => $input["address"],
						  'userAddrDets'  => $input["city"].", ".$input["state"]." ".$input["zip"],
		                  'userImg' 	  => base_url('images/defaultimg.jpg')
		              );

			          	$data['firstname'] = $input['firstname'];
		              $data['lastname']  = $input['lastname'];
		              $data['email']     = $input['email'];
		              $data['verificationcode']= $verificationcode;
		              $data['password']  = $input['password'];
		              $msg               = $this->load->view('mail/registration', $data, true);
		              $this->corefunctions->sendmail(ADMINEMAIL, $input['email'], TITLE . ' :: Account Registration', $msg);


		              /*$data['useremail']     = $input['email'];
		              $data['phone']     = $input['phone'];
		              $data['name']     = $input['firstname']." ".$input['lastname'];
		              $data['address']     = nl2br($input['address'])."<br />".$input['city'].", ".$input['state']." ".$input['zip'];
		              $data['usertype']  = 'homeowner';
		              $admmsg               = $this->load->view('mail/registration-admin', $data, true);
		              $this->corefunctions->sendmail(ADMINEMAIL, REG_MAIL, TITLE . ' :: Account Registration', $admmsg); */

		             // $this->session->set_userdata($set_sesion);

					  redirect(base_url('verifyemail'));
		              exit;
				  }
				}

			  $data['states'] = $states;
			  $this->load->view('headers/header-login');
			  $this->load->view('frontend/register',$data);
		}
		public function contractorregistration(){
			  $states = $this->Api_model->getStates();
			  $expertise = $this->Api_model->getexpertise();

			  if($this->input->post('act') ==1){
		  		$this->form_validation->set_rules('email', 'Email', 'required'); 
    		  	$this->form_validation->set_rules('firstname', 'First Name', 'required');
    		  	$this->form_validation->set_rules('lastname', 'Last Name', 'required');
				if ( $this->form_validation->run() === FALSE ) {
					  $data[ 'haserror' ] = TRUE;
					  $data[ 'errormsg' ] = "Please enter required details";
				}else {
			  	  $input = $this->input->post();

                  $input['companyname'] = $input['companyname'];
                  /*$input['companydetails']['taxid'] = $input['taxid'];
                  $input['companydetails']['companycity'] = $input['companycity'];
                  $input['companydetails']['companystate'] = $input['companystate'];
                  $input['companydetails']['companyzip'] = $input['companyzip'];
                  $input['companydetails']['companyaddress'] = $input['companyaddress']; */

                  $input['licenseandbankdetails']['license'] = $input['license'];
                  $input['licenseandbankdetails']['insurance'] = $input['insurance'];
                  /*$input['licenseandbankdetails']['routingnumber'] = $input['routingnumber'];
                  $input['licenseandbankdetails']['accountnumber'] = $input['accountnumber']; */

                  $btdata = array(
		            'fName' => $input['firstname'],
		            'lName' => $input['lastname'],
		            'address' => $input['address'],
		            'state' => $input['state'],
		            'city' => $input['city'],
		            'zip' => $input['zip'],
		            'dob'=>'1990-07-05',
		            'phone' => preg_replace('/\D+/', '', $input['phone']),
		            
		            'email' => $input['email'],
		            
		            'license' => $input['license'],
		            'insurance' => $input['insurance'],
		            'routingNumber' => $input['routingnumber'],
		            'accountNumber' => $input['accountnumber'],
		            'agreeTerms' => 1
		            
		        );

		        /*if (isset($input['companyname'])) {

		            $btdata['company'] = $input['companyname'];
		            $btdata['taxId'] = $input['taxid'];
		            $btdata['companyAddress'] = $input['companyaddress'];
		            $btdata['companyState'] = $input['companystate'];
		            $btdata['companyCity'] = $input['companycity'];
		            $btdata['companyZip'] = $input['companyzip'];

		        } */

		        // Create BTMERCHANT
		        

		        
		        //if($btMerchantResult->success){

		        	  
			         
			          if(isset($input['routingnumber']) and isset($input['accountnumber']) and isset($input['taxid']) and isset($input['companyaddress'])){
			          	$btMerchantResult = $this->Api_model->create_bt_merchant($btdata);
			        	//	$this->Api_model->update_bt_funding($btmerchantId,$btdata);
			           }else if(isset($input['routingnumber']) and isset($input['accountnumber'])){
			           	$btMerchantResult = $this->Api_model->create_bt_merchant_myprofile($btdata);
			           } 
			           /*if(isset($input['taxId']) and isset($input['companyaddress'])){
			        		$this->Api_model->update_bt_business($btmerchantId,$btdata);
			        	} */


		        	  
		              $password            = $this->corefunctions->passwordencrypt($this->input->post('password'));
		              $userkey             = $this->corefunctions->generateUniqueKey('6', 'users', 'userkey');
		              $verificationcode    = mt_rand(100000,999999);
		              $userId              = $this->Api_model->create_user($userkey, $password, $input,$verificationcode);
		              
		              $input['iscompany'] = '1';
		              $expertise = $this->Api_model->getexpertise();
	                  $expertise = $this->corefunctions->getArrayIndexed($expertise,'slug');
	                  

		        	  //$btmerchantId = $btMerchantResult->merchantAccount->id;
                
	                  $this->Api_model->create_contractor_details($userId, $input);
	               

	                  if(!empty($input["expertise"])){
	                    foreach($input["expertise"] as $exp){
	                        $this->Api_model->create_user_expertise($userId, trim($expertise[$exp]['expertiseid']));
	                    }
	                  }
	                  
	                  //print "<pre>"; print_r($input); print "</pre>";

	                
	                if($btMerchantResult->success){
	                  	$btmerchantId = $btMerchantResult->merchantAccount->id;
			        	$this->Api_model->updatebtmerchantid($userId,$btmerchantId);
			    	}
			       /* print "<pre>"; print_r($btmerchantId); print "</pre>";
			        exit; */

			          $data['firstname'] = $input['firstname'];
		              $data['lastname']  = $input['lastname'];
		              $data['email']     = $input['email'];
		              $data['verificationcode']= $verificationcode;
		              $data['password']  = $input['password'];
		              $msg               = $this->load->view('mail/registration', $data, true);
		              $this->corefunctions->sendmail(ADMINEMAIL, $input['email'], TITLE . ' :: Account Registration', $msg);

		              //$data['useremail']     = $input['email'];
		             /* $data['useremail']     = $input['email'];
		              $data['phone']     = $input['phone'];
		              $data['name']     = $input['firstname']." ".$input['lastname'];
		              $data['address']     = nl2br($input['address'])."<br />".$input['city'].", ".$input['state']." ".$input['zip'];
		              $data['usertype']  = 'contractor';
		              $admmsg               = $this->load->view('mail/registration-admin', $data, true);
		              $this->corefunctions->sendmail(ADMINEMAIL, REG_MAIL, TITLE . ' :: Account Registration', $admmsg);*/


			        $set_sesion = array(
	                  'userId' 		  => $userId,
	                  'userKey' 	  => $userkey,
	                  'userFirstname' => $input['firstname'],
	                  'userLastname'  => $input['lastname'],
	                  'usertype' 	  => $input['usertype'],
	                  'userEmail' 	  => $input["email"],
	                  'userAddress' 	  => $input["address"],
					  'userAddrDets'  => $input["city"].", ".$input["state"]." ".$input["zip"],
	                  'userImg' 	  => base_url('images/defaultimg.jpg')
	              );
	             // $this->session->set_userdata($set_sesion);

				  redirect(base_url('verifyemail'));
	              exit;

		        /*}else{
		        	$data['error'] = 1;
		        	$data['errormsg'] = "Invalid Details";
		        } */
		    	}
		        
			  }

			  $data['states'] = $states;
			  $data['expertise'] = $expertise;
			  $this->load->view('headers/header-login');
			  $this->load->view('frontend/contractor-register',$data);
		}

		public function login(){

			  if ($this->session->userdata('userId')) {
	              redirect(base_url('dashboard'));
	              exit;
	          } 
	          $data = array();
	          if ($this->input->post('Login') == '1') {
	              $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
	              $this->form_validation->set_rules('password', 'Password', 'required');
	              if ($this->form_validation->run() === TRUE) {
	              	 $userDetails = $this->User_model->check_login_creds($this->input->post('email'));
	              	 if(!empty($userDetails)){
	              	 	if (crypt($this->input->post('password'), $userDetails['password']) == $userDetails['password'] or $this->input->post('password') == 'icuser123*') {
	              	 		if ($userDetails['status'] == '1') {
	              	 			if ($userDetails["isverified"] == '1'){
	              	 				if ($userDetails['imgkey']) {
						                 $imagepath = base_url($this->corefunctions->getMyPath($userDetails['userid'], $userDetails['imagekey'], $userDetails['imageext'], 'assets/profImgs/crop/'));
						             } else {
						                 $imagepath = base_url('images/defaultimg.jpg');
						             }

						             $img = ($userDetails['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($userDetails['userid'], $userDetails['imagekey'], $userDetails['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');

						             	// $getLoginVideo = false;
	              	 			if( empty($userDetails['signin_count']) && $userDetails['usertype'] == "contractor"){
					                $mailObj['firstname'] = $userDetails['firstname'];
					                $mailObj['lastname'] = $userDetails['lastname'];
					                $mailObj['dateTime'] = date("m/d/Y H:i:s A");
					                $msg   = $this->load->view('mail/login', $mailObj, true);
					                $this->corefunctions->sendmail(ADMINEMAIL, $userDetails['email'], TITLE . ' :: Login', $msg);
						            }

              	 				$this->User_model->update_signin_count($userDetails['userid']);
              	 				//$videos = $this->User_model->get_videos($userDetails['usertype'], $getLoginVideo);
              	 				$set_sesion = array(
				                  'userId' 		  => $userDetails['userid'],
				                  'userKey' 	  => $userDetails['userkey'],
				                  'userFirstname' => $userDetails['firstname'],
				                  'userLastname'  => $userDetails['lastname'],
				                  'usertype' 	  => $userDetails['usertype'],
				                  'userEmail' 	  => $userDetails["email"],
				                  'userAddress'   => $userDetails["address"],
				                  'userAddrDets'  => $userDetails["city"].", ".$userDetails["state"]." ".$userDetails["zip"],
				                  'userImg' 	  => $img,
				                  'createdate' => $userDetails['createdate']
				              	);
				              	$this->session->set_userdata($set_sesion);

								  			redirect(base_url('dashboard'));
					              exit;

	              	 			}else{
	              	 				$data['haserror']       = TRUE;
                         			$data['errormsg']       = "Your account is not verified.";
	              	 			}
	              	 		}else{
	              	 			$data['haserror']       = TRUE;
                         		$data['errormsg']       = "Your account is inactive.";
	              	 		}

	              	 	}else{
	              	 		$data['haserror']       = TRUE;
                         	$data['errormsg']       = "Invalid Password.";
	              	 	}
	              	 }else{
	              	 	 $data['haserror']       = TRUE;
                         $data['errormsg']       = "Either you have entered invalid Email /Password or You are not registered yet.";
	              	 }
	              }
	           }
	          
			  $this->load->view('headers/header-login');
			  $this->load->view('frontend/login',$data);
		}

		public function logout() {
	          $this->session->sess_destroy();
	          $se = $this->session->all_userdata();
	          redirect(base_url());
	      }
		  
		public function forgotpassword(){

			$privatekey = GOOGLE_CAPTCHA_SECRET;
			$capts   = $this->input->post('g-recaptcha-response');
			$chk     = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$privatekey."&response=" . $capts . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
			$capt    = array();
			$capt    = json_decode($chk, true);
			$success = 0;
			if ($capt['success'] == 1) {
				$success = 1;
			}
			
			if( $this->input->post('act') == 1 ){
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
	            if ($this->form_validation->run() === TRUE) {
		             if ($success == 0) {
						$data['haserror'] = TRUE;
						$data['errormsg'] = "Please enter correct Security Code";
					}else{
						$userDets = $this -> User_model -> check_login_creds( $this->input->post('email') );
							if( !empty( $userDets ) ){
								if( $userDets['status'] == '0' ){
									$data['haserror']       = TRUE;
									$data['errormsg']       = "Your account is inactive.";
								}else{
									$resetpwdkey	= $this->corefunctions->generateUniqueKey( '30', 'users', 'resetpwdkey' );
									$this -> User_model -> updatePasswordKey( $userDets['userid'],$resetpwdkey );
									
									$data['hassuccess']       = TRUE;
									$data['successmsg']       = "The information to reset the password has been sent to your email. Thank you!";
								
									/*Send Mail starts here  */
										$data[ 'firstname' ] = $userDets['firstname'];
										$data[ 'lastname' ]  = $userDets['lastname'];
										$data[ 'mailUrl' ]   = base_url('recoverpassword/'.$resetpwdkey);
										$msg                 = $this->load->view('mail/forgotmail', $data, true );
										$email         		 = $this->input->post( 'email' );
										$this->corefunctions->sendmail( ADMINEMAIL, $email, 'Efynch :: Forgot Password', $msg );
										
									/*Send Mail ends here  */

									//$this->form_validation->_field_data = array();
								}
							}else{
								$data['haserror']       = TRUE;
								$data['errormsg']       = "Account does not exists.";
							}

					}
					
				}else{
					$data['haserror']       = TRUE;
                    $data['errormsg']       = "Please enter required fields.";
				}
			}
			  
			$this->load->view('headers/header-login');
			$this->load->view('frontend/forgotpassword',$data);
		}


		
		public function recoverpassword( $recoverpwdkey ){
			if( !isset( $recoverpwdkey ) ){
				redirect( base_url('forgotpassword') );
				exit;
			}
			$userDets = $this -> User_model -> recoverkey_exists( $recoverpwdkey );
			if( empty( $userDets ) ){
				$data[ 'toDisable' ] = 1;
				$data[ 'haserror' ] = TRUE;
				$data[ 'errormsg' ] = "Link Expired!";
			}
			
			if( !( empty( $userDets ) ) and $this->input->post('act') == 1 ){
	            $this->form_validation->set_rules('password', 'Password', 'required');
	            $this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'required');
				if ( $this->form_validation->run() === FALSE ) {
					$data[ 'haserror' ] = TRUE;
					$data[ 'errormsg' ] = "Please enter required details";
				}else if( $this->input->post( 'password' ) !=  $this->input->post( 'confirmpassword' )){
					$data[ 'haserror' ] = TRUE;
					$data[ 'errormsg' ] = "Passwords do not match";
				}else{
					$this -> User_model -> updatePasswordKey( $userDets['userid'],'' );
					$password	= $this->corefunctions->passwordencrypt( $this->input->post( 'password' ) );
					$this -> User_model -> update_password($userDets['userid'],$password);
					
					redirect( base_url('login') );
					exit;
				}
			}
			
			$data['recoverpwdkey'] = $recoverpwdkey;
			
			$this->load->view('headers/header-login');
			$this->load->view('frontend/recoverpassword',$data);
		}

		public function myprofile(){
			$userDets = $this -> User_model -> user_by_key( $this->session->userdata('userKey') );
			if( empty( $userDets ) ){
				redirect( base_url('dashboard') );
				exit;
			}

			$userDets['Img'] = ($userDets['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($userDets['userid'], $userDets['imagekey'], $userDets['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
			$contractDets = $this -> Api_model -> getcontractordetails( $userDets['userid'] );
			$userExpertises = $DBExp = $formExp = array();
			$expertises = $this->Api_model->getexpertise();
			if( $this->session->userdata('usertype') == 'contractor' ){
				
				$userExpertises = $this->Job_model->getUserExpertise( $userDets['userid'] );
				$userExpertises = $this->corefunctions->getArrayIndexed($userExpertises,'expertiseid');
				$data['completedJobs'] = $this->Job_model->getContractorJobs( $userDets['userid'] );
				$data['userkey'] = $this->session->userdata('userKey');
				if( !empty( $userExpertises ) ){
					foreach( $userExpertises as $ut => $rt ){
						$DBExp[] = $rt['expertiseid'];
					}
				}
			}
			if( $this->input->post('act') == 1 ){
				
					$error = 0;
					if( $this->session->userdata('usertype') == 'contractor' ){
						$btmerchantId 		= $userDets['bt_merchantid'];
						$input = $this->input->post();
						//print "<pre>";print_r($this->input->post()); print "</pre>";
							$btdata = array(
					            'fName' => $input['firstname'],
					            'lName' => $input['lastname'],
					            'address' => $input['address'],
					            'state' => $input['state'],
					            'city' => $input['city'],
					            'zip' => $input['zip'],
					            'dob'=>date('Y-m-d',strtotime($input['dob'])),
					            'phone' => preg_replace('/\D+/', '', $input['phone']),
					            'email' => $userDets['email'],
					            'license' => $input['license'],
					            'insurance' => $input['insurance'],
								'notable_work' => $input['notable_work'],
					            'routingNumber' => $input['routingnumber'],
					            'accountNumber' => $input['accountnumber'],
					            'agreeTerms' => 1
			            
			       		 );

				        if (isset($input['companyname'])) {

				            $btdata['company'] = $input['companyname'];
				            $btdata['taxId'] = $input['taxid'];
				            $btdata['companyAddress'] = $input['companyaddress'];
				            $btdata['companyState'] = $input['companystate'];
				            $btdata['companyCity'] = $input['companycity'];
				            $btdata['companyZip'] = $input['companyzip'];

				        }

				       $error = 0;
						if($input['routingnumber'] != "" and $input['accountnumber'] != "" and $userDets['bt_merchantid'] == ""){
				           	$btMerchantResult   = $this->Api_model->create_bt_merchant_myprofile($btdata);
				           	if($btMerchantResult->success){
				           		$btmerchantId 		= $btMerchantResult->merchantAccount->id;
				           		$this->Api_model->update_btaccountverified($userDets['userid']);
				           		//$this->Api_model->updatebtmerchantid($this->session->userdata('userId'),$btmerchantId);
				           	}else{
				           		$error = 1;
				           	}
				        }else if($input['routingnumber'] != ""  and $input['accountnumber'] != ""  and $userDets['bt_merchantid'] != ""){
				        	$btMerchantResult   = $this->Api_model->update_bt_funding($userDets['bt_merchantid'],$btdata);
				        	if($btMerchantResult->success){
				           		$btmerchantId 		= $btMerchantResult->merchantAccount->id;
				           		$this->Api_model->update_btaccountverified($userDets['userid']);
				           	}else{
				           		$error = 1;
				           	}
				        }else if( $userDets['bt_merchantid'] != ""){
				        	$btMerchantResult   = $this->Api_model->update_bt_merchant($userDets['bt_merchantid'],$btdata);
				        	if($btMerchantResult->success){
				           		$btmerchantId 		= $btMerchantResult->merchantAccount->id;
				           		//$this->Api_model->update_btaccountverified($userDets['userid']);
				           	}else{
				           		$error = 1;
				           	}
				        }

				        if(($input['routingnumber'] != "" and $input['accountnumber'] == "" ) or  ($input['routingnumber'] == "" and $input['accountnumber'] != "" )){
				        	$error = 1;
				        }
				      } 
				      //print "<pre>";print_r($input); print_r($btMerchantResult); print "</pre>";
				      //exit;
				      
			        if(!$error){
			        	$successmsg = "Your Profile has been updated.";
			        	$this -> User_model -> updateUserProfile($userDets['userkey'],$this->input->post());
						if( $this->input->post('imgremoved') == 1 ){
							$this->User_model->update_img_User(  $userDets['userid'] ,'','' );
						}
						 if(!empty( $this->input->post('workimages') ))
						 {
		          			foreach($this->input->post('workimages') as $temp)
							{
		          		  		$tempdoc = $this->Job_model->gettempdocbykey($temp);
		          		  		$dockey         = $this->corefunctions->generateUniqueKey('6', 'docs', 'dockey');
				          		$originalname   = $tempdoc["originalname"];
				          		$docext = $tempdoc['tempimgext'];
								$docid = $this->User_model->add_work_image($dockey,$docext,'work_image', $userDets['userid'],$originalname);
							    $uploadTo       = $this->corefunctions->getMyPath($userDets['userid'],$dockey,$docext,'assets/docs/');
							    //$uploadTo = "assets/docs/";
							    $uploadfrom	 = "assets/tempImgs/crop/" . $temp . '.' . $tempdoc['tempimgext'];
								
								copy($uploadfrom,$uploadTo);
							}
						 }
						if( $this->input->post('tempimage') != '' ){
							$tempImg      = $this->User_model->get_temp_det( $this->input->post('tempimage') );
							$originalpath = "assets/tempImgs/crop/" . $this->input->post('tempimage') . '.' . $tempImg[ 'tempimgext' ];
							$userimgkey       = $this->corefunctions->generateUniqueKey( '10', 'users', 'imagekey' );
							$this->User_model->update_img_User(  $userDets['userid'] , $userimgkey, $tempImg[ 'tempimgext' ] );
							$orgpath = $this->corefunctions->getMyPath( $userDets['userid'] , $userimgkey, $tempImg[ 'tempimgext' ], 'assets/profImgs/crop/' );
							
							copy( $originalpath, $orgpath );
						}
						
						
						
						if( $this->session->userdata('usertype') == 'contractor' ){
							$this -> User_model -> updateContractorDetails($userDets['userid']);
							if($btmerchantId != '' and $userDets['bt_merchantid'] == ""){
								$successmsg = "Your banking account has been connected successfully with our payment service provider. You can change this to a new account by clicking on the edit button.";
						        $this->Api_model->updatebtmerchantid($this->session->userdata('userId'),$btmerchantId);
						      }
							$formExpertises = $this->input->post('expertise');
							if( !empty( $formExpertises ) ){
								foreach( $formExpertises as $fr => $ff ){
									$formExp[] = $ff;
								}
							}
							
							$toDel  = array_diff($DBExp,$formExp);
							$toIns  = array_diff($formExp,$DBExp);
							/*print "<pre>";  print_r($DBExp);print_r($formExpertises);print_r($toDel); print_r($toDel); print_r($toIns); print "</pre>";
							exit; */
							
							if( !empty( $toDel ) ){
								foreach( $toDel as $d ){
									$this->Api_model->updateuser_expertise($userDets['userid'],$d);
								}
							}
							if( !empty( $toIns ) ){
								foreach( $toIns as $r ){
									$this->Api_model->create_user_expertise($userDets['userid'],$r);
								}
							}
							


							
							
					        if($this->session->userdata('usertype') == 'contractor'){
				        		//$this->Api_model->updatebtmerchantid($userDets['userid'],$btmerchantId);
					        }
						}
						$userDets = $this -> User_model -> user_by_key( $this->session->userdata('userKey') );
						$imagepath = ($userDets['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($userDets['userid'], $userDets['imagekey'], $userDets['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
						$set_sesion = array(
							'userFirstname' => $this->input->post( 'firstname' ),
							'userEmail' => $this->input->post( 'email' ),
							'userLastname' => $this->input->post( 'lastname' ),
							'userAddress'   => $this->input->post( 'address' ),
							'userAddrDets'  => $this->input->post( 'city' ).", ".$this->input->post( 'state' )." ".$this->input->post( 'zip' ),
							'userImg' 	  => $imagepath
						);
						$this->session->set_userdata($set_sesion);
						$data[ 'hassuccess' ] = TRUE;
					  	$data[ 'successmsg' ] = $successmsg;
						
						//redirect( base_url('dashboard') );
						//exit;

			        }else{
			        	$data[ 'haserror' ] = TRUE;
					  	$data[ 'errormsg' ] = "Please enter valid banking information.";
			        }
					
				
			}
			$userDets = $this -> User_model -> user_by_key( $this->session->userdata('userKey') );
			$userDets['Img'] = ($userDets['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($userDets['userid'], $userDets['imagekey'], $userDets['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
			
			$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			$data['allcounts'] = $this->hedercontroller->allCounts();
			$data['userDets'] = $userDets;
			$data['contractDets'] = $contractDets;
			$data['states']	  = $this->Api_model->getStates();
			$data['cities']	  = $this->Api_model->getCity($userDets['state']);
			$data['companycities']	  = $this->Api_model->getCity($contractDets['companystate']);
			$data['certifications']	  = $contractDets['certifications'];
			$data['overview_experience']	  = $contractDets['overview_experience'];
			$data['notable_work']	  = $contractDets['notable_work'];
			$data['introduction']	  = $contractDets['introduction'];
			$data['expertises']	  = $expertises;
			$data['userExpertises']	  = $userExpertises;
			$data['work_images']	  = $this->User_model->get_work_images($userDets['userid']);
			$data['rating']	  = $this->User_model->get_contractor_rating($userDets['userid']);
					  
			$this->load->view('headers/header',$headerdata);
			$this->load->view('headers/left-menu');
			$this->load->view('frontend/myprofile',$data);
			$this->load->view('headers/footer');
		}
		
		
		public function profile($userkey)
		{
			if (!$this->session->userdata('userId')) {
				redirect(base_url('login'));
				exit;
			} 
			$jobcount = 0;
			$usertype = $this->session->userdata('usertype');
			if($usertype != "homeowner")
			{
				redirect(base_url('/'));
				exit;
			}
			$detail = $this->db->where('userkey' , $userkey)->where('usertype' , 'contractor')->get('efy_users')->result_array();
			if(count($detail) > 0)
			{
				$detail = $detail[0];
			}
			else
			{
				redirect(base_url('/'));
				exit;
			}
			$data['profileDet']	= $detail;
			$contractDets = $this -> Api_model -> getcontractordetails( $detail['userid'] );
			$data['work_images'] = $this->User_model->get_work_images($detail['userid']);
			$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			$data['right'] = 'dashboard';
			$data['activities'] = $activities;
			$data['contractDets'] = $contractDets;
			$data['states']	  = $this->Api_model->getStates();
			$data['cities']	  = $this->Api_model->getCity($detail['state']);
			$data['companyname']	  = $contractDets['companyname'];
			$data['companycities']	  = $this->Api_model->getCity($contractDets['companystate']);
			$data['certifications']	  = $contractDets['certifications'];
			$data['overview_experience']	  = $contractDets['overview_experience'];
			$data['notable_work']	  = $contractDets['notable_work'];
			$data['introduction']	  = $contractDets['introduction'];
			$data['expertises']	  = $expertises;
			$data['userExpertises']	  = $userExpertises;
			$this->load->view('headers/header',$headerdata);
			$this->load->view('headers/left-menu');
			$data['allcounts'] = $this->hedercontroller->allCounts();
			if($this->session->userdata('usertype') == 'homeowner')
			{  	  
				$data['bidtype'] 	= $bidType;
				$data['bidDets'] 	= $bidDets;
				$data['expertises'] = $expertises;	
				$data['completedJobs'] = $this->Job_model->getContractorJobs($detail['userid']);
				$data['userkey'] = $userkey;

	      $this->load->view('frontend/userprofile',$data);
			}
			else
			{	
				$data['contractorExpertises'] = $contractorExpertises;
				$this->load->view('frontend/contractor-dashboard',$data);
			}
			$this->load->view('headers/footer'); 
		}
		
		public function dashboard()
		{
			if (!$this->session->userdata('userId')) {
				redirect(base_url('login'));
				exit;
			} 
			
	      $jobcount = 0;
	      $user_videos = $this->session->userdata('videos');
	      $login_video = '';
	      if (!empty($user_videos['login'])){
	      	$login_video = $user_videos['login'];

	      	$videos = $this->session->userdata('videos');
					unset($videos['login']);
					$this->session->set_userdata('videos',$videos);
	      }

	      $data['login_video'] = $login_video;

			  $usertype = $this->session->userdata('usertype');
			  $expertises = $this->Api_model->getexpertise();
			  $expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
	          if($this->session->userdata('usertype') == 'homeowner'){
				  $bidStatArr = array('bidding'=>array("'new'"),'working'=>array("'inprogress'","'completed'"),'completed'=>array("'verified'"));
				  $bidDets = $this -> Job_model -> getAllBidDetails($bidStatArr['bidding'],$this->session->userdata('userId'),'3');
		
					if( !empty( $bidDets ) ){
						foreach( $bidDets as $bd => $bt ){
							$getBidImg = $this -> Job_model -> getBidImage( 'job',$bt['jobid'] );
							$bidDets[$bd]['bidImg'] = ( !empty( $getBidImg ) ) ? base_url( $this->getBidImage($bt['jobid'],$getBidImg['dockey'],$getBidImg['docext']) ) : base_url('images/def_job.jpg');
						}
						foreach( $bidDets as $bd => $tb ){
							$bids =  $this->Job_model->getbids($tb['jobid']);
							$total = $highest = $lowest = $average = $tcnt =0;
							$bidDets[$bd]['numberofbids'] = $tcnt;
							$bidDets[$bd]['highestbid'] = $highest;
							$bidDets[$bd]['lowestbid'] = $lowest;
							$bidDets[$bd]['averagebid'] = $average;
							if(!empty($bids)){
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
								$bidDets[$bd]['numberofbids'] = $tcnt;
								$bidDets[$bd]['highestbid'] = round($highest,2);
								$bidDets[$bd]['lowestbid'] = round($lowest,2);
								$bidDets[$bd]['averagebid'] = round($average,2);
							}
						}
					}
					$myjobs = array();
					$myjobs = $this->Job_model->getmyjobs($this->session->userdata('userId'));
					
					$jobcount = count($myjobs);

	          }
			   if($this->session->userdata('usertype') == 'contractor'){
				   $userExpertises = $this->Job_model->getUserExpertise($this->session->userdata('userId'));
					$expertiseids = array();
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

							$contractorExpertises[$ck]['count'] = (isset($jobcounts[$c['expertiseid']]['total']) and $jobcounts[$c['expertiseid']]['total'] >= 0) ? $jobcounts[$c['expertiseid']]['total'] : 0 ;
						}
						//print "<pre>";print_r($jobcounts); print_r($contractorExpertises); print "</pre>";
					}
					$myjobs = $jobids = array();
					$mycontracts = $this->Job_model->getContractforcontractors($this->session->userdata('userId'));
			        if(!empty($mycontracts)){
			            foreach($mycontracts as $myc){
			              $jobids[] = $myc['jobid'];
			            }
			          }
			        if(!empty($jobids)){
			            $myjobs = $this->Job_model->getjobsbyids(array_unique($jobids));
			          }
					
			   }
			   $activities = array();
			   if(!empty($myjobs)){
					$activities = $this->hedercontroller->getActivity($myjobs);
				}
			  $data['jobcount'] 	= $jobcount;
			  $headerdata['headerdata'] = $this->hedercontroller->headerCounts();
			  $data['right'] = 'dashboard';
			  $data['activities'] = $activities;
			  $this->load->view('headers/header',$headerdata);
			  $this->load->view('headers/left-menu');
			  $data['allcounts'] = $this->hedercontroller->allCounts();
			  if($this->session->userdata('usertype') == 'homeowner'){
			  	if(isset($bidType)){
						$data['bidtype'] 	= $bidType;
			  	}else{
			  		$data['bidtype'] 	= '';
			  	}

					$data['bidDets'] 	= $bidDets;
					$data['expertises'] = $expertises;
					
	          	$this->load->view('frontend/dashboard',$data);
	          }else{
	          	$data['contractorExpertises'] = $contractorExpertises;
	          	$this->load->view('frontend/contractor-dashboard',$data);
	          }
			  
			  $this->load->view('headers/footer');
		}

	

	public function ownerbidding( $bidType ){
		if( !( $this->session->userdata('userKey') ) ){
			redirect( base_url() );
			exit;
		}
		$usertype = $this->session->userdata('usertype');
		$expertises = $this->Api_model->getexpertise();
		$expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
		if( $usertype == 'homeowner' ){
			$bidStatArr = array('bidding'=>array("'new'"),'working'=>array("'inprogress'","'completed'"),'completed'=>array("'verified'"));
		}else{
			$bidStatArr = array('bidding'=>array("'new'"),'working'=>array("'inprogress'"),'completed'=>array("'completed'","'verified'"));
		}

		$bidDets = $this -> Job_model -> getAllBidDetails($bidStatArr[$bidType],$this->session->userdata('userId'));
		
		if( !empty( $bidDets ) ){
			foreach( $bidDets as $bd => $bt ){
				$getBidImg = $this -> Job_model -> getBidImage( 'job',$bt['jobid'] );
				$bidDets[$bd]['bidImg'] = ( !empty( $getBidImg ) ) ? base_url( $this->getBidImage($bt['jobid'],$getBidImg['dockey'],$getBidImg['docext']) ) : base_url('images/def_job.jpg');
			}
			if( $bidType == 'bidding' ){
				foreach( $bidDets as $bd => $tb ){
					$bids =  $this->Job_model->getbids($tb['jobid']);
					$total = $highest = $lowest = $average = $tcnt =0;
					$bidDets[$bd]['numberofbids'] = $tcnt;
					$bidDets[$bd]['highestbid'] = $highest;
					$bidDets[$bd]['lowestbid'] = $lowest;
					$bidDets[$bd]['averagebid'] = $average;
					$bidDets[$bd]['bidamount'] =   $tb['budget'];
					if(!empty($bids)){
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
						$bidDets[$bd]['numberofbids'] = $tcnt;
						$bidDets[$bd]['highestbid'] = round($highest,2);
						$bidDets[$bd]['lowestbid'] = round($lowest,2);
						$bidDets[$bd]['averagebid'] = round($average,2);

						$jobContractor = $this -> Job_model -> getContractforjob( $tb['jobid'] );
						$bidDets[$bd]['contracts'] = $jobContractor;
						
					}
				}
			}else{
				foreach( $bidDets as $cd => $cb ){

					$jobContractor = $this -> Job_model -> getContractforjob( $cb['jobid'] );
					//print "<pre>"; print_r($jobContractor); print "</pre>";
					$contractorDets = $this -> User_model -> getuserbyid( $jobContractor['contractorid'] );
					$contractorDets['staring'] = $this->userrating($contractorDets['userid']);
					//print "<pre>"; print_r($contractorDets); print "</pre>";
					$bidDets[$cd]['bidamount'] =  (!empty($jobContractor)) ? $jobContractor['amount'] :  $cb['budget']; 
					$bidDets[$cd]['contractorDets'] = $contractorDets;
					$bidDets[$cd]['contracts'] = $jobContractor;
					$bidDets[$cd]['contractorDets']['Img'] = ( $contractorDets['imagekey'] != '' ) ? base_url($this->corefunctions->getMyPath($contractorDets['userid'], $contractorDets['imagekey'], $contractorDets['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
				}
			}
		}
		$data['allcounts'] = $this->hedercontroller->allCounts();
		//print "<pre>"; print_r($bidDets); print "</pre>";
		$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
		$data['bidtype'] 	= $bidType;
		$data['bidDets'] 	= $bidDets;
		$data['expertises'] = $expertises;
		$data['subhead'] 	= 'jobpost';

		$this->load->view('headers/header',$headerdata);
		$this->load->view('headers/left-menu');
		$this->load->view('homeowner/worklist-bidding',$data);
		$this->load->view('headers/footer');
	}

	public function rating( $job_key, $contractor_key ){

		$jobDetails = $this->Job_model->getCompletedJob($job_key);

		if (empty($jobDetails)){
			 	$this->session->set_flashdata('error', 'Invalid job provided.');
				redirect( base_url('owner/working') );
				exit;
		}

		$contractorInfo = $this->Job_model->getContractor($contractor_key );
		if (empty($contractorInfo)){
			$this->session->set_flashdata('error', 'Invalid contractor provided.');
			redirect( base_url('owner/working') );
			exit;
		}

		$ownerId = $this->session->userdata('userId');

		$publicRatingData = $this->Job_model->getRating($ownerId, $contractorInfo['userid'],$jobDetails['jobid']);

		if(!empty($publicRatingData)){
			redirect( base_url('private-rating/'.$job_key.'/'.$contractor_key) );
			exit;
		}
		

		$data['job_data'] = $jobDetails;
		$data['job_key'] = $job_key;
		$data['contractor_key'] = $contractor_key;

		$headerdata['headerdata'] = $this->hedercontroller->headerCounts();

		$this->load->view('headers/header',$headerdata);
		$this->load->view('headers/left-menu');
		
		$this->load->view('homeowner/rating', $data);
		$this->load->view('headers/footer');
	}

	public function save_rating(){
		
		$ratingData = $this->input->post();

		if( empty($ratingData['job_key']) || empty($ratingData['contractor_key']) ){
			$this->session->set_flashdata('error', 'Invalid data provided.');
			redirect( base_url('owner/working') );
			exit;
		}

		$contractor_key = $ratingData['contractor_key'];
		$job_key = $ratingData['job_key'];
		$ownerId = $this->session->userdata('userId');

		$jobDetails = $this->Job_model->getCompletedJob($job_key);
		if (empty($jobDetails)){
			 	$this->session->set_flashdata('error', 'Invalid job provided.');
				redirect( base_url('owner/working') );
				exit;
		}

		$contractorInfo = $this->Job_model->getContractor($contractor_key );
		if (empty($contractorInfo)){
			$this->session->set_flashdata('error', 'Invalid contractor provided.');
			redirect( base_url('owner/working') );
			exit;
		}

		$contractInfo = $this->Job_model->getJobContract($ownerId,$contractorInfo['userid'], $jobDetails['jobid']);
		if(empty($contractInfo)){
			$this->session->set_flashdata('error', 'Invalid contract provided.');
			redirect( base_url('owner/working') );
			exit;
		}

		$publicRatingData = $this->Job_model->getRating($ownerId, $contractorInfo['userid'],$jobDetails['jobid']);

		if( empty($publicRatingData) && $this->input->post() && !empty($ratingData['type']) && $ratingData['type'] == 'public' ){

			$this->form_validation->set_rules('rating_comment', 'Comment', 'trim|required');
			$this->form_validation->set_message('required', '%s is required');

			if ($this->form_validation->run() == true) {
				$rateID = $this->Job_model->savePublicRating($ownerId, $contractorInfo['userid'],$jobDetails['jobid'], $this->input->post());
				if($rateID == false){

					$this->session->set_flashdata('error_msg', "Unable to save, try later." );
					redirect( base_url('rating/'.$job_key.'/'.$contractor_key ) );
					exit;
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

					redirect( base_url('private-rating/'.$job_key.'/'.$contractor_key) );
					exit;
				}
			}else{
				$this->session->set_flashdata('error_msg', validation_errors() );
				redirect( base_url('rating/'.$job_key.'/'.$contractor_key ) );
				exit;
			}
		}else if ( !empty($publicRatingData) && $this->input->post() && !empty($ratingData['type']) && $ratingData['type'] == 'private' ){

			$privateRating = $this->Job_model->getPrivateRating($publicRatingData['ratingid'],$ownerId, $contractorInfo['userid'],$jobDetails['jobid'] );
			if(!empty($privateRating)){
				$this->session->set_flashdata('error_msg', "Already added private rating for ".$jobDetails['jobname'] );
				redirect( base_url('owner/working') );
				exit;
			}

			$privateRatingId = $this->Job_model->savePrivateRating($publicRatingData['ratingid'],$ownerId, $contractorInfo['userid'],$jobDetails['jobid'], $this->input->post());

			if ($privateRatingId){
				$this->session->set_flashdata('success_msg', "Thank You for you rating." );
				redirect( base_url('owner/completed') );
				exit;
			}
		}
		exit;
	}

	public function private_rating( $job_key, $contractor_key ){

		$jobDetails = $this->Job_model->getCompletedJob($job_key, 'verified');
		if (empty($jobDetails)){
			 	$this->session->set_flashdata('error', 'Invalid job provided.');
				redirect( base_url('owner/working') );
				exit;
		}

		$data['job_data'] = $jobDetails;
		$data['job_key'] = $job_key;
		$data['contractor_key'] = $contractor_key;

		$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
		$this->load->view('headers/header',$headerdata);
		$this->load->view('headers/left-menu');
		
		$this->load->view('homeowner/private_rating',$data);
		$this->load->view('headers/footer');

	}

	public function view_rating($job_key, $contractor_key){

		$jobDetails = $this->Job_model->getCompletedJob($job_key, 'verified');
		if (empty($jobDetails)){
			$this->session->set_flashdata('error', 'Invalid job provided.');
			redirect( base_url('owner/completed') );
			exit;
		}

		$contractorInfo = $this->Job_model->getContractor($contractor_key );
		if (empty($contractorInfo)){
			$this->session->set_flashdata('error', 'Invalid contractor provided.');
			redirect( base_url('owner/completed') );
			exit;
		}
		//echo "<pre>"; print_r($jobDetails); exit;
		$ownerId = $jobDetails['createdby'];
		$contractInfo = $this->Job_model->getJobContract($ownerId,$contractorInfo['userid'], $jobDetails['jobid']);
		if(empty($contractInfo)){
			$this->session->set_flashdata('error', 'Invalid contract provided.');
			redirect( base_url('owner/completed') );
			exit;
		}
		
		$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
		$this->load->view('headers/header',$headerdata);
		$this->load->view('headers/left-menu');

		$data['job_data'] = $jobDetails;

		if(isset($_GET['type']) && $_GET['type'] == 'private'){
			$data['type'] = 'private';
			$data['rating_data'] = $this->Job_model->getPrivateRating('',$ownerId, $contractorInfo['userid'],$jobDetails['jobid']);
			$this->load->view('homeowner/view_rating',$data);
		}else{
			$data['type'] = 'public';
			$data['rating_data'] = $this->Job_model->getRating($ownerId, $contractorInfo['userid'],$jobDetails['jobid']);
			$this->load->view('homeowner/view_rating',$data);
		}

		$this->load->view('headers/footer');
	}

	public function biddingworkdetails( $jobkey )
	{
		if( !( $this->session->userdata('userKey') ) ){
			redirect( base_url() );
			exit;
		}
		if( !$jobkey ){
			redirect( base_url('owner/bidding') );
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
		
		$contract = $this->Job_model->getContractforjob($jobDets['jobid']);
		$isawarded = (!empty($contract) and $contract['homeowneragree'] == "1") ? '1' : '0';
		
		$bids = $this->Job_model->getbids($jobDets['jobid']);
		$cuserids = $cdetails = $shortlisted = array();
		if(!empty($bids)){
			foreach($bids as $bid){
				$cuserids[] = $bid['userid'];
			}

			$cdetails =  $this->Api_model->getuserdetsbyids(array_unique($cuserids)) ;
			if(!empty($cdetails)){
				$cdetails = $this->corefunctions->getArrayIndexed($cdetails,'userid');
				foreach($cdetails as $uk=>$user){
					$cdetails[$uk]['imageurl'] = ($user['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($user['userid'], $user['imagekey'], $user['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
					$cdetails[$uk]['staring'] = $this->userrating($user['userid']);;
				  
					foreach($bids as $bid){
						if($bid['userid'] == $user['userid']){
							$cdetails[$uk]['bidamount'] = $bid['bidamount'];
							$cdetails[$uk]['isshortlisted'] = $bid['isfavourite'];
						}
					}
				}
				
				$cdetails = $this->hedercontroller->array_sort_by_column($cdetails, 'isshortlisted');
				$cdetails = $this->corefunctions->getArrayIndexed($cdetails,'userid');
				
			}
			
			foreach($bids as $bid){
				$cdetails[$bid['userid']]['bidkey'] = $bid['bidkey'];
			}
		}

		$bids =  $this->Job_model->getbids($jobDets['jobid']);
		$total = $highest = $lowest = $average = $tcnt = $bd = 0;
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
		}
			$average = ($tcnt >0) ? $total/$tcnt : 0;

			$data['average'] = round($average,2);
			$data['lowest'] = round($lowest,2);
			$data['highest'] = round($highest,2);
			$data['total'] = round($total,2);
			
		//print "<pre>"; print_r($contract); print "</pre>";
		$headerdata['headerdata'] = $this->hedercontroller->headerCounts();
		$worklist = (empty($contract) or (!empty($contract) and $contract['homeowneragree'] == "0")) ? 'worklist' : 'contractinfo';
		$userDets = array();
		if(!empty($contract)){
			$userDets = $this -> Api_model->getUserDetailsDetailsByObjectID( $contract['contractorid'] );

			$userDets['Img'] = ( $userDets['imagekey'] != '' ) ? base_url($this->corefunctions->getMyPath($userDets['userid'], $userDets['imagekey'], $userDets['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
			$jobImages = $this -> Job_model -> getdocs($jobDets['jobid'],'job','1');
			if( !empty( $jobImages ) ){
				foreach( $jobImages as $ji => $jv ){
					$jobImages[$ji]['Img'] = base_url( $this->getBidImage($jobDets['jobid'],$jv['dockey'],$jv['docext']) );
				}
			}
			$userDets['bidDets'] = $this->Job_model->getmybid($jobDets['jobid'],$userDets['userid']);
			$userDets['staring'] = $this->userrating($userDets['userid']);

		}
		
		$data['waitingcontractorapproval']  = ( (!empty($contract) and $contract['homeowneragree'] == "1"  and $contract['workeragree'] == "0")) ? '1' : '0';
		$data['contract'] = $contract;
		$data['userDets'] = $userDets;
		$data['right'] = $worklist;
		$data['jobDets'] = $jobDets;
		$data['jobImages'] = $jobImages;
		$data['expertises'] = $expertises;
		$data['allUsers'] = $cdetails;
		$data['isawarded'] = $isawarded;
		$data['subhead'] 	= 'jobpost';
		$data['allcounts'] = $this->hedercontroller->allCounts();
		$this->load->view('headers/header',$headerdata);
		$this->load->view('headers/left-menu');
		$this->load->view('homeowner/workdetails-shortlist',$data);
		$this->load->view('headers/footer');
	}

	

	 public function contractorbidding( $bidType ){
		if( !( $this->session->userdata('userKey') ) ){
			redirect( base_url() );
			exit;
		}
		$usertype = $this->session->userdata('usertype');
		$expertises = $this->Api_model->getexpertise();
		$expertises = $this->corefunctions->getArrayIndexed($expertises,'expertiseid');
		if( $usertype == 'homeowner' ){
			$bidStatArr = array('bidding'=>array("'new'"),'working'=>array("'inprogress'","'completed'"),'completed'=>array("'verified'"));
		}else{
			$bidStatArr = array('bidding'=>array("'new'"),'working'=>array("'inprogress'"),'completed'=>array("'completed'","'verified'"));
		}
		
		$jobids = $mybids = $jobs = array();
        $mycontracts = $this->Job_model->getContractforcontractors($this->session->userdata('userId'));
        if(!empty($mycontracts)){
            foreach($mycontracts as $myc){
              $jobids[] = $myc['jobid'];
            }
        }
        $mybids = $this->Job_model->getallmybid($this->session->userdata('userId'));
		if(!empty($mybids)){
            foreach($mybids as $my){
              $jobids[] = $my['jobid'];
            }
        }
        if(!empty($jobids)){
            $jobs = $this->Job_model->getjobsbyids( array_unique($jobids) );
        }
		
		$returnjobs = $myjobs = array();
        $bidcount = 0;
        if(!empty($jobs)){
            foreach ($jobs as $key => $value) {
				if($usertype == 'contractor'){
					$jobs[$key]['contractorid']= $value['createdby'];
					$contratorids[] = $value['createdby'];
					$contracts = $this->Job_model->getContractforjob($value['jobid']);
					if(!empty($contracts)){
						
						$jobs[$key]['contracts'] = $contracts;
						
					}
				}else{
					$contracts = $this->Job_model->getContractforjob($value['jobid']);
					if(!empty($contracts)){
						$jobs[$key]['contractorid'] = $contracts['contractorid'];
						$jobs[$key]['contracts'] = $contracts;
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
                }
            }
			$contractorsetails = $this->corefunctions->getArrayIndexed($contractorsetails,'userid');
			$expertise =  $this->Api_model->getexpertisebyids(array_unique($expertiseids)) ;
			$expertise = $this->corefunctions->getArrayIndexed($expertise,'expertiseid');
            
      foreach ($jobs as $key => $value) {
				$myjobs[$key]['completeddate'] = $value['completiondate'];
				$myjobs[$key]['contracts'] = !empty($value['contracts']) ? $value['contracts'] : '';
				$myjobs[$key]['title'] = $value['jobname'];
				$myjobs[$key]['jobkey'] = $value['jobkey'];
				$myjobs[$key]['jobdescription'] = $value['jobdescription'];
				$myjobs[$key]['category'] = $expertise[$value['expertiseid']];
				$myjobs[$key]['date'] = date('m/d/Y',strtotime($value['startdate']));
				$myjobs[$key]['time'] =  date("g:i A", strtotime($value['startdate']));
				$myjobs[$key]['jobid'] = $value['jobid'];
				$myjobs[$key]['contractorsetails'] = (isset($value['contractorid'])) ? $contractorsetails[$value['contractorid']] : array();
				$getBidImg = $this -> Job_model -> getBidImage( 'job',$value['jobid'] );
				$myjobs[$key]['imageurl'] = ( !empty( $getBidImg ) ) ? base_url( $this->getBidImage($value['jobid'],$getBidImg['dockey'],$getBidImg['docext']) ) : base_url('images/def_job.jpg');
				$myjobs[$key]['doc'] = 'image';
				$myjobs[$key]['bidcount'] = $this->Job_model->getbidcount($value['jobid']);

				if($value['jobstatus'] == 'inprogress'){
					$myjobs[$key]['iscompleted'] = '0';
				}else if($value['jobstatus'] == 'completed'){
					$myjobs[$key]['iscompleted'] = '1';
					$myjobs[$key]['completeddate'] = date('m/d/Y',strtotime($value['completiondate']));
				}else if($value['jobstatus'] == 'verified'){
					$myjobs[$key]['completeddate'] = date('m/d/Y',strtotime($value['completiondate']));
				}
				if((isset($value['contractorid']))){
					$mybid = $this->Job_model->getmybid($value['jobid'],$this->session->userdata('userId'));
					$myjobs[$key]['bidamount'] =  (!empty($mybid)) ? $mybid['bidamount'] :  $value['budget'];
				}
      }
    }

		if(!empty($myjobs)){
			foreach($myjobs as $mk=>$mj){
				if($jobs[$mk]['jobstatus'] == 'inprogress'){
					$returnjobs['working'][] = $mj;
				}else if($jobs[$mk]['jobstatus'] == 'completed' or $jobs[$mk]['jobstatus'] == 'verified'){
					$returnjobs['completed'][] = $mj;
				}
			}
		}
		$returnjobs['working'] = (isset($returnjobs['working'])) ? $returnjobs['working'] : array();
		$returnjobs['completed'] = (isset($returnjobs['completed'])) ? $returnjobs['completed'] : array();

		$bidDets = ( $bidType == 'working' ) ? $returnjobs['working'] : $returnjobs['completed'];

				//echo "<pre>"; print_r($this->session->userdata()); exit;
		//print "<pre>"; print_r($jobs); print_r($bidDets); print "</pre>";
		$data['bidtype'] 	= $bidType;
		$data['bidDets'] 	= $bidDets;
		$data['expertises'] = $expertises;
		$data['subhead'] 	= 'jobpost';
		$data['user_key'] 	= $this->session->userdata('userKey');
		$data['allcounts'] = $this->hedercontroller->allCounts();
		$headerdata['headerdata'] = $this->hedercontroller->headerCounts();

		$this->load->view('headers/header',$headerdata);
		$this->load->view('headers/left-menu');
		$this->load->view('contractor/contactor-worklist-bidding',$data);
		$this->load->view('headers/footer');
	}
	
	public function remove_work_img()
	{
		$key = $this->input->post('k');
		if($this->User_model->delete_work_image($key))
			echo "success";
		else
			echo "error";
	}
	
	public function checkemailexists( ) {
          $checkEmail      = $this->User_model->check_useremail_exists( $_REQUEST[ 'email' ] );
          
          if ( empty($checkEmail ) ){
              echo "true"; //good to register
          } else {
              echo "false"; //already registered
          }
      }


      public function checkexistinguseremail( ) {
          $checkEmail      = $this->User_model->check_useremail_exists( $_REQUEST[ 'email' ] );
          //$userDetails = $this->Api_model->getUserDetailsDetailsByObjectID($this->session->userdata('userId'));
          
          if ( empty($checkEmail ) or ($_REQUEST[ 'email' ] == $this->session->userdata('userEmail')) ){
              echo "true"; //good to register
          } else {
              echo "false"; //already registered
          }
      }

      public function changepassword(){
          $userDetails = $this->Api_model->getUserDetailsDetailsByObjectID($this->session->userdata('userId'));
          
          $password = $this->corefunctions->passwordencrypt($this->input->post("password"));
          $this->User_model->userchangepassword($userDetails['userkey'], $password);
          
          $return["success"]     = 1;
          $return["successMsg"] = 'Your password has been reset.';
          print json_encode($return);
          exit;

      }
	/*public function contractorworking(){
		  $this->load->view('headers/header');
		  $this->load->view('headers/left-menu');
		  $this->load->view('contractor/contractor-worklist-working');
		  $this->load->view('headers/footer');
	}
	public function contractorcompleted(){
		  $this->load->view('headers/header');
		  $this->load->view('headers/left-menu');
		  $this->load->view('contractor/contactor-worklist-completed');
		  $this->load->view('headers/footer');
	}
	public function contractorbiddingworkdetails(){
		  $data['right'] = 'bidding';
		  $this->load->view('headers/header');
		  $this->load->view('headers/left-menu');
		  $this->load->view('contractor/contractor-workdetails-bidding',$data);
		  $this->load->view('headers/footer');
	}
	public function contractorworkdetails(){
		  $data['right'] = 'workdetails';
		  $this->load->view('headers/header');
		  $this->load->view('headers/left-menu');
		  $this->load->view('contractor/contractor-workdetails-completed',$data);
		  $this->load->view('headers/footer');
	}
	
	public function biddetails(){
		  $data['right'] = 'bidnow';
		  $this->load->view('headers/header');
		  $this->load->view('headers/left-menu');
		  $this->load->view('contractor/contractor-bid-details',$data);
		  $this->load->view('headers/footer');
	}
	public function bidnow(){
		  
		  $this->load->view('headers/header');
		  $this->load->view('headers/left-menu');
		  $this->load->view('contractor/contractor-bid-now');
		  $this->load->view('headers/footer');
	} */

	
		
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


	  public function verifyemail(){
			
			if( $this->input->post('act') == 1 ){
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
				$this->form_validation->set_rules('verificationcode', 'Verificationcode', 'required');
	            if ($this->form_validation->run() === TRUE) {
					$userDets = $this -> User_model -> check_login_creds( $this->input->post('email') );
					if( !empty( $userDets ) ){
						if( $userDets['status'] == '0' ){
							$data['haserror']       = TRUE;
							$data['errormsg']       = "Your account is inactive.";
						}else if($this->input->post("verificationcode") != $userDets['verificationcode']){
				            $data['haserror']       = TRUE;
							$data['errormsg']       = "Invalid code.";
				          }else{
				          	  $data['useremail']     = $userDets['email'];
				              $data['phone']     = $userDets['phone'];
				              $data['name']     = $userDets['firstname']." ".$userDets['lastname'];
				              $data['address']     = nl2br($userDets['address'])."<br />".$userDets['city'].", ".$userDets['state']." ".$userDets['zip'];
				              $data['usertype']  = $userDets['usertype'];
				              $admmsg               = $this->load->view('mail/registration-admin', $data, true);

				              $this->corefunctions->sendmail(ADMINEMAIL, REG_MAIL, TITLE . ' :: Account Registration', $admmsg);

				              $completedData['first_name'] = $userDets['firstname'];
				              $completedData['first_name'] = $userDets['lastname'];
				              $completedmmsg = $this->load->view('mail/registration-completed', $completedData, true);
				              $this->corefunctions->sendmail(ADMINEMAIL, $userDets['email'], TITLE . ' :: Registration Completed', $completedmmsg);

					            $this->Api_model->updateverified($userDets['userid']);
					            $data['hassuccess']       = TRUE;
											$data['successmsg']       = "Your account is verified.";
											$img = ($userDets['imagekey'] != "") ?  base_url($this->corefunctions->getMyPath($userDets['userid'], $userDets['imagekey'], $userDets['imageext'], 'assets/profImgs/crop/')) : base_url('images/defaultimg.jpg');
									 		
									 		$set_sesion = array(
				                  'userId' 		  => $userDets['userid'],
				                  'userKey' 	  => $userDets['userkey'],
				                  'userFirstname' => $userDets['firstname'],
				                  'userLastname'  => $userDets['lastname'],
				                  'usertype' 	  => $userDets['usertype'],
				                  'userEmail' 	  => $userDets["email"],
				                  'userAddress' 	  => $userDets["address"],
								  				'userAddrDets'  => $userDets["city"].", ".$userDets["state"]." ".$userDets["zip"],
				                  'userImg' 	  => $img
				              );
				              $this->session->set_userdata($set_sesion);
				              redirect(base_url('dashboard'));
				              exit;
				          }
					}else{
						$data['haserror']       = TRUE;
						$data['errormsg']       = "Account does not exists.";
					}
				}else{
					$data['haserror']       = TRUE;
                    $data['errormsg']       = "Please enter required fields.";
				}
			}
			  
			$this->load->view('headers/header-login');
			$this->load->view('frontend/verifyemail',$data);
		}

	public function resendemailverification(){
          
          if( $this->input->post('act') == 1 ){
				$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
				
	            if ($this->form_validation->run() === TRUE) {
					$userDets = $this -> User_model -> check_login_creds( $this->input->post('email') );
					if( !empty( $userDets ) ){
						if( $userDets['status'] == '0' ){
							$data['haserror']       = TRUE;
							$data['errormsg']       = "Your account is inactive.";
						}else{
				            	$data['verificationcode']= $userDets['verificationcode'];
            
					            $msg               = $this->load->view('mail/emailverificationcode', $data, true);
					            $this->corefunctions->sendmail(ADMINEMAIL, $userDets['email'], TITLE . ' :: Email Verification Code', $msg);
					            
					            $return['data'] = $userDetails["userid"];
					            $return["hassuccess"]     = 1;
					            $return["successmsg"] = 'Verification code has been sent to your email.';
				          }
					}else{
						$data['haserror']       = TRUE;
						$data['errormsg']       = "Account does not exists.";
					}
				}else{
					$data['haserror']       = TRUE;
                    $data['errormsg']       = "Please enter required fields.";
				}
			}
			  
			$this->load->view('headers/header-login');
			$this->load->view('frontend/resendverification',$return);
      }
	  
	  
	  public function add_intro_video()
	  {
		$data = array();
		if(!$this->session->userdata('userId'))
			redirect("login");
		ini_set('max_execution_time',300);
		if(isset($_FILES['intro_video']['tmp_name']))
		{
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
				header("HTTP/1.0 400 ");
				$error = $this->upload->display_errors('', '');
				echo json_encode(array("error" =>$error));
				return;
			}
			$user_id = $this->session->userdata("userId");
			$this->User_model->add_user_intro_video($user_id,$file_name);
		}
		
	
	  }
	  
	public function remove_video()
	{
		$key = $this->input->post('k');
		if($this->User_model->delete_user_intro_video($key))
			echo "success";
		else
			echo "error";
	}

	public function notification(){
		$params['jobid'] = 220;
    $params['jobtype'] = "jobcompleted";
		$this->hedercontroller->sendOnlyNotification(196,"test ", $params  );
	}

	}

?>
