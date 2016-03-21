<?php

	/***
	***	@Get all bulk actions
	***/
	add_filter('um_admin_bulk_user_actions_hook', 'um_admin_bulk_user_actions_hook', 1);
	function um_admin_bulk_user_actions_hook( $actions ){

		$actions = null;

		$actions['um_approve_membership'] = array( 'label' => __('Approve Membership','ultimatemember') );
		$actions['um_reject_membership'] = array( 'label' => __('Reject Membership','ultimatemember') );
		$actions['um_put_as_pending'] = array( 'label' => __('Put as Pending Review','ultimatemember') );
		$actions['um_resend_activation'] = array( 'label' => __('Resend Activation E-mail','ultimatemember') );
		$actions['um_deactivate'] = array( 'label' => __('Deactivate','ultimatemember') );
		$actions['um_reenable'] = array( 'label' => __('Reactivate','ultimatemember') );
		//$actions['um_delete'] = array( 'label' => __('Delete','ultimatemember') );
		
		return $actions;
	}
	
	/***
	***	@Main admin user actions
	***/
	add_filter('um_admin_user_actions_hook', 'um_admin_user_actions_hook', 1);
	function um_admin_user_actions_hook( $actions ){

		$actions = null;
		
		um_fetch_user( um_profile_id() );

		if ( current_user_can('manage_options') ) {
		
			if ( um_user('account_status') == 'awaiting_admin_review' ){
				$actions['um_approve_membership'] = array( 'label' => __('Approve Membership','ultimatemember') );
				$actions['um_reject_membership'] = array( 'label' => __('Reject Membership','ultimatemember') );
			}
			
			if ( um_user('account_status') == 'rejected' ) {
				$actions['um_approve_membership'] = array( 'label' => __('Approve Membership','ultimatemember') );
			}
			
			if ( um_user('account_status') == 'approved' ) {
				$actions['um_put_as_pending'] = array( 'label' => __('Put as Pending Review','ultimatemember') );
			}
			
			if ( um_user('account_status') == 'awaiting_email_confirmation' ) {
				$actions['um_resend_activation'] = array( 'label' => __('Resend Activation E-mail','ultimatemember') );
			}
			
			if (  um_user('account_status') != 'inactive' ) {
				$actions['um_deactivate'] = array( 'label' => __('Deactivate this account','ultimatemember') );
			}
			
			if (  um_user('account_status') == 'inactive' ) {
				$actions['um_reenable'] = array( 'label' => __('Reactivate this account','ultimatemember') );
			}
			
			if ( um_current_user_can( 'delete', um_profile_id() ) ) {
				$actions['um_delete'] = array( 'label' => __('Delete this user','ultimatemember') );
			}
			
		}
		
		if ( current_user_can('delete_users') ) {
			$actions['um_switch_user'] = array( 'label' => __('Login as this user','ultimatemember') );
		}
		
		
		
		return $actions;
	}


	/**
	 * Filter user basename
	 * @param  string $value 
	 * @return string
	 * @hook_filter: um_clean_user_basename_filter       
	 */
	add_filter('um_clean_user_basename_filter','um_clean_user_basename_filter',2,10);
	function um_clean_user_basename_filter( $value, $raw ){
		global $wpdb;
		$permalink_base =  um_get_option('permalink_base');
		
		switch( $permalink_base ){
				case 'name':
					
					$slugname = $wpdb->get_var(
						$wpdb->prepare(
							"SELECT meta_value FROM ".$wpdb->usermeta." WHERE meta_key = %s ", 
							'um_user_profile_url_slug_name_'.$raw,
							$raw
						)
					);

					$value = $slugname;
					if( ! empty( $value ) && strrpos( $value ,".") > -1 ){
						$value = str_replace( '.', ' ', $value );
					}

					// Checks if last name has a dash
					if( ! empty( $value ) && strrpos( $value ,"_") > -1 ){
						$value = str_replace( '_', '. ', $value );
					}
					
				break;

				default:
				// Checks if last name has a dash
				if( ! empty( $value ) && strrpos( $value ,"_") > -1 && substr( $value , "_") == 1 ){
					$value = str_replace( '_', '-', $value );
				}
				break;
		}

		return $value;

	}


	/**
	 * Filter before update profile to force utf8 strings
	 * @param  mixed $value
	 * @return mixed
	 * @uses   hook filter: um_is_selected_filter_value
	 */
	add_filter('um_before_update_profile','um_before_update_profile',2,10);
	function um_before_update_profile( $changes, $user_id ){
		global $ultimatemember;

		if( ! um_get_option('um_force_utf8_strings') ) 
			return $changes;

		foreach( $changes as $key => $value ) {
			$changes[ $key ] = um_force_utf8_string( $value );

		}
		
		return $changes;
	}
