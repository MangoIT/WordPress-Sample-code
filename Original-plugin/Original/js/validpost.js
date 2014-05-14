
jQuery(document).ready(function(){
                jQuery("#post").validate({
                       
                       	rules: {
							post_title: {                                
								required: true
							},
							original_creation_year: {                                
								digits:true,
								maxlength: 4
							},
							original_dimension: {
								//number: true
							}
					    },
						messages: {
							post_title: {           
									required: 'Please enter original title'
								},
							original_creation_year: {                                
								digits: 'Please enter a valid year',
								maxlength: 'Please enter a valid year'
							},
							original_dimension: {
								//number: 'Please enter a valid dimension'
							}								
						}
				});
	jQuery("#artist_sugg").chosen({
							width : '600px',
							placeholder_text_multiple : 'Select Artist'
	});
	jQuery('#original_dimension').numeric({allow:".X "});
	
//-------------------------------------auction panel manager-----------------------------------------------------

	html = jQuery('#auction0_container')[0].outerHTML;
	//html = html.replace('display:none','');
	no_of_panels = no_of_auction;
	for(i = 1;i <= no_of_auction;i++){
		intitialize_remove_button_click(i);
		add_auction_panel_validation_rules(i);
		
	}
	
	jQuery('#add_new_auction').click(function(){
		if(no_of_panels == 0){
			no_of_auction = no_of_panels;
		}
		auction_panel_html = html.replace(/0/g,(no_of_auction+1));
		jQuery('#auction'+no_of_auction+'_container').after(auction_panel_html);
		add_auction_panel_validation_rules(no_of_auction+1);
		jQuery('#auction'+(no_of_auction+1)+'_container').slideDown();
		jQuery('.remove_auction_'+(no_of_auction)).show();
		jQuery('.remove_auction_'+(no_of_auction+1)).show();
		intitialize_remove_button_click(no_of_auction+1);
		no_of_auction++;
		jQuery('#no_of_auctions').val(no_of_auction);
		no_of_panels++;
		
	});
	
});

function intitialize_remove_button_click(auction_panel_no){
	jQuery('.remove_auction_'+(auction_panel_no)).click(function(){
		auction_no = jQuery(this).parent().children('.auction_no').val();
		jQuery('#auction'+auction_no+'_container').slideUp('slide',function(){
			jQuery('#auction'+auction_no+'_container').remove();
				if(no_of_auction == auction_panel_no){
				no_of_auction--;
				jQuery('#no_of_auctions').val(no_of_auction);
			}
			no_of_panels--;
		
			if(no_of_panels < 2){
				first_auction_no = get_first_auction_container().find('.auction_no').val();
				//jQuery('.remove_auction_'+(first_auction_no)).hide();
			}
		});
	});
}

function get_first_auction_container(){
	first_auction_container = jQuery('#auction0_container').next();
	return first_auction_container;
}

function add_auction_panel_validation_rules(i){
	jQuery('#original_auction'+i+'_hammer_price').rules( "add", {
		number: true,
		messages: {
			number: jQuery.format("Please enter a valid price")
		}
	});
	
	jQuery('#original_auction'+i+'_low_estimate').rules( "add", {
		number: true,
		messages: {
			number: jQuery.format("Please enter a valid price")
		}
	});
	
	jQuery('#original_auction'+i+'_high_estimate').rules( "add", {
		number: true,
		messages: {
			number: jQuery.format("Please enter a valid price")
		}
	});
	
	jQuery('#original_auction'+i+'_lot_number').rules( "add", {
		digits: true,
		messages: {
			digits: jQuery.format("Please enter an integer lot number.")
		}
	});
	
	jQuery('#original_auction'+i+'_sales_date').rules( "add", {
		required: true,
		messages: {
			required: jQuery.format("Please provide an auction date.")
		}
	});
	
	var today = new Date();
	jQuery('#original_auction'+i+'_sales_date').datepicker({
		dateFormat: "yy-mm-dd",
		maxDate: today
	});

}
