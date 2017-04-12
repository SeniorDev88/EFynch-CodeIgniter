<?php
  if (!defined('BASEPATH'))
      exit('No direct script access allowed');
  require_once BASEPATH . '/libraries/Session.php';
  class MY_Session extends CI_Session
  {
      function __construct()
      {
          parent::__construct();
          $this->CI->session = $this;
      }
      public function sess_update()
      {
          $CI = get_instance();
          if (!$CI->input->is_ajax_request()) {
              parent::sess_update();
          }
      }
      // --------------------------------------------------------------------
      /**
       * sess_destroy()
       *
       * Clear's out the user_data array on sess::destroy.
       *
       * @access    public
       * @return    void
       */
      public function sess_destroy()
      {
          $this->userdata = array();
          parent::sess_destroy();
      }
  }
  /* End of file MY_Session.php */
  /* Location: ./application/libraries/MY_Session.php */
?>