<link rel="stylesheet" media="screen" type="text/css" href="<?php echo base_url(); ?>css/colorbox.css" />
<script src="<?php echo base_url(); ?>js/jquery.colorbox.js"></script>

<link href="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.pack.js" type="text/javascript"></script>

<?php 
if (!empty($job_post_video) ){
?>
<script>
  $(document).ready(function() {
    $("a#job_post_video").fancybox({
        maxWidth    : 800,
        maxHeight   : 600,
        fitToView   : true,
        width       : '70%',
        height      : '70%',
        autoSize    : true,
        closeClick  : false,
        openEffect  : 'none',
        closeEffect : 'none'
    });
    $("a#job_post_video").trigger("click");
  }); 
</script>
<?php    
}
?>
<script>
$(document).ready(function(){
  $(".upload").colorbox({
    iframe: true,
    width: "650px",
    height: "650px"
  });
});

function profileImage(imagekey,imagePath,type){
  if(type == 'doc'){
    var imgCode =   '<div class="pimg_'+imagekey+' mt20 prof-pic">'+
            '<a  href="<?php echo base_url('download'); ?>/'+imagekey+'/temp"><img  style="height:150px;width:150px;border:1px solid #dddddd;object-fit: cover;" src="<?php echo base_url('images/Document-icon.png'); ?>"></a>'+
            '<a title="Delete" class="Crp-dlt" onclick="delImg(\''+imagekey+'\')"><span class="glyphicon glyphicon-remove"></span></a>'+
             '<input type="hidden" name="tempdocs[]" value="'+imagekey+'"/>'+
          '</div>';
  }else{
    var imgCode =   '<div class="pimg_'+imagekey+' mt20 prof-pic">'+
            '<img style="height:150px;width:150px;border:1px solid #dddddd;object-fit: cover;" src="'+imagePath+'">'+
            '<a title="Delete" class="Crp-dlt" onclick="delImg(\''+imagekey+'\')"><span class="glyphicon glyphicon-remove"></span></a>'+
             '<input type="hidden" name="tempdocs[]" value="'+imagekey+'"/>'+
          '</div>';
  }
  
  //$(".upload").hide();
  $("#attachdoc").append(imgCode);
}

function delImg(ke){
  if(confirm("Are you sure you want to remove  this image? ")){
    $(".pimg_"+ke).remove();
    $(".upload").show();
    
  }
}


function changeusertype(usertype,ths){
    $(".comuser").removeClass('active');
    $(ths).addClass('active');
    $("#usertype").val(usertype);
}

function upFile(file1) {
    var file = file1.files[0];
    var formData = new FormData();
    formData.append('formData', file);

    $.ajax({
		type: "POST",
		url: "<?php echo base_url('docupload'); ?>",
		contentType: false,
		processData: false,
		dataType: "json",
		data: formData,
		success: function (data) {
			if( data.success ){
				var html = '<div id="temp_'+data.dockey+'">'+data.docname+'<a onclick="removedoc(\''+data.dockey+'\')"><span class="glyphicon glyphicon-remove"></span></a><input type="hidden" name="tempdocs[]" value="'+data.dockey+'"/></div>'
				$("#attachdoc").append(html);
				$("#fileopen").val("");
			}
		}
	});
}

function removedoc(dockey){
    if(confirm("Are you sure you want to delete this image?")){
      $("#temp_"+dockey).remove();
    }
}

$(document).ready(function(){
	$(".timepick").datetimepicker({ format: 'LT', ignoreReadonly: true });
	$(".datepick").datetimepicker({ format: 'MM/DD/YYYY', ignoreReadonly: true });
	$("#postjob").validate({
		rules: {
			jobname		: 'required',
			startdate	: {
				required	: true,
				date		: true
			},
			starttime   : 'required',
			address 	: 'required',
			state		: 'required',
			city		: 'required',
			zip    		: {
				required	: true,
				postalcode	: true
			},
			daysposted	: 'required',
			jobdescription	: 'required',
			completiondate	: {
				required	: true,
				date		: true
			},
			expertiseid		: 'required',
			timeframe        : 'required',
			indoor           : 'required',
			hometype           : 'required',
			starting_state           : 'required',
			total_stories           : 'required',
			material_option: 'required',
			
			location		: 'required',
			year_constructed        : 'required',
			current_condition           : 'required',
			first_problem_notice           : 'required',
			resolution           : 'required',
			measurements           : 'required',
			material_preferences: 'required',
			purchased_materials		: 'required',
			access_to_area        : 'required',
			your_availability           : 'required',
			relevant_info           : 'required',
      budget   : {
        required  : true,
        number  : true
      }
		},
		messages: {
			jobname		: 'Please enter Job Name',
			startdate	:  {
				required	: 'Please select Date',
				date		: 'Please enter a valid Date'
			},
			starttime	: 'Please enter Time',
			address		: 'Please enter Address',
			state		: 'Please select State',
			city		: 'Please select City',
			zip			: {
				required	: "Please enter Zip",
				postalcode	: "Please enter a valid Zip"
			},
			daysposted	: 'Please select Days to be posted',
			jobdescription	: 'Please enter Job Description',
			completiondate	: {
				required	: 'Please select Completion Date',
				date		: 'Please enter a valid Date'
			},
			expertiseid		: 'Please select profession',
			timeframe        : 'Time frame is required.',
			indoor           : 'Please describe whether building is outdoor or indoor.',
			hometype           : 'Home type is required.',
			starting_state           : 'Starting state of work is required.',
			total_stories           : 'total stories is required.',
			material_option           : 'Material option is required.',
			
			
			location		: 'Work location is required.',
			year_constructed        : 'Year of construction is required.',
			current_condition           : 'Current condition of the home needs to be described.',
			first_problem_notice           : 'Please describe when was first problem noticed.',
			resolution           : 'Resolution is required.',
			measurements           : 'Measurements are required.',
			material_preferences: 'Material preferences are required.',
			purchased_materials		: 'Please describe whether you have purchased any material or not.',
			access_to_area        : 'Please describe the access to work area.',
			your_availability           : 'Please describe your availability.',
			relevant_info           : 'Please describe relevant information.',
      
      		budget   : {
        		required  : 'Please enter budget',
        		number  : 'Please enter a valid Amount'
      		}
		}
	});

	jQuery.validator.addMethod("postalcode", function (postalcode, element) {
		return this.optional(element) || postalcode.match(/(^\d{5}(-\d{4})?$)|(^[ABCEGHJKLMNPRSTVXYabceghjklmnpstvxy]{1}\d{1}[A-Za-z]{1} ?\d{1}[A-Za-z]{1}\d{1})$/);
	});
	
	$("#continue_step1").on('click',function()
	{
		showStep2();
	})
	
	$("#back_step2").on('click',function()
	{
		showStep1();
	})
	
	$("#continue_step2").on('click',function()
	{
		showStep3();
	})
	
	$("#back_step3").on('click',function()
	{
		showStep2();
	})
});

function submitMe(){
  var form = $( "form#postjob" );
  if (form.valid() == true){
    $('form#postjob').submit(function(){
      $(this).find(':input[type=submit]').prop('disabled', true);
    });
  }else{
    $(this).find(':input[type=submit]').prop('disabled', false);
  }
}

var ajaxurl= '<?php echo base_url() ?>';
function getcity(){
    var state = $("#state :selected").val();
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
            $("#city").html(html);
        },'json');
}


function triggerFileClick(){
	$("#myfile").trigger('click');
}

function showStep2()
{
	if(!validate_step1())
		return false;
	$("#step1").hide();
	$("#step3").hide();
	$("#step2").show();
}

function showStep1()
{
	$("#step1").show();
	$("#step2").hide();
}

function showStep3()
{
	if(!validate_step2())
		return false;
	$("#step3").show();
	$("#step2").hide();
}

function validate_step1()
{
	valid = true;
	$(".required_step1").each(function()
	{
		if($(this).val().trim() == "")
		{
			id = $(this).attr('id');
			if(id != 'startdate' && id !='completiondate')
				$(this).focus();
			if($("#error_"+$(this).attr('name')).length == 0)
				$(this).after('<label class="error" id="error_'+$(this).attr('name')+'">The '+$(this).attr('label')+' field is required.</label>');
			valid = false;
			
		}
		else
		{
			$("#error_"+$(this).attr('name')).hide();
		}
	})
	return valid;
}

function validate_step2()
{
	valid = true;
	$(".required_step2").each(function()
	{
		if($(this).val().trim() == "")
		{
			$(this).focus();
			if($("#error_"+$(this).attr('name')).length == 0)
				$(this).after('<label class="error" id="error_'+$(this).attr('name')+'">The '+$(this).attr('label')+' field is required.</label>');
			valid = false;
			
		}
		else
		{
			$("#error_"+$(this).attr('name')).hide();
		}
	})
	return valid;
}
</script>
<style>
	.label_txt
	{
		margin-left:30px;
		font-size:16px;
		margin-top:20px;
	}
	/*.input100-login.radius30p.datepick.margin{
		margin:0 0 60px 0;
	}*/
	
</style>
    <section class="right-side divheight">
    	<div class="container-right">
        <?php 
        if (!empty($job_post_video) ){
        ?>
        <a class="fancybox.iframe" href="<?=$job_post_video?>" style="display: none;" id="job_post_video">video</a>
        <?php 
        }
        ?>
                <?php
      $this->load->view('headers/dashboard_menu');
?>
            <?php

            $attributes = array('id' => 'postjob','onSubmit' => 'submitMe()');
            echo form_open_multipart(base_url('postjob'), $attributes);
        ?>
        	<div class="col-sm-12 col-md-9 pl0 pr0">
            	<div class="col-xs-12">
                <h1 class="FL" style="margin-bottom:10px;">My Job Post</h1>
                <!--<a class="backbuton mt30 mt15-xs" href="javascript:history.back(1)">Back</a>-->
              </div>

			  <div style="padding:20px 20px;">
              	<p>Below is the form to create job posting. Each field must be filled out in order to insure a detailed description. The more information provided, the more accurate bids you will receive. At the bottom of this form is an area to fully describe the proposed work in detail. You also have the ability to upload pictures; We strongly recommend you do both.

</p>
				<p>
                	Any fields which are not sure of, please enter N/A.
                </p>
              </div>

			<div id="step1">
            	<div class="col-xs-12" style="">
                	<h3 style="color:white; left:10px; width:120px; text-align:center;" class="badge-job-inside">Step 1 of 3</h3>
                </div>
                <div class="col-xs-12 clearfix p0">
                      
                      
                      <div class="col-xs-12 col-sm-6 col-md-6">
                      <div class="label_txt">
                            Job Name
                      </div>
                      <input class="input100-login radius30p required_step1" name="jobname" label="job name" id="jobname" type="text" placeholder="" /></div>
                  
                  <!--<div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="label_txt">
                            Job Start Date
                    </div>
                    <input class="input100-login radius30p datepick margin required_step1" readonly label="start date"  name="startdate" id="startdate"  type="text" placeholder="" /></div>-->
                     <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="label_txt">
                            What is the time frame to start work?
                          </div>
                  <div class="col-sm-12 p0">
                        <div class="select-login">
                            <select name="timeframe"  class="required_step1" label="Time frame">
                            	<option value="">Select One</option>
                                <option value="Emergency">Emergency</option>
                                <option value="Urgent">Urgent</option>
                                <option value="1 to 2 Weeks">1 to 2 Weeks</option>
                                <option value="2 to 4 Weeks">2 to 4 Weeks</option>
                                <option value="2 to 4 Weeks">No Time Frame</option>
                            </select>
                        </div>
                    </div>
                </div>
                </div>
    
                <div class="col-xs-12 clearfix p0">
                      <div class="col-xs-12 col-sm-6 col-md-6"><!--<input class="input100-login radius30p timepick" readonly type="text" placeholder="Time" name="starttime" />-->
                          <div class="label_txt">
                            Days to be posted
                          </div>
                          <div class="select-login">
                            <select name="daysposted" class="required_step1" label="days to be posted">
                                   <option value="">Select One</option>
                                   <option value="1">1 Day</option>
                                   <option value="3">3 Days</option>
                                   <option value="7">1 Week</option>
                                   <option value="14">2 Weeks</option>
                                   <option value="30">1 Month</option>
                               </select>
                          </div>
                      </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="label_txt">
                            Job Completion Date
                          </div>
                       <div class="select-logi">
                           <input class="input100-login radius30p datepick required_step1" label="completion date" readonly type="text" name="completiondate" id="completiondate" placeholder="Job Completion Date" />
                       </div>
                   </div>
                </div>
    
                <div class="col-xs-12 clearfix p0">
                <div class="col-xs-12 col-sm-6 col-md-6">
                  <div class="label_txt">
                            Type Of Professional Needed
                          </div>
                  <div class="col-xs-12 mb15-xs p0">
                    <div class="select-login">
                        <select name="expertiseid"  class="required_step1" label="Type Of Professional">
                                <option value="">Select One</option>
                                <?php if(!empty($expertises)){
                                    foreach($expertises as $ex){
                                ?>
                                <option value="<?php echo $ex['expertiseid']; ?>"><?php echo $ex['name']; ?></option>
                                <?php
                                    }
                                } ?>
                            </select></div></div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="label_txt">
                            How many stories are in your home?
                          </div>
                  <div class="col-sm-12 p0">
                        <div class="select-login">
                            <select name="total_stories"  class="required_step1" label="Total Stories" >
                            	<option value="">Select One</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>
                </div>
                </div>
                <div class="col-xs-12 clearfix p0">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="label_txt">
                            Is the project Indoors or Outdoors?
                          </div>
                  <div class="col-xs-12 mb15-xs p0">
                  <div class="select-login">
                            <div>
                                
                            </div>
                            <select name="indoor"  class="required_step1" label="indoor/outdoor" >
                            	<option value="">Select One</option>
                               <option value="Indoors">Indoors</option>
                               <option value="Outdoors">Outdoors</option>
                            </select>
                        </div></div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="label_txt">
                            What type of home do you have?
                          </div>
                  <div class="col-sm-12 p0">
                        <div class="select-login">
                            <select name="hometype"  class="required_step1" label="Home Type" >
                            	<option value="">Select One</option>
                                <option value="Condo or Appartment">Condo or Appartment</option>
                                <option value="Townhome">Townhome</option>
                                <option value="Single Family">Single Family</option>
                                <option value="Commercial">Commercial</option>
                            </select>
                        </div>
                    </div>
                </div>
                </div>
                <div class="col-xs-12 clearfix p0">
                <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="label_txt">
                            What is your state of starting the work?
                          </div>
                  <div class="col-xs-12 mb15-xs p0"><div class="select-login">
                            <select name="starting_state"  class="required_step1" label="Starting State" >
                            	<option value="">Select One</option>
                                 <option value="Emergency">Emergency</option>
                                <option value="Ready to go">Ready to go</option>
                                <option value="Planning">Planning</option>
                                <option value="Pricing">Pricing</option>
                            </select>
                        </div></div>
                </div>
                
                </div>
                <div class="col-xs-12 clearfix p0">
                  <div class="col-sm-12 col-md-8">
                    <div class="col-sm-6 FN m0auto mt30" style="margin-left:120px"><input class="submit100-login radius30p" type="button" style="margin-left:140px;" id="continue_step1" value="Next" style="margin-left:140px;"/></div>
                    <br />
                  </div>
              </div>
            </div>
            <div id="step2" style="display:none;">
            	<div class="col-xs-12">
                	<h3 style="color:white; left:10px; width:120px; text-align:center; margin-top:10px 10px;" class="badge-job-inside">Step 2 of 3</h3>
                </div>
                <div class="col-xs-12 clearfix p0">
                <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="label_txt">
                            Should materials be included in the bid?
                          </div>
                  <div class="col-xs-12 mb15-xs p0"><div class="select-login">
                            <select name="material_option"  class="required_step2" label="Material Option" >
                            	<option value="">Select One</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Review both options">Review both options</option>
                            </select>
                        </div></div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="label_txt">
                            Do you want a flat rate or hourly?
                          </div>
                  <div class="col-sm-12 p0">
                        <div class="select-login">
                            <select name="rate_type"  class="required_step2" label="Rate Type">
                            	<option value="">Select One</option>
                                <option value="Flat Rate">Flat Rate</option>
                                <option value="Hourly">Hourly</option>
                                <option value="Review both options">Review both options</option>
                                
                            </select>
                        </div>
                    </div>
                </div>
                </div>
                
                <div class="col-xs-12 clearfix p0">
                  <div class="col-xs-12 col-sm-6 col-md-6">
                  <div class="label_txt">
                            Job Approximate location (neighborhood, etc.)
                          </div>
                    <input type="text" style="" class="input100-login radius30p required_step2" label="location" name="location" placeholder=""/>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="label_txt">
                            What year was your home built(can be approximate)?
                          </div>
                    <input type="text" style="" class="input100-login radius30p required_step2" label="year constructed" placeholder="" name="year_constructed" />
                  </div>
                </div>
                
                
                <div class="col-xs-12 clearfix p0">
                  <div class="col-xs-12 col-sm-6 col-md-6">
                   <div class="label_txt">
                            What is the current condition of the project?
                          </div>
                    <input type="text" style="" class="input100-login radius30p required_step2" label="current condition" name="current_condition" placeholder=""/>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6">
                  <div class="label_txt">
                            When and how was the problem first noticed?
                          </div>
                    <input type="text" style="" class="input100-login radius30p required_step2" placeholder="" name="first_problem_notice" label="first problem notice" />
                  </div>
                </div>
                
                <div class="col-xs-12 clearfix p0">
                  <div class="col-xs-12 col-sm-6 col-md-6">
                  <div class="label_txt">
                            What is the resolution you are asking for?
                          </div>
                    <input type="text" style="" label="resolution" class="input100-login radius30p required_step2" name="resolution" placeholder=""/>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="label_txt">
                            Are there any relevant measurements you can provide(room size ,pipe size)?
                          </div>
                    <input type="text" style="" label="measurements" class="input100-login radius30p required_step2" placeholder="" name="measurements" />
                  </div>
                </div>
                
                <div class="col-xs-12 clearfix p0">
                  <div class="col-xs-12 col-sm-6 col-md-6">
                  <div class="label_txt">
                            Do you have any preference on materials used(list and attach pictures)
                          </div>
                    <input type="text" style="" label="material preferences" class="input100-login radius30p required_step2" name="material_preferences" placeholder=""/>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6">
                  <div class="label_txt">
                            Have you purchased any material for this project (list and attach pictures)?
                          </div>
                    <input type="text" style="" label="purchased materials" class="input100-login radius30p required_step2" placeholder="" name="purchased_materials" />
                  </div>
                </div>
                <div class="col-xs-12 clearfix p0">
          <div class="col-sm-12 col-md-8">
            <div class="col-sm-6 FN m0auto mt30"  style="margin-left:120px"><input class="submit100-login radius30p" style="margin-left:140px;" type="button" id="continue_step2" value="Next" /></div>
            <br />
          </div>
      </div>
            </div>
            <div id="step3" style="display:none;">
            <div class="col-xs-12">
                	<h3 style="color:white; left:10px; width:120px; text-align:center; margin-top:10px 10px;" class="badge-job-inside">Step 3 of 3</h3>
                </div>
                <div class="col-xs-12 clearfix p0">
                  <div class="col-xs-12 col-sm-6 col-md-6">
                  <div class="label_txt">
                            Please describe the access to the area(i.e basement with walk-in doors)?
                          </div>
                    <input type="text" style="" class="input100-login radius30p" name="access_to_area" placeholder=""/>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6">
                  <div class="label_txt">
                            Can you list your preffered availability(evenings, weekend, weekdays)?
                          </div>
                    <input type="text" style="" class="input100-login radius30p" placeholder="" name="your_availability" />
                  </div>
                </div>
                
                <div class="col-xs-12 clearfix p0">
                  
                   <div class="col-xs-12 col-sm-6 col-md-6">
                   <div class="label_txt">
                            Address
                          </div>
                    <textarea style="" class="input100-login radius30p" placeholder="" name="address" ><?php echo $userDets['address']; ?></textarea>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="label_txt">
                            City
                          </div>
                       <input class="input100-login radius30p" type="text" name="city" placeholder="" value="<?php echo $userDets['city']; ?>"/>
                   </div>
                </div>
    
                
                
    
                 <div class="col-xs-12 clearfix p0">
                   
                    <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="label_txt">
                            State
                          </div>
                        <div class="select-login">
                            <select name="state" id="state"  class="required_step3" label="State">
                                <option value="">Select One</option>
                                <?php if(!empty($states)){
                                    foreach($states as $st){
                                ?>
                                <option value="<?php echo $st['state_prefix']; ?>"  <?php if( $userDets['state'] == $st['state_prefix'] ): ?>selected<?php endif; ?>><?php echo $st['state_prefix']; ?></option>
                                <?php
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>
    				<div class="col-xs-12 col-sm-6 col-md-6">
                   <div class="label_txt">
                            Zip
                          </div>
                   <input class="input100-login radius30p" value="<?php echo $userDets['zip']; ?>" type="text" placeholder="" name="zip" /></div>
                </div>
    
                <div class="col-xs-12 clearfix p0">
                   
    
                   <div class="col-xs-12 col-sm-6 col-md-6">
                   <div class="label_txt">
                            Budget
                          </div>
                       <input class="input100-login radius30p" type="text" name="budget" placeholder="" value=""/>
                   </div>
                 </div>
                 <div class="col-xs-12 clearfix p0">
                  <div class="col-xs-12 col-sm-6 col-md-12">
                   <div class="label_txt">
                            Job Description
                          </div>
                    <textarea style="height:229px; " class="input100-login radius30p" name="jobdescription" placeholder=""></textarea>
                  </div>
                  
                </div>
                 <div class="col-xs-12 clearfix p0">
              <div class="col-xs-12 col-sm-12 col-md-12">
                <a href="<?php echo base_url().'Crop/index/job'; ?>" class="btn-upload upload" ><span>Upload document and pictures.</span></a>
      	      
              </div>
              <div class="col-xs-12 clearfix pt20" id="attachdoc"></div>
            </div>
            <div class="col-xs-12 clearfix p0">
          <div class="col-sm-12 col-md-8">
            <div class="col-sm-6 FN m0auto mt30"><input class="submit100-login radius30p" style="margin-left:140px;" type="submit" value="Request Bid" /></div>
          </div>
      </div>
			</div>
            

			
      </div>
            <input type="hidden" name="act" value="1">
        </form>

            <?php $this->load->view('headers/rightside'); ?>

        <div class="clear"></div>
        </div>

    </section>

<div class="clear"></div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form name="docupload" id="docupload" action ="<?php echo base_url('docupload'); ?>" enctype="multipart/form-data">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload file</h4>
      </div>
      <div class="modal-body">

        <p><input type="file" name="file"></p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="uploaddoc()">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    </form>

  </div>
</div>
