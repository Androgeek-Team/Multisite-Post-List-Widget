<?php if ( function_exists( 'sharing_display' ) ) remove_filter( 'the_content', 'sharing_display', 19 ); ?>
<?php if ( function_exists( 'sharing_display' ) ) remove_filter( 'the_excerpt', 'sharing_display', 19 ); ?>

<?php $original_blog_id = get_current_blog_id(); ?>
<?php global $post; ?>
<?php foreach($posts as $post): ?>
<?php switch_to_blog($post->blog_id); ?>
<?php setup_postdata($post) ?>
  <div class="agnp-line">
    <h2><a href="<?php the_permalink()?>" target="_blank"><?php the_title(); ?></a></h2>
    <div class="domain"><?php echo $post->domain ?></div>
    <?php the_post_thumbnail('main-block', array('class' => 'image', 'title' => strip_tags(get_the_title()), 'itemprop' => 'image')); ?>
    <div class="excerpt"><?php the_excerpt(); ?></div>
  </div>
<?php wp_reset_postdata(); ?>
<?php endforeach; ?>
<?php switch_to_blog( $original_blog_id ); ?>
