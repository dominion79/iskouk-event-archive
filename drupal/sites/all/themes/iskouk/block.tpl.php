<?php
  $beginSpan='';$endSpan='';
  $clientClass = "-other";
  $blockClass = "signup-event-sidebar";
  if (isset($_SERVER['HTTP_USER_AGENT'])){
      if(stripos($_SERVER['HTTP_USER_AGENT'], 'chrome')!==false) {
        $clientClass = "-chrome";
      }else if(stripos($_SERVER['HTTP_USER_AGENT'], 'firefox')!==false) {
        $clientClass = "-firefox";
      }else if(stripos($_SERVER['HTTP_USER_AGENT'], 'msie')!==false) {
        $clientClass = "-msie";
      }
  }
  if($block_html_id == 'block-views-childpages-block-block'){
  //if(($block_html_id == 'block-views-childpages-block-block')||($block_html_id == 'block-block-4')){
    //$beginSpan = '<span id="PkBlock" style="position: fixed; width: 201px">';
    //$endSpan = '</span>';
    $beginSpan = '<div id="PkBlock" class="' . $blockClass . ' ' . $blockClass . $clientClass . '">';
    $endSpan = '</div>';
  }else if(($block_html_id == 'block-block-4')){
    //$beginSpan = '<span id="PkBlock" style="position: fixed; width: 201px">';
    //$endSpan = '</span>';
    $beginSpan = '<div id="PkBlock2" class="' . $blockClass . '2 ' . $blockClass . $clientClass . '2">';
    $endSpan = '</div>';
  }
?>
<div id="block-<?php print $block->module . '-' . $block->delta; ?>" class="<?php print $classes; ?> " <?php print $attributes; ?>>
<?php print render($title_prefix); ?>
<?php print $beginSpan; ?>
<?php if ($block->subject): ?>
  <h2><?php print $block->subject ?></h2>
<?php endif;?>
<?php print render($title_suffix); ?>

<div class="content"<?php print $content_attributes; ?>>
<?php print $content ?>
</div>
<?php print $endSpan; ?>
</div>
