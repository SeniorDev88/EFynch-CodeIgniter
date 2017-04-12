<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$urlval = "https://efynch.com/";
?><!DOCTYPE html>
<html lang="en">
<head>
<link href="<?php echo $urlval ;?>css/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $urlval ;?>css/bootstrap.css" rel="stylesheet" type="text/css" />
<meta charset="utf-8">
<title>404 Page Not Found</title>
<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background-color: #eaeaea;
	margin: 0;
	padding:0;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}
.menubar {
    background: #ffffff none repeat scroll 0 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    display: none;
    height: 80px;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 999;
}
.logosection {
    line-height: 80px;
}
.logoimg {
    max-width: 145px;
    width: 100%;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}
.menubar{display:block;}
h2{color:#666; text-align:center; font-size:35px;}
h2 span{color:#e54040; font-size:35px;}
.boy-404{
	margin:40px auto 20px;
	text-align:center;
}
.bottom-line{
	width:100%;
	height:1px;
	background: rgba(214,214,214,1);
	background: -moz-linear-gradient(left, rgba(214,214,214,1) 0%, rgba(143,143,143,0.98) 46%, rgba(143,143,143,0.98) 55%, rgba(214,214,214,0.96) 100%);
	background: -webkit-gradient(left top, right top, color-stop(0%, rgba(214,214,214,1)), color-stop(46%, rgba(143,143,143,0.98)), color-stop(55%, rgba(143,143,143,0.98)), color-stop(100%, rgba(214,214,214,0.96)));
	background: -webkit-linear-gradient(left, rgba(214,214,214,1) 0%, rgba(143,143,143,0.98) 46%, rgba(143,143,143,0.98) 55%, rgba(214,214,214,0.96) 100%);
	background: -o-linear-gradient(left, rgba(214,214,214,1) 0%, rgba(143,143,143,0.98) 46%, rgba(143,143,143,0.98) 55%, rgba(214,214,214,0.96) 100%);
	background: -ms-linear-gradient(left, rgba(214,214,214,1) 0%, rgba(143,143,143,0.98) 46%, rgba(143,143,143,0.98) 55%, rgba(214,214,214,0.96) 100%);
	background: linear-gradient(to right, rgba(214,214,214,1) 0%, rgba(143,143,143,0.98) 46%, rgba(143,143,143,0.98) 55%, rgba(214,214,214,0.96) 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d6d6d6', endColorstr='#d6d6d6', GradientType=1 );
}
.btn-backtohome {
    background: #0f9467;
		border:1px solid #0f9467;
    border-radius: 5px;
    color: #fff;
    display: block;
    font-size: 15px;
    height: 40px;
    line-height: 40px;
    margin: 30px auto 20px;
    padding: 0 20px;
    text-align: center;
    width: 200px;
}
.btn-backtohome:hover{
	background:unset;
	color:#0f9467;
}
.developed-txt a{
	color:#333;
}
.logosection {
    line-height: 80px;
    text-align: center;
    width: 100%;
}
.logosection > a {
    display: inline-block;
}
</style>
</head>
<body>
	<!--<div id="container">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
	</div>-->
  <div class="container-fluid menubar p0" > 
    <div class="container"> 
        <div class="row">
            <div class="col-xs-12">
              <div class="logosection">
                  <a href="<?php echo $urlval ;?>"><img src="<?php echo $urlval ;?>images/logo.png" class="logoimg"></a>
              </div>
            </div>
        </div>
    </div>
  </div>
  <div class="container-fluid mt80" > 
    <div class="container"> 
        <div class="row">
            <div class="col-xs-12 text-center">
            	<div class="boy-404">
              	<img src="<?php echo $urlval ;?>images/boy-404.png" />
                <div class="bottom-line"></div>
              </div>
              <h2><span>Ohh.....</span> You Requested the page that is no longer There.</h2>
              <a href="<?php echo $urlval ;?>" class="btn-backtohome">Back To Home</a>
              <p class="copyrt tc m0">&copy; <? echo date('Y'); ?> efnch.com, All Rights Reserved.</p>
              <p class="developed-txt tc mb30">Developed by <a href="http://www.consult-ic.com/" target="_blank">InnovativeConsultants</p>
            </div>
        </div>
    </div>
  </div>
</body>
</html>