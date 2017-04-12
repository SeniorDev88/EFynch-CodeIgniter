<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
$(document).ready(function(){
  $("#contactid").validate({
	 ignore : [],
    rules :{
      firstname : 'required',
      lastname : 'required',
      email : {
        required : true,
        email : true
      },
      phone : { required : true, phoneUS : true } ,
      comments : 'required',
	  hiddenRecaptcha : {
		   required: function() {
			   if( grecaptcha.getResponse() == '') {
				   return true;
			   } else {
				   return false;
			   }
		   }
		}
    },
    messages : {
      firstname : 'Please enter First Name',
      lastname : 'Please enter Last Name',
      email : { required : 'Please enter Email',  email : 'Please enter a valid Email' },
      phone : { required : 'Please enter Phone Number',  phoneUS : 'Please enter a valid Phone Number' },
      comments : 'Please enter Comments',
	  hiddenRecaptcha  : 'Please verify your Identity'
    }
  });

  jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
      phone_number = phone_number.replace(/\s+/g, ""); 
      return this.optional(element) || phone_number.length > 9 &&
        phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
    });
});

function recaptchaCallback(){
	$('#contactid').valid("#hiddenRecaptcha");
}
</script>
<?php
$publickey = GOOGLE_CAPTCHA_KEY;
$privatekey = GOOGLE_CAPTCHA_SECRET;
?>

<?php if ( (isset ($haserror ) and $errormsg!='') or ((isset ($success ) and $successmsg!='') ) ){
  ?>
  <script>
  $(document).ready(function(){
    if($('.msg-pop').css("display") == "block"){
      setTimeout(function(){
        $('.msg_overlay, .msg-pop').hide();
      }, 4000);
    }

  });
  </script>
<?php
} ?>


  <section class="service-section">
    <div class="inside-banner"><p>Contact Us</p></div>
	<?php  if ( (isset ($haserror ) and $errormsg!='') ): ?><div class="msg_overlay"></div><div class="alert msg-danger msg-pop"><?php echo $errormsg; ?></div><?php endif; ?>
	<?php  if ( (isset ($success ) and $successmsg!='') ): ?><div class="msg_overlay"></div><div class="alert msg-success msg-pop"><?php echo $successmsg; ?></div><?php endif; ?>
    <div class="container p0">
        <div class="col-xs-12 mb15"><h1><span class="orange">EFynch</span> welcomes your valuable <span class="orange">input</span>  and <span class="orange">questions</span>. Please let us know what you are <span class="orange">thinking </span>and<span class="orange"> help us </span>grow.</h1></div>
        <?php
			$attributes = array('id' => 'contactid');
			echo form_open(base_url('contactus'), $attributes);
		?>
        <div class="col-xs-10 m0auto FN clearfix">
          <div class="col-xs-12 col-sm-6 mb20">
            <p class="box-title">First Name</p>
            <div class="frname">
              <input type="text" name="firstname" value="" class="form-control"/>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6  mb20">
            <p class="box-title">Last Name</p>
            <div class="lsname">
              <input type="text" name="lastname" value=""  class="form-control"/>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 mb20">
            <p class="box-title">Email</p>
            <div class="ema">
              <input type="text" name="email" value=""  class="form-control"/>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6  mb20">
            <p class="box-title">Phone Number</p>
            <div class="phnum">
              <input type="text" name="phone" value=""  class="form-control"/>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6  mb20">
            <p class="box-title">Comments</p>
            <div class="comme">
              <textarea class="form-control" name="comments" ></textarea>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 mb20 pt30 pt10-xs">
            <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="<?php echo $publickey;  ?>" data-theme="light"></div>
			<input type="hidden" name="hiddenRecaptcha" id="hiddenRecaptcha">
          </div>
        </div>
        <div class="row clearfix">
          <div class="col-xs-12">
            <input type="hidden" name="act" value="contact">
            <input type="submit" value="Submit" class="submit-btn"/>
          </div>
        </div>
      </form>
    </div>
  </section>
