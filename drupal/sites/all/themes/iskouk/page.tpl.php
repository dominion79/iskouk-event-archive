<?php
?>
  <?php print render($page['header']); ?>

  <div id="wrapper">
    <div id="container" class="clearfix">

      <div id="header">
        <div id="logo-floater">
        <?php if ($logo || $site_title): ?>
          <?php if ($title): ?>
            <div id="branding"><strong><a href="<?php print $front_page ?>">
            <?php if ($logo): ?>
              <img src="<?php print $logo ?>" alt="<?php print $site_name_and_slogan ?>" title="<?php print $site_name_and_slogan ?>" id="logo" />
            <?php endif; ?>
            <?php print $site_html ?>
            </a></strong></div>
          <?php else: /* Use h1 when the content title is empty */ ?>
            <h1 id="branding"><a href="<?php print $front_page ?>">
            <?php if ($logo): ?>
              <img src="<?php print $logo ?>" alt="<?php print $site_name_and_slogan ?>" title="<?php print $site_name_and_slogan ?>" id="logo" />
            <?php endif; ?>
            <?php print $site_html ?>
            </a></h1>
        <?php endif; ?>
        <?php endif; ?>
        </div>

        <?php if ($primary_nav): print $primary_nav; endif; ?>
        <?php if ($secondary_nav): print $secondary_nav; endif; ?>
      </div> <!-- /#header -->

      <?php if ($page['sidebar_first']): ?>
        <div id="sidebar-first" class="sidebar">
          <?php print render($page['sidebar_first']); ?>
        </div>
      <?php endif; ?>

      <div id="center"><div id="squeeze"><div class="right-corner"><div class="left-corner">
          <?php print $breadcrumb; ?>
          <?php if ($page['highlighted']): ?><div id="highlighted"><?php print render($page['highlighted']); ?></div><?php endif; ?>
          <a id="main-content"></a>
          <?php if ($tabs): ?><div id="tabs-wrapper" class="clearfix"><?php endif; ?>
          <?php print render($title_prefix); ?>
            <?php
            //Pk 09.10.2012 for product type signup-event change the title, removev cart content
            $prodType = '';
            if(isset($page['content']) && isset($page['content']['system_main']) && isset($page['content']['system_main']['panes']) && isset($page['content']['system_main']['panes']['cart'])){
		if(isset($page['content']['system_main']['panes']['cart']['cart_review_table']) && isset($page['content']['system_main']['panes']['cart']['cart_review_table']['#items'])){
		  $tmp = $page['content']['system_main']['panes']['cart']['cart_review_table']['#items'];
		  $tmpAr = array_values($tmp);
		  //$prodType = $tmpAr[0]['data']['type']
		  $prodType = $tmpAr[0]->data['type'];
		}
            }
            ?>
            <?php if($prodType == 'signup_event'): ?>
              <?php unset($page['content']['system_main']['panes']['cart']);?>
              <h1<?php print $tabs ? ' class="with-tabs"' : '' ?>><?php print 'Registration Form' ?></h1>
            <?php else :?>
              <?php if ($title && (!isset($node) || $node->type != "signup_event")): ?>
                <h1<?php print $tabs ? ' class="with-tabs"' : '' ?>><?php print $title ?></h1>
              <?php endif; ?>
            <?php endif; ?>
          <?php print render($title_suffix); ?>
          <?php if ($tabs): ?><?php print render($tabs); ?></div><?php endif; ?>
          <?php print render($tabs2); ?>
          <?php print $messages; ?>
          <?php print render($page['help']); ?>
          <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
          <div class="clearfix">
            <?php print render($page['content']); ?>
          </div>
          <?php print $feed_icons ?>
          <?php print render($page['footer']); ?>
      </div></div></div></div> <!-- /.left-corner, /.right-corner, /#squeeze, /#center -->

      <?php if ($page['sidebar_second']): ?>
        <div id="sidebar-second" class="sidebar">
          <?php print render($page['sidebar_second']); ?>
        </div>
      <?php endif; ?>

    </div> <!-- /#container -->
  </div> <!-- /#wrapper -->
