<link href="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.pack.js" type="text/javascript"></script>
    <section class="right-side divheight">
    	<div class="container-right pr0 pl0">
        	<div class="col-sm-9 col-lg-9 col-md-11 pl0 pr0 res-middle-side">
            	<a href="<?php echo base_url('contractor/working'); ?>" class="icon-dash my-job-post"><i></i><span>My Jobs</span><p class="badge-job-inside"><?php echo $allcounts['myjobscount']; ?></p></a>
                <a href="<?php echo base_url('mybids'); ?>" class="icon-dash my-bids"><i></i><span>My Bids</span><p class="badge-job-inside"><?php echo $allcounts['mybidscount']; ?></p></a>
                <a href="<?php echo base_url('notification'); ?>" class="icon-dash my-notification"><i></i><span>Notification</span></a>
                <a href="<?php echo base_url('messages'); ?>" class="icon-dash my-messages"><i></i><span>Messages</span></a>
                <a href="<?php echo base_url('accountbalance'); ?>" class="icon-dash my-payments"><i></i><span>Account Balance</span></a>
              	<?php 
                if (!empty($login_video) ){
                ?>
                <a class="fancybox.iframe" href="<?=$login_video?>" style="display: none;" id="login_video">video</a>
                <?php 
                }
                ?>

                <div class="col-xs-12 p0">
                	<div class="col-xs-12 dash-panel-head">
                    <div class="col-xs-12 col-sm-6 p0">
                      <h1>Job Board</h1>
                    </div>
                    <form name="searchjobs" id="searchjobs" action="<?php echo base_url('search'); ?>" method="get">
                    <div class="col-xs-12 col-sm-6 p0 mt15-xs">
                        <div class="dash-search">
                          <input class="input100-login radius30p" type="text" placeholder="Search" name="search" />
                          <button type="submit" class="searchbtn" >Search</button>
                        </div>
                    </div>
                  </form>
                  </div>
                    <div class="clear"></div>
					<?php if( !empty( $contractorExpertises ) ): ?>
						<?php foreach( $contractorExpertises as $ce => $cv ): ?>
							<a href="<?php echo base_url('mybids/'.$cv['slug']); ?>" class="col-sm-4 icon-jobboard icon-<?php echo $cv['webimgclass']; ?>"><i></i><h2><?php echo $cv['name']; ?></h2>
                <span class="badge-job"><?php echo $cv['count']; ?></span>
              </a>
						<?php endforeach; ?>
					<?php else : ?>
						<div class="alert alert-danger">No Records Found</div>
					<?php endif; ?>
                </div>

            </div>


      <?php
      $this->load->view('headers/rightside');
      //include('rightside/rightside-dashboard.php')
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
