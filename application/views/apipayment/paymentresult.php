<section class="right-side divheight">
	<div class="container-right">
		<div class="col-xs-12 pl0 pr0">
			<?php if($status == "success"){ ?>
			<div class="col-xs-12">
				<div class="paymnt-sucfail-wrp">
					<div class="paymnt-resul-imgwrp">
						<img src="<?php echo base_url('images/sucss.png') ?>">
					</div>
					<h2>SUCCESS!</h2>
					<p>Your payment has been completed.</p>
				</div>
				<?php if($type == 'web'): ?>
				<div class="col-xs-12">
					<a class="paymntbtn" href="<?php echo base_url('owner/bidding') ?>">Proceed </a>
				</div>
			<?php endif; ?>
			</div>


			
				
			<?php }else{ ?>

			<div class="col-xs-12">
				<div class="paymnt-sucfail-wrp">
					<div class="paymnt-resul-imgwrp">
						<img src="<?php echo base_url('images/error.png') ?>">
					</div>
					<h2>ERROR!</h2>
					<p>Sorry. Your payment failed.</p>
				</div>
				<div class="col-xs-12">
					<a class="paymntbtn" href="<?php echo base_url('reviewcontract/'.$key) ?>">Proceed </a>
				</div>
			</div>
			
			<?php } ?>
		</div>
		<div class="clear"></div>
	</div>
</section>
<div class="clear"></div>
</div>