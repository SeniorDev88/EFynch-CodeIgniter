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
<?php /* ?>
<link href="<?php echo base_url(); ?>css/datetimepicker.css" rel="stylesheet" type="text/css" />
<?php */ ?>
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

function submitPayment(){
	if( $("#checkout").valid() ){
		$("#checkout").submit();
	}
}
</script>

</head>
<body>


<div class="container-fluid">
    <header class="main-header shadow-bottom hidden-xs">
        <div class="col-sm-3 col-md-2 col-lg-3"><a class="logo-main" href="#"><img src="http://demo.icwares.com/clients/dev/efynch/599/vx/images/logo.png"></a></div>
        <div class="col-sm-7 col-md-7 col-lg-6 p0">
        	<nav class="main-nav">
            	<ul>
                	<li><a class="active" href="<?php echo base_url('dashboard') ?>">Dashboard</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Advice</a></li>
                    <li><a href="#">Forum</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </div>
        <div class="col-sm-2 col-md-3 col-lg-3 pr0-sm">
        	<a class="icon-notify icon-notify-alert" href="#"><span>0</span></a>
        	<a class="icon-notify icon-notify-message" href="#"><span>0</span></a>
        </div>
    </header>
		<header class="visible-xs main-header shadow-bottom">
    	<div class="col-xs-6"><a class="logo-main" href="#"><img src="http://demo.icwares.com/clients/dev/efynch/599/vx/images/logo.png"></a></div>
      <div class="col-xs-6">
				<div class="icon-menu"><i class="icon-menu-open"></i> <i style="display:none;" class="icon-menu-close"></i></div>
				<a class="mob_notify mail-mobile" href="#"><span>0</span></a>
				<a class="mob_notify bell-mobile" href="#"><span>0</span></a>
			</div>
    </header>
    <section style="display:none;" class="menu-mobile pattern-tools">
    	<div class="profile-img-sm radius50 FN m0auto"><img src="http://demo.icwares.com/clients/dev/efynch/599/vx/images/defaultimg.jpg"></div>
        <h2><?php echo $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname'); ?>  </h2>
        <h3><?php echo ( $this->session->userdata('userAddress') != '' ) ? $this->session->userdata('userAddress') : '--'; ?></h3>
				<div class="col-xs-12 mt5 mb5 tc">
					<a href="" class="mobbtn">My Profile</a>
									<a href="<?php echo base_url('logout') ?>" class="mobbtn">Logout</a>
				</div>
				<div class="clear"></div>
        <nav class="main-nav">
        <ul>
            <li><a class="active" href="<?php echo base_url('dashboard') ?>">Dashboard</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Advice</a></li>
            <li><a href="#">Forum</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
        </nav>
    </section>
</div>
<div class="wrapper">.

<section class="left-side pattern-tools divheight hidden-xs" style="height: 982px;">
    	<div class="left-profile-details">
        	<div class="profile-img left-fix-img radius50"><img src="http://demo.icwares.com/clients/dev/efynch/599/vx/images/defaultimg.jpg"></div>
        	<h2><?php echo $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname'); ?></h2>
        	<p class="icons-lpd email"><?php echo $this->session->userdata('userEmail'); ?></p>
            <p class="icons-lpd address"><?php echo ( $this->session->userdata('userAddress') != '' ) ? $this->session->userdata('userAddress') : '--'; ?></p>
                       
            <div class="clear line-1px-ash mt20"></div>
            <a class="btn-left btn-left-profile" href="http://demo.icwares.com/clients/dev/efynch/599/vx/myprofile">My Profile</a>
                        <a class="btn-left btn-left-logout" href="http://demo.icwares.com/clients/dev/efynch/599/vx/logout">Log Out</a>
        </div>
    	<div class="ftr"><p>&copy; 2016 Efynch, All Right Reserved</p><a href="#"></a></div>
    </section>
<section class="right-side divheight">
	<div class="container-right">
		<div class="col-md-8 center-block FN pl0 pr0">
			<div class="col-xs-12"><h1>Payment</h1></div>
			<?php if($error){ ?><div class="error">Your transaction failed</div> <?php } ?>

				<form name="checkout" id="checkout" method="post">

					<div class="col-xs-12 clearfix p0">
		        		<div class="col-sm-6">
		        			<input class="input100-login radius30p" data-braintree-name="number" name="creditcard" value="" placeholder="Creditcard" />
		        		</div>
		                <div class="col-sm-6 ">
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
                            <?php /* ?>
		                	<input class="input100-login radius30p datepickmonth"  data-braintree-name="expiration_month" name="expiration_month" readonly  name="expiration_month"  type="text" placeholder="Expiration Month" />
                            <?php */ ?>
		                </div>
		            </div>
		            <div class="col-xs-12 clearfix p0">
		                <div class="col-sm-6 ">
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
		                	<?php /* ?>
                            <input class="input100-login radius30p datepickyear"  data-braintree-name="expiration_year" name="expiration_year" readonly  name="expiration_year"  type="text" placeholder="Expiration Year" />
                            <?php */ ?>
		                </div>
		                <div class="col-sm-6 ">
		                	<input class="input100-login radius30p" data-braintree-name="cvv" name="cvv" value="" placeholder="CVV" />
		                </div>
		            </div>

				<input type="hidden" name="act" value="1">
				<div class="col-xs-12 clearfix p0">
            	<div class="col-sm-12 col-md-8 center-block FN">
            		<div class="col-sm-6 FN m0auto mt30"><input class="submit100-login radius30p" type="submit" value="Submit" /></div>
                </div>
        	</div>
				</form>
                <div class="clear"></div>

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
