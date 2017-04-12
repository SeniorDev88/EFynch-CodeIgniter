<script>
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
			expertiseid		: 'required'
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
			expertiseid		: 'Please select Category'
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
            echo form_open(base_url('postjob'), $attributes);
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
            	  <div class="col-xs-12 col-sm-6 col-md-4"><input class="input100-login radius30p timepick" readonly type="text" placeholder="Time" name="starttime" /></div>
                <div class="col-xs-12 col-sm-6 col-md-4">
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
            </div>

            <div class="col-xs-12 clearfix p0">
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="col-xs-12 mb15-xs p0"><input class="input100-login radius30p datepick" readonly type="text" name="completiondate" placeholder="Job Completion Date" /></div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="col-sm-12 p0">
                    <div class="select-login">
                        <select name="expertiseid" >
                            <option value="">--Category--</option>
                            <?php if(!empty($expertises)){
                                foreach($expertises as $ex){
                            ?>
                            <option value="<?php echo $ex['expertiseid']; ?>"><?php echo $ex['name']; ?></option>
                            <?php
                                }
                            } ?>
                        </select>
                    </div>
                </div>
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
                <a href="javascript:void(0)" class="btn-upload" onclick="triggerFileClick()"><span>Upload Image</span></a>
      	        <input type="file" name="file" onchange="upFile(this)" class="btn-upload" style="display:none;" id="myfile">
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
