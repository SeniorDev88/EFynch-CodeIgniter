<div class="content">
    <!-- CKEditor default -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title pull-left">Bids Detail</h5>
            <div class="pull-right">
                <a href="<?php echo base_url('admin/bids/addBid')?>">
                    <button type="button" class="btn bg-teal-400">Add New Bid<i class="icon-arrow-right14 position-right"></i></button>
                </a>
                <a href="<?php echo base_url('admin/bids/editBid/'.$data['bidid'])?>">
                    <button type="button" class="btn bg-teal-400">Edit Bid<i class="icon-arrow-right14 position-right"></i></button>
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
                    <td>Job Name:</td>
                    <td><?php echo $data['jobname'];?></td>
                </tr>
                <tr>
                    <td>User:</td>
                    <td><?php echo $data['firstname'].' '.$data['lastname'];?></td>
                </tr>
                <tr>
                    <td>Bid Amount:</td>
                    <td><?php echo $data['bidamount'];?></td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td><?php echo $data['description'];?></td>
                </tr>
                <tr>
                    <td>Additional Amount:</td>
                    <td><?php echo $data['additionalamount'];?></td>
                </tr>
                <tr>
                    <td>Start DateTime:</td>
                    <td><?php echo date('M d, Y H:i',strtotime($data['bStartDate'].' '.$data['starttime']));?></td>
                </tr>
                <tr>
                    <td>Is favourite:</td>
                    <td><?php if($data['isfavourite'] == 0) echo 'No';else echo 'Yes';?></td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td><?php if($data['status'] == 0) echo 'Off';else echo 'On';?></td>
                </tr>
                <tr>
                    <td>Created At:</td>
                    <td><?php echo date('M d,Y H:i',$data['bcreatedate']);?></td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>