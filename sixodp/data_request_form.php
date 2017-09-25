<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * Template Name: Datatoive-lomake
 *
 * @package WordPress
 * @subpackage Sixodp
 */

$content = '';
$email = '';
$name = '';

if (isset($_POST['data_request_submit_form'])) {
  $content = $_POST['data_request_content'];
  $title = $_POST['data_request_title'];
  $name = $_POST['data_request_name'];
  $email = $_POST['data_request_email'];

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
      'post_type' => 'data_request',
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

      <h1 class="page-heading"><?php _e('New Data Request', 'sixodp') ?></h1>

      <a href="<?php echo get_post_type_archive_link( 'data_request' ); ?>" class="btn btn-small btn-secondary btn--request-show-all"><?php _e('All data requests') ?> &raquo;</a>

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
          <p><?php _e('Your data request will be moderated, it may be modified or combined with similar requests. Moderated request will be published on this site and will be forwarded to person responsible for the data if possible.'); ?>

          <div class="row">
            <div class="col-xs-12">
              <div class="control-group control-full">
                <label class="control-label" for="data_request_title"><span title="This field is required" class="control-required">*</span> <?php _e('Title', 'sixodp');?></label>
                <div class="controls ">             
                  <input type="text" name="data_request_title" id="data_request_title" class="form-control" value="<?php echo $title; ?>" placeholder="<?php _e('eg. A descriptive title') ?>" />
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12">
              <div class="control-group control-full">
                <label class="control-label" for="data_request_content"><span title="This field is required" class="control-required">*</span> <?php _e('Your request', 'sixodp');?></label>
                <div class="controls">             
                  <?php
                  wp_editor($content, 'data_request_content', array(
                    'textarea_rows' => 5,
                    'media_buttons' => false,
                    'quicktags' => false
                  ));
                  ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-md-8">
              <div class="control-group control-medium">
                <label class="control-label" for="data_request_name"><span title="This field is required" class="control-required">*</span> <?php _e('Name', 'sixodp');?></label>
                <div class="controls ">             
                  <input type="text" name="data_request_name" id="data_request_name" class="form-control" value="<?php echo $name; ?>" />
                </div>
              </div>
              <div class="field-assistive-text">
                <?php _e('Your name will not be published with data request.'); ?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-md-8">
              <div class="control-group control-medium">
                <label class="control-label" for="data_request_email"><span title="This field is required" class="control-required">*</span> <?php _e('Email', 'sixodp');?></label>
                <div class="controls ">             
                  <input type="text" name="data_request_email" id="data_request_email" class="form-control" value="<?php echo $email; ?>" />
                </div>
              </div>

              <div class="field-assistive-text">
               <?php _e('Your email will not be published with data request.'); ?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12">
              <hr>
              <button type="submit" class="btn btn-primary" name="data_request_submit_form"><?php _e('Submit', 'sixodp');?></button>
            </div>
          </div>
        </form>
        <?php
      }

      ?>
      
    </div>
    
  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
