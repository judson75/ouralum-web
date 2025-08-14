(function($) {
	
	
	
	
	$(document).on('click', '.pt-key-btn', function() {
		var api_key = $('input[name="api_key"]').val();
		//, nonce: nonce

		$('.pt-key-btn').prepend('<i class="fa fa-refresh fa-spin fa-lg fa-fw"></i> ');
		$('.pt-key-btn').prop('disabled', true);
		$.ajax({
			type : "post",
			dataType : "json",
			url : ptAjax.ajaxurl,
			data : {action: "verify_api_key", api_key : api_key},
			success: function(response) {
				if(response.type == "success") {
					var html = '<div class="api-key-title">Enter User Hash</div>';
					html += '<input type="hidden" name="api_key" value="' + api_key + '">';
					html += '<input name="user_hash" class="api_key_field">';
					html += '<button class="button button-primary pt-hash-btn">Verify User Hash</button>';
					html += '<a href="javascript.Void(0);" id="modal_find_hash">Where do I find my user hash?</a>';
					$('.api-key-form').hide().html(html).fadeIn();
				}
				else {
					alert("Your api key could not be verified");
				}
			},
			error: function(xhr, error){
				console.debug(xhr); 
				console.debug(error);
			},
		});
	});


	$(document).on('click', '.pt-hash-btn', function() {
		var api_key = $('input[name="api_key"]').val();
		var user_hash = $('input[name="user_hash"]').val();

		$('.pt-hash-btn').prepend('<i class="fa fa-refresh fa-spin fa-lg fa-fw"></i> ');
		$('.pt-hash-btn').prop('disabled', true);
		$.ajax({
			type : "post",
			dataType : "json",
			url : ptAjax.ajaxurl,
			data : {action: "verify_user_hash", api_key : api_key, user_hash : user_hash},
			success: function(response) {
				if(response.type == "success") {
					var html = '<div class="api-key-title">API Key Verified!</div>';
					html += '<p>This page will refresh in 5 seconds</p><p><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></p>';
					$('.api-key-form').hide().html(html).fadeIn();
					setTimeout(function(){
						//location.reload(true);
						window.location.replace(location.href + "&set_key=" + api_key);
					}, 5000);
				}
				else {
					alert("Your api key could not be verified");
				}
			},
			error: function(xhr, error){
				console.debug(xhr); 
				console.debug(error);
			},
		});
	});
	
	$(document).on('change', 'select[name="list_source"]', function() {
		var elem = $(this);
		var source = $(this).val();
		if(source == '' || source == undefined) {
			return false;
		}
		$.ajax({
			type : "post",
			dataType : "json",
			url : ptAjax.ajaxurl,
			data : {action: "get_source_origin", source : source},
			success: function(response) {
				console.log(response);
				if(response.type == "success") {
					if(source == 'database_table') {
						var opts = '<div class="pt-form-group"><label for="database_table">Choose Database Table</label><select name="database_table" id="database_table"><option value=""></option>';
						$.each( response.data, function( index, value ) {
							opts += '<option value="' + value + '">' + value + '</option>';
						});
						opts += '</select></div>';
					}
					elem.parent().after(opts);
				}
				else {
					alert("There was an error");
				}
			},
			error: function(xhr, error){
				console.debug(xhr); 
				console.debug(error);
			},
		});
	});

	$(document).on('change', 'select[name="database_table"]', function() {
		var elem = $(this);
		var db_table = $(this).val();
		if(db_table == '' || db_table == undefined) {
			return false;
		}
		$.ajax({
			type : "post",
			dataType : "json",
			url : ptAjax.ajaxurl,
			data : {action: "get_table_columns", db_table : db_table},
			success: function(response) {
				console.log(response);
				if(response.type == "success") {					
					var html = '<div class="pt-form-group"><label for="database_table">Map Fields to Import</label>';
					html += '<table class="pt-table">';
					html += '<tr><td>First Name:</td><td>';
					html += '<select name="fields[first_name]" id="field_first_name"><option value=""></option>';
					$.each( response.data, function( index, value ) {
						html += '<option value="' + value + '">' + value + '</option>';
					});
					html += '</select>';
					html + '</td></tr>';
					html += '<tr><td>Last Name:</td><td>';
					html += '<select name="fields[last_name]" id="field_last_name"><option value=""></option>';
					$.each( response.data, function( index, value ) {
						html += '<option value="' + value + '">' + value + '</option>';
					});
					html += '</td></tr>';
					html += '<tr><td>Cell Phone:</td><td>';
					html += '<select name="fields[phone]" id="field_phone"><option value=""></option>';
					$.each( response.data, function( index, value ) {
						html += '<option value="' + value + '">' + value + '</option>';
					});
					html += '</td></tr>';
					html += '</table>';
					html += '</div>';
					html += '<div class="pt-form-group">';
					html += '<button type="button" class="button button-primary generateList" disabled>Generate List</button>';
					html += '</div>';
					elem.parent().after(html);
				}
				else {
					alert("There was an error");
				}
			},
			error: function(xhr, error){
				console.debug(xhr); 
				console.debug(error);
			},
		});
	});

	$(document).on('blur', 'input[name="list_name"]', function() {
		$('.pt-alert').remove();
		var list_name = $('input[name="list_name"]').val();
		var phone = $('select[name="fields[phone]"]').val();
		var first_name = $('select[name="fields[first_name]"]').val();
		var last_name = $('select[name="fields[last_name]"]').val();
		if(phone != '' && list_name != '') {
			//if first or last name is empty, alert
			if(first_name == '' || last_name == '') {
				$('.generateList').before('<div class="pt-alert pt-alert-danger">It is highly recommended that you import at least the first name. This will allow you to customize text to recepients.</div>');
			}
			$('.generateList').prop('disabled', false);
		}
	});

	$(document).on('change', 'select[name^="fields"]', function() {
		$('.pt-alert').remove();
		var first_name = $('select[name="fields[first_name]"]').val();
		var last_name = $('select[name="fields[last_name]"]').val();
		var phone = $('select[name="fields[phone]"]').val();
		var list_name = $('input[name="list_name"]').val();
		if(list_name == '') {
			$('input[name="list_name"]').after('<div class="pt-alert pt-alert-danger">Please enter list name.</div>');
		}
		if(phone != '' && list_name != '') {
			//if first or last name is empty, alert
			if(first_name == '' || last_name == '') {
				$('.generateList').before('<div class="pt-alert pt-alert-danger">It is highly recommended that you import at least the first name. This will allow you to customize text to recepients.</div>');
			}
			$('.generateList').prop('disabled', false);
		}
	});

	$(document).on('click', '.generateList', function() {
		//create list options, then create list ...
		var first_name = $('select[name="fields[first_name]"]').val();
		var last_name = $('select[name="fields[last_name]"]').val();
		var phone = $('select[name="fields[phone]"]').val();
		var list_name = $('input[name="list_name"]').val();
		var source = $('select[name="list_source"]').val();
		var db_table = '';
		if(source == 'database_table') {
			db_table = $('select[name="database_table"]').val();
		}
		else if(source == 'post_type') {

		}

		$('.generateList').prepend('<i class="fa fa-refresh fa-spin fa-lg fa-fw"></i> ');
		$('.generateList').prop('disabled', true);
		$.ajax({
			type : "post",
			dataType : "json",
			url : ptAjax.ajaxurl,
			data : {action: "generate_subscriber_list",  first_name_field: first_name, last_name_field: last_name, phone_field: phone, list_name: list_name, source: source, db_table: db_table },
			success: function(response) {
				console.log(response);
				if(response.type == "success") {
					var html = '';
					html += '<div class="pt-alert-header">Your List was successfully created!</div>';
					html += '<div class="pt-sublist-count">' + response.added + ' subscribers added</div>';
					html += '<div class="pt-sublist-count">' + response.skipped + ' subscribers skipped (No Phone Number)</div>';
					$('#list_create_frm').hide().html(html).fadeIn();
				}
				else {
					alert('error');
				}
			},
			error: function(xhr, error){
				console.debug(xhr); 
				console.debug(error);
			},
		});
	});
	
	
	
	$(document).on('click', '.refreshData', function() {
		var elem = $(this);
		if(!elem.hasClass('fa-spin')) {
			elem.addClass('fa-spin');
			//for testing, wait 4 sec then stop
			setTimeout(function(){
				elem.removeClass('fa-spin');
			}, 4000);
		}
	});
	
	
})(jQuery);	