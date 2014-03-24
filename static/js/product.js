function Change_type(elem) {
	$('#type').empty(); // Clear select statement

	var select = document.getElementById('type');

	var count = 0;

	for(var i = 0; i < type.length; i++)
		if(type[i].supplier_id == elem) {
			select.options[count] = new Option(type[i].name, type[i].id);
			Change_subtype(select.options[select.selectedIndex].value);
			count++;
		}

	if(!count)  {
		// If no types found

		select.options[0] = new Option("No types found!");
		$('#type').attr('disabled', 'disabled');

		var subtype_select = document.getElementById('subtype');
		$('#subtype').empty(); // Clear subtype select
		subtype_select.options[0] = new Option("No subtypes found!");

		// Disable subtype selection
		$('#subtype').attr('disabled', 'disabled');
	} else {
		$('#type').removeAttr('disabled'); // Enable selectiont
	}
}

function Change_subtype(elem) {
	$('#subtype').empty();
	var select = document.getElementById('subtype');

	var count = 0;

	for(var i =0; i < subtype.length; i++)
		if(subtype[i].type_id == elem) {
			select.options[count] = new Option(subtype[i].name, subtype[i].id);
			count++;
		}

	if(!count) {
		$('#subtype').empty();
		select.options[0] = new Option("No subtypes found!");
		$('#subtype').attr('disabled', 'disabled')	;
	} else {
		$('#subtype').removeAttr('disabled');
	}
}