function Change_type(value) {
	$('#type').empty(); // Clear select statement
	
	var select = document.getElementById('type');

	var count = 0;

	for(var i = 0; i < type.length; i++)
		if(type[i].supplier_id == value) {
			select.options[count] = new Option(type[i].name, type[i].id); 
			count++;
		}

	if(!count)  {
		// If no types found
		select.options[0] = new Option("No types found!");
		$('#type').attr('disabled', 'disabled');
	} else {
		$('#type').removeAttr('disabled');
	}
}