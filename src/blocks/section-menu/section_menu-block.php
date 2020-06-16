<?php
/**
 * Paragraphs Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */


$fixed_at_top = get_field( 'fix_menu_at_top' );
$container    = get_field( 'container_class' );
if ( ! $container ) {
	$container = 'container';
}

?>


<section <?php ign_block_attrs( $block, 'section-menu' ); ?>>
    <div class="section-menu" <?php if ( $fixed_at_top ) {
		echo 'data-scrollanimation="fixed-at-top"';
	} ?>>
        <div class="horizontal-menu <?php echo $container; ?>">
            <nav>
				<?php
				if ( get_field( 'menu' ) == 'menu' ) {
					wp_nav_menu( array(
						'menu'        => get_field( 'wp_menu_name' ),
						'menu_id'     => 'menu-' . $block['id'],
						'container'   => '',
						'fallback_cb' => 'link_to_menu_editor'
					) );
				} else {
					?>
                    <ul class="menu">
						<?php
						while ( have_rows( 'custom_menu' ) ) : the_row();
							?>
							<?php $link = get_sub_field( 'link' ); ?>
							<?php if ( $link ): ?>
                                <li>
                                    <div class="menu-item-link">
                                        <a href="<?php echo esc_attr($link['url']); ?>"><?php echo esc_html($link['title']); ?></a>
                                    </div>
                                </li>

							<?php
							endif;
						endwhile;
						?>

                    </ul>
                    <!-- /.menu -->
					<?php
				} ?>

            </nav>
            <!-- /.menu -->
        </div>
        <!-- /.horizontal-menu -->
    </div>
</section>
