<style>
#prodimgs{
	display:inline-flex;
}
.mt20 {
    margin-right: 30px;
    margin-top: 20px;
}
.heading-video{
	padding:20px 15px;
}
.video{
	position:relative;
}
.glyphicon.glyphicon-remove.rm-video {
      float: right;
    position: relative;
    right: 12%;
    top: -1px;
}
upload-content strong{
	font-size:24px;
}
.FL{
	margin-bottom:10px;
}
</style>
<script>
base_url = '<?php echo base_url();?>'
$(document).ready(function(){
		$(".datepick").datetimepicker({ format: 'MM/DD/YYYY', ignoreReadonly: true ,maxDate : 'now'});
});
</script>
<link rel="stylesheet" media="screen" type="text/css" href="<?php echo base_url(); ?>css/colorbox.css" />
<script src="<?php echo base_url(); ?>js/jquery.colorbox.js"></script>
<script src="<?php echo base_url(); ?>js/myprofile.js"></script>
<script>
$(document).ready(function(){
	$(".upload, .workupload").colorbox({
		iframe: true,
		width: "650px",
		height: "650px"
	});
	$("#profile").validate({
		ignore		: [],
		rules		: {
			firstname	: {
                    required: function(element) {
                        return ( $("#tab_personal").hasClass('active'));
                    }
                },
			lastname	: {
                    required: function(element) {
                         return ( $("#tab_personal").hasClass('active'));
                    }
                },
			address		: {
                    required: function(element) {
                         return ( $("#tab_personal").hasClass('active'));
                    }
                },
			state		: {
                    required: function(element) {
                         return ( $("#tab_personal").hasClass('active'));
                    }
                },
			city		: {
                    required: function(element) {
                         return ( $("#tab_personal").hasClass('active'));
                    }
                },
			zip			: {
				
                    required: function(element) {
                         return ( $("#tab_personal").hasClass('active'));
                    },
                    postalcode	: true
               
				
			},
			phone		: {
				
                    required: function(element) {
                         return ( $("#tab_personal").hasClass('active'));
                    
                	},
				phoneUS		: true
			},
			//dob : 'required',
			email : { 
				
                    required: function(element) {
                         return ( $("#tab_personal").hasClass('active'));
                    
                }, 
				email : true, remote : '<?php echo base_url("checkexistinguseremail") ?>' },

			hasExpertise	: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 &&  $("#tab_expertise").hasClass('active'));
					}
				}
			},

			license	: {
                    required: function(element) {
                        return ( ($("#isContractor").val() == 1) && ($(".liceclas").hasClass('active')) );
                    }
                },
			
			insurance	: {
                    required: function(element) {
                        return ( ($("#isContractor").val() == 1 ) && ($(".incclas").hasClass('active')));
                    }
                },
			/*companyname	: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 );
					}
				}
			},
			taxid		: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 );
					}
				}
			},
			companyaddress	: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 );
					}
				}
			},
			companystate	: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 );
					}
				}
			},
			companycity	: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 );
					}
				}
			},
			companyzip	: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 );
					}
				},
				postalcode	: true
			},
			hasExpertise	: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 );
					}
				}
			},
			license	: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 );
					}
				}
			},
			insurance	: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 );
					}
				}
			},
			routingnumber	: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 );
					}
				}
			},
			accountnumber	: {
				required: {
					depends: function(element) {
						return ( $("#isContractor").val() == 1 );
					}
				}
			} */
		},
		messages	: {
			email : { required : "Please enter Email", email : "Please enter a valid Email" , remote : "Email already exists" },
			firstname	: 'Please enter First Name',
			lastname	: 'Please enter Last Name',
			address		: 'Please enter Address',
			state		: 'Please select State',
			city		: 'Please select City',
			dob : 'Please enter DOB',
			zip			: {
				required	: 'Please enter Zip',
				postalcode	: 'Please enter a valid Zip'
			},
			phone		: {
				required	: 'Please enter Phone#',
				phoneUS		: 'Please enter a valid Phone#'
			},
			companyname	: 'Please enter Company Name',
			taxid		: 'Please enter Tax ID',
			companyaddress	: 'Please enter Company Adress',
			companystate	: 'Please select Company State',
			companycity		: 'Please select Company City',
			companyzip	: {
				required	: 'Please enter Company Zip',
				postalcode	: 'Please enter a valid Zip'
			},
			hasExpertise	: 'Please select atleast one Expertise',
			license		: 'Please enter License Number',
			insurance	: 'Please enter Insurance Number',
			routingnumber	: 'Please enter Routing Number',
			accountnumber	: 'Please enter Account Number'
		}
	});

	jQuery.validator.addMethod("phoneUS", function (phone_number, element) {
		phone_number = phone_number.replace(/\s+/g, "");
		return this.optional(element) || phone_number.length > 9 &&
			phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);

	});

	jQuery.validator.addMethod("postalcode", function (postalcode, element) {
		return this.optional(element) || postalcode.match(/(^\d{5}(-\d{4})?$)|(^[ABCEGHJKLMNPRSTVXYabceghjklmnpstvxy]{1}\d{1}[A-Za-z]{1} ?\d{1}[A-Za-z]{1}\d{1})$/);
	});

	$(".expclas").click(function(){
        var mydata = $(this).attr('data').split('-');
		var expid = mydata[0];
		var slug = mydata[1];
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $("#exp_"+slug).remove();
        }else{
            $(this).addClass('active');
            $("#expertiseid").append('<input type="hidden" name="expertise[]" value="'+expid+'" id="exp_'+slug+'">');
        }
    });

     $("#changepassword").validate({
	     rules: {
	            password  : { required : true , minlength : 6},
	            confirmpassword     : {
					  required: true,
					  equalTo: "#pwd"
				  }
	    },
	      messages: {
	          password:  {required : 'Please enter Password' , minlength :'Password Minimum length must be 6 characters' },
	          confirmpassword: {
	              required: "Please Confirm Password",
	              equalTo: "Password mismatch"
	          }
	      }
	  });
});

function profileImage(imagekey,imagePath,type){
	type = $("#image_type").val();
	
	if(type == "work")
	{
		var imgCode =   '<div class="wimg_'+imagekey+' mt20 prof-pic">'+
						'<img style="height:150px;width:150px;border:1px solid #dddddd;object-fit: cover;" src="'+imagePath+'">'+
						'<a title="Delete" class="Crp-dlt" onclick="delImg(\''+imagekey+'\')"><span class="glyphicon glyphicon-remove"></span></a>'+
						'<input type="hidden" name="workimages[]" value="'+imagekey+'"/>'+
						'</div>';
		$("#prodimgs").prepend(imgCode);
		existing_images = parseInt('<?php echo count($work_images);?>')
		total_images = $('input[name="workimages[]"]').length + existing_images;
		if( total_images >= 6)
			$(".workupload").hide();
	}
	else
	{
		var imgCode = 	'<div class="pimg_'+imagekey+' mt20 prof-pic">'+
							'<img style="height:150px;width:150px;border:1px solid #dddddd;object-fit: cover;" src="'+imagePath+'">'+
							'<a title="Delete" class="Crp-dlt" onclick="delImg(\''+imagekey+'\')"><span class="glyphicon glyphicon-remove"></span></a>'+
							 '<input type="hidden" name="tempimage" value="'+imagekey+'"/>'+
						'</div>';
		$(".upload").hide();
		$("#profile_img").append(imgCode);
	}
}

function delImg(ke){
	if(confirm("Are you sure you want to remove  this image? ")){
		$(".pimg_"+ke).remove();
		$(".upload").show();
		$("#imgremoved").val(1);
		$("#tempimage").val('');
		$('input[value="'+ke+'"]').remove();
		
		total_images = $('input[name="workimages[]"]').length + $('.work-pic').length;
		
		if( total_images < 6)
			$(".workupload").show();
	}
}

function delWorkImg(key){
	if(confirm("Are you sure you want to remove  this image? ")){
		$(".wimg_"+key).remove();
		$("#imgremoved").val(1);
		$("#tempimage").val('');
		
		
		$.ajax(
		{
			url:base_url+'frontend/remove_work_img',
			data: 'k='+key,
			method:'post',
			success:function(data)
			{
				total_images = $('input[name="workimages[]"]').length + $('.work-pic').length;
				
				if( total_images < 6)
					$(".workupload").show();
			}
		});
	}
}

var ajaxurl= '<?php echo base_url() ?>';

function changepass(){
	if($("#changepassword").valid()){
		var password = $("#pwd").val();
		$.post(ajaxurl+"changepassword",{
	           password   : password
	        },function (data){
	            if(data.success){
	                $('#myprofilemodal').modal('hide');
	                $("#changepassword")[0].reset();
	            }


	        },'json');

	}


}
function getcity(type){

    var state = $("#state :selected").val();

    $.post(ajaxurl+"getcity",{
           state   : state
        },function (data){
            if(data.city){
                var city = data.city;
                var html = '<option value="">City</option>';
                console.log(city);
                
                $.each(data.city,function(index, item){
                    console.log(item);
                    html += '<option value="'+item.city+'">'+item.city+'</option>';
                });
            }

            $("#city").html(html);
        },'json');
}

function profileDetails(ths){
	$(".alltabs").removeClass('active');
	$(ths).addClass('active');
	var profdata = $(ths).attr('data');
	$(".profcls").hide();
	$("#prof_"+profdata).show();
	$("#routingnumber").val('');
	$("#accountnumber").val('');
	
}

function submitProfile(){
	if( $("#isContractor").val() == 1 ){
		var hasExp = 0;
		$("#hasExpertise").val('');
		$(".expclas").each(function(){
			if( $(this).hasClass('active') ){
				hasExp = 1;
			}
		});
		if( hasExp == 1 ){
			$("#hasExpertise").val(1);
		}
		$("#hasExpertise").valid();
	}
	if( $("#profile").valid() ){
		$("#profile").submit();
	}else if( $("#isContractor").val() == 1 ){
		var errdata = '';
		$(".error").each( function(){
			if( $(this).attr('type') == 'hidden' ){
				if( $(this).val() == '' ){
					errdata = $(this).parent('div').attr('id');
					return false;
				}
			}else{
				if( $(this).css('display') != 'none' ){
					errdata = $(this).parent().parent().parent('div').attr('id');
					return false;
				}
			}
		});
		errdata = errdata.split('_');
		$(".alltabs").removeClass('active');
		$(".profcls").hide();
		$("#tab_"+errdata[1]).addClass('active');
		$("#prof_"+errdata[1]).show();
	}
}
$(document).ready(function(){
	$(".vetran_clsss").on("click",function(){
            if($("#us_veteran").is(":checked")){
                
                $("#us_veteran").attr('checked',false);
			    
            }else{
                $("#us_veteran").attr('checked',true);
            }
        });
		
		$(".wbs").on("click",function()
		{
			$("#image_type").val("work");
		});
		
		$(".upload").on("click",function()
		{
			$("#image_type").val("profile");
		});
		
        $(".incclas").on("click",function(){
            if($(this).hasClass("active")){
                $("#insurance").hide();
                $(this).removeClass("active");
                $("#insurance").val('');
            }else{
                $("#insurance").show();
                $(this).addClass("active");
            }
            $("#profile").valid() ;
        });
    });
function showaccountdet(){
	//$("#submitdiv").show();
	$("#accountdiv").show();
}
</script>
<style type="text/css">
.licence-wrp {
    border-bottom: 1px solid #eee;
    padding: 10px 0;
    width: 100%;
}
.licence-wrp > h3 {
    color: #f79724;
}
.info-subhead {
    color: #585756;
    font-size: 18px;
    line-height: 31px;
    text-align: center;
}
textarea
{
	resize:vertical;
}
</style>
<?php if($hassuccess): ?>
	<script>
	var msg = '<?php echo $successmsg ?>';
	openDialog('Success',msg,"<?php echo base_url('dashboard'); ?>");
	</script>
<?php endif; ?>
<?php
    $attributes = array('id' => 'profile');
    echo form_open(base_url('myprofile'), $attributes);
?>
	<?php  if ( (isset ($haserror ) and $errormsg!='') ){ ?><p class="error_top" style="z-index:9999;"><?php echo $errormsg; ?></p><?php } ?>
	<section class="right-side divheight">
		<div class="container-right pb40-xs">
			<div class="col-xs-12">
				<h1>My Profile</h1>
                <div style="padding-top:6px;"><?php 
					if( $this->session->userdata('usertype') != 'homeowner' )
					{
						for($rt=1;$rt<=5;$rt++){ ?>
						<?php 
							if($rt <= round($rating['rating']))
							{
						?>
								<i class="stars rstars active" data="<?php echo $rt; ?>" ></i>
						<?php		
							}
							else
							{
						?>
								<i class="stars rstars" data="<?php echo $rt; ?>"></i>
						<?php 
							}
					}
					?>        
					<?php } ?>
					&nbsp; Total Jobs on Efynch: <span style="color:#f79224;">(<?php echo $rating['total_jobs']?>)</span>
				</div>
			</div>
			<?php if( $this->session->userdata('usertype') == 'contractor' ): ?>
            	<div style="padding:50px 20px 0 20px; margin-top:30px; display:block; color:red;">
                	<p style="position: relative; top: 5px;">Must be accurate for banking and payment processing</p>
                </div>
				<div class="col-xs-12 col-sm-12 col-md-10 mt20 p0-xs">
					<div class="btn-group btn-group-justified">
						<a href="javascript:void(0)" onclick="profileDetails(this)" data="personal" class="a-tabs-justify btn btn-default alltabs active proact" id="tab_personal">Personal Details </a>
						<?php /* ?><a href="javascript:void(0)" onclick="profileDetails(this)" data="company" class="a-tabs-justify btn btn-default alltabs proact" id="tab_company">Company Info </a> <?php */ ?>
						<a href="javascript:void(0)" onclick="profileDetails(this)" data="expertise" class="btn btn-default a-tabs-justify alltabs proact" id="tab_expertise">Area of Expertise </a>
						<a href="javascript:void(0)" onclick="profileDetails(this)" data="addition" class="a-tabs-justify btn btn-default alltabs proact" id="tab_addition">Banking & License Info </a>
						<a href="javascript:void(0)" onclick="profileDetails(this)" data="jobs" class="a-tabs-justify btn btn-default alltabs proact" id="tab_jobs">Completed Jobs </a>
					</div>
				</div>
			<?php endif; ?>

			<div class="profcls" id="prof_personal">
				<div class="col-xs-12 mt15 p0">
					<div class="col-xs-12 col-sm-6 col-md-3"><a data-toggle="modal" data-target="#myprofilemodal" class="btn-change" >Change Password</a></div>
				</div>

				<div class="col-xs-12 mt15 p0">
					<div class="col-xs-12 col-sm-6 col-md-6"><input class="input100-login radius30p" type="text" placeholder="Email" name="email" value="<?php echo trim($userDets['email']); ?>"/></div>
				</div>

				<div class="col-xs-12 clearfix p0">
					<div class="col-xs-12 col-sm-6 col-md-3"><input class="input100-login radius30p" type="text" placeholder="First Name" name="firstname" value="<?php echo $userDets['firstname']; ?>"/></div>
					<div class="col-xs-12 col-sm-6 col-md-3"><input class="input100-login radius30p" type="text" placeholder="Last Name" name="lastname" value="<?php echo $userDets['lastname']; ?>"/></div>
					<div class="col-xs-12 col-sm-6 col-md-3"><textarea class="input100-login radius30p" placeholder="Address" name="address"><?php echo $userDets['address']; ?></textarea></div>
				</div>

				<div class="col-xs-12 clearfix p0">
					<div class="col-xs-12 col-sm-6 col-md-3"><div class="select-login">
						<select name="state"  id="state">
							<option value=""> --Select—</option>
							<?php if( !empty( $states ) ):
									foreach( $states as $st ): ?>
										<option value="<?php echo $st['state_prefix']; ?>" <?php if( $userDets['state'] == $st['state_prefix'] ): ?>selected<?php endif; ?>><?php echo $st['state_prefix']; ?></option>
							<?php endforeach; endif; ?>
						</select>
					</div></div>
					<div class="col-xs-12 col-sm-6 col-md-3">
						<?php /*?><div class="select-login">
						<select name="city" id="city">
							<option value="">City</option>
							<?php if( !empty( $cities ) ):
									foreach( $cities as $ct ): ?>
										<option value="<?php echo $ct['city']; ?>" <?php if( $userDets['city'] == $ct['city'] ): ?>selected<?php endif; ?>><?php echo $ct['city']; ?></option>
							<?php endforeach; endif; ?>
						</select>
					</div><?php */ ?>
					<input class="input100-login radius30p" type="text" placeholder="City" name="city" value="<?php echo $userDets['city']; ?>"/>
				</div>
					<div class="col-xs-12 col-sm-6 col-md-3"><input class="input100-login radius30p" type="text" placeholder="Zip" name="zip" value="<?php echo $userDets['zip']; ?>"/></div>
				</div>

				<div class="col-xs-12 clearfix p0">
					<div class="col-xs-12 col-sm-6 col-md-3"><input class="input100-login radius30p" type="text" placeholder="Phone" name="phone" value="<?php echo $userDets['phone']; ?>"/></div>
					<div class="col-xs-12 col-sm-6 col-md-3"><input class="input100-login radius30p datepick" type="text" placeholder="Date of Birth" name="dob" value="<?php echo ($userDets['dob'] != '' and $userDets['dob'] != '0000-00-00') ? date('m/d/Y',strtotime($userDets['dob'])) : ''; ?>"/></div>
					<?php if( $this->session->userdata('usertype') == 'contractor' ): ?>
					<div class="col-xs-12 col-sm-6 col-md-3"><input class="input100-login radius30p" type="text" placeholder="Company Legal Name" name="companyname" value="<?php echo $contractDets['companyname']; ?>"/> </div>
					<?php endif; ?>
				</div>
				<?php if( $this->session->userdata('usertype') == 'contractor' ): ?>
				<div class="col-xs-12 clearfix p0">
					<div class="col-xs-12 col-sm-6 col-md-3"><input class="input100-login radius30p" type="text" placeholder="Years of Experiance" name="experiance" value="<?php echo $userDets['experiance']; ?>"/></div>
					<div class="col-xs-12 col-sm-6 col-md-3"><textarea class="input100-login radius30p" placeholder="Description of Business" name="businessdescription"><?php echo $userDets['businessdescription']; ?></textarea></div>
                    
					<div class="col-xs-12 col-sm-6 col-md-3">
                    <textarea class="input100-login radius30p" placeholder="Certifications" name="certifications"><?php echo $certifications; ?></textarea>
                    </div>

				</div>
				<div class="col-sm-12 col-md-10 col-lg-8 clearfix">
				  <div class="col-sm-12 col-md-6 p0-xs clearfix pt30">
					<h4 class="FL mt5">U.S. Veteran</h4>
					<label class="switch FR">
					  <input type="checkbox" value="1" name="us_veteran" id="us_veteran" <?php if($contractDets['us_veteran'] =='1'){ ?>checked="checked"<?php } ?>>
					  <div class="slider round vetran_cls <?php if($contractDets['us_veteran'] !=''){ ?>active<?php } ?>"></div>
					</label>
				  </div>
				  
				</div>
                
                <div class="col-xs-12 clearfix p0">
					
                    
					<div class="col-xs-12 col-sm-6 col-md-9">
                    <textarea class="input100-login radius30p" style="height:129px;" placeholder="Introduction" name="introduction"><?php echo $introduction; ?></textarea></div>

				</div>
                
                <div class="col-xs-12 clearfix p0">
					<div class="col-xs-12 col-sm-6 col-md-9"><textarea style="height:250px;" class="input100-login radius30p" placeholder="Overview of Experience" name="overview_experience"><?php echo $overview_experience; ?></textarea></div>
					
					

				</div>
               
                
                <div class=" clearfix p0">
                	<div class="col-md-10 col-sm-10"> 
                    <div class="upload-content" style="padding:20px 40px 0 0"><strong style="font-size:26px;">Upload Pictures:</strong> We recommend to include work examples, at least 1 picture of yourself, and if you are licensed please include a picture of the license or some verification.</div>
				<a href="<?php echo base_url().'Crop/index/work'; ?>" class="btn-upload workupload wbs" style="<?php if( count($work_images) >= 6 ): ?>display:none;<?php else: ?>display:block;<?php endif; ?>"><span>Upload </span></a>
               
                <input type="hidden" value="" id="image_type" />
                	
                </div>
              </div>      
				<div class="col-xs-12 clearfix p0">
					<div class="col-md-12 col-sm-12" id="prodimgs">
						<?php 
							foreach($work_images as $img)
							{
						?>
                        		<div class="wimg_<?php echo $img['dockey']; ?> mt20 prof-pic work-pic">
								<img style="height:150px;width:150px;border:1px solid #dddddd;object-fit: cover;" src="<?php echo base_url()."assets/docs/00000/".$img['dockey'].".".$img['docext']; ?>">
								<a title="Delete" class="Crp-dlt" onclick="delWorkImg('<?php echo $img['dockey']; ?>')">
                                	<span class="glyphicon glyphicon-remove"></span>
                                </a>
								</div>
                        <?php		
							}
						?>
					</div>
                    
				</div>
                
                <div class="col-xs-12 clearfix p0">
                	<div class="col-md-12 col-sm-12" id="profile_img">
                	<br /><br />
                                <div>
                                	<strong style="font-size:26px;">Profile Image:</strong> Should be a picture of yourself for security purposes.
                                </div>
                                <br />
						<?php if( $userDets['imagekey'] != '' ): ?>
							<div class="pimg_<?php echo $userDets['imagekey']; ?> mt20 prof-pic">
								<img style="height:150px;width:150px;border:1px solid #dddddd;object-fit: cover;" src="<?php echo $userDets['Img']; ?>">
								<a title="Delete" class="Crp-dlt" onclick="delImg('<?php echo $userDets['imagekey']; ?>')"><span class="glyphicon glyphicon-remove"></span></a>
							</div>
						<?php endif; ?>
                        <a href="<?php echo base_url().'Crop/index/profile'; ?>" class="btn-upload upload" style="<?php if( $userDets['imagekey'] != '' ): ?>display:none;<?php else: ?>display:block;<?php endif; ?>"><span>Change Profile Picture </span></a>
						<input type="hidden" name="tempimage" value="" id="tempimage"/>
						<input type="hidden" name="imgremoved" value="0" id="imgremoved"/>
                	</div>
				 <div class="col-xs-12 clearfix p0">
                  <div><h2 class="heading-video">Introductory Video</h2></div>
					
                    
                    		<div id="video_player"  class="video" <?php if(!$userDets['intro_video'])echo "style='display:none;'";?>>
                            	<br /><br />
                               <a href="#" onclick="delIntroVideo('<?php echo $userDets['userkey']; ?>');return false;"> <span class="glyphicon glyphicon-remove rm-video"></span></a>
                            	<video width="100%" height="400px" id="player" controls>
                                	
									<source id="video_source" src="<?php echo base_url()."assets/videos/".$userDets['intro_video']?>" type="video/mp4" autoplay>
									Your browser does not support the video tag.
								</video>
                               
                            </div>
                    
                    		<div class="col-lg-6" id="upload_video" <?php if($userDets['intro_video'])echo "style='display:none;'";?>>
                            	<br /><br />
                                <br />
                              <div class="col-xs-12 clearfix p0">
                                <div class="progress">
                                    <div class="progress-bar" id="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" 
                                    style="width:0%">
                                      <span class="sr-only"><span id="progress_count">0</span>% Complete</span>
                                    </div>
                                 </div>
                                <a href="#" class="btn-upload" for="intro_video" id="video_upload" style="display:block;">
                                <span>Upload video</span></a>
                                <br /><br /><br />
                                <input value="" id="intro_video" type="file" style="display:none;">
                                </div>
                                <div id="preview_video"></div>
                                
                            </div>
                         
                </div>
				
                
                </div>
                
                
                
				<?php endif; ?>

				
			</div>

			<?php if( $this->session->userdata('usertype') == 'contractor' ): 
			?>

			
			<!-- skills tab-->
			<div class="profcls" id="prof_expertise" style="display:none;">
				<div class="col-xs-12 clearfix p0">
					<?php if(!empty($expertises)){
						foreach($expertises as $exp){ ?>
							<a class="col-xs-4 col-sm-4 col-md-3 expertise-area  expclas <?php if( isset( $userExpertises[$exp['expertiseid']] ) ): ?>active<?php endif; ?>" data="<?php echo $exp['expertiseid']."-".$exp['slug']; ?>"> <img src="<?php echo base_url(); ?>images/expertise/<?php echo $exp['webimg']; ?>" />  <span><?php echo $exp['name']; ?></span> </a>
					<?php } } ?>
					<div id="expertiseid" style="display:none;" >
						<?php if( !empty( $expertises ) ):
								foreach( $expertises as $ue => $ur ):
									if( isset( $userExpertises[$ur['expertiseid']] ) ): ?>
									<input type="hidden" name="expertise[]" value="<?php echo $ur['expertiseid']; ?>" id="exp_<?php echo $ur['slug']; ?>">
						<?php endif; endforeach; endif; ?>
					</div>
				</div>
				<div class="clear"></div>
				<input type="hidden" name="hasExpertise" id="hasExpertise" value="<?php if( !empty( $userExpertises ) ): ?>1<?php endif; ?>"/>
			</div>
			<div class="clear"></div>
            <!-- banking info tab -->
			<div class="profcls" id="prof_addition" style="display:none;">
				<div class="col-sm-12 col-md-10 col-lg-8 clearfix mt30 pl30">
					<div class="licence-wrp">
					<span><h3>Licence Info</h3></span> <span style="color:red;">MUST be accurate to process payments</span>	
				</div>
				</div>
				
				
				<div class="col-xs-12 clearfix p0">
					
					
					<div class="col-sm-12 col-md-10 col-lg-8 clearfix">
		              <div class="col-sm-12 col-md-6 p0-xs clearfix pt30">
		                <h4 class="FL mt5">Licensed</h4>
		                 <div class="select-login">
                      		<select name="license"  id="license">
                               <option value="no">No</option>
                               <option value="yes">Yes</option>
                           </select>
                      </div>
		              </div>
		              
		            </div>

		            <div class="col-sm-12 col-md-10 col-lg-8 clearfix">
		              <div class="col-sm-12 col-md-6 p0-xs clearfix pt30 mt15-xs">
		                <h4 class="FL mt5">Insurance</h4>
		                 <div class="select-login">
                      		<select name="insurance" id="insurance">
                               <option value="no">No</option>
                               <option value="yes">Yes</option>
                               <option value="ameteur">Amateur</option>
                           </select>
                      	</div>
                        <script>
                        	$("#license").val("<?php echo $contractDets['license']?>");
							$("#insurance").val("<?php echo $contractDets['insurance']?>");
                        </script>
		              </div>
                      
                      
                      
		              
		            </div>
				
                
                </div>
				<div class="col-sm-12 col-md-10 col-lg-8 clearfix">
		              <div class="col-sm-12 col-md-6 p0-xs clearfix pt30 mt15-xs">
		                <h4 class="FL mt5">Home Improvement License</h4>
		              </div>
		              <div class="col-sm-12 col-md-6 p0-xs"><input class="input100-login radius30p" type="text" placeholder="Home Improvement License" name="taxid" value="<?php echo $contractDets['taxid']; ?>"/></div>
                      <br />
                      <div class="col-sm-12  p0-xs" style="color:red; margin-top:20px;">We recommend uploading a picture of you license in Personal Details is appropriate. You may be asked to provide this in the future</div>
		        </div>

				
				<div class="col-sm-12 col-md-10 col-lg-8 clearfix mt30 pl30">
					<div class="licence-wrp">
						<h3 class="FL">Banking Info</h3>
						<?php if($userDets['bt_accountverified'] == '1'): ?>
						<a class="btn-change FR" style="width:80px;" onclick="showaccountdet()">Edit</a>
						<?php endif; ?>
						<div class="clear"></div>
					</div>
                    <div style="color:red;padding:20px;">For Direct Payment Processing. Must match information on the previous information page.
</div>
				</div>
				
				<?php if($userDets['bt_accountverified'] == '1'): ?>
					<div class="col-sm-12 col-md-10 col-lg-8 clearfix mt40">
						<h3 class="info-subhead">EFynch and EFynch.com is here to help you with all you home improvement needs. We assist you in the location of pros and other help around the home including the following services:</h3>
						</div>
			    <?php endif; ?>

				<div id="accountdiv" class="col-sm-12 col-md-10 col-lg-8 clearfix" <?php if($userDets['bt_accountverified'] == '1'): ?>style="display:none;"<?php endif; ?>>
					<div class="col-xs-12 col-sm-6 p0-xs"><input class="input100-login radius30p" type="text" placeholder="Routing Number" id="routingnumber" name="routingnumber" value=""/></div>
					<div class="col-xs-12 col-sm-6 p0-xs"><input class="input100-login radius30p" type="text" placeholder="Account Number" id="accountnumber" name="accountnumber" value=""/></div>
				</div>
			</div>
			<div class="profcls" id="prof_jobs" style="display:none;">
				<?php 
               if(empty($completedJobs)){
               ?>
               <div class="clearfix"></div>
               <div class="alert alert-danger mt10">No Records Found</div>
               <?php
               }else{
                  foreach($completedJobs as $job){
               ?>
               <div class="dash-bids clearfix">
                  <div class="col-xs-12 col-md-12 col-lg-6">
                     <div class="profile-img-md radius50 col-xs-12 col-sm-4 p0">
                        <?php 
                        if(!empty($job['dockey'])){
                           $path = base_url($this->corefunctions->getMyPath($job['jobid'], $job['dockey'], $job['docext'], 'assets/docs/'));
                        }else{
                           $path = base_url('images/def_job.jpg');
                        }
                        ?>
                        <img src="<?=$path?>" />
                     </div>
                     <div class="dash-bid-cont col-xs-12 col-sm-8 p0">
                        <h2><?php echo $job['jobname']; ?></h2>
                        <h4>Bid Amount : <span class="big-amount">$<?php echo number_format($job['bidamount'],2, '.', ''); ?></span></h4>
                        <h3><?php echo  ( strlen( $job['jobdescription'] ) > 100 ) ? substr(nl2br(stripslashes($job['jobdescription'])),0,100)."..." : nl2br(stripslashes($job['jobdescription'])); ?></h3>
                        <h4><?php echo $job['name']; ?>  <span>| <?php echo date('m/d/Y',strtotime($job['startdate'])); ?> </span> </h4>
                        <div class="mt10">
                           <?php 
                           for($i = 1; $i <= 5; $i++){
                              $class = '';
                              if ($i <= $job['rating'] ){
                                 $class = 'active';
                              }
                           ?>
                           <i class="stars <?=$class?>"></i>
                           <?php
                           }
                           ?>
                           <div class="clearfix"></div>
                           <a target="_blank" href="<?php echo base_url('view-rating/'.$job['jobkey'].'/'.$userkey)?>">Public Rating</a>
                           |
                           <a target="_blank" href="<?php echo base_url('view-rating/'.$job['jobkey'].'/'.$userkey)?>?type=private">Private Rating</a>
                        </div>
                     </div>
                  </div>
                  <div class="col-xs-12 col-md-12 col-lg-6 mt20-sm">
                     <div class="col-sm-6 p0 clearfix">
                        <strong>Job Given By</strong>
                        <div class="profile-name-sm">
                           <span class="profile-img-sm radius50">
                              <?php 
                              if(!empty($job['user_img'])){
                                 $path = base_url($this->corefunctions->getMyPath($job['userid'], $job['user_img'], $job['user_img_ext'], 'assets/profImgs/crop/'));
                              }else{
                                 $path =  base_url('images/defaultimg.jpg');
                              }
                              ?>
                              <img src="<?=$path?>">
                           </span>
                           <h2 class="pt5"><?php echo $job['firstname']." ".$job['lastname'][0]; ?> </h2>
                           <h3><?php echo $job['address']; ?>, <?php echo $job['state']; ?> <?php echo $job['zip']; ?></h3>
                        </div>
                     </div>
                     <div class="col-sm-4 pr5 date-wraps pl0-xs"> <p>Completed on</p> <h5><?php echo date('m/d/Y',strtotime($job['completiondate'])); ?></h5></div>
                  </div>
               </div>
               <?php
                  } 
               }
               ?>
			</div>
			<?php endif; ?>

			<div class="col-xs-12 clearfix p0 mt30" id="submitdiv">
				<div class="col-sm-3 col-sm-push-3" style="margin-bottom:30px;"><input class="submit100-login radius30p" type="button" value="Save" onclick="submitProfile()" /></div>
			</div>
			<input type="hidden" name="act" value="1"/>
			<input type="hidden" name="isContractor" id="isContractor" value="<?php echo ( $this->session->userdata('usertype') == 'contractor' ) ? 1 : 0; ?>"/>
			<div class="clear"></div>
		</div>
	</section>
</form>
<div class="clear"></div>
</div>


<div id="myprofilemodal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form name="changepassword" id="changepassword" method="post" >
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Password</h4>
      </div>
      <div class="modal-body">

        <div class="col-sm-12 pl5 pt20 FN tc pb10 strwrp clearfix">
        	<div class="col-sm-12"><input class="input100-login radius30p" id="pwd" type="password" placeholder="Password" name="password" /></div>
			<div class="col-sm-12"><input class="input100-login radius30p" type="password" placeholder="Confirm Password" name="confirmpassword" /></div>



		 </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="changepass()" >Submit</button>
      </div>
    </div>
    </form>

  </div>
</div>




