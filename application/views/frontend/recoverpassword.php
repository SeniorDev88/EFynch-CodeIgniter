<script>
function changeusertype(usertype,ths){
    $(".comuser").removeClass('active');
    $(ths).addClass('active');
    $("#usertype").val(usertype);
}
$(document).ready(function(){
  $("#recpwd").validate({
     rules: { 
            password  : { required : true, minlength : 6},
            confirmpassword     : {
				  required: true,
				  equalTo: "#pwd"
			  }
    },
      messages: {
          password: { required : 'Please enter Password'  , minlength : 'Password Minimum length must be 6 characters'} ,
          confirmpassword: {
              required: "Please enter Confirm Password",
              equalTo: "Password mismatch"
          }
      }
  });
});
</script>
    <div class="login-right pattern-tools pb80-xs">
        <h1 class="login-h1 tc">Recover Password</h1>

        <?php

            $attributes = array('id' => 'recpwd');
            echo form_open(base_url('recoverpassword/'.$recoverpwdkey), $attributes);
        ?>

        <div class="row">
          <?php  if ( (isset ($haserror ) and $errormsg!='') ){ ?><p class="error_top"><?php echo $errormsg; ?></p><?php  }?>
		  <?php  if ( (isset ($hassuccess ) and $successmsg!='') ){ ?><p class="success_top"><?php echo $successmsg; ?></p><?php  }?>
        	<div class="col-lg-5 col-md-6 col-sm-9 FN m0auto mt80 mt20-xs"></div>
            <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><input class="input100-login radius30p" type="password" name="password" id="pwd" placeholder="Password" <?php if( isset( $toDisable ) and $toDisable == 1 ): ?>readonly<?php endif; ?>/></div>
            <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><input class="input100-login radius30p" type="password" name="confirmpassword"  placeholder="Confirm Password" <?php if( isset( $toDisable ) and $toDisable == 1 ): ?>readonly<?php endif; ?>/></div>
            <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><input class="submit100-login radius30p" type="submit" value="Submit" <?php if( isset( $toDisable ) and $toDisable == 1 ): ?>disabled<?php endif; ?>/></div>
            <?php /* <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><a href="<?php echo base_url('forgotpassword'); ?>" class="btn-forgot">Forgot Password?</a></div> */ ?>
        </div>
        <input type="hidden" name="act" value="1">
    </form>

        <div class="login-backForward"><a href="#" class="back"></a><a href="#" class="forward"></a></div>

    </div>

<!--starts from header-->
</div>

</body>
</html>