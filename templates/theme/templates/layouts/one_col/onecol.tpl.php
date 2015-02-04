<div<?php print $css_id ? " id=\"$css_id\"" : ''; ?> class="onecol">

  <?php if($content['content-primary']): ?>
    <section id="content-primary" class="content-primary">
      <div class="inner">
        <?php print $content['content-primary']; ?>
      </div>
    </section>
  <?php endif;?>

  <?php if($content['content-secondary']): ?>
    <section id="content-secondary" class="content-secondary">
      <div class="inner">
        <?php print $content['content-secondary']; ?>
      </div>
    </section>
  <?php endif;?>

</div>
