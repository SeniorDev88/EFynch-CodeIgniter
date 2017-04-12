<link rel="stylesheet" media="screen" type="text/css" href="<?php echo base_url(); ?>css/colorbox.css" />
<script src="<?php echo base_url(); ?>js/jquery.colorbox.js"></script>
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
});
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
</script>

    <section class="right-side divheight">
    	<div class="container-right">
                <?php
      $this->load->view('headers/dashboard_menu');
?>
            <?php

            $attributes = array('id' => 'postjob');
            echo form_open_multipart(base_url('postjob'), $attributes);
        ?>
        	<div class="col-sm-12 col-md-9 pl0 pr0">
            	<div class="col-xs-12">
                <h1 class="FL">My Job Post</h1>
                <a class="backbuton mt30 mt15-xs" href="javascript:history.back(1)">Back</a>
              </div>




            <div class="col-xs-12 clearfix p0">
        		  <div class="col-xs-12 col-sm-6 col-md-4"><input class="input100-login radius30p" name="jobname" type="text" placeholder="Job Name" /></div>
              <div class="col-xs-12 col-sm-6 col-md-4"><input class="input100-login radius30p datepick" readonly  name="startdate"  type="text" placeholder="Job Start Date" /></div>
            </div>

            <div class="col-xs-12 clearfix p0">
            	  <div class="col-xs-12 col-sm-6 col-md-4"><!--<input class="input100-login radius30p timepick" readonly type="text" placeholder="Time" name="starttime" />-->
                      <div class="select-login">
                      	<select name="daysposted">
                               <option value="">--Days to be posted--</option>
                               <option value="1">1 Day</option>
                               <option value="3">3 Days</option>
                               <option value="7">1 Week</option>
                               <option value="14">2 Weeks</option>
                               <option value="30">1 Month</option>
                           </select>
                      </div>
                  </div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                   <div class="select-logi">
                       <input class="input100-login radius30p datepick" readonly type="text" name="completiondate" placeholder="Job Completion Date" />
                   </div>
               </div>
            </div>

            <div class="col-xs-12 clearfix p0">
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="col-xs-12 mb15-xs p0">
              	<div class="select-login">
              		<select name="expertiseid" >
                            <option value="">Type of Professional Needed</option>
                            <?php if(!empty($expertises)){
                                foreach($expertises as $ex){
                            ?>
                            <option value="<?php echo $ex['expertiseid']; ?>"><?php echo $ex['name']; ?></option>
                            <?php
                                }
                            } ?>
                        </select></div></div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="col-sm-12 p0">
                    <div class="select-login">
                        <select name="timeframe" >
                            <option value="">What is the timeframe to start work?</option>
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
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="col-xs-12 mb15-xs p0"><div class="select-login">
                        <select name="indoor" >
                            <option value="">Is the project Indoors or Outdoors?</option>
                           <option value="Indoors">Inoors</option>
                           <option value="Outdoors">Outdoors</option>
                        </select>
                    </div></div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="col-sm-12 p0">
                    <div class="select-login">
                        <select name="hometype" >
                            <option value="">What type of home do you have?</option>
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
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="col-xs-12 mb15-xs p0"><div class="select-login">
                        <select name="starting_state" >
                            <option value="">What is your state of starting the work?</option>
                             <option value="Emergency">Emergency</option>
                            <option value="Ready to go">Ready to go</option>
                            <option value="Planning">Planning</option>
                            <option value="Pricing">Pricing</option>
                        </select>
                    </div></div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="col-sm-12 p0">
                    <div class="select-login">
                        <select name="total_stories" >
                            <option value="">How many stories are in your home?</option>
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
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="col-xs-12 mb15-xs p0"><div class="select-login">
                        <select name="material_option" >
                            <option value="">Should materials be included in the bid?</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div></div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="col-sm-12 p0">
                    <div class="select-login">
                        <select name="rate_type" >
                            <option value="">Do you want a flat rate or hourly?</option>
                            <option value="Flat Rate">Flat Rate</option>
                            <option value="Hourly">Hourly</option>
                            
                        </select>
                    </div>
                </div>
            </div>
            </div>
            
            <div class="col-xs-12 clearfix p0">
              <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" style="" class="input100-login radius30p" name="location" placeholder="Job Approximate Location(country,intersection,neighborhood)"/>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" style="" class="input100-login radius30p" placeholder="What year was your home built(can be approximate)?" name="year_constructed" />
              </div>
            </div>
            
            
            <div class="col-xs-12 clearfix p0">
              <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" style="" class="input100-login radius30p" name="current_condition" placeholder="What is the current condition?(What work are you asking for?)"/>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" style="" class="input100-login radius30p" placeholder="When and how was the problem first noticed?" name="first_problem_notice" />
              </div>
            </div>
            
            <div class="col-xs-12 clearfix p0">
              <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" style="" class="input100-login radius30p" name="resolution" placeholder="What is the resolution you are asking for?"/>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" style="" class="input100-login radius30p" placeholder="Are there any relevant measurements you can provide(room size ,pipe size)?" name="measurements" />
              </div>
            </div>
            
            <div class="col-xs-12 clearfix p0">
              <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" style="" class="input100-login radius30p" name="material_preferences" placeholder="Do you have any preference on materials used(list and attach pictures)"/>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" style="" class="input100-login radius30p" placeholder="Have you purchased any material for this project (list and attach pictures)?" name="purchased_materials" />
              </div>
            </div>
            
            
            <div class="col-xs-12 clearfix p0">
              <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" style="" class="input100-login radius30p" name="access_to_area" placeholder="Please describe the access to the area(i.e basement with walk-in doors)?"/>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" style="" class="input100-login radius30p" placeholder="Can you list your preffered availability(evenings, weekend, weekdays)?" name="your_availability" />
              </div>
            </div>
            
            <div class="col-xs-12 clearfix p0">
              <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" style="" class="input100-login radius30p" name="relevant_info" placeholder="Other Relevant Information?"/>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-4">
                <!--<input type="text" style="" class="input100-login radius30p" placeholder="What year was your home built(can be approximate)?" name="year_constructed" />-->
              </div>
            </div>
            
            
            
            
            
            <div class="col-xs-12 clearfix p0">
              <div class="col-xs-12 col-sm-6 col-md-4">
                <textarea style="height:129px;" class="input100-login radius30p" name="jobdescription" placeholder="Job Description"></textarea>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-4">
                <textarea style="height:129px;" class="input100-login radius30p" placeholder="Address" name="address" ><?php echo $userDets['address']; ?></textarea>
              </div>
            </div>

             <div class="col-xs-12 clearfix p0">
               <div class="col-xs-12 col-sm-6 col-md-4">
                   <input class="input100-login radius30p" type="text" name="city" placeholder="City" value="<?php echo $userDets['city']; ?>"/>
               </div>
             	<div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="select-login">
                        <select name="state" id="state">
                            <option value="">--State--</option>
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

            </div>

            <div class="col-xs-12 clearfix p0">
               <div class="col-xs-12 col-sm-6 col-md-4"><input class="input100-login radius30p" value="<?php echo $userDets['zip']; ?>" type="text" placeholder="Zip" name="zip" /></div>

               <div class="col-xs-12 col-sm-6 col-md-4">
                   <input class="input100-login radius30p" type="text" name="budget" placeholder="Budget" value=""/>
               </div>
             </div>

            <div class="col-xs-12 clearfix p0">
              <div class="col-xs-12 col-sm-6 col-md-4">
                <a href="<?php echo base_url().'Crop/index/job'; ?>" class="btn-upload upload" ><span>Upload Document</span></a>
      	      
              </div>
              <div class="col-xs-12 clearfix pt20" id="attachdoc"></div>
            </div>

			<div class="col-xs-12 clearfix p0">
          <div class="col-sm-12 col-md-8">
            <div class="col-sm-6 FN m0auto mt30"><input class="submit100-login radius30p" type="submit" value="Request Bid" /></div>
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
