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
<div id="tve-lead_generation_select_option-component" class="tve-component" data-view="LeadGenerationSelectOption">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Main Options', 'thrive-cb' ); ?>
		<i></i>
	</div>

	<div class="dropdown-content">
		<div class="tve-control" data-view="LabelAsValue"></div>
		<div class="tve-control" data-view="InputValue"></div>
		<div class="tve-control" data-view="SetAsDefault"></div>
	</div>
</div>
