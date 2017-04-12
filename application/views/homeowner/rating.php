<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
<link rel="stylesheet" href="<?php echo base_url(); ?>css/rating.css">
<section class="right-side divheight" style="height: 403px;">
  <div class="container-right pr0 pl0">
		<div>
			<div class="page_header">
				<h1>Public Rating</h1>
			</div>
      <div class="col-md-12 col-sm-12">
          <?php 
          if($this->session->flashdata('error_msg')){
          ?>
          <div class="alert alert-danger" role="alert" style="margin: 10px;">
            <span class="sr-only">Error:</span>
            <?php echo $this->session->flashdata('error_msg'); ?>
          </div>
          <?php 
          }
          ?>
          <div class="page-title">
              <h2><?=$job_data['jobname']?></h2>
          </div>
          <form method="post" action="<?php echo  base_url(); ?>save-rating" onsubmit="return validate();">
            <div class="rating col-md-12 col-sm-12">
            	<div class="rating-star">
            		<ul>
            			<li><a href="javascript:{}"><i class="fa fa-star" onclick="rateMe(this)"></i></a></li>
            			<li><a href="javascript:{}"><i class="fa fa-star" onclick="rateMe(this)"></i></a></li>
            			<li><a href="javascript:{}"><i class="fa fa-star-o" onclick="rateMe(this)"></i></a></li>
            			<li><a href="javascript:{}"><i class="fa fa-star-o" onclick="rateMe(this)"></i></a></li>
            			<li><a href="javascript:{}"><i class="fa fa-star-o" onclick="rateMe(this)"></i></a></li>
            		</ul>	
            	</div>
                <div class="col-md-12 col-sm-12">
                    <div class="heading-2">
                        <h3>Give a Compliment?</h3>
                    </div>
                </div>
                <div class="rating-icon col-md-12 col-sm-12">
                  <div class="row padd-bott">
                      <div class="col-md-4">
                          <figure>
                              <a class="img-holder" id="carftsman" href="javascript:{}" onclick="toggleMe('carftsman')">
                                <img src="<?php echo base_url()?>images/icon_carftsman@2x.png" alt="icon_carftsman">
                                <div class="focus-img" style="display: none;">
                                  <i class="fa fa-check" aria-hidden="true" id=></i>
                                </div>
                              </a>
                              <input type="hidden" value="" name="carftsman"  id="txt_carftsman">
                              <figcaption>Craftsmen</figcaption>
                          </figure>
                      </div>
                      <div class="col-md-4">
                          <figure>
                              <a class="img-holder" id="workspace" href="javascript:{}" onclick="toggleMe('workspace')">
                                <img src="<?php echo base_url()?>images/icon_clean_workspace@2x.png" alt="icon_clean_workspace">
                                <div class="focus-img" style="display: none;">
                                  <i class="fa fa-check" aria-hidden="true" id=></i>
                                </div>
                              </a>
                              <input type="hidden" value="" name="workspace" id="txt_workspace">
                              <figcaption>Clean workspace</figcaption>
                          </figure> 
                      </div>
                      <div class="col-md-4">
                          <figure>
                              <a class="img-holder" id="excellent" href="javascript:{}" onclick="toggleMe('excellent')">
                                <img src="<?php echo base_url()?>images/icon_excellent_value@2x.png" alt="icon_excellent_value">
                                <div class="focus-img" style="display: none;">
                                  <i class="fa fa-check" aria-hidden="true" id=></i>
                                </div>
                              </a>
                              <input type="hidden" value="" name="excellent" id="txt_excellent">
                              <figcaption>Excellent Value</figcaption>
                          </figure>
                      </div>
                  </div>
                  <div class="row padd-bott">
                      <div class="col-md-4">
                          <figure>
                              <a class="img-holder" id="communicator" href="javascript:{}" onclick="toggleMe('communicator')">
                                <img src="<?php echo base_url()?>images/icon_good_communicator@2x.png" alt="icon_good communacator">
                                <div class="focus-img" style="display: none;">
                                  <i class="fa fa-check" aria-hidden="true" id=></i>
                                </div>
                              </a>
                              <input type="hidden" value="" name="communicator" id="txt_communicator">
                              <figcaption>Good Communicator</figcaption>
                          </figure>
                      </div>
                      <div class="col-md-4">
                          <figure>
                              <a class="img-holder" id="problem_solver" href="javascript:{}" onclick="toggleMe('problem_solver')">
                                <img src="<?php echo base_url()?>images/icon_problem%20solver@2x.png" alt="problem solver">
                                <div class="focus-img" style="display: none;">
                                  <i class="fa fa-check" aria-hidden="true" id=></i>
                                </div>
                              </a>
                              <input type="hidden" value="" name="problem_solver" id="txt_problem_solver">
                              <figcaption>Problem Solver</figcaption>
                          </figure>

                      </div>
                      <div class="col-md-4">
                          <figure>
                              <a class="img-holder" id="efficiently" href="javascript:{}" onclick="toggleMe('efficiently')">
                                <img src="<?php echo base_url()?>images/icon_worked%20ef%EF%AC%81ciently@2x.png" alt="work efficent">
                                <div class="focus-img" style="display: none;">
                                  <i class="fa fa-check" aria-hidden="true" id=></i>
                                </div>
                              </a>
                              <input type="hidden" value="" name="efficiently" id="txt_efficiently">
                              <figcaption>Worked Efficiently</figcaption>
                          </figure>
                      </div>
                  </div>
                  <div class="row padd-bott">
                      <div class="col-md-4 col-sm-4"></div>
                      <div class="col-md-4 col-sm-4">
                          <figure>
                              <a class="img-holder" id="prompt" href="javascript:{}" onclick="toggleMe('prompt')">
                                <img src="<?php echo base_url()?>images/icon_prompt@2x.png" alt="icon_prompt">
                                <div class="focus-img" style="display: none;">
                                  <i class="fa fa-check" aria-hidden="true" id=></i>
                                </div>
                              </a>
                              <input type="hidden" value="" name="prompt" id="txt_prompt">
                              <figcaption>Prompt</figcaption>
                          </figure>
                      </div>
                      <div class="col-md-4 col-sm-4"></div>
                  </div>
                  <div class="row">
                      <div class="textarea">
                         <textarea id="rating_comment" style="height:229px; " class="input100-login radius30p textarea_style" name="rating_comment" placeholder="type here...."></textarea>
                        <div style="color: red;display: none;" id="err_msg">Comment is required.</div>
                        <div class="submt-btn">
                          <input type="hidden" id="rating" value="" name="rating">
                          <input type="hidden" value="public" name="type">
                          <input type="hidden" value="<?=$job_key?>" name="job_key">
                          <input type="hidden" value="<?=$contractor_key?>" name="contractor_key">
                          <input type="Submit" class="btn btn-md btn-primary" value="Submit Now">
                        </div>
                      </div>
                  </div>
                </div>
            </div>
          </form>
      </div>
		</div>
	</div>
</section>
<script>

  $(document).ready(function(){
    $("input#rating").val($("i.fa-star").length)
  })

  function validate(){
    $("div#err_msg").hide();
    if ( $.trim($("#rating_comment").val()) == "" ){
      $("div#err_msg").html("Comment is required.");
      $("div#err_msg").show();
      return false;
    }
    if ($("#rating_comment").val().length > 150){
      $("div#err_msg").html("Comment must be less than or equal to 150 characters.");
      $("div#err_msg").show();
      return false;
    }
    return true;
  }

  function toggleMe(id){
    if ( $("a#"+id+" div.focus-img:visible").length == 0 ){
      $("a#"+id+" div.focus-img").show();
      selected = 1;
    }else{
      $("a#"+id+" div.focus-img").hide();
      selected = 0;
    }
    $( "input#txt_"+id ).val(selected)
  }

  function rateMe(elem){
    parents = $(elem).parent().parent();

    if ( $(elem).hasClass('fa-star') ){

      $(elem).removeClass('fa-star').addClass('fa-star-o')
      parents.nextAll("li").find("i").removeClass("fa-star").addClass('fa-star-o')
    }else{
      $(elem).removeClass('fa-star-o').addClass('fa-star')
      parents.prevAll("li").find("i").removeClass("fa-star-o").addClass('fa-star')

    }
    $(elem).parent().blur(); 
    $("input#rating").val($("i.fa-star").length)
  }
</script>