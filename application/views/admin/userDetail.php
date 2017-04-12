<div class="content">
    <!-- CKEditor default -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title pull-left">User Detail</h5>
            <div class="pull-right">
                <a href="<?php echo base_url('admin/users/addUser')?>">
                    <button type="button" class="btn bg-teal-400">Add New User<i class="icon-arrow-right14 position-right"></i></button>
                </a>
                <a href="<?php echo base_url('admin/users/editUser/'.$user['userid'])?>">
                    <button type="button" class="btn bg-teal-400">Edit User<i class="icon-arrow-right14 position-right"></i></button>
                </a>
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
                    <td>First Name:</td>
                    <td><?php echo $user['firstname'];?></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><?php echo $user['lastname'];?></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><?php echo $user['email'];?></td>
                </tr>
                <tr>
                    <td>DOB:</td>
                    <td><?php echo $user['dob'];?></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><?php echo $user['address'];?></td>
                </tr>
                <tr>
                    <td>State:</td>
                    <td><?php echo $user['state'];?></td>
                </tr>
                <tr>
                    <td>City:</td>
                    <td><?php echo $user['city'];?></td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td><?php echo $user['phone'];?></td>
                </tr>
                <tr>
                    <td>User Type:</td>
                    <td><?php echo $user['usertype'];?></td>
                </tr>

                <?php if($user['usertype']=='contractor'){?>
                    <tr>
                        <td>Company Name:</td>
                        <td><?php echo $user['companyname'];?></td>
                    </tr>
                    <tr>
                        <td>Company Address:</td>
                        <td><?php echo $user['companyaddress'];?></td>
                    </tr>
                    <tr>
                        <td>Company State:</td>
                        <td><?php echo $user['companystate'];?></td>
                    </tr>
                    <tr>
                        <td>Company Zip:</td>
                        <td><?php echo $user['companyzip'];?></td>
                    </tr>
                    <tr>
                        <td>Company City:</td>
                        <td><?php echo $user['companycity'];?></td>
                    </tr>
                    <tr>
                        <td>Bank Insurance:</td>
                        <td><?php echo $user['insurance'];?></td>
                    </tr>
                    <tr>
                        <td>Bank License:</td>
                        <td><?php echo $user['license'];?></td>
                    </tr>
                    <tr>
                        <td>Overview Experience:</td>
                        <td><?php echo $user['overview_experience'];?></td>
                    </tr>
                    <tr>
                        <td>Introduction:</td>
                        <td><?php echo $user['introduction'];?></td>
                    </tr>
                    <tr>
                        <td>Account Number:</td>
                        <td><?php echo $user['accountnumber'];?></td>
                    </tr>
                    <tr>
                        <td>Routing Number:</td>
                        <td><?php echo $user['routingnumber'];?></td>
                    </tr>
                    <tr>
                        <td>Certifications:</td>
                        <td><?php echo $user['certifications'];?></td>
                    </tr>
                    <tr>
                        <td>Notable Work:</td>
                        <td><?php echo $user['notable_work'];?></td>
                    </tr>
                    <tr>
                        <td>Insurance:</td>
                        <td><?php echo $user['contractorInsurance'];?></td>
                    </tr>
                    <tr>
                        <td>License:</td>
                        <td><?php echo $user['contractorLicense'];?></td>
                    </tr>
                    <tr>
                        <td>Us Veteran:</td>
                        <td><?php if($user['us_veteran'] == '0')echo 'No';else echo 'Yes';?></td>
                    </tr>
                    <tr>
                        <td>Work Image:</td>
                        <td>
                            <?php foreach ($user['images'] as $v) {?>
                                <img style="height: auto;width: 15%;margin-right: 10px;" src="
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
                        <td>Intro Video:</td>
                        <?php if(!empty($user['intro_video'])){?>
                        <td>
                            <video style="height: auto;width: 50%;" controls>
                                <source src="<?php echo base_url('assets/videos/'.$user['intro_video'])?>" type="video/mp4">
                            </video>
                        </td>
                        <?php }else{?>
                            <td>
                                No video found
                            </td>
                        <?php }?>
                    </tr>
                <?php }?>

                <tr>
                    <td>Is Verified:</td>
                    <td><?php if($user['isverified'] == 0) echo 'No';else echo 'Yes';?></td>
                </tr>
                <tr>
                    <td>Image:</td>
                    <td><img style="height: auto;width: 15%;" src="
                        <?php
                        if(!@getimagesize(USER_IMAGE_CROP.$user['imagekey'].'.'.$user['imageext']))
                            echo DEFAULT_USER;
                        else
                            echo USER_IMAGE_CROP.$user['imagekey'].'.'.$user['imageext'];
                        ?>" /></td>
                </tr>
                <tr>
                    <td>Created At:</td>
                    <td><?php echo date('M d,Y H:i',$user['createdate']);?></td>
                </tr>
                <tr>
                    <td>Updated On:</td>
                    <td><?php echo date('M d,Y H:i',$user['updatedate']);?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>