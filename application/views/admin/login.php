<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Efynch</title>



    <!-- Global stylesheets -->

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">

    <link href="<?php echo base_url()?>assets/admin/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">

    <link href="<?php echo base_url()?>assets/admin/css/minified/bootstrap.min.css" rel="stylesheet" type="text/css">

    <link href="<?php echo base_url()?>assets/admin/css/minified/core.min.css" rel="stylesheet" type="text/css">

    <link href="<?php echo base_url()?>assets/admin/css/minified/components.min.css" rel="stylesheet" type="text/css">

    <link href="<?php echo base_url()?>assets/admin/css/minified/colors.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url()?>assets/admin/css/all.css" rel="stylesheet" type="text/css">

    <!-- /global stylesheets -->



    <!-- Core JS files -->

    <script type="text/javascript" src="<?php echo base_url()?>assets/admin/js/plugins/loaders/pace.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url()?>assets/admin/js/core/libraries/jquery.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url()?>assets/admin/js/core/libraries/bootstrap.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url()?>assets/admin/js/plugins/loaders/blockui.min.js"></script>

    <!-- /core JS files -->





    <!-- Theme JS files -->

    <script type="text/javascript" src="<?php echo base_url()?>assets/admin/js/core/app.js"></script>

    <!-- /theme JS files -->



</head>



<body>

<!-- Page container -->

<div class="page-container login-container">



    <!-- Page content -->

    <div class="page-content">



        <!-- Main content -->

        <div class="content-wrapper">



            <!-- Content area -->

            <div class="content">



                <!-- Simple login form -->

                <form action="<?php echo base_url('admin/login');?>" method="post">

                    <div class="panel panel-body login-form">

                        <div class="text-center">

                            <strong class="login-logo">
								<a href="#"><img src="https://efynch.com/img/logo.png" alt=""></a>
							</strong>

                            <h5 class="content-group">Login to your account <small class="display-block">Enter your credentials below</small></h5>

                        </div>



                        <div class="form-group has-feedback has-feedback-left">

                            <input type="text" class="form-control" required name="email" placeholder="Username">

                            <div class="form-control-feedback">

                                <i class="icon-user text-muted"></i>

                            </div>

                        </div>



                        <div class="form-group has-feedback has-feedback-left">

                            <input type="password" class="form-control" required placeholder="Password" name="password">

                            <div class="form-control-feedback">

                                <i class="icon-lock2 text-muted"></i>

                            </div>

                        </div>



                        <?php if($this->session->flashdata('error')){?>

                            <div class="alert alert-danger" role="alert">

                                <span class="sr-only">Error:</span>

                                <?php echo $this->session->flashdata('error'); ?>

                            </div>

                        <?php }else if($this->session->flashdata('success')){?>

                            <div class="alert alert-success" role="alert">

                                <span class="sr-only">Success:</span>

                                <?php echo $this->session->flashdata('success'); ?>

                            </div>

                        <?php } ?>



                        <div class="form-group">

                            <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 position-right"></i></button>

                        </div>
                        <p class="font-color"><a href="<?php echo base_url(); ?>admin/login/change_password">Change Password?</a></p>



                        <!--<div class="text-center">

                            <a href="login_password_recover.html">Forgot password?</a>

                        </div>-->

                    </div>

                </form>

                <!-- /simple login form -->

            </div>

            <!-- /content area -->



        </div>

        <!-- /main content -->



    </div>

    <!-- /page content -->



</div>

<!-- /page container -->



</body>

</html>