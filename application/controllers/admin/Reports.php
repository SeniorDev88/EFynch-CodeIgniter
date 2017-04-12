<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Reports extends CI_Controller

{

    /////////////////////////////////////

    ////////// DECLARING VARIABLES //////

    /////////////////////////////////////

    var $data = array();

    /////////////////////////////////////

    ////////// CONSTRUCTOR //////////////

    /////////////////////////////////////

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



    /////////////////////////////////////

    ////////// INDEX FUNCTION ///////////

    /////////////////////////////////////



    public function jobs()

    {
        $fields = 'efy_users.firstname,efy_users.lastname,efy_expertise.*,efy_jobs.*';
        $expertiseJoin = array('joinTbl'=>'efy_expertise', 'on'=>'efy_expertise.expertiseid = efy_jobs.expertiseid','type'=>'left');
        $userJoin = array('joinTbl'=>'efy_users', 'on'=>'efy_users.userid = efy_jobs.createdby','type'=>'left');
        $conditions = array(
            'table'=>'efy_jobs',
            'order'=>"jobid DESC",
            'join'=>array($expertiseJoin,$userJoin),
            'fields'=>$fields
        );

        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('startdate', 'Start Date', 'trim|required');
            $this->form_validation->set_rules('enddate', 'End Date', 'trim|required');

            if ($this->form_validation->run() == FALSE)
            {
                //redirect($_SERVER['HTTP_REFERER']);
            }
            else {
                $conditions['where'] = array('DATE_FORMAT(from_unixtime(efy_jobs.createdate),"%Y-%m-%d") >='=>date('Y-m-d',strtotime($this->input->post('startdate'))),'DATE_FORMAT(from_unixtime(efy_jobs.createdate),"%Y-%m-%d") <='=>date('Y-m-d',strtotime($this->input->post('enddate'))));
                if($this->input->post('status') != 'all'){
                    $conditions['where']['efy_jobs.jobstatus'] = $this->input->post('status');
                }
                $data['startdate'] = $this->input->post('startdate');
                $data['enddate'] = $this->input->post('enddate');
                $data['status'] = $this->input->post('status');
            }
        }else{
            $sDate = date('Y-m-d',strtotime(' - 30 days'));
            $eDate = date('Y-m-d');
            $conditions['where'] = array('DATE_FORMAT(from_unixtime(efy_jobs.createdate),"%Y-%m-%d") >='=>$sDate,'DATE_FORMAT(from_unixtime(efy_jobs.createdate),"%Y-%m-%d") <='=>$eDate);
            $data['startdate'] = $sDate;
            $data['enddate'] = $eDate;
        }

        //echo '<pre>';print_r($conditions);exit;

        $content = $this->app->getData($conditions);
        // get bids count
        foreach ($content as $k=>$v) {
            $conditions = array(
                'table' => 'efy_bids',
                'where' => array('jobid' =>$v['jobid'])
            );
            $count = $this->app->getDataCount($conditions);
            $content[$k]['bidsCount'] = $count;
        }

        $new = 1;
        $inProgress = 1;
        $completed = 1;
        $verified = 1;
        $graph = array();
        foreach ($content as $k=>$v) {
            if($v['jobstatus'] == 'new'){
                $graph[$v['jobstatus']] = $new ++;
            }else if($v['jobstatus'] == 'inprogress'){
                $graph[$v['jobstatus']] = $inProgress ++;
            }else if($v['jobstatus'] == 'completed'){
                $graph[$v['jobstatus']] = $completed ++;
            }else if($v['jobstatus'] == 'verified'){
                $graph[$v['jobstatus']] = $verified ++;
            }
        }
        $data['jobs'] = $content;
        $data['report'] = $graph;

        //echo '<pre>';print_r($data);exit;

        $this->load->view('admin/template/header');

        $this->load->view('admin/job_reports',$data);

        $this->load->view('admin/template/footer');

    }

    public function users()
    {
        $fields = 'efy_users.*';

        $conditions = array(
            'table'=>'efy_users',
            'order'=>"userid DESC",
            'fields'=>$fields
        );

        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('startdate', 'Start Date', 'trim|required');
            $this->form_validation->set_rules('enddate', 'End Date', 'trim|required');

            if ($this->form_validation->run() == FALSE)
            {
                //redirect($_SERVER['HTTP_REFERER']);
            }
            else {
                $conditions['where'] = array('DATE_FORMAT(from_unixtime(efy_users.createdate),"%Y-%m-%d") >='=>date('Y-m-d',strtotime($this->input->post('startdate'))),'DATE_FORMAT(from_unixtime(efy_users.createdate),"%Y-%m-%d") <='=>date('Y-m-d',strtotime($this->input->post('enddate'))));
                if($this->input->post('status') != 'all'){
                    $conditions['where']['efy_users.usertype'] = $this->input->post('status');
                }
                $data['startdate'] = $this->input->post('startdate');
                $data['enddate'] = $this->input->post('enddate');
                $data['status'] = $this->input->post('status');
            }
        }else{
            $sDate = date('Y-m-d',strtotime(' - 30 days'));
            $eDate = date('Y-m-d');
            $conditions['where'] = array('DATE_FORMAT(from_unixtime(efy_users.createdate),"%Y-%m-%d") >='=>$sDate,'DATE_FORMAT(from_unixtime(efy_users.createdate),"%Y-%m-%d") <='=>$eDate);
            $data['startdate'] = $sDate;
            $data['enddate'] = $eDate;
        }

        //echo '<pre>';print_r($conditions);exit;

        $content = $this->app->getData($conditions);
        $new = 1;
        $inProgress = 1;
        $graph = array();
        foreach ($content as $k=>$v) {
            if($v['usertype'] == 'homeowner'){
                $graph[$v['usertype']] = $new ++;
            }else if($v['usertype'] == 'contractor'){
                $graph[$v['usertype']] = $inProgress ++;
            }

        }
        $data['users'] = $content;
        $data['report'] = $graph;

        //echo '<pre>';print_r($data);exit;

        $this->load->view('admin/template/header');

        $this->load->view('admin/user_reports',$data);

        $this->load->view('admin/template/footer');

    }

    public function financial()
    {
        $fields = 'efy_users.userid,efy_users.firstname,efy_users.lastname,efy_users.usertype';
        $condition = array(
            'table'=>'efy_users',
            'order'=>"efy_users.firstname ASC",
            'fields'=>$fields
        );

        // get jobs
        $field = "efy_jobs.jobid,efy_jobs.jobname,,efy_jobs.jobstatus,efy_contracts.contractid,efy_contracts.amount,efy_contracts.createdate as cDate";
        $userJoin = array('joinTbl'=>'efy_jobs', 'on'=>'efy_jobs.jobid = efy_contracts.jobid','type'=>'left');
        $conditions = array(
            'table'=>'efy_contracts',
            'join'=>array($userJoin),
            //'order'=>"efy_contracts.firstname DESC",
            'fields'=>$field
        );
        $conditions['where'] = array('homeowneragree'=>'1','workeragree'=>'1','efy_contracts.status'=>'1');

        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('startdate', 'Start Date', 'trim|required');
            $this->form_validation->set_rules('enddate', 'End Date', 'trim|required');

            if ($this->form_validation->run() == FALSE)
            {
                //redirect($_SERVER['HTTP_REFERER']);
            }
            else {
                $conditions['where']['DATE_FORMAT(from_unixtime(efy_contracts.createdate),"%Y-%m-%d") >='] = date('Y-m-d',strtotime($this->input->post('startdate')));
                $conditions['where']['DATE_FORMAT(from_unixtime(efy_contracts.createdate),"%Y-%m-%d") <='] = date('Y-m-d',strtotime($this->input->post('enddate')));
                //$conditions['where'] = array('DATE_FORMAT(from_unixtime(efy_contracts.createdate),"%Y-%m-%d") >='=>date('Y-m-d',strtotime($this->input->post('startdate'))),'DATE_FORMAT(from_unixtime(efy_contracts.createdate),"%Y-%m-%d") <='=>date('Y-m-d',strtotime($this->input->post('enddate'))));
                if($this->input->post('type') != 'all' && $this->input->post('users') != 'all'){
                    if($this->input->post('type') == 'homeowner'){
                        $conditions['where']['efy_contracts.ownerid'] = $this->input->post('users');
                    }else if($this->input->post('type') == 'contractor'){
                        $conditions['where']['efy_contracts.contractorid'] = $this->input->post('users');
                    }

                    // users list
                    $condition['where']['usertype'] = $this->input->post('type');
                }
                $data['startdate'] = $this->input->post('startdate');
                $data['enddate'] = $this->input->post('enddate');
                $data['type'] = $this->input->post('type');
                $data['userId'] = $this->input->post('users');
            }
        }else{
            $sDate = date('Y-m-d',strtotime(' - 30 days'));
            $eDate = date('Y-m-d');
            $conditions['where']['DATE_FORMAT(from_unixtime(efy_contracts.createdate),"%Y-%m-%d") >='] = $sDate;
            $conditions['where']['DATE_FORMAT(from_unixtime(efy_contracts.createdate),"%Y-%m-%d") <='] = $eDate;
            $data['startdate'] = $sDate;
            $data['enddate'] = $eDate;
            $condition['where']['usertype'] = 'all';
        }

       // get users list

        $users = $this->app->getData($condition);
        $content = $this->app->getData($conditions);
        //echo '<pre>';print_r($content);exit;
        $pendingAmount = 0;
        $receivedAmount = 0;
        foreach ($content as $k=>$v) {
                if ($v['jobstatus'] == 'inprogress') {
                    $pendingAmount = $pendingAmount + $v['amount'];
                } else if ($v['jobstatus'] == 'verified' || $v['jobstatus'] == 'completed') {
                    $receivedAmount = $receivedAmount + $v['amount'];
                }
        }

        $data['usersList'] = $users;
        $data['fList'] = $content;
        $data['report'] = array('Pending'=>$pendingAmount,'Received'=>$receivedAmount);

        //echo '<pre>';print_r($data);exit;

        $this->load->view('admin/template/header');

        $this->load->view('admin/financial_reports',$data);

        $this->load->view('admin/template/footer');

    }

    function getUsers(){
        $id = $this->input->post('id');
        $cities = $this->app->getData(array('table'=>'efy_users','order'=>"efy_users.firstname ASC",'where'=>array('usertype'=>$id)));
        $data['cities'] = $cities;
        $this->load->view('admin/get_users',$data);
    }
}