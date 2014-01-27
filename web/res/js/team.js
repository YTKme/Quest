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
		// Retrieve team and add them to datatable
		retrieveTeam();
		
		// Retrieve event(s) and add them to dialog select drop down
		retrieveTeamEvent();
	}
	
	/**
	 * Add team button
	 */
	$('#btnAddTeam').click(function () {
		// Add event dialog
		$('#dlgTeamInformation').removeClass('hide');
		$('#dlgTeamInformation').dialog({
			title: 'Add Team',
			buttons: {
				'Add': function () {
					if (isValidTeam()) {
						// Add team
						addTeam();
						// Close the dialog
						$(this).dialog('close');
					}
				},
				Cancel: function () {
					$(this).dialog('close');
				}
			},
			close: function () {
				resetTeamInformation();
			}
		});
	});
	
	/**
	 * Edit team button
	 * 
	 * @param Integer teamId
	 */
	function setEditTeamButton (teamId) {
		$('#btnEditTeam-' + teamId).click(function () {
			// Set focus to name
			$('#txtName').attr('autofocus', 'autofocus');
			
			// AJAX call to get team information from database
			$.ajax({
				url: '/api/team/retrieve/id/' + teamId,
				type: 'GET',
				contentType: 'application/json'
			})
			.done(function (data) {
				$('#txtName').val(data.teamName);
				$('#txtPoint').val(data.teamPoint);
			})
			.error(function () {
				fadeMessage('danger', 'ERROR: Unable to retrieve team by ID.');
			});
			
			// Edit team dialog
			$('#dlgTeamInformation').removeClass('hide');
			$('#dlgTeamInformation').dialog({
				title: 'Edit Team',
				buttons: {
					'Edit': function () {
						if (isValidTeam()) {
							// Edit team
							editTeam(teamId);
							// Close the dialog
							$(this).dialog('close');
						}
					},
					Cancel: function () {
						$(this).dialog('close');
					}
				},
				close: function () {
					resetTeamInformation();
				}
			});
		});
	}
	
	/**
	 * Remove team button
	 * 
	 * @param Integer teamId
	 */
	function setRemoveTeamButton (teamId) {
		$('#btnRemoveTeam-' + teamId).click(function () {
			var name = $(this).parent().parent().find('> :nth-child(1)').html();
			// Get table row
			var row = dTable.fnGetPosition($(this).parent().parent()[0]);
			
			$('#dlgRemoveTeam').html('<p><span class="glyphicon glyphicon-warning-sign"></span> Remove ' + name + '?</p>');
			
			// Remove event dialog
			$('#dlgRemoveTeam').removeClass('hide');
			$('#dlgRemoveTeam').dialog({
				title: 'Remove Team',
				buttons: {
					'Remove' : function () {
						// Remove user
						removeTeam(teamId, row);
						// Close the dialog
						$(this).dialog('close');
					},
					Cancel: function() {
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
	 * Retrieve team
	 */
	function retrieveTeam () {
		// AJAX call to retrieve all team(s)
		$.ajax({
			url: '/api/team/retrieve',
			type: 'GET',
			contentType: 'application/json'
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, team) {
				// Add user to datatable
				dTable.dataTable().fnAddData([
					team.teamName,
					team.teamPoint,
					0,
					'<button id="btnEditTeam-' + team.teamId + '" class="btn btn-primary btnEditTeam" type="button">Edit</button> <button id="btnRemoveTeam-' + team.teamId + '" class="btn btn-danger btnRemoveTeam" type="button">Remove</button>'
				]);
				
				// Set edit and remove team button
				setEditTeamButton(team.teamId);
				setRemoveTeamButton(team.teamId);
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve team(s).');
		});
	}
	
	/**
	 * Retrieve event
	 */
	function retrieveTeamEvent () {
		// AJAX call to retrieve all event(s)
		$.ajax({
			url: '/api/event/retrieve',
			type: 'GET',
			contentType: 'application/json'
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, event) {
				// Add event to select drop down
				$('#ddEvent').append('<option value="' + event.eventId + '">' + event.eventName + '</option>');
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve event(s).');
		});
	}
	
	/**
	 * Add team
	 */
	function addTeam () {
		// AJAX call
		$.ajax({
			url: '/api/team/add',
			type: 'POST',
			contentType: 'application/json',
			data: JSON.stringify(getTeamArray(0))
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, team) {
				// Add team to datatable
				dTable.dataTable().fnAddData([
					team.teamName,
					team.teamPoint,
					0,
					'<button id="btnEditTeam-' + team.teamId + '" class="btn btn-primary btnEditTeam" type="button">Edit</button> <button id="btnRemoveTeam-' + team.teamId + '" class="btn btn-danger btnRemoveTeam" type="button">Remove</button>'
				]);
				
				// Set edit and remove team button
				setEditTeamButton(team.teamId);
				setRemoveTeamButton(team.teamId);
			});
			
			fadeMessage('success', 'SUCCESS: Team has been added.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to add team.');
		});
	}
	
	/**
	 * Edit team
	 * 
	 * @param Integer teamId
	 */
	function editTeam (teamId) {
		// Get the team information
		var name = $('#txtName').val();
		var point = $('#txtPoint').val();
		
		// AJAX call
		$.ajax({
			url: '/api/team/edit',
			type: 'PUT',
			contentType: 'application/json',
			data: JSON.stringify(getTeamArray(teamId))
		})
		.done(function (data) {
			$('#btnEditTeam-' + teamId).parent().parent().find('> :nth-child(1)').html(name);
			$('#btnEditTeam-' + teamId).parent().parent().find('> :nth-child(2)').html(point);
			
			fadeMessage('success', 'SUCCESS: Team has been edited.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to edit team.');
		});
	}
	
	/**
	 * Remove team
	 * 
	 * 
	 */
	function removeTeam (teamId, teamRow) {
		// AJAX call
		$.ajax({
			url: '/api/team/remove',
			type: 'DELETE',
			contentType: 'application/json',
			data: JSON.stringify([{"id": teamId}])
		})
		.done(function () {
			// Update table
			dTable.dataTable().fnDeleteRow(teamRow);
			
			fadeMessage('success', 'SUCCESS: Team has been removed.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to remove team.');
		});
	}
	
	/**
	 * Get team array
	 * 
	 * @param Integer teamId
	 * @returns Array
	 */
	function getTeamArray (teamId) {
		// Create a new event array
		var teamArray = new Array();
		
		// Push the team data into the new team array
		teamArray.push({
			'id': teamId,
			'name': $('#txtName').val(),
			'point': $('#txtPoint').val(),
		});
		
		return teamArray;
	}
	
	/**
	 * Is valid team
	 * 
	 * @param String state
	 * @returns Boolean
	 */
	function isValidTeam (state) {
		var isValid = true;
		
		// Clear the class
		$(".has-error").removeClass("has-error");
		$('#msgState').html('');
		
		// Name
		if (!$('#txtName').val()) {
			$('#txtName').parent().addClass('has-error');
			$('#msgState').append('<p class="ui-state-error">Please enter a team Name.</p>');
			isValid = false;
		}
		
		// Focus on first error
		$(".has-error .form-control").first().focus();
		
		return isValid;
	}
	
	/**
	 * Reset team information
	 */
	function resetTeamInformation () {
		$(".has-error").removeClass("has-error");
		$('#msgState').html('');
		
		$('#txtName').val('');
		$('#txtPoint').val('');
		$('#ddEvent').val('');
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