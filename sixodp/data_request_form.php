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

  if (empty($content)) $errors['content'] = __('Content is required', 'sixodp');
  if (empty($title)) $errors['title'] = __('Title is required', 'sixodp');
  if (empty($email)) $errors['email'] = __('Email is required', 'sixodp');
  else if (is_email($email) === false) $errors['email'] = __('Email must be valid', 'sixodp');
  if (empty($name) or strlen($name) < 3) $errors['name'] = __('Name is required and must be over 3 characters long', 'sixodp');

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
      <div class="row">
        <div class="sidebar col-sm-3">
          <ul>
            <li class="sidebar-item"><a href="<?php echo get_post_type_archive_link( 'data_request' ); ?>"><?php _e('All data requests') ?></a></li>
        </div>
        <div class="article__wrapper col-xs-12 col-sm-9">
          <h1 class="page-heading"><?php _e('New Data Request', 'sixodp') ?></h1>
          <?php

          if ($welcome_page && sizeof($errors) == 0) {
            _e('Thank you for submission.');
          }
          else {
            ?>
            <form action="" method="POST">
              <p><?php _e('Your data request will be moderated, it may be modified or combined with similar requests. Moderated request will be published on this site and will be forwarded to person responsible for the data if possible.'); ?></p>

              <div class="row">
                <div class="col-xs-12">
                  <div class="control-group control-full <?php if (isset($errors['title'])) echo "error" ?>">
                    <label class="control-label" for="data_request_title"><span title="This field is required" class="control-required">*</span> <?php _e('Title', 'sixodp');?></label>
                    <div class="controls">             
                      <input type="text" name="data_request_title" id="data_request_title" class="form-control" value="<?php echo $title; ?>" placeholder="<?php _e('eg. A descriptive title') ?>" />
                    </div>
                    <?php if (isset($errors['title'])) echo '<span class="error-block">'. $errors['title'] .'</span>'; ?>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12">
                  <div class="control-group control-full <?php if (isset($errors['content'])) echo "error" ?>">
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
                    <?php if (isset($errors['content'])) echo '<span class="error-block">'. $errors['content'] .'</span>'; ?>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12 col-md-8">
                  <div class="control-group control-medium <?php if (isset($errors['name'])) echo "error" ?>">
                    <label class="control-label" for="data_request_name"><span title="This field is required" class="control-required">*</span> <?php _e('Name', 'sixodp');?></label>
                    <div class="controls ">             
                      <input type="text" name="data_request_name" id="data_request_name" class="form-control" value="<?php echo $name; ?>" />
                    </div>
                    <div class="editor-info-block">
                      <?php _e('Your name will not be published with data request.'); ?>
                    </div>
                    <?php if (isset($errors['name'])) echo '<span class="error-block">'. $errors['name'] .'</span>'; ?>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12 col-md-8">
                  <div class="control-group control-medium <?php if (isset($errors['email'])) echo "error" ?>">
                    <label class="control-label" for="data_request_email"><span title="This field is required" class="control-required">*</span> <?php _e('Email', 'sixodp');?></label>
                    <div class="controls ">             
                      <input type="text" name="data_request_email" id="data_request_email" class="form-control" value="<?php echo $email; ?>" />
                    </div>
                    <div class="editor-info-block">
                     <?php _e('Your email will not be published with data request.'); ?>
                    </div>
                    <?php if (isset($errors['email'])) echo '<span class="error-block">'. $errors['email'] .'</span>'; ?>
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
      </div>
    </div>
  </main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
