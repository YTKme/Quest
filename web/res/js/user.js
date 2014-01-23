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
		// Retrieve user and add them to datatable
		retrieveUser();
	}
	
	/**
	 * Add user button
	 */
	$('#btnAddUser').click(function () {
		// Enable username field if disabled
		$('#txtUsername').removeAttr('disabled');
		
		// Set focus to password
		$('#txtUsername').attr('autofocus', 'autofocus');
		
		// Add user dialog
		$('#dlgUserInformation').removeClass('hide');
		$('#dlgUserInformation').dialog({
			title: 'Add User',
			buttons: {
				'Add': function () {
					if (isValidUser('add')) {
						// Add user
						addUser();
						// Close the dialog
						$(this).dialog('close');
					}
				},
				Cancel: function () {
					$(this).dialog('close');
				}
			},
			close: function () {
				resetUserInformation();
			}
		});
	});
	
	/**
	 * Edit user button
	 * 
	 * @param Integer userId
	 */
	function setEditUserButton (userId) {
		$('#btnEditUser-' + userId).click(function () {
			// Disable username field
			$('#txtUsername').attr('disabled', 'disabled');
			
			// Set focus to password
			$('#txtPassword').attr('autofocus', 'autofocus');
			
			// Get user information from table
			$('#txtUsername').val($(this).parent().parent().find('> :nth-child(1)').html());
			$('#txtFirstName').val($(this).parent().parent().find('> :nth-child(2)').html());
			$('#txtLastName').val($(this).parent().parent().find('> :nth-child(3)').html());
			
			// Edit user dialog
			$('#dlgUserInformation').removeClass('hide');
			$('#dlgUserInformation').dialog({
				title: 'Edit User',
				buttons: {
					'Edit': function () {
						if (isValidUser()) {
							// Edit user
							editUser(userId);
							// Close the dialog
							$(this).dialog('close');
						}
					},
					Cancel: function () {
						$(this).dialog('close');
					}
				},
				close: function () {
					resetUserInformation();
				}
			});
		});
	}
	
	/**
	 * Remove user button
	 * 
	 * @param Integer userId
	 */
	function setRemoveUserButton (userId) {
		$('#btnRemoveUser-' + userId).click(function () {
			var firstName = $(this).parent().parent().find('> :nth-child(2)').html();
			var lastName = $(this).parent().parent().find('> :nth-child(3)').html();
			// Get table row
			var row = dTable.fnGetPosition($(this).parent().parent()[0]);
			
			$('#dlgRemoveUser').html('<p><span class="glyphicon glyphicon-warning-sign"></span> Remove ' + firstName + ' ' + lastName + '?</p>');
			
			// Remove user dialog
			$('#dlgRemoveUser').removeClass('hide');
			$('#dlgRemoveUser').dialog({
				title: 'Remove User',
				buttons: {
					'Remove': function () {
						// Remove user
						removeUser(userId, row);
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
	 * Retrieve user
	 */
	function retrieveUser () {
		// AJAX call to retrieve all users
		$.ajax({
			url: '/api/user/retrieve',
			type: 'GET',
			contentType: 'application/json'
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, user) {
				// Add user to datatable
				dTable.dataTable().fnAddData([
					user.userUsername,
					user.userFirstName,
					user.userLastName,
					user.userRole,
					'<button id="btnEditUser-' + user.userId + '" class="btn btn-primary btnEditUser" type="button">Edit User</button> <button id="btnRemoveUser-' + user.userId + '" class="btn btn-danger btnRemoveUser" type="button">Remove User</button>'
				]);
				
				// Set edit and remove user button
				setEditUserButton(user.userId);
				setRemoveUserButton(user.userId);
			});
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to retrieve user(s).');
		});
	}
	
	/**
	 * Add user
	 */
	function addUser () {
		// AJAX call
		$.ajax({
			url: '/api/user/add',
			type: 'POST',
			contentType: 'application/json',
			data: JSON.stringify(getUserArray(0))
		})
		.done(function (data) {
			// Loop through each result data
			$.each(data, function (index, user) {
				// Add user to datatable
				dTable.dataTable().fnAddData([
					user.userUsername,
					user.userFirstName,
					user.userLastName,
					user.userRole,
					'<button id="btnEditUser-' + user.userId + '" class="btn btn-primary btnEditUser" type="button">Edit User</button> <button id="btnRemoveUser-' + user.userId + '" class="btn btn-danger btnRemoveUser" type="button">Remove User</button>'
				]);
				
				// Set edit and remove user button
				setEditUserButton(user.userId);
				setRemoveUserButton(user.userId);
			});
			
			fadeMessage('success', 'SUCCESS: User has been added.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to add user.');
		});
	}
	
	/**
	 * Edit user
	 * 
	 * @param Integer userId
	 */
	function editUser (userId) {
		// Get the user information
		var role = $('#ddRole').val();
		var firstName = $('#txtFirstName').val();
		var lastName = $('#txtLastName').val();
		
		// AJAX call
		$.ajax({
			url: '/api/user/edit',
			type: 'PUT',
			contentType: 'application/json',
			data: JSON.stringify(getUserArray(userId))
		})
		.done(function () {
			// Update table
			$('#btnEditUser-' + userId).parent().parent().find('> :nth-child(2)').html(firstName);
			$('#btnEditUser-' + userId).parent().parent().find('> :nth-child(3)').html(lastName);
			$('#btnEditUser-' + userId).parent().parent().find('> :nth-child(4)').html(role);
			
			fadeMessage('success', 'SUCCESS: User has been edited.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to edit user.');
		});
	}
	
	/**
	 * Remove user
	 * 
	 * @param Integer userId
	 * @param Integer userRow
	 */
	function removeUser (userId, userRow) {
		// AJAX call
		$.ajax({
			url: '/api/user/remove',
			type: 'DELETE',
			contentType: 'application/json',
			data: JSON.stringify([{"id": userId}])
		})
		.done(function () {
			// Update table
			dTable.dataTable().fnDeleteRow(userRow);
			
			fadeMessage('success', 'SUCCESS: User has been removed.');
		})
		.error(function () {
			fadeMessage('danger', 'ERROR: Unable to remove user.');
		});
	}
	
	/**
	 * Get user array
	 * 
	 * @param Integer userId
	 * @returns Array
	 */
	function getUserArray (userId) {
		// Create a new user array
		var userArray = new Array();
		
		// Push the user data into the new user array
		userArray.push({
			'id': userId,
			'username': $('#txtUsername').val(),
			'password': $('#txtPassword').val(),
			'role': $('#ddRole').val(),
			'firstName': $('#txtFirstName').val(),
			'lastName': $('#txtLastName').val()
		});
		
		return userArray;
	}
	
	/**
	 * Is valid user
	 * 
	 * @param String state
	 * @returns Boolean
	 */
	function isValidUser (state) {
		var isValid = true;
		
		// Clear the class
		$(".has-error").removeClass("has-error");
		$('#msgState').html('');
		
		// Username
		if (!$('#txtUsername').val()) {
			$('#txtUsername').parent().addClass('has-error');
			$('#msgState').append('<p class="ui-state-error">Please enter a Username.</p>');
			isValid = false;
		}
		
		// Password
		if (state === 'add') {
			if (!$('#txtPassword').val() || !$('#txtConfirmPassword').val()) {
				$('#txtPassword').parent().addClass('has-error');
				$('#txtConfirmPassword').parent().addClass('has-error');
				$('#msgState').append('<p class="ui-state-error">Please enter and confirm the Password.</p>');
				isValid = false;
			}
		}
		
		if ($('#txtPassword').val() !== $('#txtConfirmPassword').val()) {
			$('#txtPassword').parent().addClass('has-error');
			$('#txtConfirmPassword').parent().addClass('has-error');
			$('#msgState').append('<p class="ui-state-error">Password mismatch.</p>');
			isValid = false;
		}
		
		// Select
		if (!$('#ddRole').val()) {
			$(this).parent().addClass('has-error');
			isValid = false;
		}
		
		// Focus on first error
		$(".has-error .form-control").first().focus();
		
		return isValid;
	}
	
	/**
	 * Reset user information
	 */
	function resetUserInformation () {
		$(".has-error").removeClass("has-error");
		$('#msgState').html('');
		
		$('#txtUsername').val('');
		$('#txtPassword').val('');
		$('#txtConfirmPassword').val('');
		$('#txtFirstName').val('');
		$('#txtLastName').val('');
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