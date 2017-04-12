<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
$(document).ready(function(){
	$("#fpwd").validate({
		rules: {
			email	: {
				required: true,
				email: true
			}
		},
		messages: {
			email: {
				required: "Please enter Email",
				email: "Please enter a valid email address"
			}
		}
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
    <div class="login-right pattern-tools pb80-xs">
        <h1 class="login-h1 tc">Forgot Password</h1>

        <?php

            $attributes = array('id' => 'fpwd');
            echo form_open(base_url('forgotpassword'), $attributes);
        ?>

        <div class="row">
          <?php  if ( (isset ($haserror ) and $errormsg!='') ){ ?><p class="error_top"><?php echo $errormsg; ?></p><?php  }?>
          <?php  if ( (isset ($hassuccess ) and $successmsg!='') ){ ?><p class="success_top"><?php echo $successmsg; ?></p><?php  }?>
        	<div class="col-lg-5 col-md-6 col-sm-9 FN m0auto mt80 mt20-xs"></div>
        		<div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><input class="input100-login radius30p" type="text" name="email" value="" placeholder="Email" /></div>

                <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto mt15 mt20-xs">
                  <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="<?php echo $publickey;  ?>" data-theme="light"></div>
                    <input type="hidden" name="hiddenRecaptcha" id="hiddenRecaptcha">
                  </div>

            <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><input class="submit100-login radius30p" type="submit" value="Submit" /></div>
            <?php /* <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><a href="<?php echo base_url('forgotpassword'); ?>" class="btn-forgot">Forgot Password?</a></div> */ ?>
        </div>
        <input type="hidden" name="act" value="1">
    </form>

        <div class="login-backForward"><a href="<?php echo base_url('login'); ?>" class="back"></a></div>

    </div>

<!--starts from header-->
</div>

</body>
</html>
