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


$container = get_field( 'container_class' );

?>


<section <?php ign_block_attrs($block); ?>>
	<div class="section-menu" <?php if ( $fixed_at_top ) {
		echo 'data-scrollanimation="fixed-at-top"';
	} ?>>
		<div class="horizontal-menu <?php echo $container; ?>">
			<nav>
				<?php
			if(get_field('menu') == 'menu'){
				wp_nav_menu( array(
						'menu' => get_field('wp_menu_name'),
						'menu_id' => 'menu-' . $block_id,
						'container' => '',
						'fallback_cb' => 'link_to_menu_editor'
					) );
				}
			else{
				?>
				<ul class="menu">
					<?php
					while ( have_rows( 'custom_menu' ) ) : the_row();
						?>
						<li>
							<div class="menu-item-link">
								<a href="<?php echo ign_get_link_field('link'); ?>"><?php echo get_sub_field( 'menu_title' ); ?></a>
							</div>
						</li>

					<?php
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
