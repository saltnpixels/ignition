<?php
//basic simple paragraph section. No frills
$class = get_sub_field( 'class' );
?>

<section id="section-<?php echo $section_hash; ?>" class="<?php echo $class; ?> section-paragraph">
	<?php the_sub_field( 'paragraph' ); ?>
</section>



