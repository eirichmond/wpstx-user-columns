<?php
/**
 * Plugin Name:     Wpstx User Columns
 * Plugin URI:      https://advent.elliottrichmond.co.uk
 * Description:     To add a custom column in the WordPress Users list
 * Author:          Elliott Richmond
 * Author URI:      https://elliottrichmond.co.uk
 * Text Domain:     wpstx-user-columns
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wpstx_User_Columns
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The function hooked to the filter hook manage_users_columns that simply
 * adds a new column by key and value to the existing core columns for the users list
 * 
 * @author  Elliott Richmond <me@elliottrichmond.co.uk>
 * @since 0.1.0
 * @param array   $columns    Existing columns array.
 * @return string $columns    The new columns array.
 * 
 */
function wpstx_custom_user_columns($columns) {
    $columns['select_role'] = 'Preference';
    return $columns;
}
add_filter('manage_users_columns', 'wpstx_custom_user_columns', 10, 1);


/**
 * The function hooked to the filter hook manage_users_custom_column that displays
 * the information we want to see by the in individual user by ID or row listed
 * 
 * @author  Elliott Richmond <me@elliottrichmond.co.uk>
 * @since 0.1.0
 * @param string   $val    		   Existing columns array.
 * @param string   $column_name    The key value of the column that we will switch to set in our function 'wpstx_custom_user_columns'.
 * @param int      $user_id    	   The users ID used to identify the row the list is going to output.
 * 
 * @return string  $val    		   The existing or new string we want to display in the users list.
 * 
 */
function wpstx_custom_user_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
		case 'select_role' :
			$meta_key = get_user_meta($user_id, 'select_role', true);
			$val = wpstx_select_role_output($meta_key);
			return $val;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'wpstx_custom_user_row', 10, 3 );


/**
 * This is an extra function to change a lowercase meta key
 * from the standard WordPress way of storing keys and changing
 * them to our prefered value as defined by the key value
 * 
 * @author  Elliott Richmond <me@elliottrichmond.co.uk>
 * @since 0.1.0
 * @param string  $meta_key    The incoming meta key.
 * @return string $preference  The output text.
 * 
 */
function wpstx_select_role_output($meta_key) {
	// die early if empty
	if ($meta_key == '') {
		return;
	}
	$output = array(
		'subscriber' => 'Recieve snippets',
		'contributor' => 'Submit snippets',
	);
	$preference = $output[$meta_key];
	return $preference;
}