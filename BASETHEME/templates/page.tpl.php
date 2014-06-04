<?php
$themepath = $base_path.path_to_theme() . '/';    
?>
<header>
  <?php print render($page['header']); ?>        
</header>
<section>
        <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
        <?php print render($page['content']);?>    
</section>
<footer>
    <?php print render($page['footer']) ?>
</footer>
