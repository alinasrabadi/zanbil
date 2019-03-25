jQuery(document).ready(function(){
	
	
	if(jQuery('#last_tab').val() == ''){

		jQuery('.zanbil-opts-group-tab:first').slideDown('fast');
		jQuery('#zanbil-opts-group-menu li:first').addClass('active');
	
	}else{
		
		tabid = jQuery('#last_tab').val();
		jQuery('#'+tabid+'_section_group').slideDown('fast');
		jQuery('#'+tabid+'_section_group_li').addClass('active');
		
	}
	
	
	jQuery('input[name="'+zanbil_opts.opt_name+'[defaults]"]').click(function(){
		if(!confirm(zanbil_opts.reset_confirm)){
			return false;
		}
	});
	
	jQuery('.zanbil-opts-group-tab-link-a').click(function(){
		relid = jQuery(this).attr('data-rel');
		
		jQuery('#last_tab').val(relid);
		
		jQuery('.zanbil-opts-group-tab').each(function(){
			if(jQuery(this).attr('id') == relid+'_section_group'){
				jQuery(this).show();
			}else{
				jQuery(this).hide();
			}
			
		});
		
		jQuery('.zanbil-opts-group-tab-link-li').each(function(){
				if(jQuery(this).attr('id') != relid+'_section_group_li' && jQuery(this).hasClass('active')){
					jQuery(this).removeClass('active');
				}
				if(jQuery(this).attr('id') == relid+'_section_group_li'){
					jQuery(this).addClass('active');
				}
		});
	});
	
	
	
	
	if(jQuery('#zanbil-opts-save').is(':visible')){
		jQuery('#zanbil-opts-save').delay(4000).slideUp('slow');
	}
	
	if(jQuery('#zanbil-opts-imported').is(':visible')){
		jQuery('#zanbil-opts-imported').delay(4000).slideUp('slow');
	}	
	
	jQuery('input, textarea, select').change(function(){
		jQuery('#zanbil-opts-save-warn').slideDown('slow');
	});
	
	
	jQuery('#zanbil-opts-import-code-button').click(function(){
		if(jQuery('#zanbil-opts-import-link-wrapper').is(':visible')){
			jQuery('#zanbil-opts-import-link-wrapper').fadeOut('fast');
			jQuery('#import-link-value').val('');
		}
		jQuery('#zanbil-opts-import-code-wrapper').fadeIn('slow');
	});
	
	jQuery('#zanbil-opts-import-link-button').click(function(){
		if(jQuery('#zanbil-opts-import-code-wrapper').is(':visible')){
			jQuery('#zanbil-opts-import-code-wrapper').fadeOut('fast');
			jQuery('#import-code-value').val('');
		}
		jQuery('#zanbil-opts-import-link-wrapper').fadeIn('slow');
	});
	
	
	
	
	jQuery('#zanbil-opts-export-code-copy').click(function(){
		if(jQuery('#zanbil-opts-export-link-value').is(':visible')){jQuery('#zanbil-opts-export-link-value').fadeOut('slow');}
		jQuery('#zanbil-opts-export-code').toggle('fade');
	});
	
	jQuery('#zanbil-opts-export-link').click(function(){
		if(jQuery('#zanbil-opts-export-code').is(':visible')){jQuery('#zanbil-opts-export-code').fadeOut('slow');}
		jQuery('#zanbil-opts-export-link-value').toggle('fade');
	});
	
	

	
	
	
});