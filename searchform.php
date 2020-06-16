<?php
/**
 * Template for displaying search forms in Ignition
 *
 * Can be gotten using get_search_form();
 * @package Ignition
 * @since 1.0
 * @version 1.0
 */
?>

<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo $unique_id; ?>">
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'ignition' ); ?></span>
	</label>
	<input <?php if(is_admin()){ echo 'disabled'; }?> type="text" id="<?php echo $unique_id; ?>" class="search-field"
	       placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'ignition' ); ?>"
	       value="<?php echo get_search_query(); ?>" name="s"/>
	<button <?php if(is_admin()){ echo 'disabled'; }?> type="submit" class="search-submit input-append"><span class="iconify" data-icon="ant-design:search-outlined" data-inline="true"></span>
        <span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'ignition' ); ?></span>
	</button>
</form>
