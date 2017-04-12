<link href="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.pack.js" type="text/javascript"></script>

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

function submitAward(){
	$("#awards").submit();
}
</script>
<section class="right-side divheight">
    <div class="container-right pr0 pl0">
    	    		<?php
      $this->load->view('headers/dashboard_menu');
?>
        <div class="col-xs-12 col-sm-12 col-md-6 pl0 pr0 mb30-sm">
			<div class="col-xs-12">
				<div class="col-sm-12"><h1>Job Info</h1></div>

				<div class="row-jobdetails mt20 clearfix">
					<div class="col-md-12 col-lg-7"><h2><?php echo $jobDets['jobname']; ?></h2></div>
					<div class="col-md-12 col-lg-5 tl-md tr"><h4><?php echo $expertises[$jobDets['expertiseid']]['name']; ?> <span>| <?php echo date('m/d/Y',strtotime($jobDets['startdate'])); ?> </span> </h4></div>
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
				
			</div>
        </div>

		<?php $this->load->view('headers/rightside'); ?>

        <div class="clear"></div>
    </div>
</section>
<!--Wrapper Starts from left menu-->
</div>
<?php 
if (!empty($hire_video) ){
?>
<a class="fancybox.iframe" href="<?=$hire_video?>" style="display: none;" id="hire_video">video</a>
<?php 
}
?>

<?php 
if(!empty($hire_video)){
?>
<script>
	$(document).ready(function() {
    $("a#hire_video").fancybox({
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
    $("a#hire_video").trigger("click");
  }); 
</script>
<?php
}
?>
