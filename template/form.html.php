<p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
</p>
<p>
  <label for="<?php echo $this->get_field_id('number_of_posts'); ?>"><?php _e('Number of Posts Per Site:'); ?></label>
  <input class="widefat" id="<?php echo $this->get_field_id('number_of_posts'); ?>" name="<?php echo $this->get_field_name('number_of_posts'); ?>" type="text" value="<?php echo esc_attr($number_of_posts); ?>">
</p>
<p>
  <label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e('Excerpt Length:'); ?></label>
  <input class="widefat" id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>" type="text" value="<?php echo esc_attr($excerpt); ?>">
</p>
<p>
  <label>
    <input type='hidden'
           value='0'
           name='<?php echo $this->get_field_name('display_blog_name_group'); ?>'
           />
    <input name="<?php echo $this->get_field_name('display_blog_name_group'); ?>"
           type="checkbox"
           value="1"
           <?php if ($display_blog_name_group): ?>checked='checked'<?php endif; ?>
           />
    <span>Display name of the blog on top</span>
  </label>
</p>
<p>
  <label><?php _e('Enabled Blogs:'); ?></label>
  <?php foreach($blogs as $blog): ?>
    <label style="padding-left: 1em; display: block;">
      <input type='hidden'
             value='0'
             name='<?php echo $this->get_field_name('blogs'); ?>[<?php echo $blog->blog_id; ?>]'
             />
      <input name="<?php echo $this->get_field_name('blogs'); ?>[<?php echo $blog->blog_id; ?>]"
             type="checkbox"
             value="1"
             <?php if ($blog->selected): ?>checked='checked'<?php endif; ?>
             />
      <span><?php echo $blog->domain; ?></span>
    </label>
  <?php endforeach; ?>
</p>
<p>
  <label><?php _e('Fields:'); ?></label>
  <?php foreach($fields as $field): ?>
    <label style="padding-left: 1em; display: block;">
      <input type='hidden'
             value='0'
             name='<?php echo $this->get_field_name('fields'); ?>[<?php echo $field->id; ?>]'
             />
      <input name='<?php echo $this->get_field_name('fields'); ?>[<?php echo $field->id; ?>]'
             type="checkbox"
             value="1"
             <?php if ($field->selected): ?>checked='checked'<?php endif; ?>
             />
      <span><?php echo $field->name; ?></span>
    </label>
  <?php endforeach; ?>
</p>
