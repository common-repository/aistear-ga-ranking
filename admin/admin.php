<?php

/* Add menu displays */
add_action( 'admin_menu', 'aistear_ga_ranking_admin_menu' );

function aistear_ga_ranking_admin_menu() {
	add_menu_page (
		'GA Ranking',
		'GA Ranking Settings',
		'administrator',
		'aistear_ga_ranking',
		'aistear_ga_ranking_setting'
	);
}


/* Setting screen display */
function aistear_ga_ranking_setting() {
	$logo_url = plugin_dir_url( __FILE__ ) . 'img/logo.png';

	if ( isset( $_POST['aistear_ga_ranking_options'] ) ) {
		check_admin_referer( 'ga_setting' );
		$options = $_POST['aistear_ga_ranking_options'];
		$transient_key = 'aistear_ga_ranking_' . $options[profile_id];
		delete_transient( $transient_key );
		update_option( 'aistear_ga_ranking_options', $options);

		echo '<strong>Save the settings</strong>';
	}
?>
<div class="wrap">
	<?php screen_icon( 'aistear' ); ?>
	
	<h2>Aistear Google Analytics Ranking</h2>
	<form action="" method="post">
		<?php wp_nonce_field( 'ga_setting' ); ?>
		<?php $options = get_option( 'aistear_ga_ranking_options' ); ?>

		<table class="form-table">
			<tr>
				<th><label for="aistear_ga_ranking_email">Google Account E-Mail</label></th>
				<td><input name="aistear_ga_ranking_options[email]" type="text" size="40" id="aistear_ga_ranking_email" value="<?php echo esc_attr( $options['email'] ); ?>" /></td>
			</tr>
			<tr>
				<th><label for="aistear_ga_ranking_pass">Google Account Password</label></th>
				<td><input name="aistear_ga_ranking_options[pass]" type="password" size="40" id="aistear_ga_ranking_pass" value="<?php echo esc_attr( $options['pass'] ); ?>" /></td>
			</tr>
			<tr>
				<th><label for="aistear_ga_ranking_profile_id">Google Analytics Profile ID</label></th>
				<td><input name="aistear_ga_ranking_options[profile_id]" type="text" size="40" id="aistear_ga_ranking_profile_id" value="<?php echo esc_attr( $options['profile_id'] ); ?>" /></td>
			</tr>
			<tr>
				<th><label for="aistear_ga_ranking_period">Period(day)</label></th>
				<td><input name="aistear_ga_ranking_options[period]" type="text" size="6" id="aistear_ga_ranking_period" value="<?php echo esc_attr( $options['period'] ); ?>" /></td>
			</tr>
			<tr>
				<th><label for="aistear_ga_ranking_count">Display Count</label></th>
				<td><input name="aistear_ga_ranking_options[count]" type="text" size="6" id="aistear_ga_ranking_count" value="<?php echo esc_attr( $options['count'] ); ?>" /></td>
			</tr>
		</table>
				
		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="Submit" />
		</p>
	</form>
	
	<div id="developper-info">
		<a href="http://aistear.info/"><img src="<?php echo $logo_url; ?>" title="Aistear" /></a>
	</div>
</div>
<?php
}

function aistear_icon_style() {
		$icon_url = plugin_dir_url( __FILE__ ) . 'img/icon.png';
?>
<style type="text/css" charset="utf-8">
#icon-aistear {
	background: url( <?php echo esc_url( $icon_url ); ?> ) no-repeat center;
}
#developper-info {
	text-align: right;
}
</style>
<?php
}
add_action( 'admin_print_styles', 'aistear_icon_style' );