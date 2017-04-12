<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
<title>Efynch</title>
<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="all-ie-only.css" />
<![endif]-->
<!--[if !IE]>
	<link rel="stylesheet" type="text/css" href="not-ie.css" />
 <!--<![endif]-->

<!-- Bootstrap Version 3.3.6 -->
<link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>css/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/datetimepicker.css" rel="stylesheet" type="text/css" />
<!-- Latest compiled and minified JavaScript -->
<script src="<?php echo base_url(); ?>js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/bootstrap.min.js" type="text/javascript"></script>
<?php /* ?>
<script src="<?php echo base_url(); ?>js/moment-with-locales.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/datetimepicker.js" type="text/javascript"></script> <?php */ ?>
<script>
$(document).ready(function(){
	//$(".datepickyear").datetimepicker({ format: 'YYYY', ignoreReadonly: true});
	//$(".datepickmonth").datetimepicker({ format: 'MM', ignoreReadonly: true});

});
</script>

</head>
<body>
<div class="wrapper">
<section class="right-side divheight">
	<div class="container-right">
		<div class="col-sm-12 col-md-9 pl0 pr0">
			<div class="col-xs-12"><h1>Payment</h1></div>
			<?php if($error){ ?><div class="error">Your transaction failed</div> <?php } ?>

				<form name="checkout" id="checkout" method="post">

					<div class="col-xs-12 clearfix p0">
		        		<div class="col-sm-6 col-md-4">
		        			<input class="input100-login radius30p" data-braintree-name="number" name="creditcard" value="" placeholder="Creditcard" />
		        		</div>

		                <div class="col-sm-6 col-md-4">
		                	<div class="select-login">
		                       <select data-braintree-name="expiration_month" name="expiration_month" >
		                           <?php 
		                           	$monthArray = array(
						                    "01" => "January", "02" => "February", "03" => "March", "04" => "April",
						                    "05" => "May", "06" => "June", "07" => "July", "08" => "August",
						                    "09" => "September", "10" => "October", "11" => "November", "12" => "December",
						                );
		                           	foreach ($monthArray as $mon=>$month) {
		                            ?>
		                           <option value="<?php echo $mon; ?>"><?php echo $month; ?></option>
		                           
		                           <?php } ?>
		                       </select>
		                   </div>
		                	<?php /* ?><input class="input100-login radius30p datepickmonth"  data-braintree-name="expiration_month" name="expiration_month" readonly  name="expiration_month"  type="text" placeholder="Expiration Month" /><?php */ ?>
		                </div>
		            </div>
		            <div class="col-xs-12 clearfix p0">
		                <div class="col-sm-6 col-md-4">
		                	<div class="select-login">
		                	<select data-braintree-name="expiration_year" name="expiration_year" >
		                           <?php 
		                           	for($i = date('Y'); $i <= date('Y') + 50; $i++){
		                            ?>
		                           <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
		                           
		                           <?php } ?>
		                       </select>
		                	<?php /* ?><input class="input100-login radius30p datepickyear"  data-braintree-name="expiration_year" name="expiration_year" readonly  name="expiration_year"  type="text" placeholder="Expiration Year" /> <?php */ ?>
		                </div>
		                 </div>
		                <div class="col-sm-6 col-md-4">
		                	<input class="input100-login radius30p" data-braintree-name="cvv" name="cvv" value="" placeholder="CVV" />
		                </div>
		            </div>

				<input type="hidden" name="act" value="1">
				<div class="col-xs-12 clearfix p0">
            	<div class="col-sm-12 col-md-8">
            		<div class="col-sm-6 FN m0auto mt30"><input class="submit100-login radius30p" type="submit" value="Submit" /></div>
                </div>
        	</div>
				</form>

		</div>
	</div>
</section>
</div>
 <script>
    $.getScript( "https://js.braintreegateway.com/v2/braintree.js", function() {
        var clientToken = "<?php echo $ctoken; ?>";
        braintree.setup(clientToken, "custom", {
            id: "checkout"

        });
    });

    </script>
</body>
</html>
