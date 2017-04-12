<script>
function shorListJob(bidkey){
	if( $("#userbid_"+bidkey).hasClass('usercls_short') ){
		var toFav = 0;
	}else{
		var toFav = 1;
	}

	$.post('<?php echo base_url('favouriteBid'); ?>',{
	   act		: 'fav',
	   key		: bidkey,
	   tofav	: toFav
	},function (data){
		if( data == 'success' ){
			if( $("#userbid_"+bidkey).hasClass('usercls_short') ){
				$("#userbid_"+bidkey).removeClass('usercls_short');
				$("#fav_"+bidkey).removeClass('active');
				if( $(".mytabs.active").attr('data') != 'All' ){
					$("#userbid_"+bidkey).hide();
				}
			}else{
				$("#userbid_"+bidkey).addClass('usercls_short');
				$("#fav_"+bidkey).addClass('active');
			}
		}
	});
  if(toFav == 1){
    alert("Contractor has been shortlisted for this job");
    location.reload();
  }
}

function listUsers(ths,type){
	$(".mytabs").removeClass('active');
	$(ths).addClass('active');
	$(".usercls").show();
   $(".shrterr").remove();
	if( type == 'short' ){
		$(".usercls").hide();
		$(".usercls_short").show();

    if($(".usercls_short").length < 1){
        $("#shoort").append('<div class="col-xs-12"><p class="alert alert-danger shrterr mt10">No Records Found.</p></div>');
    }
	}else{
    if($(".usercls").length < 1){

        $("#shoort").append('<div class="col-xs-12"><p class="alert alert-danger shrterr mt10">No Records Found.</p></div>');
    }

  }
}

function showbidinfo(bidkey){
  $("#contractor-modal").html('loading..');
  /*var html = '<div class="col-sm-12">'+
                            '<div class="clear"></div>'+
                            '<p>Amrutha S<img></p>'+
                            '<p>Bid Description</p>'+
                            '<p>$100.00</p>'+
                            '<div class="clear"></div>'+
                          '</div>';

              $("#contractor-modal").html(html);*/
  $.post('<?php echo base_url('showbidinfo'); ?>',{
       bidkey      : bidkey

    },function (data){
        if( data.success ){
              var html = '<div class="col-sm-12">'+
														'<img class="bidinfpop" src="'+data.image+'">'+
                            '<h4 class="mb10">'+data.username+'</h4>'+
                            '<h5 class="mb10">'+data.location+'</h5>'+
														'<div class="mb15">Bid Amount <h3 class="bidinfamount"> $'+data.amount+'</h3></div>'+
														'<div class="clear">'+'</div>'+
                            '<div class="col-xs-6 mb15 pl0">Additional Amount <h4> $'+data.addamount+'</h4></div>'+
                            '<div class="col-xs-6 mb15 pl0">Start Date <h4> '+data.startingtime+'</h4></div>'+
														'<div class="clear">'+'</div>'+
                            '<div class="col-xs-6 mb15 pl0">Expected Time <h4> '+data.expectedtime+'</h4></div>'+
                            '<div class="col-xs-6 mb15 pl0">Max Time <h4> '+data.maximumtime+'</h4></div>'+
														'<div class="clear">'+'</div>'+
                            '<p>'+data.description+'</p>'+
                          '</div>';

              $("#contractor-modal").html(html);
        }
    },"json");
}
</script>
<?php if($right == 'dashboard'){
  if(!empty($activities)){

?>

<div class="col-sm-3 col-md-3 col-lg-3 pl0 pr0 res-right-side">
  <div class="slide-right toright"> <i></i></div>
    <div class="slide-right toleft"  style="display:none;"><i></i></div>
  <div class="recent-activity divheight">
      <div class="ash-bar"><p>Recent Activity</p></div>
      <?php foreach($activities as $as){
       //print "<pre>"; print_r($as); print "</pre>";
        ?>
        <a href="<?php echo base_url(); ?>profile/<?php echo $as['userkey']?>" class="job-row clearfix">
          	<span class="profile-img-sm radius50 col-xs-2 p0"><img src="<?php echo $as['userimege']; ?>" /></span>
           	<h3><?php echo $as['text']; ?> </h3>
            	<span class="rec-activity-date"><?php echo date('m/d/Y',$as['createdate']); ?></span>
        </a>
        <div class="clear"></div>
        <?php

      } ?>

    </div>
</div>

<?php
  }
}else if($right == 'bidding'){
  ?>

  <div class="col-sm-3 col-md-3 col-lg-3 pl0 pr0 res-right-side">
  <div class="slide-right toright"> <i></i></div>
    <div class="slide-right toleft"  style="display:none;"> <i></i></div>
  <div class="recent-activity divheight pb10">
      <div class="ash-bar"><p>HOMEOWNER INFO</p></div>

        <div class="award-work-wrap clearfix">
          <div class="col-sm-8 pl5">
              <span class="profile-img-sm radius50"><img src="images/img-profile.jpg"></span> <h2 class="pt10">Williams John </h2>
             </div>
          <div class="col-sm-4"> <i  class="icon-msghim FR" data-toggle="tooltip" data-placement="top" title="Message"></i>   </div>

            <div class="col-sm-12 left-profile-details pb10">
                <p class="icons-lpd email">richard@gmail.com</p>
                <p class="icons-lpd address">123 Church Road</p>
                <p class="icons-lpd suit">Suite 500</p>
                <p class="icons-lpd zip">Baltimore, MD 21117</p>
            </div>
            <div class="col-xs-12 award-bottom"> <p class="FL">Bid Amount</p> <h3  class="FR">$500.00</h3>  </div>
        </div>
    </div>
</div>

<?php
}else if($right == 'bidnow'){ ?>

<div class="col-sm-3 col-md-3 col-lg-3 pl0 pr0 res-right-side">
  <div class="slide-right toright"> <i></i></div>
    <div class="slide-right toleft"  style="display:none;"> <i></i></div>
  <div class="recent-activity divheight pb10">

    <?php if($showbidnow == "1"): ?>
        <?php if( !empty( $bidDets ) ): ?>


            <?php if( $homenowneragree == '1' ): ?>
          <div class="col-sm-12 FN m0auto mt30 mb20">
            <a class="submit100-login radius30p pt15" href="<?php echo base_url('agreecontract/'.$contract['contractkey']); ?>" >Sign Contract</a>
          </div>
          <?php else:
            if($showalert == '1'){
              ?>
              <div class="alert alert-danger">You already have submitted a bid for this job.</div>
            <?php } ?>

          <?php endif; ?>
          <?php else: ?>
          <div class="col-sm-12 FN m0auto mt30" style="margin-bottom:15px;margin-top:0px;">
            <?php /* Payment Comment ?>
          <a class="submit100-login radius30p pt15" href="<?php echo base_url('bidnow/'.$jobDets['jobkey']); ?>">Bid Now</a>
          <?php */ ?>

          <?php //if($userDets['bt_merchantid'] != ""){
            ?>
            <a class="submit100-login radius30p pt15" href="<?php echo base_url('bidnow/'.$jobDets['jobkey']); ?>">Bid Now</a>
            <?php
          /*}else{
            ?>
            <a class="submit100-login radius30p pt15" onclick="alert('Please complete the details in your My Profile section to bid for this job')">Bid Now</a>
          <?php
          } */?>



          </div>

          <?php endif; ?>
        <?php endif; ?>

      <div class="ash-bar"><p>Bid Overview</p></div>

        <div class="award-work-wrap clearfix">
          <div class="col-xs-12 biddetails pl0 pr0">
              <div class="col-sm-6"><h3>Number of bids</h3></div>
                <div class="col-sm-6"><p><?php echo $bidCountDets['numberofbids']; ?></p></div>
            </div>
            <div class="col-xs-12 biddetails pl0 pr0">
              <div class="col-sm-6"><h3>Highest Bid</h3></div>
                <div class="col-sm-6"><p>$ <?php echo number_format($bidCountDets['highestbid'],2); ?></p></div>
            </div>
            <div class="col-xs-12 biddetails pl0 pr0">
              <div class="col-sm-6"><h3>Lowest Bid</h3></div>
                <div class="col-sm-6"><p>$ <?php echo number_format($bidCountDets['lowestbid'],2); ?></p></div>
            </div>
            <div class="col-xs-12 biddetails pl0 pr0">
              <div class="col-sm-6"><h3>Average</h3></div>
                <div class="col-sm-6"><p>$ <?php echo number_format($bidCountDets['averagebid'],2); ?></p></div>
            </div>
        </div>

        <div class="ash-bar"><p>All Bids</p></div>

		<?php if( !empty( $bidUsers ) ):
				foreach( $bidUsers as $ub => $uy ): ?>
					<div class="award-work-wrap clearfix">
					  <div class="col-sm-12 pl5">
						  <span class="profile-img-sm radius50"><img src="<?php echo $uy['imageurl']; ?>"></span> <h2><?php echo $uy['firstname']." ".$uy['lastname'][0]; ?> </h2>
							<?php for( $re=1;$re<=5;$re++){ ?>
								<i class="stars <?php if( $re <= $uy['staring'] ): ?>active<?php endif; ?>"></i>
							<?php } ?>
						 </div>
					</div>
		<?php endforeach; endif; ?>



    </div>
</div>


<?php }else if($right == 'workdetails'){
?>

<div class="col-sm-3 col-md-3 col-lg-3 pl0 pr0 res-right-side">
  <div class="slide-right toright"> <i></i></div>
    <div class="slide-right toleft"  style="display:none;"> <i></i></div>
  <div class="recent-activity divheight pb10">
      <div class="ash-bar"><p>HOMEOWNER INFO </p></div>

        <div class="award-work-wrap clearfix">
          <div class="col-sm-8 pl5">
              <span class="profile-img-sm radius50"><img src="images/img-profile.jpg"></span> <h2>Williams John </h2>
             </div>
          <div class="col-sm-4"> <i class="icon-msghim FR" data-toggle="tooltip" data-placement="top" title="Message"></i>   </div>

            <div class="col-sm-12 left-profile-details pb10">
              <h5 class="completed-dates">Completed on <span>05/12/2016</span></h5>
                <p class="icons-lpd email">richard@gmail.com</p>
                <p class="icons-lpd address">123 Church Road</p>
                <p class="icons-lpd suit">Suite 500</p>
                <p class="icons-lpd zip">Baltimore, MD 21117</p>
            </div>
            <div class="col-xs-12 award-bottom"> <p class="FL">Bid Amount</p> <h3  class="FR">$500.00</h3>  </div>
             <div class="col-xs-12 award-bottom"> <p class="FL">Completed on</p> <h4  class="FR">05/12/2016</h4>  </div>
        </div>



    </div>
</div>


<?php
}else if( $right == 'postjob' ){  ?>
<?php /* ?>
 <div class="col-sm-3 col-md-3 col-lg-3 pl0 pr0 res-right-side">
              <div class="slide-right toright"> <i></i></div>
              <div class="slide-right toleft"  style="display:none;"><i></i></div>
            	<div class="recent-activity divheight pb10">
                	<div class="ash-bar"><p>HELP</p></div>

                    <div class="help-right-box clearfix">
                    	<div class="col-xs-12">
                        	<img src="../images/icon-help.png" />
                    		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                        </div>
                        <div class="col-xs-12 help-right-bottom">
                        	<div class="col-sm-12 FN m0auto"><a class="btn-green-sm" href="#">Tips For Posting</a></div>
                        </div>
                    </div>





                </div>
            </div>
<?php */ ?>

<?php }else if( $right == 'worklist' ){ ?>
	<div class="col-xs-12 col-sm-12 col-md-6 pl0 pr0 jobinfo">
		<div class="recent-activity divheight pb10" id="shoort">
			<div class="ash-bar clearfix p0">
				<div class="col-xs-6 col-sm-6 p0"> <a href="javascript:void(0)" onclick="listUsers(this,'all')" data="All" class="a-tabs-ash mytabs active">All Bids</a> </div>
				<div class="col-xs-6 col-sm-6 p0"> <a href="javascript:void(0)" onclick="listUsers(this,'short')" data="short" class="a-tabs-ash mytabs">Shortlisted </a></div>
			</div>

			<?php
      if(!empty($allUsers)){
      foreach( $allUsers as $au => $ua ): ?>
				<div class="award-work-wrap clearfix usercls <?php if( $ua['isshortlisted'] == 1 ): ?>usercls_short<?php endif; ?> " id="userbid_<?php echo $ua['bidkey']; ?>">
					<div class="col-xs-8 pl5" >
						<div class="profile-img-sm radius50">
							<a title="view profile" href="<?php echo base_url(); ?>profile/<?php echo $ua['userkey']?>">
								<img src="<?php echo $ua['imageurl']; ?>"> 
							</a>
							</div>
						<div class="FL">
							<a title="view profile" href="<?php echo base_url(); ?>profile/<?php echo $ua['userkey']?>">
							<h2><?php echo $ua['firstname']." ".$ua['lastname'][0]; ?> </h2>
							</a>
	            <p class="icons-lpd zip"><?php echo $ua['city']; /*if($ua['state'] !="" or $ua['zip'] != ""){ ?>,<?php } ?> <?php echo $ua['state']; ?> <?php echo $ua['zip'];*/ ?></p>
							<?php for($r=1;$r<=5;$r++){ ?>
								<i class="stars <?php if( $r <= $ua['staring'] ): ?>active<?php endif; ?>"></i>
							<?php } ?>
						</div>
					 </div>
					<div class="col-xs-4 p0-xs">
						<i data-toggle="tooltip" data-placement="top" title="Shortlist" onclick="shorListJob('<?php echo $ua['bidkey']; ?>')" id="fav_<?php echo $ua['bidkey']; ?>" class="FR icon-shorlist <?php if( $ua['isshortlisted'] == 1 ): ?>active<?php endif; ?>"></i>
						<a class="FR" href="<?php echo base_url('messages/'.$jobDets['jobkey'].'/'.$ua['userkey']); ?>"><i data-toggle="tooltip" data-placement="top" title="Message" class="icon-msghim"></i></a>
						<a class="bidinfo-pop" data-toggle="modal" data-target="#contractordetails" onclick="showbidinfo('<?php echo $ua['bidkey']; ?>')">Bid Info</a>
          </div>

					<div class="col-xs-12 award-bottom"> <h3>$<?php echo number_format($ua['bidamount'],2); ?></h3> <?php if( $isawarded == 0 ): ?><a href="<?php echo base_url('awardwork/'.$ua['userkey'].'/'.$jobDets['jobkey']); ?>" class="btn-award">Sign Contract</a><?php endif; ?> </div>
				</div>
			<?php

      endforeach;
    }else{
      ?>
			<div class="col-xs-12"><p class="alert alert-danger shrterr mt10">No Records Found.</p></div>
      <?php
    }
      ?>

		</div>
	</div>
<?php }else if( $right == 'contractinfo' ){ ?>
	<div class="col-xs-12 col-sm-12 col-md-6 pl0 pr0">
			<div class="recent-activity divheight pb10">
				<div class="ash-bar"><p>CONTRACTOR INFO</p></div>

				<div class="award-work-wrap clearfix">
					<div class="col-xs-12 col-sm-6 pl5">
						<a class="profile-img-sm radius50" data-toggle="modal" data-target="#contractordetails" onclick="showbidinfo('<?php echo $userDets['bidDets']['bidkey']; ?>')"><img src="<?php echo $userDets['Img']; ?>"></a> <h2><?php echo $userDets['firstname']." ".$userDets['lastname'][0]; ?> </h2>
						<?php for($i=1;$i<=5;$i++){ ?>
							<i class="stars <?php if( $i <= $userDets['staring'] ): ?>active<?php endif; ?>"></i>
						<?php } ?>
					</div>
					<div class="col-xs-12 col-sm-6">
            <a href="<?php echo base_url('messages/'.$jobDets['jobkey'].'/'.$userDets['userkey']); ?>"><i class="icon-msghim FR" data-toggle="tooltip" data-placement="top" title="Message"></i></a>
            <?php if($isawarded == '1'): ?>
            <a class="backbuton" href="<?php echo base_url('agreecontract/'.$contract['contractkey']); ?>">View Contract</a>
          <?php endif; ?>
          </div>

					<div class="col-sm-12 left-profile-details pb10">
						<p class="icons-lpd email"><?php echo $userDets['email']; ?></p>
            <?php if($isawarded == '1'): ?>
						<p class="icons-lpd address"><?php echo ( $userDets['address'] != '' ) ? $userDets['address'] : '--'; ?></p>
            <?php endif; ?>
						<p class="icons-lpd zip"><?php echo $userDets['city']; ?>, <?php echo $userDets['state']; ?> <?php echo $userDets['zip']; ?></p>
					</div>
					<div class="col-xs-12 award-bottom"> <p class="FL">Bid Amount</p> <h3  class="FR">$<?php echo number_format($userDets['bidDets']['bidamount'],2); ?></h3>  </div>
				</div>
        <?php if($isawarded != '1'): ?>
        <?php
          $attributes = array('id' => 'awards');
          echo form_open(base_url('awardwork/'.$userDets['userkey'].'/'.$jobDets['jobkey']), $attributes);
        ?>
          <div class="col-xs-10 col-sm-7 col-md-8 col-lg-6 FN m0auto mt30">
            <a class="submit100-login radius30p pt15" href="javascript:void(0)" onclick="submitAward()" >Sign Contract</a>
            <input type="hidden" name="act" value="1"/>
          </div>
        </form>
         <?php endif; ?>
         <?php if($waitingcontractorapproval == '1'): ?>
         <div class="col-xs-12 alert alert-danger">Work awarded. Awaiting Contractor Approval.</div>
       <?php endif; ?>
			</div>
        </div>
<?php } ?>



<div class="modal fade bs-example-modal-lg" id="contractordetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Bid Info</h4>
    </div>
    <div class="modal-body clearfix" id="contractor-modal">

    </div>
  </div>
</div>
</div>
