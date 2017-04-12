<?php
  /***********meera march 14 2014*****************/
  class Crop extends CI_Controller {
      public function __construct( ) {
          parent::__construct();
          $this->load->model( array(
              'User_model' 
          ) );
          $this->load->library( array(
               'corefunctions',
              'cropimage' 
          ) );

          
      }
	  
      function Rotate_img($file, $fname, $tempid) {
          $Storage        = "assets/tempImgs/original/";
          $rotateFilename = $file; // PATH
          $degrees        = -90;
          $fileType       = strtolower(substr($fname, strrpos($fname, '.') + 1));
          $filekey        = $this->corefunctions->generateUniqueKey('12', 'tempimage', 'tempimgkey');
          $newpath        = $Storage . $filekey . '.' . $fileType;
          if ($fileType == 'png' || $fileType == 'PNG') {
              //header('Content-type: image/png');
              $source  = imagecreatefrompng($rotateFilename);
              $bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
              // Rotate
              $rotate  = imagerotate($source, $degrees, $bgColor);
              imagesavealpha($rotate, true);
              imagepng($rotate, $newpath);
          }
          if ($fileType == 'jpg' || $fileType == 'jpeg') {
              //header('Content-type: image/jpeg');
              $source = imagecreatefromjpeg($rotateFilename);
              // Rotate
              $rotate = imagerotate($source, $degrees, 0);
              imagejpeg($rotate, $newpath);
          }
          $this->User_model->Updatecropedimage($tempid, $filekey);
          // Free the memory
          imagedestroy($source);
          imagedestroy($rotate);
          //unlink($file);		  
		  delete_files($file);
          $redata['newpath'] = $newpath;
          $redata['imgkey']  = $filekey;
          return $redata;
      }	  
	  
      public function index( $type ) {
          
          $location        = "assets/tempImgs/";
          $tempStorage     = $location . "original/";
          $tempCropStorage = $location . "crop/";
          $haserror        = 0;
		  $imgfor          = $type;
          /*
          allowed image upload types
          */
          $imageArray = array(
               "profile" => array(
                   "width" => 200,
                  "height" => 200,
                  "minwidth" => 200,
                  "minheight" => 200 
              ),
              "news" => array(
                   "width" => 250,
                  "height" => 250,
                  "minwidth" => 250,
                  "minheight" => 250 
              ),
              "estore" => array(
                   "width" => 300,
                  "height" => 400,
                  "minwidth" => 300,
                  "minheight" => 400 
              ),
              "userprofile" => array(
                   "width" => 200,
                  "height" => 200,
                  "minwidth" => 200,
                  "minheight" => 200 
              ),
              "post" => array(
                   "width" => 670,
                  "height" => 370,
                  "minwidth" => 670,
                  "minheight" => 370 
              ) 
          );
          /*
          allowed image upload types
          */
          if ( !array_key_exists( $type, $imageArray ) ) {
              exit;
          }
          $data[ "imageSizes" ] = $imageArray[ $type ];
          $data[ "view" ]       = 1;
          $data[ "type" ]       = $type;
          $data[ "imgfor" ]    = $imgfor;			  
          $data[ 'error' ]      = "";
          
		  
          if ( isset( $_POST[ "fileupload" ] ) ) {
              $image = $_FILES[ 'myfile' ];
			  if($image[ 'tmp_name' ]!=""){
              list( $width, $height, $type, $attr ) = getimagesize( $image[ 'tmp_name' ] );
              
			if($_POST[ 'crop'] == "1" ){
			  if ( $width < $imageArray[ $_POST[ "imtype" ] ][ 'minwidth' ] || $height < $imageArray[ $_POST[ "imtype" ] ][ 'minheight' ] ) {
                  $haserror        = 1;
                  $data[ 'error' ] = 'Please upload an image of size greater than ' . $imageArray[ $_POST[ "imtype" ] ][ 'minwidth' ] . ' X ' . $imageArray[ $_POST[ "imtype" ] ][ 'minheight' ] . ' pixels';
                  
                  
            }	
			  }
              if ( $haserror != 1 ) {
                  $config[ 'upload_path' ]   = $tempStorage;
                  $config[ 'allowed_types' ] = 'gif|jpg|png|jpeg';
				  if($imgfor != "estore"){
					  $config[ 'max_size' ]      = '7000';
					  $config[ 'max_width' ]     = '4000';
					  $config[ 'max_height' ]    = '4000';
				  }
                  
                  /* Generate Unique Key for image uploading */
                  $imgKey = $this->corefunctions->generateUniqueKey( '12', 'tempimage', 'tempimgkey' );
                  
                  /* Extension Details */
                  $ext                   = pathinfo( $_FILES[ 'myfile' ][ 'name' ], PATHINFO_EXTENSION );
                  $config[ 'file_name' ] = $fileName = $imgKey . "." . $ext;
                  
                  
                  $this->load->library( 'upload', $config );
                  if ( !$this->upload->do_upload( 'myfile' ) ) {
                      $data[ 'error' ] = $this->upload->display_errors();
                  } else {
                      $uploadData = $this->upload->data();
                      
                      /* Create Temporary Image Data */
                      $this->User_model->create_temp_img( $imgKey, $ext, $uploadData[ "image_width" ], $uploadData[ "image_height" ] );
                      
                      $data[ 'image' ]     = array(
                           'upload_data' => $uploadData 
                      );
                      $data[ 'imagekey' ]  = $imgKey;
                      $data[ "type" ]      = $type;					  
                      $data[ "imgfor" ]    = $imgfor;					  
                      $data[ 'imageLink' ] = base_url() . $tempStorage . $fileName;
					  if($imgfor != "estore"){
                      $data["view"]      = 2;
					  }else{
					  $data["view"]      = 4;  
					  }
                      $data[ 'error' ]     = "";
                  }
              }
		  }else{
			  $haserror        = 1;
              $data[ 'error' ] ="Please select an image to proceed";
		  }
		          
          }
          
          if (isset($_POST["Rotate"])) {
              if ($_POST["Rotate"] == "Rotate") {
                  if ($_POST['left'] == 1) {
                      $imgdet            = $this->User_model->get_temp_det($_POST["imagekey"]);
                      $imname            = $imgdet['tempimgkey'] . '.' . $imgdet['tempimgext'];
                      $saveto            = base_url() . $tempStorage . $imname;
                      $rdata             = $this->Rotate_img($saveto, $imname, $imgdet['tempimgid']);
                      $data['imagekey']  = $rdata['imgkey'];
                      $data['imageLink'] = base_url() . $rdata['newpath'];
                      $data["view"]      = 4;
                      $data[ "type" ]    = $type;	
                      $data[ "imgfor" ]  = $imgfor;					  
                      $data['error']     = "";
                      //print_r($data); exit;
                  }
              }
          }
          if (isset($_POST["Skiprotate"])) {
              if ($_POST["Skiprotate"] == "Crop") {
                  $imgdet            = $this->User_model->get_temp_det($_POST["imagekey"]);
                  $imname            = $imgdet['tempimgkey'] . '.' . $imgdet['tempimgext'];
                  $saveto            = base_url() . $tempStorage . $imname;
                  $data['imagekey']  = $imgdet['tempimgkey'];
                  $data['imageLink'] = $saveto;
                  $data["view"]      = 2;
				  $data[ "type" ]    = $type;	
				  $data[ "imgfor" ]  = $imgfor;					  
                  $data["isshow"]    = 'show';
                  $data['error']     = "";
              }
          }
          
          if ( isset( $_POST[ "thumbnail" ] )  or $_POST['skip'] == "1")  {
              $tempImageDetails = $this->User_model->get_temp_det( $_POST[ "imagekey" ] );
              
              if ( !empty( $tempImageDetails )  and $_POST['skip'] == "1") {
                  $tempSource      =  base_url() .$tempStorage . $tempImageDetails[ "tempimgkey" ] . "." . $tempImageDetails[ "tempimgext" ];
                   $cropDestination =  $tempCropStorage . $tempImageDetails[ "tempimgkey" ] . "." . $tempImageDetails[ "tempimgext" ];
				   copy($tempSource,$cropDestination);
				  
				           
                  $data[ 'imagekey' ]  = $tempImageDetails[ "tempimgkey" ];
                  $data[ 'imagePath' ] = base_url().$cropDestination;
                  $data[ "view" ]      = 3;
                  $data[ "skip" ]      = "1";
			  }else if(!empty( $tempImageDetails )){	
				$tempSource      = $tempStorage . $tempImageDetails[ "tempimgkey" ] . "." . $tempImageDetails[ "tempimgext" ];
                  
				  $cropDestination = $tempCropStorage . $tempImageDetails[ "tempimgkey" ] . "." . $tempImageDetails[ "tempimgext" ];
                  
                  $data[ 'imageType' ] = $tempSource;
                  if($_POST['crop'] == 1){
                  list( $imagewidth, $imageheight, $imageType ) = getimagesize( $tempSource );
                  $imageType = image_type_to_mime_type( $imageType );
                  print "<pre>";
                  print_r( $imageType );
                  print "</pre>";
                  
                  switch ( $imageType ) {
                      case "image/gif":
                          $img_r = imagecreatefromgif( $tempSource );
                          break;
                      case "image/pjpeg":
                      case "image/jpeg":
                      case "image/jpg":
                          $img_r = imagecreatefromjpeg( $tempSource );
                          break;
                      case "image/png":
                      case "image/x-png":
                          $img_r = imagecreatefrompng( $tempSource );
                          break;
                  }
                  
                  //$img_r = imagecreatefromjpeg( $tempSource );
                  $dst_r = imagecreatetruecolor( $imageArray[ $type ][ 'width' ], $imageArray[ $type ][ 'height' ] );
                  //$dst_r = ImageCreateTrueColor( $imageArray[ $type ][ 'width' ], $imageArray[ $type ][ 'height' ] );
                  imagecopyresampled( $dst_r, $img_r, 0, 0, $_POST[ 'x1' ], $_POST[ 'y1' ], $imageArray[ $type ][ 'width' ], $imageArray[ $type ][ 'height' ], $_POST[ 'w' ], $_POST[ 'h' ] );
                  switch ( $imageType ) {
                      case "image/gif":
                          imagegif( $dst_r, $cropDestination );
                          break;
                      case "image/pjpeg":
                      case "image/jpeg":
                      case "image/jpg":
                          imagejpeg( $dst_r, $cropDestination, 90 );
                          break;
                      case "image/png":
                      case "image/x-png":
                          imagepng( $dst_r, $cropDestination );
                          break;
                  }
                  //imagejpeg( $dst_r, $cropDestination );
			  }
                  $data[ 'imagekey' ]  = $tempImageDetails[ "tempimgkey" ];
                  $data[ 'imagePath' ] = base_url() . $cropDestination;
                  $data[ "view" ]      = 3;
                  
                  
              }
              
          }
          
          $this->load->view( 'crop', $data );
      }
  }