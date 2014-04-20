<?php
if ( function_exists( 'sharing_display' ) ) remove_filter( 'the_content', 'sharing_display', 19 );
if ( function_exists( 'sharing_display' ) ) remove_filter( 'the_excerpt', 'sharing_display', 19 );

//the_excerpt_max_charlength($options['excerpt']);

$original_blog_id = get_current_blog_id();
global $post;
foreach($options['blogs'] as $blog):
  if (!$blog->selected) {
    continue;
  }
  switch_to_blog($blog->blog_id);
  $posts = get_posts(
    array(
      'posts_per_page' => $options['number_of_posts']
    )
  );
  foreach($posts as $post):
    setup_postdata($post);
?>
  <div class="agnp-line">
    <?php if ($options['fields']['domain']): ?>
      <h2><a href="<?php the_permalink()?>" target="_blank"><?php the_title(); ?></a></h2>
    <?php endif; ?>

    <?php if ($options['fields']['domain']): ?>
      <div class="domain"><?php echo $blog->domain ?></div>
    <?php endif; ?>

    <?php if ($options['fields']['blogname']): ?>
      <div class="blogname"><?php echo get_bloginfo('name'); ?></div>
    <?php endif; ?>

    <?php if ($options['fields']['blogname_with_link']): ?>
      <div class="blogname">
        <a href="<?php echo get_bloginfo('url'); ?>"><?php echo get_bloginfo('name'); ?></a>
      </div>
    <?php endif; ?>

    <?php if ($options['fields']['published_at']): ?>
      <div class="published_at">
        <time datetime="<?php echo get_the_date('Y-m-d\TH:i:sP'); ?>" itemprop="datePublished">
          <?php echo get_the_date(); ?>
        </time>
      </div>
    <?php endif; ?>

    <?php if ($options['fields']['author_name']): ?>
      <div class="author">
        <?php the_author_posts_link() ?>
      </div>
    <?php endif; ?>

    <?php if ($options['fields']['featured_image']): ?>
    <?php
    the_post_thumbnail(
      'main-block',
      array(
        'class' => 'image',
        'title' => strip_tags(get_the_title()),
        'itemprop' => 'image'
      )
    ); ?>
    <?php endif; ?>

    <?php if ($options['fields']['content']): ?>
      <div class="excerpt">
        <?php echo wp_trim_words(get_the_excerpt(), $options['excerpt']); ?>
        <?php //the_excerpt(); ?>
      </div>
    <?php endif; ?>
  </div>
<?php
    wp_reset_postdata();
  endforeach;
endforeach;
switch_to_blog( $original_blog_id );
