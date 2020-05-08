<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
} ?>

<div class="sf-accounts-global-settings">
	<input type="hidden" name="socialflow_nonce" value="{{ $ctrl.settings.wpnonce }}" />

	<div class="sf_compose sf-checkbox-block">
		<input 
			id="sf_compose" 
			type="checkbox" 
			name="socialflow[global][compose_now]" 
			ng-model="$ctrl.settings.compose_now" 
			ng-true-value="1" 
			ng-false-value="0"
			value="1"
		/>
		<label for="sf_compose">
			<span ng-if="'publish' == $ctrl.post.status">
				<?php esc_html_e( 'Send to SocialFlow when the post is updated', 'socialflow' ); ?>
			</span>
			<span ng-if="'publish' != $ctrl.post.status">
				<?php esc_html_e( 'Send to SocialFlow when the post is published', 'socialflow' ); ?>
			</span>
		</label>
	</div>

<!--	<div class="sf-media-toggle-container sf-checkbox-block" ng-if="'attachment' != $ctrl.post.type"> -->
<!--		<input -->
<!--			id="sf_media_compose" -->
<!--			type="checkbox" -->
<!--			name="socialflow[global][compose_media]" -->
<!--			ng-model="$ctrl.settings.compose_media"-->
<!--			ng-true-value="1" -->
<!--			ng-false-value="0"-->
<!--			ng-change="$ctrl.onChangeMediaCompose()"-->
<!--			ng-disabled="$ctrl.disableComposeMedia()"-->
<!--			value="1"-->
<!--		/>-->
<!--		<label for="sf_media_compose">-->
<!--		</label>-->
<!--	</div>-->

	<div class="sf-autcomplete-toggle-container sf-checkbox-block">
		<input
			id="sf_disable_autcomplete"
			type="checkbox"
			name="socialflow[global][disable_autcomplete]"
			ng-model="$ctrl.settings.disable_autcomplete"
			ng-true-value="1"
			ng-false-value="0"
			value="1"
		/>
		<label for="sf_disable_autcomplete">
			<?php esc_html_e( 'Disable autocomplete', 'socialflow' ); ?>
		</label>
	</div>

	<div class="sf-autofill-button-container">
		<button 
			id="sf_autofill" 
			ng-click="$ctrl.clickAutocomple( $event, '<?php esc_html_e( 'Are you sure you would like to update social text?', 'socialflow' ); ?>' )"
			class="sf-button sf-button-blue"
		>
			<?php esc_html_e( 'Revert', 'socialflow' ); ?>
		</button>
	</div>
</div>
