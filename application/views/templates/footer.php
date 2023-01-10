<footer id="page-footer" class="opacity-0">
	<div class="content py-20 font-size-xs clearfix">
		<!-- <div class="float-right">
			Crafted with <i class="fa fa-heart text-pulse"></i> by <a class="font-w600" href="https://1.envato.market/ydb" target="_blank">pixelcave</a>
		</div> -->
		<div class="float-left">
			<a class="font-w600" target="_blank">CBI</a> &copy; <span class="js-year-copy">2022</span>
		</div>
	</div>
</footer>
<!-- END Footer -->
</div>
<!-- END Page Container -->

<!--
            Codebase JS Core

            Vital libraries and plugins used in all pages. You can choose to not include this file if you would like
            to handle those dependencies through webpack. Please check out assets/_es6/main/bootstrap.js for more info.

            If you like, you could also include them separately directly from the assets/js/core folder in the following
            order. That can come in handy if you would like to include a few of them (eg jQuery) from a CDN.

            assets/js/core/jquery.min.js
            assets/js/core/bootstrap.bundle.min.js
            assets/js/core/simplebar.min.js
            assets/js/core/jquery-scrollLock.min.js
            assets/js/core/jquery.appear.min.js
            assets/js/core/jquery.countTo.min.js
            assets/js/core/js.cookie.min.js
        -->
<script src="assets/js/codebase.core.min.js"></script>

<!--
            Codebase JS

            Custom functionality including Blocks/Layout API as well as other vital and optional helpers
            webpack is putting everything together at assets/_es6/main/app.js
        -->
<script src="assets/js/codebase.app.min.js"></script>

<!-- Page JS Plugins -->
<!-- <script src="assets/js/plugins/chartjs/Chart.bundle.min.js"></script> -->

<!-- Page JS Code -->
<!-- <script src="assets/js/pages/be_pages_dashboard.min.js"></script> -->
<!-- Page JS Plugins -->
<script src="assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page JS Code -->
<script src="assets/js/pages/be_tables_datatables.min.js"></script>
<script src="assets/js/xlsx.full.min.js"></script>
<script src='assets/js/fullcalendar-6.0.2/dist/index.global.js'></script>

<script>
	var isAddDocuments = '<?php echo isset($is_add_documents) ? $is_add_documents : 'false'; ?>' === 'true';

	var mapOfSheet = {};
	var dataTableRows = [];
	let calendarEvents = [];
	let checksheets = [];
	<?php
	if (isset($schedules)) {
		//insert every item to calendarEvents
		foreach ($schedules as $schedule) {
			echo "calendarEvents.push({title: '{$schedule['title']}', start: '{$schedule['start']}'});";
		}
	}
	?>
	<?php
	if (isset($checksheet)) {
		//insert every item to checksheets(id, name)
		foreach ($checksheet as $checksheet) {
			echo "checksheets.push({id: '{$checksheet['id']}', name: '{$checksheet['name']}'});";
		}
	}
	?>
	var calendar;

	function refreshCalendar() {
		var calendarEl = document.getElementById('calendar');
		calendar = new FullCalendar.Calendar(calendarEl, {
			// plugins: ['interaction', 'dayGrid', 'timeGrid'],
			initialView: 'dayGridMonth',
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay'
			},
			events: calendarEvents.concat(dataTableRows.map((row) => {
				return {
					title: row.desc,
					start: row.date,
				}
			})),
		});

		calendar.render();
	}


	function refreshDataTable() {
		$("#DataTables_Table_0").DataTable().clear().draw();
		let usedRows = ["desc", "date", "id_checksheet", "priority", ];

		dataTableRows.forEach(function callback(row, index) {
			//usinf datatable add every row
			var cells = [(index + 1)];
			usedRows.forEach((key) => {
				if (key === "id_checksheet") {
					//create option for selected checksheet from checksheets
					var options = '<select class="form-control" id="id_checksheet-' + index + '" onchange="updateChecksheet(' + index + ')">';
					checksheets.forEach((checksheet) => {
						if (checksheet.id === row[key]) {
							options += "<option value='" + checksheet.id + "' selected>" + checksheet.name + "</option>";
						} else {
							options += "<option value='" + checksheet.id + "'>" + checksheet.name + "</option>";
						}
					});
					options += "</select>";
					cells.push(options);
				} else if (key === "priority") {
					if (row[key] === 'low') {
						//create option for active and processing status
						cells.push('<select class="form-control" id="status-' + index + '" onchange="updatePriority(' + index + ')"><option value="low" selected>Low</option><option value="medium">Medium</option><option value="high">High</option></select>');
					} else if (row[key] === 'medium') {
						//create option for active and processing status
						cells.push('<select class="form-control" id="status-' + index + '" onchange="updatePriority(' + index + ')"><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option></select>');
					} else {
						cells.push('<select class="form-control" id="status-' + index + '" onchange="updatePriority(' + index + ')"><option value="low">Low</option><option value="medium">Medium</option><option value="high" selected>High</option></select>');

						// cells.push("<span class='badge badge-danger'> Tidak Aktif </span>");
					}
				} else if (typeof(row[key]) === 'undefined') {
					cells.push("");
				} else {
					cells.push(row[key]);
				}
			});
			// add icon button to delete row using jquery $("table tr:eq(2)").remove();
			cells.push("<button class='btn btn-danger btn-sm' onClick='deleteThisRow(" + index + ")'><i class='fa fa-trash'></i></button>");

			$("#DataTables_Table_0").DataTable().row.add(cells).draw();
		});
	}

	function updatePriority(index) {
		dataTableRows[index].priority = $("#status-" + index).val();
		console.log(dataTableRows[index]);
	}

	function updateChecksheet(index) {
		dataTableRows[index].id_checksheet = $("#id_checksheet-" + index).val();
		console.log(dataTableRows[index]);
	}

	function onUploadFile() {
		var file = document.getElementById("file").files[0];
		var reader = new FileReader();
		reader.onload = function(e) {
			var data = e.target.result;
			var workbook = XLSX.read(data, {
				type: 'binary'
			});
			workbook.SheetNames.forEach(function(sheetName) {
				// Here is your object
				var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
				var json_object = JSON.stringify(XL_row_object);
				mapOfSheet[sheetName] = json_object;
			})
			// console.log(mapOfSheet);
			// console.log(JSON.parse(mapOfSheet["Sheet1"]));
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].nama_alat);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].pabrik_pembuat);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].kapasitas);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].lokasi);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].no_seri);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].no_perijinan);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].expired_date);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].status);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].keterangan);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].file);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].file_name);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].file_type);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].file_size);
			// console.log(JSON.parse(mapOfSheet["Sheet1"])[0].file_path);
			//
		}
	}

	function refreshDataTableAdd() {
		$("#DataTables_Table_0").DataTable().clear().draw();
		let usedRows = ["nama_alat", "pabrik_pembuat", "kapasitas", "lokasi", "no_seri", "no_perijinan", "expired_date", "status"];
		//create row with forms inside it and in column action add button to add row
		var formsCell = ["", "<input type='text' class='form-control' name='nama_alat' id='nama_alat' placeholder='Nama Alat'>",
			"<input type='text' class='form-control' name='pabrik_pembuat' id='pabrik_pembuat' placeholder='Pabrik Pembuat'>",
			"<input type='text' class='form-control' name='kapasitas' id='kapasitas' placeholder='Kapasitas'>",
			"<input type='text' class='form-control' name='lokasi' id='lokasi' placeholder='Lokasi'>",
			"<input type='text' class='form-control' name='no_seri' id='no_seri' placeholder='No Seri'>",
			"<input type='text' class='form-control' name='no_perijinan' id='no_perijinan' placeholder='No Perijinan'>",
			"<input type='date' class='form-control' data-date='' data-date-format='YYYY/MM/DD' name='expired_date' id='expired_date' placeholder='2022/11/20'>",
			" ",
			"<button class='btn btn-success btn-sm' onClick='addThisRow()'><i class='fa fa-plus'></i></button>"
		];
		$("#DataTables_Table_0").DataTable().row.add(formsCell).draw();

		dataTableRows.forEach(function callback(row, index) {
			//usinf datatable add every row
			var cells = [(index + 1)];
			usedRows.forEach((key) => {
				if (key === "id_checksheet") {
					//create option for selected checksheet from checksheets
					var options = "";
					checksheets.forEach((checksheet) => {
						if (checksheet.id_checksheet === row[key]) {
							options += "<option value='" + checksheet.id_checksheet + "' selected>" + checksheet.namaChecksheet + "</option>";
						} else {
							options += "<option value='" + checksheet.id_checksheet + "'>" + checksheet.namaChecksheet + "</option>";
						}
					});

				}
				if (key === "priority") {
					if (row[key] === 'low') {

						cells.push('<select class="form-control" id="status-' + index + '" onchange="updatePriority(' + index + ')"><option value="low" selected>Low</option><option value="medium">Medium</option><option value="high">High</option></select>');
					} else if (row[key] === 'medium') {
						cells.push('<select class="form-control" id="status-' + index + '" onchange="updatePriority(' + index + ')"><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option></select>');
					} else {
						cells.push('<select class="form-control" id="status-' + index + '" onchange="updatePriority(' + index + ')"><option value="low" >Low</option><option value="medium">Medium</option><option value="high" selected>High</option></select>');
					}
				} else if (typeof(row[key]) === 'undefined') {
					cells.push("");
				} else {
					cells.push(row[key]);
				}

			});
			// add icon button to delete row using jquery $("table tr:eq(2)").remove();
			cells.push("<button class='btn btn-danger btn-sm' onClick='deleteThisRow(" + index + ")'><i class='fa fa-trash'></i></button>");

			$("#DataTables_Table_0").DataTable().row.add(cells).draw();
		})
	}

	function addSchedule(desc, date, id_checksheet, priority) {
		// add new temporary schedule to dataTableRows
		dataTableRows.push({
			"desc": desc,
			"date": date,
			"id_checksheet": id_checksheet,
			"priority": priority
		});
		//refresh datatable
		refreshDataTable();
		refreshCalendar();
	}

	function addThisRow() {
		//get data from first cell form in datatable
		var nama_alat = $("#nama_alat").val();
		var pabrik_pembuat = $("#pabrik_pembuat").val();
		var kapasitas = $("#kapasitas").val();
		var lokasi = $("#lokasi").val();
		var no_seri = $("#no_seri").val();
		var no_perijinan = $("#no_perijinan").val();
		var expired_date = $("#expired_date").val();
		//if expired_date is yyyy-mm-dd then convert it to yyyy/mm/dd
		if (expired_date.includes("-")) {
			expired_date = expired_date.replace(/-/g, "/");
		}
		var date = new Date(expired_date);
		var status = 'active';
		let today = new Date();
		today.setHours(0, 0, 0, 0);
		if (date < today) {
			status = 'expired';
		} else {
			status = 'active';
		}

		//add data to array
		dataTableRows.push({
			nama_alat: nama_alat,
			pabrik_pembuat: pabrik_pembuat,
			kapasitas: kapasitas,
			lokasi: lokasi,
			no_seri: no_seri,
			no_perijinan: no_perijinan,
			expired_date: expired_date,
			status: status
		});
		//refresh datatable
		refreshDataTableAdd();

	}

	function deleteThisRow(index) {
		dataTableRows.splice(index, 1);
		if (isAddDocuments) {
			refreshDataTableAdd();
		} else {
			refreshDataTable();
		}
	}

	function setupFormScheduleTabs() {
		const formView = document.querySelector("#form-view")
		const importView = document.querySelector("#import-view")

		const options = {
			attributes: true
		}
		$(`#import-view`).hide();

		function callback(mutationList, observer) {
			mutationList.forEach(function(mutation) {
				if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
					// handle class change
					console.log(mutation.target.className)
					console.log(mutation.target.id)

					if (mutation.target.className.toString().includes("show") && mutation.target.id == "import-view") {

						//show calendar-view and hide table-view
						$(`#form-view`).hide();
						$(`#import-view`).show();

					} else {
						//show table-view and hide calendar-view
						$(`#form-view`).show();
						$(`#import-view`).hide();

					}
				}
			})
		}

		const observer = new MutationObserver(callback)
		observer.observe(importView, options)
		observer.observe(formView, options)
	}

	function setupPreviewScheduleTabs() {
		const tableView = document.querySelector("#table-view")
		const calendarView = document.querySelector("#calendar-view")

		const options = {
			attributes: true
		}
		$(`#calendar-view`).hide();

		function callback(mutationList, observer) {
			mutationList.forEach(function(mutation) {
				if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
					// handle class change
					console.log(mutation.target.className)
					console.log(mutation.target.id)

					if (mutation.target.className.toString().includes("show") && mutation.target.id == "calendar-view") {

						//show calendar-view and hide table-view
						$(`#calendar-view`).show();
						$(`#table-view`).hide();

					} else {
						//show table-view and hide calendar-view
						$(`#calendar-view`).hide();
						$(`#table-view`).show();

					}
				}
			})
		}

		const observer = new MutationObserver(callback)
		observer.observe(calendarView, options)
		observer.observe(tableView, options)
	}

	function serialDateToDate(serial) {
		var utc_days = Math.floor(serial - 25569);
		var utc_value = utc_days * 86400;
		var date_info = new Date(utc_value * 1000);

		var fractional_day = serial - Math.floor(serial) + 0.0000001;

		var total_seconds = Math.floor(86400 * fractional_day);

		var seconds = total_seconds % 60;

		total_seconds -= seconds;

		var hours = Math.floor(total_seconds / (60 * 60));
		var minutes = Math.floor(total_seconds / 60) % 60;

		var date = new Date(date_info.getFullYear(), date_info.getMonth(), date_info.getDate(), hours, minutes, seconds);
		return date;
	}
	$(document).ready(function() {
		//init datatable for add documents if isAddDocuments is true
		if (isAddDocuments == true) {
			refreshDataTableAdd();
		} else {
			// refreshDataTable();
		}
		refreshCalendar();
		// $('a[data-toggle="tabs"]').on('shown.bs.tab', function(e) {
		// 	var target = $(e.target).attr("href") // activated tab
		// 	console.log(target);
		// });
		// $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
		// 	var target = $(e.target).attr("href") // activated tab
		// 	console.log(target);
		// });
		setupPreviewScheduleTabs();
		setupFormScheduleTabs();

		$('[data-toggle="tooltip"]').tooltip();
		$("#form-add-schedule").submit(function(e) {
			e.preventDefault();
			var desc = $("#description").val();
			var date = $("#date").val();
			var id_checksheet = $("#id_checksheet").val();
			var priority = $("#priority").val();
			//validate every field
			//check if desc is empty

			if (desc == "") {
				$("#description").addClass("is-invalid is-invalid-custom");
				return;
			} else {
				$("#description").removeClass("is-invalid is-invalid-custom");
			}
			if (date == "") {
				$("#date").addClass("is-invalid is-invalid-custom");
				return;
			} else {
				$("#date").removeClass("is-invalid is-invalid-custom");
			}
			if (id_checksheet == "") {
				$("#id_checksheet").addClass("is-invalid is-invalid-custom");
				return;
			} else {
				$("#id_checksheet").removeClass("is-invalid is-invalid-custom");
			}
			if (priority == "") {
				$("#priority").addClass("is-invalid is-invalid-custom");
				return;
			} else {
				$("#priority").removeClass("is-invalid is-invalid-custom");
			}
			//add schedule if all field is valid

			addSchedule(desc, date, id_checksheet, priority);

		});
		// listen #table-view on class property changed if its .show then show else hide
		$("#table-view").on('classChanged', function() {
			if ($(this).val() == "show") {
				$("#table-view").show();
			} else {
				$("#table-view").hide();
			}
		});

		// listen #calendar-view on changed if its .show then show else hide
		$("#calendar-view").on('classChanged', function() {
			if ($(this).val() == "show") {
				$("#calendar-view").show();
			} else {
				$("#calendar-view").hide();
			}
		});



		$('#modal-block-upload').on('show.bs.modal', function(event) {
			var button = $(event.relatedTarget) // Button that triggered the modal
			var id = button.data('id') // Extract info from data-* attributes
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			var modal = $(this)
			// modal.find('.modal-title').text('New message to ' + recipient)
			$('#form-modal-upload').attr('action', '<?php echo base_url('upload/') ?>' + id);
		})

		$('#file').change(function(e) {
			var files = e.target.files,
				f = files[0];
			var reader = new FileReader();
			reader.onload = function(e) {
				var data = e.target.result;
				var workbook = XLSX.read(data, {
					type: 'binary'
				});
				workbook.SheetNames.forEach(function(sheetName) {
					console.log(sheetName);
					// console.log(sheetName.toLowerCase().includes('all'));
					// if (sheetName.toLowerCase().includes('all')) {
					// Here is your object

					var XL_row_object = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName]);
					var json_object = JSON.stringify(XL_row_object);
					console.log(XL_row_object);
					var rows = [];
					XL_row_object.forEach((obj) => {
						var newobj = {}
						var key, keys = Object.keys(obj);
						var n = keys.length;
						while (n--) {
							console.log(keys[n] + ':' + obj[keys[n]]);
							key = keys[n];
							newobj[key.toLowerCase().trim().replace(" ", "_")] = obj[key].toString();

						}
						// check if is expired date is nan or not
						if (isNaN(newobj.date)) {
							newobj.date = newobj.date;
						} else {
							var serial = newobj['date'];
							// newobj['expired_date'] = newobj['__empty_1'] + "/" + newobj['__empty'] + "/" + newobj['expired'];
							var date = serialDateToDate(serial);
							// assign date as yyyy/mm/dd string to newobj expired_date
							if ((date.getMonth() + 1) < 10) {
								newobj['date'] = date.getFullYear() + "-0" + (date.getMonth() + 1) + "-" + date.getDate();
							} else {
								newobj['date'] = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
							}

						}
						// string of yyyy/mm/dd to date
						// var expired_date = newobj['expired_date'];
						// var date = new Date(expired_date);

						// var dateFromSerial = serialDateToDate(newobj['date']);
						// var formatedDate = dateFromSerial.getFullYear() + "-" + (dateFromSerial.getMonth() + 1) + "-" + dateFromSerial.getDate();


						// var date = new Date(newobj['expired_date']);
						// newobj['date'] = date;
						// newobj['status'] = 'aktif';
						// let today = new Date();
						// newobj['date'] = formatedDate;
						// today.setHours(0, 0, 0, 0);
						// if (date < today) {
						// 	newobj['status'] = 'expired';
						// } else {
						// 	newobj['status'] = 'active';
						// }
						rows.push(newobj);
					})

					mapOfSheet[sheetName] = rows;
					// console.log(json_object);
					console.table(rows);
					console.table(mapOfSheet);

				})
				var html_select_content = "<label for='option-sheet'>select sheet</label>"
				html_select_content += "<select class='form-control' id='option-sheet'>";
				Object.keys(mapOfSheet).forEach((key) => {
					html_select_content += "<option value='" + key + "'>" + key + "</option>";
				})
				html_select_content += "</select>";
				$("#select-sheet").append(html_select_content);
			};
			reader.onerror = function(ex) {
				console.log(ex);
			};
			reader.readAsBinaryString(f);
		});


		// load excel file
		$('#load').click(function() {
			var sheet = $('#option-sheet').val();
			var usedRows = ["description", "date", "priority", ];

			//create newRows from mapOfSheet[sheet] and usedRows
			var newRows = mapOfSheet[sheet].map((row) => {
				var newRow = {};
				usedRows.forEach((key) => {
					if (key == "description") {
						newRow["desc"] = row[key];
					} else {
						newRow[key] = row[key];
					}
				})
				//set id_checksheet to first id from checksheets
				newRow["id_checksheet"] = checksheets[0].id;
				return newRow;
			});
			dataTableRows = dataTableRows.concat(newRows);
			//load rows to table
			refreshDataTable();
			refreshCalendar();
		})
		$('#save-all').click(function() {
			console.log(dataTableRows);
			var jsonObjects = [];
			let usedRows = ["desc", "date", "id_checksheet", "priority", ];
			dataTableRows.forEach(function callback(row, index) {
				var jsonObject = {};
				usedRows.forEach((key) => {
					if (typeof(row[key]) === 'undefined') {
						if (key == "desc") {
							jsonObject["description"] = "";
						} else {
							jsonObject[key] = "";
						}
					} else {
						if (key == "desc") {
							jsonObject["description"] = row[key];
						} else {
							jsonObject[key] = row[key];
						}
					}
				});
				jsonObjects.push(jsonObject);
			});
			console.log(JSON.stringify(jsonObjects));
			// var usedRows = ["nama_alat", "pabrik_pembuat", "kapasitas", "lokasi", "no_seri", "no_perijinan", "expired_date"];
			//then post raw json data to  document/imports
			$.ajax({
				url: 'schedule/add',
				type: 'POST',
				data: JSON.stringify(jsonObjects),
				success: function(data) {
					console.log(data);
					// alert('success');
					//then locate to root path /
					window.location.href = '<?php echo base_url(); ?>';
				}
			});

		});
		// on import-table click then post json from datatable to server
		$('#import-table').click(function() {
			console.log(dataTableRows);
			var jsonObjects = [];
			var usedRows = ["nama_alat", "pabrik_pembuat", "kapasitas", "lokasi", "no_seri", "no_perijinan", "expired_date", "status"];
			dataTableRows.forEach(function callback(row, index) {
				var jsonObject = {};
				usedRows.forEach((key) => {
					if (typeof(row[key]) === 'undefined') {
						jsonObject[key] = "";
					} else {
						jsonObject[key] = row[key];
					}
				});
				jsonObjects.push(jsonObject);
			});
			console.log(JSON.stringify(jsonObjects));
			// var usedRows = ["nama_alat", "pabrik_pembuat", "kapasitas", "lokasi", "no_seri", "no_perijinan", "expired_date"];
			//then post raw json data to  document/imports
			$.ajax({
				url: 'schedule/imports',
				type: 'POST',
				data: JSON.stringify(jsonObjects),
				success: function(data) {
					console.log(data);
					// alert('success');
					//then locate to root path /
					window.location.href = '<?php echo base_url(); ?>';
				}
			});

		});
		// when id="exports-table" clicked then get json from <?php echo base_url('document/exports'); ?> and export to excel using XLSX utils
		$('#exports-table').click(function() {
			$.ajax({
				url: 'schedule/exports',
				type: 'GET',
				//specify dataType to json
				dataType: 'json',
				success: function(data) {
					console.log(data);
					//then locate to root path /
					var wb = XLSX.utils.book_new();
					var ws = XLSX.utils.json_to_sheet(data);
					XLSX.utils.book_append_sheet(wb, ws, "reports 1");
					//write with name document-export-<current date>.xlsx
					XLSX.writeFile(wb, "document-export-" + new Date().toISOString().slice(0, 10) + ".xlsx");
				}
			});
		});

	});
</script>
<!-- <script>
	document.addEventListener('DOMContentLoaded', function() {
		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth'
		});
		calendar.render();
	});
</script> -->

</body>

</html>
