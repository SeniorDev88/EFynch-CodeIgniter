<?php



function validator($params){

    $obj = get_instance();

    $paramVal = array();

    $undefinedVals = array();

    foreach($params as $val){

        if ($obj->input->post($val)) {

            $paramVal[$val] = (trim($obj->input->post($val)));

        }else{

            $undefinedVals[$val] = 'Not set';

        }

    }



    return array($paramVal,$undefinedVals);

}



function getValidator($params){

    $obj = get_instance();

    $paramVal = array();

    $undefinedVals = array();

    foreach($params as $val){

        if ($obj->input->get($val)) {

            $paramVal[$val] = mysql_real_escape_string(trim($obj->input->get($val)));

        }else{

            $undefinedVals[$val] = 'Not set';

        }

    }



    return array($paramVal,$undefinedVals);

}



function jsonOutPut($response){

    echo json_encode($response);exit;

}



function checkSession(){

    $obj = get_instance();

    $obj->load->model('front/mdl_users');

    $data = false;

    if(isset($_COOKIE['c_user'])){

        $user = $obj->mdl_users->getUser($_COOKIE['c_user']);

        $data = $user;

    }



    return $data;

}



function generateCode() {

    $lowercase = "qwertyuiopasdfghjklzxcvbnm";

    $uppercase = "ASDFGHJKLZXCVBNMQWERTYUIOP";

    $numbers = "1234567890";

    //$specialcharacters = "-_!";

    $randomCode = "";

    mt_srand(crc32(microtime()));

    $max = strlen($lowercase) - 1;

    for ($x = 0; $x < abs(6/3); $x++) {

        $randomCode .= $lowercase{mt_rand(0, $max)};

    }

    $max = strlen($uppercase) - 1;

    for ($x = 0; $x < abs(6/3); $x++) {

        $randomCode .= $uppercase{mt_rand(0, $max)};

    }

    $max = strlen($numbers) - 1;

    for ($x = 0; $x < abs(6/3); $x++) {

        $randomCode .= $numbers{mt_rand(0, $max)};

    }

    return  str_shuffle($randomCode);

    //return $random;

}



function searchVal($data,$key){

    $result = '';

    foreach ($data as $k=>$v) {

        if ($v['type'] == $key) {

            $result = $v['content'];

        }

    }



    return $result;

}



function loadModel(){

    $obj = get_instance();

    $obj->load->model('front/mdl_app','app');

    return $obj;

}



function getUniqueColumnList($tbl){

    $obj = loadModel();

    $fields = 'id,category';

    $data = $obj->app->getContent($tbl,false,false,$fields,false,false,false,0,'category');

    return $data;

    //echo '<pre>';print_r($data);exit;

}



function generateRecoveryToken($length = 50) {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $randomString = '';

    for ($i = 0; $i < $length; $i++) {

        $randomString .= $characters[rand(0, strlen($characters) - 1)];

    }

    return $randomString . uniqid();

}

function generateUniqueKey( $count ) {
    $ukey = substr( strtolower( md5( microtime() . rand() ) ), 0, $count );
    return $ukey;
}

function passwordencrypt($password,$rounds = 7)
{
    $salt = "";
    $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
    for($i=0; $i < 22; $i++) {
        $salt .= $salt_chars[array_rand($salt_chars)];
    }
    return crypt($password, sprintf('$2a$%02d$', $rounds) . $salt);
}

function do_upload($name , $types = '' , $upload = '')
{
    $CI = get_instance();
    $config['upload_path'] = $upload;
    $config['allowed_types'] = $types;
    $config['remove_spaces'] = TRUE;
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $new_name = generateUniqueKey(6).$ext;
    $config['file_name'] = $new_name;
    if ( ! is_dir($config['upload_path']) ) die("THE UPLOAD DIRECTORY DOES NOT EXIST");
    $CI->load->library('upload', $config);
    if ( ! $CI->upload->do_upload($name)) 
    {
        return array('error' => $CI->upload->display_errors());
    } 
    else 
    {
        return array('success' => $CI->upload->data());
    }
}



?>