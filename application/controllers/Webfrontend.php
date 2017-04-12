<?php
  if (!defined('BASEPATH'))
      exit('No direct script access allowed');
	class Webfrontend extends CI_Controller {
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
		  
		
          
    }
    	public function index(){
    		$header['page'] = 'index';
    		$header['title'] = 'Home : '.TITLE;
    		$header['keywords'] = 'Efynch, efynch,Efinch.com, Homeowner,contractor, bids, Collection of bids, Maryland Homeowners, Maryland, Property managers, property, Electrical, Plumbing, Handyman, Lifting, Assembly, Moving, or Digging, construction, Roofing / Gutters, Painting, HVAC.';
    		$header['description'] = 'EFynch.com is a Home Improvement Platform which helps to connect homeowners and contractors when projects and work is needed.';

    		$this->load->view('Webfrontend/header',$header);
			$this->load->view('Webfrontend/index');
			$this->load->view('Webfrontend/footer');
    	}
    	public function info(){
    		$header['page'] = 'services';
    		$header['title'] = 'Info : '.TITLE;
    		$header['keywords'] = 'Efynch, efynch,Efinch.com, Homeowner,contractor, bids, Collection of bids, Maryland Homeowners, Maryland, Property Managers, Services, Communications, a secure platform for payments, Stress of sales, Service providers, Labourers, Appliances, Handy Man, Moving, Windows and Siding, Electrical, HVAC, Painting, Other, Flooring, Flooring, Roofing / Gutters.';
    		$header['description'] = 'EFynch and EFynch.com is here to help you with all you home improvement needs.';

    		$this->load->view('Webfrontend/header',$header);
			$this->load->view('Webfrontend/info');
			$this->load->view('Webfrontend/footer');
    	}
    	public function community(){
    		$header['page'] = 'tips';

    		$header['title'] = 'Community : '.TITLE;
    		$header['keywords'] = 'Efynch, efynch,Efinch.com, Homeowner,contractor, bids, Collection of bids, Maryland Homeowners, Maryland, Property Managers, Services, Communications, a secure platform for payments, Stress of sales, Service providers, Labourers, Appliances, Handy Man, Moving, Windows and Siding, Electrical, HVAC, Painting, Other, Flooring, Flooring, Roofing / Gutters, Advice, Features.';
    		$header['description'] = 'EFynch and EFynch.com is here to help you with all you home improvement needs.';

    		$this->load->view('Webfrontend/header',$header);
			$this->load->view('Webfrontend/community');
			$this->load->view('Webfrontend/footer');
    	}
    	public function contactus(){
    		$header['title'] = 'Contact Us : '.TITLE;
    		$header['keywords'] = 'Efynch, efynch,Efinch.com, Homeowner,contractor, bids, Collection of bids, Maryland Homeowners, Maryland, Property Managers, Services, Communications, a secure platform for payments, Stress of sales, Service providers, Labourers, Appliances, Handy Man, Moving, Windows and Siding, Electrical, HVAC, Painting, Other, Flooring, Flooring, Roofing / Gutters, Advice, Features.';
    		$header['description'] = 'EFynch welcomes your valuable input and questions. Please let us know what you are thinking and help us grow.';

			if( $this->input->post('act') == 'contact' ){
				$privatekey = GOOGLE_CAPTCHA_SECRET;
				$capts   = $this->input->post('g-recaptcha-response');
				$chk     = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$privatekey."&response=" . $capts . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
				$capt    = array();
				$capt    = json_decode($chk, true);
				$success = 0;
				if ($capt['success'] == 1) {
					$success = 1;
				}
				
				$this->form_validation->set_rules( 'firstname', 'First Name', 'required' );
				$this->form_validation->set_rules( 'lastname', 'Last Name', 'required' );
				$this->form_validation->set_rules( 'email', 'Email', 'required' );
				$this->form_validation->set_rules( 'phone', 'Phone', 'required' );
				$this->form_validation->set_rules( 'comments', 'Comments', 'required' );
				$this->form_validation->set_rules( 'g-recaptcha-response', 'Security Code', 'required' );
				
				if ( $this->form_validation->run() === FALSE ) {
					$data[ 'haserror' ] = TRUE;
					$data[ 'errormsg' ] = "Please enter required details";
				}else if ($success == 0) {
					$data['haserror'] = TRUE;
					$data['errormsg'] = "Please enter correct Security Code";
				}else{
					/*Send Mail starts here  */
						$data[ 'firstname' ] = $this->input->post( 'firstname' );
						$data[ 'lastname' ]  = $this->input->post( 'lastname' );
						$data[ 'comments' ]  = $this->input->post( 'comments' );
						$data[ 'phone' ] 	 = $this->input->post( 'phone' );
						$data[ 'email' ]     = $this->input->post( 'email' );
						$msg                 = $this->load->view('mail/contactusmail', $data, true );
						$email         		 = ADMINEMAIL;
						$this->corefunctions->sendmail( ADMINEMAIL, ADMINEMAIL, 'Efynch.com : Contact Us', $msg );


						$msg                 = $this->load->view('mail/contactus-usermail', $data, true );
						$email         		 = ADMINEMAIL;
						$this->corefunctions->sendmail( ADMINEMAIL, $this->input->post( 'email' ), 'Efynch.com : Contact Us', $msg );
					/*Send Mail ends here  */

					
					
					$data['success'] = TRUE;
					$data['successmsg'] = "Your Inquiry has been submitted successfully.";
				}
			}
			
    		$header['page'] = 'contact';
    		$this->load->view('Webfrontend/header',$header);
			$this->load->view('Webfrontend/contact',$data);
			$this->load->view('Webfrontend/footer');
    	}

    	public function termsandcondition(){
    		
    		$header['title'] = 'Terms and Conditions : '.TITLE;
    		
    		$this->load->view('Webfrontend/header',$header);
			$this->load->view('Webfrontend/termandconditions');
			$this->load->view('Webfrontend/footer');
    	}
    	public function efynchtermsandcondition(){
    		
    		$header['title'] = 'Terms and Conditions : '.TITLE;
    		//$this->load->view('Webfrontend/header',$header);
			$this->load->view('Webfrontend/termandconditions-api');
			//$this->load->view('Webfrontend/footer');
    	}

    	public function videoAPI(){
    		
    		
			$this->load->view('Webfrontend/api-video');
			
    	}
    	
	}

?>
