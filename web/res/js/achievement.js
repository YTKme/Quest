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
		// Retrieve achievement(s) and add them to datatable
		retrieveAchievement();
		
		// Retrieve game(s) and add them to dialog select drop down
		retrieveAchievementGame();
	}
	
	/**
	 * Add achievement button
	 */
	$('#btnAddAchievement').click(function () {
		// Add achievement dialog
		$('#dlgAchievementInformation').removeClass('hide');
		$('#dlgAchievementInformation').dialog({
			title: 'Add Achievement',
			width: 400,
			buttons: {
				'Add': function () {
					if (isValidAchievement()) {
						// Add achievement
						addAchievement();
						// Close the dialog
						$(this).dialog('close');
					}
				},
				Cancel: function () {
					//resetAchievementInformation();
					$(this).dialog('close');
				}
			},
			close: function () {
				// Clear description
				$('#txtDescription').val('');
				resetAchievementInformation();
			}
		});
	});
	
	/**
	 * Edit achievement button
	 * 
	 * @param Integer achievementId
	 */
	function setEditAchievementButton (achievementId) {
		$('#btnEditAchievement-' + achievementId).click(function () {
			// Set focus to name
			$('#txtName').attr('autofocus', 'autofocus');
			
			// AJAX call to get achievement information from database
			$.ajax({
				url: '/api/achievement/retrieve/id/' + achievementId,
				type: 'GET',
				contentType: 'application/json'
			})
			.done(function (data) {
				// Populate achievement information form
				$('#txtName').val(data.achievementName);
				$('#txtDescription').html(data.achievementDescription);
				$('#txtPoint').val(data.achievementPoint);
			})
			.error(function () {
				fadeMessage('danger', 'ERROR: Unable to retrieve achievement by ID.');
			});
			
			// Edit achievement dialog
			$('#dlgAchievementInformation').removeClass('hide');
			$('#dlgAchievementInformation').dialog({
				title: 'Edit Achievement',
				width: 400,
				buttons: {
					'Edit': function () {
						if (isValidAchievement()) {
							// Edit achievement
							editAchievement(achievementId);
							// Close the dialog
							$(this).dialog('close');
						}
					},
					Cancel: function () {
						//resetAchievementInformation();
						$(this).dialog('close');
					}
				},
				close: function () {
					resetAchievementInformation();
				}
			});
		});
	}
	
	/**
	 * Remove achievement button
	 * 
	 * @param Integer achievementId
	 */
	function setRemoveAchievementButton (achievementId) {
		$('#btnRemoveAchievement-' + achievementId).click(function () {
			var name = $(this).parent().parent().find('> :nth-child(1)').html();
			// Get table row
			var row = dTable.fnGetPosition($(this).parent().parent()[0]);
			
			$('#dlgRemoveAchievement').html('<p><span class="glyphicon glyphicon-warning-sign"></span> Remove ' + name + '?</p>');
			
			// Remove achievement dialog
			$('#dlgRemoveAchievement').removeClass('hide');
			$('#dlgRemoveAchievement').dialog({
				title: 'Remove Achievement',
				buttons: {
					'Remove': function () {
						// Remove achievement
						removeAchievement(achievementId, row);
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
					achievement.achievementName,
					(achievement.achievementIcon !== null) ? '<img class="center icoAchievement" src="' + achievement.achievementIcon + '" alt="Icon" title="Icon" />' : '<img class="center icoAchievement" src="' + _HOST + '/web/res/img/trophy.png" alt="Icon" title="Icon" />',
					achievement.achievementPoint,
					'<button id="btnEditAchievement-' + achievement.achievementId + '" class="btn btn-primary btnEditAchievement" type="button">Edit</button> <button id="btnRemoveAchievement-' + achievement.achievementId + '" class="btn btn-danger btnRemoveAchievement" type="button">Remove</button>'
				]);
				
				// Set edit and remove achievement button
				setEditAchievementButton(achievement.achievementId);
				setRemoveAchievementButton(achievement.achievementId);
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve achievement(s).');
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
				$('#ddGame').append('<option value="' + game.gameId + '">' + game.gameName + '</option>');
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve game(s).');
		});
	}
	
	/**
	 * Add achievement
	 */
	function addAchievement () {
		// AJAX call
		$.ajax({
			url: '/api/achievement/add',
			type: 'POST',
			contentType: 'application/json',
			data: JSON.stringify(getAchievementArray(0))
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, achievement) {
				console.log(achievement);
				// Add achievement to datatable
				dTable.dataTable().fnAddData([
					achievement.achievementName,
					(!achievement.achievementIcon)
						? '<img class="center icoAchievement" src="/web/res/img/trophy.png" alt="Icon" title="Icon">'
						: '<img class="center icoAchievement" src="' + achievement.achievementIcon + '" alt="Icon" title="Icon">',
					(!achievement.achievementPoint)
						? '0'
						: achievement.achievementPoint,
					'<button id="btnEditAchievement-' + achievement.achievementId + '" class="btn btn-primary btnEditAchievement" type="button">Edit</button> <button id="btnRemoveAchievement-' + achievement.achievementId + '" class="btn btn-danger btnRemoveAchievement" type="button">Remove</button>'
				]);
				
				// Set edit and remove achievement button
				setEditAchievementButton(achievement.achievementId);
				setRemoveAchievementButton(achievement.achievementId);
			});
			
			fadeMessage('success', 'SUCCESS: Achievement has been added.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to add achievement.');
		});
	}
	
	/**
	 * Edit achievement
	 * 
	 * @param Integer achievementId
	 */
	function editAchievement (achievementId) {
		// Get the achievement information
		var name = $('#txtName').val();
		var point = $('#txtPoint').val();
		
		// AJAX call
		$.ajax({
			url: '/api/achievement/edit',
			type: 'PUT',
			contentType: 'application/json',
			data: JSON.stringify(getAchievementArray(achievementId))
		})
		.done(function () {
			// Update table
			$('#btnEditAchievement-' + achievementId).parent().parent().find('> :nth-child(1)').html(name);
			$('#btnEditAchievement-' + achievementId).parent().parent().find('> :nth-child(3)').html(point);
			
			fadeMessage('success', 'SUCCESS: Achievement has been edited.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to edit achievement.');
		});
	}
	
	/**
	 * Remove achievement
	 * 
	 * @param Integer achievementId
	 * @param Integer achievementRow
	 */
	function removeAchievement (achievementId, achievementRow) {
		// AJAX call
		$.ajax({
			url: '/api/achievement/remove',
			type: 'DELETE',
			contentType: 'application/json',
			data: JSON.stringify([{"id": achievementId}])
		})
		.done(function () {
			// Update table
			dTable.dataTable().fnDeleteRow(achievementRow);
			
			fadeMessage('success', 'SUCCESS: Achievement has been removed.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to remove achievement.');
		});
	}
	
	/**
	 * Get achievement array
	 * 
	 * @param Integer achievementId
	 * @returns Array
	 */
	function getAchievementArray (achievementId) {
		// Create a new achievement array
		var achievementArray = new Array();
		
		achievementArray.push({
			'id': achievementId,
			'name': $('#txtName').val(),
			'description': $('#txtDescription').val(),
			'point': $('#txtPoint').val(),
			'game': $('#ddGame').val()
		});
		
		return achievementArray;
	}
	
	/**
	 * Is valid achievement
	 * 
	 * @param String state
	 * @returns Boolean
	 */
	function isValidAchievement (state) {
		var isValid = true;
		
		// Clear the class
		$(".has-error").removeClass("has-error");
		$('#msgState').html('');
		
		// Name
		if (!$('#txtName').val()) {
			$('#txtName').parent().addClass('has-error');
			$('#msgState').append('<p class="ui-state-error">Please enter a Name.</p>');
			isValid = false;
		}
		
		// Focus on first error
		$(".has-error .form-control").first().focus();
		
		return isValid;
	}
	
	/**
	 * Reset achievement information
	 */
	function resetAchievementInformation () {
		$(".has-error").removeClass("has-error");
		$('#msgState').html('');
		
		$('#txtName').val('');
		$('#txtDescription').html('');
		$('#txtPoint').val('');
		$('#ddGame').val('');
	}
	
	/**
	 * Fade message
	 */
	function fadeMessage (type, message) {
		// Clear message
		$('#msgAlert').html('');
		$('#msgAlert').removeClass();
		
		$('#msgAlert').html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + '<p>' + message + '</p>');
		$('#msgAlert').addClass('alert alert-' + type + ' alert-dismissable');
	}
	
});