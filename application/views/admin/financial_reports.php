<div class="content">



    <!-- CKEditor default -->

    <div class="panel panel-flat">

        <div class="panel-heading">
            <h3 class="panel-title">Report</h3>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">

                    <form method="post" class="form-horizontal" action="<?php echo(base_url('admin/reports/financial')); ?>">

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
                                <label class="control-label col-lg-2">User Type:</label>
                                <div class="col-lg-6">
                                    <select id="type" name="type" class="form-control">
                                        <option value="all">All</option>
                                        <option <?php if(isset($type) && $type == 'homeowner') echo 'selected';?> value="homeowner">HomeOwner</option>
                                        <option <?php if(isset($type) && $type == 'contractor') echo 'selected';?> value="contractor">Contractor</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-lg-2">Users:</label>
                                <div class="col-lg-6">
                                    <select id="users" name="users" class="form-control">
                                        <option value="all">All</option>
                                        <?php foreach ($usersList as $val) {?>
                                        <option <?php if(isset($userId) && $userId == $val['userid']) echo 'selected';?> value="<?php echo $val['userid'];?>"><?php echo $val['firstname'].' '.$val['lastname'];?></option>
                                        <?php }?>
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
                    <?php if(count($fList)>0){?>
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h4 class="panel-title">Financial Reporting:</h4>
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
                            <h4 class="panel-title">Financial Report List:</h4>
                        </div>

                        <div class="panel-body">
                            <?php if(count($fList)>0){ ?>
                            <table class="table table-bordered table-hover datatable-highlight">
                                <thead>
                                <tr>
                                    <th>Job Title</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>                <?php foreach ($fList as $val) { ?>
                                    <tr>
                                        <td><?php echo ucfirst($val['jobname']) ?></td>
                                        <td><?php echo ucfirst($val['amount']) ?></td>
                                        <td><?php echo ucfirst($val['jobstatus']) ?></td>
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
        $(document).ready(function(){

            $('#type').on('change', function() {
                $("#loaderImg").show();
                var elm = $('option:selected', this);
                var id = elm.val();
                var url = '<?php echo base_url('admin/reports/getUsers');?>';
                $.ajax(
                    {
                        url: url,
                        type: "POST",
                        dataType: "text",
                        data: {id:id},
                        success: function (data) {
                            if (data != '') {
                                //alert(JSON.stringify(data));
                                $("#loaderImg").hide();
                                $('#users').html(data);
                            }

                            //data: return data from server
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $("#loaderImg").hide();
                            alert("Something went wrong... Please try again")
                            //if fails
                        }
                    });
            });
        });
    </script>
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
                       /* title: {
                            text: 'Total Users: <?php //echo count($users);?>',
                            //subtext: 'Open source information',
                            x: 'center'
                        },*/

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