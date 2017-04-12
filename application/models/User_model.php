<?php
  class User_model extends CI_Model {
      public function __construct() {
          $this->load->database();
          $this->load->dbforge();
          $this->load->library(array(
              'corefunctions'
          ));
      }
      public function filter($data) {
          $data = trim(strip_tags($data));
          return $data;
      }
      public function get_states() {
          $this->db->order_by("state_prefix", "asc");
          $query = $this->db->get("state");
          //print $this->db->last_query();
          return $query->result_array();
      }
    
     
      public function check_login_creds($email) {
          $sql   = 'SELECT * FROM ' . $this->db->dbprefix('users') . ' WHERE email = ? limit 1';
          $query = $this->db->query($sql, array(
              $email
          ));
          //print $this->db->last_query();
          return $query->row_array();
      }
      public function check_useremail_exists($email) {
          $myarray = array(
              "email" => $email
          );
          $query   = $this->db->get_where("users", $myarray);
          // print $this->db->last_query();
          return $query->row_array();
      }
     
      public function update_img_User($userid, $imgkey, $imgext) {
          $data = array(
              'imagekey' => $imgkey,
              'imageext' => $imgext
          );
          $this->db->where('userid', $userid);
          $this->db->update('users', $data);
          //print $this->db->last_query();
      }
	
      public function Updatecropedimage($tempid,$filekey) {
          $data = array(
              'tempimgkey' => $filekey
          );
          $this->db->where('tempimgid', $tempid);
          $this->db->update('tempimage', $data);
          //print $this->db->last_query();
      }	
	  
      public function getuserbyid($id) {
          $myarray = array(
              "userid" => $id
          );
          $query   = $this->db->get_where("users", $myarray);
          // print $this->db->last_query();
          return $query->row_array();
      }
      public function check_login_creds_user($email) {
          $sql   = 'SELECT * FROM ' . $this->db->dbprefix('users') . ' WHERE email = ? limit 1';
          $query = $this->db->query($sql, array(
              $email
          ));
          //print $this->db->last_query();
          return $query->row_array();
      }
      public function userchangepassword($userkey, $password) {
          $data = array(
              'password' => $password
          );
          $this->db->where('userkey', $userkey);
          $this->db->update('users', $data);
          //print $this->db->last_query();
      }
      
      public function update_lastlogin($userid) {
          $data = array(
              'lastlogindate' => time(),
              'updatedate' => time()
          );
          $this->db->where('userid', $userid);
          $this->db->update('users', $data);
          //print $this->db->last_query();
      }
      public function check_user_by_email($email) {
          $sql   = 'SELECT userid, userkey, firstname, lastname, email,status FROM ' . $this->db->dbprefix('users') . ' WHERE email = ? limit 1';
          $query = $this->db->query($sql, array(
              $email
          ));
          //print $this->db->last_query();
          return $query->row_array();
      }
   
      public function updatePasswordKey($userid, $key) {
          $data = array(
              'resetpwdkey' => $key,
              'resetpwdtime' => time(),
              'updatedate' => time()
          );
          $this->db->where('userid', $userid);
          $this->db->update('users', $data);
          //print $this->db->last_query();
      }
     
      public function recoverkey_exists($resetpwdkey) {
          $sql   = 'select * from ' . $this->db->dbprefix('users') . ' where resetpwdkey = ? limit 1';
          $query = $this->db->query($sql, array(
              $resetpwdkey
          ));
          return $query->row_array();
      }
      public function update_password($userid, $password) {
          $data = array(
              'password' => $password,
              'updatedate' => time()
          );
          $this->db->where('userid', $userid);
          $this->db->update('users', $data);
          //print $this->db->last_query();
      }
      
      public function get_temp_det($key) {
          $myarray = array(
              "tempimgkey" => $key
          );
          $query   = $this->db->get_where("tempimage", $myarray);
          // print $this->db->last_query();
          return $query->row_array();
      }
      public function create_temp_img($tempimgkey, $tempimgext, $width, $height,$originalname="") {
          $data = array(
              'tempimgkey' => $tempimgkey,
              'tempimgext' => $tempimgext,
              'width' => $width,
              'height' => $height,
              'originalname'=>$originalname,
              'createdate' => time()
          );
          $this->db->insert('tempimage', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }
     
      
      public function user_by_key($userkey) {
          $sql   = 'SELECT * FROM ' . $this->db->dbprefix('users') . ' WHERE userkey = ?  limit 1';
          $query = $this->db->query($sql, array(
              $userkey
          ));
          //print $this->db->last_query();
          return $query->row_array();
      }
     
      public function getuserbykey($userkey) {
          $myarray = array(
              "userkey" => $userkey
          );
          $query   = $this->db->get_where("users", $myarray);
          // print $this->db->last_query();
          return $query->row_array();
      }
      public function Getusername($userid) {
          $sql   = ' SELECT firstname,lastname FROM ' . $this->db->dbprefix('users') . ' where userid = ? and status = ? ';
          $query = $this->db->query($sql, array(
              $userid,
              '1'
          ));
          return $query->row_array();
      }
     
	 
      public function getuserdetsbyid($userid) {
          $sql   = ' SELECT email,userkey,userid,firstname,lastname,imagekey,imageext,usertype,phone,address,state,city,zip,bt_merchantid,bt_accountverified FROM ' . $this->db->dbprefix('users') . ' where userid = ? and status = ? ';
          $query = $this->db->query($sql, array(
             $userid, '1'
          ));
          return $query->row_array();
      }	 
	 
     public function update_passwordverificationcode($userid, $key) {
          $data = array(
              'passwordverification' => $key,
              'updatedate' => time()
          );
          $this->db->where('userid', $userid);
          $this->db->update('users', $data);
          //print $this->db->last_query();
      }

      public function getuseractivities($jobids,$recent="new") {
          $sql   = ' SELECT a.historyid,a.userid,a.jobid,a.activityid,b.activity,b.activitytext,a.createdate FROM ' . $this->db->dbprefix('history') . ' as a LEFT JOIN ' . $this->db->dbprefix('activity') . ' as b ON a.activityid = b.activityid where a.jobid in ('.$jobids.')and a.status = ? and a.touserid = ?  order by createdate desc';
          if($recent == "new"){
            $sql   .= " limit ".LIMIT;
          }
        
          $query = $this->db->query($sql, array(
             '1',$this->session->userdata('userId')
          ));
          return $query->result_array();
      } 
	 
     
		public function updateUserProfile($userkey, $input) {
			$data = array(
				'firstname' => $input['firstname'],
				'lastname' => $input['lastname'],
				'address' => $input['address'],
				'state' => $input['state'],
        'email' => $input['email'],
				'city' => $input['city'],
				'zip' => $input['zip'],
				'phone' => $input['phone'],
        'dob'      => (isset($input['dob']) and $input['dob'] != '') ? date('Y-m-d',strtotime($input['dob'])) : '0000-00-00',
        'experiance'      => (isset($input['experiance']) and $input['experiance'] != '') ? $input['experiance'] : '',
        'businessdescription'      => (isset($input['businessdescription']) and $input['businessdescription'] != '') ? $input['businessdescription'] : '',
        'updatedate' => time()
			);
			$this->db->where('userkey', $userkey);
			$this->db->update('users', $data);
			//print $this->db->last_query();
     // exit;
		}
		
		public function updateContractorDetails($userid) {
			$data = array(
				'companyname'       =>  $this->filter($this->input->post('companyname')),
				'taxid'    			=>  $this->filter($this->input->post('taxid')),
				'companycity'       =>  $this->filter($this->input->post('companycity')),
				'companystate'      =>  $this->filter($this->input->post('companystate')),
				'companyzip'    	=>  $this->filter($this->input->post('companyzip')),
				'companyaddress'	=>  $this->filter($this->input->post('companyaddress')),
				'license'     		=>  ($this->input->post('license') != "") ? $this->filter($this->input->post('license')) : '',
				'insurance'    		=> ($this->input->post('insurance') != "") ?  $this->filter($this->input->post('insurance')) : '',
				'routingnumber'     =>  $this->filter($this->input->post('routingnumber')),
				'accountnumber'     =>  $this->filter($this->input->post('accountnumber')),
				
				
				'overview_experience'     =>  $this->filter($this->input->post('overview_experience')),
				'introduction'     =>  $this->filter($this->input->post('introduction')),
				'certifications'     =>  $this->filter($this->input->post('certifications')),
				'notable_work'     =>  $this->filter($this->input->post('notable_work')),
				'us_veteran'     =>  $this->filter($this->input->post('us_veteran')),
				
        
				'updatedate'    	=> time()
			);
			$this->db->where('userid', $userid);
			$this->db->update('contractor_details', $data);
		}
	
	 	public function add_work_image($dockey,$docext,$parenttype,$parentid,$originalname)
		{
        	$doctype = ($docext == "jpg" || $docext == "jpeg" || $docext == "png" || $docext == "gif" ) ? "image" : "doc";
        	$data = array(
              'dockey' => $dockey,
              'docext' => $docext,
              'parenttype' => $parenttype,
              'parentid' => $parentid,
              'originalname' => $originalname,
              'doctype' => $doctype,
              'status' => '1',
              'createdate' => time()
          	);
          	$this->db->insert('docs', $data);
          	$insert_id = $this->db->insert_id();
          	$this->db->trans_complete();
          	//print $this->db->last_query();
          	return $insert_id;

      } 
	  
	  public function delete_work_image($key)
	  {
		  $this->db->select("docext")->from("docs")->where("dockey",$key)->where("parentid",$this->session->userdata("userId"));
		  $image = $this->db->get()->row_array();
		  
		  if(!empty($image['docext']))
		  {
			  $this->db->where("dockey",$key)->where("parentid",$this->session->userdata("userId"));
			  $this->db->delete("docs");
			  @unlink("assets/docs/00000/".$key.".".$image['docext']);
			  return true;
		  }
		  return false;
	  }
	  
	  public function get_work_images($user_id)
	  {
		  $this->db->select("*")->from("docs")->where("parenttype","work_image")->where("parentid",$user_id);
		  $work_images = $this->db->get()->result_array();
		  return $work_images;
	  }
	  
	  public function add_user_intro_video($user_id, $video_name)
	  {
			$data['intro_video'] = $video_name;
			$this->db->where('userid',$user_id);	  
			$this->db->update("users",$data);
	  }
	  
	  public function delete_user_intro_video($key)
	  {
		  $this->db->select("intro_video")->from("users")->where("userkey",$key)->where("userid",$this->session->userdata("userId"));
		  $video = $this->db->get()->row_array();
		  
		  if(!empty($video['intro_video']))
		  {
			  $data['intro_video'] = "";
			  $this->db->where("userkey",$key)->where("userid",$this->session->userdata("userId"));
			  $this->db->update("users",$data);
			  @unlink("assets/videos/".$video['intro_video']);
			  return true;
		  }
		  return false;
	  }
	  
	  public function get_contractor_rating($contractor_id)
	  {
		  $this->db->select("avg(rating) as rating")->from("rating")->where("contractorid",$contractor_id);
		  $rating = $this->db->get()->row_array();
		  $this->db->select("count(contractid) as total_jobs")->from("contracts")->where("contractorid",$contractor_id);
		  $this->db->where("escrow_released",1);
		  $total_jobs = $this->db->get()->row_array();
		  $rating['total_jobs'] = $total_jobs['total_jobs'];
		  return $rating;
	  }

    public function get_videos($user_type, $getLoginVideo){
      if($getLoginVideo){
        $this->db->select(array("url","action"))->from("videos")->where("type",$user_type)->where("active",1);
      }else{
        $this->db->select(array("url","action"))->from("videos")->where("type",$user_type)->where("active",1)->where(" action != 'login' ");
      }
      $videos = $this->db->get()->result_array();
      $data = array();
      foreach($videos as $video){
        $data[ $video['action']] = $video['url'];
      }
      return $data;
    }

    public function update_signin_count($user_id){
      $sql = "Update efy_users set signin_count = signin_count+1 where userid = ".$user_id;
      $this->db->query($sql);
    }
  }
?>