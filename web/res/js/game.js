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
		retrieveGame();
	}
	
	/**
	 * Add game button
	 */
	$('#btnAddGame').click(function () {
		// Add game dialog
		$('#dlgGameInformation').removeClass('hide');
		$('#dlgGameInformation').dialog({
			title: 'Add Game',
			buttons: {
				'Add': function () {
					if (isValidGame()) {
						// Add game
						addGame();
						// Close the dialog
						$(this).dialog('close');
					}
				},
				Cancel: function () {
					$(this).dialog('close');
				}
			},
			close: function () {
				// Clear game
				$('#txtDescription').val('');
				resetGameInformation();
			}
		});
	});
	
	/**
	 * Edit game button
	 */
	function setEditGameButton (gameId) {
		$('#btnEditGame-' + gameId).click(function () {
			// Set focus to name
			$('#txtName').attr('autofocus', 'autofocus');
			
			// Get game information from table
			$('#txtName').val($(this).parent().parent().find('> :nth-child(1)').html());
			$('#txtDescription').val($(this).parent().parent().find('> :nth-child(2)').html());
			$('#ddLocation').val($(this).parent().parent().find('> :nth-child(3)').html());
			
			// Edit achievement dialog
			$('#dlgGameInformation').removeClass('hide');
			$('#dlgGameInformation').dialog({
				title: 'Edit Game',
				buttons: {
					'Edit': function () {
						if (isValidGame()) {
							// Edit game
							editGame(gameId);
							// Close the dialog
							$(this).dialog('close');
						}
					},
					Cancel: function () {
						$(this).dialog('close');
					}
				},
				close: function () {
					resetGameInformation();
				}
			});
		});
	}
	
	/**
	 * Remove game button
	 */
	function setRemoveGameButton (gameId) {
		$('#btnRemoveGame-' + gameId).click(function () {
			var name = $(this).parent().parent().find('> :nth-child(1)').html();
			// Get table row
			var row = dTable.fnGetPosition($(this).parent().parent()[0]);
			
			$('#dlgRemoveGame').html('<p><span class="glyphicon glyphicon-warning-sign"></span> Remove ' + name + '?</p>');
			
			// Remove game dialog
			$('#dlgRemoveGame').removeClass('hide');
			$('#dlgRemoveGame').dialog({
				title: 'Remove Game',
				buttons: {
					'Remove': function () {
						// Remove game
						removeGame(gameId, row);
						// close the dialog
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
	 * Retrieve game
	 */
	function retrieveGame () {
		// AJAX call to retrieve all game
		$.ajax({
			url: '/api/game/retrieve',
			type: 'GET',
			contentType: 'application/json'
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, game) {
				// Add game to datatable
				dTable.dataTable().fnAddData([
					game.gameName,
					game.gameDescription,
					game.gameLocation,
					'<button id="btnEditGame-' + game.gameId + '" class="btn btn-primary btnEditGame" type="button">Edit</button> <button id="btnRemoveGame-' + game.gameId + '" class="btn btn-danger btnRemoveGame" type="button">Remove</button>'
				]);
				
				// Set edit and remove game button
				setEditGameButton(game.gameId);
				setRemoveGameButton(game.gameId);
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve game(s).');
		});
	}
	
	/**
	 * Add game
	 */
	function addGame () {
		// AJAX call
		$.ajax({
			url: '/api/game/add',
			type: 'POST',
			contentType: 'application/json',
			data: JSON.stringify(getGameArray(0))
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, game) {
				// Add game to datatable
				dTable.dataTable().fnAddData([
					game.gameName,
					game.gameDescription,
					game.gameLocation,
					'<button id="btnEditGame-' + game.gameId + '" class="btn btn-primary btnEditGame" type="button">Edit</button> <button id="btnRemoveGame-' + game.gameId + '" class="btn btn-danger btnRemoveGame" type="button">Remove</button>'
				]);
				
				// Set edit and remove game button
				setEditGameButton(game.gameId);
				setRemoveGameButton(game.gameId);
			});
			
			fadeMessage('success', 'SUCCESS: Game has been added.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to add game.');
		});
	}
	
	/**
	 * Edit game
	 * 
	 * @param Integer id
	 */
	function editGame (id) {
		// Get the game information
		var name = $('#txtName').val();
		var description = $('#txtDescription').val();
		var location = $('#ddLocation').val();
		
		// AJAX call
		$.ajax({
			url: '/api/game/edit',
			type: 'PUT',
			contentType: 'application/json',
			data: JSON.stringify(getGameArray(id))
		})
		.done(function () {
			// Update table
			$('#btnEditGame-' + id).parent().parent().find('> :nth-child(1)').html(name);
			$('#btnEditGame-' + id).parent().parent().find('> :nth-child(2)').html(description);
			$('#btnEditGame-' + id).parent().parent().find('> :nth-child(3)').html(location);
			
			fadeMessage('success', 'SUCCESS: Game has been edited.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to edit game.');
		});
	}
	
	/**
	 * Remove game
	 * 
	 * @param Integer id
	 * @param Integer row
	 */
	function removeGame (id, row) {
		// AJAX call
		$.ajax({
			url: '/api/game/remove',
			type: 'DELETE',
			contentType: 'application/json',
			data: JSON.stringify([{"id": id}])
		})
		.done(function () {
			// Update table
			dTable.dataTable().fnDeleteRow(row);
			
			fadeMessage('success', 'SUCCESS: Game has been removed.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to remove game.');
		});
	}
	
	/**
	 * Get game array
	 * 
	 * @returns Array
	 */
	function getGameArray (id) {
		// Create a new game array
		var gameArray = new Array();
		
		gameArray.push({
			'id': id,
			'name': $('#txtName').val(),
			'description': $('#txtDescription').val(),
			'location': $('#ddLocation').val()
		});
		
		return gameArray;
	}
	
	/**
	 * Is valid game
	 * 
	 * @returns Boolean
	 */
	function isValidGame (state) {
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
	 * Reset game information
	 */
	function resetGameInformation () {
		$(".has-error").removeClass("has-error");
		$('#msgState').html('');
		
		$('#txtName').val('');
		$('#txtDescription').html('');
		$('#ddLocation').val('');
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