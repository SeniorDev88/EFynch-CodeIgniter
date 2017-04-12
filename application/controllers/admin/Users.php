<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Users extends CI_Controller

{

    /////////////////////////////////////

    ////////// DECLARING VARIABLES //////

    /////////////////////////////////////

    var $data = array();
    var $tbl = 'efy_users';
    var $states = 'efy_states';
    var $cities = 'efy_city';

    /////////////////////////////////////

    ////////// CONSTRUCTOR //////////////

    /////////////////////////////////////

    function __construct()
    {

        parent::__construct();

        ini_set('display_errors', 1);

        $this->load->model('mdl_app', 'app');

        if (!$this->session->userdata('user')) {
            $this->session->set_flashdata('error', 'Login to view page');
            redirect(base_url('admin'));
        }

    }



    /////////////////////////////////////

    ////////// INDEX FUNCTION ///////////

    /////////////////////////////////////


    public function index()

    {
        $limit = 20;
        $conditions = array(
            'table' => $this->tbl,
            'order' => "createdate DESC"
        );
        //pagination
        $this->load->library('pagination');
        if ($this->uri->segment(4)) {
            $page = $this->uri->segment(4);
        } else {
            $page = 1;
        }

        $config['uri_segment'] = 4;
        $config['per_page'] = $limit;

        $offset = ($page * $config['per_page']) - $config['per_page'];

        if ($this->input->get('q')) {
            $q = $this->input->get('q');
            $conditions['custom'] = "CONCAT(firstname,' ',lastname) like '%" . $q . "%' OR usertype like '%" . $q . "%' OR email like '%" . $q . "%' OR phone like '%" . $q . "%'";
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
        $config['base_url'] = base_url('admin/users/index');
        $config['suffix'] = '?' . $query;
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
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $links = $this->pagination->create_links();

        // add limit
        $conditions = $conditions + array('limit' => $limit, 'offset' => $offset);
        $content = $this->app->getData($conditions);
        $data['content'] = $content;
        $data['links'] = $links;
        $data['offset'] = $offset;
        $data['perPage'] = $config['per_page'];
        $data['dataInfo'] = 'Showing ' . ($offset + 1) . ' to ' . ($offset + count($content)) . ' of ' . $total_row . ' entries';
        //echo '<pre>';print_r($data);exit;
        $this->load->view('admin/template/header');
        $this->load->view('admin/users', $data);
        $this->load->view('admin/template/footer');

    }

    public function addUser()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('usertype', 'User Type', 'trim|required');
            //$this->form_validation->set_rules('dob', 'DOB', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                //redirect($_SERVER['HTTP_REFERER']);
            } else {
                // check if images are greater then 6
                if(isset($_FILES["wIm"]) && count($_FILES["wIm"]['tmp_name'])<=6) {
                    // check is email already exits
                    $isEmailEx = $this->app->getData(array('table' => 'efy_users', 'where' => array('email' => $this->input->post('email'))));
                    if (count($isEmailEx) <= 0) {

                        $data = array(
                            'userkey' => generateUniqueKey(6),
                            'firstname' => $this->input->post('firstname'),
                            'lastname' => $this->input->post('lastname'),
                            'email' => $this->input->post('email'),
                            'usertype' => $this->input->post('usertype'),
                            'dob' => $this->input->post('dob'),
                            'phone' => $this->input->post('phone'),
                            'state' => $this->input->post('state'),
                            'city' => $this->input->post('city'),
                            'password' => passwordencrypt($this->input->post('password')),
                            'createdate' => time(),
                            'dob' => '',
                            'isagreeterms' => '1',
                            'isverified' => '1',
                            'status' => '1',
                            'updatedate' => time(),
                        );

                        $isAdded = $this->app->addContent($this->tbl, $data);
                        if ($isAdded) {
                            $lId = $this->app->lastId();
                            if ($this->input->post('usertype') == 'contractor') {
                                $data = array(
                                    'companyname' => $this->input->post('companyname'),
                                    'companyaddress' => $this->input->post('companyaddress'),
                                    'companycity' => $this->input->post('companycity'),
                                    'companyzip' => $this->input->post('companyzip'),
                                    'companystate' => $this->input->post('companystate'),
                                    'overview_experience' => $this->input->post('overview_experience'),
                                    'introduction' => $this->input->post('introduction'),
                                    'routingnumber' => $this->input->post('routingnumber'),
                                    'accountnumber' => $this->input->post('accountnumber'),
                                    'license' => $this->input->post('license'),
                                    'insurance' => $this->input->post('insurance'),
                                    'contractorLicense' => $this->input->post('contractorLicense'),
                                    'contractorInsurance' => $this->input->post('contractorInsurance'),
                                    'certifications' => $this->input->post('certifications'),
                                    'notable_work' => $this->input->post('notable_work'),
                                    'us_veteran' => $this->input->post('us_veteran'),
                                    'userid' => $lId
                                );
                                $this->app->addContent('efy_contractor_details', $data);
                                $file = $this->uploadWorkImg($lId);
                                $video = $this->uploadIntoVideo($lId);
                            }
                            $file = $this->uploadImg($lId);
                            $this->session->set_flashdata('success', "User added successfully");
                            redirect("admin/users");
                        } else {
                            $this->session->set_flashdata('error', 'Something Went Wrong... Try Again');
                            redirect($_SERVER['HTTP_REFERER']);
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Provided email already exits');
                    }
                }else {
                    $this->session->set_flashdata('error', 'Only 6 working images are allowed');
                }
            }
        }

        // get state and cities
        $states = $this->app->getData(array('table' => $this->states));
        reset($states);
        $cities = $this->app->getData(array('table' => $this->cities, 'where' => array('state_code' => $states[0]['state_prefix'])));

        $data['cities'] = $cities;
        $data['states'] = $states;

        $this->load->view('admin/template/header');
        $this->load->view('admin/addUser', $data);
        $this->load->view('admin/template/footer');
    }

    public function editUser($id)
    {
        $images = $this->app->getData(array(
            'table'=>'efy_docs',
            'where'=>array('efy_docs.parentid' => $id,'efy_docs.parenttype'=>'work_image')
        ));

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
            //$this->form_validation->set_rules('email', 'Email', 'trim|required');
            //$this->form_validation->set_rules('usertype', 'User Type', 'trim|required');
            //$this->form_validation->set_rules('dob', 'DOB', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                //redirect($_SERVER['HTTP_REFERER']);
            } else {
                //  echo 1;exit;
                //$file = $this->imageUpload('file');

                // check is email already exits
                $isEmailEx = $this->app->getData(array('table' => 'efy_users', 'where' => array('userid !='=>$id,'email' => $this->input->post('email'))));
                if (count($isEmailEx) <= 0) {
                    $data = array(
                        'firstname' => $this->input->post('firstname'),
                        'lastname' => $this->input->post('lastname'),
                        //'email' => $this->input->post('email'),
                        //'usertype' => $this->input->post('usertype'),
                        'dob' => $this->input->post('dob'),
                        'state' => $this->input->post('state'),
                        'city' => $this->input->post('city'),
                        'phone' => $this->input->post('phone'),
                        'updatedate' => time(),
                    );

                    if ($this->input->post('password') && $this->input->post('password') != '') {
                        $data['password'] = passwordencrypt($this->input->post('password'));
                    }
                    //if($file)
                    //  $data['file'] = $file;

                    $isUpdated = $this->app->updateRecord($this->tbl, array('userid' => $id), $data);
                    if ($isUpdated) {
                        $file = $this->uploadImg($id);
                        // contractor detail
                        if ($this->input->post('usertype') == 'contractor') {
                            $data = array(
                                'companyname' => $this->input->post('companyname'),
                                'companyaddress' => $this->input->post('companyaddress'),
                                'companycity' => $this->input->post('companycity'),
                                'companyzip' => $this->input->post('companyzip'),
                                'companystate' => $this->input->post('companystate'),
                                'overview_experience' => $this->input->post('overview_experience'),
                                'introduction' => $this->input->post('introduction'),
                                'routingnumber' => $this->input->post('routingnumber'),
                                'accountnumber' => $this->input->post('accountnumber'),
                                'license' => $this->input->post('license'),
                                'insurance' => $this->input->post('insurance'),
                                'contractorLicense' => $this->input->post('contractorLicense'),
                                'contractorInsurance' => $this->input->post('contractorInsurance'),
                                'certifications' => $this->input->post('certifications'),
                                'notable_work' => $this->input->post('notable_work'),
                                'us_veteran' => $this->input->post('us_veteran'),
                            );
                            $isUpdated = $this->app->updateRecord('efy_contractor_details', array('detailsid' => $this->input->post('detailsid')), $data);
                            // check if images are greater then 6
                            if(isset($_FILES["wIm"]) && count($_FILES["wIm"]['tmp_name']) <= 6-count($images)) {
                                $file = $this->uploadWorkImg($id);
                                $video = $this->uploadIntoVideo($id);
                                $this->session->set_flashdata('success', "User updated successfully");
                                redirect("admin/users/userDetail/" . $id);
                            }else{
                                $this->session->set_flashdata('error', 'Only '.(6-count($images)).' working images are allowed');
                            }
                        }else {
                            $this->session->set_flashdata('success', "User updated successfully");
                            redirect("admin/users/userDetail/" . $id);
                        }
                    } else {
                        $this->session->set_flashdata('success', 'Nothing Changed');
                        redirect("admin/users");
                    }
                }else{
                    $this->session->set_flashdata('error', 'Provided email already exits');
                }
            }
        }

        $content = $this->app->getContent($this->tbl, 'userid', $id);
        $contractorD = $this->app->getContent('efy_contractor_details', 'userid', $id);
        $content[0]['contractor'] = $contractorD[0];
        $content[0]['images'] = $images;
        $data['content'] = $content[0];

        // get state and cities
        $states = $this->app->getData(array('table' => $this->states));
        reset($states);
        $city = $this->app->getData(array('table' => $this->cities, 'where' => array('city' => $content[0]['city'])));
        if (count($city) > 0)
            $cities = $this->app->getData(array('table' => $this->cities, 'where' => array('state_code' => $city[0]['state_code'])));
        else
            $cities = $this->app->getData(array('table' => $this->cities, 'where' => array('state_code' => $states[0]['state_prefix'])));

        $data['cities'] = $cities;
        $data['states'] = $states;
        //echo '<pre>';print_r($data);exit;
        $this->load->view('admin/template/header');
        $this->load->view('admin/editUser', $data);
        $this->load->view('admin/template/footer');
    }

    function userDetail($id)
    {
        $contractJoin = array('joinTbl'=>'efy_contractor_details', 'on'=>'efy_contractor_details.userid = efy_users.userid','type'=>'left');
        $user = $this->app->getData(array(
            'table' => $this->tbl,
            'where' => array('efy_users.userid' => $id),
            'join'=>array($contractJoin),
            'fields'=>'efy_users.*,efy_contractor_details.companyname,efy_contractor_details.companyaddress,efy_contractor_details.overview_experience,efy_contractor_details.introduction,efy_contractor_details.accountnumber,efy_contractor_details.routingnumber,efy_contractor_details.insurance,efy_contractor_details.license,efy_contractor_details.companyzip,efy_contractor_details.companycity,efy_contractor_details.certifications,efy_contractor_details.notable_work,efy_contractor_details.us_veteran,efy_contractor_details.contractorInsurance,efy_contractor_details.contractorLicense,efy_contractor_details.companystate'
        ));

        if (count($user) <= 0) {
            $this->session->set_flashdata('error', "No user found");
            redirect("admin/users");
        }

        $images = $this->app->getData(array(
            'table'=>'efy_docs',
            'where'=>array('efy_docs.parentid' => $id,'efy_docs.parenttype'=>'work_image')
        ));
        $user[0]['images'] = $images;
        $data['user'] = $user[0];
        //echo '<pre>';print_r($user);exit;
        $this->load->view('admin/template/header');
        $this->load->view('admin/userDetail', $data);
        $this->load->view('admin/template/footer');
    }

    function deleteUser($id)
    {
        $this->app->delete($this->tbl, 'userid', $id);
        $this->session->set_flashdata('success', "User deleted successfully");
        redirect("admin/users");
    }

    function getDeleteWorkImg(){
        $id = $this->input->post('id');
        $this->app->delete('efy_docs', 'docid', $id);
        echo 'Deleted successfully';exit;
    }

    function getCities()
    {
        $id = $this->input->post('id');
        $cities = $this->app->getData(array('table' => $this->cities, 'where' => array('state_code' => $id)));
        $data['cities'] = $cities;
        $this->load->view('admin/get_cities', $data);
    }

    private function uploadImg($userId)
    {
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
            //$file = $_FILES['image'];
            $target_dir = APPPATH . "../assets/profImgs/crop/00000";
            $filename = $_FILES['image']["name"];
            //$filename = time() . '_' . $filename;
            $docKey = generateUniqueKey(10);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $target_file = $target_dir . '/' . $docKey . '.' . $ext;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $data = array(
                    'imagekey' => $docKey,
                    'imageext' => $ext
                );
                $isAdded = $this->app->updateRecord('efy_users', array('userid'=>$userId),$data);
            } /*else {
                $this->session->set_flashdata('error', "Image upload failure");
                redirect("admin/jobs/addJob");
            }*/
        }
    }

    private function uploadWorkImg($jobId){
        $i=0;
        foreach($_FILES["wIm"]["tmp_name"] as $key=>$tmp_name)
            if($i<7) {
                if (!empty($_FILES['wIm']['name'][$key]) && $_FILES['wIm']['error'][$key] == 0) {
                    //$file = $_FILES['image'];
                    $target_dir = APPPATH . "../assets/docs/00000";
                    $filename = $_FILES['wIm']["name"][$key];
                    //$filename = time() . '_' . $filename;
                    $docKey = generateUniqueKey(6);
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $target_file = $target_dir . '/' . $docKey . '.' . $ext;
                    if (move_uploaded_file($_FILES["wIm"]["tmp_name"][$key], $target_file)) {
                        $data = array(
                            'dockey' => $docKey,
                            'docext' => $ext,
                            'parenttype' => 'work_image',
                            'parentid' => $jobId,
                            'originalname' => $filename,
                            'createdate' => time()
                        );
                        $isAdded = $this->app->addContent('efy_docs', $data);
                        $i++;
                    }
                    /*else {
                    $this->session->set_flashdata('error', "Image upload failure");
                    redirect("admin/jobs/addJob");
                }*/
                }
            }

    }

    private function uploadIntoVideo($user_id){
        ini_set('max_execution_time',300);
        if(isset($_FILES['video']['tmp_name']))
        {
            //echo '<pre>';print_r($_FILES);exit;
            $config['upload_path']          = APPPATH."../assets/videos";
            $config['allowed_types']        = 'm4v|ogg|mp4|mov';
            $config['max_size']             = 50000;
            $ext = explode(".",$_FILES['video']['name']);
            $ext = end($ext);
            $output_file_name = 'post_'.mt_rand();
            $file_name = $output_file_name.".$ext";
            $config['file_name'] = $file_name;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('video'))
            {
                //var_dump($this->upload->display_errors('<div class="alert alert-error">', '</div>'));exit;

            }else {
                //echo 1;exit;
                $data = array('intro_video'=>$file_name);
                $isUpdated = $this->app->updateRecord($this->tbl, array('userid' => $user_id), $data);
            }
        }
    }
}