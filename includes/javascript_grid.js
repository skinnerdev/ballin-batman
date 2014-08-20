jQuery(document).ready(function() {
	//block1a to block 12l
	var column_var=1, row_num=1, row_var="a";
	while (column_var<=12) {
		while (row_num<=12) {
			if (row_num==1) {row_var="a";} else if (row_num==2) {row_var="b";} else if (row_num==3) {row_var="c";} else if (row_num==4) {row_var="d";} else if (row_num==5) {row_var="e";} else if (row_num==6) {row_var="f";} else if (row_num==7) {row_var="g";} else if (row_num==8) {row_var="h";} else if (row_num==9) {row_var="i";} else if (row_num==10) {row_var="j";} else if (row_num==11) {row_var="k";} else if (row_num==12) {row_var="l";}
			$("#block" + column_var + row_var).click(function() {
				
				
			});
			row_num++;
		}
		row_num=1;
		column_var++;
	}
});