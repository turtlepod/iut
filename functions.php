<?php
/**
 * Infinite Update Theme
**/


/* Add our update before it is set */
add_filter( 'pre_set_site_transient_update_themes', 'iut_update_themes', 10, 2 );


/**
 * Add this plugin to update array
 * @link https://developer.wordpress.org/reference/functions/set_site_transient/
 * @since 1.0.0
 */
function iut_update_themes( $value, $transient ){

	/* Check if "response" object is set */
	if( isset( $value->response ) ){

		/* Active theme folder */
		$this_theme = get_template();

		/* This theme update data */
		$update = array(
			'theme'       => $this_theme,
			'new_version' => '2.0.0',
			'url'         => 'https://github.com/turtlepod/iut',
			'package'     => 'https://github.com/turtlepod/iut/archive/master.zip',
		);

		/* Add to update data */
		$value->response[$this_theme] = $update;
	}

	return $value;
}

/* Folder Name Fix */
add_filter( 'upgrader_source_selection', 'iut_upgrader_source_selection', 10, 3);


/**
 * Fix theme folder
 * @since 1.0.0
 */
function iut_upgrader_source_selection( $source, $remote_source, $upgrader ){

	/* If theme name is set */
	if( isset( $upgrader->skin->theme_info->template ) ){

		/* Active theme folder */
		$this_theme = get_template();

		/* Only in current theme */
		if ( $this_theme == $upgrader->skin->theme_info->template ){

			/* Add notification feedback text */
			$upgrader->skin->feedback( 'Trying to rename theme folder...' );

			/* only if everything is set */
			if( isset( $source, $remote_source ) ){

				/* Set new source to correct theme folder */
				$new_source = $remote_source . '/' . $this_theme . '/';

				/* rename the folder */
				if( @rename( $source, $new_source ) ){
					$upgrader->skin->feedback( 'Renamed theme folder successfully.' );
					return $new_source;
				}
				/* Unable to rename the folder to correct theme folder */
				else{
					$upgrader->skin->feedback( 'Unable to rename updated theme.' );
					return new WP_Error();
				}
			}
			/* Fallback */
			else{
				$upgrader->skin->feedback( 'Source or Remote Source is unavailable.' );
			}
		}
	}
	return $source;
}









