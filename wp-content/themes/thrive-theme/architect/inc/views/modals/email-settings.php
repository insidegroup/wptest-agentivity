<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
?>
<h2 class="tcb-modal-title">
	<?php echo __( 'Compose Email', 'thrive-cb' ); ?>
</h2>
<div class="tve-email-setup">
	<div class="tve-advanced-controls extend-grey m-0">
		<div class="dropdown-header open" data-prop="primary">
			<span class="dropdown-title"><?php echo __( 'Primary Email', 'thrive-cb' ); ?></span>
		</div>
		<div class="dropdown-content pt-0 pb-0" data-prop="primary">
			<div class="input-container-with-label mt-5">
				<span class="tve-email-label"><?php echo __( 'To', 'thrive-cb' ); ?></span>
				<input type="text" class="tve-email-data change input" data-fn="setValue" data-prop="to">
				<span class="click tve-add-more ml-5" data-fn="toggleRecipients">+ CC/BCC</span>
			</div>
			<span class="tcb-error" data-error-prop="to"></span>
			<div class="tve-email-more-recipients tcb-hidden">
				<div class="input-container-with-label mt-5">
					<span class="tve-email-label"><?php echo __( 'CC', 'thrive-cb' ); ?></span>
					<input type="text" class="tve-email-data change input" data-fn="setValue" data-prop="cc">

				</div>
				<span class="tcb-error" data-error-prop="cc"></span>
				<div class="input-container-with-label mt-5">
					<span class="tve-email-label"><?php echo __( 'BCC', 'thrive-cb' ); ?></span>
					<input type="text" class="tve-email-data change input" data-fn="setValue" data-prop="bcc">
				</div>
				<span class="tcb-error" data-error-prop="bcc"></span>
			</div>
			<div class="input-container-with-label mt-5">
				<span class="tve-email-label"><?php echo __( 'From Name', 'thrive-cb' ); ?></span>
				<input type="text" class="tve-email-data change input" data-fn="setValue" data-prop="from_name">
			</div>
			<span class="tcb-error" data-error-prop="from_name"></span>
			<div class="input-container-with-label mt-5">
				<span class="tve-email-label"><?php echo __( 'From Email', 'thrive-cb' ); ?></span>
				<input type="text" class="tve-email-data change input" data-fn="setValue" data-prop="from_email">
			</div>
			<span class="tcb-error" data-error-prop="from_email"></span>
			<div class="input-container mt-15">
				<input type="text" class="tve-email-data change input prevent-focus" data-fn="setValue" data-prop="email_subject">
			</div>
			<div class="input-container mt-15">
				<textarea class="tve-email-data change input prevent-focus" data-fn="setValue" data-prop="email_message"></textarea>
			</div>

			<div class="tve-email-shortcodes mt-10 mb-10">
				<span class="tve-email-label mr-15"><?php echo __( 'Add Shortcodes', 'thrive-cb' ); ?></span>
				<div class="tve-email-shortcode">
					<select class="tve-select-shortcode">
						<optgroup label="Standard Fields">
							<option data-label="<?php esc_attr_e( 'List all the fields and data captured in the form', 'thrive-cb' ); ?>" value="[all_form_fields]">[all_form_fields]</option>
							<option data-label="<?php esc_attr_e( 'The first name of visitor', 'thrive-cb' ); ?>" value="[first_name]">[first_name]</option>
							<option data-label="<?php esc_attr_e( 'The email of visitor', 'thrive-cb' ); ?>" value="[user_email]">[user_email]</option>
							<option data-label="<?php esc_attr_e( 'The phone of visitor', 'thrive-cb' ); ?>" value="[phone]">[phone]</option>
						</optgroup>
						<optgroup label="Custom Fields">
						</optgroup>
						<optgroup label="Other">
							<option data-label="<?php esc_attr_e( 'Date of submission', 'thrive-cb' ); ?>" value="[date]">[date]</option>
							<option data-label="<?php esc_attr_e( 'Time of submission', 'thrive-cb' ); ?>" value="[time]">[time]</option>
							<option data-label="<?php esc_attr_e( 'The title of your Wordpress site', 'thrive-cb' ); ?>" value="[wp_site_title]">[wp_site_title]</option>
							<option data-label="<?php esc_attr_e( 'Page containing the form', 'thrive-cb' ); ?>" value="[page_url]">[page_url]</option>
							<option data-label="<?php esc_attr_e( 'IP address of visitor', 'thrive-cb' ); ?>" value="[ip_address]">[ip_address]</option>
							<option data-label="<?php esc_attr_e( '"Chrome 3.3.2" for example', 'thrive-cb' ); ?>" value="[device_settings]">[device_settings]</option>
							<option data-label="<?php esc_attr_e( 'The slug of form e.g "/form/123"', 'thrive-cb' ); ?>" value="[form_url_slug]">[form_url_slug]</option>
						</optgroup>

					</select>
					<span class="tve-lg-shortcode-select-arrow"><?php tcb_icon( 'a_down' ); ?></span>
				</div>
				<div class="tve-email-add-shortcode click tve-button ml-15 ghost blue" data-fn="addShortcode" data-target="email_message"><?php echo __( 'Insert Field', 'thrive-cb' ); ?></div>
			</div>
		</div>
	</div>
	<div class="tve-advanced-controls extend-grey  m-0 mt-15">
		<div class="dropdown-header" data-prop="confirmation">
			<span class="dropdown-title"><?php echo __( 'Send confirmation email to user that submitted the form', 'thrive-cb' ); ?></span>
			<div class="tve-email-enable-confirmation"></div>
		</div>
		<div class="dropdown-content pt-0 pb-0" data-prop="confirmation">
			<div class="input-container mt-5">
				<input type="text" class="tve-email-data change input prevent-focus" data-fn="setValue" data-prop="email_confirmation_subject">
			</div>
			<div class="input-container mt-15">
				<textarea class="tve-email-data change input prevent-focus" data-fn="setValue" data-prop="email_confirmation_message"></textarea>
			</div>
			<div class="tve-email-shortcodes mt-10 mb-10" data-prop="confirmation">
				<span class="tve-email-label mr-15"><?php echo __( 'Add Shortcodes', 'thrive-cb' ); ?></span>
				<div class="tve-email-shortcode">
					<select class="tve-select-shortcode">
						<optgroup label="Standard Fields">
							<option data-label="<?php esc_attr_e( 'List all the fields and data captured in the form', 'thrive-cb' ); ?>" value="[all_form_fields]">[all_form_fields]</option>
							<option data-label="<?php esc_attr_e( 'The first name of visitor', 'thrive-cb' ); ?>" value="[first_name]">[first_name]</option>
							<option data-label="<?php esc_attr_e( 'The email of visitor', 'thrive-cb' ); ?>" value="[user_email]">[user_email]</option>
							<option data-label="<?php esc_attr_e( 'The phone of visitor', 'thrive-cb' ); ?>" value="[phone]">[phone]</option>
						</optgroup>
						<optgroup label="Custom Fields">
						</optgroup>
						<optgroup label="Other">
							<option data-label="<?php esc_attr_e( 'Date of submission', 'thrive-cb' ); ?>" value="[date]">[date]</option>
							<option data-label="<?php esc_attr_e( 'Time of submission', 'thrive-cb' ); ?>" value="[time]">[time]</option>
							<option data-label="<?php esc_attr_e( 'The title of your Wordpress site', 'thrive-cb' ); ?>" value="[wp_site_title]">[wp_site_title]</option>
							<option data-label="<?php esc_attr_e( 'Page containing the form', 'thrive-cb' ); ?>" value="[page_url]">[page_url]</option>
							<option data-label="<?php esc_attr_e( 'IP address of visitor', 'thrive-cb' ); ?>" value="[ip_address]">[ip_address]</option>
							<option data-label="<?php esc_attr_e( '"Chrome 3.3.2" for example', 'thrive-cb' ); ?>" value="[device_settings]">[device_settings]</option>
							<option data-label="<?php esc_attr_e( 'The slug of form e.g "/form/123"', 'thrive-cb' ); ?>" value="[form_url_slug]">[form_url_slug]</option>
						</optgroup>

					</select>
					<span class="tve-lg-shortcode-select-arrow"><?php tcb_icon( 'a_down' ); ?></span></div>
				<div class="tve-email-add-shortcode click tve-button ml-15 ghost blue" data-fn="addShortcode" data-target="email_confirmation_message"><?php echo __( 'Insert Field', 'thrive-cb' ); ?></div>
			</div>
		</div>
	</div>
</div>

<div class="tcb-modal-footer clearfix flex-end">
	<button type="button" class="justify-self-start tve-button medium tcb-modal-cancel ghost grey"><?php echo __( 'Cancel', 'thrive-cb' ); ?></button>
	<button type="button" class="tcb-right tve-button medium tcb-modal-save">
		<?php echo __( 'Save', 'thrive-cb' ); ?>
	</button>
</div>
