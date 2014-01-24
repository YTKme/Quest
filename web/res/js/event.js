/**
 * 
 */

$(function () {
	
	// Initialize
	init();
	
	/**
	 * Main
	 */
	function init () {
		// Set datetime picker
		setDatetimePicker();
		
		// Retrieve event(s) and add them to datatable
		retrieveEvent();
		
		// Retrieve game(s) and add them to dialog select drop down
		retrieveEventGame();
	}
	
	/**
	 * Set datetime picker
	 */
	function setDatetimePicker () {
		// Set today's date with no time
		var today = new Date();
		today.setHours(0,0,0,0);
		
		$('#txtStart').datetimepicker({
			dateFormat: 'yy-mm-dd', // Set date format (ISO 8601)
			timeFormat: 'HH:mm:ss',
			controlType: 'select',
			minDate: today,
			showSecond: false
		});
		
		$('#txtLength').timepicker({
			timeFormat: 'HH:mm:ss'
		});
	}
	
	/**
	 * Add event button
	 */
	$('#btnAddEvent').click(function () {
		// Add event dialog
		$('#dlgEventInformation').removeClass('hide');
		$('#dlgEventInformation').dialog({
			title: 'Add Event',
			buttons: {
				'Add': function () {
					
				},
				Cancel: function () {
					$(this).dialog('close');
				}
			},
			close: function () {
				resetEventInformation();
			}
		});
	});
	
	/**
	 * Edit event button
	 * 
	 * @param Integer eventId
	 */
	function setEditEventButton (eventId) {
		$('#btnEditEvent-' + eventId).click(function () {
			// Set focus to name
			$('#txtName').attr('autofocus', 'autofocus');
			
			// Edit achievement dialog
			$('#dlgEventInformation').removeClass('hide');
			$('#dlgEventInformation').dialog({
				title: 'Edit Event',
				buttons: {
					'Edit': function () {
						
					},
					Cancel: function () {
						$(this).dialog('close');
					}
				},
				close: function () {
					resetEventInformation();
				}
			});
		});
	}
	
	/**
	 * Retrieve event
	 */
	function retrieveEvent() {
		// AJAX call to retrieve all events
		$.ajax({
			url: '/api/event/retrieve',
			type: 'GET',
			contentType: 'application/json'
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, event) {
				var startDateTime = new Date(event.eventStart.date);
				var lengthDateTime = new Date(event.eventLength.date);
				var endDateTime = new Date(event.eventStart.date);
				endDateTime.setHours(
					startDateTime.getHours() + lengthDateTime.getHours(),
					startDateTime.getMinutes() + lengthDateTime.getMinutes(),
					startDateTime.getSeconds() + lengthDateTime.getSeconds()
				);
				
				console.log(startDateTime);
				console.log(endDateTime);
				
				// Add event to datatable
				dTable.dataTable().fnAddData([
					event.eventName,
					event.eventCode,
					event.eventStart.date,
					event.eventLength.date.substring(event.eventLength.date.indexOf(' ')),
					'<span id="countdown-' + event.eventId + '"></span>',
					'<button id="btnEditEvent-' + event.eventId + '" class="btn btn-primary btnEditEvent" type="button">Edit</button> <button id="btnRemoveEvent-' + event.eventId + '" class="btn btn-danger btnRemoveEvent" type="button">Remove</button>'
				]);
				
				var currentTime;
				var secondLeft;
				var endTime = endDateTime.getTime();
				var countHour;
				var countMinute;
				var countSecond;
				
				setInterval(function () {
					// Get current time
					currentTime = new Date().getTime();
					// Get remaining time in second
					secondLeft = (endTime - currentTime) / 1000;
					
					// Hour
					countHour = parseInt(secondLeft / 3600);
					secondLeft = secondLeft % 3600;
					
					// Minute
					countMinute = parseInt(secondLeft / 60);
					countSecond = parseInt(secondLeft % 60);
					
					// Format countdown and set value
					$('#countdown-' + event.eventId).html(countHour + ":" + countMinute + ":" + countSecond);
				}, 500);
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve event(s).');
		});
	}
	
	/**
	 * Retrieve event game
	 */
	function retrieveEventGame () {
		// AJAX call
		$.ajax({
			url: '/api/game/retrieve',
			type: 'GET',
			contentType: 'application/json'
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, game) {
				// Add game to select drop down
				$('#ddGame').append('<option value="' + game.gameId + '">' + game.gameName + '</option>');
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve game(s).');
		});
	}
	
	/**
	 * Get event array
	 * 
	 * @param Integer userId
	 * @returns Array
	 */
	function getEventArray (eventId) {
		// Create a new event array
		var eventArray = new Array();
		
		// Push the event data into the new event array
		userArray.push({
			'id': eventId,
			'name': $('#txtName').val(),
			'description': $('#txtDescription').val(),
			'start': $('#txtStart').val(),
			'length': $('#txtLength').val()
		});
		
		return eventArray;
	}
	
	/**
	 * Is valid event
	 * 
	 * @param String state
	 * @returns Boolean
	 */
	function isValidEvent (state) {
		var isValid = true;
		
		// Clear the class
		$(".has-error").removeClass("has-error");
		$('#msgState').html('');
		
		// Name
		if (!$('#txtName').val()) {
			$('#txtName').parent().addClass('has-error');
			$('#msgState').append('<p class="ui-state-error">Please enter a event Name.</p>');
			isValid = false;
		}
		
		// Start
		if (!$('#txtStart').val()) {
			$('#txtStart').parent().addClass('has-error');
			$('#msgState').append('<p class="ui-state-error">Please select event Start datetime.</p>');
			isValid = false;
		}
		
		// Length
		if (!$('#txtLength').val()) {
			$('#txtLength').parent().addClass('has-error');
			$('#msgState').append('<p class="ui-state-error">Please select event Length.</p>');
			isValid = false;
		}
		
		// Game
		if (!$('#ddGame').val()) {
			$('#ddGame').parent().addClass('has-error');
			$('#msgState').append('<p class="ui-state-error">Please select a Game.</p>');
			isValid = false;
		}
		
		// Focus on first error
		$(".has-error .form-control").first().focus();
		
		return isValid;
	}
	
	/**
	 * Reset event information
	 */
	function resetEventInformation () {
		$(".has-error").removeClass("has-error");
		$('#msgState').html('');
		
		$('#txtName').val('');
		$('#txtDescription').html('');
		$('#txtStart').val('');
		$('#txtLength').val('');
	}
	
	/**
	 * Fade message
	 * 
	 * @param String type
	 * @param String message
	 */
	function fadeMessage (type, message) {
		// Clear message
		$('#msgAlert').html('');
		$('#msgAlert').removeClass();
		
		$('#msgAlert').html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + '<p>' + message + '</p>');
		$('#msgAlert').addClass('alert alert-' + type + ' alert-dismissable');
	}
	
});