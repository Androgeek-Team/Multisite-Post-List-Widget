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
  function __construct()
  {
    global $wpdb;
    $this->database = $wpdb;
    parent::__construct(
      'AG_PostListWidget',
      __('AG Network Posts', 'agnp_widget_domain'),
      array( 'description' => __( 'Display posts across the whole network.', 'agnp_widget_domain' ), )
    );
  }

  public function widget( $args, $instance )
  {
    $title = apply_filters( 'widget_title', $instance['title'] );
    echo $args['before_widget'];
    if ( ! empty( $title ) ) {
      echo $args['before_title'] . $title . $args['after_title'];
    }
    $this->displayWidget();
    echo $args['after_widget'];
  }

  private function displayWidget()
  {
    $blogs = $this->getBlogList();
    $posts = array();
    foreach($blogs as $blog) {
      if ($blog->blog_id == 5) {
        continue;
      }
      $tableName = "wp_" . $blog->blog_id. "_posts";
      if ($blog->blog_id == 1) {
        $tableName = "wp_posts";
      }
      if ($this->database->posts === $tableName) {
        continue;
      }
      $query = "select *, '{$blog->blog_id}' as blog_id from {$tableName} where post_status = 'publish' and post_type = 'post' order by post_date desc limit 1;";
      $post = $this->database->get_row($query);
      $post->domain = $blog->domain;
      $posts[$blog->blog_id] = $post;
    }
    require_once('template/widget.html.php');
  }

  private function getBlogList()
  {
    $results = $this->database->get_results('select * from wp_blogs;');
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
