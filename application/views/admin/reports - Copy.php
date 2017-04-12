<!-- Content area -->

<div class="content">



    <!-- CKEditor default -->

    <div class="panel panel-flat">

        <div class="panel-heading">
            <h5 class="panel-title">Reporting</h5>
        </div>



        <div class="panel-body">

            <form method="post" class="form-horizontal" action="<?php echo(base_url('admin/reports')); ?>" enctype="multipart/form-data">

                <fieldset class="content-group">

                    <div class="form-group">
                        <label class="control-label col-lg-2">Employees</label>
                        <div class="col-lg-6">
                            <select id="employees" name="employeeId" class="form-control">
                            <?php foreach ($employees as $val) {?>
                                <option value="<?php echo $val['id']?>"><?php echo $val['name']?></option>
                            <?php }?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Date Range</label>
                        <div class="col-lg-6">
                            <div class="col-md-6" style="padding-left: 0;">
                                <p><input type="text" name="start" class="form-control" id="rangeDemoStart" placeholder="Start date"></p>
                            </div>
                            <div class="col-md-6" style="padding-right: 0;">
                                <p><input type="text" name="end" class="form-control" id="rangeDemoFinish" placeholder="Finish date" disabled="disabled"></p>
                            </div>
                        </div>
                        <input type="button" id="rangeDemoToday" class="btn btn-primary" value="today">
                        <input type="button" id="rangeDemoClear" class="btn btn-default" value="clear">
                    </div>

                    <div class="form-group">

                        <div class="col-lg-3">
                            <button type="submit" class="btn bg-teal-400">Generate<i class="icon-arrow-right14 position-right"></i></button>
                        </div>

                    </div>



                </fieldset>

            </form>

        </div>

    </div>

    <!-- /CKEditor default -->