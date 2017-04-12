<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mail Template | Registration</title>
</head>
<body style="font-family:Arial, 'Helvetica', sans-serif; padding:0; margin:0; background-color:#333333;">
<table width="700" border="0" cellpadding="0" cellspacing="0" bgcolor="#d2d7d5" style="margin:0 auto;">
 <tr>
 	<td colspan='3' style="width:700px;height:25px;"></td>
 </tr>
 <tr>
  	<td width="20px"></td>

    <td style="border-left:#e4e2e2 1px solid;border-right:#e4e2e2 1px solid;">
     <table cellspacing="0" cellpadding="0" border="0">
         <tr>
        <td style="width:350px;height:125px; padding-left:35px;"><img src="http://demo.icwares.com/clients//dev/efynch_web/frontend_html/img/logo.png" /></td>
        <td valign="center" style="width:350px;height:125px; font-size:25px; font-weight:bold; color:#f79724;" >Registration</td>
        </tr>
     </table>
    </td>

    <td width="20px"></td>


 </tr>

 <tr>
  	<td style="width:20px"></td>
   <td style="width:660px; height:60px; background:#ffffff; border-left:#e4e2e2 1px solid; border-right:#e4e2e2 1px solid; padding:20px 15px 10px;">
   <p style="font-size:15px;color:#747373;padding:10px 20px;">Hello <span style="color:#474747;"> <b><?php echo $firstname." ".$lastname;?>,</b></span></p>

   <p style="line-height:24px;font-size:15px;color:#747373; padding:10px 20px 0px 20px;">
   <b>Welcome To Efynch.</b><br />
   Your account has been successfully created. Your email verification details are provided below. Please keep this email for any future reference.</p>
   <p><b style="padding:10px 20px 0px 20px;">Verification Code </b> : <?php echo $verificationcode; ?></p>
   </td>
   <td style="width:20px"></td>
 </tr>

 <tr>
    <td style="width:20px"></td>
   	<td style=" height:80px; width:660px; background:#ffffff; border-left:#e4e2e2 1px solid; border-right:#e4e2e2 1px solid; padding:0 15px;">
     	<p style="line-height:24px;font-size:14px;color:#747373; padding-left:20px"> Thank you, <br /> <span style="font-size:16px;">Efynch Team</span></p>
     </td>
     <td style="width:20px"></td>
 </tr>

 <tr>
     <td style="width:20px"></td>
    	 <td style=" height:70px; width:660px; background:#ffffff; border:#e4e2e2 1px solid;">
          <table>
              <tr>
                <td style="width:100%; height:70px; padding-left:15px; padding-right:15px;">
                  <p style="line-height:18px;font-size:12px;color:#747373; text-align:center;">Copyright © <? echo date("Y"); ?> EFynch,
All Rights Reserved.</p>
                </td>
              </tr>
          </table>

     </td>
     <td style="width:20px"></td>
 </tr>
 <tr>
 	<td colspan='3' style="width:700px;height:25px;"></td>
 </tr>

</table>

</body>
</html>
