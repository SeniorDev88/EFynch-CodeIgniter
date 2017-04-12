<script>
$(document).ready(function(){
	$(".timepick").datetimepicker({ format: 'LT', ignoreReadonly: true });
	$(".datepick").datetimepicker({ format: 'MM/DD/YYYY', ignoreReadonly: true });

	$("#bidnow").validate({
		rules: {
			//description		: 'required',
			bidamount		: {
				required	: true,
				number		: true
			},
			/*additionalamount	: {
				required	: true,
				number		: true
			},
			startdate	: {
				required	: true,
				date		: true
			},
			starttime   : 'required',
			exptime 	: {
				required	: true,
				number		: true,
				noDecimal	: true
			},
			exptype		: 'required',
			maxtime 	: {
				required	: true,
				number		: true,
				noDecimal	: true
			},
			maxtype		: 'required' */
		},
		messages: {
			description		: 'Please enter Project Description',
			bidamount	:  {
				required	: 'Please enter Bid Amount',
				number		: 'Please enter a valid Amount'
			},
			additionalamount	:  {
				required	: 'Please enter Additional Amount',
				number		: 'Please enter a valid Amount'
			},
			startdate	:  {
				required	: 'Please enter Start Date',
				date		: 'Please enter a valid Date'
			},
			starttime	: 'Please enter Start Time',
			exptime		: {
				required	: 'Please enter Expected Time',
				number	 	: 'Please enter a valid Time'
			},
			exptype		: 'Please select Expected Time',
			maxtime		: {
				required	: 'Please enter Maximum Time',
				number	 	: 'Please enter a valid Time'
			},
			maxtype		: 'Please select Maximum Time'
		}
	});

	jQuery.validator.addMethod("postalcode", function (postalcode, element) {
		return this.optional(element) || postalcode.match(/(^\d{5}(-\d{4})?$)|(^[ABCEGHJKLMNPRSTVXYabceghjklmnpstvxy]{1}\d{1}[A-Za-z]{1} ?\d{1}[A-Za-z]{1}\d{1})$/);
	});

	jQuery.validator.addMethod("noDecimal", function(value, element) {
		return !(value % 1);
	}, "Please enter a valid number");
});

var servicefee = <?php echo SERVICEFEE; ?>;
$(document).ready(function(){
	$("#bidamount").on("keyup",function(){
		var amt = $(this).val();
		var htm = amt * servicefee / 100 ;
		var n = htm.toFixed(2);
		var result = amt - n;
		result = result.toFixed(2);
		$("#servfee").html("$"+n);
		$("#servrec").html("$"+result);
	});
});
</script>

    <section class="right-side divheight">
    	<div class="container-right">
<?php
      $this->load->view('headers/dashboard_menu');
?>
            <?php
				$attributes = array('id' => 'bidnow');
				echo form_open(base_url('editbid/'.$bidkey), $attributes);
			?>
				<div class="col-sm-12 col-md-10 col-lg-9 pl0 pr0">
					<div class="col-xs-12"><h1>Bid Now</h1></div>
					<div class="col-xs-12 clearfix p0">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8"><textarea style="height:129px;" class="input100-login radius30p" name="description" placeholder="Detailed Project Description"><?php echo $bidDets['description']; ?></textarea>
							<p class="eg-text">Including materials, permits and relevant information.</p>
						</div>
					</div>

					<div class="col-xs-12 clearfix p0">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
							<div style="position:relative;">
								<p class="dolor-input">$</p>
								<input class="input100-login radius30p pl50" id="bidamount" name="bidamount" type="text" placeholder="Bid Amount" value="<?php echo $bidDets['bidamount']; ?>"/>
							</div>
							<p class="eg-text pazh-red"><?php echo SERVICEFEE; ?>% fee will be assessed for transaction processing, security, and improvement. Please include this in your bid.
								{bidamount} - <?php echo SERVICEFEE; ?>% Fee = total earning
							</p>

						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 pt10">
							<p class="col-xs-12 col-sm-6 pazh-style">Efynch Service Fee : <span id="servfee" class="pazh-red">$<?php echo $servicefee; ?></span></p>
							<p class="col-xs-12 col-sm-6 pazh-style">You will receive : <span id="servrec" class="pazh-green">$<?php echo $amountreceive; ?></span></p>
						</div>

					</div>

					<div class="col-xs-12 clearfix p0">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
							<div style="position:relative;">
								<p class="dolor-input">$</p>
								<input class="input100-login radius30p pl50" name="additionalamount" type="text" placeholder="Additional Amount Per Hour" value="<?php echo $bidDets['additionalamount']; ?>"/>
							</div>
						<p class="eg-text">If additional materials and/or time is required, the rate for these materials shall (per hour)</p></div>

					</div>

					 <div class="col-xs-12 clearfix p0">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><input class="input100-login radius30p datepick" name="startdate" type="text" placeholder="Start Date" value="<?php echo ($bidDets['startdate'] != '0000-00-00') ? date('m/d/Y',strtotime($bidDets['startdate'])) : ''; ?>"/></div>
					   <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><input class="input100-login radius30p timepick" type="text" placeholder="Start Time" name="starttime" value="<?php echo ($bidDets['starttime'] != '00:00:00') ? date('g:i A',strtotime($bidDets['starttime'])) : ''; ?>"/></div>
					</div>

					<div class="col-xs-12 clearfix p0">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><input class="input100-login radius30p" type="text" placeholder="Expected Time to Complete" name="exptime" value="<?php echo $bidDets['exptime']; ?>"/></div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
							<div class="select-login">
								<select name="exptype">
									<option value="h" <?php if( $bidDets['exptype'] == 'h' ): ?>selected<?php endif; ?>>Hours</option>
									<option value="d" <?php if( $bidDets['exptype'] == 'd' ): ?>selected<?php endif; ?>>Days</option>
									<option value="w" <?php if( $bidDets['exptype'] == 'w' ): ?>selected<?php endif; ?>>Weeks</option>
								</select>
							</div>
						</div>
					</div>

					<div class="col-xs-12 clearfix p0">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4"><input class="input100-login radius30p" type="text" placeholder="Maximum Time to Complete" name="maxtime" value="<?php echo $bidDets['maxtime']; ?>"/></div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
							<div class="select-login">
								<select name="maxtype">
									<option value="h" <?php if( $bidDets['maxtype'] == 'h' ): ?>selected<?php endif; ?>>Hours</option>
									<option value="d" <?php if( $bidDets['maxtype'] == 'd' ): ?>selected<?php endif; ?>>Days</option>
									<option value="w" <?php if( $bidDets['maxtype'] == 'w' ): ?>selected<?php endif; ?>>Weeks</option>
								</select>
							</div>
						</div>
					</div>

					<div class="col-xs-12 clearfix p0">
						<div class="col-sm-12 col-md-8">
							<div class="col-sm-6 FN m0auto mt30"><input class="submit100-login radius30p" type="submit" value="Bid Now" /></div>
						</div>
					</div>
				</div>
				<input type="hidden" name="act" value="1">
			</form>
			<div class="clear"></div>
		</div>
	</section>
<div class="clear"></div>
</div>
