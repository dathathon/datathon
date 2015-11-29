<!DOCTYPE html>
<html>
    <head>
		<meta charset="UTF-8">
		<title>Team Enthusiastics - Datathon</title>
		<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">

		<link rel="shortcut icon" href="favicon.ico" />

		<!-- bootstrap framework -->
		<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">


		<link href="assets/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" media="screen">

		<!-- custom icons -->
			<!-- font awesome icons -->
			<link href="assets/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet" media="screen">
			<!-- ionicons -->
			<link href="assets/icons/ionicons/css/ionicons.min.css" rel="stylesheet" media="screen">
			<!-- flags -->
			<link rel="stylesheet" href="assets/icons/flags/flags.css">


	<!-- page specific stylesheets -->

		<!-- nvd3 charts -->
		<link rel="stylesheet" href="assets/lib/novus-nvd3/nv.d3.min.css">
		<!-- owl carousel -->
		<link rel="stylesheet" href="assets/lib/owl-carousel/owl.carousel.css">

		<!-- main stylesheet -->
		<link href="assets/css/style.css" rel="stylesheet" media="screen">

		<!-- google webfonts -->
		<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400&amp;subset=latin-ext,latin' rel='stylesheet' type='text/css'>

		<!-- moment.js (date library) -->
		<script src="assets/lib/moment-js/moment.min.js"></script>

		<!-- morries charts -->
		<link rel="stylesheet" href="assets/morris/morris.css">

		<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
		<script src="assets/morris/morris.min.js"></script>
		<style>
			.filterCss {
				font-size: 11px;
				line-height: 16px;
				height: 200px;
				overflow: auto;
				cursor: pointer;
				padding: 0px 0px 0px 12px;
			}
			.panelheadercss {
				padding-bottom: 6px;
			}
		</style>
    </head>
    <body>
		<!-- top bar -->
		<header class="navbar navbar-fixed-top" role="banner">
			<div class="container-fluid">
				<div class="navbar-header" style="color: rgb(255, 255, 255); font-size: 20px; padding-top: 6px;">
					Team Enthusiastics
				</div>
			</div>
		</header>

		<!-- main content -->
		<div id="main_wrapper">
			<div class="page_content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-body">
									<style>

							      #heatmap {
							        width:100%;
							        height:300px;
							      }
							        #floating-panel {
							          position: absolute;
							          top: 10px;
							          left: 25%;
							          z-index: 5;
							          background-color: #fff;
							          padding: 5px;
							          border: 1px solid #999;
							          text-align: center;
							          font-family: 'Roboto','sans-serif';
							          line-height: 30px;
							          padding-left: 10px;
							        }

							      #floating-panel {
							        background-color: #fff;
							        border: 1px solid #999;
							        right: 30%;
							        padding: 5px;
							        position: absolute;
							        top: 0px;
							        z-index: 5;
							      }
							    </style>
									<div id="floating-panel">
										<select name="places" class="form-control">
											<option class='toggelPlace' place='airport'>Airports</option>
											<option class='toggelPlace' place='school'>Schools</option>
											<option class='toggelPlace' place='university'>University</option>
											<option class='toggelPlace' place='shopping_mall'>Shopping Mall</option>
											<option class='toggelPlace' place='train_station'>Train Station</option>
											<option class='toggelPlace' place='subway_station'>Subway Station</option>
											<option class='toggelPlace' place='taxi_stand'>Taxi Stand</option>
										</select>
							      <!-- <button onclick="toggleHeatmap()">Toggle Heatmap</button>
							      <button onclick="changeGradient()">Change gradient</button>
							      <button onclick="changeRadius()">Change radius</button>
							      <button onclick="changeOpacity()">Change opacity</button> -->
							    </div>
							    <div id="heatmap"></div>
									<!-- <div id="nvd3_cumulativeLine" style="width:100%;height:300px">
										<svg></svg>
									</div> -->
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-body">
									<div id="donutChart" style="width:100%;height:300px">
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-6">
									<div class="panel panel-default">
										<div class="panel-header panelheadercss">
											Category
										</div>

										<div class="panel-body">
											<input type="text" name="appsiteids"  >
											<select id="appsiteids" class="selectpicker" multiple  data-live-search="true" onchange="commaSelector('appsiteids')">

										  </select>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="panel panel-default">
										<div class="panel-header panelheadercss">
											Time
										</div>
										<input type="text" name="time" >
										<div class="panel-body">
											<select id="time" class="selectpicker" multiple  data-live-search="true" onchange="commaSelector('time')">
										    <?php
										    	foreach(range(intval('00:00:00'),intval('23:00:00')) as $time) {
										    		$ap = (date("H", mktime($time+1)) < 12)? "AM" : "PM";
													  echo '<option value="'.date("H", mktime($time+1)).'">'.date("H:00", mktime($time+1)).' '.$ap.'</option>';
													}

										    ?>
										  </select>
										</div>
									</div>
								</div>
							</div><!-- ending row -->
							<!-- div class="row">
								<div class="col-md-6">
									<div class="panel panel-default">
										<div class="panel-header">
											Impression
										</div>
										<div class="panel-body">
											<div  onclick="getGrapData('app','id');">App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="panel panel-default">
										<div class="panel-header">
											Avg bid wins
										</div>
										<div class="panel-body">
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
										</div>
										</div>
									</div>
								</div> --><!-- ending row -->
								<!-- <div class="row">
								<div class="col-md-6">
									<div class="panel panel-default">
										<div class="panel-header">
											Impression
										</div>
										<div class="panel-body">
											<div  onclick="getGrapData('app','id');">App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="panel panel-default">
										<div class="panel-header">
											Avg bid wins
										</div>
										<div class="panel-body">
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
											<div>App1 <span style="float:right;">12K</span></div>
										</div>
										</div>
									</div>
								</div> --><!-- ending row -->
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="heading_b">Social Networks</div>
									<div class="row">
										<div class="col-md-7">
											<table class="table table-striped">
												<thead>
													<tr>
														<th>Social Network</th>
														<th class="col_md sub_col">Visits</th>
														<th class="col_md sub_col">Pageviews</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><a href="#">Twitter</a></td>
														<td class="sub_col">423</td>
														<td class="sub_col">631</td>
													</tr>
													<tr>
														<td><a href="#">Google+</a></td>
														<td class="sub_col">316</td>
														<td class="sub_col">549</td>
													</tr>
													<tr>
														<td><a href="#">LinkedIn</a></td>
														<td class="sub_col">264</td>
														<td class="sub_col">388</td>
													</tr>
													<tr>
														<td><a href="#">Facebook</a></td>
														<td class="sub_col">152</td>
														<td class="sub_col">274</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="col-md-5">
											<div id="flot_social" class="chart" style="height:240px;width:100%">
												<script>
													chart_social_data = [
														{ label: "Twitter", data: 423, color: '#1f77b4' },
														{ label: "Google+", data: 316, color: '#ff7f0e' },
														{ label: "LinkedIn", data: 264, color: '#2ca02c' },
														{ label: "Facebook", data: 152, color: '#d62728' }
													];
												</script>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="heading_b">Browsers</div>
									<div class="row">
										<div class="col-md-7">
											<table class="table table-striped">
												<thead>
													<tr>
														<th>Browser</th>
														<th class="col_md sub_col">Visits</th>
														<th class="col_md sub_col">% visits</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><a href="#">Firefox</a></td>
														<td class="sub_col">1428</td>
														<td class="sub_col">54%</td>
													</tr>
													<tr>
														<td><a href="#">Chrome</a></td>
														<td class="sub_col">858</td>
														<td class="sub_col">21%</td>
													</tr>
													<tr>
														<td><a href="#">Safari</a></td>
														<td class="sub_col">647</td>
														<td class="sub_col">11%</td>
													</tr>
													<tr>
														<td><a href="#">Internet Explorer</a></td>
														<td class="sub_col">433</td>
														<td class="sub_col">6%</td>
													</tr>
													<tr>
														<td><a href="#">Opera</a></td>
														<td class="sub_col">141</td>
														<td class="sub_col">2%</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="col-md-5">
											<div id="flot_browsers" class="chart" style="height:240px;width:100%">
												<script>
													chart_browsers_data = [
														{ label: "Firefox", data: 1428, color: '#1f77b4' },
														{ label: "Chrome", data: 858, color: '#aec7e8' },
														{ label: "Safari", data: 647, color: '#ff7f0e' },
														{ label: "IE", data: 433, color: '#ffbb78' },
														{ label: "Opera", data: 141, color: '#2ca02c' }
													];
												</script>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>

		<!-- jQuery -->
		<script src="assets/js/jquery.min.js"></script>
		<!-- easing -->
		<script src="assets/js/jquery.easing.1.3.min.js"></script>
		<!-- bootstrap js plugins -->
		<script src="assets/bootstrap/js/bootstrap.min.js"></script>
		<!-- top dropdown navigation -->
		<script src="assets/js/tinynav.js"></script>
		<!-- perfect scrollbar -->
		<script src="assets/lib/perfect-scrollbar/min/perfect-scrollbar-0.4.8.with-mousewheel.min.js"></script>

		<!-- common functions -->
		<script src="assets/js/tisa_common.js"></script>

		<!-- style switcher -->

		<!-- morries charts -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
		<script src="assets/morris/morris.min.js"></script>


		<script src="assets/bootstrap-select/js/bootstrap-select.min.js"></script>



	<!-- page specific plugins -->

		<!-- nvd3 charts -->
		<script src="assets/lib/d3/d3.min.js"></script>
		<script src="assets/lib/novus-nvd3/nv.d3.min.js"></script>
		<!-- flot charts-->
		<script src="assets/lib/flot/jquery.flot.min.js"></script>
		<script src="assets/lib/flot/jquery.flot.pie.min.js"></script>
		<script src="assets/lib/flot/jquery.flot.resize.min.js"></script>
		<script src="assets/lib/flot/jquery.flot.tooltip.min.js"></script>
		<!-- clndr -->
		<script src="assets/lib/underscore-js/underscore-min.js"></script>
		<script src="assets/lib/CLNDR/src/clndr.js"></script>
		<!-- easy pie chart -->
		<script src="assets/lib/easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
		<!-- owl carousel -->
		<script src="assets/lib/owl-carousel/owl.carousel.min.js"></script>

		<!-- dashboard functions -->
		<script src="assets/js/apps/tisa_dashboard.js"></script>

		<!-- coustom js -->
		<script src="assets/js/custom.js"></script>
		<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAB__cbbJ2PipAypvhQ3wjV5MB0mavfhEY&signed_in=true&libraries=visualization,places&callback=initMap">
    </script>
    </body>
</html>
