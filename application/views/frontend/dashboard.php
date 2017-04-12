<link href="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.pack.js" type="text/javascript"></script>
<section class="right-side divheight">
    <div class="container-right pr0 pl0">
    	<div class="col-sm-12 col-md-12 col-lg-9 pl0 pr0">
        	<a href="<?php echo base_url('postjob'); ?>" class="icon-dash post-a-job"><i></i><span>Post a Job</span></a>
            <a href="<?php echo base_url('owner/bidding'); ?>" class="icon-dash my-job-post"><i></i><span>My Job Posts</span><p class="badge-job-inside"><?php echo $allcounts['myjobscount']; ?></p></a>
            <a href="<?php echo base_url('notification'); ?>" class="icon-dash my-notification"><i></i><span>Notification</span></a>
            <a href="<?php echo base_url('messages'); ?>" class="icon-dash my-messages"><i></i><span>Messages</span></a>
            <a href="<?php echo base_url('accountbalance'); ?>" class="icon-dash my-payments"><i></i><span>My Payments</span></a>
        	<?php 
            if (!empty($login_video) ){
            ?>
            <a class="fancybox.iframe" href="<?=$login_video?>" style="display: none;" id="login_video">video</a>
            <?php 
            }
            ?>
            <div class="col-xs-12 pl30 pr30 p5-xs">
				<?php if( !empty( $bidDets ) ):
						foreach( $bidDets as $bt => $bd ): ?>
							<a href="<?php echo base_url('worklist/'.$bd['jobkey']); ?>" class="dash-bids clearfix">
								<div class="col-xs-12 col-md-8">
									<div class="profile-img-md radius50"><img src="<?php echo $bd['bidImg']; ?>" /></div>
                <div class="visible-xs visible-sm clear"></div>
									<div class="dash-bid-cont">
										<h2><?php echo $bd['jobname']; ?></h2>
										<p class="dash-description"><?php echo ( strlen( $bd['jobdescription'] ) > 100 ) ? substr(nl2br(stripslashes($bd['jobdescription'])),0,100)."..." : nl2br(stripslashes($bd['jobdescription'])); ?></p>
										<h4><?php echo $expertises[$bd['expertiseid']]['name']; ?>  <span>| <?php echo date('m/d/Y',$bd['createdate']); ?> </span> </h4>
									</div>
								</div>
								<div class="col-md-4 dash-bids-right"> <div class="bid-count radius50 mt15"><?php echo $bd['numberofbids']; ?> <h5>Bids</h5></div> </div>
							</a>
						<?php endforeach; ?>
				<?php else: ?>
					<div class="alert alert-danger mt10">No new Jobs found</div>
				<?php endif; ?>
            </div>
        </div>
        <?php
          $this->load->view('headers/rightside');
        ?>
        <div class="clear"></div>
    </div>
</section>
<!--Wrapper Starts from left menu-->
</div>
<?php 
if (!empty($login_video) ){
?>
<script>
    $(document).ready(function() {
        $("a#login_video").fancybox({
            maxWidth    : 800,
            maxHeight   : 600,
            fitToView   : true,
            width       : '70%',
            height      : '70%',
            autoSize    : true,
            closeClick  : false,
            openEffect  : 'none',
            closeEffect : 'none'
        });
        $("a#login_video").trigger("click");
    }); 
</script>
<?php    
}
?>
