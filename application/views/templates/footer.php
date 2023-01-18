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
<!-- import assets/js/fcyear.js -->
<script src="assets/js/fcyear.js"></script>


<script>
	var isAddDocuments = '<?php echo isset($is_add_documents) ? $is_add_documents : 'false'; ?>' === 'true';

	var mapOfSheet = {};
	var dataTableRows = [];
	let calendarEvents = [];
	let checksheets = [];
	let rawSchedule = [];
	<?php
	if (isset($schedules)) {
		//insert every item to calendarEvents
		foreach ($schedules as $schedule) {
			$status = "";
			if (isset($schedule['status'])) {
				$status = $schedule['status'];
			}
			echo "calendarEvents.push({title: '{$schedule['title']}',id:{$schedule['id']},status:'{$status}', start: '{$schedule['start']}'});";
		}
	}
	echo "console.log(" . json_encode($rawSchedules) . ");";
	if (isset($rawSchedules)) {
		//insert every item to calendarEvents
		foreach ($rawSchedules as $schedule) {
			//schedule properties: id_schedule,id_checksheet,status,date,priority,description
			if (!empty($schedule['id_response'])) {
				echo "var item = {id: '{$schedule['id_schedule']}', id_checksheet: '{$schedule['id_checksheet']}',id_response: '{$schedule['id_response']}', status: '{$schedule['status']}', date: '{$schedule['date']}', priority: '{$schedule['priority']}', description: '{$schedule['description']}'};";
			} else {
				echo "var item = {id: '{$schedule['id_schedule']}', id_checksheet: '{$schedule['id_checksheet']}', status: '{$schedule['status']}', date: '{$schedule['date']}', priority: '{$schedule['priority']}', description: '{$schedule['description']}'};";
			}
			echo "rawSchedule.push(item);";
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
	var yearlyCalendar;

	function showDialog(idSchedule) {
		//clear and append detail schedule information on modal
		// that will contains description, date, and button to open checksheet url in newpage
		$("#modalDetail-content").empty();
		var schedule = rawSchedule.find(x => x.id == idSchedule);
		var dialogContent = "";
		// dialogContent += "<div class='modal-header'>";
		dialogContent += '<div class="block block-themed block-transparent mb-0">';
		dialogContent += '<div class="block-header bg-primary-dark">';
		dialogContent += '<h3 class="block-title">Detail Schedule</h3>';
		dialogContent += '<div class="block-options">';
		dialogContent += '<button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">';
		dialogContent += '<i class="fa fa-fw fa-times"></i>';
		dialogContent += '</button>';
		dialogContent += '</div>';
		dialogContent += '</div>';
		dialogContent += "</div>";
		// dialogContent += "</div>";
		dialogContent += "<div class='modal-body'>";

		// show description as Paragraph
		dialogContent += "<div class='row'>";
		dialogContent += "<div class='col-md-12'>";
		dialogContent += "<p class='font-size-sm text-muted'>Description</p>";
		dialogContent += "<p class='font-size-sm text-muted'>" + schedule.description + "</p>";
		//show date as Paragraph
		dialogContent += "<p class='font-size-sm text-muted'>Date</p>";
		dialogContent += "<p class='font-size-sm text-muted'>" + schedule.date + "</p>";
		//show priority as flag if high is red, if medium is yellow, if low is green
		dialogContent += "<p class='font-size-sm text-muted'>Priority</p>";
		dialogContent += "<p class='font-size-sm text-muted'>";
		if (schedule.priority == "high") {
			dialogContent += "<span class='badge badge-danger'>High</span>";
		} else if (schedule.priority == "medium") {
			dialogContent += "<span class='badge badge-warning'>Medium</span>";
		} else if (schedule.priority == "low") {
			dialogContent += "<span class='badge badge-success'>Low</span>";
		}
		dialogContent += "</p>";

		//show status as flag if contains missing is red, if contains scheduled is blue light,if contains working yellow, if done early is green, if done is blue, if done late is red light 
		dialogContent += "<p class='font-size-sm text-muted'>Status</p>";
		dialogContent += "<p class='font-size-sm text-muted'>";
		if (schedule.status == "missing") {
			dialogContent += "<span class='badge badge-danger'>Missing</span>";
		} else if (schedule.status == "scheduled") {
			dialogContent += "<span class='badge badge-info'>Scheduled</span>";
		} else if (schedule.status.includes("working")) {
			dialogContent += "<span class='badge badge-warning'>Working</span>";
		} else if (schedule.status == "done early") {
			dialogContent += "<span class='badge badge-success'>Done Early</span>";
		} else if (schedule.status == "done") {
			dialogContent += "<span class='badge badge-primary'>Done</span>";
		} else if (schedule.status == "done late") {
			dialogContent += "<span class='badge badge-danger'>Done Late</span>";
		}
		dialogContent += "</p>";

		//show button to open checksheet in new page
		dialogContent += "</div>";
		dialogContent += "</div>";
		dialogContent += "</div>";

		dialogContent += "<div class='modal-footer'>";
		dialogContent += "<div class='row'>";
		dialogContent += "<div class='col-md-12'>";
		dialogContent += '<button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>';
		var base64url;
		var buttonTitle = "Open Checksheet";
		// if schedule status is contains working, then button title is "Open Checksheet (Draft)"
		// is schedule status is done, then button title is "Open Checksheet (Readonly)"
		if (schedule.status.includes("working")) {
			buttonTitle = "Open Checksheet (Draft)";
		} else if (schedule.status == "done") {
			buttonTitle = "Open Checksheet (Readonly)";
		}
		// if schedule have id_response the button will redirect to response page with id_response as parameter
		// if schedule dont have id_response the button will redirect to checksheet page with id_checksheet as parameter
		if (schedule.id_response != undefined) {
			const rawPath = "response/viewResponseData?id=" + schedule.id_response + "&note=from-schedule-" + idSchedule;
			const base64Path = btoa(rawPath);

			const rawUrl = "http://portal.incoe.astra.co.id/checksheet/login/redirectAnonymHandler?a=<?php echo $_SESSION['username']; ?>&b=<?php echo $_SESSION['password']; ?>&c=" + base64Path;
			base64url = btoa(rawUrl);
		} else {
			const rawPath = "dashboard/insertData?id=" + schedule.id_checksheet + "&note=from-schedule-" + idSchedule;
			const base64Path = btoa(rawPath);
			const rawUrl = "http://portal.incoe.astra.co.id/checksheet/login/redirectAnonymHandler?a=<?php echo $_SESSION['username']; ?>&b=<?php echo $_SESSION['password']; ?>&c=" + base64Path;
			base64url = btoa(rawUrl);
		}
		dialogContent += "<a class='btn btn-sm btn-primary' href='<?= base_url('schedule/redirect') ?>/" + base64url + "' target='_blank'>" + buttonTitle + "</a>";
		dialogContent += "</div>";
		dialogContent += "</div>";
		dialogContent += "</div>";
		$("#modalDetail-content").append(dialogContent);
		//show modal
		$('#modalDetail').modal('show');
	}

	//function to show modal multi detail schedule
	function showModalMultiDetail(detailEvents) {
		//clear modal content
		$("#modalDetail-content").empty();
		//create modal content
		var dialogContent = "";
		dialogContent += "<div class='modal-header'>";
		dialogContent += "<h5 class='modal-title'>Detail Schedule</h5>";
		dialogContent += "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
		dialogContent += "<span aria-hidden='true'>&times;</span>";
		dialogContent += "</button>";
		dialogContent += "</div>";
		dialogContent += "<div class='modal-body'>";
		dialogContent += "<div class='row'>";
		dialogContent += "<div class='col-md-12'>";
		dialogContent += "<table class='table table-sm table-bordered'>";
		dialogContent += "<thead>";
		dialogContent += "<tr>";
		dialogContent += "<th>Schedule Description</th>";
		dialogContent += "<th>Date</th>";
		dialogContent += "<th>Priority</th>";
		dialogContent += "<th>Status</th>";
		dialogContent += "<th>Action</th>";
		dialogContent += "</tr>";
		dialogContent += "</thead>";
		dialogContent += "<tbody>";
		//looping detail events
		detailEvents.forEach((detailEvent) => {
			dialogContent += "<tr>";
			dialogContent += "<td>" + detailEvent.description + "</td>";
			dialogContent += "<td>" + detailEvent.date + "</td>";
			dialogContent += "<td>";
			if (detailEvent.priority == "high") {
				dialogContent += "<span class='badge badge-danger'>High</span>";
			} else if (detailEvent.priority == "medium") {
				dialogContent += "<span class='badge badge-warning'>Medium</span>";
			} else if (detailEvent.priority == "low") {
				dialogContent += "<span class='badge badge-success'>Low</span>";
			}
			dialogContent += "</td>";
			dialogContent += "<td>";
			if (detailEvent.status == "missing") {
				dialogContent += "<span class='badge badge-danger'>Missing</span>";
			} else if (detailEvent.status == "scheduled") {
				dialogContent += "<span class='badge badge-info'>Scheduled</span>";
			} else if (detailEvent.status.includes("working")) {
				dialogContent += "<span class='badge badge-warning'>Working</span>";
			} else if (detailEvent.status == "done") {
				dialogContent += "<span class='badge badge-success'>Done</span>";
			}
			dialogContent += "</td>";
			dialogContent += "<td>";
			var buttonTitle = "Open Checksheet";
			// if schedule status is contains working, then button title is "Open Checksheet (Draft)"
			// is schedule status is done, then button title is "Open Checksheet (Readonly)"
			if (detailEvent.status.includes("working")) {
				buttonTitle = "Open Checksheet (Draft)";
			} else if (detailEvent.status == "done") {
				buttonTitle = "Open Checksheet (Readonly)";
			}
			//if schedule have id_response the button will redirect to response page with id_response as parameter
			//if schedule dont have id_response the button will redirect to checksheet page with id_checksheet as parameter
			if (detailEvent.id_response != undefined) {
				const rawPath = "response/viewResponseData?id=" + detailEvent.id_response + "&note=from-schedule-" + detailEvent.id;
				const base64Path = btoa(rawPath);
				const rawUrl = "http://portal.incoe.astra.co.id/checksheet/login/redirectAnonymHandler?a=<?php echo $_SESSION['username']; ?>&b=<?php echo $_SESSION['password']; ?>&c=" + base64Path;
				base64url = btoa(rawUrl);
			} else {
				const rawPath = "dashboard/insertData?id=" + detailEvent.id_checksheet + "&note=from-schedule-" + detailEvent.id;
				const base64Path = btoa(rawPath);
				const rawUrl = "http://portal.incoe.astra.co.id/checksheet/login/redirectAnonymHandler?a=<?php echo $_SESSION['username']; ?>&b=<?php echo $_SESSION['password']; ?>&c=" + base64Path;
				base64url = btoa(rawUrl);
			}
			dialogContent += "<a class='btn btn-sm btn-primary' href='<?= base_url('schedule/redirect') ?>/" + base64url + "' target='_blank'>" + buttonTitle + "</a>";
			dialogContent += "</td>";
			dialogContent += "</tr>";
		});
		dialogContent += "</tbody>";
		dialogContent += "</table>";
		dialogContent += "</div>";
		dialogContent += "</div>";
		dialogContent += "</div>";
		dialogContent += "<div class='modal-footer'>";
		dialogContent += "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
		dialogContent += "</div>";

		$("#modalDetail-content").append(dialogContent);
		$("#modalDetail").modal("show");
	}


	function refreshCalendar() {
		var calendarEl = document.getElementById('calendar');

		calendar = new FullCalendar.Calendar(calendarEl, {
			// plugins: ['interaction', 'dayGrid', 'timeGrid'],
			initialView: 'dayGridMonth',
			headerToolbar: {
				left: 'today prevYear,prev,next,nextYear',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
			},
			eventClick: function(info) {
				console.log(info)
				console.log(info.event)
				console.log(info.event._def.publicId)
				showDialog(info.event._def.publicId);

			},
			events: calendarEvents.concat(dataTableRows.map((row) => {

				return {
					title: row.desc,
					start: row.date,
				}
			})).map((row) => {

				var backgroundColor;
				// if all events is done or done early, set background color to green
				// else if all events is any missing status, set background color to red
				// else if all events is any done late status, or draft, set background color to yellow
				// else set background color to blue
				if (row.status == undefined) {
					backgroundColor = '#337ab7';
				} else if (row.status == 'done' || row.status == 'done early') {
					backgroundColor = '#5cb85c';
				} else if (row.status.includes('missing')) {
					backgroundColor = '#d9534f';
				} else if (row.status == 'done late' || row.status == 'draft') {
					backgroundColor = '#f0ad4e';
				} else {
					backgroundColor = '#337ab7';
				}
				return {
					...row,
					backgroundColor: backgroundColor,
				}
			}),
		});

		calendar.render();
	}

	function refreshYearlyCalendar() {
		try {
			var calendarEl = $('#yearlyCalendar');

			yearlyCalendar = new FcYear(calendarEl, (info) => {
					console.log(info)
					console.log(info.event);
					console.log(JSON.stringify(info.event.extendedProps.events));
					console.log(info.event.start);
					let splitDate = info.event.start.toISOString().split("T")[0].split("-");
					//splitDate[2] to integer and add 1
					splitDate[2] = parseInt(splitDate[2]) + 1;
					let dateString = splitDate[0] + "-" + splitDate[1] + "-" + splitDate[2];
					console.log(dateString);
					//get detail event from raw data then remove undefined value
					let detailEvents = info.event.extendedProps.events.map((event) => {
						//get detail event from raw data
						let detailEvent = rawSchedule.find((row) => {
							return row.id == event.id;
						});
						return detailEvent;
					}).filter((event) => {
						return event != undefined;
					})


					console.log(detailEvents);
					showModalMultiDetail(detailEvents);
					// click <a  href="#monthly-view">
					// $("a[href='#monthly-view']").click()
					// sleep(500);
					// let delayInMilliseconds = 500; //1 second
					// setTimeout(function() {
					// calendar.goToDate(dateString);
					// }, delayInMilliseconds);

				},
				rawSchedule.map((row) => {
					return {
						...row,
						title: row.description,
						start: row.date,

					}
				}),
			);

			yearlyCalendar.renderContent();
		} catch (e) {
			console.log(e);
		}
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
		try {
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
		} catch (e) {
			console.log(e)
		}
	}

	function setupPreviewScheduleTabs() {
		try {
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
		} catch (e) {
			console.log(e)
		}
	}

	function setupCalendarTabs() {
		try {
			const annualView = document.querySelector("#annual-view")
			const monthlyVIew = document.querySelector("#monthly-view")

			const options = {
				attributes: true
			}
			$(`#monthly-view`).hide();

			function callback(mutationList, observer) {
				mutationList.forEach(function(mutation) {
					if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
						// handle class change
						console.log(mutation.target.className)
						console.log(mutation.target.id)

						if (mutation.target.className.toString().includes("show") && mutation.target.id == "monthly-view") {

							//show calendar-view and hide table-view
							$(`#annual-view`).hide();
							$(`#monthly-view`).show();

						} else {
							//show table-view and hide calendar-view
							$(`#annual-view`).show();
							$(`#monthly-view`).hide();

						}
					}
				})
			}

			const observer = new MutationObserver(callback)
			observer.observe(annualView, options)
			observer.observe(monthlyVIew, options)
		} catch (e) {
			console.log(e)
		}
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
		//init datatable for add documents if isAddDocuments is true
		// if (isAddDocuments == true) {
		// 	refreshDataTableAdd();
		// } else {
		// 	// refreshDataTable();
		// }
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
		setupCalendarTabs();
		refreshYearlyCalendar();

		$('[data-toggle="tooltip"]').tooltip();

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



		// $('#modal-block-upload').on('show.bs.modal', function(event) {
		// 	var button = $(event.relatedTarget) // Button that triggered the modal
		// 	var id = button.data('id') // Extract info from data-* attributes
		// 	// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		// 	// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		// 	var modal = $(this)
		// 	// modal.find('.modal-title').text('New message to ' + recipient)
		// 	$('#form-modal-upload').attr('action', '<?php echo base_url('upload/') ?>' + id);
		// })

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
				$("#select-sheet").empty();
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