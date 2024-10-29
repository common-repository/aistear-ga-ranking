<?php
/*
Plugin Name: Aistear GA Ranking
Plugin URI: http://aistear.info/engineer/
Description: Display plugin rankings using Google Analytics.
Version: 1.1
Author: Endoh Shingo(mebius0)
Author URI: http://aistear.info/engineer/
License: GPL2

Copyright 2013 Endoh Shingo (email : s.endoh@aistear.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once( WP_PLUGIN_DIR . '/aistear-ga-ranking/admin/admin.php' );
require_once( WP_PLUGIN_DIR . '/aistear-ga-ranking/class/gapi.class.php' );

function aistear_ga_ranking( $args ) {

	$defaults = array(
			'container' => 'ol',
			'container_class' => 'ga-ranking',
			'before' => '<li class="ga-ranking-list ga-ranking-list-%1$d">',
			'after' => '</li>',
			'echo' => 'true',
		);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );
	
	$options = get_option( 'aistear_ga_ranking_options' );
	
	$ga_email = $options[email];
	$ga_pass = $options[pass];
	$ga_profile_id = $options[profile_id];
	$dimensions = array( 'pageTitle', 'pagePath' );
	$metrics = array( 'pageviews', 'visits' );
	$sort_metric = '-pageviews';
	$filter="";
	
	$start = '-'. $options[period] . ' day';
	$start_date = date('Y-m-d', strtotime($start));
	$end_date = date('Y-m-d', strtotime('-1 day'));
	
	$start_index = 1;
	$max_results = $options[count];
	
	try {
		$ga = new gapi( $ga_email, $ga_pass );
	} catch ( Exception $e ) {
		if ( is_user_logged_in() ) {
			echo "<p>Authentication Failed</p>";
			echo "<p>" . $e->getMessage() . "</p>";
		}
	}
	
	if ( !empty($ga) ) {
		$ga->requestReportData(
			$ga_profile_id,
		    $dimensions,
		    $metrics,
			$sort_metric,
			$filter,
			$start_date,
			$end_date,
			$start_index,
		    $max_results
		);
		
		$transient_key = 'aistear_ga_ranking_' . $ga_profile_id;
		$transients = get_transient( $transient_key );
		
		if ( $transients === false ) {
			$rank = 1;
			$container_class = esc_attr( $container_class );
			$transients = '<' . $container . ' class="' . $container_class . '">';
			foreach( $ga->getResults() as $result ) {
				$sbefore = sprintf( $before, $rank );
				$transients .= $sbefore . '<a href="' . $result->getPagepath() . '" title="' . $result->getPagetitle() . '">' . $result->getPagetitle() . '</a>'. $after;
				$rank++;
			}
			$transients .= '</' . $container . '>';
			
			set_transient( $transient_key, $transients, 24*60*60 );
		}
		
		$output = $transients;
		if (!$echo) { return $output; }
		echo $output;
	}
	
}


function aistear_ga_ranking_deactivation() {
	$options = get_option( 'aistear_ga_ranking_options' );
	$transient_key = 'aistear_ga_ranking_' . $options[profile_id];
	delete_transient( $transient_key );

	delete_option( 'aistear_ga_ranking_options' );
}
register_deactivation_hook( __FILE__, 'aistear_ga_ranking_deactivation');


/* Widget */
class Aistear_GA_Ranking_WidgetItem extends WP_Widget {

	function __construct() {
		$widget_options = array( 'classname' => 'ga-ranking-widget', 'description' => 'Displays the ranking that is based on the data of Google Analytics' );
		parent::__construct('aistear_ga_rankig', __('Google Analytics Ranking'), $widget_options);
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $before_widget;
		if ( $title ) {
        		echo $before_title . $title . $after_title;
		}
		
		aistear_ga_ranking();
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {	
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	
	function form($instance) {
		$title = esc_attr($instance['title']);
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
<?php
	}
}
add_action('widgets_init', create_function('', 'return register_widget( "Aistear_GA_Ranking_WidgetItem" );'));