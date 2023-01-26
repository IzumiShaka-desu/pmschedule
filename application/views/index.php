 <!--create styling sheet assets/css/radiocustom.css  -->
 <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/radiocustom.css" />


 <!-- Main Container -->
 <main id="main-container" style="min-height: 2130px;">
 	<div class="bg-image bg-image-bottom" style="background-image: url('assets/media/photos/photo34@2x.jpg');">
 		<div class="bg-primary-dark-op">
 			<div class="content content-top text-center overflow-hidden">
 				<div class="pt-50 pb-20">
 					<h1 class="font-w700 text-white mb-10 invisible" data-toggle="appear" data-class="animated fadeInUp">Dashboard</h1>
 					<h2 class="h4 font-w400 text-white-op invisible" data-toggle="appear" data-class="animated fadeInUp">Welcome to your custom panel!</h2>
 				</div>
 			</div>
 		</div>
 	</div>
 	<div class="content">
 		<h2 class="content-heading">Schedule Management</h2>


 		<!-- Dynamic Table Full -->
 		<div class="block">
 			<div class="block-header block-header-default">
 				<h3 class="block-title">Schedules<small></small></h3>
 			</div>
 			<div class="block-content block-content-full">
 				<div class="col-12 text-right" style="margin-bottom: 18px;">
 					<!-- create selectable tab item there is calendar icon and table icon, then listen tab item change, when tab item changed console log -->
 					<ul class="nav nav-pills nav-pills-alt" data-toggle="tabs" role="tablist">
 						<li class="nav-item">
 							<a class="nav-link active" href="#annual-view">Year</a>
 						</li>
 						<li class="nav-item">
 							<a class="nav-link" href="#monthly-view">Month</a>
 						</li>
 					</ul>
 				</div>
 				<div class="col-12">
 					<br>
 				</div>
 				<div class="col-12">
 					<!-- create element to enable/disable editable calendar status -->
 					<div class="form-group row">
 						<label class="col-12" for="example-text-input">Enable Editable Calendar</label>
 						<div class="col-12">
 							<label class="css-control css-control-primary css-switch">
 								<input type="checkbox" class="css-control-input" id="enable-editable-calendar" name="enable-editable-calendar" checked>
 								<span class="css-control-indicator"></span>
 							</label>
 						</div>

 					</div>
 				</div>
 				<!-- create tombol import to database on right side -->
 				<!-- <div class="col-12 text-right">
 					<button type="button" class="btn btn-alt-primary" id="exports-table">Exports All</button>
 					 create whitespace -->

 				<!-- </div> -->
 				<div id="monthly-view" class="col-12">
 					<br>
 					<div id='calendar'></div>
 				</div>
 				<div id="annual-view">
 					<br>
 					<div id='yearlyCalendar'></div>
 				</div>
 				<!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->

 			</div>
 			<!-- modal for upload file by index of documents  -->
 			<!-- using post url 'upload/:id' -->
 			<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
 				<div class="modal-dialog modal-dialog-top modal-lg" role="document">
 					<!-- Modal content-->
 					<div class="modal-content" id="modalDetail-content">
 					</div>
 				</div>
 			</div>
 			<div class="modal" id="modal-block-upload" tabindex="-1" role="dialog" aria-labelledby="modal-block-upload" aria-hidden="true">
 				<div class="modal-dialog modal-dialog-top modal-lg" role="document">
 					<div class="modal-content">
 						<form action="<?php echo base_url('upload'); ?>" id="form-modal-upload" method="post" enctype="multipart/form-data">
 							<div class="block block-themed block-transparent mb-0">
 								<div class="block-header bg-primary-dark">
 									<h3 class="block-title">Upload File</h3>
 									<div class="block-options">
 										<button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
 											<i class="fa fa-fw fa-times"></i>
 										</button>
 									</div>
 								</div>
 								<div class="block-content">
 									<divxw class="form-group">
 										<label for="example-file-input">File</label>
 										<input type="file" id="example-file-input" name="file">
 								</div>
 							</div>
 							<div class="block-content block-content-full text-right border-top">
 								<button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
 								<button type="submit" class="btn btn-sm btn-primary" id="btn-modal-upload-document"><i class="fa fa-check mr-1"></i>Save</button>
 							</div>
 						</form>
 					</div>
 				</div>
 				<!-- END Page Content -->


 </main>
 -->
 <script src="assets/js/codebase.core.min.js"></script>

 <!--
            Codebase JS

            Custom functionality including Blocks/Layout API as well as other vital and optional helpers
            webpack is putting everything together at assets/_es6/main/app.js
        -->
 <!-- <script src="assets/js/codebase.app.min.js"></script>

    Page JS Plugins
     Page JS Code -->
 <!-- <script src="assets/js/pages/be_tables_datatables.min.js"></script>
    <script src="assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/plugins/datatables/jquery.dataTables.min.js"></script> -->

 <!-- END Main Container -->
