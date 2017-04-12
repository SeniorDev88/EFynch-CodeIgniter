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

						 <a href="javascript:void(0)" class="icon-size icon-delete" onclick="deleteJobPost('<?php echo $jobDets['jobkey']; ?>')"></a>  
						<a href="<?php echo base_url('editjobpost/'.$jobDets['jobkey']); ?>" class="icon-size icon-edit" data-toggle="tooltip" data-placement="top" title="Edit"></a>

					</div>
				</div>

				<div class="row-jobdetails mt20 mt0-xs clearfix">
					<div class="col-md-12 col-lg-6"><h2><?php echo $jobDets['jobname']; ?></h2></div>
					<div class="col-md-12 col-lg-6 tr tl-md"><h4 class="mt0-xs"><?php echo $expertises[$jobDets['expertiseid']]['name']; ?> <?php if($jobDets['startdate'] != '0000-00-00'){ ?> <span>| <?php echo date('m/d/Y',strtotime($jobDets['startdate'])); ?> </span><?php } ?><?php if($completiondate['startdate'] != '0000-00-00'){ ?> <br/><span> Completion Date : <?php echo date('m/d/Y',strtotime($jobDets['completiondate'])); ?> </span><?php } ?> </h4></div>
					<div class="col-md-12 col-lg-6"><p>Budget : $<?php echo  number_format(round($jobDets['budget'],2),2); ?><br/><?php echo nl2br($jobDets['address']); ?><br/><?php echo $jobDets['city']; ?> <?php echo $jobDets['state']; ?>, <?php echo $jobDets['zip']; ?></p></div>
					<div class="col-sm-12">
						<?php if( !empty( $jobImages ) ): ?>
							<?php foreach( $jobImages as $ji ): ?>
								<?php if($ji['doctype'] == 'image'): ?>
									<img src="<?php echo $ji['Img']; ?>" />
								<?php else: ?>
								<a href="<?php echo base_url('download/'.$ji['dockey']); ?>" ><img src="<?php echo base_url('images/Document-icon.png'); ?>" /></a>
							<?php endif; ?>
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
				<div class="col-xs-12 bid-bottom">
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
