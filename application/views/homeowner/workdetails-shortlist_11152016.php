<script>
function deleteJobPost(key){
	if( confirm('Are you sure you want to delete this job?') ){
		$.post('<?php echo base_url('deleteJobPost'); ?>',{
		   act	: 'statchange',
		   key	: key
		},function (data){
			if( data == 'success' ){
				window.location.href = '<?php echo base_url('owner/bidding'); ?>';
			}
		});
	}
}
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
<section class="right-side divheight">
    <div class="container-right pr0 pl0">
    	    		<?php
      $this->load->view('headers/dashboard_menu');
?>
        <div class="col-xs-12 col-sm-12 col-md-6 pl0 pr0 pb40">
			<div class="col-xs-12">
				<div class="col-sm-12 clearfix">
					<h1 class="FL">Job Info</h1>
					<div class="jobshortlist">
						<a class="backbuton" href="javascript:history.back(1)">Back</a>
						
						<?php /* <a href="javascript:void(0)" class="icon-size icon-delete" onclick="deleteJobPost('<?php echo $jobDets['jobkey']; ?>')"></a> ?/> */ ?>
						<a href="<?php echo base_url('editjobpost/'.$jobDets['jobkey']); ?>" class="icon-size icon-edit" data-toggle="tooltip" data-placement="top" title="Edit"></a>
						
					</div>
				</div>

				<div class="row-jobdetails mt20 mt0-xs clearfix">
					<div class="col-md-12 col-lg-6"><h2><?php echo $jobDets['jobname']; ?></h2></div>
					<div class="col-md-12 col-lg-6 tr tl-md"><h4 class="mt0-xs"><?php echo $expertises[$jobDets['expertiseid']]['name']; ?> <?php if($jobDets['startdate'] != '0000-00-00'){ ?> <span>| <?php echo date('m/d/Y',strtotime($jobDets['startdate'])); ?> </span><?php } ?><?php if($completiondate['startdate'] != '0000-00-00'){ ?> <br/><span> Completion Date : <?php echo date('m/d/Y',strtotime($jobDets['completiondate'])); ?> </span><?php } ?> </h4></div>
					<div class="col-md-12 col-lg-6"><p><?php echo nl2br($jobDets['address']); ?><br/><?php echo $jobDets['city']; ?> <?php echo $jobDets['state']; ?>, <?php echo $jobDets['zip']; ?></p></div>
					<div class="col-sm-12">
						<?php if( !empty( $jobImages ) ): ?>
							<?php foreach( $jobImages as $ji ): ?>
								<img src="<?php echo $ji['Img']; ?>" />
							<?php endforeach; ?>
						<?php endif; ?>
						<div class="clear"></div>
						<p><?php echo nl2br(stripslashes($jobDets['jobdescription'])); ?></p>
					</div>

				<div class="clear"></div>
				</div>

				<div class="col-xs-12 bid-bottom">
						<span class="col-xs-4 p0"><p class="bidhead bidhy">High</p><h4>$<?php echo number_format(round($highest,2),2); ?></h4></span>
						<span class="col-xs-4 p0"><p class="bidhead bidav">Average</p><h4>$<?php echo number_format(round($average,2),2); ?></h4></span>
						<span class="col-xs-4 p0"><p class="bidhead bidlo">Low</p><h4>$<?php echo number_format(round($lowest,2),2); ?></h4></span>
					</div>

			</div>
        </div>

		<?php $this->load->view('headers/rightside'); ?>

        <div class="clear"></div>
    </div>
</section>
<!--Wrapper Starts from left menu-->
</div>
