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
					<?php if( $deleteedit == '1' ): ?>
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
									Budget : $<?php echo  number_format(round($jobDets['budget'],2),2); ?><br/>
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
								<?php if($ji['doctype'] == 'image'): ?>
									<img src="<?php echo $ji['Img']; ?>" />
								<?php else: ?>
								<a href="<?php echo base_url('download/'.$ji['dockey']); ?>" ><img src="<?php echo base_url('images/Document-icon.png'); ?>" /></a>
							<?php endif; ?>
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
				<style>
					.first_aun_ques{
						margin-top: 40px;
					}
					.question_div{
						font-weight: bold;
						font-size: 15px;
					}
					.answer_div{
						margin-top: 5px;
					}
				</style>
				<div class="col-xs-12">
					<h1>More Questions</h1>
					<div class="question_div first_aun_ques">
						What is the time frame to start work?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['timeframe'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						Days to be posted
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['daysposted'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						How many stories are in your home?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['total_stories'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						Is the project Indoors or Outdoors?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['indoor'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						What type of home do you have?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['hometype'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						What is your state of starting the work?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['starting_state'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						Should materials be included in the bid?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['material_option'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						Do you want a flat rate or hourly?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['rate_type'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						What year was your home built(can be approximate)?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['year_constructed'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						What is the current condition of the project?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['current_condition'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						When and how was the problem first noticed?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['first_problem_notice'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						What is the resolution you are asking for?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['resolution'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						Are there any relevant measurements you can provide(room size ,pipe size)?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['measurements'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						Do you have any preference on materials used(list and attach pictures)
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['material_preferences'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						Have you purchased any material for this project (list and attach pictures)?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['purchased_materials'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						Please describe the access to the area(i.e basement with walk-in doors)?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['access_to_area'])); ?></p>
					</div>
				</div>
				<div class="col-xs-12 bid-bottom">
					<div class="question_div">
						Can you list your preffered availability(evenings, weekend, weekdays)?
					</div>
					<div class="answer_div">
						<p><?php echo nl2br(stripslashes($jobDets['your_availability'])); ?></p>
					</div>
				</div>
				
			</div>
		</div>

		<?php $this->load->view('headers/rightside'); ?>
		<div class="clear"></div>
	</div>
</section>
<!--Wrapper Starts from left menu-->
</div>
