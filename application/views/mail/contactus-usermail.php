<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mail Template | Contact Us</title>
</head>
<body style="font-family:Arial, \'Helvetica\', sans-serif; padding:0; margin:0; background-color:#333333;">
<table width="700" border="0" cellpadding="0" cellspacing="0" bgcolor="#333" style="margin:0 auto;">
 <tr>
 	<td colspan="3" style="width:700px;height:25px;"></td>
 </tr>
 <tr>
  	<td width="20px" style="border-bottom:1px solid #e4e2e2;"></td>

    <td style="border-left:#e4e2e2 1px solid;border-right:#e4e2e2 1px solid; background:#fff; border-bottom:1px solid #e4e2e2;">
     <table cellspacing="0" cellpadding="0" border="0">
        <tr>
        <td style="width:477px;height:125px; padding-left:35px;"><img src="<?php echo base_url('img/logo.png') ?>" /></td>
        <td style="width:183px;height:125px; font-size:25px; font-weight:bold; color:#f79724;" >Contact Us</td>
        </tr>
     </table>
    </td>

    <td width="20px" style="border-bottom:1px solid #e4e2e2;"></td>


 </tr>

 <tr>
  	<td style="width:20px"></td>
   <td style="width:660px; height:110px; background:#ffffff; border-left:#e4e2e2 1px solid; border-right:#e4e2e2 1px solid;  padding:20px 15px 10px;">
   <p style="font-size:15px;color:#747373;padding:10px 20px;">Hello <span style="color:#474747;"> <b><?php echo $firstname." ".$lastname; ?>,</b></span></p>

   <p style="line-height:24px;font-size:15px;color:#747373; padding:5px 20px 0px 20px;">
   <b>Your inquiry has been submitted to efynch.com. Our staff will contact you as soon as possible. Please see the inquiry details below</b>. <br />
  </p>
   </td>
   <td style="width:20px"></td>
 </tr>

 <tr>
 	<td style="width:20px"></td>
    <td style="border-top:#e4e2e2 1px solid; border-left:#e4e2e2 1px solid; border-right:#e4e2e2 1px solid;" >
    <table style="background:#f8f7f7; width:100%; height:45px;">
      <tr>
      	<td style="height:45px; width:158px; padding-left:35px;"> <p style="color:#5d5d5d; font-size:14px;"><b>Inquiry</b></p></td>
        <td style="height:45px; width:502px; padding-left:20px;"> <p style="color:#5d5d5d; font-size:14px;"><b><?php echo nl2br(stripslashes($comments)); ?> </b></p></td>
      </tr>
    </table>
    </td>
    <td style="width:20px"></td>
 </tr>

 <tr>
 	<td style="width:20px"></td>
    <td style="border:#e4e2e2 1px solid;">
    <table style="background:#f8f7f7; width:100%; height:45px;">
      <tr>
      	<td style="height:45px; width:158px; padding-left:35px;"> <p style="color:#5d5d5d; font-size:14px;"><b>Email</b></p></td>
        <td style="height:45px; width:502px; padding-left:20px;"> <p style="color:#5d5d5d; font-size:14px;"><b><?php echo $email; ?></b></p></td>
      </tr>
    </table>
    </td>
    <td style="width:20px"></td>
 </tr>

 <tr>
 	<td style="width:20px"></td>
    <td style="border:#e4e2e2 1px solid;">
    <table style="background:#f8f7f7; width:100%; height:45px;">
      <tr>
      	<td style="height:45px; width:158px; padding-left:35px;"> <p style="color:#5d5d5d; font-size:14px;"><b>Phone</b></p></td>
        <td style="height:45px; width:502px; padding-left:20px;"> <p style="color:#5d5d5d; font-size:14px;"><b><?php echo $phone; ?> </b></p></td>
      </tr>
    </table>
    </td>
    <td style="width:20px"></td>
 </tr>



 <tr>
     <td style="width:20px"></td>
    	 <td style=" height:80px; width:100%; background:#ffffff; border-left:#e4e2e2 1px solid; border-right:#e4e2e2 1px solid; padding:5px 15px 0px;">
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
 	<td colspan="3" style="width:700px;height:25px;"></td>
 </tr>

</table>

</body>
</html>
