jQuery(document).ready(function($){
	
	$('#qib_archive_display').change(function() {		
		if (this.value === 'None') {				
			$('#qib_archive_after').parents().eq(1).hide();
		} else {
			$('#qib_archive_after').parents().eq(1).show();					
		}
	});
	
});