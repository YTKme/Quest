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
		retrieveTeamAchievement();
	}
	
	// Retrieve team achievement
	function retrieveTeamAchievement () {
		// Get team ID
		var teamId = $('h2[name^="team-"]').attr('name').substring(5);
		
		// AJAX call to retrieve team and achievement
		$.ajax({
			url: 'http://quest.localhost:10080/api/team/retrieve/id/' + teamId,
			type: 'GET',
			contentType: 'application/json'
		})
		.done(function (data) {
			// Set team name
			$('#teamName').html(data.teamName);
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve team and or achievement(s).');
		});
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