<script>
var ajaxurl= '<?php echo base_url() ?>';
function showstep(step,typ){
    var vald = 1;
    if(typ == 'fwd'){
        vald = $("#contractorreg").valid();
    }
    //alert(vald);
    if(vald){
        $("#currentstep").val(step);
        var imgarray = ['images/step2.png','images/step3-5.png','images/step4-5.png','images/step5-5.png'];
        var headArray = ['Personal Information','Additional Info','Area of Expertise','License, insurance & Banking Info'];
        $("#idimg").attr('src',imgarray[step-1]);
        $("#idh1").html(headArray[step-1]);
        $(".contclas").hide();
        $("#step"+step).show();
        if(step == 3){
            $(".fwdclas").hide();
        }else{
            $(".fwdclas").show();
        }
    }
}


function getcity(type){

    var state = $("#"+type+"state :selected").val();

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

            $("#"+type+"city").html(html);
        },'json');
}
$(document).ready(function(){
    $(".expclas").click(function(){

        var slug = $(this).attr('data');
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $("#exp_"+slug).remove();
        }else{
            $(this).addClass('active');
            $("#expertiseid").append('<input type="hidden" name="expertise[]" value="'+slug+'" id="exp_'+slug+'">');
        }
        var expval = ($(".expclas.active").length > 0 ) ? '1' : '';
        //alert(expval);
        $("#expertcheck").val(expval);

    });
    $(".fwdclas").click(function(){
        var step = $("#currentstep").val();
        step++;
        if(step == 4){
           // $(".fwdclas").hide();
        }
        showstep(step,'fwd');
    });
    $(".bckclas").click(function(){
        var step = $("#currentstep").val();
        step--;
        if(step == 0){
            window.location.href = '<?php echo base_url("primaryrole") ?>';
        }
        showstep(step,'back');
    });
    $("#contractorreg").validate({
        ignore : [],
        rules : {
            firstname :  {
                    required: function(element) {
                        return ( $("#currentstep").val() == 1 );
                    }
                },
            lastname:  {
                    required: function(element) {
                        return ( $("#currentstep").val() == 1 );
                    }
                },
            email : {
                    required: function(element) {
                        return ( $("#currentstep").val() == 2 );
                    },
                    email : true,
                    remote : '<?php echo base_url("checkuserexists") ?>'
                },
            state :  {
                    required: function(element) {
                        return ( $("#currentstep").val() == 1 );
                    }
                },
            city :  {
                    required: function(element) {
                        return ( $("#currentstep").val() == 1 );
                    }
                },
            zip : {
                    required: function(element) {
                        return ( $("#currentstep").val() == 1 );
                    },
                    postalcode : true
                },
                phone : {
                    required: function(element) {
                        return ( $("#currentstep").val() == 1 );
                    },
                    phoneUS : true
                },
                address : {
                    required: function(element) {
                        return ( $("#currentstep").val() == 1 );
                    }
                },
            password : {
                        required: function(element) {
                            return ( $("#currentstep").val() == 2 );
                        },
                        minlength : 6
                    },
            confirmpassword : {
                        required: function(element) {
                            return ( $("#currentstep").val() == 2 );
                        },
                        equalTo : "#password"
            },
            isagreeterms : {
                    required: function(element) {
                        return ( $("#currentstep").val() == 3 );
                    }
                },
            expertcheck :  {
                    required: function(element) {
                        return ( $("#currentstep").val() == 3 );
                    }
                },
            insurance : {
                    required: function(element) {
                        return ( $("#currentstep").val() == 2  && $(".incclas").hasClass('active'));
                    }
                },
                licence : {
                    required: function(element) {
                        return ( $("#currentstep").val() == 2 && $(".liceclas").hasClass('active'));
                    }
                }

        },
        messages : {
            firstname : 'Please enter First Name',
            lastname: 'Please enter Last Name',
            address : 'Please enter Address',
            email : { required : 'Please enter Email', email : 'Please enter valid email' , remote : 'Email already exists'},
            state : 'Please enter State',
            city : 'Please enter City',
            //state : 'Please enter address',
            zip : { required : 'Please enter Zip' , postalcode : 'Please enter valid Zip'},
            phone : { required : 'Please enter Phone Number' , phoneUS : 'Please enter valid Phone Number'},
            password : { required : 'Please enter Password', minlength : 'Password Minimum length must be 6 characters' },
            confirmpassword : { required  : 'Please Confirm Password' , equalTo : "Password Mismatch"},
            isagreeterms : "PPlease accept Terms and Conditions",
            insurance : 'Please enter Insurance number',
            licence : 'Please enter Licence number',
            expertcheck : 'Please select an Expertise'

        }
    });

        jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
            phone_number = phone_number.replace(/\s+/g, "");
            return this.optional(element) || phone_number.length > 9 &&
                phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
        });

        jQuery.validator.addMethod("postalcode", function(postalcode, element) {
            return this.optional(element) || postalcode.match(/(^\d{5}(-\d{4})?$)|(^[ABCEGHJKLMNPRSTVXYabceghjklmnpstvxy]{1}\d{1}[A-Za-z]{1} ?\d{1}[A-Za-z]{1}\d{1})$/);
        });

        $(".liceclas").on("click",function(){
            if($(this).hasClass("active")){
                $("#licence").hide();
                $(this).removeClass("active");
            }else{
                $("#licence").show();
                $(this).addClass("active");
            }
        });

        $(".incclas").on("click",function(){
            if($(this).hasClass("active")){
                $("#insurance").hide();
                $(this).removeClass("active");
            }else{
                $("#insurance").show();
                $(this).addClass("active");
            }
        });

});
</script>

    <div class="login-right pattern-tools pb70-xs pb100-sm">
    		<div class="row-steps clearfix">
            	<div class="step-box"><img id="idimg" src="images/step2.png" /></div>
                <h1 id="idh1">Personal Information</h1>
            </div>
<?php

    $attributes = array('id' => 'contractorreg');
    echo form_open(base_url('contractorregistration'), $attributes);
?>

<?php if(isset($error)){ ?><div class="error"><?php echo $errormsg; ?></div><?php } ?>

        <div class="row contclas" id="step1">
            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
        		  <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" name="firstname" type="text" placeholder="First Name" /></div>
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p"  name="lastname" type="text" placeholder="Last Name" /></div>
            </div>
            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p"  name="companyname" type="text" placeholder="Company Name" /></div>
              <div class="col-sm-12 col-md-6 p0-xs"><textarea class="input100-login radius30p"  name="address" placeholder="Address"></textarea></div>
            </div>
            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p"  name="city" type="text" placeholder="City" /></div>
              <div class="col-sm-12 col-md-6 p0-xs">
                    <div class="select-login">
                    <select name="state"  id="userstate">
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
            </div>
            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
        		  <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" type="text" placeholder="Zip"  name="zip"  /></div>
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p"  name="phone" type="text" placeholder="Phone" /></div>
            </div>


            <div class="col-sm-8 col-md-4 FN m0auto"><input class="submit100-login radius30p" type="button" value="Next" onclick="showstep(2,'fwd')" /></div>
        </div>

         <div class="row contclas" id="step2" style="display:none;">

             <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
              <div class="col-sm-12 col-md-6 p0-xs clearfix pt30">
                <h4 class="FL mt5">Licensed</h4>
                <label class="switch FR">
                  <input type="checkbox">
                  <div class="slider round liceclas"></div>
                </label>
              </div>
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" style="display:none;" id="licence"   type="text" name="licence"  placeholder="License Number" /></div>
            </div>

            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
              <div class="col-sm-12 col-md-6 p0-xs clearfix pt30 mt15-xs">
                <h4 class="FL mt5">Insurance</h4>
                <label class="switch FR">
                  <input type="checkbox">
                  <div class="slider round incclas"></div>
                </label>
              </div>
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" style="display:none;"  type="text" id="insurance" name="insurance"  placeholder="Insurance Number" /></div>
            </div>


                <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
                  <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" type="text" name="email"  placeholder="Email" /></div>
            </div>

            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
                  <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" type="password" id="password" name="password"  placeholder="Password" /></div>
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" type="password" name="confirmpassword"  placeholder="Confirm Password" /></div>
            </div>

            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
                <div class="col-sm-8 col-md-4 FN m0auto"> <button type="button" class="submit100-login radius30p skipit" onclick="showstep(3,'fwd')">Next</button>  </div>
            </div>


        </div>
        <div class="row contclas" id="step3" style="display:none;">
            <p class="area-subhead">(For the filtering of Jobs to show in your feed)</p>
            <?php if(!empty($expertise)){
                foreach($expertise as $exp){

            ?>

            <a class="col-xs-4 col-sm-4 col-md-3 expertise-area  expclas" data="<?php echo $exp['slug']; ?>"> <img src="images/expertise/<?php echo $exp['webimg']; ?>" />
              <span><?php echo $exp['name']; ?></span>
              <?php if($exp['slug'] == 'handy_man'  ){ ?> <p>(Recommended for all)</p> <?php } ?>
            </a>
            <?php
                }
            } ?>

            <div class="clear"></div>
            <div id="expertiseid" style="display:none;" ></div>
            <input type="hidden" name="expertcheck" id="expertcheck" value="">
            <div class="col-xs-12 mt10 clearfix">
              <div class="acceptbox">
                <div class="roundedTwo FL">
                  <input type="checkbox" value="None" id="roundedTwo" name="isagreeterms"/>
                  <span for="roundedTwo"></span>
                </div>
                <a href="<?php echo base_url('termsandcondition'); ?>" target="_blank" class="term_accept">Accept Terms and Conditions</a>
              </div>
             </div>
             <div class="clear"></div>
            <div class="col-sm-8 col-md-4 FN m0auto"><input class="submit100-login radius30p" type="submit" value="Register" /></div>

            </div>


            <?php /* ?>

            <div class="row contclas" id="step4" style="display:none;" >
            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
                  <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" type="text" name="licence"  placeholder="License" /></div>
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" type="text" name="insurance"  placeholder="Insurance" /></div>
            </div>
            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto clearfix">
                  <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" name="routingnumber"  type="text" placeholder="Routing Number" /></div>
              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" type="text" name="accountnumber"  placeholder="Account Number" /></div>
            </div>

            <div class="col-sm-12 col-md-10 col-lg-8 FN m0auto mt10 clearfix">
              <div class="col-sm-12 col-md-6 p0-xs posabs"><input type="checkbox"  name="isagreeterms" class="radius30p ">Accept Terms and Conditions</div>
             </div>

            <div class="col-sm-8 col-md-4 FN m0auto"><input class="submit100-login radius30p" type="submit" value="Register" /></div>
        </div>

        <?php */ ?>

        <input type="hidden" name="currentstep" id="currentstep" value="1">
        <input type="hidden" name="usertype" value="contractor">
        <input type="hidden" name="act" value="1">


    </form>

        <div class="login-backForward"><a href="javascript:void(0)" class="back bckclas"></a><a href="javascript:void(0)" class="forward fwdclas"></a></div>

    </div>

<!--starts from header-->
</div>

</body>
</html>
