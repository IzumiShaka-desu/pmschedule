class FcYear {

	constructor(element, onDayClicked, events) {
		this.element = element;
		//get current year
		this.events = events;
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
	}

	goToNextYear() {
		this.year++;
		this.render();
	}
	goToPrevYear() {
		this.year--;
		this.render();
	}

	render() {
		if (!this.rendering) {
			this.rendering = true;
			this.element.empty();
			// 	create button previous year in left, create title in center, create button next year in right
			this.element.append(`
			<div class="row">
				<div class="col-sm-4">
					<button id="prev-year" class="btn btn-primary">Prev Year</button>
				</div>
				<div class="col-sm-4">
					<h1>${this.year}</h1>
				</div>
				<div class="col-sm-4">
					<button id="next-year" class="btn btn-primary">Next Year</button>
				</div>
			</div>
			`);
			//add event listener to button
			$('#prev-year').on("click", () => {
				this.goToPrevYear();
			});
			$('#next-year').on("click", () => {
				this.goToNextYear();
			});
			//create 3 column for month using bootstrap grid
			this.element.append('<div class= "container">');
			for (var rowIndex = 0; rowIndex < 4; rowIndex++) {
				this.element.append('<div class="row">');
				for (var columnIndex = 0; columnIndex < 3; columnIndex++) {
					this.element.append(`
					<div class="col-sm">
						<div id="calendar-${(columnIndex + (rowIndex * 3))}"></div>
					</div>
					`);
				}
				this.element.append('</div>');
			}
			this.element.append('</div>');
			//create calendar for each month
			for (var monthIndex = 0; monthIndex < 12; monthIndex++) {
				//get events this years by month map per day
				var rawEvents = this.getThisYearEventsByMonthMapPerDay(monthIndex);
				//convert map to array of events
				var events = [];
				rawEvents.forEach((value, key) => {
					events.push({
						title: value.length + ' event',
						start: this.year + '-' + (monthIndex + 1) + '-' + key,
						allDay: true,
						display: 'background',
						events: value
					});
				});

				//create calendar

				var calendarEl = document.getElementById('calendar-' + monthIndex);
				var calendar = new FullCalendar.Calendar(calendarEl, {
					plugins: ['dayGrid'],
					defaultView: 'dayGridMonth', // change default view to dayGridMonth
					locale: 'id',
					headerToolbar: {
						center: 'title',
					},
					events: events,
					eventClick: (info) => {
						this.onDayClicked(info);
					},

				});
				this.rendering = false;
			}
		}
	}

}
