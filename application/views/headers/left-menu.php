<div class="wrapper"><!---->
	<section class="left-side pattern-tools divheight hidden-xs">
    	<div class="left-profile-details">
        	<div class="profile-img left-fix-img radius50"><img src="<?php echo $this->session->userdata('userImg'); ?>" /></div>
        	<h2><?php echo $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname')[0]; ?></h2>
        	<?php /* <p class="icons-lpd email"><?php echo $this->session->userdata('userEmail'); ?></p>
            <p class="icons-lpd address"><?php echo ( $this->session->userdata('userAddress') != '' ) ? $this->session->userdata('userAddress') : '--'; ?></p>
            <p class="icons-lpd suit">Suite 500</p> */ ?>
            <p class="icons-lpd zip"><?php echo $this->session->userdata('userAddrDets'); ?></p>
            <div class="clear line-1px-ash mt20"></div>
            <a href="<?php echo base_url('myprofile'); ?>" class="btn-left btn-left-profile">My Profile</a>
            <?php /* ?><a href="#" class="btn-left btn-left-settings">Settings</a><?php */ ?>
            <a href="<?php echo base_url('logout') ?>" class="btn-left btn-left-logout">Log Out</a>
        </div>
    	<div class="ftr"><p>&copy; 2016 Efynch, All Right Reserved</p></div>
    </section>
