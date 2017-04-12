<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<meta name="description" content="<?php echo $description; ?>" />

<link href="<?php echo base_url(); ?>css/frontend/bootstrap.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>css/frontend/reset.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>js/jquery-1.11.1.min.js"></script>
<script src="<?php echo base_url(); ?>js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $(".burger-menu").click(function(event){
      $('.burger-menu').toggleClass('menuclose');
      $('.mobmenu').slideToggle();
      event.stopPropagation();
    });
    if($(window).width() <= 767) {
      $('html').click(function() {
        $('.mobmenu').slideUp();
        $('.burger-menu').removeClass('menuclose');
      });
    }

    $("input, textarea").focusin(function(){
        $(this).parent().siblings('.box-title').css("color", "#f79724");
        $(this).parent().addClass('active');
    });
    $("input, textarea").focusout(function(){
        $(this).parent().siblings('.box-title').css("color", "#d0d0d0");
        $(this).parent().removeClass('active');
    });

    var homhyt = $('#homeowner').height();
    var conthyt = $('#contractor').height();
    $('.homeownerfixed').css("height",homhyt);
    $('.contractorfixed').css("height",conthyt);

    videoPopupClick();
    function videoPopupClick(){
    	$('.video-thumb').click(function(){
    		$('.videopopup').modal('show')
    		var videoframe = $(this).attr('data-video');
    		var videoname = $(this).attr('data-name');
            $("#myModalLabel").html(videoname);
    		$("#videowrapper").html(videoframe);
    	});
    	$('.videopopup').on('hidden.bs.modal', function (e) {
    		$("#videowrapper").html('');
    	})
    }

//Smoothscroll script
  $(function() {
  var headhyt = $('header').height();
  $('a[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top - headhyt
        }, 1000);
        return false;
      }
    }
  });

});

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
  <div class="modal fade bs-example-modal-lg videopopup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel"></h4>
	  </div>
	  <div class="modal-body">
			<div id="videowrapper"></div>
	  </div>
	</div>
	</div>
	</div>
<?php //print "<pre>"; print_r($_SERVER); print "</pre>"; ?>
  <header>
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-3">
          <a class="logo" href="<?php echo base_url(); ?>">
            <img src="<?php echo base_url(); ?>img/logo.png"/>
          </a>
          <a class="burger-menu"><i></i></a>
        </div>
        <div class="col-xs-12 col-sm-9 mobmenu p0-xs">
          <ul class="menu">
            <li><a class="<?php if($page == 'index'){ ?>menulist<?php } ?>" href="<?php echo base_url(); ?>">Home</a></li>
            <li><a class="<?php if($page  == 'services'){ ?>menulist<?php } ?>" href="<?php echo base_url('info'); ?>">Info</a></li>
            <li><a class="<?php if($page ==  'tips'){ ?>menulist<?php } ?>" href="<?php echo base_url('community'); ?>">Community</a></li>
            <li><a class="<?php if($page ==  'contact'){ ?>menulist<?php } ?>" href="<?php echo base_url('contactus'); ?>">Contact Us</a></li>
            <?php if(! $this->session->userdata('userKey')){
              ?>
               <li><a class="menu_login" href="<?php echo base_url('app') ?>" >Login</a></li>
              <?php 
            }else{
              ?>
              <li><a class="menu_login" href="<?php echo base_url('dashboard') ?>" >Dashboard</a></li>
            <?php
            } ?>
           
          </ul>
      </div>
      <div>
    </div>
  </header>
