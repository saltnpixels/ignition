<?php
//basic simple paragraph section. No frills
$class = get_sub_field( 'class' );
?>

<section id="section-<?php echo $section_hash; ?>" class="<?php echo $class; echo ' ' . get_row_layout(); ?> ">
	<?php the_sub_field( 'paragraph' ); ?>
</section>



