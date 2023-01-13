class FcYear {

	constructor(element, onDayClicked, events) {
		this.element = element;
		//get current year
		this.toolbar = "";
		this.body = "";
		let newEvents = [];
		this.calendars = [];
		//iterate and makesure every start property in events is instance of date
		events.forEach((event) => {
			if (event.start instanceof Date) {
				newEvents.push(event);
			} else {
				newEvents.push({
					...event,
					start: new Date(event.start)
				});
			}
		});


		this.events = newEvents;
		this.year = new Date().getFullYear();
		this.onDayClicked = (info) => {
			onDayClicked(info);
		}


	}

	getEventsByYear(year) {
		return this.events.filter((event) => {
			return event.start.getFullYear() == year;
		});
	}
	getEventsByMonth(year, month) {
		return this.events.filter((event) => {
			return event.start.getFullYear() == year && event.start.getMonth() == month;
		});
	}
	getThisYearEventsByMonthMapPerDay(month) {
		var map = new Map();
		this.getEventsByMonth(this.year, month).forEach((event) => {
			var day = event.start.getDate();
			if (!map.has(day)) {
				map.set(day, []);
			}
			map.get(day).push(event);
		}
		);
		return map;
	}

	goToNextYear() {
		this.year++;
		this.renderContent();
	}
	goToPrevYear() {
		this.year--;
		this.renderContent();
	}

	renderToolbar() {
		// 	create button previous year in left, create title in center, create button next year in right
		this.toolbar = (`
	<div class="row">
		<div class="col-sm">
			<button id="prev-year" class="btn btn-primary">Prev Year</button>
		</div>
		<div class="col-sm" style="text-align: center;">
			<h1>${this.year}</h1>
		</div>
		<div class="col-sm" style="display: flex;justify-content: flex-end;">
			<button id="next-year" class="btn btn-primary">Next Year</button>
		</div>
	</div>
	`);

	}

	renderBody() {
		//create 3 column for month using bootstrap grid
		let htmlBody = "";
		htmlBody += ('<div class= "container">');
		for (var rowIndex = 0; rowIndex < 6; rowIndex++) {
			htmlBody += ('<div class="row" style="margin-top:16px">');
			for (var columnIndex = 0; columnIndex < 2; columnIndex++) {
				htmlBody += (`
					<div class="col-sm">
						<div id="calendar-${(columnIndex + (rowIndex * 2))}"></div>
					</div>
					`);
			}
			htmlBody += ('</div>');
		}
		htmlBody += ('</div>');
		this.body = htmlBody;
	}
	renderHtml() {
		this.renderToolbar();
		this.renderBody();
		this.element.html(this.toolbar + this.body);
		//add event listener to button
		let goToPrevYear = this.goToPrevYear.bind(this);
		let goToNextYear = this.goToNextYear.bind(this);
		document.getElementById("prev-year").addEventListener('click', function () {
			goToPrevYear();
		});
		document.getElementById("next-year").addEventListener('click', function () {
			goToNextYear();
		});
	}

	getEventsRecapByMonth(monthIndex) {
		var rawEvents = this.getThisYearEventsByMonthMapPerDay(monthIndex);
		//convert map to array of events
		var events = [];

		for (let [key, value] of rawEvents) {
			var dateString = `${yearlyCalendar.year}`
				+ '-' + (monthIndex < 9 ? `0${(monthIndex + 1)}` : (monthIndex + 1)) + `${key}`;
			var backgroundColor;
			// if all events is done or done early, set background color to green
			// else if all events is any missing status, set background color to red
			// else if all events is any done late status, or draft, set background color to yellow
			// else set background color to blue
			if (value.every((event) => event.status == 'done' || event.status == 'done early')) {
				backgroundColor = '#5cb85c';
			} else if (value.some((event) => event.status == 'missing')) {
				backgroundColor = '#d9534f';
			} else if (value.some((event) => event.status == 'done late' || event.status == 'draft')) {
				backgroundColor = '#f0ad4e';
			} else {
				backgroundColor = '#337ab7';
			}

			events.push({
				title: value.length + ' schedules',
				start: dateString,
				allDay: true,
				textColor: '#000000',
				backgroundColor: backgroundColor,
				// display: 'background',
				events: value
			});
		};
		return events;
	}

	renderContent() {
		if (!this.rendering) {
			this.rendering = true;
			this.renderHtml();
			//create calendar for each month
			for (var monthIndex = 0; monthIndex < 12; monthIndex++) {
				//get events this years by month map per day
				var events = this.getEventsRecapByMonth(monthIndex);
				// );
				var calendarEl = document.getElementById('calendar-' + monthIndex);
				try {
					var dateString = `${yearlyCalendar.year}`
						+ '-' + (monthIndex < 9 ? `0${(monthIndex + 1)}` : (monthIndex + 1)) + '-01';
					console.log(`go to date ${dateString}`);
					this.calendars[monthIndex] = new FullCalendar.Calendar(calendarEl, {
						// plugins: ['dayGrid'],
						// defaultView: 'dayGridMonth', // change default view to dayGridMonth
						// locale: 'id',
						headerToolbar: {
							left: '',
							right: '',
							center: 'title'
						},
						initialDate: dateString,
						events: events,
						eventClick: (info) => {
							this.onDayClicked(info);
						},
					});
					this.calendars[monthIndex].render();
					// console.log(`go to date ${dateString}`);

					// this.calendars[monthIndex].gotoDate(dateString);

				} catch (e) {
					console.log(e);
				}
				this.rendering = false;
			}
		}
	}

}
