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
<div id="tve-lead_generation_select-component" class="tve-component" data-view="LeadGenerationSelect">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Main Options', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control gl-st-button-toggle-1 hide-states" data-view="DropdownPalettes"></div>
		<div class="tve-control tve-style-options no-space preview" data-view="StyleChange"></div>
		<div class="tve-control" data-key="SelectStylePicker" data-initializer="selectStylePicker"></div>
		<hr>
		<div class="tve-control" data-view="ShowLabel"></div>
		<div class="tve-control" data-key="Required" data-view="Checkbox"></div>
		<div class="tve-control mt-10" data-view="RowsWhenOpen"></div>
		<div class="tve-control" data-view="Placeholder"></div>
		<div class="tve-control" data-view="PlaceholderInput"></div>
		<div class="tve-control" data-key="DropdownIcon" data-initializer="dropdownIcon"></div>
		<div class="tve-control if-not-hamburger" data-view="DropdownAnimation"></div>
	</div>
</div>
