<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
<link rel="stylesheet" href="<?php echo base_url(); ?>css/rating.css">
<section class="right-side divheight" style="height: 403px;">
<?php 
$checked_val = 1;
?>
  <div class="container-right pr0 pl0">
		<div>
			<div class="page_header">
				<h1>Private Rating</h1>
			</div>
      <div class="col-md-12 col-sm-12">
        <div class="page-title">
            <h2><?=$job_data['jobname']?></h2>
        </div>
        <form method="post" action="<?php echo  base_url(); ?>save-rating" onsubmit="return validate();">
          <div class="col-md-12 col-sm-12 Private_page">
            <div class="Private_page_secton">
              <div class="label_txt">Name On a scale of 1 - 10 please rate the Pro in each category (Use the scale below as guidance)</div>
              <div class="block-div">
                <div class="temp-radio inline-radio">
                  <p>1 - 2</p>
                  <div class="show-radio" style="padding-left: 0px;">
                      <!-- <input type="radio" name="scale" id="radio1" value="bad" > -->
                      <label for="radio1">
                          Bad 
                      </label>
                  </div>
                </div>  
                </div>    
                <div class="block-div">  
                  <div class="temp-radio inline-radio">
                    <p>3 - 4</p>
                    <div class="show-radio">
                        <!-- <input type="radio" name="scale" id="radio2" value="below_average"> -->
                        <label for="radio2">
                            Slightly below average
                        </label>
                    </div>
                  </div>  
                </div>    
                <div class="block-div">  
                  <div class="temp-radio inline-radio">
                    <p>5 - 6</p>
                    <div class="show-radio">
                      <!-- <input type="radio" name="scale" id="radio3" value="average" checked > -->
                      <label for="radio3">
                          Average 
                      </label>
                    </div>
                </div> 
              </div>    
              <div class="block-div">
                  <div class="temp-radio inline-radio">
                    <p>7 - 9</p>   
                    <div class="show-radio">
                      <!-- <input type="radio" name="scale" id="radio4" value="above_average" > -->
                      <label for="radio4">
                          Slightly above average 
                      </label>
                    </div>
                  </div>
                </div>    
                  <div class="block-div">
                    <div class="temp-radio inline-radio">
                        <p class="last-child">10</p>     
                         <div class="show-radio">
                            <!-- <input type="radio" name="scale" id="radio5" value="extraordinary"  > -->
                            <label for="radio5">
                                Extraordinary
                            </label>
                        </div>
                    </div>  
                </div>      
            </div>
            <div class="heder-tit">
              <h3>Timing:</h3>
            </div>
            <div class="Private_page_secton">
              <div class="label_txt">1) Was your Home Pro on time and kept all meetings unless a proper excuse and warning was provided?</div>
              <div class="rating-panel">
                <?php 
                for($i = 1; $i <= 10; $i++){
                  $checked = "";
                    if($i == $checked_val){
                      $checked = "checked";
                    }
                ?>
                <div class="sub-panel">
                  
                  <div class="tooltip" style="opacity: 1; z-index: 1;">
                    <?php 
                    if($i == 1 || $i == 5 || $i == 10){
                      if($i == 1){
                        $txt = "Bad";
                      }else if($i == 5){
                        $txt = "Average";
                      }else{
                        $txt = "Extraordinary";
                      }
                    ?>
                    <span class="tooltiptext"><?=$txt?></span>
                    <?php 
                    }
                    ?>
                  </div>
                  <div class="radio">
                    <div class="inline-radio">
                      <input type="radio" name="time_quality" id="time_quality_radio<?=$i?>" value="<?=$i?>" <?=$checked?> >
                      <label for="time_quality_radio<?=$i?>"></label>
                    </div>
                  </div>
                  <p><label for="time_quality_radio<?=$i?>"><?=$i?></label></p>
                </div>
                <?php
                }
                ?>
                <div class="clear"></div>
              </div>
            </div>
            <div class="Private_page_secton">
              <div class="label_txt">2) Given the quality of work you received, was the Home Pro efﬁcient with the time spent working on the project?</div>
              <div class="rating-panel">
                <?php 
                for($i = 1; $i <= 10; $i++){
                  $checked = "";
                    if($i == $checked_val){
                      $checked = "checked";
                    }
                ?>
                <div class="sub-panel">
                  <div class="tooltip" style="opacity: 1; z-index: 1;">
                    <?php 
                    if($i == 1 || $i == 5 || $i == 10){
                      if($i == 1){
                        $txt = "Bad";
                      }else if($i == 5){
                        $txt = "Average";
                      }else{
                        $txt = "Extraordinary";
                      }
                    ?>
                    <span class="tooltiptext"><?=$txt?></span>
                    <?php 
                    }
                    ?>
                  </div>
                  <div class="radio">
                    <div class="inline-radio">
                      <input type="radio" name="work_quality" id="work_quality<?=$i?>" value="<?=$i?>" <?=$checked?> >
                      <label for="work_quality<?=$i?>"></label>
                    </div>
                  </div>
                  <p><label for="work_quality<?=$i?>"><?=$i?></label></p>
                </div>
                <?php
                }
                ?>
                <div class="clear"></div>
              </div>
            </div>
            <div class="Private_page_secton">
              <div class="label_txt">3) Was the Home Pro ﬂexible and able to schedule work according to your schedule?</div>
              <div class="rating-panel">
                <?php 
                for($i = 1; $i <= 10; $i++){
                  $checked = "";
                    if($i == $checked_val){
                      $checked = "checked";
                    }
                ?>
                <div class="sub-panel">
                  <div class="tooltip" style="opacity: 1; z-index: 1;">
                    <?php 
                    if($i == 1 || $i == 5 || $i == 10){
                      if($i == 1){
                        $txt = "Bad";
                      }else if($i == 5){
                        $txt = "Average";
                      }else{
                        $txt = "Extraordinary";
                      }
                    ?>
                    <span class="tooltiptext"><?=$txt?></span>
                    <?php 
                    }
                    ?>
                  </div>
                  <div class="radio">
                    <div class="inline-radio">
                      <input type="radio" name="schedule" id="schedule<?=$i?>" value="<?=$i?>" <?=$checked?> >
                      <label for="schedule<?=$i?>"></label>
                    </div>
                  </div>
                  <p><label for="schedule<?=$i?>"><?=$i?></label></p>
                </div>
                <?php
                }
                ?>
                <div class="clear"></div>
              </div>
            </div>
            <div class="Private_page_secton">
              <div class="label_txt">4) Overall: How would you rate the home pro on their consideration of your time?</div>
              <div class="rating-panel">
                <?php 
                for($i = 1; $i <= 10; $i++){
                  $checked = "";
                    if($i == $checked_val){
                      $checked = "checked";
                    }
                ?>
                <div class="sub-panel">
                  <div class="tooltip" style="opacity: 1; z-index: 1;">
                    <?php 
                    if($i == 1 || $i == 5 || $i == 10){
                      if($i == 1){
                        $txt = "Bad";
                      }else if($i == 5){
                        $txt = "Average";
                      }else{
                        $txt = "Extraordinary";
                      }
                    ?>
                    <span class="tooltiptext"><?=$txt?></span>
                    <?php 
                    }
                    ?>
                  </div>
                  <div class="radio">
                    <div class="inline-radio">
                      <input type="radio" name="overall_comments" id="overall_comments<?=$i?>" value="<?=$i?>" <?=$checked?> >
                      <label for="overall_comments<?=$i?>"></label>
                    </div>
                  </div>
                  <p><label for="overall_comments<?=$i?>"><?=$i?></label></p>
                </div>
                <?php
                }
                ?>
                <div class="clear"></div>
              </div>
            </div>
            <div class="heder-tit">
                <h3>Value:</h3>
            </div>
            <div class="Private_page_secton">
              <div class="label_txt">1) How satisﬁed were you with the ultimate price of the work which was performed? </div>
                <div class="rating-panel">
                  <?php 
                  for($i = 1; $i <= 10; $i++){
                    $checked = "";
                    if($i == $checked_val){
                      $checked = "checked";
                    }
                  ?>
                  <div class="sub-panel">
                    <div class="tooltip" style="opacity: 1; z-index: 1;">
                    <?php 
                    if($i == 1 || $i == 5 || $i == 10){
                      if($i == 1){
                        $txt = "Bad";
                      }else if($i == 5){
                        $txt = "Average";
                      }else{
                        $txt = "Extraordinary";
                      }
                    ?>
                    <span class="tooltiptext"><?=$txt?></span>
                    <?php 
                    }
                    ?>
                  </div>
                    <div class="radio">
                      <div class="inline-radio">
                        <input type="radio" name="price_comments" id="price_comments<?=$i?>" value="<?=$i?>" <?=$checked?> >
                        <label for="price_comments<?=$i?>"></label>
                      </div>
                    </div>
                    <p><label for="price_comments<?=$i?>"><?=$i?></label></p>
                  </div>
                  <?php
                  }
                  ?>
                  <div class="clear"></div>
                </div>
              </div>
            <div class="Private_page_secton">
              <div class="label_txt">2) Was the initial bid accurate and did not change without true justiﬁcation? </div>
                <div class="rating-panel">
                  <?php 
                  for($i = 1; $i <= 10; $i++){
                    $checked = "";
                    if($i == $checked_val){
                      $checked = "checked";
                    }
                  ?>
                  <div class="sub-panel">
                    <div class="tooltip" style="opacity: 1; z-index: 1;">
                    <?php 
                    if($i == 1 || $i == 5 || $i == 10){
                      if($i == 1){
                        $txt = "Bad";
                      }else if($i == 5){
                        $txt = "Average";
                      }else{
                        $txt = "Extraordinary";
                      }
                    ?>
                    <span class="tooltiptext"><?=$txt?></span>
                    <?php 
                    }
                    ?>
                  </div>
                    <div class="radio">
                      <div class="inline-radio">
                        <input type="radio" name="bid_accurate_comments" id="bid_accurate_comments<?=$i?>" value="<?=$i?>" <?=$checked?> >
                        <label for="bid_accurate_comments<?=$i?>"></label>
                      </div>
                    </div>
                    <p><label for="bid_accurate_comments<?=$i?>"><?=$i?></label></p>
                  </div>
                  <?php
                  }
                  ?>
                  <div class="clear"></div>
                </div>
            </div>
            <div class="heder-tit">
              <h3>Quality:</h3>
            </div>
            <div class="Private_page_secton">
              <div class="label_txt">1) Did you home pro communicate efﬁciently and keep you updated on progress?</div>
              <div class="rating-panel">
                  <?php 
                  for($i = 1; $i <= 10; $i++){
                    $checked = "";
                    if($i == $checked_val){
                      $checked = "checked";
                    }
                  ?>
                  <div class="sub-panel">
                    <div class="tooltip" style="opacity: 1; z-index: 1;">
                    <?php 
                    if($i == 1 || $i == 5 || $i == 10){
                      if($i == 1){
                        $txt = "Bad";
                      }else if($i == 5){
                        $txt = "Average";
                      }else{
                        $txt = "Extraordinary";
                      }
                    ?>
                    <span class="tooltiptext"><?=$txt?></span>
                    <?php 
                    }
                    ?>
                  </div>
                    <div class="radio">
                      <div class="inline-radio">
                        <input type="radio" name="progress_update" id="progress_update<?=$i?>" value="<?=$i?>" <?=$checked?> >
                        <label for="progress_update<?=$i?>"></label>
                      </div>
                    </div>
                    <p><label for="progress_update<?=$i?>"><?=$i?></label></p>
                  </div>
                  <?php
                  }
                  ?>
                  <div class="clear"></div>
                </div>
            </div>
            <div class="Private_page_secton">
              <div class="label_txt">2) Did you feel your pro was “in it to win it”, making sure the job was done correctly and with quality work. </div>
              <div class="rating-panel">
                  <?php 
                  for($i = 1; $i <= 10; $i++){
                    $checked = "";
                    if($i == $checked_val){
                      $checked = "checked";
                    }
                  ?>
                  <div class="sub-panel">
                    <div class="tooltip" style="opacity: 1; z-index: 1;">
                    <?php 
                    if($i == 1 || $i == 5 || $i == 10){
                      if($i == 1){
                        $txt = "Bad";
                      }else if($i == 5){
                        $txt = "Average";
                      }else{
                        $txt = "Extraordinary";
                      }
                    ?>
                    <span class="tooltiptext"><?=$txt?></span>
                    <?php 
                    }
                    ?>
                  </div>
                    <div class="radio">
                      <div class="inline-radio">
                        <input type="radio" name="job_done" id="job_done<?=$i?>" value="<?=$i?>" <?=$checked?> >
                        <label for="job_done<?=$i?>"></label>
                      </div>
                    </div>
                    <p><label for="job_done<?=$i?>"><?=$i?></label></p>
                  </div>
                  <?php
                  }
                  ?>
                  <div class="clear"></div>
                </div>
            </div>
            <div class="Private_page_secton">
              <div class="label_txt">3) Were you satisﬁed with the quality of materials used and ﬁnal presentation?</div>
              <div class="rating-panel">
                  <?php 
                  for($i = 1; $i <= 10; $i++){
                    $checked = "";
                    if($i == $checked_val){
                      $checked = "checked";
                    }
                  ?>
                  <div class="sub-panel">
                    <div class="tooltip" style="opacity: 1; z-index: 1;">
                    <?php 
                    if($i == 1 || $i == 5 || $i == 10){
                      if($i == 1){
                        $txt = "Bad";
                      }else if($i == 5){
                        $txt = "Average";
                      }else{
                        $txt = "Extraordinary";
                      }
                    ?>
                    <span class="tooltiptext"><?=$txt?></span>
                    <?php 
                    }
                    ?>
                  </div>
                    <div class="radio">
                      <div class="inline-radio">
                        <input type="radio" name="materials_quality" id="materials_quality<?=$i?>" value="<?=$i?>" <?=$checked?> >
                        <label for="materials_quality<?=$i?>"></label>
                      </div>
                    </div>
                    <p><label for="materials_quality<?=$i?>"><?=$i?></label></p>
                  </div>
                  <?php
                  }
                  ?>
                  <div class="clear"></div>
                </div>
            </div>
            <div class="submt-btn Private-btn">
              <input type="hidden" value="<?=$job_key?>" name="job_key">
              <input type="hidden" value="private" name="type">
              <input type="hidden" value="<?=$contractor_key?>" name="contractor_key">
              <input type="Submit" class="btn btn-md btn-primary" value="Submit Now">
            </div>
          </div>
      </div>
    </div>
	</div>
</section>