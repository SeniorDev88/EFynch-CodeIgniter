<script>
function deleteMyBid(key){
	if( confirm('Are you sure you want to delete this bid?') ){
		$.post('<?php echo base_url('deleteBid'); ?>',{
		   act	: 'statchange',
		   key	: key
		},function (data){
			if( data == 'success' ){
				window.location.href = '<?php echo base_url('mybids'); ?>';
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
		<div class="col-xs-12 col-sm-11 col-md-9 pl0 pr0">
			<div class="col-xs-12">
				<div class="col-sm-9"><h1>Bid Info</h1></div>
				<div class="col-sm-3 mt20 mt10-xs clearfix pl0 pr0">
				<?php if( !empty( $bidDets ) ): ?>
					<?php if( $homenowneragree == 0 ): ?>
						<a href="javascript:void(0)" class="icon-size icon-delete" onclick="deleteMyBid('<?php echo $bidDets['bidkey']; ?>')" data-toggle="tooltip" data-placement="top" title="Delete"></a>
						<a href="<?php echo base_url('editbid/'.$bidDets['bidkey']); ?>" class="icon-size icon-edit" data-toggle="tooltip" data-placement="top" title="Edit"></a>
					<?php endif; ?>
					<a class="FR" href="<?php echo base_url('messages/'.$jobDets['jobkey'].'/'.$homeOwner['userkey']); ?>"><i data-toggle="tooltip" data-placement="top" title="Message" class="icon-msghim"></i></a>

				<?php endif; ?>
				</div>
				<div class="row-jobdetails mt20 mt0-xs clearfix">
					<div class="col-xs-12"><h2><?php echo $jobDets['jobname']; ?></h2></div>
					<div class="col-xs-12">
						<div class="col-xs-12 bid-job-box">
							<div class="col-xs-12 col-sm-12 col-md-6 p0 mb15-sm">
								<div class="bidhomeadrs">
									<?php if( $contract['bt_transaction_id'] != '' ): ?><?php echo nl2br($jobDets['address']); ?><br/><?php endif; ?>
									<?php echo $jobDets['city']; ?> <?php echo $jobDets['state']; ?>, <?php echo $jobDets['zip']; ?></div>
							</div>

						<div class="col-xs-12 col-sm-12 col-md-6 tr tl-md p0">
								<div class="bidh4"><?php echo $expertises[$jobDets['expertiseid']]['name']; ?> <?php if($jobDets['startdate'] != '0000-00-00'){ ?> <span>| <?php echo date('m/d/Y',strtotime($jobDets['startdate'])); ?> </span><?php } ?><?php if($completiondate['startdate'] != '0000-00-00'){ ?> <br/><span> Completion Date : <?php echo date('m/d/Y',strtotime($jobDets['completiondate'])); ?> </span><?php } ?> </div>
						</div>
						</div>
					</div>
					<div class="clear"></div>
					<div class="col-sm-12">
						<p class="mt15"><?php echo nl2br(stripslashes($jobDets['jobdescription'])); ?></p>
						<?php if( !empty( $jobImages ) ): ?>
								<h4>Images</h4>
							<?php
								foreach( $jobImages as $ji ): ?>
									<img src="<?php echo $ji['Img']; ?>" />
						<?php endforeach; endif; ?>
						<div class="clear"></div>
					</div>
				<div class="clear"></div>
				</div>

				<?php if( !empty( $bid ) ): ?>
				<div class="col-sm-12"><h3 class="mt20">Bid Details</h3></div>
				<div class="row-jobdetails mt0-xs clearfix">
					<div class="col-xs-12">
						<div class="col-xs-12 bid-job-box">
							<div class="col-xs-12 col-sm-12 col-md-6 p0">
								<div class="bidhomeadrs"><span> Start Date : <?php echo  $bid['startingtime']; ?> </span> <br/><span> Expected Time : <?php echo  $bid['expectedtime']; ?> </span><br/><span> Maximum Time : <?php echo  $bid['maximumtime']; ?> </span> </div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 p0">
								<div class="bidhomeadrs tr tl-sm"><span> Bid Amount : <?php echo  $bid['amount']; ?> </span> <br/><span> Additional Amount: <?php echo  $bid['addamount']; ?> </span></div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
					<div class="col-sm-12">
						<p class="mt15"><?php echo nl2br(stripslashes($bid['description'])); ?></p>

						<div class="clear"></div>
					</div>
				<div class="clear"></div>
				</div>

				<?php endif; ?>

				
			</div>
		</div>

		<?php $this->load->view('headers/rightside'); ?>
		<div class="clear"></div>
	</div>
</section>
<!--Wrapper Starts from left menu-->
</div>
