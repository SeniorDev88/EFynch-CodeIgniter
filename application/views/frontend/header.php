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
<script src="<?php echo base_url(); ?>js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/moment-with-locales.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/datetimepicker.js" type="text/javascript"></script>
<script>
	$(function(){
		var windowHit = $(window).height();
		var heightDiv = $('.divheight').height();
		//console.log(windowHit);
		if(windowHit > heightDiv){
			$('.divheight').css('height',windowHit-78);
		}
		else{
			$('.divheight').css('height',windowHit);
		}
		//
		$('.icon-menu-open').click(function(){
			$('.menu-mobile').slideDown();
			$(this).hide();
			$('.icon-menu-close').show();
		});
		$('.icon-menu-close').click(function(){
			$('.menu-mobile').slideUp();
			$(this).hide();
			$('.icon-menu-open').show();
		});
	});
</script>
<script>
$(document).ready(function(){

if($(window).width() < 1200){
$('.toright').click(function(){
	$('.res-right-side').animate({ 'width' :300 });
	$(this).hide();
	$('.toleft').show();
});
$('.toleft').click(function(){
	$('.res-right-side').animate({ 'width' :15 });
	$(this).hide();
	$('.toright').show();
});
}
else{
$('.res-right-side').css('width', '25%');
}
});
$(window).resize(function(){
if($(window).width() >= 1200){
$('.res-right-side').css('width', '25%');
}
else{
$('.res-right-side').css('width', '15px');
$('.toleft').hide();
$('.toright').show();
}
});
</script>

<script type="text/javascript">
 document.createElement('header');
 document.createElement('hgroup');
 document.createElement('nav');
 document.createElement('menu');
 document.createElement('section');
 document.createElement('article');
 document.createElement('aside');
 document.createElement('footer');
 document.createElement('figure');
</script>

</head>
<body>
<div class="container-fluid">
    <header class="main-header shadow-bottom hidden-xs">
        <div class="col-sm-3 col-md-2 col-lg-3"><a href="#" class="logo-main"><img src="<?php echo base_url(); ?>images/logo.png" /></a></div>
        <div class="col-sm-7 col-md-7 col-lg-6 p0">
        	<nav class="main-nav">
            	<ul>
                	<li><a href="<?php echo base_url('dashboard'); ?>" class="active">Dashboard</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Advice</a></li>
                    <li><a href="#">Forum</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </div>
        <div class="col-sm-2 col-md-3 col-lg-3 pr0-sm">
        	<a href="#" class="icon-notify icon-notify-alert"><span>33</span></a>
        	<a href="#" class="icon-notify icon-notify-message"><span>133</span></a>
        </div>
    </header>
		<header class="visible-xs main-header shadow-bottom">
    	<div class="col-xs-6"><a href="#" class="logo-main"><img src="images/logo.png" /></a></div>
      <div class="col-xs-6">
				<a href="#" class="mob_notify bell-mobile"><span>33</span></a>
				<a href="#" class="mob_notify mail-mobile"><span>133</span></a>
				<div class="icon-menu"><i class="icon-menu-open"></i> <i class="icon-menu-close" style="display:none;"></i></div>
			</div>
    </header>
    <section class="menu-mobile pattern-tools" style="display:none;">
    	<div class="profile-img-sm radius50 FN m0auto"><img src="images/img-profile.jpg" /></div>
        <h2>Williams John </h2>
        <h3>richard@gmail.com <br /> 123 Church Road  <br /> Suite 500 <br /> Baltimore, MD 21117</h3>
        <nav class="main-nav">
        <ul>
            <li><a href="#" class="active">Dashboard</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Advice</a></li>
            <li><a href="#">Forum</a></li>
            <li><a href="#">Contact</a></li>
						<li><a href="#">My Profile</a></li>
						<li><a href="#">Settings</a></li>
						<li><a href="<?php echo base_url('logout') ?>">Logout</a></li>
        </ul>
        </nav>
    </section>
</div>

   <!-- <div class="login-left">
        <a href="#" class="logo-login"><img src="images/logo.png" /></a>
        <a href="#" class="btn-login-play">How we work?</a>
    </div>-->
