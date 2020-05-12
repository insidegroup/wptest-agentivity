<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package TCB2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}
?>
<div id="tve-lead_generation_radio_option-component" class="tve-component" data-view="LeadGenerationRadioOption">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Main Options', 'thrive-cb' ); ?>
		<i></i>
	</div>

	<div class="dropdown-content">
		<div class="tve-control gl-st-button-toggle-1 hide-states" data-view="RadioPalettes"></div>
		<div class="tve-control tve-style-options no-space preview" data-view="StyleChange"></div>
		<div class="tve-control" data-key="RadioStylePicker" data-initializer="radioStylePicker"></div>
		<hr>
		<div class="tve-control" data-view="LabelAsValue"></div>
		<div class="tve-control" data-view="InputValue"></div>
		<div class="tve-control" data-view="SetAsDefault"></div>
		<div class="tve-control" data-view="RadioSize"></div>
	</div>
</div>
