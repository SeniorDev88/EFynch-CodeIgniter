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
<link href="<?php echo base_url('css/bootstrap.css') ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('css/reset.css') ?>" rel="stylesheet" type="text/css" />
<!-- Latest compiled and minified JavaScript -->
<script src="<?php echo base_url('js/jquery-1.11.1.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('js/bootstrap.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('js/jquery.validate.js') ?>" type="text/javascript"></script>


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
<script type="text/javascript">
  function winhytadjust(){
    if($(window).width() >= 768 )
    {
      var minHyt = $(window).height();
      $('.login-left, .login-right').addClass('hyt-adjust');
      var maxHeight = 0;
      $(".hyt-adjust").each(function(){
        if ($(this).innerHeight() > maxHeight)
        {
          maxHeight = $(this).innerHeight();
        }
      });
      console.log(maxHeight);
      $(".hyt-adjust").innerHeight(maxHeight);
      $(".hyt-adjust").css("height",minHyt);
    }
  }
  $(document).ready(function(){
    winhytadjust();
  });
  $(window).resize(function(){
    winhytadjust();
  });

  $(document).ready(function(){
    videoPopupClick();
    function videoPopupClick(){
    	$('.video-frame').click(function(){
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

  });

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
<div class="container-login">
    <div class="login-left">
        <a href="<?php echo base_url('dashboard'); ?>" class="logo-login"><img src="<?php echo base_url('images/logo.png') ?>" /></a>
        <a class="video-frame shadow-all" data-video='<iframe width="100%" height="490" src="https://www.youtube.com/embed/wRACV_IS0BI?autoplay=1" frameborder="0" allowfullscreen></iframe>' data-name="Efynch Homeowner Intro"><img src="<?php echo base_url('images/img-video.jpg') ?>" /> <i></i></a>
    </div>
