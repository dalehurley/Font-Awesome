$(document).ready(function() {
			$.featureList(
				$("#tabs li a"),
				$("#output li"), {
					start_item	:	1
				}
			);

			/*
			
			// Alternative

			
			$('#tabs li a').featureList({
				output			:	'#output li',
				start_item		:	1
			});

			*/


});