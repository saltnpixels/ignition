<?php
//Scrolls to each section on the page

if ( have_rows( 'section_names' ) ) {
	?>
	<section class="alignfull section-menu-holder no-gutters">
		<div class="section-menu" data-scrollanimation="fixed-at-top">
			<div class="horizontal-menu container">
				<nav>
					<ul class="menu">
						<?php
						while ( have_rows( 'section_names' ) ) : the_row();
							?>
							<li>
								<div class="menu-item-link">
									<a href="#section-<?php the_sub_field( 'section_id' ) ?>"><?php the_sub_field( 'section_name' ); ?></a>
								</div>
							</li>

						<?php
						endwhile;
						?>

					</ul>
					<!-- /.menu -->
				</nav>
				<!-- /.menu -->
			</div>
			<!-- /.horizontal-menu -->
		</div>
	</section>
	<!-- /.alignfull -->
	<?php
}
?>

<script>

	document.addEventListener('DOMContentLoaded', function () {
		//fill menu with the sections that exist
		const MenuSize = document.querySelector('.section-menu').offsetHeight;
		const sectionMenuItems = document.querySelectorAll('.container-content .section-menu .menu a');

		for (const section of sectionMenuItems) {
			section.setAttribute('data-anchor-offset', MenuSize);
		}

	});


</script>