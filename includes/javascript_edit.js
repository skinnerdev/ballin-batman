jQuery(document).ready(function() {
	var faction_loop=1, character_loop=1, var_id, faction_num, character_num;
	while (faction_loop<=12) {
		//delete faction
		$("#delete_faction_" + faction_loop).click(function() {
			var_id = this.id;
			if ([var_id.length - 2] != 1) {
				faction_num = var_id[var_id.length -1];
			} else {
				faction_num = var_id[var_id.length -2] + var_id[var_id.length -1];
			}
			if (confirm('Are you sure you want to delete Faction ' + faction_num + '?')) {
				window.location.replace("edit_project.php?delete_faction=" + faction_num)
			} else {
				return false;
			}
		});
		
		//delete faction qty
	
		while (character_loop<=12) {
			//delete character
			$("#delete_faction_" + faction_loop + "_character_" + character_loop).click(function() {
				var_id = this.id;
				if (("" + var_id[var_id.length - 2]) == "_") {
					character_num = "" + var_id[var_id.length -1];
					if (("" + var_id[var_id.length - 14]) == "_") {
						faction_num = var_id[var_id.length -13];
					} else {
						faction_num = "" + var_id[var_id.length -14] + var_id[var_id.length -13];
					}
				} else {
					character_num = "" + var_id[var_id.length -2] + var_id[var_id.length -1];
					if (var_id[var_id.length - 15] == "_") {
						faction_num = var_id[var_id.length -14];
					} else {
						faction_num = "" + var_id[var_id.length -15] + var_id[var_id.length -14];
					}
				}
				if (confirm('Are you sure you want to delete Character ' + character_num + ' from Faction ' + faction_num + '?')) {
					window.location.replace("edit_project.php?delete_faction=" + faction_num + "&delete_character=" + character_num);
				} else {
					return false;
				}
			});
			//delete character qty
			character_loop++;
		}
		character_loop=1;
		faction_loop++;
	}
	faction_loop=0;
});