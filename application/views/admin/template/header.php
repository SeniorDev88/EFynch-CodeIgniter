<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Efynch</title> <!-- Global stylesheets -->

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"

          type="text/css">

    <link href="<?php echo(base_url()); ?>assets/admin/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">

    <link href="<?php echo(base_url()); ?>assets/admin/css/minified/bootstrap.min.css" rel="stylesheet" type="text/css">

    <link href="<?php echo(base_url()); ?>assets/admin/css/minified/core.min.css" rel="stylesheet" type="text/css">

    <link href="<?php echo(base_url()); ?>assets/admin/css/minified/components.min.css" rel="stylesheet"

          type="text/css">

    <link href="<?php echo(base_url()); ?>assets/admin/css/minified/colors.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"/>
<link href="<?php echo base_url()?>assets/admin/css/all.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <script type="text/javascript"

            src="<?php echo(base_url()); ?>assets/admin/js/core/libraries/jquery.min.js"></script>

    <!-- /page container -->

    <!-- Core JS files -->

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/loaders/pace.min.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/core/libraries/bootstrap.min.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/loaders/blockui.min.js"></script>

    <!-- /core JS files -->



    <!-- Theme JS files -->

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/visualization/d3/d3.min.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/visualization/d3/d3_tooltip.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/forms/styling/switchery.min.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/forms/styling/uniform.min.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/forms/selects/bootstrap_multiselect.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/ui/moment/moment.min.js"></script>



    <!--<script type="text/javascript" src="<?php /*echo(base_url()); */?>assets/admin/js/core/app.js"></script>-->

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/core/libraries/jquery_ui/datepicker.min.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/core/libraries/jquery_ui/effects.min.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/notifications/jgrowl.min.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/ui/moment/moment.min.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/daterangepicker.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/anytime.min.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/pickadate/picker.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/pickadate/picker.date.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/pickadate/picker.time.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/pickadate/legacy.js"></script>

    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/pages/picker_date.js"></script>

    <!--<script type="text/javascript" src="<?php /*echo(base_url()); */?>assets/admin/js/pages/dashboard.js"></script>-->

    <!-- /theme JS files -->



    <!-- Theme JS files for ckEditor -->

    <!--<script type="text/javascript" src="<?php /*echo(base_url()); */?>assets/ckeditor/ckeditor.js"></script>-->
    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/core/app.js"></script>
    <!--<script type="text/javascript" src="<?php /*echo(base_url()); */?>assets/admin/js/pages/editor_ckeditor.js"></script>-->

</head>

<body><?php $controller = $this->router->fetch_class();

$method = $this->router->fetch_method();

$last = $this->uri->total_segments();

$last = $this->uri->segment($last); ?>    <!-- Main navbar -->

<div class="navbar navbar-inverse">

    <div class="navbar-header"><a class="bnd-logo" href=""> <img

                src="<?php echo(base_url()); ?>assets/admin/images/logo.png" alt=""> <span>Efynch</span> </a>

        <ul class="nav navbar-nav visible-xs-block">

            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>

            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>

        </ul>

    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">

        <ul class="nav navbar-nav">

            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a>

            </li>

        </ul>

        <ul class="nav navbar-nav navbar-right">

            <li class="dropdown dropdown-user"><a class="dropdown-toggle" data-toggle="dropdown"> <img

                        src="<?php echo(base_url()); ?>assets/admin/images/placeholder.jpg" alt=""> <span><?php echo $this->session->userdata('user')[0]['name'];?></span>

                    <i class="caret"></i> </a>

                <ul class="dropdown-menu dropdown-menu-right">

                    <li><a href="<?php echo base_url('logout-admin'); ?>"><i class="icon-switch2"></i> Logout</a></li>

                </ul>

            </li>

        </ul>

    </div>

</div>

<!-- /main navbar -->        <!-- Page container -->

<div class="page-container">        <!-- Page content -->

    <div class="page-content">        <!-- Main sidebar -->

        <div class="sidebar sidebar-main">

            <div class="sidebar-content">        <!-- Main navigation -->

                <div class="sidebar-category sidebar-category-visible">

                    <div class="category-content no-padding" id="menuBoxX">

                        <ul class="navigation navigation-main navigation-accordion">        <!-- Main -->

                            <li <?php if ($controller == 'dashboard') echo "class='active'"; ?>><a

                                    href="<?php echo(base_url('admin/dashboard')); ?>"><i class="fa fa-home icon-style" aria-hidden="true"></i> <span>Dashboard</span></a>

                            </li>

                            <!--<li <?php /*if($controller == 'projects') echo "class='active'";*/ ?>>            <a href="#"><i class="icon-home4"></i> <span>Projects and Publication</span></a>            <ul>                <li <?php /*if($controller == 'projects') echo "class='active'";*/ ?>><a href="<?php /*echo(base_url('admin/projects')); */ ?>">Projects</a></li>                <li <?php /*if($controller == 'reports') echo "class='active'";*/ ?>><a href="<?php /*echo(base_url('admin/reports')); */ ?>">Financial Reports</a></li>                <li <?php /*if($controller == 'performance') echo "class='active'";*/ ?>><a href="<?php /*echo(base_url('admin/performance')); */ ?>">Performance Reports</a></li>            </ul>        </li>-->

                            <!--<li <?php /*if($controller == 'employees') echo "class='active'";*/ ?>>            <a href="<?php /*echo(base_url('admin/employees')); */ ?>"><i class="icon-home4"></i> <span>Employees</span></a>        </li>        <li <?php /*if($controller == 'surveys') echo "class='active'";*/ ?>>            <a href="<?php /*echo(base_url('admin/surveys')); */ ?>"><i class="icon-home4"></i> <span>Surveys</span></a>        </li>-->

                            <li <?php if ($controller == 'users') echo "class='active'"; ?>><a

                                    href="<?php echo(base_url('admin/users')); ?>"><i class="fa fa-users icon-style"></i> <span>Users</span></a>

                            </li>

                            <li <?php if ($controller == 'jobs') echo "class='active'"; ?>><a

                                    href="<?php echo(base_url('admin/jobs')); ?>"><i class="fa fa-briefcase icon-style"></i>

                                    <span>Jobs</span></a></li>

                            <li <?php if ($controller == 'bids') echo "class='active'"; ?>><a

                                    href="<?php echo(base_url('admin/bids')); ?>"><i class="fa fa-circle-o icon-style"></i>

                                    <span>Bids</span></a></li>

                            <li <?php if ($controller == 'reports') echo "class='active'"; ?>>
                                <a href="#" class="has-ul"><i class="fa fa-circle-o icon-style"></i> <span>Reports</span></a>
                                <ul class="hidden-ul" style="<?php if ($controller == 'reports' && ($method == 'financial' || $method == 'jobs' || $method == 'users')) echo "display: block";else echo  "display: none;";?>">
                                    <li <?php if ($controller == 'reports' && $method == 'jobs') echo "class='active'"; ?>><a href="<?php echo(base_url('admin/reports/jobs')); ?>">Jobs</a></li>
                                    <li <?php if ($controller == 'reports' && $method == 'users') echo "class='active'"; ?>><a href="<?php echo(base_url('admin/reports/users')); ?>">Users</a></li>
                                    <li <?php if ($controller == 'reports' && $method == 'financial') echo "class='active'"; ?>><a href="<?php echo(base_url('admin/reports/financial')); ?>">Financial</a></li>
                                </ul>
                            </li>

                            <!--<li <?php /*if($controller == 'reports') echo "class='active'";*/ ?>>            <a href="<?php /*echo(base_url('admin/reports')); */ ?>"><i class="icon-home4"></i> <span>Reports</span></a>        </li>-->

                        </ul>

                    </div>

                </div>

                <!-- /main navigation -->        </div>

        </div>

        <!-- /main sidebar -->        <!-- Main content -->

        <div class="content-wrapper">            <!-- Page header -->

            <!--<div class="page-header">

                <div class="breadcrumb-line">

                    <ul class="breadcrumb">

                        <li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>

                        <li class="active"><?php /*echo ucfirst($method) */?></li>

                    </ul>

                </div>

            </div>-->

            <!-- messages --> <img id="loaderImg"

                                   style="display:none;left: 0;margin: auto;position: fixed;right: 0;top: 40%;z-index: 100000;"

                                   src="<?php echo(base_url('assets/admin/images/ajax-loader(1).gif')); ?>"

                                   alt="Loading Please Wait...!">            <?php if ($this->session->flashdata('error')) { ?>

                <div class="alert alert-danger" role="alert">

                    <!--<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>--> <span

                        class="sr-only">Error:</span>                    <?php echo $this->session->flashdata('error'); ?>

                </div>            <?php } else if ($this->session->flashdata('success')) { ?>

                <div class="alert alert-success" role="alert">

                    <!--<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>--> <span

                        class="sr-only">Success:</span>                    <?php echo $this->session->flashdata('success'); ?>

                </div>            <?php } ?>            <!-- /page header -->