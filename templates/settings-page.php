<?php
/**
 * Plugin setting fields
 *
 * @package Text_Wrapper
 */

?>

<div class="wrap">
	<h1><?php esc_html_e( 'Text Wrapper Settings', 'text-wrapper' ); ?></h1>
	<form method="post" action="">
		<?php wp_nonce_field( 'ctw_save_settings', '_wpnonce' ); ?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="ctw_tag"><?php esc_html_e( 'HTML Tag', 'text-wrapper' ); ?></label>
				</th>
				<td>
					<input type="text" id="ctw_tag" name="ctw_settings[tag]" value="<?php echo esc_attr( $settings['tag'] ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="ctw_class"><?php esc_html_e( 'CSS Class', 'text-wrapper' ); ?></label>
				</th>
				<td>
					<input type="text" id="ctw_class" name="ctw_settings[class]" value="<?php echo esc_attr( $settings['class'] ); ?>" />
				</td>
			</tr>
		</table>
		<?php submit_button( __( 'Save Settings', 'text-wrapper' ) ); ?>
	</form>
</div>
