<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Bids extends CI_Controller
{

    /////////////////////////////////////

    ////////// DECLARING VARIABLES //////

    /////////////////////////////////////

    var $data = array();
    var $tbl = 'efy_bids';
    var $jobs = 'efy_jobs';
    var $users = 'efy_users';
    var $states = 'efy_states';
    var $cities = 'efy_city';

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



    public function index()
    {
        $limit = 20;
        $fields = 'efy_users.firstname,efy_users.lastname,efy_bids.startdate as bStartDate,efy_bids.*,efy_jobs.*';
        $jobJoin = array('joinTbl'=>'efy_jobs', 'on'=>'efy_bids.jobid = efy_jobs.jobid','type'=>'left');
        $userJoin = array('joinTbl'=>'efy_users', 'on'=>'efy_bids.userid = efy_users.userid','type'=>'left');
        $conditions = array(
            'table'=>$this->tbl,
            'order'=>"bidid DESC",
            'join'=>array($jobJoin,$userJoin)
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
            $conditions['custom'] = "efy_jobs.jobname like '%".$q."%' OR CONCAT(efy_users.firstname,' ',efy_users.lastname) like '%".$q."%' OR efy_bids.bidamount like '%".$q."%'";
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
        $config['base_url'] = base_url('admin/bids/index');
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
        $data['content'] = $content;
        $data['links'] = $links;
        $data['offset'] = $offset;
        $data['perPage'] = $config['per_page'];
        $data['dataInfo'] = 'Showing ' . ($offset+1) .' to '.($offset + count($content)).' of '.$total_row.' entries';
        //echo '<pre>';print_r($data);exit;
        $this->load->view('admin/template/header');
        $this->load->view('admin/bids',$data);
        $this->load->view('admin/template/footer');

    }

    function bidDetail($id){
        $fields = 'efy_users.firstname,efy_users.lastname,efy_bids.createdate as bcreatedate,efy_bids.updatedate as bupdatedate,efy_bids.startdate as bStartDate,efy_bids.*,efy_jobs.*';
        $jobJoin = array('joinTbl'=>'efy_jobs', 'on'=>'efy_bids.jobid = efy_jobs.jobid','type'=>'left');
        $userJoin = array('joinTbl'=>'efy_users', 'on'=>'efy_bids.userid = efy_users.userid','type'=>'left');
        $conditions = array('table'=>$this->tbl,'where'=>array('bidid'=>$id),'fields'=>$fields,'join'=>array($userJoin,$jobJoin));
        $content = $this->app->getData($conditions);
        if(count($content)<=0){
            $this->session->set_flashdata('error', 'No record found');
            redirect('admin/bids');
        }
        $data['data'] = $content[0];
        //echo '<pre>';print_r($data);exit;
        $this->load->view('admin/template/header');
        $this->load->view('admin/bidDetail',$data);
        $this->load->view('admin/template/footer');
    }

    public function addBid(){
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('bidamount', 'Bid Amount', 'trim|required');
            if ($this->form_validation->run() == FALSE)
            {
                // redirect($_SERVER['HTTP_REFERER']);
            }
            else {
                //$file = $this->imageUpload('file');
                $data = array(
                    'bidkey' => generateUniqueKey(10),
                    'jobid' => $this->input->post('jobid'),
                    'description' => $this->input->post('description'),
                    'userid' => $this->input->post('userid'),
                    'status'=> $this->input->post('status'),
                    'isfavourite' => $this->input->post('isfavourite'),
                    'additionalamount' => $this->input->post('additionalamount'),
                    'startdate' => $this->input->post('startdate'),
                    'bidamount' => $this->input->post('bidamount'),
                    'maxtime' =>$this->input->post('maxtime'),
                    'maxtype' =>$this->input->post('maxtype'),
                    'exptype' =>$this->input->post('exptype'),
                    'exptime' =>$this->input->post('exptime'),
                    'createdate'=>time(),
                    'updatedate' => time(),
                );

                $isAdded = $this->app->addContent($this->tbl, $data);
                if ($isAdded) {
                    $this->session->set_flashdata('success', "Bid added successfully");
                    redirect("admin/bids");
                } else {
                    $this->session->set_flashdata('error', 'Something Went Wrong... Try Again');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }

        $users = $this->app->getData(array('table'=>$this->users,'fields'=>'userid,firstname,lastname','where'=>array('usertype'=>"contractor")));
        $jobs = $this->app->getData(array('table'=>$this->jobs,'fields'=>'jobid,jobname'));
        $data['users'] = $users;
        $data['jobs'] = $jobs;
//        print_r($data);exit;
        $this->load->view('admin/template/header');
        $this->load->view('admin/addBid',$data);
        $this->load->view('admin/template/footer');
    }

    public function editBid($id){
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('bidamount', 'Bid Amount', 'trim|required');
            if ($this->form_validation->run() == FALSE)
            {
               // redirect($_SERVER['HTTP_REFERER']);
            }
            else {
                //$file = $this->imageUpload('file');
                $data = array(
                    'jobid' => $this->input->post('jobid'),
                    'description' => $this->input->post('description'),
                    'userid' => $this->input->post('userid'),
                    'status'=> $this->input->post('status'),
                    'isfavourite' => $this->input->post('isfavourite'),
                    'additionalamount' => $this->input->post('additionalamount'),
                    'maxtime' =>$this->input->post('maxtime'),
                    'maxtype' =>$this->input->post('maxtype'),
                    'exptype' =>$this->input->post('exptype'),
                    'exptime' =>$this->input->post('exptime'),
                    'startdate' => $this->input->post('startdate'),
                    'bidamount' => $this->input->post('bidamount'),
                    'updatedate' => time(),
                );

                $isUpdated = $this->app->updateRecord($this->tbl, array('bidid'=>$id),$data);
                if ($isUpdated) {
                    $this->session->set_flashdata('success', "Bid updated successfully");
                    redirect("admin/bids");
                } else {
                    $this->session->set_flashdata('success', 'Nothing Changed');
                    redirect("admin/bids");
                }
            }
        }

        $conditions = array('table'=>$this->tbl,'where'=>array('bidid'=>$id));
        $content = $this->app->getData($conditions);
        if(count($content)<=0){
            $this->session->set_flashdata('error', 'No record found');
            redirect('admin/bids');
        }
        $users = $this->app->getData(array('table'=>$this->users,'fields'=>'userid,firstname,lastname'));
        $jobs = $this->app->getData(array('table'=>$this->jobs,'fields'=>'jobid,jobname'));
        $data['content'] = $content[0];
        $data['users'] = $users;
        $data['jobs'] = $jobs;
       //print_r($data);exit;
        $this->load->view('admin/template/header');
        $this->load->view('admin/editBid',$data);
        $this->load->view('admin/template/footer');
    }

    function deleteBid($id){
        $this->app->delete($this->tbl,'bidid',$id);
        $this->session->set_flashdata('success', "Bid deleted successfully");
        redirect("admin/bids");

    }

}