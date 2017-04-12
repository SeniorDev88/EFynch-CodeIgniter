<!-- Content area -->

<div class="content">

    <!-- CKEditor default -->

    <div class="panel panel-flat">

        <div class="panel-heading">

            <div>

                <h5 class="panel-title">Jobs List</h5>

            </div>

            <div style="padding-top: 15px;">

                <a href="<?php echo base_url('admin/jobs/addJob')?>">

                    <button class="btn btn-success" type="button">Add New Job</button>

                </a>

            </div>

        </div>



        <div class="panel-body">

            <form action="<?php echo base_url('admin/jobs')?>" method="get" >

                <div class="datatable-header">

                    <div id="DataTables_Table_4_filter" class="dataTables_filter">

                        <label><span>Filter:</span>

                            <input style="width: 380px;" class="" value="<?php echo @$q;?>" name="q" placeholder="Job name, Created By, City, State, Address, Job Status" aria-controls="DataTables_Table_4" type="search">

                        </label>

                        <button class="btn btn-default" type="submit">Search</button>

                    </div>

                </div>

            </form>

            <table class="table table-bordered table-hover datatable-highlight">

                <thead>

                <tr>

                    <th>Title</th>

                    <th>Created By</th>

                    <th>Total Bids</th>

                    <th>Expertise</th>

                    <th>State</th>

                    <th>City</th>

                    <th>Zip</th>

                    <th>Budget</th>

                    <th>Job Status</th>

                    <th>Creation Date</th>

                    <th class="text-center">Actions</th>

                </tr>

                </thead>

                <tbody>

                <?php foreach ($content as $val) {

				?>

                    <tr>

                        <td><?php echo ucfirst($val['jobname'])?></td>

                        <td><a href="<?php echo base_url('admin/users?q='.$val['firstname'].' '.$val['lastname']);?>"><?php echo ucfirst($val['firstname'].' '.$val['lastname'])?></a></td>

                        <td><a href="<?php echo base_url('admin/bids?q='.$val['jobname']);?>"><?php echo ucfirst($val['bidsCount'])?></a></td>

                        <td><?php echo ucfirst($val['name'])?></td>

                        <!--<td><?php /*echo ucfirst($val['address'])*/?></td>-->

                        <td><?php echo ucfirst($val['state'])?></td>

                        <td><?php echo ucfirst($val['city'])?></td>

                        <td><?php echo ucfirst($val['zip'])?></td>

                        <td><?php echo ucfirst($val['budget'])?></td>

                        <td><?php echo ucfirst($val['jobstatus'])?></td>

                        <td><?php echo ucfirst(date('M d, Y H:i',($val['createdate'])))?></td>

                        <td class="text-center">

                            <ul class="icons-list">

                                <li class="dropdown">

                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                                        <i class="icon-menu9"></i>

                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-right">

                                        <li><a href="<?php echo(base_url('admin/jobs/jobDetail/'.$val['jobid'])); ?>"><i class="icon-file-pdf"></i> Detail</a></li>

                                        <li><a href="<?php echo(base_url('admin/jobs/editJob/'.$val['jobid'])); ?>"><i class="icon-file-pdf"></i> Edit</a></li>

                                        <li><a data-toggle="modal" data-target="#myModal_Del_<?php echo $val['jobid']?>" href=""><i class="icon-file-pdf"></i>Delete</a></li>

                                        <?php
											if($val['jobstatus'] == 'completed')
											{
										?>
                                        		<li><a data-toggle="modal" _id="<?php echo $val['jobid']?>" data-target="#myModal_<?php echo $val['jobid']?>" href="">
                                                <i class="icon-file-pdf"></i> Release Escrow</a></li>
                                                
                                                
                                        <?php
                                        	}

											else if($val['bt_transaction_id'] != "" && $val['escrow_released'] != 1)
											{
										?>
                                        		<li><a href="<?php echo(base_url('admin/jobs/completeJob/'.$val['jobid'])); ?>"><i class="icon-file-pdf"></i> Mark As Complete</a></li>
                                        <?php		

											}

											else if($val['jobstatus'] == "inprogress" && $val['bt_transaction_id'] == "")

											{

										?>

                                        		<li><a data-toggle="modal" _id="<?php echo $val['jobid']?>" data-target="#No_payment_Modal_<?php echo $val['jobid']?>" href="#"><i class="icon-file-pdf"></i> Mark As Complete</a></li>

                                        <?php

											}

										?>

                                    </ul>

                                </li>

                            </ul>
							<div id="myModal_<?php echo $val['jobid']?>" class="modal fade mymodelstyle" role="dialog">
                                                
                                                 <div class="modal-dialog">
                                            
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <img src="<?php echo base_url()?>assets/images/logo_ef.png" alt="" />
                                                      </div>
                                                      <div class="modal-body">
                                                        <p>Are you sure. It will escrow payment?</p>
                                                        <div class="btn-payment">
                                                            <a type="button" href="<?php echo(base_url('admin/jobs/releasePayment/'.$val['jobid'])) ?>" class="btn btn-md btn-ok">ok</a>
                                                            <a type="button" href="#" class="btn btn-md btn-cancel" class="close" data-dismiss="modal">cancel</a>
                                                            
                                                        </div>
                                                      </div>
                                                  </div>
                                                </div>
                                                </div>

                            <!-- delete modal-->
                            <div id="myModal_Del_<?php echo $val['jobid']?>" class="modal fade mymodelstyle" role="dialog">

                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <img src="<?php echo base_url()?>assets/images/logo_ef.png" alt="" />
                                        </div>
                                        <div class="modal-body">
                                            <p>Would you like to delete this job?</p>
                                            <div class="btn-payment">
                                                <a type="button" href="<?php echo(base_url('admin/jobs/deleteJob/'.$val['jobid'])); ?>" class="btn btn-md btn-ok">ok</a>
                                                <a type="button" href="#" class="btn btn-md btn-cancel" class="close" data-dismiss="modal">cancel</a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- delete modal /-->
                            
                            
                            <div id="No_payment_Modal_<?php echo $val['jobid']?>" class="modal fade mymodelstyle" role="dialog">
                                                
                             <div class="modal-dialog">
                        
                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <img src="<?php echo base_url()?>assets/images/logo_ef.png" alt="" />
                                  </div>
                                  <div class="modal-body">
                                    <p>The payment has not been made yet.</p>
                                    <div class="btn-payment">
                                        <a type="button" href="#" class="btn btn-md btn-cancel" data-dismiss="modal">Ok</a>
                                        
                                    </div>
                                  </div>
                              </div>
                            </div>
                            </div>
                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

            <div class="datatable-footer">

                <div class="dataTables_info" id="DataTables_Table_4_info" role="status" aria-live="polite"><?php echo $dataInfo;?></div>

                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_4_paginate">

                    <?php echo $links;?>

                    <!--<a class="paginate_button previous disabled" aria-controls="DataTables_Table_4" data-dt-idx="0" tabindex="0" id="DataTables_Table_4_previous">←</a>

                    <span><a class="paginate_button current" aria-controls="DataTables_Table_4" data-dt-idx="1" tabindex="0">1</a><a class="paginate_button " aria-controls="DataTables_Table_4" data-dt-idx="2" tabindex="0">2</a></span>

                    <a class="paginate_button next" aria-controls="DataTables_Table_4" data-dt-idx="3" tabindex="0" id="DataTables_Table_4_next">→</a>-->

                </div>

            </div>

        </div>

    </div>
    <!-- Modal -->
    