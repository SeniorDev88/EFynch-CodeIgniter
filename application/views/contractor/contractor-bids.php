<section class="right-side divheight">
	<div class="container-right pr0 pl0">
		<?php
      $this->load->view('headers/dashboard_menu');
?>
		<div class="col-sm-12">
			<div class="col-md-6"><h1><?php if(!$jobbids){ echo "My Bids"; }else{  echo "My Jobs";} ?></h1></div>

			<div class="col-md-6 clearfix">
          <div class="bid-types tc">
						<span class="bid-price bid-avg">Avg.</span> <span class="bid-price bid-low">Low</span>  <span class="bid-price bid-high">High</span>
					</div>
				</div>
			<div class="clear"></div>
			<?php if( !empty( $jobs ) ): ?>
				<?php foreach( $jobs as $jk => $jy ): ?>
				<?php if($frompage == 'board'){
					?>
					<a href="<?php echo base_url('bidjob/'.$jy['jobkey']); ?>" class="dash-bids clearfix">
					<?php
				}else{
					?>
					<a href="<?php echo base_url('biddedjob/'.$jy['jobkey']); ?>" class="dash-bids clearfix">
					<?php
				} ?>

						<div class="col-xs-12 col-md-12 col-lg-6">
							<div class="profile-img-md radius50 col-xs-12 col-sm-4 p0"><img src="<?php echo $jy['bidImg']; ?>" /></div>
							<div class="dash-bid-cont col-xs-12 col-sm-8 p0">
								<h2><?php echo $jy['jobname']; ?></h2>
								<?php if(!$jobbids){
									?>
									<h4>My Bid : $<?php echo number_format(round($jy['bidamount'],2),2); ?></h4>
								<?php
								} ?>
								<?php if($jy['bidplaced'] == 1): ?><h4> Bid Placed</h4><?php endif; ?>
								<h3><?php echo ( strlen( $jy['jobdescription'] ) > 100 ) ? substr(nl2br(stripslashes($jy['jobdescription'])),0,100)."..." : nl2br(stripslashes($jy['jobdescription'])); ?></h3>
								<h4><?php echo $expertises[$jy['expertiseid']]['name']; ?>  <span>| <?php echo date('m/d/Y',strtotime($jy['startdate'])); ?> </span> </h4>
							</div>
						</div>

						<div class="col-xs-12 col-md-12 col-lg-6 mt20-sm dash-bids-right pb60-md pb60-xs">
							<?php if( $jy['numberofbids'] > 0 ): ?>
								<span class="bid-price bid-avg">$<?php echo number_format(round($jy['averagebid'],2),2);; ?></span> <span class="bid-price bid-low">$<?php echo number_format(round($jy['lowestbid'],2),2);; ?></span>  <span class="bid-price bid-high">$<?php echo number_format(round($jy['highestbid'],2),2);; ?></span>
							<?php endif; ?>
							<div class="bid-count radius50"><?php echo $jy['numberofbids']; ?></div>
						</div>
					</a>
				<?php endforeach; ?>
			<?php else: ?>
				<div class="alert alert-danger mt10">No Jobs Found</div>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
	</div>
</section>
<!--Wrapper Starts from left menu-->
</div>
