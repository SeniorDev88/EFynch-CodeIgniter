<div class="col-sm-12 col-md-12 col-lg-12 pl0 pr0 clearfix dashmenu">
	<?php if($this->session->userdata('usertype') == 'homeowner'): ?>
	<a href="<?php echo base_url('postjob'); ?>" class="icon-dash-small post-a-job <?php if($subhead == 'createjob'): ?>active<?php endif; ?>"><i></i><span>Post a Job</span></a>
	<a href="<?php echo base_url('owner/bidding'); ?>" class="icon-dash-small my-job-post <?php if($subhead == 'jobpost'): ?>active<?php endif; ?>"><i></i><span>My Job Posts</span><p class="badge-job-inside"><?php echo $allcounts['myjobscount']; ?></p></a>
	<a href="<?php echo base_url('notification'); ?>" class="icon-dash-small my-notification <?php if($subhead == 'notification'): ?>active<?php endif; ?>"><i></i><span>Notification</span></a>
	<a href="<?php echo base_url('messages'); ?>" class="icon-dash-small my-messages <?php if($subhead == 'message'): ?>active<?php endif; ?>"><i></i><span>Messages</span></a>
	<a href="<?php echo base_url('accountbalance'); ?>" class="icon-dash-small my-payments <?php if($subhead == 'payment'): ?>active<?php endif; ?>"><i></i><span>My Payments</span></a>
<?php else: ?>
	<a href="<?php echo base_url('contractor/working'); ?>" class="icon-dash-small my-job-post <?php if($subhead == 'jobpost'): ?>active<?php endif; ?>"><i></i><span>My Jobs</span><p class="badge-job-inside"><?php echo $allcounts['myjobscount']; ?></p></a>
    <a href="<?php echo base_url('dashboard'); ?>" class="icon-dash-small <?php if($subhead == 'dashboard'): ?>active<?php endif; ?>"><i></i><span>Job Board</span></a>
    <a href="<?php echo base_url('mybids'); ?>" class="icon-dash-small my-bids <?php if($subhead == 'mybid'): ?>active<?php endif; ?>"><i></i><span>My Bids</span><p class="badge-job-inside"><?php echo $allcounts['mybidscount']; ?></p></a>
    <a href="<?php echo base_url('notification'); ?>" class="icon-dash-small my-notification <?php if($subhead == 'notification'): ?>active<?php endif; ?>"><i></i><span>Notification</span></a>
    <a href="<?php echo base_url('messages'); ?>" class="icon-dash-small my-messages <?php if($subhead == 'message'): ?>active<?php endif; ?>"><i></i><span>Messages</span></a>
    <a href="<?php echo base_url('accountbalance'); ?>" class="icon-dash-small my-payments <?php if($subhead == 'payment'): ?>active<?php endif; ?>"><i></i><span>Account Balance</span></a>
<?php  endif; ?>
</div>
