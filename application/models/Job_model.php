<?php
  class Job_model extends CI_Model {
      public function __construct() {
          $this->load->database();
          $this->load->dbforge();
          $this->load->library(array(
              'corefunctions',
              'hedercontroller'
          ));
      }
      public function filter($data) {
          $data = trim(strip_tags($data));
          return $data;
      }
     
      public function create_job($jobkey, $expertiseid, $input) {
          $data = array(
              'jobkey' => $jobkey,
              'jobname' => $input['jobname'],
              'jobdescription' => $input['jobdescription'],
              //'location' => $input['location'],
              'budget' => (isset($input['budget'])) ? $input['budget'] : 0,
              'expertiseid' => $expertiseid,
              'completiondate' => date('Y-m-d',strtotime($input['completiondate'])),
              'completeddate'=>(isset($input['completeddate'])) ? date('Y-m-d',strtotime($input['completeddate'])) : '',
              'address' => $input['address'],
              'state' => $input['state'],
              'city' => $input['city'],
              'zip' => $input['zip'],
              'startdate' => $input['startdate'],
              'daysposted' => $input['daysposted'],



			  'timeframe' => $input['timeframe'],
              'indoor' => $input['indoor'],
              'hometype' => $input['hometype'],
              'starting_state' => $input['starting_state'],
              'total_stories' => $input['total_stories'],
			  'material_option' => $input['material_option'],
              'rate_type' => $input['rate_type'],
              'location' => $input['location'],
              'year_constructed' => $input['year_constructed'],
              'current_condition' => $input['current_condition'],
              'first_problem_notice' => $input['first_problem_notice'],
			  'resolution' => $input['resolution'],
              'measurements' => $input['measurements'],
              'material_preferences' => $input['material_preferences'],
              'purchased_materials' => $input['purchased_materials'],
              'access_to_area' => $input['access_to_area'],
              'your_availability' => $input['your_availability'],
			  'relevant_info' => (isset($input['relevant_info'])) ? $input['relevant_info'] : '',





              'latitude' =>  (isset($input['geoLocation']['lat'])) ? $input['geoLocation']['lat'] : '',
              'longitude' =>  (isset($input['geoLocation']['lng'])) ? $input['geoLocation']['lng'] : '',
              'jobstatus' => 'new',
              'createdby' => $this->session->userdata('userId'),
              'status' => '1',
              'createdate' => time()
          );

         // echo '<pre>';print_r($data);exit;
          $this->db->insert('jobs', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          $job_data['toname'] = $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname');
          $userEmail = $this->session->userdata('userEmail');
          $msg   = $this->load->view('mail/owner-post-job', $job_data, true);
          $this->corefunctions->sendmail(ADMINEMAIL, $userEmail, TITLE . ' :: Job Post', $msg);

		      return $insert_id;
      }

       public function updatejobs($input,$jobid,$expertiseid) {
        
        
          $data = array(
              'jobname' => $input['jobname'],
              'jobdescription' => $input['jobdescription'],
              //'location' => $input['location'],
              'budget' => (isset($input['budget'])) ? $input['budget'] : 0,
              'expertiseid' => $expertiseid,
              'completiondate' => date('Y-m-d',strtotime($input['completiondate'])),
              'completeddate'=>date('Y-m-d',strtotime($input['completeddate'])),
              'address' => $input['address'],
              'state' => $input['state'],
              'city' => $input['city'],
              'zip' => $input['zip'],
              'startdate' => $input['startdate'],
              'daysposted' => $input['daysposted'],
			  
			  
			  'timeframe' => $input['timeframe'],
              'indoor' => $input['indoor'],
              'hometype' => $input['hometype'],
              'starting_state' => $input['starting_state'],
              'total_stories' => $input['total_stories'],
			  'material_option' => $input['material_option'],
              'rate_type' => $input['rate_type'],
              'location' => $input['location'],
              'year_constructed' => $input['year_constructed'],
              'current_condition' => $input['current_condition'],
              'first_problem_notice' => $input['first_problem_notice'],
			  'resolution' => $input['resolution'],
              'measurements' => $input['measurements'],
              'material_preferences' => $input['material_preferences'],
              'purchased_materials' => $input['purchased_materials'],
              'access_to_area' => $input['access_to_area'],
              'your_availability' => $input['your_availability'],
			  'relevant_info' => $input['relevant_info'],
			  
			  
              'latitude' =>  (isset($input['geoLocation']['lat'])) ? $input['geoLocation']['lat'] : '',
              'longitude' =>  (isset($input['geoLocation']['lng'])) ? $input['geoLocation']['lng'] : '',
              'updatedate' => time()
          );
          $this->db->where('jobid', $jobid);
          $this->db->update('jobs', $data);
          //print $this->db->last_query();
      }
      
       public function deletejob($jobid) {
        
        
          $data = array(
              'status' => '0',
              'updatedate' => time()
          );
          $this->db->where('jobid', $jobid);
          $this->db->update('jobs', $data);
          //print $this->db->last_query();
      }
      
      

      public function createdoc($dockey,$docext,$parenttype,$parentid,$originalname){
        $docext = strtolower($docext);
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

      public function getmyjobs($userid) {
          $sql   = "SELECT * FROM " . $this->db->dbprefix('jobs') . " WHERE createdby = ? and status = '1' order by createdate desc";
          $query = $this->db->query($sql, array(
              $userid
          ));
          //print $this->db->last_query();
          return $query->result_array();
      }
      public function getjobbyid($jobid) {
          $sql   = 'SELECT * FROM ' . $this->db->dbprefix('jobs') . ' WHERE jobid = ? ';
          $query = $this->db->query($sql, array(
              $jobid
          ));
          //print $this->db->last_query();
          return $query->row_array();
      }

      public function getdocs($parentid,$parenttype,$status='1') {
          $sql   = 'SELECT * FROM ' . $this->db->dbprefix('docs') . ' WHERE parentid = ? and parenttype = ? and status = ?';
		  
          $query = $this->db->query($sql, array(
              $parentid,$parenttype,'1'
          ));
          //print $this->db->last_query();
          return $query->result_array();
      }
      
      public function getUserExpertise($userid) {
          $sql   = "SELECT * FROM " . $this->db->dbprefix('user_expertise') . " WHERE userid = ? and status = '1' ";
          $query = $this->db->query($sql, array(
              $userid
          ));
          //print $this->db->last_query();
          return $query->result_array();
      }
      
      public function getExpertiseJobs($expertiseid,$status="",$sortby="date",$sortorder="desc") {
          $sql   = "SELECT * FROM " . $this->db->dbprefix('jobs') . " WHERE expertiseid = ? and status = '1' ";
          if($status !=""){
            $sql   .= " and jobstatus = '".$status."'";
          }
          if($sortby == "name"){
            $sql   .= " order by jobname ".$sortorder;
          }else{
            $sql   .= " order by createdate ".$sortorder;
          }
          $query = $this->db->query($sql, array(
              $expertiseid
          ));
          //print $this->db->last_query();
          return $query->result_array();
      }
      
      public function addBid($data){
         $data['status']    = '1';
         $data['createdate'] = time();
         
          $this->db->insert('bids', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;

      }

      public function getbidcount($jobid){
        $sql   = "SELECT count(*) as total FROM " . $this->db->dbprefix('bids') . " WHERE jobid = ? and status = '1'";
        $query = $this->db->query($sql, array(
              $jobid
          ));
          //print $this->db->last_query();
          $bid =  $query->row_array();
          return (isset($bid['total'])) ? $bid['total'] : 0 ;
      }

      public function getbids($jobid){
        $sql   = "SELECT bidamount,userid,bidid,isfavourite,bidkey FROM " . $this->db->dbprefix('bids') . " WHERE jobid = ? and status = '1' order by createdate desc";
        $query = $this->db->query($sql, array(
              $jobid
          ));
          //print $this->db->last_query();
          return  $query->result_array();
          
      }

      public function getmybid($jobid,$userid){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('bids') . " WHERE jobid = ? and userid = ? and status = '1'";
        $query = $this->db->query($sql, array(
              $jobid,$userid
          ));
          //print $this->db->last_query();
          return  $query->row_array();
          
      }

      public function editBid($data,$jobid,$userid) {
        
          $data['updatedate'] = time();
          $this->db->where(array('jobid'=> $jobid,'userid'=>$userid,'status'=>'1'));
          $this->db->update('bids', $data);
          //print $this->db->last_query();
      }
      public function deleteBid($jobid,$userid) {
          $data = array(
            'updatedate' => time(),
            'status' => '0'
            );
          
          $this->db->where(array('jobid'=> $jobid,'userid'=>$userid));
          $this->db->update('bids', $data);
          //print $this->db->last_query();
      }

      public function favouriteBid($jobid,$userid,$isfavourite) {
          $data = array(
            'updatedate' => time(),
            'isfavourite' => $isfavourite
            );
          
          $this->db->where(array('jobid'=> $jobid,'userid'=>$userid));
          $this->db->update('bids', $data);
          //print $this->db->last_query();
      }

      public function create_contracts($contractkey,$ownerid,$contractorid,$bidid,$jobid,$amount){
          $data = array(
              'contractkey' => $contractkey,
              'ownerid' => $ownerid,
              'contractorid' => $contractorid,
              'amount'=>$amount,
              'bidid' => $bidid,
              'jobid' => $jobid,
              'status' => '1',
              'createdate' => time()
          );
          $this->db->insert('contracts', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }
      public function updateContractorfields($field,$contractkey){
        $other = '';
        if($field == 'workeragree'){
          $other = 'workeragreeddate' ;
        }else if($field == 'escrow_released'){
          $other = 'paymentreleased' ;
        }else if($field == 'completed'){
          $other = 'completeddate' ;
        }else if($field == 'homeowneragree'){
          $other = 'owneragreeddate' ;
        }

          if($other != ''){
            $data = array(
              'updatedate' => time(),
              $other => time(),
              $field => '1'
            );
          }else{
            $data = array(
              'updatedate' => time(),
              $field => '1'
            );
          }
          
          
          $this->db->where('contractkey',$contractkey);
          $this->db->update('contracts', $data);

      }

      function getContractorCompletedJobCount(){
          $sql   = "SELECT count(*) as ttl FROM efy_jobs WHERE jobstatus = ?";
          $query = $this->db->query($sql, array(
              'completed'
          ));
          //print $this->db->last_query();
          return  $query->row_array();
      }

      public function getContract($jobid,$contractorid){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('contracts') . " WHERE jobid = ? and contractorid = ? and status = '1'";
        $query = $this->db->query($sql, array(
              $jobid,$contractorid
          ));
          //print $this->db->last_query();
          return  $query->row_array();

      }
      public function getContractforjob($jobid){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('contracts') . " WHERE jobid = ?  and status = '1' limit 1";
        $query = $this->db->query($sql, array(
              $jobid
          ));
          //print $this->db->last_query();
          return  $query->row_array();

      }
      public function getContractbykey($contractkey){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('contracts') . " WHERE contractkey = ? and status = '1'";
        $query = $this->db->query($sql, array(
              $contractkey
          ));
          //print $this->db->last_query();
          return  $query->row_array();

      }

      public function updateJobfields($field,$value,$jobid){
        $data = array(
            'updatedate' => time(),
            $field => $value
            );
          
          $this->db->where('jobid',$jobid);
          $this->db->update('jobs', $data);

      }

      public function getallmybid($userid){ 
        $sql   = "SELECT * FROM " . $this->db->dbprefix('bids') . " WHERE  userid = ? and status = '1' order by createdate desc";
        $query = $this->db->query($sql, array(
              $userid
          ));
          //print $this->db->last_query();
          return  $query->result_array();
          
      }

      public function getjobsbyids($jobids) {
          $sql   = "SELECT * FROM " . $this->db->dbprefix('jobs') . " WHERE jobid in (".join(",",$jobids).") and status = '1'  order by createdate desc";
          $query = $this->db->query($sql);
          //print $this->db->last_query();
          return $query->result_array();
      }

       public function getjobsbyidsmesage($jobids) {
          $sql   = "SELECT * FROM " . $this->db->dbprefix('jobs') . " WHERE jobid in (".join(",",$jobids).")  order by createdate desc";
          $query = $this->db->query($sql);
          //print $this->db->last_query();
          return $query->result_array();
      }

      public function create_tempdocs($dockey,$docext,$originalname){
          $data = array(
              'dockey' => $dockey,
              'docext' => $docext,
              'originalname' => $originalname,
              'status' => '1',
              'createdate' => time()
          );
          $this->db->insert('tempdocs', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }
      public function gettempdocbykey($dockey){
       // $sql   = "SELECT * FROM " . $this->db->dbprefix('tempdocs') . " WHERE dockey = ? and status = '1'";
         $sql   = "SELECT * FROM " . $this->db->dbprefix('tempimage') . " WHERE tempimgkey = ? ";
        $query = $this->db->query($sql, array(
              $dockey
          ));
          //print $this->db->last_query();
          return  $query->row_array();

      }

      public function create_rating($rating,$ownerid,$contractorid,$jobid){
          $data = array(
              'rating' => $rating,
              'ownerid' => $ownerid,
              'contractorid' => $contractorid,
              'jobid' => $jobid,
              'status' => '1',
              'createdate' => time()
          );
          $this->db->insert('rating', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }
      public function getcontractorrating($contractorid){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('rating') . " WHERE  contractorid = ? and status = '1'";
        $query = $this->db->query($sql, array(
              $contractorid
          ));
          //print $this->db->last_query();
          return  $query->result_array();

      }

      public function getContractforcontractors($contractorid){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('contracts') . " WHERE  contractorid = ? and status = '1'  order by createdate desc";
        $query = $this->db->query($sql, array(
              $contractorid
          ));
          //print $this->db->last_query();
          return  $query->result_array();

      }

      public function getContractforhomeowners($ownerid){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('contracts') . " WHERE  ownerid = ? and status = '1'  order by createdate desc";
        $query = $this->db->query($sql, array(
              $ownerid
          ));
          //print $this->db->last_query();
          return  $query->result_array();

      }

      public function create_message($message,$fromuserid,$touserid,$jobid,$hasdoc='0',$completionnote='0'){
        $messagekey =  $this->corefunctions->generateUniqueKey('6', 'messages', 'messagekey');
          $data = array(
              'messagekey' => $messagekey,
              'message' => $message,
              'fromuserid' => $fromuserid,
              'touserid' => $touserid,
              'jobid' => $jobid,
              'hasdoc'=>$hasdoc,
              'completionnote'=>$completionnote,
              'status' => '1',
              'createdate' => time()
          );
          $this->db->insert('messages', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }
     
		public function getAllBidDetails($jobstat,$userid,$haslimit=''){
			$sql   = "SELECT * FROM " . $this->db->dbprefix('jobs') . " WHERE jobstatus in (".join(",",$jobstat).") and createdby = ? and status = '1' ";
			$sql .= " order by createdate desc ";
			if( $haslimit != '' ){
				$sql .= " limit ".$haslimit;
			}
			$query = $this->db->query($sql, array(
				$userid
			));
			//print $this->db->last_query();
			return $query->result_array();
		}
		
		public function getBidImage($parenttype,$parentid,$doctype='image'){
			$sql   = "SELECT * FROM " . $this->db->dbprefix('docs') . " WHERE parenttype = ? and parentid = ? and status = '1' and doctype = ? limit 1";
			$query = $this->db->query($sql, array(
				$parenttype,$parentid,$doctype
			));
			//print $this->db->last_query();
			return $query->row_array();
		}

     public function getusermessages($userid){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('messages') . " WHERE  fromuserid = ? or touserid = ? and status = '1' order by createdate desc";
        $query = $this->db->query($sql, array(
              $userid,$userid
          ));
          //print $this->db->last_query();
          return  $query->result_array();

      }
      public function getuserJobmessages($userid,$jobid){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('messages') . " WHERE  ( fromuserid = ? or touserid = ? ) and jobid = ? and status = '1' order by messageid desc";
        $query = $this->db->query($sql, array(
              $userid,$userid,$jobid
          ));
          //print $this->db->last_query();
          return  $query->result_array();

      }

      public function getmessages($fromuserid,$touserid,$jobid,$lastmessageid="0"){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('messages') . " WHERE (( fromuserid = ? and touserid = ? ) or ( fromuserid = ? and touserid = ? ) ) and jobid = ? and status = '1'";
        if($lastmessageid != "0"){
          $sql   .= " and messageid < ' ".$lastmessageid ."'";
        }
        $sql   .= " order by messageid desc limit ".LIMIT;
        $query = $this->db->query($sql, array(
              $fromuserid,$touserid,$touserid,$fromuserid,$jobid
          ));
          //print $this->db->last_query();
          return  $query->result_array();

      }

      public function getmessageCount($touserid){
        $sql   = "SELECT count(*) as total FROM " . $this->db->dbprefix('messages') . " as a JOIN " . $this->db->dbprefix('jobs') . " as b ON a.jobid = b.jobid WHERE a.touserid = ? and a.status = '1' and a.readmessage = '0' and b.status = '1' ";
        
        $query = $this->db->query($sql, array(
              $touserid
          ));
          //print $this->db->last_query();
          return  $query->row_array();

      }

      public function getnewmessages($fromuserid,$touserid,$jobid,$lastmessageid){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('messages') . " WHERE (( fromuserid = ? and touserid = ? ) or ( fromuserid = ? and touserid = ? ) ) and jobid = ? and status = '1' and messageid > ' ".$lastmessageid ."'";
        
        
        $sql   .= " order by createdate asc  ";
        $query = $this->db->query($sql, array(
              $fromuserid,$touserid,$touserid,$fromuserid,$jobid
          ));
          //print $this->db->last_query();
          return  $query->result_array();

      }
    public function msgByid( $messageid ){
      $sql   = "SELECT * FROM " . $this->db->dbprefix('messages') . " WHERE messageid = ? and status = '1' ";
      $query = $this->db->query($sql, array(
        $messageid
      ));
      //print $this->db->last_query();
      return $query->row_array();
    }
    public function msgBykey( $messagekey ){
      $sql   = "SELECT * FROM " . $this->db->dbprefix('messages') . " WHERE messagekey = ? and status = '1' ";
      $query = $this->db->query($sql, array(
        $messagekey
      ));
      //print $this->db->last_query();
      return $query->row_array();
    }

       public function updatereadmessages($jobid,$touserid){
        $data = array(
            'updatedate' => time(),
            'readmessage' => '1'
            );
          
          $this->db->where(array('jobid'=>$jobid,'touserid'=>$touserid));
          $this->db->update('messages', $data);

      }
	  
		public function jobByKey( $jobkey ){
			$sql   = "SELECT * FROM " . $this->db->dbprefix('jobs') . " WHERE jobkey = ? and status = '1' ";
			$query = $this->db->query($sql, array(
				$jobkey
			));
			//print $this->db->last_query();
			return $query->row_array();
		}
	
		public function updateDocStat($dockey,$status) {
			$data = array(
				'status' 	 => $status,
				'updatedate' => time()
			);
			$this->db->where('dockey', $dockey);
			$this->db->update('docs', $data);
			//print $this->db->last_query();
		}

     public function updateContractorescrow($contractid,$bt_transaction_id,$amount,$servicefee){
        $data = array(
            'updatedate' => time(),
            'bt_transaction_id' => $bt_transaction_id,
            'amount' => $amount,
            'servicefee' => $servicefee,
            'servicefeepercent' => SERVICEFEE,
            //'homeowneragree'=>'1',
            'paymentdate' => time()
            
            );
          
          $this->db->where('contractid',$contractid);
          $this->db->update('contracts', $data);

      }

      public function getnotification($userid){

        $sql   = "SELECT * FROM " . $this->db->dbprefix('notification') . " WHERE touserid = ? and status = '1' order by createdate desc";
        $query = $this->db->query($sql, array(
          $userid
        ));
        //print $this->db->last_query();
        return $query->result_array();

      }

       public function updateread($touserid) {
        $data = array(
          'isread'   => '1',
          'updatedate' => time()
        );
        $this->db->where('touserid', $touserid);
        $this->db->update('notification', $data);
      //print $this->db->last_query();
    }

      public function deleteDoc($docid) {
        $data = array(
          'status'   => '0',
          'updatedate' => time()
        );
        $this->db->where('docid', $docid);
        $this->db->update('docs', $data);
      //print $this->db->last_query();
    }

    public function create_notifications($fromuserid,$touserid,$jobid,$parenttype,$parentid, $params = array() ){
        $notificationkey =  $this->corefunctions->generateUniqueKey('6', 'notification', 'notificationkey');
          $data = array(
              'notificationkey' => $notificationkey,
              'parenttype' => $parenttype,
              'fromuserid' => $fromuserid,
              'touserid' => $touserid,
              'jobid' => $jobid,
              'parentid'=>$parentid,
              'status' => '1',
              'createdate' => time()
          );
          $this->db->insert('notification', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          $this->hedercontroller->sendpushnotification($fromuserid,$touserid,$jobid,$parenttype,$insert_id,$params);
          //print $this->db->last_query();
          return $insert_id;
      }

      

      public function updateothercontracts($jobid){
        $data = array(
            'updatedate' => time(),
            'status' => '0'
            );
          
          $this->db->where('jobid',$jobid);
          $this->db->update('contracts', $data);

      }

      public function create_history($userid,$activityid,$jobid,$touserid){
        $historykey =  $this->corefunctions->generateUniqueKey('6', 'history', 'historykey');
          $data = array(
              'historykey' => $historykey,
              'activityid' => $activityid,
              'userid' => $userid,
              'touserid' => $touserid,
              'jobid' => $jobid,
              'status' => '1',
              'createdate' => time()
          );
          $this->db->insert('history', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }
	  
		public function bidByKey( $bidkey ){
			$sql   = "SELECT * FROM " . $this->db->dbprefix('bids') . " WHERE bidkey = ? and status = '1' ";
			$query = $this->db->query($sql, array(
				$bidkey
			));
			//print $this->db->last_query();
			return $query->row_array();
		}
		
		public function editBidByKey($data,$bidkey) {
        
          $data['updatedate'] = time();
          $this->db->where(array('bidkey'=> $bidkey));
          $this->db->update('bids', $data);
          //print $this->db->last_query();
		}
		
		public function deleteBidByKey($bidkey) {
          $data = array(
            'updatedate' => time(),
            'status' => '0'
            );
          
          $this->db->where(array('bidkey'=> $bidkey));
          $this->db->update('bids', $data);
          //print $this->db->last_query();
		}
		
		public function bidFavourite($bidkey,$isfavourite) {
			  $data = array(
				'updatedate' => time(),
				'isfavourite' => $isfavourite
				);
			  
			  $this->db->where(array('bidkey'=> $bidkey));
			  $this->db->update('bids', $data);
			  //print $this->db->last_query();
		}

     public function create_braintree_response($ownerid,$contractorid,$jobid,$response,$type){
          //$req_dump = print_r($input, TRUE);
          $filename = $ownerid."_".$contractorid."_".$jobid."_".time().".txt";
          $fp       = fopen('assets/bt_response/'.$filename, 'w+');
          fwrite($fp, $response);
          fclose($fp);
        
          $data = array(
              'ownerid' => $ownerid,
              'contractorid' => $contractorid,
              'jobid' => $jobid,
              'response' => $response,
              'status' => '1',
              'type'=>$type,
              'createdate' => time()
          );
          $this->db->insert('braintree_response', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }

      public function cancelcontract($contractkey){
            $canceledby = ($this->session->userdata('usertype') == 'homeowner') ? 'h' : 'w';
            $data = array(
              'updatedate' => time(),
              'canceleddate'=>time(),
              'status' => '-1'
            );
          
          $this->db->where('contractkey',$contractkey);
          $this->db->update('contracts', $data);

      }

      public function getExpertiseJobsCounts($expertiseids) {
          $sql   = "SELECT count(*) as total,expertiseid,jobid FROM " . $this->db->dbprefix('jobs') . " WHERE expertiseid in ( ".$expertiseids.") and status = '1' and jobstatus  ='new' group by expertiseid";
          $query = $this->db->query($sql);
          //print $this->db->last_query();
          return $query->result_array();
      }

      public function create_payment($contractid,$cardtype,$cardnumber,$response){
        $this->update_paymentstatus($contractid,'0');
        $paymentkey =  $this->corefunctions->generateUniqueKey('6', 'paymentdetails', 'paymentkey');
          $data = array(
              'paymentkey' => $paymentkey,
              'contractid' => $contractid,
              'cardtype' => $cardtype,
              'cardnumber' => $cardnumber,
              'response' => $response,
              'status' => '1',
              'createdate' => time()
          );
          $this->db->insert('paymentdetails', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
          return $insert_id;
      }

      public function update_paymentstatus($contractid,$status){
        $data = array(
              'updatedate' => time(),
              'status' => $status
            );
          
          $this->db->where('contractid',$contractid);
          $this->db->update('paymentdetails', $data);
      }

      public function getcontractbyid($contractid){

        $sql   = "SELECT * FROM " . $this->db->dbprefix('contracts') . " WHERE contractid = ? limit 1";
        $query = $this->db->query($sql, array(
          $contractid
        ));
        //print $this->db->last_query();
        return $query->row_array();

      }

      public function getpaymentcontractid($contractid){

        $sql   = "SELECT * FROM " . $this->db->dbprefix('paymentdetails') . " WHERE contractid = ? and status = '1' limit 1";
        $query = $this->db->query($sql, array(
          $contractid
        ));
        //print $this->db->last_query();
        return $query->row_array();

      }

       public function getdocbykey($dockey){
       
         $sql   = "SELECT * FROM " . $this->db->dbprefix('docs') . " WHERE dockey = ? ";
        $query = $this->db->query($sql, array(
              $dockey
          ));
          //print $this->db->last_query();
          return  $query->row_array();

      }

      public function gettemperorydocbykey($dockey){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('tempdocs') . " WHERE dockey = ? and status = '1'";
         
        $query = $this->db->query($sql, array(
              $dockey
          ));
          //print $this->db->last_query();
          return  $query->row_array();

      }

      public function getjobbyname($expertiseids,$jobname="",$sortby="date",$sortorder="desc") {
          $sql   = "SELECT * FROM " . $this->db->dbprefix('jobs') . " WHERE  expertiseid in (".$expertiseids.") and status = '1' ";
          if($jobname !=""){
            $sql   .= " and jobname like '%".$jobname."%'";
          }
          if($sortby == "name"){
            $sql   .= " order by jobname ".$sortorder;
          }else{
            $sql   .= " order by createdate ".$sortorder;
          }
          
          $query = $this->db->query($sql);
          //print $this->db->last_query();
          return $query->result_array();
      }

      public function bidByID( $bidid ){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('bids') . " WHERE bidid = ?  ";
        $query = $this->db->query($sql, array(
          $bidid
        ));
        return $query->row_array();
        //print $this->db->last_query();
      }

      public function braintreeresponse($input){
          $req_dump = print_r($input, TRUE);
          $fp       = fopen('assets/braintree.txt', 'w+');
          fwrite($fp, $req_dump);
          fclose($fp);
      }

      public function getCompletedJobById($jobid, $status = ""){
        $sql  = "SELECT * FROM " . $this->db->dbprefix('jobs') . " WHERE jobid = ? ";
        if ($status != ""){
          $sql .= " AND jobstatus = '".$status."' ";
        }
        $query = $this->db->query($sql, array($jobid));
        return $query->row_array();
      }

      public function getCompletedJob($jobkey, $status = ""){ 

        $sql  = "SELECT * FROM " . $this->db->dbprefix('jobs') . " WHERE jobkey = ? ";
        if ($status != ""){
          $sql .= " AND jobstatus = '".$status."' ";
        }
        $query = $this->db->query($sql, array($jobkey));
        return $query->row_array();
      }

      public function getContractor($userkey){
        $sql  = "SELECT * FROM " . $this->db->dbprefix('users') . " WHERE userkey = ? AND usertype = ? ";
        $query = $this->db->query($sql, array($userkey, 'contractor'));
        return $query->row_array();
      }

      public function getJobContract($ownerid, $contractorid, $jobid){
        $sql  = "SELECT * FROM " . $this->db->dbprefix('contracts') . " WHERE ownerid = ? AND contractorid = ? AND jobid = ? AND homeowneragree = '1' AND workeragree = '1' ";
        $query = $this->db->query($sql, array($ownerid, $contractorid,$jobid));
        return $query->row_array();
      }

      public function getRating($ownerid, $contractorid, $jobid){
        $sql  = "SELECT * FROM " . $this->db->dbprefix('rating') . " WHERE ownerid = ? and contractorid = ?  AND jobid = ? AND status = '1'";
        $query = $this->db->query($sql, array($ownerid,$contractorid, $jobid));
        
        return $query->row_array();
      }

      public function getPrivateRating($ratingID,$ownerid, $contractorid, $jobid ){

        $sql  = "SELECT * FROM " . $this->db->dbprefix('private_rating') . " WHERE ownerid = ? AND contractorid = ?  AND jobid = ? ";
        if ($ratingID != ""){
          $sql .= " AND rating_id = ".$ratingID;
        }
        $query = $this->db->query($sql, array($ownerid, $contractorid, $jobid));
        
        return $query->row_array();
      }

      public function savePublicRating($ownerid, $contractorid, $jobid, $postData){

        
        $date = new DateTime();

        $ratingData['ownerid'] = $ownerid;
        $ratingData['contractorid'] = $contractorid;
        $ratingData['jobid'] = $jobid;

        $ratingData['rating'] = !empty($postData['rating']) ? $postData['rating']: 1;
        $ratingData['status'] = '1';
        $ratingData['carftsman'] = !empty($postData['carftsman']) ? 1: 0;
        $ratingData['clean_workspace'] = !empty($postData['workspace']) ? 1: 0;
        $ratingData['excellent_value'] = !empty($postData['excellent']) ? 1: 0;
        $ratingData['good_communicator'] = !empty($postData['communicator']) ? 1: 0;
        $ratingData['problem_solver'] = !empty($postData['problem_solver']) ? 1: 0;
        $ratingData['worked_efficiently'] = !empty($postData['efficiently']) ? 1: 0;
        $ratingData['prompt'] = !empty($postData['prompt']) ? 1: 0;
        $ratingData['comment'] = !empty($postData['rating_comment']) ? $postData['rating_comment']: '';
        $ratingData['createdate'] = $date->getTimestamp();
        $ratingData['updatedate'] = $date->getTimestamp();

        if ( $this->db->insert($this->db->dbprefix('rating'), $ratingData) ){
          return $this->db->insert_id();
        }

        return false;
      }

      public function savePrivateRating($ratingid, $ownerid, $contractorid, $jobid, $postData){
        //echo "<pre>";print_r($postData);exit;
        $ratingData['rating_id'] = $ratingid;
        $ratingData['ownerid'] = $ownerid;
        $ratingData['contractorid'] = $contractorid;
        $ratingData['jobid'] = $jobid;

        $ratingData['scale'] = !empty($postData['scale']) ? $postData['scale']: '';
        
        if( !empty($postData['time_quality']) ){
          $txt = $postData['time_quality'];
          $value = $postData['time_quality'];
          if($value >= 1 && $value <= 2){
            $txt = 'Bad';
          }else if ($value > 2 && $value <= 4){
            $txt = 'Slightly below average';
          }else if ($value > 4 && $value <= 6){
            $txt = 'Average';
          }else if ($value > 6 && $value <= 9){
            $txt = 'Slightly above average';
          }else if ($value > 9 ){
            $txt = 'Extraordinary';
          }
          $ratingData['time_quality'] = $txt;
        }

        if( !empty($postData['work_quality']) ){
          $txt = $postData['work_quality'];
          $value = $postData['work_quality'];
          if($value >= 1 && $value <= 2){
            $txt = 'Bad';
          }else if ($value > 2 && $value <= 4){
            $txt = 'Slightly below average';
          }else if ($value > 4 && $value <= 6){
            $txt = 'Average';
          }else if ($value > 6 && $value <= 9){
            $txt = 'Slightly above average';
          }else if ($value > 9 ){
            $txt = 'Extraordinary';
          }
          $ratingData['work_quality'] = $txt;
        }

        if( !empty($postData['schedule']) ){
          $txt = $postData['schedule'];
          $value = $postData['schedule'];
          if($value >= 1 && $value <= 2){
            $txt = 'Bad';
          }else if ($value > 2 && $value <= 4){
            $txt = 'Slightly below average';
          }else if ($value > 4 && $value <= 6){
            $txt = 'Average';
          }else if ($value > 6 && $value <= 9){
            $txt = 'Slightly above average';
          }else if ($value > 9 ){
            $txt = 'Extraordinary';
          }
          $ratingData['schedule'] = $txt;
        }

        if( !empty($postData['overall_comments']) ){
          $txt = $postData['overall_comments'];
          $value = $postData['overall_comments'];
          if($value >= 1 && $value <= 2){
            $txt = 'Bad';
          }else if ($value > 2 && $value <= 4){
            $txt = 'Slightly below average';
          }else if ($value > 4 && $value <= 6){
            $txt = 'Average';
          }else if ($value > 6 && $value <= 9){
            $txt = 'Slightly above average';
          }else if ($value > 9 ){
            $txt = 'Extraordinary';
          }
          $ratingData['overall_comments'] = $txt;
        }

        if( !empty($postData['price_comments']) ){
          $txt = $postData['price_comments'];
          $value = $postData['price_comments'];
          if($value >= 1 && $value <= 2){
            $txt = 'Bad';
          }else if ($value > 2 && $value <= 4){
            $txt = 'Slightly below average';
          }else if ($value > 4 && $value <= 6){
            $txt = 'Average';
          }else if ($value > 6 && $value <= 9){
            $txt = 'Slightly above average';
          }else if ($value > 9 ){
            $txt = 'Extraordinary';
          }
          $ratingData['price_comments'] = $txt;
        }

        if( !empty($postData['bid_accurate_comments']) ){
          $txt = $postData['bid_accurate_comments'];
          $value = $postData['bid_accurate_comments'];
          if($value >= 1 && $value <= 2){
            $txt = 'Bad';
          }else if ($value > 2 && $value <= 4){
            $txt = 'Slightly below average';
          }else if ($value > 4 && $value <= 6){
            $txt = 'Average';
          }else if ($value > 6 && $value <= 9){
            $txt = 'Slightly above average';
          }else if ($value > 9 ){
            $txt = 'Extraordinary';
          }
          $ratingData['bid_accurate_comments'] = $txt;
        }

        if( !empty($postData['progress_update']) ){
          $txt = $postData['progress_update'];
          $value = $postData['progress_update'];
          if($value >= 1 && $value <= 2){
            $txt = 'Bad';
          }else if ($value > 2 && $value <= 4){
            $txt = 'Slightly below average';
          }else if ($value > 4 && $value <= 6){
            $txt = 'Average';
          }else if ($value > 6 && $value <= 9){
            $txt = 'Slightly above average';
          }else if ($value > 9 ){
            $txt = 'Extraordinary';
          }
          $ratingData['progress_update'] = $txt;
        }

        if( !empty($postData['job_done']) ){
          $txt = $postData['job_done'];
          $value = $postData['job_done'];
          if($value >= 1 && $value <= 2){
            $txt = 'Bad';
          }else if ($value > 2 && $value <= 4){
            $txt = 'Slightly below average';
          }else if ($value > 4 && $value <= 6){
            $txt = 'Average';
          }else if ($value > 6 && $value <= 9){
            $txt = 'Slightly above average';
          }else if ($value > 9 ){
            $txt = 'Extraordinary';
          }
          $ratingData['job_done'] = $txt;
        }

        if( !empty($postData['materials_quality']) ){
          $txt = $postData['materials_quality'];
          $value = $postData['materials_quality'];
          if($value >= 1 && $value <= 2){
            $txt = 'Bad';
          }else if ($value > 2 && $value <= 4){
            $txt = 'Slightly below average';
          }else if ($value > 4 && $value <= 6){
            $txt = 'Average';
          }else if ($value > 6 && $value <= 9){
            $txt = 'Slightly above average';
          }else if ($value > 9 ){
            $txt = 'Extraordinary';
          }
          $ratingData['materials_quality'] = $txt;
        }
        // $ratingData['time_quality'] = !empty($postData['time_quality']) ? $postData['time_quality']: 'Bad';
        // $ratingData['work_quality'] = !empty($postData['work_quality']) ? $postData['work_quality']: '';
        // $ratingData['schedule'] = !empty($postData['schedule']) ? $postData['schedule']: '';
        // $ratingData['overall_comments'] = !empty($postData['overall_comments']) ? $postData['overall_comments']: '';
        // $ratingData['price_comments'] = !empty($postData['price_comments']) ? $postData['price_comments']: '';
        // $ratingData['bid_accurate_comments'] = !empty($postData['bid_accurate_comments']) ? $postData['bid_accurate_comments']: '';
        // $ratingData['progress_update'] = !empty($postData['progress_update']) ? $postData['progress_update']: '';
        // $ratingData['job_done'] = !empty($postData['job_done']) ? $postData['job_done']: '';
        // $ratingData['materials_quality'] = !empty($postData['materials_quality']) ? $postData['materials_quality']: '';

        $ratingData['created_at'] = date("Y-m-d H:i:s");

        if ( $this->db->insert($this->db->dbprefix('private_rating'), $ratingData) ){
          return $this->db->insert_id();
        }

        return false;
      }

      public function markJobVerified($jobid){
        $data['jobstatus'] = 'verified';
        //$data['completiondate'] = date("Y-m-d");
        $this->db->update($this->db->dbprefix('jobs'), $data, array("jobid"=>$jobid));
        return;
      }

      public function getContractorJobs($contractorId){
        $sql = "SELECT
                efy_jobs.`jobid`,efy_jobs.`jobname`,efy_jobs.`jobkey`,efy_jobs.`jobdescription`, efy_jobs.`startdate`, efy_bids.`bidamount`, efy_expertise.`name`, efy_jobs.`completiondate`, 
                efy_users.`firstname`, efy_users.`lastname`,efy_users.`userkey`, efy_users.`address`, efy_users.`state`, efy_users.`zip`,efy_jobs.`status`,
                efy_users.`imagekey` 'user_img', efy_users.`userid`, efy_users.`imageext` 'user_img_ext', efy_docs.`dockey`,
                efy_docs.`docext`, efy_rating.`rating`, efy_rating.`ratingid`, contractor.`userkey` contractor_key
                FROM
                efy_contracts
                INNER JOIN efy_jobs ON efy_contracts.jobid = efy_jobs.jobid
                INNER JOIN efy_bids ON efy_contracts.bidid = efy_bids.bidid
                INNER JOIN efy_expertise ON efy_jobs.expertiseid = efy_expertise.expertiseid
                INNER JOIN efy_users ON efy_contracts.ownerid = efy_users.userid
                INNER JOIN efy_users contractor ON efy_contracts.contractorid = contractor.userid
                LEFT JOIN efy_docs ON efy_docs.parentid = efy_contracts.jobid AND efy_docs.parenttype = 'job'
                LEFT JOIN efy_rating ON efy_rating.jobid = efy_contracts.jobid
                WHERE
                efy_contracts.`contractorid` = ?
                AND efy_jobs.`jobstatus` = 'verified'
                AND efy_contracts.`status` = '1' GROUP BY jobid";

        $query = $this->db->query($sql, array($contractorId));
        $result = $query->result_array();
        $data = array();
        foreach($result as $i => $r){
          $data[$i] = $r;

          if(!empty($r['dockey'])){
            $job_img = base_url($this->corefunctions->getMyPath($r['jobid'], $r['dockey'], $r['docext'], 'assets/docs/'));
          }else{
            $job_img = base_url('images/def_job.jpg');
          }

          if(!empty($job['user_img'])){
             $user_img = base_url($this->corefunctions->getMyPath($r['userid'], $r['user_img'], $r['user_img_ext'], 'assets/profImgs/crop/'));
          }else{
             $user_img =  base_url('images/defaultimg.jpg');
          }

          $data[$i]['job_img'] = $job_img;
          $data[$i]['user_img'] = $user_img;

        }
        return $data;
      }

      public function searchContractors($state, $category){
        $sql  = "SELECT efy_users.`userid`,efy_users.`email`, efy_users.`firstname`, efy_users.`lastname`, efy_expertise.`expertiseid`, efy_expertise.`name` expertise_name FROM " . $this->db->dbprefix('users') . " INNER JOIN efy_user_expertise ON efy_users.userid = efy_user_expertise.userid INNER JOIN efy_expertise ON efy_expertise.expertiseid = efy_user_expertise.expertiseid WHERE efy_user_expertise.expertiseid = ? AND efy_user_expertise.`status` = '1' AND `usertype` = 'contractor' GROUP BY efy_users.`userid`";

        $query = $this->db->query($sql, array($category));
        return $query->result_array();
      }

      public function getStateName($slug){
        $slug = strtoupper($slug);
        $sql  = "SELECT * FROM " . $this->db->dbprefix('states') . " WHERE state_prefix = ? ";

        $query = $this->db->query($sql, array($slug));
        return $query->row_array();
      }

      public function sendJobPostNotification($job_data, $user_data, $current_user,$jobid){

          $userEmail = $user_data['email'];
          $job_data['toname'] = $user_data['firstname'].' '.$user_data['lastname'];
          $job_data['from'] = $current_user['userFirstname'].' '.$user_data['lastname'];
          $job_data['expertise_name'] = $user_data['expertise_name'];

          
          $state = $job_data['state'];
          $stateArr = $this->getStateName($state);

          if(!empty($stateArr)){
            $state = $stateArr['state_name'];
            $job_data['state'] = $state;
          }

          $msg   = $this->load->view('mail/post-job', $job_data, true);
          $this->corefunctions->sendmail(ADMINEMAIL, $userEmail, TITLE . ' :: New Job Post', $msg);
          $message = "New Job is posted under ".ucfirst($job_data['expertise_name'])." category in ".$state;
          $params['jobid'] = $jobid;
          $params['jobtype'] = "newjob";
          $this->hedercontroller->sendOnlyNotification($user_data['userid'], $message,$params);
      }
  }
?>