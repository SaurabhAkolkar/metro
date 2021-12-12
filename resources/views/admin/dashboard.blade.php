@include('admin.header')

<style>
  .f-14{
   font-size:14px !important;
  }
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
 <section class="content-header">
      <h1>
        Dashboard
      </h1>
      
    </section> 
  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
   <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Users</span>
              <span class="info-box-number badge bg-custom">{{$maleCount+$femaleCount}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-4 col-sm-6 col-xs-6">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-ban"></i></span>

            <div class="info-box-content">
              <span class="info-box-text f-14" >Active</span>
              <span class="info-box-number f-14 badge bg-custom" >{{$total->total_active}}</span>
              <span class="info-box-text f-14" >Inactive</span>
              <span class="info-box-number f-14 badge bg-custom" >{{$total->total_inactive}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
       
        <div class="col-md-4 col-sm-6 col-xs-6">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-male"></i></span>

            <div class="info-box-content">
              <span class="info-box-text f-14" >Male</span>
              <span class="info-box-number f-14 badge bg-custom" >{{$maleCount}}</span>
              <span class="info-box-text f-14" >Female</span>
              <span class="info-box-number f-14 badge bg-custom" >{{$femaleCount}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->



        <div class="col-md-4 col-sm-6 col-xs-6">
          <div class="info-box">
            <span class="info-box-icon bg-custom"><i class="fa fa-exchange"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Requests</span>
              <span class="info-box-number badge bg-custom">{{$interest + $accepted}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        
        <div class="col-md-4 col-sm-6 col-xs-6">
          <div class="info-box">
            <span class="info-box-icon label-primary"><i class="fa fa-location-arrow"></i></span>
        
            <div class="info-box-content">
              <span class="info-box-text f-14" >Pending</span>
              <span class="info-box-number f-14 badge bg-custom" >{{$interest}}</span>
              <span class="info-box-text f-14" >Accepted</span>
              <span class="info-box-number f-14 badge bg-custom" >{{$accepted}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-12">
         <div class="box">
          <canvas id="canvas"></canvas>
          </div>
        </div>
      </div>


</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@include('admin.footer')
<script src="<?=url("assets/admin/plugin/chartjs/Chart.bundle.js")?>"></script>
<script src="<?=url("assets/admin/plugin/chartjs/utils.js")?>"></script>
<style>
  canvas {
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
    /* max-height: 270px; */
  }
</style>
<script>
  var color = Chart.helpers.color;
		var barChartData = {
		labels: [
        @foreach($graph as $g) 
        '{{date("d",strtotime($g->date))}}', 
        @endforeach
      ],
			datasets: [ {
				type: 'line',
				label: 'Female',
        fill:true,
				backgroundColor: color("#ff4081").alpha(0.7).rgbString(),
				borderColor: "#ad1457",
				data: [
					@foreach($graph as $g)
             {{ $g->female}},
          @endforeach
				]
			},
       {
				type: 'line',
        title:"days",
				label: 'Male',
        fill:true,
				backgroundColor: color("#2962ff").alpha(0.5).rgbString(),
				borderColor: "#2962ff",
				data: [
				@foreach($graph as $g)
             {{$g->male }},
          @endforeach
				]
			},
      {
				type: 'bar',
				label: 'Total',
				backgroundColor: color("#00c853").alpha(0.7).rgbString(),
				borderColor: "#00c853",
				data: [
					@foreach($graph as $g)
             {{$g->male + $g->female}},
          @endforeach
				]
			}
      
      
      ]
		};

		// Define a plugin to provide data labels
		Chart.plugins.register({
			afterDatasetsDraw: function(chart) {
				var ctx = chart.ctx;

				chart.data.datasets.forEach(function(dataset, i) {
					var meta = chart.getDatasetMeta(i);
					if (!meta.hidden) {
						meta.data.forEach(function(element, index) {
							// Draw the text in black, with the specified font
							ctx.fillStyle = 'rgb(0, 0, 0)';

							var fontSize = 16;
							var fontStyle = 'normal';
							var fontFamily = 'Helvetica Neue';
							ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

							// Just naively convert to string for now
							var dataString = dataset.data[index].toString();

							// Make sure alignment settings are correct
							ctx.textAlign = 'center';
							ctx.textBaseline = 'middle';

							var padding = 5;
							var position = element.tooltipPosition();
              if(i==2)
							ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
						});
					}
				});
			}
		});

		window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Users Per day'
					},
          scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Days'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'No of Users'
						}
					}]
				}
          
				}
			});
		};

		
</script>
<script>
 
// var options = {
//         events: false,
//         showTooltips: false,
//         animation: {
//             duration: 0,
//             onComplete: function () {
//                 var ctx = this.chart.ctx;
//                 ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
//                 ctx.textAlign = 'center';
//                 ctx.textBaseline = 'bottom';

//                 this.data.datasets.forEach(function (dataset) {
//                 		console.log(dataset);
//                     for (var i = 0; i < dataset.data.length; i++) {
//                     		console
//                         var model = dataset._meta[0].dataset._children[i]._model;
//                         ctx.fillText(dataset.data[i], model.x, model.y - 5);
//                     }
//                 });               
//             }
//         }
//     };




// 	var config = {
// 		type: 'line',
//     showTooltips: false,
// 		data: {
// 			labels: [
//         @foreach($graph as $g) 
//         '{{date("d",strtotime($g->date))}}', 
//         @endforeach
//       ],
// 			datasets: [{
// 				label: 'Users',
// 				backgroundColor: window.chartColors.red,
// 				borderColor: window.chartColors.red,
// 				fill: false,
// 				data: [
// 					@foreach($graph as $g)
//              {{$g->total}},
//           @endforeach
// 				],
// 			}]
// 		},
// 		options: options
    
// 	};

// 	window.onload = function() {
// 		var ctx = document.getElementById('canvas').getContext('2d');
// 		window.myLine = new Chart(ctx, config);
// 	};


</script>

