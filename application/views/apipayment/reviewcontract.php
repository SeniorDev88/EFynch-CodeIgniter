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


</head>
<body>


<div class="container-fluid">
    <header class="main-header shadow-bottom hidden-xs">
        <div class="col-sm-3 col-md-2 col-lg-3"><a class="logo-main" href="#"><img src="<?php echo base_url(); ?>images/logo.png"></a></div>
        <div class="col-sm-7 col-md-7 col-lg-6 p0">
        	<nav class="main-nav">
            	<ul>
                	<li><a href="<?php echo base_url('dashboard'); ?>" class="active">Dashboard</a></li>
                    <li><a href="<?php echo base_url('info'); ?>">Info</a></li>
                    <li><a href="<?php echo base_url('community'); ?>">Community</a></li>
                    <li><a href="<?php echo base_url('contactus'); ?>">Contact</a></li>
                </ul>
            </nav>
        </div>
        <div class="col-sm-2 col-md-3 col-lg-3 pr0-sm">
        	<a href="<?php echo base_url('notification'); ?>" class="icon-notify icon-notify-alert"><span><?php echo $headerdata['notifycount']; ?></span></a>
            <a href="<?php echo base_url('messages'); ?>" class="icon-notify icon-notify-message"><span><?php echo $headerdata['messagecount']; ?></span></a>
        </div>
    </header>
		<header class="visible-xs main-header shadow-bottom">
    	<div class="col-xs-6"><a class="logo-main" href="#"><img src="<?php echo base_url(); ?>images/logo.png"></a></div>
      <div class="col-xs-6">
				<div class="icon-menu"><i class="icon-menu-open"></i> <i style="display:none;" class="icon-menu-close"></i></div>
				<a href="<?php echo base_url('notification'); ?>" class="mob_notify mail-mobile"><span><?php echo $headerdata['notifycount']; ?></span></a>
                <a href="<?php echo base_url('messages'); ?>" class="mob_notify bell-mobile"><span><?php echo $headerdata['messagecount']; ?></span></a>
			</div>
    </header>
    <section style="display:none;" class="menu-mobile pattern-tools">
    	<div class="profile-img-sm radius50 FN m0auto"><img src="<?php echo base_url(); ?>images/defaultimg.jpg"></div>
        <h2><?php echo $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname'); ?>  </h2>
        <h3><?php echo ( $this->session->userdata('userAddress') != '' ) ? $this->session->userdata('userAddress') : '--'; ?></h3>
				<div class="col-xs-12 mt5 mb5 tc">
					<a href="" class="mobbtn">My Profile</a>
									<a href="<?php echo base_url('logout') ?>" class="mobbtn">Logout</a>
				</div>
				<div class="clear"></div>
        <nav class="main-nav">
        <ul>
             <li><a href="<?php echo base_url('dashboard'); ?>" class="active">Dashboard</a></li>
            <li><a href="<?php echo base_url('info'); ?>">Info</a></li>
            <li><a href="<?php echo base_url('community'); ?>">Community</a></li>
            <li><a href="<?php echo base_url('contactus'); ?>">Contact</a></li>
        </ul>
        </nav>
    </section>
</div>
<div class="wrapper">

<section class="left-side pattern-tools divheight hidden-xs" style="height: 982px;">
    	<div class="left-profile-details">
        	<div class="profile-img left-fix-img radius50"><img src="<?php echo base_url(); ?>images/defaultimg.jpg"></div>
        	<h2><?php echo $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname'); ?></h2>
        	<p class="icons-lpd email"><?php echo $this->session->userdata('userEmail'); ?></p>
            <p class="icons-lpd address"><?php echo ( $this->session->userdata('userAddress') != '' ) ? $this->session->userdata('userAddress') : '--'; ?></p>
                       
            <div class="clear line-1px-ash mt20"></div>
            <a class="btn-left btn-left-profile" href="<?php echo base_url('myprofile'); ?>">My Profile</a>
                        <a class="btn-left btn-left-logout" href="<?php echo base_url('logout'); ?>">Log Out</a>
        </div>
    	<div class="ftr"><p>&copy; 2016 Efynch, All Right Reserved</p><a href="#"></a></div>
    </section>


<section class="right-side divheight">
	<div class="container-right">
		<div class="col-sm-12 pl0 pr0">
			<div class="col-xs-12 tc"><h1>Contract Details</h1></div>
			<div class="col-xs-12 col-md-8 m0auto clearfix">
				<div class="col-md-12 p20">
					<h3 class="tab-head">Job Info</h3>
					<div class="tabpad">
						<div class="tabnametab">Job Name</div><div class="tabnametab"> : <?php echo $jobDets['jobname']; ?></div>
						<div class="tabnametab">Amount</div> <div class="tabnametab">: $<?php echo number_format($userDets['bidDets']['bidamount'],2); ?></div>
					</div>
				</div>
				<div class="col-md-12 p20">
					<h3 class="tab-head">Contractor Details</h3>
					<div class="tabpad">
						<div class="tabnametab">Name</div><div class="tabnametab"> : <?php echo $userDets['firstname']." ".$userDets['lastname']; ?></div>
						<div class="tabnametab">Email</div><div class="tabnametab"> : <?php echo $userDets['email']; ?></div>
						<div class="tabnametab">Phone</div><div class="tabnametab"> : <?php echo ( $userDets['phone'] != '' ) ? $userDets['phone'] : '--'; ?></div>
						<div class="tabnametab">Address</div><div class="tabnametab"> : <?php echo ( $userDets['address'] != '' ) ? $userDets['address'] : '--'; ?><br/><?php echo $userDets['city'].", ".$userDets['state']." ".$userDets['zip']; ?></div>
						<div class="tabnametab">Date</div><div class="tabnametab"> : <?php echo date('m/d/Y',strtotime($jobDets['startdate'])); ?></div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="col-sm-4 m0auto">
			<a class="submit100-login radius30p pt15" href="<?php echo base_url('confirmpayment/'.$contractDets['contractkey']); ?>">Continue</a>
		</div>
	</div>
</section>

</div>



</body>
</html>
