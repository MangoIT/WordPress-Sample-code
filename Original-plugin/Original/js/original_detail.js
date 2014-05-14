jQuery(document).ready(function(){
	
	jQuery('.additional_image').click(function(){
		current_image_obj = jQuery(this);
		image_id = current_image_obj.attr('image_id');
		image_src = image_array[image_id];
		jQuery('#featured_image').attr('src',image_src);
		jQuery('.additional_image').removeClass('additional_image_highlight');
		current_image_obj.addClass('additional_image_highlight');
		
	});
	preloadImages(image_array); 
});

jQuery('.forward').click(function(){
	current_selected = jQuery('.additional_image_highlight');
	if(current_selected.length){
		image_to_highlight  = jQuery(current_selected).parent().next();
		if(!image_to_highlight.length){
			image_to_highlight = jQuery('.additional_images .additional_image_container:first-child');
		}
	} else {
		image_to_highlight = jQuery('.additional_images .additional_image_container:first-child');
	}
	jQuery(image_to_highlight).children('.additional_image').click();
	
});

jQuery('.backward').click(function(){
	current_selected = jQuery('.additional_image_highlight');
	if(current_selected.length){
		image_to_highlight  = jQuery(current_selected).parent().prev();
		if(!image_to_highlight.length){
			image_to_highlight = jQuery('.additional_images .additional_image_container:first-child');
		}
	} else {
		image_to_highlight = jQuery('.additional_images .additional_image_container:first-child');
	}
	jQuery(image_to_highlight).children('.additional_image').click();
});

function preloadImages(array) {
    if (!preloadImages.list) {
        preloadImages.list = [];
    }
    for (var i = 0; i < array.length; i++) {
        var img = new Image();
        img.src = array[i];
        preloadImages.list.push(img);
    }
}

