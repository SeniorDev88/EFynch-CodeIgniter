<!-- Content area -->
<div class="content">    <!-- CKEditor default -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <div><h5 class="panel-title">Users List</h5></div>
            <div style="padding-top: 15px;"><a href="<?php echo base_url('admin/users/addUser') ?>">
                    <button class="btn btn-success" type="button">Add New User</button>
                </a></div>
        </div>
        <div class="panel-body">
            <form action="<?php echo base_url('admin/users') ?>" method="get">
                <div class="datatable-header">
                    <div id="DataTables_Table_4_filter" class="dataTables_filter"><label><span>Filter:</span> <input
                                style="width: 380px;" class="" value="<?php echo @$q; ?>" name="q"
                                placeholder="First name, Last name, Email, Phone, User type" aria-controls="DataTables_Table_4"
                                type="search"> </label>
                        <button class="btn btn-default" type="submit">Search</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered table-hover datatable-highlight">
                <thead>
                <tr>
                    <th>First Name</th>
                    <th>last Name</th>
                    <th>Email</th>
                    <!--<th>DOB</th>-->
                    <th>Phone</th>
                    <th>User type</th>
                    <th>Creation Date</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>                <?php foreach ($content as $val) { ?>
                    <tr>
                        <td><?php echo ucfirst($val['firstname']) ?></td>
                        <td><?php echo ucfirst($val['lastname']) ?></td>
                        <td><?php echo ucfirst($val['email']) ?></td>
                        <!--<td><?php /*echo ucfirst($val['dob']) */?></td>-->
                        <td><?php echo ucfirst($val['phone']) ?></td>
                        <td><?php echo ucfirst($val['usertype']) ?></td>
                        <td><?php echo ucfirst(date('M d, Y H:i', ($val['createdate']))) ?></td>
                        <td class="text-center">
                            <ul class="icons-list">
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i
                                            class="icon-menu9"></i> </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="<?php echo(base_url('admin/users/userDetail/' . $val['userid'])); ?>"><i
                                                    class="fa fa-file-text-o"></i> Detail</a></li>
                                        <li>
                                            <a href="<?php echo(base_url('admin/users/editUser/' . $val['userid'])); ?>"><i
                                                    class="fa fa-pencil"></i> Edit</a></li>
                                        <li><a data-toggle="modal" data-target="#myModal_Del_<?php echo $val['userid']?>" href=""><i
                                                    class="fa fa-trash-o"></i> Delete</a></li>
                                    </ul>
                                </li>
                            </ul>

                            <!-- delete modal-->
                            <div id="myModal_Del_<?php echo $val['userid']?>" class="modal fade mymodelstyle" role="dialog">

                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <img src="<?php echo base_url()?>assets/images/logo_ef.png" alt="" />
                                        </div>
                                        <div class="modal-body">
                                            <p>Would you like to delete this user?</p>
                                            <div class="btn-payment">
                                                <a type="button" href="<?php echo(base_url('admin/users/deleteUser/' . $val['userid'])); ?>" class="btn btn-md btn-ok">ok</a>
                                                <a type="button" href="#" class="btn btn-md btn-cancel" class="close" data-dismiss="modal">cancel</a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- delete modal /-->

                        </td>
                    </tr>                <?php } ?>                </tbody>
            </table>
            <div class="datatable-footer">
                <div class="dataTables_info" id="DataTables_Table_4_info" role="status"
                     aria-live="polite"><?php echo $dataInfo; ?></div>
                <div class="dataTables_paginate paging_simple_numbers"
                     id="DataTables_Table_4_paginate">                    <?php echo $links; ?>
                    <!--<a class="paginate_button previous disabled" aria-controls="DataTables_Table_4" data-dt-idx="0" tabindex="0" id="DataTables_Table_4_previous">←</a>                    <span><a class="paginate_button current" aria-controls="DataTables_Table_4" data-dt-idx="1" tabindex="0">1</a><a class="paginate_button " aria-controls="DataTables_Table_4" data-dt-idx="2" tabindex="0">2</a></span>                    <a class="paginate_button next" aria-controls="DataTables_Table_4" data-dt-idx="3" tabindex="0" id="DataTables_Table_4_next">→</a>-->                </div>
            </div>
        </div>
    </div>