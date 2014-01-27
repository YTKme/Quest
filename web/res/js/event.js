/**
 * 
 */

$(function () {
	
	// Store interval ID
	var eventCoundownId = new Array();
	
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
		retrieveGame();
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
					if (isValidEvent()) {
						// Add event
						addEvent();
						// Close the dialog
						$(this).dialog('close');
					}
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
			
			// AJAX call to get event information from database
			$.ajax({
				url: '/api/event/retrieve/id/' + eventId,
				type: 'GET',
				contentType: 'application/json'
			})
			.done(function (data) {
				// Populate event information form
				$('#txtName').val(data.eventName);
				$('#txtDescription').html(data.eventDescription);
				$('#txtStart').val(data.eventStart.date);
				$('#txtLength').val(data.eventLength.date.substring(data.eventLength.date.indexOf(' ')));
				$('#ddGame').val(data.eventGame.gameId);
			})
			.error(function () {
				fadeMessage('danger', 'ERROR: Unable to retrieve event by ID.');
			});
			
			// Edit achievement dialog
			$('#dlgEventInformation').removeClass('hide');
			$('#dlgEventInformation').dialog({
				title: 'Edit Event',
				buttons: {
					'Edit': function () {
						if (isValidEvent()) {
							// Edit event
							editEvent(eventId);
							// Close the dialog
							$(this).dialog('close');
						}
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
	 * Remove event button
	 * 
	 * @param Integer eventId
	 */
	function setRemoveEventButton (eventId) {
		$('#btnRemoveEvent-' + eventId).click(function () {
			var name = $(this).parent().parent().find('> :nth-child(1)').html();
			// Get table row
			var row = dTable.fnGetPosition($(this).parent().parent()[0]);
			
			$('#dlgRemoveEvent').html('<p><span class="glyphicon glyphicon-warning-sign"></span> Remove ' + name + '?</p>');
			
			// Remove event dialog
			$('#dlgRemoveEvent').removeClass('hide');
			$('#dlgRemoveEvent').dialog({
				title: 'Remove Event',
				buttons: {
					'Remove': function () {
						// Remove event
						removeEvent(eventId, row);
						// Close the dialog
						$(this).dialog('close');
					},
					Cancel: function () {
						$(this).dialog('close');
					}
				},
				close: function () {
					$(this).dialog('close');
				}
			});
		});
	}
	
	/**
	 * Retrieve event
	 */
	function retrieveEvent() {
		// AJAX call to retrieve all event(s)
		$.ajax({
			url: '/api/event/retrieve',
			type: 'GET',
			contentType: 'application/json'
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, event) {
				// Add event to datatable
				dTable.dataTable().fnAddData([
					event.eventName,
					event.eventCode,
					event.eventStart.date,
					event.eventLength.date.substring(event.eventLength.date.indexOf(' ')),
					'<span id="countdown-' + event.eventId + '"></span>',
					'<button id="btnEditEvent-' + event.eventId + '" class="btn btn-primary btnEditEvent" type="button">Edit</button> <button id="btnRemoveEvent-' + event.eventId + '" class="btn btn-danger btnRemoveEvent" type="button">Remove</button>'
				]);
				
				// Set edit and remove event button
				setEditEventButton(event.eventId);
				setRemoveEventButton(event.eventId);
				
				// Set countdown for event
				setCountdown(event);
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve event(s).');
		});
	}
	
	/**
	 * Retrieve game
	 */
	function retrieveGame () {
		// AJAX call to retrieve all event game(s)
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
	 * Add event
	 */
	function addEvent () {
		// AJAX call
		$.ajax({
			url: '/api/event/add',
			type: 'POST',
			contentType: 'application/json',
			data: JSON.stringify(getEventArray(0))
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, event) {
				// Add event to datatable
				dTable.dataTable().fnAddData([
					event.eventName,
					event.eventCode,
					event.eventStart.date,
					event.eventLength.date.substring(event.eventLength.date.indexOf(' ')),
					'<span id="countdown-' + event.eventId + '"></span>',
					'<button id="btnEditEvent-' + event.eventId + '" class="btn btn-primary btnEditEvent" type="button">Edit</button> <button id="btnRemoveEvent-' + event.eventId + '" class="btn btn-danger btnRemoveEvent" type="button">Remove</button>'
				]);
				
				// Set edit and remove event button
				setEditEventButton(event.eventId);
				setRemoveEventButton(event.eventId);
				
				// Set countdown for event
				setCountdown(event);
			});
			
			fadeMessage('success', 'SUCCESS: Event has been added.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to add event.');
		});
	}
	
	/**
	 * Edit event
	 * 
	 * @param Integer eventId
	 */
	function editEvent (eventId) {
		// Get the event information
		var name = $('#txtName').val();
		var start = $('#txtStart').val();
		var length = $('#txtLength').val();
		
		// AJAX call
		$.ajax({
			url: '/api/event/edit',
			type: 'PUT',
			contentType: 'application/json',
			data: JSON.stringify(getEventArray(eventId))
		})
		.done(function (data) {
			// Update table
			$('#btnEditEvent-' + eventId).parent().parent().find('> :nth-child(1)').html(name);
			$('#btnEditEvent-' + eventId).parent().parent().find('> :nth-child(3)').html(start);
			$('#btnEditEvent-' + eventId).parent().parent().find('> :nth-child(4)').html(length.substring(length.indexOf(' ')));
			//$('#btnEditEvent-' + eventId).parent().parent().find('> :nth-child(5)').html('<span id="countdown-' + eventId + '"></span>');
			
			// Loop through each result data
			$.each(data, function (index, event) {
				clearInterval(eventCoundownId['countdown' + event.eventId]);
				// Set countdown for event
				setCountdown(event);
			});
			
			fadeMessage('success', 'SUCCESS: Event has been edited.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to edit event.');
		});
	}
	
	/**
	 * Remove event
	 * 
	 * @param Integer eventId
	 * @param Integer eventRow
	 */
	function removeEvent (eventId, eventRow) {
		// AJAX call
		$.ajax({
			url: '/api/event/remove',
			type: 'DELETE',
			contentType: 'application/json',
			data: JSON.stringify([{"id": eventId}])
		})
		.done(function () {
			// Update table
			dTable.dataTable().fnDeleteRow(eventRow);
			
			fadeMessage('success', 'SUCCESS: Event has been removed.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to remove event.');
		});
	}
	
	/**
	 * Set countdown
	 * 
	 * @param Object event
	 */
	function setCountdown (event) {
		var startDateTime = new Date(event.eventStart.date);
		var lengthDateTime = new Date(event.eventLength.date);
		var endDateTime = new Date(event.eventStart.date);
		endDateTime.setHours(
			startDateTime.getHours() + lengthDateTime.getHours(),
			startDateTime.getMinutes() + lengthDateTime.getMinutes(),
			startDateTime.getSeconds() + lengthDateTime.getSeconds()
		);
		
		var currentTime;
		var secondLeft;
		var endTime = endDateTime.getTime();
		var countHour;
		var countMinute;
		var countSecond;
		
		eventCoundownId['countdown' + event.eventId] = setInterval(function () {
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
			if (countHour <= 0 && countMinute <= 0 && countSecond <= 0) {
				$('#countdown-' + event.eventId).html('Ended');
			} else {
				$('#countdown-' + event.eventId).html(
					((countHour < 10) ? '0' + countHour : countHour) + ":" +
					((countMinute < 10) ? '0' + countMinute : countMinute) + ":" +
					((countSecond < 10) ? '0' + countSecond : countSecond)
				);
			}
		}, 500);
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
		eventArray.push({
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
		$('#ddGame').val('');
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