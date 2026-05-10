<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form id="csp-general-settings-form" method="post">
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="csp-enabled"><?php esc_html_e( 'Enable Plugin', 'custom-store-plugin' ); ?></label>
				</th>
				<td>
					<select id="csp-enabled" name="enabled">
						<option value="yes" <?php selected( $settings['enabled'] ?? 'yes', 'yes' ); ?>><?php esc_html_e( 'Yes', 'custom-store-plugin' ); ?></option>
						<option value="no" <?php selected( $settings['enabled'] ?? 'yes', 'no' ); ?>><?php esc_html_e( 'No', 'custom-store-plugin' ); ?></option>
					</select>
					<p class="description"><?php esc_html_e( 'Enable or disable all checkout customizations.', 'custom-store-plugin' ); ?></p>
				</td>
			</tr>
		</table>

		<p class="submit">
			<button type="submit" class="button button-primary" id="csp-save-general">
				<?php esc_html_e( 'Save Changes', 'custom-store-plugin' ); ?>
			</button>
			<span class="spinner csp-spinner" style="float:none;margin-top:4px"></span>
			<span class="csp-save-status"></span>
		</p>
	</form>
</div>
