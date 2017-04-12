<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Login extends CI_Controller

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

        $this->load->model('mdl_app', 'app');

    }



    /////////////////////////////////////

    ////////// INDEX FUNCTION ///////////

    /////////////////////////////////////


    public function index()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            if ($this->form_validation->run() == FALSE)
            {
                redirect($_SERVER['HTTP_REFERER']);
            }
            else {
                
				$conditions = array('email'=>$this->input->post('email'),'password'=>md5($this->input->post('password')));
                $user = $this->app->getContentWMC('admin_users', $conditions);
             
				if (count($user)>0) {
                    $user['login'] = true;
                    $this->session->set_userdata('user',$user);
                    //var_dump($this->session->userdata('user')[0]['email']);exit;
                    redirect('admin/dashboard');
                } else {
                    $this->session->set_flashdata('error', 'Invalid username/password');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
        $this->load->view('admin/login');
    }

    public function change_password()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('pre_password', 'Old Password', 'trim|required');
            $this->form_validation->set_rules('new_password', 'New Password', 'trim|required');

            if ($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('error', validation_errors());
                redirect('admin/login/change_password');
            }
            else
            {

                if($this->app->check_pre_password($this->input->post('pre_password'), $this->input->post('email')) == FALSE)
                {
                    $this->session->set_flashdata('error', 'Old Password or Username invalid!');
                    redirect('admin/login/change_password');
                }
                else
                {
                    $data = array(
                        'password' => md5($this->input->post(new_password))
                    );
                    $response = $this->app->update_password($data, $this->input->post('email'));
                    if($response == true)
                    {
                        $this->session->set_flashdata('success', 'Password changed successfully!');
                        redirect('admin/login/change_password');
                    }
                    else
                    {
                        $this->session->set_flashdata('error', 'Something went wrong!');
                        redirect('admin/login/change_password');
                    }
                }
            }
        }
        $this->load->view('admin/change_pass');
    }

    function logout(){
        $this->session->sess_destroy();
        redirect(base_url('admin'));
    }
}