<!-- For uploading Job Gallery -->
<form id="upload_form_gallery" enctype="multipart/form-data" method="post" style="display: none;">
    <input type="file" name="file_name" id="gallery_inp" style="display:none;"><br>
</form>
<div class="content">

<!-- CKEditor default -->
<div class="panel panel-flat">
    <div class="panel-heading">

        <h5 class="panel-title">Add Job Detail</h5>

    </div>
    <div class="panel-body">
        <form method="post" class="form-horizontal" action="<?php echo(base_url('admin/jobs/addJob')); ?>" enctype="multipart/form-data">

            <fieldset class="content-group">

                <div class="form-group">

                    <label class="control-label col-lg-2">Job Name: </label>

                    <div class="col-lg-6">

                        <div class="error"> <?php echo form_error('jobname'); ?></div>

                        <input type="text" name="jobname" value="<?php echo set_value('jobname'); ?>" class="form-control">

                    </div>

                </div>

                <div class="form-group" id="state">

                    <label class="control-label col-lg-2">Created By:</label>

                    <div class="col-lg-6">

                        <select id="user" name="createdby" class="form-control">

                            <?php foreach ($users as $v) {?>

                                <option value="<?php echo $v['userid']; ?>"><?php echo $v['firstname'].' '.$v['lastname'];?></option>

                            <?php }?>

                        </select>

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-lg-2">Description: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('jobdescription'); ?>

                        <textarea name="jobdescription" class="form-control"><?php echo set_value('jobdescription'); ?></textarea>

                    </div>

                </div>

                <div class="form-group" id="state">

                    <label class="control-label col-lg-2">Expertise:</label>

                    <div class="col-lg-6">

                        <select id="exp" name="expertiseid" class="form-control">

                            <?php foreach ($exp as $v) {?>

                                <option value="<?php echo $v['expertiseid']; ?>"><?php echo $v['name'];?></option>

                            <?php }?>

                        </select>

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Address: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('address'); ?>

                        <textarea name="address" class="form-control"><?php echo set_value('address'); ?></textarea>

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-lg-2">Budget: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('budget'); ?>

                        <input type="text" name="budget" value="<?php echo set_value('budget'); ?>" class="form-control">

                    </div>

                </div>

                <div class="form-group">

                    <label class="control-label col-lg-2">Days Posted: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('daysposted'); ?>

                        <input type="text" name="daysposted" value="<?php echo set_value('daysposted'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group" id="timeframe">

                    <label class="control-label col-lg-2">Work TimeFrame:</label>

                    <div class="col-lg-6">

                        <select name="timeframe" class="form-control">

                            <option value="Emergency">Emergency</option>

                            <option value="Urgent">Urgent</option>

                            <option value="1 to 2 Weeks">1 to 2 Weeks</option>

                            <option value="2 to 4 Weeks">2 to 4 Weeks</option>

                            <option value="No Time Frame">No Time Frame</option>

                        </select>

                    </div>

                </div>



                <div class="form-group" id="indoor">

                    <label class="control-label col-lg-2">Indoor/Outdoor:</label>

                    <div class="col-lg-6">

                        <select name="indoor" class="form-control">

                            <option value="Indoors">Indoors</option>

                            <option value="Outdoors">Outdoors</option>

                        </select>

                    </div>

                </div>



                <div class="form-group" id="hometype">

                    <label class="control-label col-lg-2">Home Type:</label>

                    <div class="col-lg-6">

                        <select name="hometype" class="form-control">

                            <option value="Condo or Appartment">Condo or Appartment</option>

                            <option value="Townhome">Townhome</option>

                            <option value="Single Family">Single Family</option>

                            <option value="Commercial">Commercial</option>

                        </select>

                    </div>

                </div>



                <div class="form-group" id="starting_state">

                    <label class="control-label col-lg-2">Starting State:</label>

                    <div class="col-lg-6">

                        <select name="starting_state" class="form-control">

                            <option value="Emergency">Emergency</option>

                            <option value="Ready to go">Ready to go</option>

                            <option value="Planning">Planning</option>

                            <option value="Pricing">Pricing</option>

                        </select>

                    </div>

                </div>



                <div class="form-group" id="total_stories">

                    <label class="control-label col-lg-2">Total Stories Of Home:</label>

                    <div class="col-lg-6">

                        <select name="total_stories" class="form-control">

                            <option value="1">1</option>

                            <option value="2">2</option>

                            <option value="3">3</option>

                            <option value="4">4</option>

                        </select>

                    </div>

                </div>



                <div class="form-group" id="material_option">

                    <label class="control-label col-lg-2">Include Material In Bid:</label>

                    <div class="col-lg-6">

                        <select name="material_option" class="form-control">

                            <option value="Yes">Yes</option>

                            <option value="No">No</option>

                        </select>

                    </div>

                </div>



                <div class="form-group" id="rate_type">

                    <label class="control-label col-lg-2">Rate Type:</label>

                    <div class="col-lg-6">

                        <select name="rate_type" class="form-control">

                            <option value="Flat Rate">Flat Rate</option>

                            <option value="Hourly">Hourly</option>

                        </select>

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Job Approximate Location: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('location'); ?>

                        <input type="text" placeholder="Job Approximate Location(country,intersection,neighborhood)" name="location" value="<?php echo set_value('location'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Home Built In?: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('year_constructed'); ?>

                        <input type="text" placeholder="What year was your home built(can be approximate)?" name="year_constructed" value="<?php echo set_value('year_constructed'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Current Condition: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('current_condition'); ?>

                        <input type="text" placeholder="What is the current condition?(What work are you asking for?)" name="current_condition" value="<?php echo set_value('current_condition'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Problem Description: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('first_problem_notice'); ?>

                        <input type="text" placeholder="When and how was the problem first noticed?" name="first_problem_notice" value="<?php echo set_value('first_problem_notice'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Resolution: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('resolution'); ?>

                        <input type="text" placeholder="What is the resolution you are asking for?" name="resolution" value="<?php echo set_value('resolution'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Measurements: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('measurements'); ?>

                        <input type="text" placeholder="Are there any relevant measurements you can provide(room size ,pipe size)?" name="measurements" value="<?php echo set_value('measurements'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Material Preferences: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('material_preferences'); ?>

                        <input type="text" placeholder="Do you have any preference on materials used(list and attach pictures)" name="material_preferences" value="<?php echo set_value('material_preferences'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Purchased Materials: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('purchased_materials'); ?>

                        <input type="text" placeholder="Have you purchased any material for this project (list and attach pictures)?" name="purchased_materials" value="<?php echo set_value('purchased_materials'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Access To Area: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('access_to_area'); ?>

                        <input type="text" placeholder="Please describe the access to the area(i.e basement with walk-in doors)?" name="access_to_area" value="<?php echo set_value('access_to_area'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Your Availability: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('your_availability'); ?>

                        <input type="text" placeholder="Can you list your preffered availability(evenings, weekend, weekdays)?" name="your_availability" value="<?php echo set_value('your_availability'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Relevant Info: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('relevant_info'); ?>

                        <input type="text" placeholder="Other Relevant Information?" name="relevant_info" value="<?php echo set_value('relevant_info'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group">

                    <label class="control-label col-lg-2">Zip: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('zip'); ?>

                        <input type="text" name="zip" value="<?php echo set_value('zip'); ?>" class="form-control">

                    </div>

                </div>



                <div class="form-group" id="state">

                    <label class="control-label col-lg-2">States:</label>

                    <div class="col-lg-6">

                        <select id="states" name="state" class="form-control">

                            <?php foreach ($states as $v) {?>

                                <option value="<?php echo $v['state_prefix']; ?>"><?php echo $v['state_name'];?></option>

                            <?php }?>

                        </select>

                    </div>

                </div>



                <div class="form-group" id="city">

                    <label class="control-label col-lg-2">Cities:</label>

                    <div class="col-lg-6">

                        <select id="cities" name="city" class="form-control">

                            <?php foreach ($cities as $v) {?>

                                <option value="<?php echo $v['city']; ?>"><?php echo $v['city'];?></option>

                            <?php }?>

                        </select>

                    </div>

                </div>



                <!--<div class="form-group" id="jobstatus">

                    <label class="control-label col-lg-2">Job Status:</label>

                    <div class="col-lg-6">

                        <select id="jobstatus" name="jobstatus" class="form-control">

                            <?php /*foreach ($status as $k=>$v) {*/?>

                                <option value="<?php /*echo $k; */?>"><?php /*echo $v;*/?></option>

                            <?php /*}*/?>

                        </select>

                    </div>

                </div>-->



                <div class="form-group">

                    <label class="control-label col-lg-2"> Start Date: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('startdate'); ?>

                        <input type="text" name="startdate" id="datepicker" value="<?php echo set_value('startdate'); ?>" class="form-control pickadate-limits">

                    </div>

                </div>



               <!-- <div class="form-group">

                    <label class="control-label col-lg-2"> Completed Date: </label>

                    <div class="col-lg-6">

                        <?php /*echo form_error('completeddate'); */?>

                        <input type="text" name="completeddate" id="datepicker" value="<?php /*echo set_value('completeddate'); */?>" class="form-control pickadate-limits">

                    </div>

                </div>-->



                <div class="form-group">

                    <label class="control-label col-lg-2"> Completion Date: </label>

                    <div class="col-lg-6">

                        <?php echo form_error('completiondate'); ?>

                        <input type="text" name="completiondate" id="datepicker2" value="<?php echo set_value('completiondate'); ?>" class="form-control pickadate-limits">

                    </div>

                </div>



                <!-- <div class="form-group">

                    <label class="control-label col-lg-2"> Image: </label>

                    <div class="col-lg-6">

                        <input type="file" multiple="" name="image[]" id="image" class="form-control">

                    </div>
                </div> -->

                <div class="form-group">
                    <label for="image" class="control-label col-lg-2">Image(s):</label>
                    <div class="col-lg-6">
                        <div class="text-center">
                            <progress style="display:none" id="progressBarGall" value="0" max="100" style="width:300px;"></progress>
                        </div>
                        <div id="filedivGall"> </div>
                        <div id="errordivGall"> </div>
                        <div id="add_imgGall" class="fileUpBox">
                            <a id="up_button_gall" class="upload_btn" href="javascript:void(0)">Select Image 
                                <span class="icon-plus">
                                    <i class="icon-file-plus"></i>
                                </span>
                            </a>
                        </div>
                        <span class="instructions">JPEG, PNG or GIF file allowed </span>
                        <input id="pro_gall" class="input-item input-full" name="job_gallery" type="hidden">
                    </div>
                </div>



                <div class="form-group">

                    <div class="col-lg-3">

                        <button type="submit" class="btn bg-teal-400">Submit<i class="icon-arrow-right14 position-right"></i></button>

                        <a href="<?php echo base_url('admin/users')?>">

                            <button type="button" class="btn bg-teal-400">Cancel<i class="icon-arrow-right14 position-right"></i></button>

                        </a>

                    </div>

                </div>

            </fieldset>



        </form>



    </div>



</div>

<script>

    $(document).ready(function(){



        $('#states').on('change', function() {

            $("#loaderImg").show();

            var elm = $('option:selected', this);

            var id = elm.val();

            var url = '<?php echo base_url('admin/users/getCities');?>';

            $.ajax(

                {

                    url: url,

                    type: "POST",

                    dataType: "text",

                    data: {id:id},

                    success: function (data) {

                        if (data != '') {

                            //alert(JSON.stringify(data));

                            $("#loaderImg").hide();

                            $('#cities').html(data);

                        }



                        //data: return data from server

                    },

                    error: function (jqXHR, textStatus, errorThrown) {

                        $("#loaderImg").hide();

                        alert("Something went wrong... Please try again")

                        //if fails

                    }

                });

        });

    });
</script>

<script type="text/javascript">
    /*Uploaing Image Gallery Script */
function _(el){
    return document.getElementById(el);
}
function uploadFileGall()
{
    var form = $('#upload_form_gallery')[0]; // You need to use standart javascript object here
    var formData = new FormData(form);
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandlerGall, false);
    ajax.addEventListener("load", completeHandlerGall, false);
    ajax.addEventListener("error", errorHandlerGall, false);
    ajax.addEventListener("abort", abortHandlerGall, false);
    ajax.open("POST", "<?php echo base_url();?>admin/jobs/upload_file");
    ajax.send(formData);
}
function progressHandlerGall(event)
{
    $("#progressBarGall").show();
    $("#loaded_n_total").html("Uploaded "+event.loaded+" bytes of "+event.total);
    var percent = (event.loaded / event.total) * 100;
    $("#progressBarGall").val(Math.round(percent));
    $("#status").html(Math.round(percent)+"% uploaded... please wait");
}
function delete_file_gall(id)
{
    var fval = $('#pro_gall').val();
    var res = fval.split(",");
    var index = res.indexOf(id);
    {
    if(index > -1)
        res.splice(index, 1);
    }
    var ret = res.join();
    $('#pro_gall').val(ret)
    $('#filedidGall'+get_filename_gall(id)).hide();
  //   var addBtn = '<a id="up_button_gall" class="upload_btn" href="javascript:void(0)">';
  //    addBtn += 'Select Image';
  //    addBtn += '<span class="icon-plus">';
  //    addBtn += '<i class="fa fa-plus"></i>';
  //    addBtn += '</span>';
  //    addBtn += '</a>';                       
        // $('#add_imgGall').append(addBtn);
}
function get_filename_gall(name)
{
    var na = name.split('.');
    return na[0];
}
function completeHandlerGall(event)
{
    resp = jQuery.parseJSON(event.target.responseText);
    if (resp.hasOwnProperty('success')) {
        var fval = $('#pro_gall').val();
        if(fval == '')
        {
            $('#pro_gall').val(resp.filename);
        }
        else
        {
            $('#pro_gall').val(fval+','+resp.filename);
        }
        var filen = get_filename_gall(resp.filename);
        var hrm = '<div id="filedidGall'+filen+'" class="cut_file" style="background: #fff; padding: 5px; overflow: hidden;';
        hrm += 'margin: 0 0 20px;">';
        hrm += '<div class="img-thumb"><img src="<?php echo base_url();?>assets/docs/00000/'+resp.filename+'"/></div>';
        hrm += '<a onclick="delete_file_gall(';
        hrm += "'"+resp.filename+"'";
        hrm += ')"><span class="icon-remove"><i class="icon-cross"></i></span></a>';
        hrm += '</div>';
        $('#filedivGall').append(hrm);
        /*$('#up_button_gall').remove();*/
    }
    else if (resp.hasOwnProperty('error')) {
        alert(resp.error);
    }
    $("#progressBarGall").hide();
    $("#status").innerHTML = 'File upoaded successfully.';
    $("#progressBarGall").val(0);
    $('#gallery_inp').val('');
}
function errorHandlerGall(event)
{
    $("#status").innerHTML = "Upload Failed";
    alert("File uploading error");
    $("#progressBarGall").hide();
}
function abortHandlerGall(event)
{
    $("#status").innerHTML = "Upload Aborted";
    alert("File uploading error");
    $("#progressBarGall").hide();
}

$('#add_imgGall').on('click', '#up_button_gall', function() {
    $('#gallery_inp').click();
});
$('#gallery_inp').change(function(){
    var fval = $(this).val();
    var ext = fval.split('.').pop();
    var arr = ['jpg' , 'png' , 'gif'];
    if(fval != '')
    {
        if(arr.indexOf(ext) > -1)
        {
            uploadFileGall();
        }
        else
        {
            $('#image_inp').val('');
            var err = '<div class="alert alert-danger"> Invalid file type! </div>';
            $('#errordivGall').append(err);
            $('#errordivGall').delay(3000).fadeOut(300)
        }
    }
});
</script>

<!-- /CKEditor default