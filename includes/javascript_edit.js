jQuery(document).ready(function() {
	$('.delete_faction').click(function(e) {
		e.preventDefault();
		var faction_num = $(this).data('faction-id');
		var faction_name = $(this).data('faction-name');
		if (confirm('Are you sure you want to delete Faction: ' + faction_name + '?')) {
		    var url = "edit_project.php?action=save-form&result=json";
		    $.ajax({
				type: "POST",
				url: url,
				data: $("#project-form").serialize(),
				success: function(data)
				{
					console.log(data);
					window.location.replace("edit_project.php?action=delete&type=faction&id=" + faction_num);
				}
	        });
		}
	});
	$('.restore_faction').click(function(e) {
		e.preventDefault();
		var faction_num = $(this).data('faction-id');
		var faction_name = $(this).data('faction-name');
		if (confirm('Are you sure you want to restore Faction: ' + faction_name + '?')) {
		    var url = "edit_project.php?action=save-form&result=json";
		    $.ajax({
				type: "POST",
				url: url,
				data: $("#project-form").serialize(),
				success: function(data)
				{
					console.log(data);
					window.location.replace("edit_project.php?action=restore&type=faction&id=" + faction_num);
				}
	        });
		}
	});
	$('.toggle_character').click(function(e) {
		e.preventDefault();
		var character_id = $(this).data('character-id');
		var character_name = $(this).data('character-name');
		var character_faction = $(this).data('character-faction');
		var action = $(this).data('action');
		if (confirm('Are you sure you want to ' + action + ' Character: ' + character_name + '?')) {
		    var url = "edit_project.php?action=save-form&result=json";
		    $.ajax({
				type: "POST",
				url: url,
				data: $("#project-form").serialize(),
				success: function(data)
				{
					$.get( "edit_project.php?action=" + action + "&type=character&id=" + character_id, function(data) {
						parsedData = JSON.parse(data);
						if (parsedData == "success") {
							atitle = "Restore Character";
							aclass = "fa fa-undo fa-lg restore-character";
							baction = 'restore';
							place = 'deleted';
							disabled = true;
							if (action == 'restore') {
								atitle = "Delete Character";
								aclass = "fa fa-times fa-2x delete-character";
								baction = 'delete';
								place = 'active';
								disabled = false;
							}
							$("#character_" + character_id).children("input").prop('disabled', disabled);
							$("#character_" + character_id).children("select").prop('disabled', disabled);
							$("a[data-character-id='" + character_id + "']").prop('title', atitle);
							$("a[data-character-id='" + character_id + "']").children("i").prop('class', aclass);
							$('#' + place + '_characters_' + character_faction).append($("#character_" + character_id));
							$("a[data-character-id='" + character_id + "']").data('action', baction);
						} else {
							alert("There was a problem with that action.");
						}
					}).fail(function() {
					    console.log('Error getting random names');
					});
				}
	        });
		}
	});
	$('#random-names').click(function(e) {
		e.preventDefault();
		if (confirm('Are you sure you want to randomly fill all character names?')) {
			//var emptyCharacterNames = $('.character-names:enabled').filter(function() { return this.value == ""; });
			var emptyCharacterNames = $('.character-names:enabled');
			var nameCount = emptyCharacterNames.length;
			var randomNames;
			$.get( "edit_project.php?action=get-names&name-count=" + nameCount, function(data) {
				emptyCharacterNames.each(function(index) {
					parsedData = JSON.parse(data);
			      	this.value = parsedData[index];
			    });
			}).fail(function() {
			    console.log('Error getting random names');
			});
		}
	});
	$('#clear-character-names').click(function(e) {
		e.preventDefault();
		if (confirm('Are you sure you want to clear all character names?')) {
			//var emptyCharacterNames = $('.character-names').filter(function() { return this.value == ""; });
			$('.character-names:enabled').each(function(index) {
		      	this.value = '';
		    });
		}
	});
	$('#clear-player-names').click(function(e) {
		e.preventDefault();
		if (confirm('Are you sure you want to clear all player names?')) {
			//var emptyCharacterNames = $('.character-names').filter(function() { return this.value == ""; });
			$('.player-names:enabled').each(function(index) {
		      	this.value = '';
		    });
		}
	});
	$('#clear-character-bio').click(function(e) {
		e.preventDefault();
		if (confirm('Are you sure you want to clear all characters bio?')) {
			//var emptyCharacterNames = $('.character-names').filter(function() { return this.value == ""; });
			$('.character-bio:enabled').each(function(index) {
		      	this.value = '';
		    });
		}
	});

	$('#add-faction').click(function(e) {
		e.preventDefault();
		var faction_count = parseInt($('#faction-count').html());
		var faction_limit = parseInt($('#faction-limit').html());
		var faction_id = faction_count + 1;
		if (faction_id > faction_limit) {
			alert('You already at the faction limit of ' + faction_limit);
			return false;
		}
	    var url = "edit_project.php?action=save-form&result=json";
	    $.ajax({
			type: "POST",
			url: url,
			data: $("#project-form").serialize(),
			success: function(data)
			{
				console.log(data);
				window.location.replace("edit_project.php?action=add-faction");
			}
        });
	});
});