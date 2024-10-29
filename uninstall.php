<?php

if( !defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();

	$options = get_option( 'aistear_ga_ranking_options' );
	$transient_key = 'aistear_ga_ranking_' . $options[profile_id];
	delete_transient( $transient_key );

	delete_option( 'aistear_ga_ranking_options' );

?>