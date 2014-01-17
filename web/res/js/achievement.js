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
		// Retrieve achievement and add them to datatable
		retrieveAchievement();
		
		// Retrieve game and add them to dialog select drop down
		retrieveAchievementGame();
	}
	
	/**
	 * Retrieve achievement
	 */
	function retrieveAchievement () {
		var _HOST = window.location.protocol + '//' + window.location.host;
		
		// AJAX call to retrieve all teams
		$.ajax({
			url: '/api/achievement/retrieve',
			type: 'GET',
			contentType: 'application/json'
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, achievement) {
				// Add achievement to datatable
				dTable.dataTable().fnAddData([
					achievement.name,
					(achievement.icon !== null) ? '<img class="center icoAchievement" src="' + achievement.icon + '" alt="Icon" title="Icon" />' : '<img class="center icoAchievement" src="' + _HOST + '/web/res/img/trophy.png" alt="Icon" title="Icon" />',
					achievement.point,
					'<button id="btnEditAchievement-' + achievement.id + '" class="btn btn-primary btnEditAchievement" type="button">Edit</button> <button id="btnRemoveTeam-' + achievement.id + '" class="btn btn-danger btnRemoveAchievement" type="button">Remove</button>'
				]);
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve team(s).');
		});
	}
	
	/**
	 * Retrieve achievement game
	 */
	function retrieveAchievementGame () {
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
				$('#ddGame').append('<option value="' + game.id + '">' + game.name + '</option>');
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve game(s).');
		});
	}
	
});