<section class="right-side divheight">
   <div class="container-right pr0 pl0">
      <div class="col-sm-12 col-md-12 col-lg-12 pl0 pr0">
         <a href="<?php echo base_url('postjob'); ?>" class="icon-dash post-a-job"><i></i><span>Post a Job</span></a>
         <a href="<?php echo base_url('owner/bidding'); ?>" class="icon-dash my-job-post">
            <i></i><span>My Job Posts</span>
            <p class="badge-job-inside"><?php echo $allcounts['myjobscount']; ?></p>
         </a>
         <a href="<?php echo base_url('notification'); ?>" class="icon-dash my-notification"><i></i><span>Notification</span></a>
         <a href="<?php echo base_url('messages'); ?>" class="icon-dash my-messages"><i></i><span>Messages</span></a>
         <a href="<?php echo base_url('accountbalance'); ?>" class="icon-dash my-payments"><i></i><span>My Payments</span></a>
         <?php /* ?><a href="#" class="icon-dash my-disclaimers"><i></i><span>Disclaimers</span></a><?php */ ?>
         <div class="col-xs-12 pl30 pr30 p5-xs">
            <?php 
               $image = "";
               if(!empty($profileDet['imagekey']))
               {
               	$image = base_url()."assets/profImgs/crop/00000/".$profileDet['imagekey'].".".$profileDet['imageext'];
               }
               else
               {
               	$image = base_url()."images/defaultimg.jpg";
               }
               ?>
            <style>
               .detail-info{
               background: #e6e6e6;
               padding: 10px 20px;
               min-height: 40px;
               }
               .uperdet{
               margin-top:10px;
               font-weight: bold;
               }
               .outer-layer{
               padding: 10px;
               }
               .first-layer{
               margin-top:30px;
               }
            </style>
            <div class="profile-img left-fix-img radius50">
               <img src="<?php echo $image; ?>" />
            </div>
            <div class="col-md-12 text-center">
               <strong><?php echo $profileDet['firstname'].' '.$profileDet['lastname']; ?></strong>
            </div>
            <div class="col-sm-12 mt20 mb20">
               <div class="btn-group btn-group-justified">
                  <a href="javascript:{}" id="profile_links" onclick="showMe(this,'profile')" class="a-tabs-justify btn btn-default active">Profile </a>
                  <a href="javascript:{}" id="jobs_link" onclick="showMe(this,'jobs')" class="btn btn-default a-tabs-justify ">Completed Jobs</a>
               </div>
            </div>
            <div id="profile">
               <div class="col-md-12 outer-layer first-layer">
                  <div class="col-md-6">
                     <div class="detail-para">
                        <div class="col-md-4 uperdet">Address:</div>
                        <div class="col-md-8 radius30p detail-info"><?php echo $profileDet['city'].' '.$profileDet['state'].' ,'.$profileDet['zip']; ?></div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="detail-para">
                        <div class="col-md-4 uperdet">Phone:</div>
                        <div class="col-md-8 radius30p detail-info"><?php echo $profileDet['phone']; ?></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 outer-layer">
                  <div class="col-md-6">
                     <div class="detail-para">
                        <div class="col-md-4 uperdet">Company:</div>
                        <div class="col-md-8 radius30p detail-info"><?php echo $companyname; ?></div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="detail-para">
                        <div class="col-md-4 uperdet">Experience:</div>
                        <div class="col-md-8 radius30p detail-info"><?php echo $profileDet['experiance']; ?></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 outer-layer">
                  <div class="col-md-12">
                     <div class="detail-para">
                        <div class="col-md-2 uperdet">Email:</div>
                        <div class="col-md-10 radius30p detail-info"><?php echo $profileDet['email']; ?></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 outer-layer">
                  <div class="col-md-12">
                     <div class="detail-para">
                        <div class="col-md-4 uperdet">Introduction:</div>
                        <div class="col-md-8 radius30p detail-info" style="min-height: 120px;"><?php echo $introduction; ?></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 outer-layer">
                  <div class="col-md-12">
                     <div class="detail-para">
                        <div class="col-md-4 uperdet">Overview of Experience:</div>
                        <div class="col-md-8 radius30p detail-info" style="min-height: 150px;"><?php echo $overview_experience; ?></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 outer-layer">
                  <div class="col-md-12">
                     <div class="detail-para">
                        <div class="col-md-4 uperdet">Working Images:</div>
                        <div class="col-md-8" id="prodimgs">
                           <?php 
                              foreach($work_images as $singImg)
                              {
                              	?>
                           <div class=" col-md-4 work-pic" style="margin-top: 10px;">
                              <img style="height:150px;width:150px;border:1px solid #dddddd;object-fit: cover;" src="<?php echo base_url(); ?>assets/docs/00000/<?php echo $singImg['dockey']; ?>.<?php echo $singImg['docext']; ?>">
                           </div>
                           <?php
                              }
                              ?>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 outer-layer">
                  <div class="col-md-12">
                     <div class="detail-para">
                        <div class="col-md-4 uperdet">Introductory video:</div>
                        <div class="col-md-8">
                           <?php 
                              if(!empty($profileDet['intro_video']))
                              {
                              	?>
                           <video width="100%" height="400px" id="player" controls="">
                              <source id="video_source" src="<?php echo base_url(); ?>assets/videos/<?php echo $profileDet['intro_video']; ?>" type="video/mp4" autoplay="">
                              Your browser does not support the video tag.
                           </video>
                           <?php
                              }
                              ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div id="jobs" style="display: none;">
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
                           <a target="_blank" href="<?php echo base_url('view-rating/'.$job['jobkey'].'/'.$userkey)?>">View Rating Given</a>
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
         </div>
      </div>
      <div class="clear"></div>
   </div>
</section>
<!--Wrapper Starts from left menu-->
</div>
<script>
   function showMe(elem,id){
      
      if( id == "profile"){
         $("a#jobs_link").removeClass("active");
         $("div#jobs").hide();
      }else{
         $("a#profile_links").removeClass("active");
         $("div#profile").hide();
      }
      $(elem).blur();
      $(elem).addClass("active");
      $("div#"+id).show();
   }
</script>