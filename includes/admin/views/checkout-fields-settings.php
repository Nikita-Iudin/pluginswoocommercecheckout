<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<p class="description"><?php esc_html_e( 'Drag and drop to reorder fields. Edit label, placeholder, priority, and toggle visibility / required status for each field.', 'custom-store-plugin' ); ?></p>

	<div class="csp-checkout-fields-wrapper">
		<ul id="csp-checkout-fields-list" class="csp-sortable">
			<?php
			$index = 0;
			foreach ( $fields as $key => $field ) :
				$enabled  = ! empty( $field['enabled'] );
				$required = ! empty( $field['required'] );
			?>
			<li class="csp-field-item" data-key="<?php echo esc_attr( $key ); ?>">
				<div class="csp-field-header">
					<span class="csp-drag-handle dashicons dashicons-menu"></span>
					<label class="csp-field-toggle">
						<input type="checkbox" class="csp-field-enabled" <?php checked( $enabled ); ?> />
						<strong class="csp-field-key"><?php echo esc_html( $key ); ?></strong>
					</label>
					<button type="button" class="csp-field-toggle-details button button-link">
						<span class="dashicons dashicons-arrow-down-alt2"></span>
					</button>
				</div>

				<div class="csp-field-details" style="display:none;">
					<table class="form-table">
						<tr>
							<th><label><?php esc_html_e( 'Label', 'custom-store-plugin' ); ?></label></th>
							<td>
								<input type="text" class="regular-text csp-field-label" value="<?php echo esc_attr( $field['label'] ?? '' ); ?>" />
							</td>
						</tr>
						<tr>
							<th><label><?php esc_html_e( 'Placeholder', 'custom-store-plugin' ); ?></label></th>
							<td>
								<input type="text" class="regular-text csp-field-placeholder" value="<?php echo esc_attr( $field['placeholder'] ?? '' ); ?>" />
							</td>
						</tr>
						<tr>
							<th><label><?php esc_html_e( 'Priority', 'custom-store-plugin' ); ?></label></th>
							<td>
								<input type="number" class="small-text csp-field-priority" value="<?php echo esc_attr( $field['priority'] ?? 50 ); ?>" min="1" max="200" step="1" />
							</td>
						</tr>
						<tr>
							<th><label><?php esc_html_e( 'Required', 'custom-store-plugin' ); ?></label></th>
							<td>
								<input type="checkbox" class="csp-field-required" <?php checked( $required ); ?> />
							</td>
						</tr>
					</table>
					<input type="hidden" class="csp-field-type" value="<?php echo esc_attr( $field['type'] ?? 'billing' ); ?>" />
				</div>
			</li>
			<?php $index++; endforeach; ?>
		</ul>
	</div>

	<p class="submit">
		<button type="button" class="button button-primary" id="csp-save-checkout-fields">
			<?php esc_html_e( 'Save Fields', 'custom-store-plugin' ); ?>
		</button>
		<button type="button" class="button" id="csp-reset-checkout-fields">
			<?php esc_html_e( 'Reset to Defaults', 'custom-store-plugin' ); ?>
		</button>
		<span class="spinner csp-spinner" style="float:none;margin-top:4px"></span>
		<span class="csp-save-status"></span>
	</p>
</div>
