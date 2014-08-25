jQuery(document).ready(function() {
	$('.delete_faction').click(function(e) {
		e.preventDefault();
		var faction_num = $(this).data('faction-id');
		var faction_name = $(this).data('faction-name');
		if (confirm('Are you sure you want to delete Faction: ' + faction_name + '?')) {
			window.location.replace("edit_project.php?action=delete&type=faction&id=" + faction_num);
		}
	});
	$('.restore_faction').click(function(e) {
		e.preventDefault();
		var faction_num = $(this).data('faction-id');
		var faction_name = $(this).data('faction-name');
		if (confirm('Are you sure you want to restore Faction: ' + faction_name + '?')) {
			window.location.replace("edit_project.php?action=restore&type=faction&id=" + faction_num);
		}
	});
	$('.delete_character').click(function(e) {
		e.preventDefault();
		var character_num = $(this).data('character-id');
		var character_name = $(this).data('character-name');
		if (confirm('Are you sure you want to delete Character: ' + character_name + '?')) {
			window.location.replace("edit_project.php?action=delete&type=character&id=" + character_num);
		}
	});
	$('.restore_character').click(function(e) {
		e.preventDefault();
		var character_num = $(this).data('character-id');
		var character_name = $(this).data('character-name');
		if (confirm('Are you sure you want to restore Character: ' + character_name + '?')) {
			window.location.replace("edit_project.php?action=restore&type=character&id=" + character_num);
		}
	});
	$('#random-names').click(function(e) {
		e.preventDefault();
		if (confirm('Are you sure you want to randomly fill all blank character names?')) {
			var emptyCharacterNames = $('.character-names').filter(function() { return this.value == ""; });
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
	$('#add-faction').click(function(e) {
		e.preventDefault();
		var faction_count = parseInt($('#faction-count').html());
		var faction_limit = parseInt($('#faction-limit').html());
		var faction_id = faction_count + 1;
		if (faction_id > faction_limit) {
			alert('You already at the faction limit of ' + faction_limit);
			return false;
		}
		window.location.replace("edit_project.php?action=add-faction");
	});
});