<script>
function changeusertype(usertype,ths){
    $(".comuser").removeClass('active');
    $(ths).addClass('active');
    $("#usertype").val(usertype);
}
$(document).ready(function(){
  $("#login").validate({
     rules: {
            password  : 'required',
            email     : {
                          required: true,
                          email: true
                      }
            },
      messages: {
          password:  'Please enter Password' ,
          email: {
              required: "Please enter Email",
              email: "Please enter a valid email address"
          }
      }
  });
});
</script>
    <div class="login-right pattern-tools pb80-xs">
        <h1 class="login-h1 tc">Welcome Home</h1>
        <p class="login-p tc pl100 pr100 pl0-md pr0-md">EFynch.com is a Home Improvement Platform which helps to connect homeowners and contractors when projects and work is needed. We strive to do this efficiently as possible, making most services automated while protecting your privacy.</p>

        <?php

            $attributes = array('id' => 'login');
            echo form_open(base_url('login'), $attributes);
        ?>

        <div class="row">
          <?php  if ( (isset ($haserror ) and $errormsg!='') ){ ?><p class="error_top"><?php echo $errormsg; ?></p><?php  }?>

          <?php /* ?>
          <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto mt80 mt20-xs">
            	<div class="btn-group-login radius30p"><a href="javascript:void(0)" onclick="changeusertype('homeowner',this)" class="comuser active">Homeowner</a> <a href="javascript:void(0)" onclick="changeusertype('contractor',this)" class="comuser">Contractor	</a></div>
            </div>
            <input type="hidden" name="usertype" id="usertype" value="homeowner">
        		<?php */ ?>

            <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><input class="input100-login radius30p" type="text" name="email" value="<?php echo $this->input->post('email'); ?>" placeholder="Email" /></div>
            <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><input class="input100-login radius30p" type="password" name="password"  placeholder="Password" /></div>
            <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><input class="submit100-login radius30p" type="submit" value="Login" /></div>
            <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><a href="<?php echo base_url('forgotpassword'); ?>" class="btn-forgot">Forgot Password?</a></div>
            <div class="col-lg-5 col-md-6 col-sm-9 FN m0auto"><a href="<?php echo base_url('verifyemail'); ?>"  class="btn-forgot">Verify Email</a></div>

        </div>
        <input type="hidden" name="Login" value="1">
    </form>

      <div class="login-backForward"> <a href="<?php echo base_url('app') ?>" class="back"></a></div>

    </div>

<!--starts from header-->
</div>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-96444822-1', 'auto');
ga('send', 'pageview');

</script>
</body>
</html>
