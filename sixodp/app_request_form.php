<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * Template Name: Sovellustoive-lomake
 *
 * @package WordPress
 * @subpackage Sixodp
 */

$content = '';
$email = '';
$name = '';

if (isset($_POST['app_request_submit_form'])) {
  $content = $_POST['app_request_content'];
  $title = $_POST['app_request_title'];
  $name = $_POST['app_request_name'];
  $email = $_POST['app_request_email'];

  $errors = array();

  if (empty($content)) $errors[] = __('Content is required', 'sixodp');
  if (empty($title)) $errors[] = __('Title is required', 'sixodp');
  if (empty($email)) $errors[] = __('Email is required', 'sixodp');
  else if (is_email($email) === false) $errors[] = __('Email must be valid', 'sixodp');
  if (empty($name) or strlen($name) < 3) $errors[] = __('Name is required and must be over 3 characters long', 'sixodp');

  if (sizeof($errors) == 0) {  
    wp_insert_post(array(
      'post_content' => $content,
      'post_title' => $title,
      'post_type' => 'app_request',
      'post_status' => 'pending',
      'meta_input' => array(
        'name' => $name,
        'email' => $email,
      )
    ));

    $welcome_page = true;
  }
}

get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main site-main--home" role="main">
    <?php
      get_template_part('partials/header-logos');
    ?>

    <div class="container">
      <?php

      if ($welcome_page && sizeof($errors) == 0) {
        _e('Thank you for submission.');
      }
      else {
        if (sizeof($errors) > 0) {
          foreach($errors as $error) {
            echo '<p>'. $error .'</p>';
          } 
        }
        ?>
        <form action="" method="POST">
          <label for="app_request_title"><?php _e('Title', 'sixodp');?></label>
          <input type="text" name="app_request_title" id="app_request_title" class="form-control" value="<?php echo $name; ?>" />
          <label for="app_request_content"><?php _e('Your request', 'sixodp');?></label>
          <?php
          wp_editor($content, 'app_request_content', array(
            'textarea_rows' => 5,
            'media_buttons' => false,
            'quicktags' => false
          ));
          ?>
          <label for="app_request_name"><?php _e('Name', 'sixodp');?></label>
          <input type="text" name="app_request_name" id="app_request_name" class="form-control" value="<?php echo $name; ?>" />
          <label for="app_request_email"><?php _e('Email', 'sixodp');?></label>
          <input type="text" name="app_request_email" id="app_request_email" class="form-control" value="<?php echo $email; ?>"/>
          <button type="submit" class="btn btn-lg btn-primary" name="app_request_submit_form"><?php _e('Submit', 'sixodp');?></button>
        </form>
        <?php
      }

      ?>
      
    </div>
    
  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>