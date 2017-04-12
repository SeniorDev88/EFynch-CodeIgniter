<div class="content">



    <!-- CKEditor default -->

    <div class="panel panel-flat">

        <div class="panel-heading">
            <h3 class="panel-title">Report</h3>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">

                    <form method="post" class="form-horizontal" action="<?php echo(base_url('admin/reports/users')); ?>">

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
                                        <option <?php if(isset($status) && $status == 'homeowner') echo 'selected';?> value="homeowner">HomeOwner</option>
                                        <option <?php if(isset($status) && $status == 'contractor') echo 'selected';?> value="contractor">Contractor</option>
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
                            <h4 class="panel-title">User Reporting:</h4>
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
                            <h4 class="panel-title">Users List:</h4>
                        </div>

                        <div class="panel-body">
                            <?php if(count($users)>0){ ?>
                            <table class="table table-bordered table-hover datatable-highlight">
                                <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>last Name</th>
                                    <th>Email</th>
                                    <th>DOB</th>
                                    <th>Phone</th>
                                    <th>Creation Date</th>
                                </tr>
                                </thead>
                                <tbody>                <?php foreach ($users as $val) { ?>
                                    <tr>
                                        <td><?php echo ucfirst($val['firstname']) ?></td>
                                        <td><?php echo ucfirst($val['lastname']) ?></td>
                                        <td><?php echo ucfirst($val['email']) ?></td>
                                        <td><?php echo ucfirst($val['dob']) ?></td>
                                        <td><?php echo ucfirst($val['phone']) ?></td>
                                        <td><?php echo ucfirst(date('M d, Y H:i', ($val['createdate']))) ?></td>
                                    </tr>                <?php } ?>                </tbody>
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
                            text: 'Total Users: <?php echo count($users);?>',
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