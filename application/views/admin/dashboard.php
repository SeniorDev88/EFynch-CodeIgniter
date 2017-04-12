<div class="content">
	
	<div class="dashboard-content">
		<div class="three-column">
			<div class="col-md-4">
                <a href="<?php echo base_url('admin/users?q=contractor');?>">
				<div class="dash-box green">
					<div class="icon-holder">
						<i class="fa fa-users" aria-hidden="true"></i>
					</div>
					<div class="dash-content">
						<strong><?php echo $contractorUsers;?></strong>
						<h2>Contractor</h2>
					</div>
				</div>
                </a>
			</div>
			<div class="col-md-4">
                <a href="<?php echo base_url('admin/users?q=homeowner');?>">
				<div class="dash-box blue">
					<div class="icon-holder">
						<i class="fa fa-home" aria-hidden="true"></i>
					</div>
					<div class="dash-content">
						<strong><?php echo $homeUsers;?></strong>
						<h2>Homeowner</h2>
					</div>
				</div>
                </a>
				<!-- Box End -->
			</div>
			<div class="col-md-4">
                <a href="<?php echo base_url('admin/jobs');?>">
				<div class="dash-box indigo">
					<div class="icon-holder">
						<i class="fa fa-briefcase" aria-hidden="true"></i>
					</div>
					<div class="dash-content">
						<strong><?php echo $jobs;?></strong>
						<h2>Jobs</h2>
					</div>
				</div>
                </a>
				<!-- Box End -->
			</div>
		</div>
		<!-- Three Columns -->
		<div class="map-holder">
			<div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h4 class="panel-title">Users Graph:</h4>
                    </div>

                    <div class="panel-body">
                        <div class="chart-container has-scroll">
                            <div class="chart has-fixed-height has-minimum-width" id="basic_pie"></div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h4 class="panel-title">Jobs Graph:</h4>
                    </div>

                    <div class="panel-body">
                        <div class="chart-container has-scroll">
                            <div class="chart has-fixed-height has-minimum-width" id="jobG"></div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<!-- map-holder -->
	</div>
	<!-- Dashboard content -->
	
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

                var basic_pie = ec.init(document.getElementById('jobG'), limitless);


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
                            <?php foreach ($jobsG as $k=>$val){
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