<?php


            $message = "John updated the file cabinet";
          $deviceToken = "30a576894b6b987fdcc57072de1563802ef758bc9b39437d19db97db9ff18c1a";       
              // My private key's passphrase here:
              $passphrase = 'icwares';       
              // My alert message here:        
              //badge
              $badge = 1;        
          $ctx = stream_context_create();
           // stream_context_set_option($ctx, 'ssl', 'local_cert', 'certs/EFDCertificates-1.pem');
          //stream_context_set_option($ctx, 'ssl', 'local_cert', 'certs/efdevcertificate.pem');
         //live 
           //  stream_context_set_option($ctx, 'ssl', 'local_cert', 'certs/EFSCertificates-2.pem');
          stream_context_set_option($ctx, 'ssl', 'local_cert', 'certs/efdiscertificate.pem');
            //
          stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);           
          // Open a connection to the APNS server
           /*$fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); */
           $fp = stream_socket_client(
            'ssl://gateway.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
                     
          if (!$fp)
          exit("Failed to connect: $err $errstr" . PHP_EOL);
          
          //echo 'Connected to APNS' . PHP_EOL;
          
          // Create the payload body
          $body['aps'] = array(
            'alert' => $message,
            'badge' => $badge,
            'sound' => 'default'
          );  
                 
          // Encode the payload as JSON
          $payload = json_encode($body);
          
          // Build the binary notification
          $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
          
          // Send it to the server
          $result = fwrite($fp, $msg, strlen($msg));
          
   if (!$result)
            echo 'Error, notification not sent' . PHP_EOL;
          else
            echo 'notification sent!' . PHP_EOL; 

          //print "<pre>";  print_r($body); echo " AM1 "; print_r($msg);echo " AM2 "; print_r($result);  echo " deviceToken "; print $deviceToken;  echo " payload "; print $payload;print "</pre>"; exit;
          //exit;  
            
          
          // Close the connection to the server
          fclose($fp);


?>