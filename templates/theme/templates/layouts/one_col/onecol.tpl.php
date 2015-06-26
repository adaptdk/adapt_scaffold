<div<?php print $css_id ? " id=\"$css_id\"" : ''; ?> class="onecol">

  <?php if($content['content-primary']): ?>
    <section class="content-primary">
      <div class="inner">
        <?php print $content['content-primary']; ?>
      </div>
    </section>
  <?php endif;?>

  <?php if($content['content-secondary']): ?>
    <section class="content-secondary">
      <div class="inner">
        <?php print $content['content-secondary']; ?>
      </div>
    </section>
  <?php endif;?>

</div>
