<?php

defined( 'ABSPATH' ) or exit();

?>

<div class="wrap">

	<!-- Logo Container & Image -->
	<div id="skystats-logo-container">
		<?php $mashboard_page_url = esc_attr( admin_url() . 'admin.php?page=skystats-mashboard' ); ?>
		<?php $brand_logo_url = esc_attr( get_option( 'skystats_brand_logo_image_url' ) ); ?>
		<a href="<?php echo $mashboard_page_url; ?>"><img src="<?php echo $brand_logo_url; ?>"></a>
	</div>

	<!-- Accordion -->
	<div id="skystats-settings-accordion">

		<!-- License Settings -->
		<h3 class="skystats-setting-header"><?php _e( 'License', SKYSTATS_TEXT_DOMAIN ); ?></h3>
		<div>
			<p id="skystats-successful-license-validation-request" class="skystats-success-message"></p>
			<p id="skystats-unsuccessful-license-validation-request" class="skystats-error-message"></p>
			<form method="POST" role="form">
				<div class="skystats-form-group">
					<label class="skystats-form-label" for="license_key"><?php _e( 'License Key', SKYSTATS_TEXT_DOMAIN ); ?></label>
					<?php $license_key = get_option( 'skystats_license_key' ); ?>
					<input type="text" class="skystats-form-control" name="license_key" id="license_key" required="required" value="<?php echo esc_attr( $license_key ); ?>">
					<div id="skystats-settings-validate-license-loading-container" class="skystats-loading-container">
						<img class="skystats-loading-image" src="<?php echo SKYSTATS_TEMPLATE_IMAGES_URL . 'loading-spin.svg'; ?>" width="64" height="64">
					</div>
				</div>
				<div class="skystats-form-group">
					<button id="validate_license" type="submit" class="skystats-button" name="validate_license" value="-1"><?php _e( 'Validate', SKYSTATS_TEXT_DOMAIN ); ?></button>
				</div>
			</form>
		</div>

		<!-- General Settings -->
		<h3 class="skystats-setting-header"><?php _e( 'Settings', SKYSTATS_TEXT_DOMAIN ); ?></h3>
		<div>
			<p><?php _e( 'Make your changes below then click "Save Settings" at the bottom of the page. You will need to refresh the page to see some of your changes.', SKYSTATS_TEXT_DOMAIN ); ?></p>
			<form method="POST" role="form">

				<!-- Show/Hide Mashboard Integrations/Cards -->
				<fieldset class="skystats-fieldset">
					<legend><?php _e( 'Show/Hide Mashboard Cards', SKYSTATS_TEXT_DOMAIN ); ?></legend>
					<?php
					/**
					 * Functions related to the cards on the Mashboard.
					 */
					require_once SKYSTATS_FUNCTIONS_PATH . 'mashboard_cards.php';
					$mashboard_cards_visibility_status = get_option( 'skystats_mashboard_cards_visibility_status' );
					ksort( $mashboard_cards_visibility_status );
					$mashboard_card_names = skystats_get_mashboard_card_names();
					$mashboard_cards_enabled_status = skystats_get_mashboard_cards_enabled_status();
					?>
					<p><?php _e( 'Select which cards you would like to be visible on the Mashboard. If a card is an integration, it\'s detail page will also be hidden.', SKYSTATS_TEXT_DOMAIN ); ?></p>
					<div class="form-group">
						<ul id="skystats-mashboard-cards-visibility-status-checkbox">
							<?php foreach ( $mashboard_cards_visibility_status as $card_identifier => $visible ) : ?>
								<?php if ( isset( $mashboard_cards_enabled_status[ $card_identifier ] ) && $mashboard_cards_enabled_status[ $card_identifier ] == '0' ) : ?>
									<?php continue; ?>
								<?php endif; ?>
								<li>
									<input type="checkbox" name="skystats_mashboard_cards_visiblity_status[]" value="<?php echo $card_identifier; ?>" <?php checked( $visible, '1' ); ?>> <?php echo $mashboard_card_names[ $card_identifier ]; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</fieldset>

				<!-- White Label -->
				<fieldset class="skystats-fieldset">
					<legend><?php _e( 'White Label', SKYSTATS_TEXT_DOMAIN ); ?></legend>
					<!-- Brand Name -->
					<div class="skystats-form-group">
						<label class="skystats-form-label" for="brand_name"><?php _e( 'Brand Name', SKYSTATS_TEXT_DOMAIN ); ?></label>
						<?php $brand_name = esc_attr( wp_strip_all_tags( stripslashes( get_option( 'skystats_brand_name' ) ) ) ); ?>
						<input type="text" class="skystats-form-control" name="brand_name" id="brand_name" required="required" value="<?php echo $brand_name; ?>">
					</div>
					<hr class="skystats-setting-hr">
					<!-- Brand Menu Name -->
					<div class="skystats-form-group">
						<label class="skystats-form-label" for="brand_menu_name"><?php _e( 'Brand Menu Name', SKYSTATS_TEXT_DOMAIN ); ?></label>
						<?php $brand_menu_name = esc_attr( wp_strip_all_tags( stripslashes( get_option( 'skystats_brand_menu_name' ) ) ) ); ?>
						<input type="text" class="skystats-form-control" name="brand_menu_name" id="brand_menu_name" required="required" value="<?php echo $brand_menu_name; ?>">
					</div>
					<!-- Mashboard Menu Name -->
					<div class="skystats-form-group">
						<label class="skystats-form-label" for="skystats_mashboard_menu_name"><?php _e( 'Mashboard Menu Name', SKYSTATS_TEXT_DOMAIN ); ?></label>
						<?php $mashboard_menu_name = esc_attr( wp_strip_all_tags( stripslashes( get_option( 'skystats_mashboard_menu_name' ) ) ) ); ?>
						<input type="text" class="skystats-form-control" name="skystats_mashboard_menu_name" id="skystats_mashboard_menu_name" required="required" value="<?php echo $mashboard_menu_name; ?>">
					</div>
					<hr class="skystats-setting-hr">
					<!-- Brand Logo Image URL -->
					<div class="skystats-form-group">
						<label class="skystats-form-label" for="brand_logo_image_url"><?php _e( 'Brand Logo Image', SKYSTATS_TEXT_DOMAIN ); ?></label>
						<?php $brand_logo_url = esc_attr( get_option( 'skystats_brand_logo_image_url' ) ); ?>
						<div class="skystats-setting-image-container">
							<input type="text" class="skystats-form-control skystats-setting-image-url" name="brand_logo_image_url" id="brand_logo_image_url" required="required" value="<?php echo $brand_logo_url; ?>">
						</div>
						<div class="skystats-setting-image-upload-container">
							<button class="skystats-button skystats-upload-image-button"><?php _e( 'Upload Image', SKYSTATS_TEXT_DOMAIN ); ?></button>
							<input class="skystats-upload-image" type="file" name="files[]">
						</div>
					</div>
					<hr class="skystats-setting-hr">
					<!-- Brand Background Image URL -->
					<div class="skystats-form-group">
						<label class="skystats-form-label" for="brand_background_image_url"><?php _e( 'Brand Background Image', SKYSTATS_TEXT_DOMAIN ); ?></label>
						<?php $brand_background_image_url = esc_attr( get_option( 'skystats_brand_background_image_url' ) ); ?>
						<div class="skystats-setting-image-container">
							<input type="text" class="skystats-form-control skystats-setting-image-url" name="brand_background_image_url" id="brand_background_image_url" value="<?php echo $brand_background_image_url; ?>">
						</div>
						<div class="skystats-setting-image-upload-container">
							<button class="skystats-button skystats-upload-image-button"><?php _e( 'Upload Image', SKYSTATS_TEXT_DOMAIN ); ?></button>
							<input class="skystats-upload-image" type="file" name="files[]">
						</div>
					</div>
					<hr class="skystats-setting-hr">
					<!-- Brand Background Color -->
					<div class="skystats-form-group">
						<label class="skystats-form-label" for="brand_background_color"><?php _e( 'Brand Background Color Hex Value', SKYSTATS_TEXT_DOMAIN ); ?></label>
						<p><?php _e( 'The Brand Background Color can be used instead of a background image and it is also used instead of any background image for small screen sizes.', SKYSTATS_TEXT_DOMAIN ); ?></p>
						<?php $brand_background_color = esc_attr( get_option( 'skystats_brand_background_color' ) ); ?>
						<input type="text" class="skystats-form-control" name="brand_background_color" id="brand_background_color" required="required" value="<?php echo $brand_background_color; ?>">
					</div>
					<!-- Date Range Label Color -->
					<div class="skystats-form-group">
						<label class="skystats-form-label" for="skystats_date_range_label_color"><?php _e( 'Date Range Label Color', SKYSTATS_TEXT_DOMAIN ); ?></label>
						<p><?php _e( 'The color to be used for the date range labels at the top of the Mashboard page or an integration\'s detail page e.g. "Date Range" and "Chart Plotting Frequency" (if viewing the Google Analytics detail page).', SKYSTATS_TEXT_DOMAIN ); ?></p>
						<?php $date_range_label_color = esc_attr( get_option( 'skystats_date_range_label_color' ) ); ?>
						<input type="text" class="skystats-form-control" name="skystats_date_range_label_color" id="skystats_date_range_label_color" required="required" value="<?php echo $date_range_label_color; ?>">
					</div>
				</fieldset>

				<fieldset class="skystats-fieldset">
					<legend><?php _e( 'Stats Access', SKYSTATS_TEXT_DOMAIN ); ?></legend>
					<div class="skystats-form-group">
						<label class="skystats-form-label" for="skystats_reports_users_allowed_access"><?php _e( 'Roles', SKYSTATS_TEXT_DOMAIN ); ?></label>
						<?php $skystats_reports_users_allowed_access = get_option( 'skystats_reports_users_allowed_access' ); ?>
						<?php $roles = (array) get_editable_roles(); ?>
						<p><?php _e( 'Select which roles are allowed to see and access the Mashboard and detail pages. By default, or if this field is left blank, only administrators will be able to view the stats. When selecting a role, be sure to remember to include your own role, so that you don\'t accidentally lock yourself out of the stats pages. Also, only the selected role(s) will be able to view the Stats pages, they don\'t apply upwards (e.g. selecting editor does not allow admins to access the Stats pages).', SKYSTATS_TEXT_DOMAIN ); ?></p>
						<select class="skystats-form-control" name="skystats_reports_users_allowed_access" id="skystats_reports_users_allowed_access" required="required" data-placeholder="<?php _e( 'Click here to select a role', SKYSTATS_TEXT_DOMAIN ); ?>" multiple>
							<?php foreach ( $roles as $identifier => $role ): ?>
								<?php if ( isset( $role['name'] ) ): ?>
									<option value="<?php echo esc_attr( $identifier ); ?>" <?php if ( in_array( $identifier, $skystats_reports_users_allowed_access ) ): ?>selected="selected"<?php endif; ?>><?php echo esc_attr( $role['name'] ); ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</div>
				</fieldset>

				<fieldset class="skystats-fieldset">
					<legend><?php _e( 'Settings Access', SKYSTATS_TEXT_DOMAIN ); ?></legend>
					<div class="skystats-form-group">
						<label class="skystats-form-label" for="skystats_settings_users_allowed_access"><?php _e( 'Users', SKYSTATS_TEXT_DOMAIN ); ?></label>
						<?php $skystats_settings_users_allowed_access = get_option( 'skystats_settings_users_allowed_access' ); ?>
						<p><?php _e( 'Select which users are allowed to view and edit the Settings.  By default, or if this field is left blank, only administrators will be able to view the settings. When selecting a user, be sure to remember to include yourself, so that you don\'t accidentally lock yourself out of the settings.', SKYSTATS_TEXT_DOMAIN ); ?></p>
						<?php
						global $wpdb;
						$results = $wpdb->get_results( "SELECT `ID`, `user_login` FROM `{$wpdb->users}`" );
						$users = array();
						if ( is_array( $results ) && ! empty( $results ) ) {
							foreach ( $results as $user ) {
								if ( ! is_object( $user ) ) {
									continue;
								}
								if ( ! isset( $user->ID, $user->user_login ) ) {
									continue;
								}
								$users[ $user->ID ] = $user->user_login;
							}
						}
						?>
						<select class="skystats-form-control" name="skystats_settings_users_allowed_access" id="skystats_settings_users_allowed_access" required="skystats_settings_users_allowed_access" data-placeholder="<?php _e( 'Click here to select a user', SKYSTATS_TEXT_DOMAIN ); ?>" multiple>
							<?php foreach ( $users as $user_id => $user_login_name ) : ?>
								<option value="<?php echo esc_attr( $user_id ); ?>" <?php if ( in_array( $user_id, $skystats_settings_users_allowed_access ) ): ?>selected="selected"<?php endif; ?>><?php echo $user_login_name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</fieldset>

				<!-- Default Dashboard -->
				<fieldset class="skystats-fieldset">
					<legend><?php _e( 'Default Dashboard', SKYSTATS_TEXT_DOMAIN ); ?></legend>
					<p><?php _e( 'Select whether users are sent to the SkyStats Mashboard or the WordPress Dashboard when they login or revisit the WordPress dashboard (they will still be able to access the WordPress dashboard). In order for a user to be redirected to the SkyStats Mashboard, their role must be selected in the Stats Access setting above.', SKYSTATS_TEXT_DOMAIN ); ?></p>
					<div class="form-group">
						<select name="default_dashboard" id="default_dashboard">
							<?php $default_dashboard = get_option( 'skystats_default_dashboard' ); ?>
							<option value="skystats_mashboard" <?php selected( $default_dashboard, 'skystats_mashboard' ); ?>><?php _e( 'SkyStats Mashboard', SKYSTATS_TEXT_DOMAIN ); ?></option>
							<option value="wordpress_dashboard"<?php selected( $default_dashboard, 'wordpress_dashboard' ); ?>><?php _e( 'WordPress Dashboard', SKYSTATS_TEXT_DOMAIN ); ?></option>
						</select>
					</div>
				</fieldset>

				<!-- Caching -->
				<fieldset class="skystats-fieldset">
					<legend><?php _e( 'Caching', SKYSTATS_TEXT_DOMAIN ); ?></legend>
					<p><?php _e( 'Select whether you would like to used a cached version of the data for your integrations if it is available, which dramatically increases the performance of the requests.', SKYSTATS_TEXT_DOMAIN ); ?></p>
					<div class="form-group">
						<select name="cache_mode" id="cache_mode">
							<?php $cache_mode = get_option( 'skystats_cache_mode' ); ?>
							<option value="enabled" <?php selected( $cache_mode, 'enabled' ); ?>>
								<?php _e( 'Enabled', SKYSTATS_TEXT_DOMAIN ); ?>
							</option>
							<option value="disabled" <?php selected( $cache_mode, 'disabled' ); ?>>
								<?php _e( 'Disabled', SKYSTATS_TEXT_DOMAIN ); ?>
							</option>
						</select>
					</div>
				</fieldset>

				<div id="skystats-save-settings-result" class="skystats-form-group skystats-success-message">
					<p><?php _e( 'Settings saved successfully. You will need to reload the page to see some of your changes.', SKYSTATS_TEXT_DOMAIN ); ?></p>
				</div>

				<div class="skystats-form-group">
					<button type="submit" class="skystats-button" id="save_settings" name="save_settings" value="-1"><?php _e( 'Save Settings', SKYSTATS_TEXT_DOMAIN ); ?></button>
					<div id="skystats-settings-save-settings-loading-container" class="skystats-loading-container">
						<img class="skystats-loading-image" src="<?php echo SKYSTATS_TEMPLATE_IMAGES_URL . 'loading-spin.svg'; ?>" width="64" height="64">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>