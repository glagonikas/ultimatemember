wp.hooks.addAction( 'um_after_account_tab_changed', 'um_jobboardwp', function( tab_ ) {
	if ( 'jobboardwp' === tab_ ) {
		jb_responsive();
	}
});

wp.hooks.addAction( 'um_account_active_tab_inited', 'um_jobboardwp', function( tab_ ) {
	if ( 'jobboardwp' === tab_ ) {
		jb_responsive();
	}
});
// show header if there is map
wp.hooks.addFilter( 'um_bookmarks_remove_button_args', 'um_jobboardwp', function( data ) {
	data.job_list = true;
	return data;
}, 10 );


wp.hooks.addFilter( 'um_bookmarks_add_button_args', 'um_jobboardwp', function( data ) {
	data += '&job_list=1';
	return data;
}, 10 );
(function( $ ) {
	'use strict';

	$(document).on('click', "a.um-toggle-terms" ,function() {
		 
		var me = jQuery(this);

		$( ".um-terms-conditions-content" ).toggle( "fast", function() {
			if( $( ".um-terms-conditions-content" ).is(':visible') ){
				me.text( me.data('toggle-hide') );
		   	}

			if( $( ".um-terms-conditions-content" ).is(':hidden') ){
				me.text( me.data('toggle-show') );
		  	}
		    
		});

	});


	$(document).on('click', "a.um-hide-terms" ,function() {

		var me = jQuery(this).parents('.um-field-area' ).find('a.um-toggle-terms');

		$( ".um-terms-conditions-content" ).toggle( "fast", function() {
			if( $( ".um-terms-conditions-content" ).is(':visible') ) {
				me.text( me.data('toggle-hide') );
		   	}

			if( $( ".um-terms-conditions-content" ).is(':hidden') ) {
				me.text( me.data('toggle-show') );
		  	}

		});

	});


})( jQuery );
