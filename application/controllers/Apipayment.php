<?php
  if (!defined('BASEPATH'))
      exit('No direct script access allowed');
	class Apipayment extends CI_Controller {
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
    
    	public function apipaymentsuccesstest($key){
    		 $array['status'] = 1;
    		 $array['successMessage'] = "Payment Success";
    		 $contract = $this->Job_model->getContractbykey($key);
    		 //$transaction = Braintree_Transaction::find('2nntcm18');e6sqhhmm
    		 $result = Braintree_Transaction::releaseFromEscrow($contract['bt_transaction_id']);
    		 print "<pre>"; print_r($result); print "</pre>";
    		// header("Content-type: application/json; charset=utf-8");
			 //print json_encode($array);
	          exit;

	         // $this->load->view('apipayment/payment',$data);

    	}

    	public function apipaymentsuccess($key,$type="api"){
    		$data['type'] = $type;
    		if($type=="web"){
    			
    			$data['status'] = 'success';
				$data['key'] = $key;
				$this->load->view('headers/header');
				$this->load->view('headers/left-menu');
				$this->load->view('apipayment/paymentresult',$data);
				$this->load->view('headers/footer');
    		}else{

    			$data['status'] = 'success';
				$data['key'] = $key;
    			$this->load->view('apipayment/paymentresult',$data);

    		}
			
    	}
    	public function apipaymentfail($key){
			
			$data['status'] = 'fail';
			$data['key'] = $key;
	        $this->load->view('headers/header');
			$this->load->view('headers/left-menu');
			$this->load->view('apipayment/paymentresult',$data);
			$this->load->view('headers/footer');
    	}

    	

		public function makepayment($key){

			$contract = $this->Job_model->getContractbykey($key);
			/*$this->Job_model->updateContractorescrow($contract['contractid'],'',$contract['amount']);
		  	 $this->Job_model->create_notifications($contract['ownerid'],$contract['contractorid'],$contract['jobid'],'contract',$contract['contractid']);

		  	 redirect( base_url('apipaymentsuccess/'.$key) );
	  		 exit; */
	  		 
			$ctoken = Braintree_ClientToken::generate();
			//print $ctoken;
			 //print "<pre>"; print_r($_REQUEST); print "</pre>";
			$data['error'] = 0;
			//print "<pre>";print_r($this->input->post()); print "</pre>";
			if($this->input->post('act') == 1){
				$contract = $this->Job_model->getContractbykey($key);
				$user = $this->Api_model->getUserDetailsDetailsByObjectID($contract['contractorid']);
				$serviceamount = $this->hedercontroller->getserviceamount($contract['amount']);
                //print "<pre>";print_r($serviceamount); print_r($contract); print "</pre>";exit;
				if($user['bt_merchantid'] != ""){
                    try{
                        $result = Braintree_Transaction::sale([
                            'amount' => $contract['amount'],
                            'paymentMethodNonce' => $this->input->post('payment_method_nonce'),
                            'merchantAccountId' => $user['bt_merchantid'],
                            'serviceFeeAmount' => $serviceamount,
                            'options' => [
                                'submitForSettlement' => true,
                                // 'holdInEscrow' => true,
                            ]
                        ]);
                    }
                    catch(Braintree\Exception\Authorization $e)
                    {
                        redirect( base_url('apipaymentfail/'.$key) );
                    }
                    catch(Braintree\Exception\Authentication $e)
                    {
                        redirect( base_url('apipaymentfail/'.$key) );
                    }
                    catch(Braintree\Exception\NotFound $e)
                    {
                        redirect( base_url('apipaymentfail/'.$key) );
                    }
                    catch(Braintree\Exception\ServerError $e)
                    {
                        redirect( base_url('apipaymentfail/'.$key) );
                    }
                    //print "<pre>"; print_r($result); print "</pre>";
									$bresponse = json_encode($result);
							$this->Job_model->create_braintree_response($contract['ownerid'],$contract['contractorid'],$contract['jobid'],$result,'payment');

							$input['type'] = 'payment from api';
							$input['contract'] = $contract;
							$input['bt_result'] = $result;
							$input['date'] = date('m/d/Y H:i:s A');
							$this->Job_model-> braintreeresponse($input);
					  
					  //$this->Job_model->updateContractorfields('homeowneragree',$key);

					  if($result->success){
					  	 $esresult = Braintree_Transaction::holdInEscrow($result->transaction->id);
					  	 $bresponse = json_encode($esresult);
						 $this->Job_model->create_braintree_response($contract['ownerid'],$contract['contractorid'],$contract['jobid'],$esresult,'escrow');
						 	$binput['type'] = 'escrow from api';
							$binput['contract'] = $contract;
							$binput['bt_result'] = $esresult;
							$binput['date'] = date('m/d/Y H:i:s A');
							$this->Job_model-> braintreeresponse($binput);
						 /*if($_SERVER['CI_ENV']!=='production') {
					  	 	$teresult = Braintree_Test_Transaction::settle($result->transaction->id);
					  	 	$bresponse = json_encode($teresult);
							$this->Job_model->create_braintree_response($contract['ownerid'],$contract['contractorid'],$contract['jobid'],$teresult);
					  	 }*/
					  	 //print "<pre>";print_r($esresult); print "</pre>";
					  	 if($esresult->success){
					  	 	$transaction = $result->transaction;
						  	 $bt_transaction_id = $result->transaction->id;
						  	 $amount = $result->transaction->amount;
						  	$cardtype = $result->transaction->creditCard['cardType'];
						  	 $cardnumber = $result->transaction->creditCard['last4'];
						  	 $this->Job_model->updateContractorescrow($contract['contractid'],$bt_transaction_id,$amount,$serviceamount);
						  	 $this->Job_model->create_payment($contract['contractid'],$cardtype,$cardnumber,$result);
						  	 //$this->Job_model->create_notifications($contract['ownerid'],$contract['contractorid'],$contract['jobid'],'contract',$contract['contractid']);
						  	 $this->sendcontractmail($contract['contractid']);
						  	 redirect( base_url('apipaymentsuccess/'.$key) );
					  		 exit;
					  	 }
					  	 
					  }else{
					  	redirect( base_url('apipaymentfail/'.$key) );
				  		 exit;
					  	//$data['error'] = 0;
					  	//$data['errormsg'] = 'Your transaction failed.';
					  }
				}else{
					redirect( base_url('apipaymentfail/'.$key) );
				  	exit;
				  	//$data['error'] = 0;
				  	//$data['errormsg'] = 'Your transaction failed.';
				  }
				
			}
			  //$ctoken = '';
			  $data['ctoken'] = $ctoken;
			  //
			  //$this->load->view('headers/header');
			  $this->load->view('apipayment/payment',$data);
			  //$this->load->view('headers/footer');
		}
		public function bidconfirm($key){
			  $this->Job_model->updateContractorfields('workeragree',$key);
			  $contract = $this->Job_model->getContractbykey($key);
			  $this->Job_model->updateJobfields('jobstatus','inprogress',$contract['jobid']);
			  $this->load->view('headers/header-login');
			  $this->load->view('apipayment/payment');
		}
		
		public function confirmpayment($key){
			/* Fake Payment 
			$contract = $this->Job_model->getContractbykey($key);
			$this->Job_model->updateContractorescrow($contract['contractid'],'',$contract['amount']);
		  	 $this->Job_model->create_notifications($contract['ownerid'],$contract['contractorid'],$contract['jobid'],'contract',$contract['contractid']);

		  	 redirect( base_url('owner/bidding') );
	  		 exit; 

	  		  Fake Payment */
			
			$ctoken = Braintree_ClientToken::generate();
			//print $ctoken;
			 //print "<pre>"; print_r($_REQUEST); print "</pre>";
			$data['error'] = 0;
			//print "<pre>";print_r($this->input->post()); print "</pre>";
			if($this->input->post('act') == 1){
				$contract = $this->Job_model->getContractbykey($key);
				$user = $this->Api_model->getUserDetailsDetailsByObjectID($contract['contractorid']);
				//print "<pre>";print_r($user); print_r($contract); print "</pre>";
				if($user['bt_merchantid'] != ""){
					$serviceamount = $this->hedercontroller->getserviceamount($contract['amount']);
                    try {
                        $result = Braintree_Transaction::sale([
                            'amount' => $contract['amount'],
                            'paymentMethodNonce' => $this->input->post('payment_method_nonce'),
                            'merchantAccountId' => $user['bt_merchantid'],
                            'serviceFeeAmount' => $serviceamount,
                            'options' => [
                                'submitForSettlement' => true,
                                // 'holdInEscrow' => true,
                            ]
                        ]);
                    }
                    catch(Braintree\Exception\Authorization $e)
                    {
                        redirect( base_url('apipaymentfail/'.$key) );
                    }
                    catch(Braintree\Exception\Authentication $e)
                    {
                        redirect( base_url('apipaymentfail/'.$key) );
                    }
                    catch(Braintree\Exception\NotFound $e)
                    {
                        redirect( base_url('apipaymentfail/'.$key) );
                    }
                    catch(Braintree\Exception\ServerError $e)
                    {
                        redirect( base_url('apipaymentfail/'.$key) );
                    }

					$bresponse = json_encode($result);
					$this->Job_model->create_braintree_response($contract['ownerid'],$contract['contractorid'],$contract['jobid'],$result,'payment');
							$binput['type'] = 'payment from web';
							$binput['contract'] = $contract;
							$binput['bt_result'] = $result;
							$binput['date'] = date('m/d/Y H:i:s A');
							$this->Job_model-> braintreeresponse($binput);
					 /*print "<pre>";print_r($result->transaction->creditCard['cardType']);print_r($result->transaction->creditCard); print_r($result); print "</pre>";
					  exit; */
					  //$this->Job_model->updateContractorfields('homeowneragree',$key);
					  if($result->success){
					  	 $esresult = Braintree_Transaction::holdInEscrow($result->transaction->id);
					  	 $bresponse = json_encode($esresult);
						 $this->Job_model->create_braintree_response($contract['ownerid'],$contract['contractorid'],$contract['jobid'],$esresult,'escrow');
						 	$binput['type'] = 'escrow from web';
							$binput['contract'] = $contract;
							$binput['bt_result'] = $esresult;
							$binput['date'] = date('m/d/Y H:i:s A');
							$this->Job_model-> braintreeresponse($binput);
					  	/*if(ENVIRONMENT !=='production') {
					  	 	$teresult = Braintree_Test_Transaction::settle($result->transaction->id);
					  	 	$bresponse = json_encode($teresult);
							$this->Job_model->create_braintree_response($contract['ownerid'],$contract['contractorid'],$contract['jobid'],$teresult);
					  	 }  */
					  	 //print "<pre>";print_r($bresponse); print_r($teresult); print "</pre>";exit;
					  	 if($esresult->success){
							 
					  	 	 $transaction = $result->transaction;
						  	 $bt_transaction_id = $result->transaction->id;
						  	 $amount = $result->transaction->amount;
						  	 $cardtype = $result->transaction->creditCard['cardType'];
						  	 $cardnumber = $result->transaction->creditCard['last4'];
						  	 $this->Job_model->updateContractorescrow($contract['contractid'],$bt_transaction_id,$amount,$serviceamount);
						  	 $this->Job_model->create_payment($contract['contractid'],$cardtype,$cardnumber,$result);
						  	 //$this->Job_model->create_notifications($contract['ownerid'],$contract['contractorid'],$contract['jobid'],'contract',$contract['contractid']);
						  	 $this->sendcontractmail($contract['contractid']);
						  	 redirect( base_url('apipaymentsuccess/'.$key.'/web') );
					  		 exit;
					  	 }
					  	 
					  }else{
					  	redirect( base_url('apipaymentfail/'.$key) );
				  		 exit;
					  	//$data['error'] = 0;
					  	//$data['errormsg'] = 'Your transaction failed.';
					  }
				}else{
					redirect( base_url('apipaymentfail/'.$key) );
				  	exit;
				  	//$data['error'] = 0;
				  	//$data['errormsg'] = 'Your transaction failed.';
				  }
				
			}
			  //$ctoken = '';
			  $data['ctoken'] = $ctoken;
			  //
			  //$this->load->view('headers/header');
			  $this->load->view('apipayment/webpayment',$data);
			  //$this->load->view('headers/footer');
		}


		public function sendcontractmail($contractid){
			  $contract = $this->Job_model->getcontractbyid($contractid);
			  $paymentdetails =  $this->Job_model->getpaymentcontractid($contractid);
			  $homeOwnerInfo = $this->Api_model->getUserDetailsDetailsByObjectID($contract['ownerid']);
			  $contractor = $this->Api_model->getUserDetailsDetailsByObjectID($contract['contractorid']);
			  $job = $this->Job_model->getjobbyid($contract['jobid']);

			  $data['user'] 		   = $homeOwnerInfo;
              $data['job']  		   = $job;
              $data['contract']  	   = $contract;
              $data['paymentdetails']  = $paymentdetails;
              $data['contractor']  = $contractor;
              
              $msg               = $this->load->view('mail/paymentreceipt', $data, true);
              $this->corefunctions->sendmail(ADMINEMAIL, $homeOwnerInfo['email'], TITLE . ' :: Receipt', $msg);
		}

	}

?>
