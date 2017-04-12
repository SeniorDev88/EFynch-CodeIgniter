<section class="right-side divheight">
	<div class="container-right pr0 pl0">
		<?php
      $this->load->view('headers/dashboard_menu');
?>
<script>
$(document).ready(function(){
  $(".sort").click(function(){
    var dataname = $(this).attr('data').split("_");
    if(dataname[0] == 'date'){
    	sortbydate(dataname[1]);
    }else{
    	sortbyname(dataname[1]);
    }

  });
});

function sortbyname(sortorder){
	if(sortorder == "asc"){
		var alphabeticallyOrderedDivs = $(".sortres").sort(function (a, b) {
	        return ($(b).find(".result_name").text().toLowerCase()) < ($(a).find(".result_name").text().toLowerCase()) ? 1 : -1;
	      });
	}else{
		var alphabeticallyOrderedDivs = $(".sortres").sort(function (a, b) {
        return ($(b).find(".result_name").text().toLowerCase()) > ($(a).find(".result_name").text().toLowerCase()) ? 1 : -1;
      });
	}
	$("#jobdiv").html(alphabeticallyOrderedDivs);
}
function getsubstring(str){
	return str.split("| ")[1];
}
function sortbydate(sortorder){
	if(sortorder == "asc"){
		var alphabeticallyOrderedDivs = $(".sortres").sort(function (a, b) {
	        //return ($(b).find(".result_name").text()) < ($(a).find(".result_name").text()) ? 1 : -1;
	        var first = getsubstring($(a).find(".result_date").text());
	        var sec = getsubstring($(b).find(".result_date").text());

	        return (sec < first) ? 1 : -1;
	      });
	}else{
		var alphabeticallyOrderedDivs = $(".sortres").sort(function (a, b) {
        	var first = getsubstring($(a).find(".result_date").text());
	        var sec = getsubstring($(b).find(".result_date").text());

	        return (sec > first) ? 1 : -1;
      });
	}
	$("#jobdiv").html(alphabeticallyOrderedDivs);

}
</script>
		<div class="col-sm-12">
			<div class="col-md-12 col-lg-4"><h1>Search Results <?php echo isset($_GET['search']) ?  'of "'.$_GET['search'].'"' : ''; ?></h1></div>
			<div class="col-md-12 col-lg-8 clearfix">
					<div class="sort-panel-box">
						<p>Sort By</p>
						<div class="jobname-box">
							<label>Job Name</label>
							<a data="name_asc" class="sort sort_top"></a><a data="name_desc" class="sort sort_bottom"></a>
						</div>
						<div class="jobname-box">
							<label>Date</label>
							<a class="sort sort_top" data="date_asc"></a><a class="sort sort_bottom" data="date_desc"></a>
						</div>
					</div>
          <div class="bid-types tc">
						<span class="bid-price bid-avg">Avg.</span> <span class="bid-price bid-low">Low</span>  <span class="bid-price bid-high">High</span>
					</div>
				</div>
			<div class="clear"></div>
			<div id="jobdiv">
			<?php if( !empty( $jobs ) ): ?>
				<?php foreach( $jobs as $jk => $jy ): ?>

					<a href="<?php echo base_url('bidjob/'.$jy['jobkey']); ?>" class="sortres dash-bids clearfix">


						<div class="col-xs-12 col-md-12 col-lg-6">
							<div class="profile-img-md radius50 col-xs-12 col-sm-4 p0"><img src="<?php echo $jy['bidImg']; ?>" /></div>
							<div class="dash-bid-cont col-xs-12 col-sm-8 p0">
								<h2><?php echo $jy['jobname']; ?></h2>
								<h4>Budget : $<?php echo number_format(round($jy['budget'],2),2); ?></h4>
								<?php if(!$jobbids){
									?>
									<h4>My Bid : $<?php echo number_format(round($jy['bidamount'],2),2); ?></h4>
								<?php
								} ?>
								<?php if($jy['bidplaced'] == 1): ?><h4> Bid Placed</h4><?php endif; ?>
								<h3><?php echo ( strlen( $jy['jobdescription'] ) > 100 ) ? substr(nl2br(stripslashes($jy['jobdescription'])),0,100)."..." : nl2br(stripslashes($jy['jobdescription'])); ?></h3>
								<h4 class="result_name"><?php echo $expertises[$jy['expertiseid']]['name']; ?>  <span class="result_date">| <?php echo date('m/d/Y',strtotime($jy['startdate'])); ?> </span> </h4>
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
		</div>
		<div class="clear"></div>
	</div>
</section>
<!--Wrapper Starts from left menu-->
</div>
