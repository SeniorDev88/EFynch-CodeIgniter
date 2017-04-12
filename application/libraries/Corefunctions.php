<?php
  if ( !defined( 'BASEPATH' ) )
      exit( 'No direct script access allowed' );
  class Corefunctions extends CI_Model {
      function __construct( ) {
          parent::__construct();
          $this->load->database();
      }
      function parseIncoming( ) {
          global $_GET, $_POST, $HTTP_CLIENT_IP, $REQUEST_METHOD, $REMOTE_ADDR, $HTTP_PROXY_USER, $HTTP_X_FORWARDED_FOR;
          $return = array( );
          if ( is_array( $_GET ) ) {
              $return1 = recursivePostCheck( $_GET );
              $return  = array_merge( $return, $return1 );
          }
          if ( is_array( $_POST ) ) {
              $return1 = recursivePostCheck( $_POST );
              $return  = array_merge( $return, $return1 );
          }
          $return[ 'REQUEST_METHOD' ] = $_SERVER[ 'REQUEST_METHOD' ];
          $return[ 'IP_ADDRESS' ]     = $_SERVER[ 'SERVER_ADDR' ];
          $return[ 'IP_CLIENT' ]      = $_SERVER[ 'REMOTE_ADDR' ];
          $return[ 'IP_CLIENT' ]      = isset( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ? $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] : $_SERVER[ 'REMOTE_ADDR' ];
          define( 'IS_POST', $return[ 'REQUEST_METHOD' ] == 'POST' );
          define( 'IP_CLIENT', $return[ 'IP_CLIENT' ] );
          //define ('IP_CLIENT','94.75.245.1');
          return $return;
      }
      function recursivePostCheck( $anush ) {
          //op ($anush);
          $return = array( );
          while ( list( $k, $v ) = each( $anush ) ) {
              if ( is_array( $anush[ $k ] ) ) {
                  $return[ $k ] = recursivePostCheck( $anush[ $k ] );
                  //$return = array_merge ($return, $return1);
              } else {
                  $return[ cleanKey( $k ) ] = cleanValue( $v );
              }
          }
          //op (  $return );
          return $return;
      }
      function recursiveArraySearch( $haystack, $needle, $index = null ) {
          $aIt = new RecursiveArrayIterator( $haystack );
          $it  = new RecursiveIteratorIterator( $aIt );
          while ( $it->valid() ) {
              if ( ( ( isset( $index ) AND ( $it->key() == $index ) ) OR ( !isset( $index ) ) ) AND ( $it->current() == $needle ) ) {
                  return $aIt->key();
              }
              $it->next();
          }
          return false;
      }
      function cleanKey( $key ) {
          return $key;
      }
      function cleanValue( $val ) {
          if ( get_magic_quotes_gpc() != 0 ) {
              $val = stripslashes( $val );
          }
          $val = ltrim( rtrim( strip_tags( $val ) ) );
          return $val;
      }
      function setCookies( $name, $value, $sticky = 1 ) {
          if ( $sticky == 1 ) {
              $expires = time() + 60 * 60 * 24 * 365;
          }
          if ( $_SERVER[ 'HTTP_HOST' ] != '127.0.0.1' && $_SERVER[ 'HTTP_HOST' ] != 'localhost' ) {
              if ( strtolower( substr( $_SERVER[ 'HTTP_HOST' ], 0, 4 ) ) == 'www.' )
                  $cookie_domain = substr( $_SERVER[ 'HTTP_HOST' ], 3 );
              else
                  $cookie_domain = '.' . $_SERVER[ 'HTTP_HOST' ];
          } else {
              $cookie_domain = "";
          }
          $cookie_path = "/";
          $name        = COOKIEPREFIX . $name;
          setcookie( $name, $value, $expires, $cookie_path, $cookie_domain );
      }
      function getCookies( $name ) {
          global $_COOKIE;
          if ( isset( $_COOKIE[ COOKIEPREFIX . $name ] ) ) {
              return urldecode( $_COOKIE[ COOKIEPREFIX . $name ] );
          } else {
              return FALSE;
          }
      }
      function getCookieFrom( $cookiename, $source ) {
          $cookiename   = 'file_' . $cookiename;
          $cookiestring = $source;
          $index1       = strpos( $cookiestring, $cookiename );
          if ( $index1 === false || $cookiename == "" )
              return "";
          $index2 = strpos( $cookiestring, ';', $index1 );
          if ( $index2 === false )
              $index2 = strlen( $cookiestring );
          return PHPunescape( substr( $cookiestring, $index1 + strlen( $cookiename ) + 1, $index2 - $index1 - strlen( $cookiename ) - 1 ) );
      }
      function input2cookie( $input, $prefix = '' ) {
          $parts = split( ',', $prefix );
          foreach ( $input as $name => $value ) {
              if ( !is_array( $value ) ) {
                  if ( $parts )
                      foreach ( $parts as $part ) {
                          if ( substr( $name, 0, strlen( $part ) ) == $part ) {
                              setCookies( $name, $value );
                              break;
                          }
                      } else {
                      setCookies( $name, $value );
                  }
              }
          }
      }
      function verifyEmail( $email ) {
          return preg_match( "~^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$~", $email );
      }
      function urlCheck( $url ) {
          $url     = ltrim( rtrim( $url ) );
          $pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
          if ( !preg_match( $pattern, $url ) ) {
              return false;
          } else {
              return true;
          }
      }
      function ago( $time ) {
          $periods    = array(
               "second",
              "minute",
              "hour",
              "day",
              "week",
              "month",
              "year",
              "decade" 
          );
          $lengths    = array(
               "60",
              "60",
              "24",
              "7",
              "4.35",
              "12",
              "10" 
          );
          $now        = time();
          $difference = $now - $time;
          $tense      = "ago";
          for ( $j = 0; $difference >= $lengths[ $j ] && $j < count( $lengths ) - 1; $j++ ) {
              $difference /= $lengths[ $j ];
          }
          $difference = round( $difference );
          if ( $difference != 1 ) {
              $periods[ $j ] .= "s";
          }
          return "$difference $periods[$j]";
      }
      function op( $var ) {
          if ( is_object( $var ) or is_array( $var ) ) {
              print "<br /><pre>";
              print_r( $var );
              print "</pre>";
          } else {
              print "<br />" . $var;
          }
      }
      public function checkDuplicate( $table, $field1, $fieldvalue1, $field2 = '', $fieldvalue2 = '' ) {
          global $db;
          $db1   = $db;
          $query = $this->db->get_where( $table, array(
               $field1 => $fieldvalue1 
          ) );
          return $query->row_array() ? true : false;
      }
      public function generateUniqueKey( $count, $table, $field ) {
          $ukey = substr( strtolower( md5( microtime() . rand() ) ), 0, $count );
          while ( $this->checkDuplicate( $table, $field, $ukey ) ) {
              $ukey = substr( strtolower( md5( microtime() . rand() ) ), 0, $count );
          }
          return $ukey;
      }
/*       public function passwordencrypt( $password ) {
          $passwordencrypt = md5( PWD . $password ); //to find PWD check config/constants.php
          return $passwordencrypt;
      } */
	  public function passwordencrypt($password,$rounds = 7)
		{
			$salt = "";
			$salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
			for($i=0; $i < 22; $i++) {
				$salt .= $salt_chars[array_rand($salt_chars)];
			}
			return crypt($password, sprintf('$2a$%02d$', $rounds) . $salt);
		}
      function generateUniqueKey1( $count ) {
          $ukey = substr( strtolower( md5( microtime() . rand() ) ), 0, $count );
          return $ukey;
      }
      function RetriveSingleRowDetails( $tablename, $field, $fieldvalue ) {
          global $db;
          $ret_scom_query = "select * from #_$tablename where $field = '" . $fieldvalue . "'";
          $db->setQuery( $ret_scom_query );
          $db->query();
          //op($db -> getQuery());
          return $db->getNumRows() ? $db->loadRow() : array( );
      }
      function getMyPath( $id, $file_id, $ext, $upload_dir ) {
          $dx          = sprintf( "%05d", $id / 5000 );
          //$upload_dir = ROOT . "/uploads/" ;
          $folder_path = $upload_dir . $dx;
          if ( !is_dir( $folder_path ) )
              mkdir( $folder_path );
          $save_file = $folder_path . "/" . $file_id . "." . $ext;
          return $save_file;
      }
      function getTempPath( $id, $file_id, $ext, $upload_dir ) {
          $dx          = sprintf( "%05d", $id / 5000 );
          $folder_path = $upload_dir . $dx;
          $save_file   = $folder_path . "/" . $file_id . "." . $ext;
          return $save_file;
      }
      function getTempPathForPreview1( $id, $file_id, $ext, $upload_dir ) {
          $dx          = sprintf( "%05d", $id / 5000 );
          $folder_path = base_url() . $upload_dir . $dx;
          $file_folder = $folder_path . "/" . $file_id;
          $save_file   = $folder_path . "/" . $file_id . "." . $ext;
          return $save_file;
      }
      function remove_path( $folder ) {
          $files = glob( $folder . "/" . '*' );
          foreach ( $files as $file ) {
              if ( $file == '.' || $file == '..' ) {
                  continue;
              }
              if ( is_dir( $file ) ) {
                  $this->remove_path( $file );
              } else {
                  unlink( $file );
              }
          }
          rmdir( $folder );
      }
      /*Meera - Mail with excel attachment*/
      function mailWithAttachmentOfExcel( $from, $to, $subject, $message, $attachment ) {
          $fileatt       = $attachment; // Path to the file                  
          $fileatt_type  = "application"; // File Type 
          $start         = strrpos( $attachment, '/' ) == -1 ? strrpos( $attachment, '//' ) : strrpos( $attachment, '/' ) + 1;
          $fileatt_name  = substr( $attachment, $start, strlen( $attachment ) ); // Filename that will be used for the file as the     attachment 
          $email_from    = $from; // Who the email is from 
          $email_subject = $subject; // The Subject of the email 
          $email_txt     = $message; // Message that the email has in it 
          $email_to      = $to; // Who the email is to
          $headers       = "From: " . $email_from;
          //$headers .= "\nCc: payment@comtranslations.com";
          //$headers .= "\nCc: ".$o3->email;
          $file          = fopen( $fileatt, 'rb' );
          $data          = fread( $file, filesize( $fileatt ) );
          fclose( $file );
          $msg_txt       = "";
          $semi_rand     = md5( time() );
          $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
          $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
          $email_txt .= $msg_txt;
          $email_message .= "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type:text/html; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $email_txt . "\n\n";
          $data = chunk_split( base64_encode( $data ) );
          $email_message .= "--{$mime_boundary}\n" . "Content-Type: {$fileatt_type};\n" . " name=\"{$fileatt_name}\"\n" . 
          //"Content-Disposition: attachment;\n" . 
              
          //" filename=\"{$fileatt_name}\"\n" . 
              "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n" . "--{$mime_boundary}--\n";
          $ok = @mail( $email_to, $email_subject, $email_message, $headers );
          if ( $ok ) {
              return true;
          } else {
              return false;
          }
      }
      public function sendmail( $from, $to, $subject, $message ) {
          /*$headers = "MIME-Version: 1.0" . "\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          $headers .= 'From: <' . $from . '>' . "\r\n";
          //$headers .= 'Cc: myboss@example.com' . "\r\n";
          mail( $to, $subject, $message, $headers ); */
            $this->load->library('email');
            $config['mailtype'] = 'html';
            $config['newline'] = '\r\n';
            $config['crlf'] = '\r\n';
            $config['charset'] = 'UTF-8';
            $this->email->initialize($config);

            $this->email->from($from);
            $this->email->to($to);
            
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
      }
      public function getImagePath( $id, $upload_dir, $type = "upload" ) {
          $dx          = sprintf( "%05d", $id / 5000 );
          $folder_path = $upload_dir . $dx;
          if ( $type == "upload" ) {
              if ( !is_dir( $folder_path ) )
                  mkdir( $folder_path );
          }
          $save_file = $folder_path . "/";
          return $save_file;
      }
      public function getArrayIndexed( $array, $index ) {
          $finalArray = array( );
          if ( !empty( $array ) ) {
              foreach ( $array as $a ) {
                  $finalArray[ $a[ $index ] ] = $a;
              }
          }
          return $finalArray;
      }
      function agoAndDate( $time ) {
          $periods    = array(
               "second",
              "minute",
              "hour",
              "day",
              "week",
              "month",
              "year",
              "decade" 
          );
          $lengths    = array(
               "60",
              "60",
              "24",
              "7",
              "4.35",
              "12",
              "10" 
          );
          $now        = time();
          $difference = $now - $time;
          $tense      = "ago";
          if ( $difference <= "604800" ) {
              for ( $j = 0; $difference >= $lengths[ $j ] && $j < count( $lengths ) - 1; $j++ ) {
                  $difference /= $lengths[ $j ];
              }
              $difference = round( $difference );
              if ( $difference != 1 ) {
                  $periods[ $j ] .= "s";
              }
              return "$difference $periods[$j] $tense";
          } else {
              return date( 'M d ,Y h:i A', $time );
          }
      }
      public function stripe( $card_number, $cvvnumber, $od_totalamount, $month, $year ) {
          /**************stripe starts meera march 20***********/
          //$this->load->library('ci_payments/stripe');
          $card     = array(
               'number' => $card_number,
              'exp_month' => $month,
              'exp_year' => $year,
              'cvc' => $cvvnumber 
          );
          $response = $this->stripe->charge_card( $od_totalamount, $card, 'Payment' );
          $response = json_decode( $response, true );
          return $response;
          /**************stripe starts meera march 20***********/
      }
      public function usps_inernational( $country, $weight, $shipping_method, $w, $l, $h, $ounce, $option ) {
          $devurl      = "testing.shippingapis.com/ShippingAPITest.dll";
          $puburl      = "https://secure.shippingapis.com/ShippingAPITest.dll";
          $prourl      = "http://production.shippingapis.com/ShippingAPI.dll";
          $service     = "IntlRateV2";
          $userName    = USPS_USERNAME;
          $orig_zip    = USPS_ORIGIN_ZIP; // Zipcode you are shipping FROM
          $destination = $country;
          $weight      = $weight;
          $ren_xml     = "<IntlRateV2Request USERID=\"$userName\">
				    <Revision>2</Revision>
				    <Package ID=\"1ST\">
				    <Pounds>$weight</Pounds>
				    <Ounces>$ounce</Ounces>
				    <MailType>$shipping_method</MailType>
				    <GXG>
				     <POBoxFlag>N</POBoxFlag>
				     <GiftFlag>N</GiftFlag>
				     </GXG>
				     <ValueOfContents>0</ValueOfContents>
				    <Country>$destination</Country>
				    <Container>RECTANGULAR</Container>
				    <Size>REGULAR</Size>
				      <Width>$w</Width>
				      <Length>$l</Length>
				      <Height>$h</Height>
				      <Girth>0</Girth>
				    <OriginZip>$orig_zip</OriginZip>
				    </Package>
				    </IntlRateV2Request>
				    ";
          $xml         = rawurlencode( $ren_xml );
          $request     = $prourl . "?API=" . $service . "&xml=" . $xml;
          $ch          = curl_init();
          curl_setopt( $ch, CURLOPT_URL, $request );
          curl_setopt( $ch, CURLOPT_HEADER, false );
          curl_setopt( $ch, CURLOPT_HTTPGET, true );
          curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
          $response = curl_exec( $ch );
          if ( $response ) {
              $data       = strstr( $response, '<?' );
              // echo '<!-- '. $data. ' -->'; // Uncomment to show XML in comments
              $xml_parser = xml_parser_create();
              xml_parse_into_struct( $xml_parser, $data, $vals, $index );
              xml_parser_free( $xml_parser );
              $params = array( );
              $level  = array( );
              $xml    = simplexml_load_string( $response );
              $fields = array( );
              foreach ( $xml->field as $f ) {
                  $f         = (array) $f->attributes();
                  $fields[ ] = $f[ '@attributes' ];
              }
              echo "<pre>";
              print_r( $vals );
              echo "</pre>";
              foreach ( $vals as $xml_elem ) {
                  if ( $xml_elem[ 'type' ] == 'open' ) {
                      if ( array_key_exists( 'attributes', $xml_elem ) ) {
                          list( $level[ $xml_elem[ 'level' ] ], $extra ) = array_values( $xml_elem[ 'attributes' ] );
                      } else {
                          $level[ $xml_elem[ 'level' ] ] = $xml_elem[ 'tag' ];
                      }
                  }
                  if ( $xml_elem[ 'type' ] == 'complete' ) {
                      $start_level = 1;
                      $php_stmt    = '$params';
                      while ( $start_level < $xml_elem[ 'level' ] ) {
                          $php_stmt .= '[$level[' . $start_level . ']]';
                          $start_level++;
                      }
                      $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
                      eval( $php_stmt );
                  }
              }
              curl_close( $ch );
              // op($ren_xml);
              // op($params);
              $arr_i = 15;
              if ( $option == "First Class" ) {
                  $arr_i = 15;
              }
              if ( $option == "Priority Mail" ) {
                  $arr_i = 2;
              }
              if ( $option == "Express Mail" ) {
                  $arr_i = 1;
              }
              return $params[ 'INTLRATEV2RESPONSE' ][ '1ST' ][ $arr_i ][ 'POSTAGE' ] ? $params[ 'INTLRATEV2RESPONSE' ][ '1ST' ][ $arr_i ][ 'POSTAGE' ] : 0;
          }
      }
      public function usps_domestic( $weight, $dest_zip, $shipping_method, $w, $l, $h, $ounce ) {
          $devurl   = "testing.shippingapis.com/ShippingAPITest.dll";
          $puburl   = "https://secure.shippingapis.com/ShippingAPITest.dll";
          $prourl   = "http://production.shippingapis.com/ShippingAPI.dll";
          $service  = "RateV4";
          $userName = USPS_USERNAME;
          $orig_zip = USPS_ORIGIN_ZIP; // Zipcode you are shipping FROM
          //$dest_zip 	= 21117;
          $weight   = $weight;
          $ft       = ( $shipping_method == "FIRST CLASS" ) ? "<FirstClassMailType>PARCEL</FirstClassMailType>" : "";
          $ren_xml  = "<RateV4Request USERID=\"$userName\" >
	     <Revision/>
	     <Package ID=\"1ST\">
		  <Service>$shipping_method</Service>$ft
		  <ZipOrigination>$orig_zip</ZipOrigination>
		  <ZipDestination>$dest_zip</ZipDestination>
		  <Pounds>$weight</Pounds>
		  <Ounces>$ounce</Ounces>
		  <Container/>
		  <Size>REGULAR</Size>
		  <Width>$w</Width>
		  <Length>$l</Length>
		  <Height>$h</Height>
		  <Girth>0</Girth>
		  <Machinable>false</Machinable>
	     </Package>
	</RateV4Request>";
          $xml      = rawurlencode( $ren_xml );
          $request  = $prourl . "?API=" . $service . "&xml=" . $xml;
          $ch       = curl_init();
          curl_setopt( $ch, CURLOPT_URL, $request );
          curl_setopt( $ch, CURLOPT_HEADER, false );
          curl_setopt( $ch, CURLOPT_HTTPGET, true );
          curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
          $response   = curl_exec( $ch );
          $data       = strstr( $response, '<?' );
          // echo '<!-- '. $data. ' -->'; // Uncomment to show XML in comments
          $xml_parser = xml_parser_create();
          xml_parse_into_struct( $xml_parser, $data, $vals, $index );
          xml_parser_free( $xml_parser );
          $params = array( );
          $level  = array( );
          $xml    = simplexml_load_string( $response );
          $fields = array( );
          foreach ( $xml->field as $f ) {
              $f         = (array) $f->attributes();
              $fields[ ] = $f[ '@attributes' ];
          }
          foreach ( $vals as $xml_elem ) {
              if ( $xml_elem[ 'type' ] == 'open' ) {
                  if ( array_key_exists( 'attributes', $xml_elem ) ) {
                      list( $level[ $xml_elem[ 'level' ] ], $extra ) = array_values( $xml_elem[ 'attributes' ] );
                  } else {
                      $level[ $xml_elem[ 'level' ] ] = $xml_elem[ 'tag' ];
                  }
              }
              if ( $xml_elem[ 'type' ] == 'complete' ) {
                  $start_level = 1;
                  $php_stmt    = '$params';
                  while ( $start_level < $xml_elem[ 'level' ] ) {
                      $php_stmt .= '[$level[' . $start_level . ']]';
                      $start_level++;
                  }
                  $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
                  eval( $php_stmt );
              }
          }
          curl_close( $ch );
          //op($ren_xml); 
          //op($params);
          if ( $shipping_method == "FIRST CLASS" ) {
              $arr_i = 0;
          }
          if ( $shipping_method == "PRIORITY" ) {
              $arr_i = 1;
          }
          if ( $shipping_method == "EXPRESS" ) {
              $arr_i = 3;
          }
          if ( $shipping_method == "STANDARD POST" ) {
              $arr_i = 4;
          }
          return $params[ 'RATEV4RESPONSE' ][ '1ST' ][ $arr_i ][ 'RATE' ] ? $params[ 'RATEV4RESPONSE' ][ '1ST' ][ $arr_i ][ 'RATE' ] : 0;
      }
      function array_multi_subsort( $array, $subkey ) {
          $b = array( );
          $c = array( );
          foreach ( $array as $k => $v ) {
              $b[ $k ] = strtolower( $v[ $subkey ] );
          }
          asort( $b );
          foreach ( $b as $key => $val ) {
              $c[ ] = $array[ $key ];
          }
          return $c;
      }
	  
 public function check_captcha($str){
	  
    $word = $this->session->userdata('captchaWord');
    if(strcmp(strtoupper($str),strtoupper($word)) == 0){		
      return true;
    }
    else{
      return false;
    }
  }
  
  public function CI_create_captcha(){
	 $random_number = substr(number_format(time() * rand(),0,'',''),0,6);
      $vals = array(
             'word' => $random_number,
             'img_path' => './captcha/',
             'img_url' => base_url().'captcha/',
             'expiration' => 7200,
			 'colors'        => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(180, 180, 180),
                'grid' => array(255, 255, 255)
           )

            );
      $data['captcha'] = create_captcha($vals);
      $this->session->set_userdata('captchaWord',$data['captcha']['word']); 
	  return $data['captcha']['image'];
  
  } 
	
	public function geteventdets($eventid){
		  $sql   = 'SELECT * FROM ' . $this->db->dbprefix('events') . ' WHERE eventid = ? limit 1';
          $query = $this->db->query($sql, array(
              $eventid
          ));
          //print $this->db->last_query();
          return $query->row_array();		
	}
	
	public function addloanlog($eventid,$loanid,$ipaddress,$device,$admin){
	  $userid = ( $this->session->userdata('userId')== "" ) ? $this->session->userdata('adminId') : $this->session->userdata('userId');
	  $logkey = $this->generateUniqueKey('12', 'events_log', 'logkey');
	  $isadmin = ($admin == 1) ? '1': '0';
	  $data    = array(
		  'eventid' => $eventid,
		  'loanid' => $loanid,
		  'userid' => $userid,
		  'logkey' => $logkey,
		  'ip' => $ipaddress,
		  'byadmin'=>$isadmin,
		  'device' => $device,
		  'createdate' => time()
	  );
	  $this->db->insert('events_log', $data);
	  $insert_id = $this->db->insert_id();
	  $this->db->trans_complete();
	  //print $this->db->last_query();
	  return $insert_id;		
	}
	
  public function addlogdata($logID,$dbnewinput,$dboldinput=NULL){
	   $data = array(
		  'logdata'   => $dbnewinput,
		  'olddata'   => $dboldinput,
		  'updatedate'      => time()
	  );
	  $this->db->where('logid', $logID);
	  $this->db->update('events_log', $data);
	  //print $this->db->last_query();
	  //exit;
  }	

	public function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
 
		return $ipaddress;  
	}
  
	public function createloan_logs($eventid,$loanid,$newdata=NULL,$olddata=NULL,$admin=NULL){
		if($eventid !="" and $loanid !=""){
			$eventdet = $this->geteventdets($eventid);
				if(!empty($eventdet)){
					$ipaddress = $this->get_client_ip();
					$device    = $_SERVER['HTTP_USER_AGENT'];
					$logID = $this->addloanlog($eventid,$loanid,$ipaddress,$device,$admin);
					if(!empty($olddata) and !empty($newdata)){
					 $dboldinput =  json_encode($olddata);
					 $dbnewinput =  json_encode($newdata);				 				 
						$this->addlogdata($logID,$dbnewinput,$dboldinput);
					}else if(!empty($newdata)){
						$newdatas = json_encode($newdata);
						$this->addlogdata($logID,$newdatas);
					}				
				}
		}
	}

      public function getAllUserTypesIds() {
          $sql   = 'SELECT * FROM ' . $this->db->dbprefix('usertype');
          $query = $this->db->query($sql, array());
          // print $this->db->last_query();
          return $this->corefunctions->getArrayIndexed($query->result_array(),'usertypeid');
		  
      }
	
	
	public function getactivitylogtext($data){
		$loanlog = array();
		if(!empty($data)){	
			if($data['eventid'] == 1){
				$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				$loanlog['usertype'] = $data['user']['usertype'];
				$loanlog['event']    = "Created this loan file";
				$loanlog['time']  = date("m/d/Y | h:i a",$data['createdate']);
					if($data['user']['imgkey']){
						if($data['user']['userid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
						}else if($data['user']['adminid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
						}										
					}else{
						$loanlog['img']   = base_url()."images/defaultimg.jpg";
					}
			}
			else if($data['eventid'] == 2){				
				if(!empty($data['data']) and !empty($data['old'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $data['user']['usertype'];						
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
						
							$m = 1;
							$cn = count($data['data']);
							foreach($data['old'] as $k => $v){
								foreach($data['data'] as $d => $s){														
									if($k == $d and $v and $s){
										if($m < $cn){
											if($k == "loantypeid"){
												$edited.= "Loan Type from ".$v." to ".$s.",";
											}else{
										  $edited.= $k." from ".$v." to ".$s.",";	
											}										  
										}else{
										if($k == "loantypeid"){
											$edited.= "Loan Type from ".$v." to ".$s;	
											}else{	
										 $edited.= $k." from ".$v." to ".$s;	
											}										 
										}
									}
								}
							$m++;	
							}	  							
					$loanlog['event']	= "Edited ".$edited;	
				}else if(!empty($data['data']) and empty($data['old'])){
					$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
					$loanlog['usertype'] = $data['user']['usertype'];
					$loanlog['event']    = "Added new fields";
					$loanlog['time']  = date("m/d/Y | h:i a",$data['createdate']);
						if($data['user']['imgkey']){
							if($data['user']['userid'] !=""){
								$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
							}else if($data['user']['adminid'] !=""){
								$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
							}										
						}else{
							$loanlog['img']   = base_url()."images/defaultimg.jpg";
						}										
				}
			}else if($data['eventid'] == 3){
				$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				$loanlog['usertype'] = $data['user']['usertype'];
				$loanlog['event']    = "Added ".$data['data']['username']." as ".$data['data']['addedas'];
				$loanlog['time']  = date("m/d/Y | h:i a",$data['createdate']);
					if($data['user']['imgkey']){
						if($data['user']['userid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
						}else if($data['user']['adminid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
						}
					}else{
						$loanlog['img']   = base_url()."images/defaultimg.jpg";
					}
			}else if($data['eventid'] == 4){
				if(!empty($data['data'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $data['user']['usertype'];						
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}					
							$docdets = $data['data'];						
				    $edited .= "Folder : ".$docdets['mainfolder']."/".$docdets['foldername']."<br/> Files : ";
					if($docdets['docs']){
						$newcnt = count($docdets['docs']);
						$statcnt = 1;
						foreach($docdets['docs'] as $rr => $yy){
							if($statcnt < $newcnt){
							$filess .= $yy.", "; 
							}else{
							$filess .= $yy;	
							}	
						$statcnt++;							
						}
						
					}
					$edited .= $filess;	
					$loanlog['event']	= "File Upload <br/> ".$edited;	
				}					
			}else if($data['eventid'] == 5){
				if(!empty($data['data'])){
						$fdet = $data['data'];
					    $loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];	
				        $loanlog['usertype'] = $data['user']['usertype'];						
						$loanlog['event'] = "Created the folder ".$fdet['newfolder']." in ".$fdet['mainfolder'];
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
				}
			}else if($data['eventid'] == 6){
				if(!empty($data['data'])){
						$fdet = $data['data'];
					    $loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];	
				        $loanlog['usertype'] = $data['user']['usertype'];						
						$loanlog['event'] = "Moved to ".$fdet['moved'];
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
				}
			}else if($data['eventid'] == 7){	
				if(!empty($data['data'])){
						$fdet = $data['data'];
					    $loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];	
				        $loanlog['usertype'] = $data['user']['usertype'];												
						if(!empty($fdet['newfield'])){
						   $newf = count($fdet['newfield']); 
						   $cntf = 1;
						   foreach($fdet['newfield'] as $az => $ar){
							   if($cntf < $newf){
								   $setfiled .= $ar.",";
							   }else{
								   $setfiled .= $ar;
							   }
							   $cntf++;
						   }
						}
						$loanlog['event'] = "Added ".$setfiled." to ".$fdet['folder'];
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
				}
			}else if($data['eventid'] == 8){	
				if(!empty($data['data'])){
						$fdet = $data['data'];
					    $loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];	
				        $loanlog['usertype'] = $data['user']['usertype'];												
						if(!empty($fdet['removed'])){
						   $newf = count($fdet['removed']); 
						   $cntf = 1;
						   foreach($fdet['removed'] as $az => $ar){
							   if($cntf < $newf){
								   $setfiled .= $ar.",";
							   }else{
								   $setfiled .= $ar;
							   }
							   $cntf++;
						   }
						}
						$loanlog['event'] = "Removed ".$setfiled." from ".$fdet['folder'];
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
				}
			}else if($data['eventid'] == 9){
				$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				$loanlog['usertype'] = $data['user']['usertype'];
				$loanlog['event']    = "Removed ".$data['data']['username']." from loan users";
				$loanlog['time']  = date("m/d/Y | h:i a",$data['createdate']);
					if($data['user']['imgkey']){
						if($data['user']['userid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
						}else if($data['user']['adminid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
						}
					}else{
						$loanlog['img']   = base_url()."images/defaultimg.jpg";
					}
			}else if($data['eventid'] == 10){
				if(!empty($data['data'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $data['user']['usertype'];						
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}					
							$docdets = $data['data'];						
				    $edited .= "<br/> Folder : ".$docdets['mainfolder']."/".$docdets['foldername']."<br/> Files : ".$docdets['docs'];																											
					$loanlog['event']	= "Removed ".$edited;	
				}					
			}else if($data['eventid'] == 11){
				$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				$loanlog['usertype'] = $data['user']['usertype'];
				$loanlog['event']    = "Verified ".$data['data']['foldername']." in ".$data['data']['mainfolder'];
				$loanlog['time']  = date("m/d/Y | h:i a",$data['createdate']);
					if($data['user']['imgkey']){
						if($data['user']['userid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
						}else if($data['user']['adminid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
						}
					}else{
						$loanlog['img']   = base_url()."images/defaultimg.jpg";
					}
			}else if($data['eventid'] == 12){
				if(!empty($data['data'])){
						$fdet = $data['data'];						
					    $loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];	
				        $loanlog['usertype'] = $data['user']['usertype'];						
						$loanlog['event'] = "Removed the folder ".$fdet['newfolder']." from ".$fdet['mainfolder'];
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
				}
			}else if($data['eventid'] == 13){
				if(!empty($data['data'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];					
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}					
							$docdets = $data['data'];						
				    $edited .= "Approved : ".$docdets['mainfolder']."/".$docdets['foldername']."<br/>File : ".$docdets['docname'];	
					$loanlog['event']	= $edited;	
				}					
			}else if($data['eventid'] == 14){
				if(!empty($data['data'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];					
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}					
							$docdets = $data['data'];						
				    $edited .= "Rejected : ".$docdets['mainfolder']."/".$docdets['foldername']."<br/>File : ".$docdets['docname'];	
					$loanlog['event']	= $edited;	
				}					
			}else if($data['eventid'] == 15){
				if(!empty($data['data'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];					
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}					
							$docdets = $data['data'];						
				    $edited .= "Folder status changed from Verified to Not verified. <br/>".$docdets['mainfolder']."/".$docdets['foldername'];	
					$loanlog['event']	= $edited;	
				}					
			}
		}						
		return $loanlog;			   
	}

	public function getactivitylogtext_Api($data){
		$usertype = $this->getAllUserTypesIds();
		$loanlog = array();
		if(!empty($data)){	
			if($data['eventid'] == 1){
				$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				$loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];
				$loanlog['event']    = "Created this loan file";
				$loanlog['time']  = date("m/d/Y | h:i a",$data['createdate']);
					if($data['user']['imgkey']){
						if($data['user']['userid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
						}else if($data['user']['adminid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
						}										
					}else{
						$loanlog['img']   = base_url()."images/defaultimg.jpg";
					}
			}
			else if($data['eventid'] == 2){				
				if(!empty($data['data']) and !empty($data['old'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];						
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
						
							$m = 1;
							$cn = count($data['data']);
							foreach($data['old'] as $k => $v){
								foreach($data['data'] as $d => $s){														
									if($k == $d and $v and $s){
										if($m < $cn){
										  $edited.= $k." from ".$v." to ".$s.",";									
										}else{
										 $edited.= $k." from ".$v." to ".$s;										
										}
									}
								}
							$m++;	
							}	  							
					$loanlog['event']	= "Edited ".$edited;	
				}else if(!empty($data['data']) and empty($data['old'])){
					$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
					$loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];
					$loanlog['event']    = "Added new fields";
					$loanlog['time']  = date("m/d/Y | h:i a",$data['createdate']);
						if($data['user']['imgkey']){
							if($data['user']['userid'] !=""){
								$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
							}else if($data['user']['adminid'] !=""){
								$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
							}										
						}else{
							$loanlog['img']   = base_url()."images/defaultimg.jpg";
						}										
				}
			}else if($data['eventid'] == 3){
				$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				$loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];
				$loanlog['event']    = "Added ".$data['data']['username']." as ".$data['data']['addedas'];
				$loanlog['time']  = date("m/d/Y | h:i a",$data['createdate']);
					if($data['user']['imgkey']){
						if($data['user']['userid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
						}else if($data['user']['adminid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
						}
					}else{
						$loanlog['img']   = base_url()."images/defaultimg.jpg";
					}
			}else if($data['eventid'] == 4){
				if(!empty($data['data'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];					
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}					
							$docdets = $data['data'];						
				    $edited .= "Folder : ".$docdets['mainfolder']."/".$docdets['foldername']."\nFiles : ";
					if($docdets['docs']){
						$newcnt = count($docdets['docs']);
						$statcnt = 1;
						foreach($docdets['docs'] as $rr => $yy){
							if($statcnt < $newcnt){
							$filess .= $yy.", "; 
							}else{
							$filess .= $yy;	
							}	
						$statcnt++;							
						}
						
					}
					$edited .= $filess;	
					$loanlog['event']	= "File Upload \n".$edited;	
				}					
			}else if($data['eventid'] == 5){
				if(!empty($data['data'])){
						$fdet = $data['data'];
					    $loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];	
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];					
						$loanlog['event'] = "Created the folder ".$fdet['newfolder']." in ".$fdet['mainfolder'];
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
				}
			}else if($data['eventid'] == 6){
				if(!empty($data['data'])){
						$fdet = $data['data'];
					    $loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];	
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];					
						$loanlog['event'] = "Moved to ".$fdet['moved'];
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
				}
			}else if($data['eventid'] == 7){	
				if(!empty($data['data'])){
						$fdet = $data['data'];
					    $loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];	
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];											
						if(!empty($fdet['newfield'])){
						   $newf = count($fdet['newfield']); 
						   $cntf = 1;
						   foreach($fdet['newfield'] as $az => $ar){
							   if($cntf < $newf){
								   $setfiled .= $ar.",";
							   }else{
								   $setfiled .= $ar;
							   }
							   $cntf++;
						   }
						}
						$loanlog['event'] = "Added ".$setfiled." to ".$fdet['folder'];
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
				}
			}else if($data['eventid'] == 8){	
				if(!empty($data['data'])){
						$fdet = $data['data'];
					    $loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];	
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];										
						if(!empty($fdet['removed'])){
						   $newf = count($fdet['removed']); 
						   $cntf = 1;
						   foreach($fdet['removed'] as $az => $ar){
							   if($cntf < $newf){
								   $setfiled .= $ar.",";
							   }else{
								   $setfiled .= $ar;
							   }
							   $cntf++;
						   }
						}
						$loanlog['event'] = "Removed ".$setfiled." from ".$fdet['folder'];
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
				}
			}else if($data['eventid'] == 9){
				$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				$loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];
				$loanlog['event']    = "Removed ".$data['data']['username']." from loan users";
				$loanlog['time']  = date("m/d/Y | h:i a",$data['createdate']);
					if($data['user']['imgkey']){
						if($data['user']['userid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
						}else if($data['user']['adminid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
						}
					}else{
						$loanlog['img']   = base_url()."images/defaultimg.jpg";
					}
			}else if($data['eventid'] == 10){
				if(!empty($data['data'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];					
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}					
							$docdets = $data['data'];						
				    $edited .= "\nFolder : ".$docdets['mainfolder']."/".$docdets['foldername']."\nFiles : ".$docdets['docs'];																											
					$loanlog['event']	= "Removed ".$edited;	
				}					
			}else if($data['eventid'] == 11){
				$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				$loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];
				$loanlog['event']    = "Verified ".$data['data']['foldername']." in ".$data['data']['mainfolder'];
				$loanlog['time']  = date("m/d/Y | h:i a",$data['createdate']);
					if($data['user']['imgkey']){
						if($data['user']['userid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
						}else if($data['user']['adminid'] !=""){
							$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
						}
					}else{
						$loanlog['img']   = base_url()."images/defaultimg.jpg";
					}
			}else if($data['eventid'] == 12){
				if(!empty($data['data'])){
						$fdet = $data['data'];						
					    $loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];	
				        $loanlog['usertype'] = $data['user']['usertype'];						
						$loanlog['event'] = "Removed the folder ".$fdet['newfolder']." from ".$fdet['mainfolder'];
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}
				}
			}else if($data['eventid'] == 13){
				if(!empty($data['data'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];					
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}					
							$docdets = $data['data'];						
				    $edited .= "Approved : ".$docdets['mainfolder']."/".$docdets['foldername']."\nFile : ".$docdets['docname'];	
					$loanlog['event']	= $edited;	
				}					
			}else if($data['eventid'] == 14){
				if(!empty($data['data'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];					
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}					
							$docdets = $data['data'];						
				    $edited .= "Rejected : ".$docdets['mainfolder']."/".$docdets['foldername']."\nFile : ".$docdets['docname'];	
					$loanlog['event']	= $edited;	
				}					
			}else if($data['eventid'] == 15){
				if(!empty($data['data'])){				
						$edited ="";
						$loanlog['username'] = $data['user']['firstname']." ".$data['user']['lastname'];
				        $loanlog['usertype'] = $usertype[$data['user']['usertypeid']]['usertype'];					
						$loanlog['time'] = date("m/d/Y | h:i a",$data['createdate']);
							if($data['user']['imgkey']){
								if($data['user']['userid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['userid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/profImgs/crop/'));						
								}else if($data['user']['adminid'] !=""){
									$loanlog['img']   = base_url($this->getMyPath($data['user']['adminid'], $data['user']['imgkey'], $data['user']['imgext'],'assets/admin/profImgs/crop/'));	
								}
							}else{
								$loanlog['img']   = base_url()."images/defaultimg.jpg";
							}					
							$docdets = $data['data'];						
				    $edited .= "Folder status changed from Verified to Not verified . \n".$docdets['mainfolder']."/".$docdets['foldername'];	
					$loanlog['event']	= $edited;	
				}					
			}
		}						
		return $loanlog;			   
	}
	
	public function decodeloan_logs($data){	
/* 		   print "<pre>";
		   print_r($data);
		   print "</pre>";	
		   exit;	 */
		   $activitylog = array();
		   if(!empty($data)){  
			foreach($data as $ck => $cv){
				if($cv['eventid']){
					$logdets = $this->getactivitylogtext($cv);
					/* print "<pre>";
					print_r ($logdets);
					print "</pre>"; */
					if(!empty($logdets)){
						$activitylog[] = $logdets;
					}		
				}							
			}			
		   }
/* 		   print "<pre>";
		   print_r($activitylog);
		   print "</pre>";	
		   exit; */
		return 	$activitylog;
	}

	
	public function decodeloan_logs_Api($data){		
		   $activitylog = array();
		   if(!empty($data)){  
			foreach($data as $ck => $cv){
				if($cv['eventid']){
					$logdets = $this->getactivitylogtext_Api($cv);
					if(!empty($logdets)){
						$activitylog[] = $logdets;
					}		
				}							
			}			
		   }
		return 	$activitylog;
	}	
	
      public function getuserloanids() {
          $sql   = 'SELECT loanid FROM ' . $this->db->dbprefix('loanuser') . ' WHERE userid = ?';
          $query = $this->db->query($sql, array(
              $this->session->userdata('userId')
          ));
          // print $this->db->last_query();
          return $query->result_array();
      }

	public function checkifuserloan($loanids){
		$result      = false;
		$attachedids = array();
		$loanarray = $this->getuserloanids();
		if(!empty($loanarray)){
		foreach($loanarray as $ck => $cv){
			$attachedids[] = $cv['loanid'];
		}
		}
		if(!empty($loanids) and !empty($attachedids)){
			$result = !empty(array_intersect($attachedids, $loanids));
		}		
		return $result;
	}

  }
?>