<?php
  /**
  * Popular tags -partial.
  */
?>

<div class="tagbox">
  <h4 class="tagbox-header">Suositut avainsanat</h4>
  <ul class="tag-list">
    <?php
      foreach(get_popular_tags() as $key => $val) :
        echo '<li><a class="tag" href="/dataset?tags='.$key.'">'.$key.' ('.$val.')</a></li>';
      endforeach;
    ?>
  </ul>
</div>
