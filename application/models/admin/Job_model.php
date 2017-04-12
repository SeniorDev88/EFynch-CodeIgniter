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
              'address' => $input['address'],
              'state' => $input['state'],
              'city' => $input['city'],
              'zip' => $input['zip'],
              'startdate' => $input['startdate'],
              'daysposted' => $input['daysposted'],
              'latitude' =>  (isset($input['geoLocation']['lat'])) ? $input['geoLocation']['lat'] : '',
              'longitude' =>  (isset($input['geoLocation']['lng'])) ? $input['geoLocation']['lng'] : '',
              'jobstatus' => 'new',
              'createdby' => $this->session->userdata('userId'),
              'status' => '1',
              'createdate' => time()
          );
          $this->db->insert('jobs', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
          //print $this->db->last_query();
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
              'address' => $input['address'],
              'state' => $input['state'],
              'city' => $input['city'],
              'zip' => $input['zip'],
              'startdate' => $input['startdate'],
              'daysposted' => $input['daysposted'],
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

      public function getContract($jobid,$contractorid){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('contracts') . " WHERE jobid = ? and contractorid = ? and status = '1'";
        $query = $this->db->query($sql, array(
              $jobid,$contractorid
          ));
          //print $this->db->last_query();
          return  $query->row_array();

      }
	  
	  public function getContractByJobId($jobid){
        $sql   = "SELECT * FROM " . $this->db->dbprefix('contracts') . " WHERE jobid = ? and status = '1'";
        $query = $this->db->query($sql, array(
              $jobid
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

    public function create_notifications($fromuserid,$touserid,$jobid,$parenttype,$parentid){
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
          $this->hedercontroller->sendpushnotification($fromuserid,$touserid,$jobid,$parenttype,$insert_id);
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
              'response' => "$response",
              'status' => '1',
              'type'=>$type,
              'createdate' => time()
          );
          $this->db->insert('braintree_response', $data);
          $insert_id = $this->db->insert_id();
          $this->db->trans_complete();
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


	  
  }
?>