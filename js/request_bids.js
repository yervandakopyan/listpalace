document.observe('dom:loaded', function() {
	$('main_category').observe('change',function(e){
		if ($F('main_category') != '0') {
			get_sub_categories($F('main_category'));
			$('job_info').show();
		}
    });
	
});

function get_sub_categories (id_main_category) {
	new Ajax.Request('/ajax/sub_categories.php', {
		method: "post",
		parameters: {
			"id_main_category": id_main_category,
		},
		asynchronous: false,
		onSuccess: function(response) {
			var result=response.responseText.evalJSON();
			
			var display_name = "",
				id_sub_cat = "", 
				select_value = "",
				idx,
				len = result.length;
				
				if(result == 'Fail') {
					$('error_box').update('An error has occured. Please reload the page and try again.');
					$('error_box').show();
				}
				
				for( idx=0; idx < len; idx++ ) {
					str = result[idx % len].sub_display_name;
					id_sub_cat = result[idx % len].id_sub_category;
					select_value += "<option value=" + id_sub_cat + ">" + str + "</option>";
				} 
				
				$('sub_cat_div').show();
				$('sub_category').update(select_value);
				
			
		},
		onFailure: function(response) {
			$('error_box').update('An error has occured. Please reload the page and try again.');
			$('error_box').show();
		}
	}); 
}