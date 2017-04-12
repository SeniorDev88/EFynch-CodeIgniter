
    <section class="right-side divheight">
    	<div class="container-right pr0 pl0">
                        <?php
      $this->load->view('headers/dashboard_menu');
?>
        	<div class="col-sm-12 pl0 pr0">

                <div class="col-xs-12 pl30 pr30">
                	<div class="col-sm-12 p0 mb10"><h1>Messages</h1></div>  <div class="clear"></div>

                    <?php if(!empty($jobs)){
                            foreach($jobs as $jb){


                        ?>
                        <a href="<?php echo base_url('messages/'.$jb['jobkey']); ?>" class="row-middle-wrap row-message-inbox clearfix active">
                        <div class="profile-img-md radius50"><img src="<?php echo $jb['imageurl']; ?>"></div>
                        <h2><?php echo $jb['jobname']; ?></h2>
                        <h6><?php echo $jb['msgcount']; ?></h6>
                    </a>

                    <?php
                        }
                    }else{
                        ?>
                        <p class="alert alert-danger mt10">No Messages Found.</p>
                    <?php
                    } ?>




                </div>
            </div>


        <div class="clear"></div>
        </div>

    </section>

<!--Wrapper Starts from left menu-->
</div>
