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
		<h2 class="content-heading">Documents Management</h2>
		<div class="block">
			<div class="block-header block-header-default">
				<h3 class="block-title">Documents<small></small></h3>
			</div>
			<div class="block-content block-content-full">

				<div class="col-12">
					<br>
				</div>

				<div class="col-12 text-left" style="margin-bottom: 18px;">
					<!-- create selectable tab item there is calendar icon and table icon, then listen tab item change, when tab item changed console log -->
					<ul class="nav nav-pills nav-pills-alt" data-toggle="tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" href="#form-view">Add</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#import-view">Import</a>
						</li>
					</ul>
				</div>
				<!-- create form for add schedule with property : desciption,date,priority(low,medium,high) and on submit handle with javascipt without reload-->
				<div id="form-view">
					<form id="form-add-schedule" method="post" enctype="multipart/form-data">
						<div class="form-group row">
							<label class="col-12" for="example-text-input">Description</label>
							<div class="col-12">
								<input type="text" class="form-control" id="description" name="description" placeholder="Description">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-12" for="example-text-input">Date</label>
							<div class="col-12">
								<input type="date" class="form-control" id="date" name="date" placeholder="Date">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-12" for="example-text-input">Priority</label>
							<div class="col-12">
								<select class="form-control" id="priority" name="priority">
									<option value="low">Low</option>
									<option value="medium">Medium</option>
									<option value="high">High</option>
								</select>
							</div>
						</div>
						<!-- create form item for checksheet using select option value id checksheet and text is checksheet name -->
						<div class="form-group row">
							<label class="col-12" for="example-text-input">Checksheet</label>
							<div class="col-12">
								<select class="form-control" id="id_checksheet" name="checksheet">
									<?php
									//array dummy
									foreach ($checksheet as $key => $value) { ?>
										<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-12">
								<button type="submit" class="btn btn-alt-primary">Add</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div id="import-view" class="col-12">
				<div class="form-group row">
					<div class="col-12">
						<label for="file">File</label>
						<div class="form-material floating">
							<input type="file" class="form-control" id="file" name="file">
						</div>
					</div>

					<div class="col-12 select-sheet" id="select-sheet">

					</div>

					<div class="col-12">
						<button type="button" class="btn btn-alt-primary" id="load">Load</button>
					</div>
					<!-- create whitespace -->
					<div class="col-12">
						<br>
					</div>

				</div>


			</div>
			<div class="col-12 text-right" style="margin-bottom: 18px;">
				<button type="button" class="btn btn-alt-primary" id="save-all">Save All</button>
				<!-- create whitespace -->
				<br />
			</div>
			<div class="col-12 text-left" style="margin-bottom: 18px;">
				<!-- create selectable tab item there is calendar icon and table icon, then listen tab item change, when tab item changed console log -->
				<ul class="nav nav-pills nav-pills-alt" data-toggle="tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" href="#table-view">Table</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#calendar-view">Calendar</a>
					</li>
				</ul>
			</div>

			<!-- create table to show data from excel -->
			<div id="table-view" class="col-12">
				<table class="table table-bordered table-striped table-vcenter js-dataTable-full ">
					<thead>
						<tr>
							<th class="text-center"></th>
							<th class="d-none d-sm-table-cell">Description</th>
							<th class="d-none d-sm-table-cell">Date</th>
							<th class="d-none d-sm-table-cell" style="width: 15%;">Checksheet</th>
							<th class="text-center" style="width: 15%;">Priority</th>
							<th class="text-center" style="width: 15%;">Action</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<div id="calendar-view" class="col-12">
				<br>
				<div id='calendar'></div>
			</div>
		</div>
	</div>
	</div>

</main>
