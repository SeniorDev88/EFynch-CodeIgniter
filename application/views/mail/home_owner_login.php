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
        <td valign="center" style="width:350px;height:125px; font-size:25px; font-weight:bold; color:#f79724;" ></td>
        </tr>
     </table>
    </td>

    <td width="20px"></td>


 </tr>

 <tr>
  	<td style="width:20px"></td>
   <td style="width:660px; height:60px; background:#ffffff; border-left:#e4e2e2 1px solid; border-right:#e4e2e2 1px solid; padding:20px 15px 10px;">
   <p style="font-size:15px;color:#747373;padding:10px 20px;">Dear <span style="color:#474747;"> <b><?php echo $firstname." ".$lastname;?>,</b></span></p>

   <p style="line-height:24px;font-size:15px;color:#747373; padding:10px 20px 0px 20px;">
   Welcome to <a href="<?=base_url()?>">EFynch.com</a>. We are here to help you no matter what is needed around your house!</p>

    <p style="line-height:24px;font-size:15px;color:#747373; padding:10px 20px 0px 20px;">When you are on the site, feel free to use the little blue circle (bottom right) to ask any questions. They can be about the site or even general home improvement questions.</p>

    <p style="line-height:24px;font-size:15px;color:#747373; padding:10px 20px 0px 20px;">Our bidding system is very unique because it guarantees some of the best on demand pricing. Don’t forget to utilize the Payment Escrow feature which allows you to pay for all transactions
with a credit card and have our 100% satisfaction guarantee.</p>

    <p style="line-height:24px;font-size:15px;color:#747373; padding:10px 20px 0px 20px;">We are here to help. So think of a project (nothing to big or too small), post it now, and see how easy it can be.</p>
    <p style="line-height:24px;font-size:15px;color:#747373; padding:10px 20px 0px 20px;">Don’t forget to check out our sister site (<a href="http://www.fynchnest.com">www.fynchnest.com</a>) for weekly deals and other tips.</p>
   </td>
   <td style="width:20px"></td>
 </tr>

 <tr>
    <td style="width:20px"></td>
   	<td style=" height:80px; width:660px; background:#ffffff; border-left:#e4e2e2 1px solid; border-right:#e4e2e2 1px solid; padding:0 15px;">
     	<p style="line-height:24px;font-size:14px;color:#747373; padding-left:20px"> Thanks, <br /> <span style="font-size:16px;">The Efynch Team.</span></p>
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
