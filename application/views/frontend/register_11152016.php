<script>
var ajaxurl= '<?php echo base_url() ?>';
function getcity(type){

    var state = $("#userstate :selected").val();

    $.post(ajaxurl+"getcity",{
           state   : state
        },function (data){
            if(data.city){
                var city = data.city;
                var html = '<option value="">--City--</option>';
                console.log(city);
                //$(data.city).each(function(item){
                $.each(data.city,function(index, item){
                    console.log(item);
                    html += '<option value="'+item.city+'">'+item.city+'</option>';
                });
            }

            $("#usercity").html(html);
        },'json');
}

$(document).ready(function(){
   $("#homeownerreg").validate({
        rules : {
            firstname : 'required',
            lastname: 'required',
            email :
            { required : true, email : true ,
                remote : '<?php echo base_url("checkuserexists") ?>'
            },
            state : 'required',
            city : 'required',
            address : 'required',
            phone : {
                    required: true,
                    phoneUS : true
                },

            zip : { required : true , postalcode : true},
            password : { required : true , minlength : 6 },
            confirmpassword : { required  : true , equalTo : "#password"}


        },
        messages : {
            firstname : 'Please enter firstname',
            lastname: 'Please enter lastname',
            address : 'Please enter address',
            email : { required : 'Please enter email', email : 'Please enter valid email' , remote : 'Email already exists'},
            phone : { required : 'Please enter phone number' , phoneUS : 'Please enter valid phone number'},
            state : 'Please enter state',
            city : 'Please enter city',
            zip : { required : 'Please enter zip'},
            password : { required : 'Please enter password' , minlength : 'Password Minimum length must be 6 characters'},
            confirmpassword : { required  : 'Please confirm password' , equalTo : "Password mismatch"}
        }
    });

        jQuery.validator.addMethod("postalcode", function(postalcode, element) {
            return this.optional(element) || postalcode.match(/(^\d{5}(-\d{4})?$)|(^[ABCEGHJKLMNPRSTVXYabceghjklmnpstvxy]{1}\d{1}[A-Za-z]{1} ?\d{1}[A-Za-z]{1}\d{1})$/);
        });

});
</script>
    <div class="login-right pattern-tools pb70-xs pb100-sm">
    		<div class="row-steps clearfix">
            	<div class="step-box"><img src="images/step2.png" /></div>
                <h1>Personal Information</h1>
            </div>
<?php

    $attributes = array('id' => 'homeownerreg');
    echo form_open(base_url('homeownerregistration'), $attributes);
?>

       <div class="row contclas" id="step1">
            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
                  <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" name="firstname" type="text" placeholder="First Name" /></div>
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p"  name="lastname" type="text" placeholder="Last Name" /></div>
            </div>
            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
                  <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p"  name="phone" type="text" placeholder="Phone" /></div>
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p"  name="email" type="text" placeholder="Email" /></div>
            </div>
            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
                  <div class="col-sm-12 col-md-6 p0-xs"><textarea class="input100-login radius30p"  name="address" placeholder="Address"></textarea></div>
              <div class="col-sm-12 col-md-6 p0-xs">
                  <input class="input100-login radius30p"  name="city" type="text" placeholder="City" />
              </div>
            </div>
            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
                <div class="col-sm-12 col-md-6 p0-xs">
                  <div class="select-login">
                      <select name="state" id="userstate">
                              <option value="">--State--</option>
                              <?php if(!empty($states)){
                                  foreach($states as $st){
                              ?>
                              <option value="<?php echo $st['state_prefix']; ?>"><?php echo $st['state_prefix']; ?></option>
                              <?php
                                  }
                              } ?>
                          </select>
                  </div>
              </div>
                  <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" type="text" placeholder="Zip"  name="zip"  /></div>
            </div>
            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
                  <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" type="password" id="password" name="password"  placeholder="Password" /></div>
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" type="password" name="confirmpassword"  placeholder="Confirm Password" /></div>
            </div>

            <div class="col-sm-8 col-md-4 FN m0auto"><input class="submit100-login radius30p" type="submit" value="Submit"/></div>
        </div>
		<input type="hidden" name="usertype" value="homeowner" />
		<input type="hidden" name="act" value="1" />
</form>
        <div class="login-backForward"> <a href="<?php echo base_url('primaryrole') ?>" class="back"></a></div>

    </div>



<div id="regmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form name="registrationmodal" id="registrationmodal" method="post" action="<?php echo base_url('verifyemail'); ?>">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Please enter Verifiction Code</h4>
      </div>
      <div class="modal-body">

        <div class="col-sm-12 pl5 pt20 FN tc pb10 strwrp">
        <input type="text" placeholder="Verification Code" value="" name="verificationcode" class="input100-login radius30p">
        <input type="hidden" name="email" value="<?php $this->input->post('email'); ?>">
         </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" >Submit</button>
      </div>
    </div>
    </form>

  </div>
</div>


<!--starts from header-->
</div>

</body>
</html>
