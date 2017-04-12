<!-- Content area -->
<div class="content">



    <!-- CKEditor default -->

    <div class="panel panel-flat">

        <div class="panel-heading">
            <h5 class="panel-title">Add Bid Detail</h5>
        </div>



        <div class="panel-body">

            <form method="post" class="form-horizontal" action="<?php echo(base_url('admin/bids/addBid')); ?>" enctype="multipart/form-data">

                <fieldset class="content-group">
                    <div class="form-group" id="">
                        <label class="control-label col-lg-2">Jobs:</label>
                        <div class="col-lg-6">
                            <select id="jobid" name="jobid" class="form-control">
                                <?php foreach ($jobs as $v) {?>
                                    <option value="<?php echo $v['jobid'];?>"><?php echo $v['jobname'];?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="">
                        <label class="control-label col-lg-2">Users:</label>
                        <div class="col-lg-6">
                            <select id="userids" name="userid" class="form-control">
                                <?php foreach ($users as $v) {?>
                                    <option value="<?php echo $v['userid'];?>"><?php echo $v['firstname'];?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Bid Amount: </label>
                        <div class="col-lg-6">
                            <?php echo form_error('bidamount'); ?>
                            <input type="number" min="0" required="" name="bidamount" value="<?php echo set_value('bidamount'); ?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Additional Amount: </label>
                        <div class="col-lg-6">
                            <?php echo form_error('additionalamount'); ?>
                            <input type="number" min="0" name="additionalamount" value="<?php echo set_value('additionalamount'); ?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Description: </label>
                        <div class="col-lg-6">
                            <?php echo form_error('description'); ?>
                            <textarea name="description" class="form-control"><?php echo set_value('description'); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2"> Start Date: </label>
                        <div class="col-lg-6">
                            <?php echo form_error('startdate'); ?>
                            <input type="text" name="startdate" id="datepicker" value="<?php echo set_value('startdate');?>" class="form-control pickadate-limits">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2"> Expected Time: </label>
                        <div class="col-lg-3">
                            <?php echo form_error('exptime'); ?>
                            <input type="text" id="exptime" name="exptime" value="<?php echo set_value('exptime');?>" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <select id="exptype" name="exptype" class="form-control">
                                <option value="h">Hours</option>
                                <option value="d">Days</option>
                                <option value="w">Weeks</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2"> Maximum Time: </label>
                        <div class="col-lg-3">
                            <?php echo form_error('maxtime'); ?>
                            <input type="text" id="maxtime" name="maxtime" value="<?php echo set_value('maxtime');?>" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <select id="maxtype" name="maxtype" class="form-control">
                                <option value="h">Hours</option>
                                <option value="d">Days</option>
                                <option value="w">Weeks</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2"> Is Favorite: </label>
                        <div class="col-lg-6">
                            <select id="isfavourite" name="isfavourite" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2"> Status: </label>
                        <div class="col-lg-6">
                            <select id="status" name="status" class="form-control">
                                <option value="0">OFF</option>
                                <option value="1">ON</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-3">
                            <button type="submit" class="btn bg-teal-400">Submit<i class="icon-arrow-right14 position-right"></i></button>
                            <a href="<?php echo base_url('admin/bids')?>">
                                <button type="button" class="btn bg-teal-400">Cancel<i class="icon-arrow-right14 position-right"></i></button>
                            </a>
                        </div>
                    </div>

                </fieldset>

            </form>

        </div>

    </div>
    <script>
        $(document).ready(function(){
            $("#biding").submit(function(e){
                if($('#exptype').val() == 'h'){
                    var exptime = $('#exptime').val();
                    if(exptime > 24 || exptime <= 0 || exptime == ''){
                        alert("Select hours between 1-24");
                        return false;
                    }
                }

                if($('#exptype').val() == 'd'){
                    var exptime = $('#exptime').val();
                    if(exptime > 31 || exptime <= 0 || exptime == ''){
                        alert("Select days between 1-31");
                        return false;
                    }
                }

                if($('#exptype').val() == 'w'){
                    var exptime = $('#exptime').val();
                    if(exptime > 5 || exptime <= 0 || exptime == ''){
                        alert("Select weeks between 1-5");
                        return false;
                    }
                }

                if($('#maxtype').val() == 'h'){
                    var maxtime = $('#maxtime').val();
                    if(maxtime > 24 || maxtime <= 0 || maxtime == ''){
                        alert("Select hours between 1-24");
                        return false;
                    }
                }

                if($('#maxtype').val() == 'd'){
                    var maxtime = $('#maxtime').val();
                    if(maxtime > 31 || maxtime <= 0 || maxtime == ''){
                        alert("Select days between 1-31");
                        return false;
                    }
                }

                if($('#maxtype').val() == 'w'){
                    var maxtime = $('#maxtime').val();
                    if(maxtime > 5 || maxtime <= 0 || maxtime == ''){
                        alert("Select weeks between 1-5");
                        return false;
                    }
                }
                /*if(!$("#chk1").is(':checked')){
                 // $("#tP").effect( "shake" );
                 $("#tP").shake(100,10,3);
                 e.preventDefault();
                 }*/
            });

            jQuery.fn.shake = function(interval,distance,times){
                interval = typeof interval == "undefined" ? 100 : interval;
                distance = typeof distance == "undefined" ? 10 : distance;
                times = typeof times == "undefined" ? 3 : times;
                var jTarget = $(this);
                jTarget.css('position','relative');
                for(var iter=0;iter<(times+1);iter++){
                    jTarget.animate({ left: ((iter%2==0 ? distance : distance*-1))}, interval);
                }
                return jTarget.animate({ left: 0},interval);
            };
        });
    </script>