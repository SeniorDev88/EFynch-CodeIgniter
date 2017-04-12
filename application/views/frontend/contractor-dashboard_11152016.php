
    <section class="right-side divheight">
    	<div class="container-right pr0 pl0">
        	<div class="col-sm-9 col-lg-9 col-md-11 pl0 pr0 res-middle-side">
            	<a href="<?php echo base_url('contractor/working'); ?>" class="icon-dash my-job-post"><i></i><span>My Jobs</span><p class="badge-job-inside"><?php echo $allcounts['myjobscount']; ?></p></a>
                <a href="<?php echo base_url('mybids'); ?>" class="icon-dash my-bids"><i></i><span>My Bids</span><p class="badge-job-inside"><?php echo $allcounts['mybidscount']; ?></p></a>
                <a href="<?php echo base_url('notification'); ?>" class="icon-dash my-notification"><i></i><span>Notification</span></a>
                <a href="<?php echo base_url('messages'); ?>" class="icon-dash my-messages"><i></i><span>Messages</span></a>
                <a href="<?php echo base_url('accountbalance'); ?>" class="icon-dash my-payments"><i></i><span>Account Balance</span></a>
            	<?php /*?><a href="#" class="icon-dash my-disclaimers"><i></i><span>Disclaimers</span></a> <?php */ ?>

                <div class="col-xs-12 pl30 pr30">
                	<div class="col-xs-12 mb20"><h1>Job Board</h1></div> <div class="clear"></div>
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
