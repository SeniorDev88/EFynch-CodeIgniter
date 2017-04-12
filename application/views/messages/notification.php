
    <section class="right-side divheight">
    	<div class="container-right pr0 pl0">
                        <?php
      $this->load->view('headers/dashboard_menu');
?>

                <div class="col-xs-12">

                    <div class="col-sm-12 mt20">
                        <div class="btn-group btn-group-justified">
                        <a class="a-tabs-justify btn btn-default <?php if($type == "all"){ ?>active<?php } ?> " href="<?php echo base_url('notification') ?>">All </a> <a class="a-tabs-justify btn btn-default <?php if($type == "bids"){ ?>active<?php } ?>" href="<?php echo base_url('notification/bids') ?>">Bids </a>  <a class="btn btn-default a-tabs-justify <?php if($type == "payment"){ ?>active<?php } ?>" href="<?php echo base_url('notification/payment') ?>">Payments </a>
                        </div>
                    </div>

                	<div class="col-sm-12 mb10"><h1>Notification</h1></div>  <div class="clear"></div>

                    <div class="col-sm-12 p0-xs">
                        <?php if(!empty($notification)){
                                foreach($notification as $not){
                                    //print "<pre>"; print_r($not); print "</pre>";
                                    ?>
                                    <a class="row-middle-wrap row-notification clearfix pr5" href="<?php echo $not['link']; ?>">
                                        <div class="profile-img-md radius50"><img src="<?php echo $not['image']; ?>"></div>
                                        <p><?php echo $not['notificationmessage']; ?> </p>
                                        <h2><?php //echo $not['jobname']; ?> <?php /* ?><span>(Job No: 34567) </span><?php */ ?></h2>
                                        <span><?php echo date('m/d/Y | H:i A',$not['createdate']); ?></span>
                                    </a>
                            <?php
                                }
                        }else{

                        ?>
                        <p class="alert alert-danger mt10">No Notifications Found.</p>
                        <?php
                        } ?>


                    </div>


                </div>



        <div class="clear"></div>
        </div>

    </section>

<!--Wrapper Starts from left menu-->
</div>
