<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');







class Jobs extends CI_Controller

{

    var $data = array();

    var $tbl = 'efy_jobs';

    var $expertise = 'efy_expertise';

    var $states = 'efy_states';

    var $users = 'efy_users';

    var $cities = 'efy_city';

    var $bids = 'efy_bids';

    var $status = array('new'=>'New','inprogress'=>'In Progress','completed'=>'Completed','verified'=>'Verified');



    function __construct()

    {



        parent::__construct();



        ini_set('display_errors', 1);



        $this->load->model('mdl_app','app');

        if(!$this->session->userdata('user')){

            $this->session->set_flashdata('error','Login to view page');

            redirect(base_url('admin'));

        }



    }



    public function index()

    {

        $limit = 20;

        $fields = 'efy_users.firstname,efy_users.lastname,efy_expertise.*,efy_jobs.*,efy_contracts.bt_transaction_id,efy_contracts.escrow_released';

        $expertiseJoin = array('joinTbl'=>'efy_expertise', 'on'=>'efy_expertise.expertiseid = efy_jobs.expertiseid','type'=>'left');

        $userJoin = array('joinTbl'=>'efy_users', 'on'=>'efy_users.userid = efy_jobs.createdby','type'=>'left');

        $contractJoin = array('joinTbl'=>'efy_contracts', 'on'=>'efy_jobs.jobid = efy_contracts.jobid','type'=>'left');

        $conditions = array(

            'table'=>$this->tbl,

            'order'=>"efy_jobs.jobid DESC",

            'join'=>array($expertiseJoin,$userJoin,$contractJoin)

        );

        //pagination

        $this->load->library('pagination');

        if($this->uri->segment(4)){

            $page = $this->uri->segment(4);

        }else{

            $page = 1;

        }

        $config['uri_segment'] = 4;

        $config['per_page'] = $limit;



        $offset = ($page * $config['per_page']) - $config['per_page'];



        if($this->input->get('q')){

            $q = $this->input->get('q');

            $conditions['custom'] = "CONCAT(efy_users.firstname,' ',efy_users.lastname) like '%".$q."%' OR efy_jobs.jobname like '%".$q."%' OR efy_jobs.jobstatus like '%".$q."%' OR efy_jobs.address like '%".$q."%' OR efy_jobs.state like '%".$q."%' OR efy_jobs.city like '%".$q."%'";

            $data['q'] = $q;

        }



        $total_row = $this->app->getDataCount($conditions);



        $config['total_rows'] = $total_row;

        $config['use_page_numbers'] = TRUE;

        $config['num_links'] = 2;

        $config['display_pages'] = TRUE;



        // Use pagination number for anchor URL.

        $config['use_page_numbers'] = TRUE;



        $query = $_SERVER['QUERY_STRING'];

        $config['base_url'] = base_url('admin/jobs/index');

        $config['suffix'] = '?'.$query;

        $config['full_tag_open'] = '<ul class="pagination">';

        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'First';

        $config['first_tag_open'] = '<li>';

        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last';

        $config['last_tag_open'] = '<li>';

        $config['last_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li>';

        $config['first_tag_close'] = '</li>';

        $config['prev_link'] = 'Previous';

        $config['prev_tag_open'] = '<li class="prev">';

        $config['prev_tag_close'] = '</li>';

        $config['next_link'] = 'Next';

        $config['next_tag_open'] = '<li>';

        $config['next_tag_close'] = '</li>';

        $config['last_tag_open'] = '<li>';

        $config['last_tag_close'] = '</li>';

        $config['cur_tag_open'] =  '<li class="active"><a href="#">';

        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';

        $config['num_tag_close'] = '</li>';



        $this->pagination->initialize($config);



        $links =  $this->pagination->create_links();



        // add limit

        $conditions = $conditions + array('limit'=>$limit,'offset'=>$offset,'fields'=>$fields);

        $content = $this->app->getData($conditions);

        // get bids count

        foreach ($content as $k=>$v) {

            $conditions = array(

                'table' => $this->bids,

                'where' => array('jobid' =>$v['jobid'])

            );

            $count = $this->app->getDataCount($conditions);

            $content[$k]['bidsCount'] = $count;

        }



        $data['content'] = $content;

        $data['links'] = $links;

        $data['offset'] = $offset;

        $data['perPage'] = $config['per_page'];

        $data['dataInfo'] = 'Showing ' . ($offset+1) .' to '.($offset + count($content)).' of '.$total_row.' entries';

        //echo '<pre>';print_r($data);exit;

        $this->load->view('admin/template/header');

        $this->load->view('admin/jobs',$data);

        $this->load->view('admin/template/footer');



    }



    function jobDetail($id){

        $contractJoin = array('joinTbl'=>'efy_contracts', 'on'=>'efy_jobs.jobid = efy_contracts.jobid','type'=>'left');

        $expertiseJoin = array('joinTbl'=>'efy_expertise', 'on'=>'efy_expertise.expertiseid = efy_jobs.expertiseid','type'=>'left');

        $userJoin = array('joinTbl'=>'efy_users', 'on'=>'efy_users.userid = efy_jobs.createdby','type'=>'left');

        $conditions = array('table'=>$this->tbl,'where'=>array('efy_jobs.jobid'=>$id),'fields'=>'efy_users.firstname,efy_users.lastname,efy_expertise.*,efy_jobs.*','join'=>array($expertiseJoin,$userJoin,$contractJoin));

        $content = $this->app->getData($conditions);

        if(count($content)<=0){

            $this->session->set_flashdata('error', 'No record found');

            redirect('admin/jobs');

        }

        $data['data'] = $content[0];

        $images = $this->app->getData(array(

            'table'=>'efy_docs',

            'where'=>array('efy_docs.parentid' => $data['data']['jobid'],'efy_docs.parenttype'=>'job')

        ));

        $data['data']['images'] = $images;

        //echo '<pre>';print_r($data);exit;

        $this->load->view('admin/template/header');

        $this->load->view('admin/jobDetail',$data);

        $this->load->view('admin/template/footer');

    }



    public function addJob(){

        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('jobname', 'Job Name', 'trim|required');

            if ($this->form_validation->run() == FALSE)

            {

                // redirect($_SERVER['HTTP_REFERER']);

            }

            else {

                //echo '<pre>';print_r($_FILES);exit;

                $data = array(

                    'jobkey' => generateUniqueKey(6),

                    'createdby'=>$this->input->post('createdby'),

                    'jobname' => $this->input->post('jobname'),

                    'jobdescription' => $this->input->post('jobdescription'),

                    'expertiseid' => $this->input->post('expertiseid'),

                    'address' => $this->input->post('address'),

                    'budget' => $this->input->post('budget'),

                    'daysposted' => $this->input->post('daysposted'),

                    'state' => $this->input->post('state'),

                    'jobstatus'=> 'new',

                    'zip' => $this->input->post('zip'),

                    'timeframe' => $this->input->post('timeframe'),

                    'indoor' => $this->input->post('indoor'),

                    'hometype' => $this->input->post('hometype'),

                    'starting_state' => $this->input->post('starting_state'),

                    'total_stories' => $this->input->post('total_stories'),

                    'material_option' => $this->input->post('material_option'),

                    'rate_type' => $this->input->post('rate_type'),

                    'location' => $this->input->post('location'),

                    'year_constructed' => $this->input->post('year_constructed'),

                    'current_condition' => $this->input->post('current_condition'),

                    'first_problem_notice' => $this->input->post('first_problem_notice'),

                    'resolution' => $this->input->post('resolution'),

                    'measurements' => $this->input->post('measurements'),

                    'material_preferences' => $this->input->post('material_preferences'),

                    'purchased_materials' => $this->input->post('purchased_materials'),

                    'access_to_area' => $this->input->post('access_to_area'),

                    'your_availability' => $this->input->post('your_availability'),

                    'relevant_info' => $this->input->post('relevant_info'),

                    'city' => $this->input->post('city'),

                    'startdate' => $this->input->post('startdate'),

                    //'completeddate' => $this->input->post('completeddate'),

                    'completiondate' => $this->input->post('completiondate'),

                    'createdate'=>time(),

                    'updatedate' => time(),

                );



                $isAdded = $this->app->addContent($this->tbl, $data);
                $last_id = $this->app->lastId();
                if ($isAdded) {

                    // add image
                    $gallery = explode(',', $this->input->post('job_gallery'));
                    foreach ($gallery as $key => $jobImage)
                    {
                        $dockey = explode('.', $jobImage);
                        $docKey = $dockey[0];
                        $ext = pathinfo($jobImage, PATHINFO_EXTENSION);

                        $data = array(

                            'dockey' => $docKey,
                            'docext' => $ext,
                            'parenttype' => 'job',
                            'parentid' => $last_id,
                            'originalname' => $jobImage,
                            'createdate' => time()

                        );

                        $isAddedImgs = $this->app->addContent('efy_docs', $data);
                    }

                    //$file = $this->uploadImg($this->app->lastId());

                    $this->session->set_flashdata('success', "Job added successfully");

                    redirect("admin/jobs");

                } else {

                    $this->session->set_flashdata('error', 'Something Went Wrong... Try Again');

                    redirect($_SERVER['HTTP_REFERER']);

                }

            }

        }



        // get users

        $users = $this->app->getData(array('table'=>$this->users,'where'=>array('usertype'=>'homeowner'),'fields'=>'userid,firstname,lastname'));

        $exp = $this->app->getData(array('table'=>$this->expertise,'fields'=>'expertiseid,name'));

        // get state and cities

        $states = $this->app->getData(array('table'=>$this->states));

        reset($states);

        $cities = $this->app->getData(array('table'=>$this->cities,'where'=>array('state_code'=>$states[0]['state_prefix'])));



        $data['users'] = $users;

        $data['cities'] = $cities;

        $data['states'] = $states;

        $data['status'] = $this->status;

        $data['exp'] = $exp;

//        print_r($data);exit;

        $this->load->view('admin/template/header');

        $this->load->view('admin/addJob',$data);

        $this->load->view('admin/template/footer');

    }



    public function editJob($id){

        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('jobname', 'Job Name', 'trim|required');

            if ($this->form_validation->run() == FALSE)

            {

                // redirect($_SERVER['HTTP_REFERER']);

            }

            else {

                //$file = $this->imageUpload('file');

                $data = array(

                    'jobname' => $this->input->post('jobname'),

                    'createdby'=>$this->input->post('createdby'),

                    'jobdescription' => $this->input->post('jobdescription'),

                    'expertiseid' => $this->input->post('expertiseid'),

                    'address' => $this->input->post('address'),

                    'budget' => $this->input->post('budget'),

                    'zip' => $this->input->post('zip'),

                    'timeframe' => $this->input->post('timeframe'),

                    'indoor' => $this->input->post('indoor'),

                    'hometype' => $this->input->post('hometype'),

                    'starting_state' => $this->input->post('starting_state'),

                    'total_stories' => $this->input->post('total_stories'),

                    'material_option' => $this->input->post('material_option'),

                    'rate_type' => $this->input->post('rate_type'),

                    'location' => $this->input->post('location'),

                    'year_constructed' => $this->input->post('year_constructed'),

                    'current_condition' => $this->input->post('current_condition'),

                    'first_problem_notice' => $this->input->post('first_problem_notice'),

                    'resolution' => $this->input->post('resolution'),

                    'measurements' => $this->input->post('measurements'),

                    'material_preferences' => $this->input->post('material_preferences'),

                    'purchased_materials' => $this->input->post('purchased_materials'),

                    'access_to_area' => $this->input->post('access_to_area'),

                    'your_availability' => $this->input->post('your_availability'),

                    'relevant_info' => $this->input->post('relevant_info'),

                    'daysposted' => $this->input->post('daysposted'),

                    'jobstatus'=> $this->input->post('jobstatus'),

                    'state' => $this->input->post('state'),

                    'city' => $this->input->post('city'),

                    'startdate' => $this->input->post('startdate'),

                    'completeddate' => $this->input->post('completeddate'),

                    'completiondate' => $this->input->post('completiondate'),

                    'updatedate' => time(),

                );



                $isUpdated = $this->app->updateRecord($this->tbl, array('jobid'=>$id),$data);

                if ($isUpdated) {

                    $delOld = $this->app->delete('efy_docs','parentid',$id);
                        // add image
                        $gallery = explode(',', $this->input->post('job_gallery'));
                        foreach ($gallery as $key => $jobImage)
                        {
                            $dockey = explode('.', $jobImage);
                            $docKey = $dockey[0];
                            $ext = pathinfo($jobImage, PATHINFO_EXTENSION);

                            $data = array(

                                'dockey' => $docKey,
                                'docext' => $ext,
                                'parenttype' => 'job',
                                'parentid' => $id,
                                'originalname' => $jobImage,
                                'createdate' => time()

                            );

                            $isAddedImgs = $this->app->addContent('efy_docs', $data);
                        }

                    //$file = $this->uploadImg($id);

                    $this->session->set_flashdata('success', "Job updated successfully");

                    redirect("admin/jobs/jobDetail/".$id);

                } else {

                    $this->session->set_flashdata('success', 'Nothing Changed');

                    redirect("admin/jobs");

                }

            }

        }



        // get users

        $users = $this->app->getData(array('table'=>$this->users,'where'=>array('usertype'=>'homeowner'),'fields'=>'userid,firstname,lastname'));



        $expertiseJoin = array('joinTbl'=>'efy_expertise', 'on'=>'efy_expertise.expertiseid = efy_jobs.expertiseid','type'=>'left');

        $conditions = array('table'=>$this->tbl,'where'=>array('jobid'=>$id),'fields'=>'efy_expertise.*,efy_jobs.*','join'=>array($expertiseJoin));

        $content = $this->app->getData($conditions);

        if(count($content)<=0){

            $this->session->set_flashdata('error', 'No record found');

            redirect('admin/jobs');

        }

        $exp = $this->app->getData(array('table'=>$this->expertise,'fields'=>'expertiseid,name'));

        // get state and cities

        $states = $this->app->getData(array('table'=>$this->states));

        reset($states);

        $city = $this->app->getData(array('table'=>$this->cities,'where'=>array('city'=>$content[0]['city'])));

        if(count($city)>0)

            $cities = $this->app->getData(array('table'=>$this->cities,'where'=>array('state_code'=>$city[0]['state_code'])));

        else

            $cities = $this->app->getData(array('table'=>$this->cities,'where'=>array('state_code'=>$states[0]['state_prefix'])));



        $data['cities'] = $cities;

        $data['users'] = $users;

        $data['states'] = $states;

        $data['status'] = $this->status;

        //images

        $images = $this->app->getData(array(

            'table'=>'efy_docs',

            'where'=>array('efy_docs.parentid' => $id,'efy_docs.parenttype'=>'job')

        ));

        $data['content'] = $content[0];

        $data['content']['images'] = $images;

        $data['exp'] = $exp;

        ///echo '<pre>';print_r($data);exit;

        $this->load->view('admin/template/header');

        $this->load->view('admin/editJob',$data);

        $this->load->view('admin/template/footer');

    }



    function deleteJob($id){

        $this->app->delete($this->tbl,'jobid',$id);

        $this->app->delete('efy_bids','jobid',$id);

        $this->session->set_flashdata('success', "Job deleted successfully");

        redirect("admin/jobs");



    }



    private function uploadImg($jobId){

        foreach($_FILES["image"]["tmp_name"] as $key=>$tmp_name)

            if (!empty($_FILES['image']['name'][$key]) && $_FILES['image']['error'][$key] == 0) {

                //$file = $_FILES['image'];

                $target_dir = APPPATH . "../assets/docs/00000";

                $filename = $_FILES['image']["name"][$key];

                //$filename = time() . '_' . $filename;

                $docKey = generateUniqueKey(6);

                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                $target_file = $target_dir . '/' . $docKey . '.' . $ext;

                if (move_uploaded_file($_FILES["image"]["tmp_name"][$key], $target_file)) {

                    $data = array(

                        'dockey' => $docKey,

                        'docext' => $ext,

                        'parenttype' => 'job',

                        'parentid' => $jobId,

                        'originalname' => $filename,

                        'createdate' => time()

                    );

                    $isAdded = $this->app->addContent('efy_docs', $data);

                }

                /*else {

                $this->session->set_flashdata('error', "Image upload failure");

                redirect("admin/jobs/addJob");

            }*/

            }
    }



    public function releasePayment()

    {

        $jobId = $this->uri->segment(4);

        $this->load->model("admin/job_model");

        $this->load->model("user_model");

        $jobDets = $this -> Job_model -> getjobbyid( $jobId );

        require_once(APPPATH.'third_party/braintree/lib/Braintree.php');

        try

        {

            Braintree\Configuration::environment(BT_environment);

            Braintree\Configuration::merchantId(BT_merchantId);

            Braintree\Configuration::publicKey(BT_publicKey);

            Braintree\Configuration::privateKey(BT_privateKey);



            $userkey = $this->input->post('userkey');

            $star = $this->input->post('star');

            $userDets = $this->User_model->getuserbyid($jobDets['createdby']);

            $contract = $this->Job_model->getContractByJobId($jobId);

            $escresult = array();

            //$transaction = Braintree_Transaction::find($contract['bt_transaction_id']);

            //$result = Braintree_Transaction::submitForSettlement($contract['bt_transaction_id']);

            $escresult = Braintree_Transaction::releaseFromEscrow($contract['bt_transaction_id']);

            $bresponse = json_encode($escresult);



            $this->Job_model->create_braintree_response($contract['ownerid'],$contract['contractorid'],$contract['jobid'],$escresult,'release');

            if($escresult->success === false)

            {

                $this->session->set_flashdata('error', 'Could not release the payment from escrow.');

                redirect($_SERVER['HTTP_REFERER']);

            }



        }

        catch(Braintree\Exception\NotFound $e)

        {

            $this->session->set_flashdata('error', 'Payment token is expired.');

            redirect($_SERVER['HTTP_REFERER']);

        }

        catch(InvalidArgumentException $e)

        {

            $this->session->set_flashdata('error', 'Payment is not made by home owner yet.');

            redirect($_SERVER['HTTP_REFERER']);

        }



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

            $this->session->set_flashdata('success', "Job completed successfully");

            redirect("admin/jobs");

        }else{



            $this->session->set_flashdata('error', 'Something Went Wrong... Try Again');

            redirect($_SERVER['HTTP_REFERER']);

        }





        exit;

    }



    public function completeJob()

    {

        $jobId = $this->uri->segment(4);

        $this->load->model("admin/job_model");

        $this->load->model("user_model");

        $jobDets = $this -> Job_model -> getjobbyid( $jobId );



        $this->Job_model->updateJobfields('jobstatus','completed',$jobDets['jobid']);

        $this->Job_model->updateJobfields('completeddate',date('Y-m-d'),$jobDets["jobid"]);

        $contract = $this->db->select("*")->from("contracts")->where("jobid",$jobId)->get()->row_array();

        //$contract = $this->Job_model->getContract($jobDets["jobid"],$contract['contractorid']);



        //print_r($contract);

        $this->Job_model->updateContractorfields('completed',$contract['contractkey']);

        $this->Job_model->create_notifications($contract['contractorid'],$contract['ownerid'],$contract['jobid'],'jcomplete',$contract['contractid']);

        $this->Job_model->create_history($contract['contractorid'],4,$jobDets['jobid'],$contract['ownerid']);

        parse_str($this->input->post('formdata'),$input);

        //print_r($input);

        $message = "";//$input['comment'];

        $this->Job_model->create_message($message,$contract['contractorid'],$contract['ownerid'],$contract['jobid'],'0','1');

        if(!empty($input['tempdocs'])){

            foreach($input['tempdocs'] as $temp){

                $messageid = $this->Job_model->create_message('',$contract['contractorid'],$contract['ownerid'],$contract['jobid'],'1','1');

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



        $this->session->set_flashdata('success', 'Job Completed successfully');

        redirect($_SERVER['HTTP_REFERER']);



    }





    function getDeleteWorkImg(){

        $id = $this->input->post('id');

        $this->app->delete('efy_docs', 'docid', $id);

        echo 'Deleted successfully';exit;

    }

    /*Upload File*/
    public function upload_file()
    {
        $up_file = do_upload('file_name', 'jpg|gif|png', APPPATH.'../assets/docs/00000');
        if(isset($up_file['success']))
        {
            $res = array(
                'success' => 'File upload successfully.',
                'filename' => $up_file['success']['file_name']
                );
            echo json_encode($res);
        }
    }

}