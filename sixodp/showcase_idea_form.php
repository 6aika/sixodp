<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * Template Name: Sovellusidea-lomake
 *
 * @package WordPress
 * @subpackage Sixodp
 */

$content = '';
$email = '';
$name = '';

if (isset($_POST['showcase_idea_submit_form'])) {
  $content = $_POST['showcase_idea_content'];
  $title = $_POST['showcase_idea_title'];
  $name = $_POST['showcase_idea_name'];
  $email = $_POST['showcase_idea_email'];

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
      'post_type' => 'showcase_idea',
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
  <main id="main" role="main">
    <?php
      get_template_part('partials/header-logos');
    ?>

    <div class="container">
      <h1 class="page-heading"><?php _e('New showcase idea', 'sixodp') ?></h1>

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
          <p><?php _e('By filling this form you can submit your application request. ', 'sixodp') ?></p>

          <div class="col-xs-12">
            <div class="control-group control-full">
              <label class="control-label" for="showcase_idea_title"><span title="This field is required" class="control-required">*</span> <?php _e('Title', 'sixodp');?></label>
              <div class="controls ">             
                <input type="text" name="showcase_idea_title" id="showcase_idea_title" class="form-control" value="<?php echo $name; ?>" placeholder="<?php _e('eg. A descriptive title') ?>" />
              </div>
            </div>
          </div>

          <div class="col-xs-12">
            <div class="control-group control-full">
              <label class="control-label" for="showcase_idea_content"><span title="This field is required" class="control-required">*</span> <?php _e('Your request', 'sixodp');?></label>
              <div class="controls">             
                <?php
                wp_editor($content, 'showcase_idea_content', array(
                  'textarea_rows' => 5,
                  'media_buttons' => false,
                  'quicktags' => false
                ));
                ?>
              </div>
            </div>
          </div>

          <div class="col-xs-12 col-md-8">
            <div class="control-group control-medium">
              <label class="control-label" for="showcase_idea_name"><span title="This field is required" class="control-required">*</span> <?php _e('Name', 'sixodp');?></label>
              <div class="controls ">             
                <input type="text" name="showcase_idea_name" id="showcase_idea_name" class="form-control" value="<?php echo $name; ?>" />
              </div>
            </div>
          </div>

          <div class="col-xs-12 col-md-8">
            <div class="control-group control-medium">
              <label class="control-label" for="showcase_idea_email"><span title="This field is required" class="control-required">*</span> <?php _e('Email', 'sixodp');?></label>
              <div class="controls ">             
                <input type="text" name="showcase_idea_email" id="showcase_idea_email" class="form-control" value="<?php echo $name; ?>" />
              </div>
            </div>
          </div>

          <div class="col-xs-12">
            <hr>
            <button type="submit" class="btn btn-primary" name="showcase_idea_submit_form"><?php _e('Submit', 'sixodp');?></button>
          </div>
        </form>
        <?php
      }

      ?>
      
    </div>
    
  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>