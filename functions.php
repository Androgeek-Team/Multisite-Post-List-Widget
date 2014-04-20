<?php
/**
 * Plugin Name: Multisite Post List Widget
 * Description: Display posts across the whole network
 * Version: 0.1
 * Author: Androgeek Team
 * Author URI: http://code.androgeek.hu/
 * License: GPL2
 */

class AG_PostListWidget extends WP_Widget
{
  private $database;
  private $originalExcerptLength = 55;

  function __construct()
  {
    global $wpdb;
    $this->database = $wpdb;
    parent::__construct(
      'AG_PostListWidget',
      __('Multisite Posts', 'agnp_widget_domain'),
      array( 'description' => __( 'Display posts across the whole network.', 'agnp_widget_domain' ), )
    );
    add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
  }

  public function register_plugin_styles()
  {
    wp_register_style('multisite-post-list', plugins_url('multisite-post-list/css/plugin.css'));
    wp_enqueue_style('multisite-post-list');
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance )
  {
    $title = apply_filters( 'widget_title', $instance['title'] );
    echo $args['before_widget'];
    if ( ! empty( $title ) ) {
      echo $args['before_title'] . $title . $args['after_title'];
    }
    $this->displayWidget($instance);
    echo $args['after_widget'];
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance )
  {
    if (isset($instance[ 'title' ])) {
      $title = $instance['title'];
    } else {
      $title = __('Posts across our Network', 'agnp_widget_domain');
    }

    if (isset($instance[ 'excerpt' ])) {
      $excerpt = $instance['excerpt'];
    } else {
      $excerpt = 55;
    }

    if (isset($instance[ 'display_blog_name_group' ])) {
      $display_blog_name_group = $instance['display_blog_name_group'];
    } else {
      $display_blog_name_group = 0;
    }

    if (isset($instance[ 'number_of_posts' ])) {
      $number_of_posts = $instance['number_of_posts'];
    } else {
      $number_of_posts = 1;
    }

    if (isset($instance['blogs'])) {
      $blogs = $instance['blogs'];
    } else {
      $blogs = $this->getBlogList();
    }

    if (isset($instance['fields'])) {
      $fields = $instance['fields'];
    } else {
      $fields = $this->getFieldList();
    }

    require('template/form.html.php');
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = (!empty($new_instance['title']))
      ? strip_tags($new_instance['title'])
      : '';

    $instance['display_blog_name_group'] = (!empty($new_instance['display_blog_name_group']))
      ? (int)strip_tags($new_instance['display_blog_name_group'])
      : 0;
    $instance['excerpt'] = (!empty($new_instance['excerpt']))
      ? (int)strip_tags($new_instance['excerpt'])
      : 55;
    $instance['number_of_posts'] = (!empty($new_instance['number_of_posts']))
      ? (int)strip_tags($new_instance['number_of_posts'])
      : 1;

    if (isset($new_instance['blogs']) && is_array($new_instance['blogs'])) {
      $blogs = $this->getBlogList();
      foreach($blogs as $blog) {
        $blog->selected = $new_instance['blogs'][$blog->blog_id];
      }
      $instance['blogs'] = $blogs;
    }

    if (isset($new_instance['fields']) && is_array($new_instance['fields'])) {
      $fields = $this->getFieldList();
      foreach($fields as $field) {
        $field->selected = $new_instance['fields'][$field->id];
      }
      $instance['fields'] = $fields;
    }

    return $instance;
  }

  private function displayWidget($instance)
  {
    $options = $instance;
    require_once('template/widget.html.php');
  }

  private function getFieldList()
  {
    return array(
      'title' => (object) array(
        'id' => 'title',
        'name' => 'Title',
        'selected' => 1
      ),
      'content' => (object) array(
        'id' => 'content',
        'name' => 'Content',
        'selected' => 1
      ),
      'featured_image' => (object) array(
        'id' => 'featured_image',
        'name' => 'Featured Image',
        'selected' => 0
      ),
      'domain' => (object) array(
        'id' => 'domain',
        'name' => "Domain Name",
        'selected' => 0
      ),
      'blogname' => (object) array(
        'id' => 'blogname',
        'name' => "Blog Name",
        'selected' => 0
      ),
      'blogname_with_link' => (object) array(
        'id' => 'blogname_with_link',
        'name' => "Blog Name as Link",
        'selected' => 0
      ),
      'author_name' => (object) array(
        'id' => 'author_name',
        'name' => "Author's Name",
        'selected' => 0
      ),
      'published_at' => (object) array(
        'id' => 'published_at',
        'name' => "Published At",
        'selected' => 0
      )
    );
  }

  private function getBlogList()
  {
    $results = $this->database->get_results('select blog_id, domain, 1 as selected from wp_blogs;');
    return $results;
  }
}

class AndrogeekPostList
{
  private $database;
  public function __construct($database)
  {
    $this->database = $database;
    add_action('widgets_init', array($this, 'registerWidgets'));
  }

  public function registerWidgets()
  {
    register_widget('AG_PostListWidget');
  }
}

$agpn = new AndrogeekPostList($wpdb);
