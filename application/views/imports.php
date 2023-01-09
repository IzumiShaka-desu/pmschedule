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
				<!-- create input file and button with label "load" to triggering function to load excel using xlsx library then convert that to json -->
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
					<!-- create tombol import to database on right side -->
					<div class="col-12 text-right">
						<button type="button" class="btn btn-alt-primary" id="import-table">Import</button>
						<!-- create whitespace -->

					</div>
					<div class="col-12">
						<br>
					</div>
					<!-- create table to show data from excel -->
					<table class="table table-bordered table-striped table-vcenter js-dataTable-full ">
						<thead>
							<tr>
								<th class="text-center"></th>
								<th class="d-none d-sm-table-cell">Nama Alat</th>
								<th class="d-none d-sm-table-cell">Pabrik Pembuat</th>
								<th class="d-none d-sm-table-cell" style="width: 15%;">Kapasitas</th>
								<th class="text-center" style="width: 15%;">Lokasi</th>
								<th class="text-center" style="width: 15%;">No Seri</th>
								<th class="text-center" style="width: 15%;">No Perizinan</th>
								<th class="text-center" style="width: 15%;">Expired Date</th>
								<th class="text-center" style="width: 15%;">Status</th>
								<th class="text-center" style="width: 15%;">Action</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>

</main>
<!-- <script>
	$(document).ready(function() {
		// load excel file
		$('#load').click(function() {
			// get file
			var file = $('#file')[0].files[0];
			// create reader
			var reader = new FileReader();
			// read file
			reader.readAsBinaryString(file);
			// on load
			reader.onload = function(e) {
				// get data
				var data = e.target.result;
				// create workbook
				var workbook = XLSX.read(data, {
					type: "binary"
				});
				// get first sheet
				var first_sheet_name = workbook.SheetNames[0];
				// get worksheet
				var worksheet = workbook.Sheets[first_sheet_name];
				// convert to json
				var json = XLSX.utils.sheet_to_json(worksheet, {
					raw: true
				});
				// print json
				console.log(json);
			}
		});
	});
	$('#input-excel').change(function(e) {
		var reader = new FileReader();
		reader.readAsArrayBuffer(e.target.files[0]);

		reader.onload = function(e) {
			var data = new Uint8Array(reader.result);
			var wb = XLSX.read(data, {
				type: 'array'
			});
			var sheet_name_list = wb.SheetNames;

			var dataj = XLSX.utils.sheet_to_json(wb.Sheets[sheet_name_list[0]], {
				raw: true,
				defval: null
			})
			table = $('table.test').DataTable();

			dataj.map(function(r) {
				table.row.add(JSON.stringify(r)).draw().node();

			});
		}
	});
</script> -->
