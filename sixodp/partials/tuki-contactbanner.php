<?php
  /**
  * Contact box on Tuki frontpage.
  */

$support_user = get_user_by('login', 'support');
?>

<div class="wrapper--contactbanner">
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="contactbanner">
          <i class="material-icons contactbanner__icon--chat">chat</i>
          <div class="row justify-content-center">
            <h1 class="h1 heading"><?php _e('Any questions?', 'sixodp');?></h1>
            <a type="button" href="mailto:<?php echo $support_user->user_email?>"
               class="btn btn-transparent btn--banner"><?php _e('Contact us', 'sixodp');?>&nbsp
              <i class="material-icons">arrow_forward</i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
