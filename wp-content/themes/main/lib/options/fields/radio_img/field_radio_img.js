/*
 *
 * ZANBIL_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function zanbil_radio_img_select(relid, labelclass){
	jQuery(this).prev('input[type="radio"]').prop('checked');

	jQuery('.zanbil-radio-img-'+labelclass).removeClass('zanbil-radio-img-selected');	
	
	jQuery('label[for="'+relid+'"]').addClass('zanbil-radio-img-selected');
}//function