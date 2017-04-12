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
<link href="<?php echo base_url(); ?>css/jquery-ui.css" rel="stylesheet" type="text/css" />
<!-- Latest compiled and minified JavaScript -->
<script src="<?php echo base_url(); ?>js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/moment-with-locales.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/datetimepicker.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	sideHeight();
});
$(window).resize(function(){
	sideHeight();
});
function sideHeight(){
		var windowHit = $(window).height();
		var heightDiv = $('.divheight').height();
		var midHeight = $('.res-middle-side').height();

		$('.recent-activity').css('min-height',midHeight);
		//console.log(windowHit);
		if(windowHit > heightDiv){
			$('.divheight').css('height',windowHit-78);
		}
		else{
			$('.divheight').css('height',windowHit);
		}
		//
		$('.icon-menu-open').click(function(){
			$('body').css('overflow','hidden');
			$('.menu-mobile').slideDown();
			$(this).hide();
			$('.icon-menu-close').show();
		});
		$('.icon-menu-close').click(function(){
			$('body').css('overflow','visible');
			$('.menu-mobile').slideUp();
			$(this).hide();
			$('.icon-menu-open').show();
		});
	}
</script>
<script>
$(document).ready(function(){
	rightFunction();
});
$(window).resize(function(){
	rightFunction();
});
function rightFunction(){
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
}
</script>
<script>
	$(document).ready(function(){
		sideAdjust();
	});
	$(window).resize(function(){
		sideAdjust();
	});
	function sideAdjust(){
		var headerHeight = $('.main-header').height();
		if ($(window).width() < 1200) {
   		$('.res-right-side').css('top',headerHeight);
		}
		else {
		}
	}
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
        <div class="col-sm-3 col-md-2 col-lg-3"><a href="<?php echo base_url('dashboard'); ?>" class="logo-main"><img src="<?php echo base_url(); ?>images/logo.png" /></a></div>
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
    	<div class="col-xs-6"><a href="<?php echo base_url(); ?>" class="logo-main"><img src="<?php echo base_url(); ?>images/logo.png" /></a></div>
      <div class="col-xs-6">
				<div class="icon-menu"><i class="icon-menu-open"></i> <i class="icon-menu-close" style="display:none;"></i></div>
				<a href="<?php echo base_url('notification'); ?>" class="mob_notify mail-mobile"><span><?php echo $headerdata['notifycount']; ?></span></a>
				<a href="<?php echo base_url('messages'); ?>" class="mob_notify bell-mobile"><span><?php echo $headerdata['messagecount']; ?></span></a>
			</div>
    </header>
    <section class="menu-mobile pattern-tools" style="display:none;">
    	<div class="profile-img-sm radius50 FN m0auto"><img src="<?php echo $this->session->userdata('userImg'); ?>" /></div>
        <h2><?php echo $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname'); ?> </h2>
        <h3><?php echo ( $this->session->userdata('userAddress') != '' ) ? $this->session->userdata('userAddress') : '--'; ?></h3>
				<div class="col-xs-12 mt5 mb5 tc">
					<a class="mobbtn" href="<?php echo base_url('myprofile'); ?>">My Profile</a>
				<?php /* ?>	<a class="mobbtn" href="">Settings</a> <?php */ ?>
					<a class="mobbtn" href="<?php echo base_url('logout') ?>">Logout</a>
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


    <script>

function openDialog(title,description,url){
  $( "#dialog" ).attr('title',title);
  $( "#dialog p" ).html(description);

  $( function() {
      $( "#dialog" ).dialog({
      	height: 150,
      	width: 500,
      	modal: true,
      	open : function(){
      		$('.ui-widget-overlay').bind('click',function(){
                jQuery('#dialog').dialog('close');
            })
      	},
      	 close: function( event, ui ) {
      	 	if(url){
      	 		window.location.href = url;
      	 	}

      	 }
      });
      $( "#dialog" ).dialog( "open" );
    } );


}
$(document).ready(function(){
//openDialog('test title','test description');
});

  </script>

<div id="dialog" title="Basic dialog">
  <p></p>
</div>

   <!-- <div class="login-left">
        <a href="#" class="logo-login"><img src="images/logo.png" /></a>
        <a href="#" class="btn-login-play">How we work?</a>
    </div>-->
