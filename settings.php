<?php
defined('ABSPATH') or die('No script kiddies please!');
?>
<div class="wrap">
  <form method="post" action="options.php">
  	 <?php wp_nonce_field('update-options'); ?>
    <h1>Author Bind Settings</h1>
    <label for="author_bind_user_id">User ID:</label>
    <input type="text" value="<?php echo get_option('author_bind_user_id'); ?>" name="author_bind_user_id" />
    <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /> 
    <input type="hidden" name="page_options" value="author_bind_user_id" />
    <input type="hidden" name="action" value="update" />
  </form>
</div>
