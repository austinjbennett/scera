<?php

?>

<div class="event-tabs">
	<div class="tab-menu">

		<?php $has_cast = (get_field('cast')||get_field('cast_copy_and_paste') ? '':'current')?>

		<?php if(get_field('cast')||get_field('cast_copy_and_paste')) {?><a class="tabber current" data-target="#cast">CAST</a><?php }?>
		<?php if(get_field('tab_2_title') && get_field('tab_2_content')): ?><a class="tabber <?php echo $has_cast?>" data-target="#tab2"><?php the_field('tab_2_title'); ?></a><?php endif; ?>
		<?php if(get_field('tab_3_title') && get_field('tab_3_content')): ?><a class="tabber" data-target="#tab3"><?php the_field('tab_3_title'); ?></a><?php endif; ?>
		<?php if(get_field('tab_4_title') && get_field('tab_4_content')): ?><a class="tabber" data-target="#tab4"><?php the_field('tab_4_title'); ?></a><?php endif; ?>
		<?php if(get_field('tab_5_title') && get_field('tab_5_content')): ?><a class="tabber" data-target="#tab5"><?php the_field('tab_5_title'); ?></a><?php endif; ?>
	</div>

	<?php 		if(  get_field('cast') ): ?>

	<div id="cast" class="tab-block current">
		<?php
			// Start Cast loop
			while ( has_sub_field('cast') ):
				?><div class="cast-wrap"><?php
				$cast = get_sub_field('cast_member');
				if( $cast ):
					foreach( $cast as $p ):

						if( get_field('picture', $p->ID) ): ?>
						<div class="picture-wrap">
							<img src="<?php the_field('picture', $p->ID); ?>" alt="" />
						</div><?php
						endif; ?>

						<div class="cast-content-wrap">
							<h2><?php echo get_the_title( $p->ID ); ?></h2>
							<?php if( get_sub_field('role') ): ?><h4><?php the_sub_field('role'); ?></h4><?php endif; ?>
							<div class="cast-content entry-content"><?php echo wp_trim_words(get_post_field('post_content', $p->ID), 40); ?></div>
							<a class="bio" href="<?php echo get_permalink( $p->ID ); ?>">Read full bio >></a>
							<?php
							if( get_sub_field('featured_dates') ):
								?><div class="fdates"><h5>Featured Dates: </h5><?php
								while( has_sub_field('featured_dates') ):
									if(get_sub_field('month')): the_sub_field('month'); endif;
									if(get_sub_field('days')): the_sub_field('days'); ?>&nbsp;&nbsp;<?php endif;
								endwhile;
								?></div><?php
							endif; ?>
						</div><?php

					endforeach;
				endif;
				?></div><?php
			endwhile;
			// End Cast Loop
			?></div><?php
		elseif( get_field('cast_copy_and_paste')): ?>
			<div id="cast" class="tab-block current">
			<?php the_field('cast_copy_and_paste');?>
			</div>
		<?php endif; ?>
	<?php if(get_field('tab_2_content') && trim(str_replace(array('&nbsp;', '<p>', '</p>'), '', get_field('tab_2_content') ))) : ?>
	<div id="tab2" class="tab-block <?php echo $has_cast?>">
		<?php the_field('tab_2_content'); ?>
	</div>
	<?php endif; ?>
	<?php if(get_field('tab_3_content') && trim(str_replace(array('&nbsp;', '<p>', '</p>'), '', get_field('tab_3_content')))) : ?>
	<div id="tab3" class="tab-block">
		<?php the_field('tab_3_content'); ?>
	</div>
	<?php endif; ?>
	<?php if(get_field('tab_4_content') && trim(str_replace(array('&nbsp;', '<p>', '</p>'), '', get_field('tab_4_content')))) : ?>
	<div id="tab4" class="tab-block">
		<?php the_field('tab_4_content'); ?>
	</div>
	<?php endif; ?>
	<?php if(get_field('tab_5_content') && trim(str_replace(array('&nbsp;', '<p>', '</p>'), '', get_field('tab_5_content')))) : ?>
	<div id="tab5" class="tab-block">
		<?php the_field('tab_5_content'); ?>
	</div>
	<?php endif; ?>
</div>
