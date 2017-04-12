<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
<link rel="stylesheet" href="<?php echo base_url(); ?>css/rating.css">
<style type="text/css">
  .rating-star ul li a:hover{
    color: #f79227;
  }
  .rating-star ul li a:focus{
    color: #f79227;
  }
  .img-holder {
    cursor: default;
  }
  .radio input[type="radio"]:disabled + label{
    opacity: 1;
  }
  .radio label, .checkbox label{
    cursor: default;
  }
</style>
<section class="right-side divheight" style="height: 403px;">
  <div class="container-right pr0 pl0">
    <?php 
    if($type == "public"){
    ?>
		<div>
			<div class="page_header">
				<h1>Public Rating</h1>
			</div>
      <div class="col-md-12 col-sm-12">
          <div class="page-title">
              <h2><?=$job_data['jobname']?></h2>
          </div>
          <div class="rating col-md-12 col-sm-12">
          	<div class="rating-star">
          		<ul>
                <?php 
                for($i = 1; $i <= 5; $i++){
                  $class = "fa-star-o";
                  if ($i <= $rating_data['rating'] ){
                    $class = "fa-star";
                  }
                ?>
                <li><a href="javascript:{}"><i style="cursor: default;" class="fa <?=$class?>" ></i></a></li>
                <?php
                }
                ?>
          	</div>
              <div class="col-md-12 col-sm-12">
                  <div class="heading-2">
                      <h3>Compliment Given</h3>
                  </div>
              </div>
              <div class="rating-icon col-md-12 col-sm-12">
                <div class="row padd-bott">
                    <div class="col-md-4">
                        <figure>
                            <a class="img-holder" id="carftsman" href="javascript:{}">
                              <img src="<?php echo base_url()?>images/icon_carftsman@2x.png" alt="icon_carftsman">
                              <div class="focus-img" style="<?=!empty($rating_data['carftsman']) ? 'display:block' : 'display:none;'; ?>">
                                <i class="fa fa-check" aria-hidden="true" id=></i>
                              </div>
                            </a>
                            <figcaption>Craftsmen</figcaption>
                        </figure>
                    </div>
                    <div class="col-md-4">
                        <figure>
                            <a class="img-holder" id="workspace" href="javascript:{}">
                              <img src="<?php echo base_url()?>images/icon_clean_workspace@2x.png" alt="icon_clean_workspace">
                              <div class="focus-img" style="<?=!empty($rating_data['clean_workspace']) ? 'display:block' : 'display:none;'; ?>">
                                <i class="fa fa-check" aria-hidden="true" id=></i>
                              </div>
                            </a>
                            <figcaption>Clean workspace</figcaption>
                        </figure> 
                    </div>
                    <div class="col-md-4">
                        <figure>
                            <a class="img-holder" id="excellent" href="javascript:{}">
                              <img src="<?php echo base_url()?>images/icon_excellent_value@2x.png" alt="icon_excellent_value">
                              <div class="focus-img" style="<?=!empty($rating_data['excellent_value']) ? 'display:block' : 'display:none;'; ?>">
                                <i class="fa fa-check" aria-hidden="true" id=></i>
                              </div>
                            </a>
                            <figcaption>Excellent Value</figcaption>
                        </figure>
                    </div>
                </div>
                <div class="row padd-bott">
                    <div class="col-md-4">
                        <figure>
                            <a class="img-holder" id="communicator" href="javascript:{}">
                              <img src="<?php echo base_url()?>images/icon_good_communicator@2x.png" alt="icon_good communacator">
                              <div class="focus-img" style="<?=!empty($rating_data['good_communicator']) ? 'display:block' : 'display:none;'; ?>">
                                <i class="fa fa-check" aria-hidden="true" id=></i>
                              </div>
                            </a>
                            <figcaption>Good Communicator</figcaption>
                        </figure>
                    </div>
                    <div class="col-md-4">
                        <figure>
                            <a class="img-holder" id="problem_solver" href="javascript:{}">
                              <img src="<?php echo base_url()?>images/icon_problem%20solver@2x.png" alt="problem solver">
                              <div class="focus-img" style="<?=!empty($rating_data['problem_solver']) ? 'display:block' : 'display:none;'; ?>">
                                <i class="fa fa-check" aria-hidden="true" id=></i>
                              </div>
                            </a>
                            <figcaption>Problem Solver</figcaption>
                        </figure>

                    </div>
                    <div class="col-md-4">
                        <figure>
                            <a class="img-holder" id="efficiently" href="javascript:{}">
                              <img src="<?php echo base_url()?>images/icon_worked%20ef%EF%AC%81ciently@2x.png" alt="work efficent">
                              <div class="focus-img" style="<?=!empty($rating_data['worked_efficiently']) ? 'display:block' : 'display:none;'; ?>">
                                <i class="fa fa-check" aria-hidden="true" id=></i>
                              </div>
                            </a>
                            <figcaption>Worked Efficiently</figcaption>
                        </figure>
                    </div>
                </div>
                <div class="row padd-bott">
                    <div class="col-md-4 col-sm-4"></div>
                    <div class="col-md-4">
                        <figure>
                            <a class="img-holder" id="prompt" href="javascript:{}">
                              <img src="<?php echo base_url()?>images/icon_prompt@2x.png" alt="icon_prompt">
                              <div class="focus-img" style="<?=!empty($rating_data['prompt']) ? 'display:block' : 'display:none;'; ?>">
                                <i class="fa fa-check" aria-hidden="true" id=></i>
                              </div>
                            </a>
                            <figcaption>Prompt</figcaption>
                        </figure>
                    </div>
                    <div class="col-md-4 col-sm-4"></div>
                </div>
                <div class="col-md-12 col-sm-12">
                  <div class="heading-2">
                      <h3>Comments Given</h3>
                  </div>
                </div>
                <div class="row">
                    <div class="textarea">
                      <textarea style="height:229px;" disabled class="input100-login radius30p textarea_style"><?=$rating_data['comment']?></textarea>
                    </div>
                </div>
              </div>
          </div>
      </div>
		</div>
    <?php 
    }else if(!empty($job_data)){
    ?>
    <div>
      <div class="page_header">
        <h1>Private Rating</h1>
      </div>
      
      <div class="col-md-12 col-sm-12">
        <div class="page-title">
            <h2><?=$job_data['jobname']?></h2>
        </div>
        <div class="col-md-12 col-sm-12 Private_page">
          <div class="Private_page_secton">
              <div class="label_txt">Name On a scale of 1 - 10</div>
              <div class="block-div">
                <div class=" inline-radio">
                  <p>1 - 2</p>
                  <div class="radio">
                      <input type="radio" name="scale" id="radio1" value="bad" disabled="" >
                      <label for="radio1">
                          Bad 
                      </label>
                  </div>
                </div>  
                </div>    
                <div class="block-div">  
                  <div class=" inline-radio">
                    <p>3 - 4</p>
                    <div class="radio">
                        <input type="radio" name="scale" id="radio2" value="below_average" disabled="">
                        <label for="radio2">
                            Slightly below average
                        </label>
                    </div>
                  </div>  
                </div>    
                <div class="block-div">  
                  <div class=" inline-radio">
                    <p>5 - 6</p>
                    <div class="radio inline-radio">
                      <input type="radio" name="scale" id="radio3" value="average" checked disabled="" >
                      <label for="radio3">
                          Average 
                      </label>
                    </div>
                </div> 
              </div>    
              <div class="block-div">
                  <div class=" inline-radio">
                    <p>7 - 9</p>   
                    <div class="radio ">
                      <input type="radio" name="scale" id="radio4" value="above_average" disabled="">
                      <label for="radio4">
                          Slightly above average 
                      </label>
                    </div>
                  </div>
                </div>    
                  <div class="block-div">
                    <div class=" inline-radio">
                        <p class="last-child">10</p>     
                         <div class="radio">
                            <input type="radio" name="scale" id="radio5" value="extraordinary" disabled="" >
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
                <strong>
                <?php
                if( !empty($rating_data['time_quality']) ){
                  echo $rating_data['time_quality'];
                }else{
                  echo "-";
                }
                ?>
                </strong>
              </div>
          </div>
          <div class="Private_page_secton">
            <div class="label_txt">2) Given the quality of work you received, was the Home Pro efﬁcient with the time spent working on the project?</div>
            <div class="rating-panel">
              <strong>
                <?php
                if( !empty($rating_data['work_quality']) ){
                  echo $rating_data['work_quality'];
                }else{
                  echo "-";
                }
                ?>
                </strong>
              </div>
          </div>
          <div class="Private_page_secton">
            <div class="label_txt">3) Was the Home Pro ﬂexible and able to schedule work according to your schedule?</div>
            <div class="rating-panel">
                <strong>
                  <?php
                  if( !empty($rating_data['schedule']) ){
                    echo $rating_data['schedule'];
                  }else{
                    echo "-";
                  }
                  ?>
                </strong>
              </div>
          </div>
          <div class="Private_page_secton">
            <div class="label_txt">4) Overall: How would you rate the home pro on their consideration of your time?</div>
            <div class="rating-panel">
              <strong>
                  <?php
                  if( !empty($rating_data['overall_comments']) ){
                    echo $rating_data['overall_comments'];
                  }else{
                    echo "-";
                  }
                  ?>
                    </strong>
              </div>
          </div>
          <div class="heder-tit">
              <h3>Value:</h3>
          </div>
          <div class="Private_page_secton">
            <div class="label_txt">1) How satisﬁed were you with the ultimate price of the work which was performed? </div>
              <div class="rating-panel">
                <strong>
                  <?php
                  if( !empty($rating_data['price_comments']) ){
                    echo $rating_data['price_comments'];
                  }else{
                    echo "-";
                  }
                  ?>
                    </strong>
                </div>
            </div>
          <div class="Private_page_secton">
            <div class="label_txt">2) Was the initial bid accurate and did not change without true justiﬁcation? </div>
            <div class="rating-panel">
              <strong>
                  <?php
                  if( !empty($rating_data['bid_accurate_comments']) ){
                    echo $rating_data['bid_accurate_comments'];
                  }else{
                    echo "-";
                  }
                  ?>
                    </strong>
              </div>
          </div>
          <div class="heder-tit">
            <h3>Quality:</h3>
          </div>
          <div class="Private_page_secton">
            <div class="label_txt">1) Did you home pro communicate efﬁciently and keep you updated on progress?</div>
            <div class="rating-panel">
                <strong>
                  <?php
                  if( !empty($rating_data['progress_update']) ){
                    echo $rating_data['progress_update'];
                  }else{
                    echo "-";
                  }
                  ?>
                </strong>
              </div>
          </div>
          <div class="Private_page_secton">
            <div class="label_txt">2) Did you feel your pro was “in it to win it”, making sure the job was done correctly and with quality work. </div>
            <div class="rating-panel">
              <strong>
                  <?php
                  if( !empty($rating_data['job_done']) ){
                    echo $rating_data['job_done'];
                  }else{
                    echo "-";
                  }
                  ?>
              </strong>
            </div>
          </div>
          <div class="Private_page_secton">
            <div class="label_txt">3) Were you satisﬁed with the quality of materials used and ﬁnal presentation?</div>
            <div class="rating-panel">
                <strong>
                  <?php
                  if( !empty($rating_data['materials_quality']) ){
                    echo $rating_data['materials_quality'];
                  }else{
                    echo "-";
                  }
                  ?>
                </strong>
              </div>
          </div>
        </div>
      </div>
    </div>
    <?php 
    }else{
    ?>
    <div>
      <div class="page_header">
        <h1>Private Rating</h1>
      </div>
      <div class="col-md-12 col-sm-12">Private Rating is not given.</div>
    </div>
    <?php 
    }
    ?>
	</div>
</section>