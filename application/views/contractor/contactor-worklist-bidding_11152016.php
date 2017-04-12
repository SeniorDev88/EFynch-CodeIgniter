<script>
function completeJob(jobkey){
	if( confirm('Are you sure to mark this job as completed?') ){
		$.post('<?php echo base_url('completeJob'); ?>',{
		   act		: 'complete',
		   key		: jobkey
		},function (data){
			if( data == 'success' ){
				window.location.href = '<?php echo base_url('contractor/completed'); ?>';
			}
		});
	}
}
</script>

<section class="right-side divheight">

    <div class="container-right pr0 pl0">
    		 <?php
      $this->load->view('headers/dashboard_menu');
?>
        <div class="col-sm-12">
			<div class="col-sm-12 mt20">
				<div class="btn-group btn-group-justified">
					<?php /* <a href="#" class="a-tabs-justify btn btn-default active">Bidding </a> */ ?>
					<a href="<?php echo base_url('contractor/working'); ?>" class="a-tabs-justify btn btn-default <?php if( $bidtype == 'working' ): ?>active<?php endif; ?>">Working </a>
					<a href="<?php echo base_url('contractor/completed'); ?>" class="btn btn-default a-tabs-justify <?php if( $bidtype == 'completed' ): ?>active<?php endif; ?>">Completed </a>
				</div>
			</div>
			<div class="col-md-12"><h1>My Job Post</h1></div>

			<div class="clear"></div>
			<?php if( !empty( $bidDets ) ): ?>
				<?php foreach( $bidDets as $bt => $bd ):  ?>
				<?php// print_r($bd['contracts']); ?>
					<div class="dash-bids clearfix" id="jobs_<?php echo $bd['jobkey']; ?>">
						<div class="col-xs-12 col-md-12 col-lg-6">
							<div class="profile-img-md radius50 col-xs-12 col-sm-4 p0"><img src="<?php echo $bd['imageurl']; ?>" /></div>
							<div class="dash-bid-cont col-xs-12 col-sm-8 p0">
								<h2><?php echo $bd['title']; ?></h2>
								<h4>Bid Amount : <span class="big-amount">$<?php echo number_format($bd['bidamount'],2, '.', ''); ?></span></h4>
								<h3><?php echo  ( strlen( $bd['jobdescription'] ) > 100 ) ? substr(nl2br(stripslashes($bd['jobdescription'])),0,100)."..." : nl2br(stripslashes($bd['jobdescription'])); ?></h3>
								<h4><?php echo $bd['category']['name']; ?>  <span>| <?php echo $bd['date']; ?> </span> </h4>
							</div>
						</div>
						<div class="col-xs-12 col-md-12 col-lg-6 mt20-sm">
							<div class="col-sm-6 p0 clearfix">
								<a class="profile-name-sm" href="javascript:void(0)">
									<span class="profile-img-sm radius50"><img src="<?php echo $bd['contractorsetails']['imgurl']; ?>"></span> 
									<h2 class="pt5"><?php echo $bd['contractorsetails']['firstname']." ".$bd['contractorsetails']['lastname'][0]; ?> </h2> 
									<h3><?php echo $bd['contractorsetails']['city']; ?>, <?php echo $bd['contractorsetails']['state']; ?> <?php echo $bd['contractorsetails']['zip']; ?></h3>
								</a>
							</div>
							<?php if( $bidtype == 'working' ): ?>
							<?php  if( $bd['contracts']['bt_transaction_id'] != '' ): ?>
								<div class="col-sm-6 p0 clearfix">
									<a title="Complete Job" class="btn-green-accept" href="javascript:void(0)" onclick="completeJob('<?php echo $bd['jobkey']; ?>')">Complete This Job</a>
								</div>
							
							<?php endif; ?>
							<?php else: ?>
								<div class="col-sm-4 pr5 date-wraps pl0-xs"> <p>Completed on</p> <h5><?php echo date('m/d/Y',strtotime($bd['completeddate'])); ?></h5></div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<div class="alert alert-danger">No Records Found</div>
			<?php endif; ?>
        </div>
        <div class="clear"></div>
    </div>
</section>
<!--Wrapper Starts from left menu-->
</div>
