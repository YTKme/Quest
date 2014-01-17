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
	}
	
	/**
	 * Retrieve team
	 */
	function retrieveTeam () {
		// AJAX call to retrieve all teams
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
					team.name,
					team.point,
					0,
					'<button id="btnEditTeam-' + team.id + '" class="btn btn-primary btnEditTeam" type="button">Edit Team</button> <button id="btnRemoveTeam-' + team.id + '" class="btn btn-danger btnRemoveTeam" type="button">Remove Team</button>'
				]);
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve team(s).');
		});
	}
	
});