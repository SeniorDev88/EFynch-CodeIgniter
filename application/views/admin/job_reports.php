<div class="content">



    <!-- CKEditor default -->

    <div class="panel panel-flat">

        <div class="panel-heading">
            <h3 class="panel-title">Report</h3>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">

                    <form method="post" class="form-horizontal" action="<?php echo(base_url('admin/reports/jobs')); ?>">

                        <fieldset class="content-group">
                            <div class="form-group">
                                <label class="control-label col-lg-2"> Start Date: </label>
                                <div class="col-lg-6">
                                    <input type="text" name="startdate" id="datepicker" value="<?php if(isset($startdate)) echo $startdate;?>" class="form-control end-current-date">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-lg-2"> End Date: </label>
                                <div class="col-lg-6">
                                    <input type="text" name="enddate" id="datepicker2" value="<?php if(isset($enddate)) echo $enddate;?>" class="form-control end-current-date">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-lg-2">Status:</label>
                                <div class="col-lg-6">
                                    <select id="status" name="status" class="form-control">
                                        <option value="all">All</option>
                                        <option <?php if(isset($status) && $status == 'new') echo 'selected';?> value="new">New</option>
                                        <option <?php if(isset($status) && $status == 'inprogress') echo 'selected';?> value="inprogress">In-progress</option>
                                        <option <?php if(isset($status) && $status == 'completed') echo 'selected';?> value="completed">Completed</option>
                                        <option <?php if(isset($status) && $status == 'verified') echo 'selected';?> value="verified">Verified</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-3">
                                    <button type="submit" class="btn bg-teal-400">Search<i class="icon-arrow-right14 position-right"></i></button>
                                </div>
                            </div>
                        </fieldset>

                    </form>

                    <!-- Basic pie chart -->
                    <?php if(count($report)>0){?>
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h4 class="panel-title">Job Reporting:</h4>
                        </div>
                        <div class="panel-body">
                            <div class="chart-container has-scroll">
                                <div class="chart has-fixed-height has-minimum-width" id="basic_pie"></div>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <!-- Basic pie chart -->
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h4 class="panel-title">Jobs List:</h4>
                        </div>

                        <div class="panel-body">
                            <?php if(count($jobs)>0){?>
                            <table class="table table-bordered table-hover datatable-highlight">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Created By</th>
                                    <th>Total Bids</th>
                                    <th>Expertise</th>
                                    <!--<th>Address</th>-->
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Zip</th>
                                    <th>Budget</th>
                                    <th>Job Status</th>
                                    <th>Creation Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($jobs as $val) {?>
                                    <tr>
                                        <td><a href="<?php echo base_url('admin/jobs/jobDetail/'.$val['jobid']);?>"><?php echo ucfirst($val['jobname'])?></a></td>
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
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <?php }else{?>
                                <h6>No record found</h6>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/visualization/echarts/echarts.js"></script>
    <!--<script type="text/javascript" src="<?php /*echo(base_url()); */?>assets/admin/js/charts/echarts/pies_donuts.js"></script>-->
    <script>
        /* ------------------------------------------------------------------------------
         *
         *  # Echarts - pies and donuts
         *
         *  Pies and donuts chart configurations
         *
         *  Version: 1.0
         *  Latest update: August 1, 2015
         *
         * ---------------------------------------------------------------------------- */

        $(function () {
            // Set paths
            // ------------------------------

            require.config({
                paths: {
                    echarts: '<?php echo base_url();?>assets/admin/js/plugins/visualization/echarts'
                }
            });

            // Configuration
            // ------------------------------

            require(
                [
                    'echarts',
                    'echarts/theme/limitless',
                    'echarts/chart/pie',
                    'echarts/chart/funnel'
                ],


                // Charts setup
                function (ec, limitless) {


                    // Initialize charts
                    // ------------------------------

                    var basic_pie = ec.init(document.getElementById('basic_pie'), limitless);


                    // Charts setup
                    // ------------------------------

                    //
                    // Basic pie options
                    //

                    basic_pie_options = {

                        // Add title
                        title: {
                            text: 'Total Job: <?php echo count($jobs);?>',
                            //subtext: 'Open source information',
                            x: 'center'
                        },

                        // Add tooltip
                        tooltip: {
                            trigger: 'item',
                            formatter: "{a} <br/>{b}: {c} ({d}%)"
                        },

                        // Add legend
                        legend: {
                            orient: 'vertical',
                            x: 'left',
                            data: [<?php //.echo '"'.implode('","', array_column($options, 'title')).'"' ?>]
                        },
                        // Add series
                        series: [{
                            //name: 'Question:',
                            type: 'pie',
                            radius: '70%',
                            //center: ['50%', '57.5%'],
                            data: [
                                <?php foreach ($report as $k=>$val){
                                    echo "{value: ".$val.", name: '".$k."'},";
                                    }
                                ?>
                                //{value: '335', name: 'abc'},
                                //{value: 310, name: 'xyz'}
                            ]
                        }]
                    };




                    // Apply options
                    // ------------------------------

                    basic_pie.setOption(basic_pie_options);
                }
            );
        });

    </script>
    <!-- /CKEditor default -->