<script>
$(document).ready(function(){
	$("#ratemyjob").validate({
		ignore	: [],
		rules	: {
			hasRate : 'required'
		},
		messages	: {
			hasRate	: 'Please rate this job'
		}
	});
});

function verifyJob(){
	if( $("#ratemyjob").valid() ){
		$.post('<?php echo base_url('completeJob'); ?>',{
		   act		: 'verify',
		   key		: $("#selectedjob").val(),
		   userkey	: $("#selecteduser").val(),
		   star		: $("#hasRate").val()
		},function (data){
			if( data == 'success' ){
				window.location.href = '<?php echo base_url('owner/completed'); ?>';
			}
		});
	}
}

function rateJob(jobkey,userkey){
	$(".rstars").removeClass('active');
	$("#selectedjob").val(jobkey);
	$("#selecteduser").val(userkey);
	$("#hasRate").val('');
	$("#ratemod").modal('show');
	var imgurl = $("#contrctorimgid_"+jobkey+"_"+userkey).attr('src');
	//alert(imgurl);
	$("#rateimg").attr('src',imgurl);
}

function selectRate(ths){
	var myrate = $(ths).attr('data');
	$(".rstars").removeClass('active');
	$(".rstars").each(function(){
		if( $(this).attr('data') <= myrate ){
			$(this).addClass('active');
		}
	});
	$("#hasRate").val(myrate);
	$("#hasRate").valid();
}
</script>
<style type="text/css">
.strwrp .stars {
    background: rgba(0, 0, 0, 0) url("../images/iconset.png") no-repeat scroll -57px -185px;
    cursor: pointer;
    display: inline-block;
    height: 14px;
    transform: scale(1.2);
    width: 14px;
    float: none;
}
.strwrp  .stars.active {
    background-position: -71px -185px;
}
</style>

<div id="ratemod" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form name="ratemyjob" id="ratemyjob" method="post">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Rate and Accept</h4>
      </div>
      <div class="modal-body">
      	<div class="col-sm-12 pl5 pt20  tc pb10 ">
      		<div class="profile-img-md radius50 m0auto"><img  id="rateimg" src="" /></div>
      	</div>
        <div class="col-sm-12 pl5 pt20 FN tc pb10 strwrp">
		<?php for($rt=1;$rt<=5;$rt++){ ?>
			<i class="stars rstars" data="<?php echo $rt; ?>" onclick="selectRate(this)"></i>
		<?php } ?>

		 </div>
		 <input type="hidden" name="selectedjob" id="selectedjob" value=""/>
		<input type="hidden" name="selecteduser" id="selecteduser" value=""/>
		<input type="hidden" name="hasRate" id="hasRate" value=""/>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="verifyJob()">Submit</button>
      </div>
    </div>
    </form>

  </div>
</div>

<section class="right-side divheight">
   <div class="container-right pr0 pl0">
   	    		<?php
      $this->load->view('headers/dashboard_menu');
?>
        <div class="col-sm-12 pb40">
			<div class="col-xs-12 col-sm-9 mt20 p0-xs">
				<div class="btn-group btn-group-justified">
					<a href="<?php echo base_url('owner/bidding'); ?>" class="a-tabs-justify btn btn-default <?php if( $bidtype == 'bidding' ): ?>active<?php endif; ?>">Bidding </a>
					<a href="<?php echo base_url('owner/working'); ?>" class="a-tabs-justify btn btn-default <?php if( $bidtype == 'working' ): ?>active<?php endif; ?>">Working </a>
					<a href="<?php echo base_url('owner/completed'); ?>" class="btn btn-default a-tabs-justify <?php if( $bidtype == 'completed' ): ?>active<?php endif; ?>">Completed </a>
				</div>
			</div>
      <div class="col-xs-12 col-sm-3 tc-xs">
        <a class="backbuton mt25 mt15-xs" href="javascript:history.back(1)">Back</a>
      </div>
      <div class="clear"></div>
			<div class="<?php if( $bidtype == 'bidding' ): ?>col-md-6<?php else: ?>col-md-12<?php endif; ?>"><h1><?php echo ( $bidtype == 'bidding' ) ? 'My Job Post' : 'Award Work'; ?></h1></div>
			<?php if( $bidtype == 'bidding'  and !empty( $bidDets )): ?>
				<div class="col-md-6 clearfix">
          <div class="bid-types tc">
						<span class="bid-price bid-avg">Avg.</span> <span class="bid-price bid-low">Low</span>  <span class="bid-price bid-high">High</span>
					</div>
				</div>
			<?php endif; ?>
            <div class="clear"></div>
			<?php if( !empty( $bidDets ) ): ?>
					<?php if( $bidtype == 'bidding' ): ?>
						<?php foreach( $bidDets as $bt => $bd ): ?>
							<a href="<?php echo base_url('worklist/'.$bd['jobkey']); ?>" class="dash-bids clearfix">
								<div class="col-xs-12 col-sm-2 col-md-2 col-lg-1"><div class="profile-img-md radius50"><img src="<?php echo $bd['bidImg']; ?>" /></div></div>
								<div class="col-xs-12 col-sm-10 col-md-10 col-lg-11 p0-xs">
									<div class="dash-bid-cont col-xs-12 col-sm-12 col-md-12 col-lg-6">
										<h2><?php echo $bd['jobname']; ?></h2>
										<p class="dash-description"><?php echo ( strlen( $bd['jobdescription'] ) > 100 ) ? substr(nl2br(stripslashes($bd['jobdescription'])),0,100)."..." : nl2br(stripslashes($bd['jobdescription'])); ?></p>
										<h4><?php echo $expertises[$bd['expertiseid']]['name']; ?>  <span>| <?php echo date('m/d/Y',strtotime($bd['startdate'])); ?> </span> </h4>
									</div>

	                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-4 pr0 pl0">
										<?php if( $bd['numberofbids'] > 0 ): ?>
											<span class="bid-price bid-avg">$<?php echo number_format($bd['averagebid'],2,'.',''); ?></span> <span class="bid-price bid-low">$<?php echo number_format($bd['lowestbid'],2,'.',''); ?></span>  <span class="bid-price bid-high">$<?php echo number_format($bd['highestbid'],2,'.',''); ?></span>
										<?php endif; ?>
	                </div>
	                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 p0">
	                  <div class="postbid-count radius50 mt25-xs"><?php echo $bd['numberofbids']; ?></div>
	                </div>

								</div>
							</a>
						<?php endforeach; ?>
					<?php else: ?>
						<?php foreach( $bidDets as $bt => $bd ): ?>
							<div class="dash-bids clearfix">
								<div class="col-xs-12 col-md-12 col-lg-6">
									<a href="<?php echo base_url('worklist/'.$bd['jobkey']); ?>">
										<div class="profile-img-md radius50 col-xs-12 col-sm-4 p0"><img src="<?php echo $bd['bidImg']; ?>" /></div>
										<div class="dash-bid-cont col-xs-12 col-sm-8 p0">
											<h2><?php echo $bd['jobname']; ?></h2>
											<h4>Bid Amount : <span class="big-amount">$<?php echo number_format($bd['bidamount'],2, '.', ''); ?></span></h4>
											<h3><?php echo nl2br(stripslashes($bd['jobdescription'])); ?></h3>
											<h4><?php echo $expertises[$bd['expertiseid']]['name']; ?>  <span>| <?php echo date('m/d/Y',strtotime($bd['startdate'])); ?> </span> </h4>
										</div>
									</a>
								</div>
								<div class="col-xs-12 col-md-12 col-lg-6 mt20-sm">
									<div class="col-sm-6 pl5 p0-xs">
										<a class="profile-name-sm" href="javascript:void(0);">
											<span class="profile-img-sm radius50">
												<img id="contrctorimgid_<?php echo $bd['jobkey']; ?>_<?php echo $bd['contractorDets']['userkey']; ?>" src="<?php echo $bd['contractorDets']['Img']; ?>">
											</span>
											<h2><?php echo $bd['contractorDets']['firstname']." ".$bd['contractorDets']['lastname'][0]; ?></h2>
											<p><?php echo $bd['contractorDets']['city']; ?> <?php echo $bd['contractorDets']['state']; ?>, <?php echo $bd['contractorDets']['zip']; ?></p>

											<?php for( $re=1;$re<=5;$re++){ ?>
												<i class="stars <?php if( $re <= $bd['contractorDets']['staring'] ): ?>active<?php endif; ?>"></i>
											<?php } ?>
										</a>
									</div>
									<?php if( $bidtype == 'working' ): ?>
										<div class="col-sm-6 p0 clearfix">
											<?php if( $bd['jobstatus'] == 'completed' ): ?>
												<a href="javascript:void(0)" onclick="rateJob('<?php echo $bd['jobkey']; ?>','<?php echo $bd['contractorDets']['userkey']; ?>')"  class="btn-green-accept minor-adjs" title="Accept Completion and Release Payment">Accept Completion and Release Payments</a>
											<?php else: ?>
												<?php if( !empty($bd['contracts']) and $bd['contracts']['workeragree'] == '0'): ?>
													<p class="pendingalert">Pending Contractor Approval</p>
												<?php elseif(!empty($bd['contracts']) and $bd['contracts']['bt_transaction_id'] == ''): ?>
												<?php if($bd['contractorDets']['bt_accountverified'] =='1' and $bd['contractorDets']['bt_merchantid'] !=''): ?>
												<a href="<?php echo base_url('reviewcontract/'.$bd['contracts']['contractkey']); ?>"   class="btn-green-accept" title="Make Payment">Make Payment</a>
												<?php else: ?>
												<a onclick="openDialog('Banking Information Missing','The contractor has not yet connected a bank account to receive payments in EFynch. You can initiate the payment only after they enter the banking information in their profile.')"   class="btn-green-accept" title="Make Payment">Make Payment</a>
												<?php endif; ?>
												<?php endif; ?>
											<?php endif; ?>
										</div>
									<?php else: ?>
										<div class="col-sm-4 pr5 date-wraps pl0-xs"> <p>Completed on</p> <h5><?php echo date('m/d/Y',strtotime($bd['completeddate'])); ?></h5></div>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
			<?php else: ?>
				<div class="alert alert-danger">No Records Found</div>
			<?php endif; ?>
        </div>
        <div class="clear"></div>
    </div>
</section>
<!--Wrapper Starts from left menu-->
</div>
