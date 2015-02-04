<?php
/**
 * @file
 * This layout is designed to be the site template layout when using
 * the Panels Everywhere module.
 */
?>
<div<?php print $css_id ? " id=\"$css_id\"" : ''; ?> class="page-wrapper">

  <?php if (!empty($content['header'])): ?>
    <header id="header" role="banner" class="header">
      <div class="inner">
        <?php print $content['header']; ?>
      </div>
    </header>
  <?php endif; ?>

  <?php if (!empty($content['main'])): ?>
    <main id="main" role="main" class="main">
      <div class="inner">
        <?php print render($content['main']); ?>
      </div>
    </main>
  <?php endif; ?>

  <?php if (!empty($content['footer'])): ?>
    <footer id="footer" role="contentinfo" class="footer">
      <div class="inner">
        <?php print $content['footer']; ?>
      </div>
    </footer>
  <?php endif; ?>

</div>
