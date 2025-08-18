      <!-- Footer Script -->
      <!--================================-->
      <script src="{{url('/')}}/AdminAssets/assets/plugins/jquery/jquery.min.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/jquery-ui/jquery-ui.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/moment/moment.min.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/popper/popper.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/feather/feather.min.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/typeahead/typeahead.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/typeahead/typeahead-active.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/pace/pace.min.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/highlight/highlight.min.js"></script>
      <!-- Dashboard Script -->
      <script src="{{url('/')}}/AdminAssets/assets/plugins/daterangepicker/daterangepicker.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/jqvmap/jquery-jvectormap-2.0.2.min.js"></script>	
      <script src="{{url('/')}}/AdminAssets/assets/plugins/jqvmap/gdp-data.js"></script>	
      <script src="{{url('/')}}/AdminAssets/assets/plugins/jqvmap/maps/jquery-jvectormap-world-mill-en.js"></script>	
      <script src="{{url('/')}}/AdminAssets/assets/plugins/apex-chart/apexcharts.min.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/apex-chart/irregular-data-series.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/flot/jquery.flot.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/flot/jquery.flot.pie.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/flot/jquery.flot.resize.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/flot/sampledata.js"></script>  
      <script src="{{url('/')}}/AdminAssets/assets/plugins/summernote/summernote.min.js"></script>

      <!-- Required Script -->
      <script src="{{url('/')}}/AdminAssets/assets/js/app.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/js/avesta.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/js/avesta-customizer.js"></script>
      <!--Toaster-->
      <script src="{{url('/')}}/AdminAssets/assets/plugins/toastr/toastr.min.js"></script>

      <!-- dataTables Script -->
      <script src="{{url('/')}}/AdminAssets/assets/plugins/dataTable/datatables.min.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/dataTable/responsive/dataTables.responsive.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/dataTable/extensions/dataTables.jqueryui.min.js"></script>

      <script src="{{url('/')}}/AdminAssets/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
      <script src="{{url('/')}}/AdminAssets/assets/plugins/select2/js/select2.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

      <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
      <!-- Javascript -->
      @if(request()->is('admin/users/create'))
      <script>
         //World Map
         "use strict";
         $(document).ready(function () {
         	// World Map
         	$('#world-map').vectorMap({
         		map: 'world_mill_en',
         		backgroundColor: 'transparent',
         		markerStyle: {
         			initial: {
         				fill: '#2e2e2e',
         				stroke: '#2e2e2e',
         				"fill-opacity": 1,
         				"stroke-width": 15,
         				"stroke-opacity": 0.2
         			}
         		},
         		markers: [{
         				latLng: [37.18, -93.35],
         				name: 'United States'
         			},
         			{
         				latLng: [20.59, 78.96],
         				name: 'India'
         			},
         			{
         				latLng: [-25.27, 133.77],
         				name: 'Australia'
         			},
         			{
         				latLng: [-38.41, -63.61],
         				name: 'Argentina'
         			},
         			{
         				latLng: [61.52, 105.31],
         				name: 'Russia'
         			},
         			{
         				latLng: [-30.55, 22.93],
         				name: 'South Africa'
         			},
         		],
         		focusOn: {
         			x: 0,
         			y: 0,
         			scale: 1
         		},
         		series: {
         			regions: [{
         				values: {
         					US: 'rgba(148, 77, 255, 0.3)',
         					AU: 'rgba(255, 228, 17, 0.3)',
         					IN: 'rgba(8, 210, 111, 0.3)',
         					AR: 'rgba(252, 79, 104, 0.3)',
         					RU: 'rgba(129, 206, 246, 0.3)',
         					ZA: 'rgba(252, 79, 104, 0.3)',
         				}
         			}]
         		},
         		regionStyle: {
         			initial: {
         				fill: '#e9eff9'
         			}
         		}
         	});
         
         });
         
         // Dashboard DateTimePicker
         $(function() {
         var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
         
         // Button         
         var start = moment().subtract(29, 'days');
         var end = moment();
         
         function cb(start, end) {
         $('#dashboardDate').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
         }         
         $('#dashboardDate').daterangepicker({
         startDate: start,
         endDate: end,
         ranges: {
         'Today': [moment(), moment()],
         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
         'This Month': [moment().startOf('month'), moment().endOf('month')],
         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
         },
         opens: (isRtl ? 'left' : 'right')
         }, cb);         
         cb(start, end);         
         
         // Replace icons         
         $('#dashboardDate').each(function() {
         var obj = $(this).data('daterangepicker');
         var _updateCalendars = obj.updateCalendars;         
         obj.updateCalendars = function() {
         // Call original function
         _updateCalendars.call(obj)         
         // Replace icons
         obj.container.find('.prev > i').each(function() { this.className = 'ion ion-ios-arrow-back' });
         obj.container.find('.next > i').each(function() { this.className = 'ion ion-ios-arrow-forward' });
         obj.container.find('.daterangepicker_input > i').each(function() { this.className = 'ion ion-md-calendar' });
         obj.container.find('.calendar-time > i').each(function() { this.className = 'ion ion-md-time' });
         };
         });
         });
      </script>
      <!-- / Javascript -->
      @endif
      <script>
        // Hoverable DataTable	
        $('#data_Table').DataTable({
         	responsive: true,
         	language: {
         	  searchPlaceholder: 'Search...',
         	  sSearch: ''
         	},
         	"order": [[ 0, "desc" ]]
           });
      </script>

<script>
          $(document).ready(function() {
              $('.dropify').dropify();
          });
      </script>
