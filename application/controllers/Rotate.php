<?php
  /***********meera march 14 2014*****************/
  class Rotate extends CI_Controller {
      public function __construct( ) {
          parent::__construct();
          $this->load->model( array(
               'loan/Loan_model'
          ) );
          $this->load->library( array(
               'corefunctions',
               'cropimage' 
          ) );  
      }
	  
		function TEST($file,$fname,$loandocid){	
			
			$rotateFilename = $file; // PATH
			$degrees = 90;
			$fileType = strtolower(substr($fname, strrpos($fname, '.') + 1));
			$filekey = $this->corefunctions->generateUniqueKey('12', 'loan_docs', 'dockey');
			//$newpath = "assets/loan/docs/".$filekey.'.'.$fileType;	
			$newpath = $this->corefunctions->getMyPath($loandocid, $filekey, $fileType, 'assets/loan/docs/');
			if($fileType == 'png' || $fileType == 'PNG'){
			   //header('Content-type: image/png');
			   $source = imagecreatefrompng($rotateFilename);
			   $bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
			   // Rotate
			   $rotate = imagerotate($source, $degrees, $bgColor);
			   imagesavealpha($rotate, true);
			   imagepng($rotate,$newpath);
			   $this->Loan_model->Updatecropedloanimage($loandocid,$filekey,$fname);
				//imagedestroy($source);
				//imagedestroy($rotate);
				//unlink($file);
				return $newpath;
			}
           elseif($fileType == 'jpg' || $fileType == 'jpeg'){
			   //header('Content-type: image/jpeg');
			   $source = imagecreatefromjpeg($rotateFilename);
			   // Rotate
			   $rotate  = imagerotate($source, $degrees, 0);
			   imagejpeg($rotate,$newpath);	
               $this->Loan_model->Updatecropedloanimage($loandocid,$filekey,$fname);	
                //imagedestroy($source);
				//imagedestroy($rotate);
				//unlink($file);
				return $newpath;			   
			}
			
		}
	  
      public function index( $dockey ) {    
          $imageArray = array(
               "loandocs" => array(
                  "width" => "",
                  "height" => "",
                  "minwidth" => "",
                  "minheight" => "" 
              )
          );
          $data[ "imageSizes" ] = $imageArray['loandocs'];
          $data[ "view" ]       = 1;
          $data[ "type" ]       = "loandocs";
          $data[ 'error' ]      = "";
		  $imageview            = $dockey;
		  
		  if(isset($_POST[ "Rotate" ])){
			  $view = 1;
		  }else if(isset( $_POST[ "Crop" ] )){
			  $view = 2;
		  }else if(isset( $_POST[ "thumbnail" ] )){
			  $view = 3;
		  }else{
			  $view = 1; 
		  }
		  
		  
		  
		  
		  
		  
			if($dockey){
		    $imgdets = $this->Loan_model->getdocdetailsbykey($dockey);
			  if(!empty($imgdets)){
			  //$imgpath = base_url('assets/loan/docs/'.$imgdets['dockey'].'.'.$imgdets['docext']);                                                         
			  $imgpath = $this->corefunctions->getMyPath($imgdets['loandocid'], $imgdets['dockey'], $imgdets['docext'], 'assets/loan/docs/');
			  $location        = "assets/loan/docs/";			  
			  $haserror        = 0;                     
					if(!isset($_POST[ "Rotate" ])){
					$data[ 'dockey' ]  = $imgdets['dockey'];
                      $data[ 'imageLink' ] = base_url().$imgpath;
                      $data[ "view" ]      = $view;
                      $data[ 'error' ]     = "";
                      $data[ 'viewdoc' ]     = $imageview;
                  }
			}
		  }
	
          if(isset($_POST[ "Rotate" ])){
			if($_POST[ "Rotate" ] == "Rotate"){
			  if($_POST['left'] == 1){				  
			$filename = $imgpath;
			$imname   = $imgdets['dockey'].'.'.$imgdets['docext'];
			//$saveto   = "assets/loan/docs/".$imgdets['dockey'].'.'.$imgdets['docext'];
			$saveto = $this->corefunctions->getMyPath($imgdets['loandocid'], $imgdets['dockey'], $imgdets['docext'], 'assets/loan/docs/');
			$newfile  = $this->TEST($saveto,$imname,$imgdets['loandocid']);
			$data[ 'dockey' ]  = $imgdets['dockey'];
			$data[ 'imageLink' ] = base_url().$newfile;			
			$data[ "view" ]      = $view;	
            $data[ 'error' ]     = "";
		    $data[ 'viewdoc' ]     = $imageview;
			//print_r($data); exit;
			
			  }
		  }			  
		  } 
		  
		if(isset($_POST[ "Crop" ])){
			$imgdets = $this->Loan_model->getdocdetailsbykey($dockey);
					if($_POST[ "Crop" ] == "Crop"){
					  $data[ 'dockey' ]  = $imgdets['dockey'];
                      $data[ 'imageLink' ] = base_url().$imgpath;
                      $data[ "view" ]      = $view;
                      $data[ 'error' ]     = "";
				      $data[ 'viewdoc' ]     = $imageview;					  
		}  
		}
	  
		 if ( isset( $_POST[ "thumbnail" ] ) ) {
			 $imgdets = $this->Loan_model->getdocdetailsbykey($dockey);
			 $filekey = $this->corefunctions->generateUniqueKey('12', 'loan_docs', 'dockey');
			 //$oldpath = 'assets/loan/docs/'.$imgdets['dockey'].'.'.$imgdets['docext'];
			 $oldpath = $this->corefunctions->getMyPath($imgdets['loandocid'], $imgdets['dockey'], $imgdets['docext'], 'assets/loan/docs/');			 
             $tempImageDetails = $imgdets;
			 //$tempCropStorage = "assets/loan/docs/".$filekey.'.'.$imgdets['docext']; 
             $tempCropStorage = $this->corefunctions->getMyPath($imgdets['loandocid'], $filekey, $imgdets['docext'], 'assets/loan/docs/'); 
              if ( !empty( $tempImageDetails ) ) {
                  //$tempSource      = base_url('assets/loan/docs/'.$imgdets['dockey'].'.'.$imgdets['docext']); 
                  $tempSource      = $this->corefunctions->getMyPath($imgdets['loandocid'], $imgdets['dockey'], $imgdets['docext'], 'assets/loan/docs/');		
				  $cropDestination = $tempCropStorage;
                  
                  $data[ 'imageType' ] = $tempSource;
                  
                  list( $imagewidth, $imageheight, $imageType ) = getimagesize( $tempSource );
                  $imageType = image_type_to_mime_type( $imageType );
/*                   print "<pre>";
                  print_r( $imageType );
                  print "</pre>"; */
                  
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
                  $dst_r = imagecreatetruecolor( $_POST[ 'w' ], $_POST[ 'h' ] );
                  //$dst_r = ImageCreateTrueColor( $imageArray[ $type ][ 'width' ], $imageArray[ $type ][ 'height' ] );
                  imagecopyresampled( $dst_r, $img_r, 0, 0, $_POST[ 'x1' ], $_POST[ 'y1' ], $_POST[ 'w' ], $_POST[ 'h' ], $_POST[ 'w' ], $_POST[ 'h' ] );
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
                  $this->Loan_model->Updatecropedloanimage($imgdets['loandocid'],$filekey);
			      unlink($oldpath);				  
                  $data[ 'imagekey' ]  = $tempImageDetails[ "dockey" ];
                  $data[ 'imagePath' ] = base_url() . $tempCropStorage;
                  $data[ "view" ]      = 3;
				  $data[ 'viewdoc' ]   = $imageview;                  
				  $data[ 'imageLink' ] = base_url() . $tempCropStorage;             
                  
              }
              
          }
          $this->load->view( 'rotate', $data );
      }
	  
	  
	  
  }