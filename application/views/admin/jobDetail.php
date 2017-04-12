<div class="content">
    <!-- CKEditor default -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title pull-left">Job Detail</h5>
            <div class="pull-right">
                <a href="<?php echo base_url('admin/jobs/addJob')?>">
                    <button type="button" class="btn bg-teal-400">Add New Job<i class="icon-arrow-right14 position-right"></i></button>
                </a>
                <a href="<?php echo base_url('admin/jobs/editJob/'.$data['jobid'])?>">
                    <button type="button" class="btn bg-teal-400">Edit Job<i class="icon-arrow-right14 position-right"></i></button>
                </a>
                 <?php 
											
											if($data['jobstatus'] == 'completed')
											{
										?>
                                        		<a onclick="return confirm('Are you sure. It will escrow payment?');" href="<?php echo(base_url('admin/jobs/releasePayment/'.$data['jobid'])); ?>">
                                                 <button type="button" class="btn bg-teal-400">Release Escrow<i class="icon-arrow-right14 position-right"></i></button>
                                                 
                                                 
                                                 </a>
                                        <?php
                                        	}
											else if(@$data['bt_transaction_id'] != "")
											{
										?>
                                        		<a href="<?php echo(base_url('admin/jobs/completeJob/'.$data['jobid'])); ?>">
                                                <button type="button" class="btn bg-teal-400">Mark as completed<i class="icon-arrow-right14 position-right"></i></button></a>
                                        <?php		
											}
											else if($data['jobstatus'] == "inprogress" && @$data['bt_transaction_id'] == "")
											{
										?>
                                        		<a onclick="alert('Job owner has not paid yet.');return false;" href="#">
                                                <button type="button" class="btn bg-teal-400">Mark As Complete<i class="icon-arrow-right14 position-right"></i></button>
                                                 </a>
                                        <?php
											}
										?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <table class="table table-lg">
                <tbody>
                <tr>
                    <th colspan="3" class="active">Basic Detail</th>
                </tr>
                <tr>
                    <td>Job Name:</td>
                    <td><?php echo $data['jobname'];?></td>
                </tr>
                <tr>
                    <td>Created By:</td>
                    <td><?php echo $data['firstname'].' '.$data['lastname'];?></td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td><?php echo $data['jobdescription'];?></td>
                </tr>
                <tr>
                    <td>Expertise:</td>
                    <td><?php echo $data['name'];?></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><?php echo $data['address'];?></td>
                </tr>
                <tr>
                    <td>State:</td>
                    <td><?php echo $data['state'];?></td>
                </tr>
                <tr>
                    <td>City:</td>
                    <td><?php echo $data['city'];?></td>
                </tr>
                <tr>
                    <td>Budget:</td>
                    <td><?php echo $data['budget'];?></td>
                </tr>
                <tr>
                    <td>Days Posted:</td>
                    <td><?php echo $data['daysposted'];?></td>
                </tr>
                <tr>
                    <td>Latitude:</td>
                    <td><?php echo $data['latitude'];?></td>
                </tr>
                <tr>
                    <td>Longitude:</td>
                    <td><?php echo $data['longitude'];?></td>
                </tr>
                <tr>
                    <td>Zip:</td>
                    <td><?php echo $data['zip'];?></td>
                </tr>
                <tr>
                    <td>Timeframe:</td>
                    <td><?php echo $data['timeframe'];?></td>
                </tr>
                <tr>
                    <td>Indoor:</td>
                    <td><?php echo $data['indoor'];?></td>
                </tr>
                <tr>
                    <td>Home Type:</td>
                    <td><?php echo $data['hometype'];?></td>
                </tr>
                <tr>
                    <td>Starting State:</td>
                    <td><?php echo $data['starting_state'];?></td>
                </tr>
                <tr>
                    <td>Total Stories:</td>
                    <td><?php echo $data['total_stories'];?></td>
                </tr>
                <tr>
                    <td>Material Option:</td>
                    <td><?php echo $data['material_option'];?></td>
                </tr>
                <tr>
                    <td>Rate Type:</td>
                    <td><?php echo $data['rate_type'];?></td>
                </tr>
                <tr>
                    <td>Year Constructed:</td>
                    <td><?php echo $data['year_constructed'];?></td>
                </tr>
                <tr>
                    <td>Current Condition:</td>
                    <td><?php echo $data['current_condition'];?></td>
                </tr>
                <tr>
                    <td>First Problem Notice:</td>
                    <td><?php echo $data['first_problem_notice'];?></td>
                </tr>
                <tr>
                    <td>Resolution:</td>
                    <td><?php echo $data['resolution'];?></td>
                </tr>
                <tr>
                    <td>Measurements:</td>
                    <td><?php echo $data['measurements'];?></td>
                </tr>
                <tr>
                    <td>Material Preferences:</td>
                    <td><?php echo $data['material_preferences'];?></td>
                </tr>
                <tr>
                    <td>Purchased Materials:</td>
                    <td><?php echo $data['purchased_materials'];?></td>
                </tr>
                <tr>
                    <td>Access To Area:</td>
                    <td><?php echo $data['access_to_area'];?></td>
                </tr>
                <tr>
                    <td>Your Availability:</td>
                    <td><?php echo $data['your_availability'];?></td>
                </tr>
                <tr>
                    <td>Relevant Info:</td>
                    <td><?php echo $data['relevant_info'];?></td>
                </tr>
                <tr>
                    <td>Image:</td>
                    <td>
                        <?php foreach ($data['images'] as $v) {?>
                        <img style="height: auto;width: 15%;" src="
                        <?php
                        if(!@getimagesize(JOB_IMG.$v['dockey'].'.'.$v['docext']))
                            echo DEFAULT_USER;
                        else
                            echo JOB_IMG.$v['dockey'].'.'.$v['docext'];
                        ?>" />
                        <?php }?>
                    </td>
                </tr>
                <tr>
                    <td>Job Status:</td>
                    <td><?php echo $data['jobstatus'];?></td>
                </tr>
                <tr>
                    <td>Start date:</td>
                    <td><?php echo date('M d,Y',strtotime($data['startdate']));?></td>
                </tr>
                <tr>
                    <td>Completed Date:</td>
                    <td><?php if($data['completeddate'] == '0000-00-00') echo $data['completeddate'];else echo date('M d,Y',strtotime($data['completeddate']));?></td>
                </tr>
                <tr>
                    <td>Completion Date:</td>
                    <td><?php echo date('M d,Y',strtotime($data['completiondate']));?></td>
                </tr>

                <tr>
                    <td>Created At:</td>
                    <td><?php echo date('M d,Y H:i',$data['createdate']);?></td>
                </tr>
                <tr>
                    <td>Updated On:</td>
                    <td><?php echo date('M d,Y H:i',$data['updatedate']);?></td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>