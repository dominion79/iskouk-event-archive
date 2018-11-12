<?php

/**
 * Override of theme_breadcrumb().
 */
function ___iskouk_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode(' › ', $breadcrumb) . '</div>';
    return $output;
  }
}

/**
 * Override or insert variables into the maintenance page template.
 */
function ___iskouk_preprocess_maintenance_page(&$vars) {
  // While markup for normal pages is split into page.tpl.php and html.tpl.php,
  // the markup for the maintenance page is all in the single
  // maintenance-page.tpl.php template. So, to have what's done in
  // iskouk_preprocess_html() also happen on the maintenance page, it has to be
  // called here.
  iskouk_preprocess_html($vars);
}

/**
 * Override or insert variables into the html template.
 */
function ___iskouk_preprocess_html(&$vars) {
  // Toggle fixed or fluid width.
  if (theme_get_setting('iskouk_width') == 'fluid') {
    $vars['classes_array'][] = 'fluid-width';
  }
  // Add conditional CSS for IE6.
  drupal_add_css(path_to_theme() . '/fix-ie.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lt IE 7', '!IE' => FALSE), 'preprocess' => FALSE));
}

/**
 * Override or insert variables into the html template.
 */
function ___iskouk_process_html(&$vars) {
  // Hook into color.module
  if (module_exists('color')) {
    _color_html_alter($vars);
  }
}
//function iskouk_field__uc_product_image($variables) {
function iskouk_field__uc_product_image(&$variables) {
  $output = '';
  if (!$variables['label_hidden']) {
    $output .= '<div ' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
  }else{
    //$output .= '<div ' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
    //$output .= '<div >' . drupal_render($variables['element']) . '</div>';
//    $output .= '<div >' . "blabla" . '</div>';
  }
  return $output;
}
function iskouk_preprocess_image(&$variables) {
  $output = '';
    // If this image is of the type 'Staff Photo' then assign additional classes to it:
    if (isset($variables['style_name']) && $variables['style_name'] == 'staff_photo') {
        $variables['attributes']['class'][] = 'lightbox';
        $variables['attributes']['class'][] = 'wideborder';
    }
    if(isset($variables['width'])){
      if($variables['width'] > 760){
          $variables['width'] = '100%';
      }
    }
}
function iskouk_field($variables) {
  $output = '';
 
  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<div ' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
  }
 
  // Render the items.
  $output .= '<div ' . $variables['content_attributes'] . '>';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<div ' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
  }
  $output .= '</div>';
 
  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';
 
  return $output;
}
/**
 * Override or insert variables into the page template.
 */
function iskouk_preprocess_page(&$vars) {
  if(isset($vars)){
    if(isset($vars['page']) && isset($vars['page']['content']) 
            && isset($vars['page']['content']['system_main']) 
            && isset($vars['page']['content']['system_main']['panes'])
                && isset($vars['page']['content']['system_main']['#form_id'])
                && $vars['page']['content']['system_main']['#form_id'] == 'uc_cart_checkout_form'
            ){
      if(isset($vars['page']['content']['system_main']['panes']['billing']['address'])
              && isset($vars['page']['content']['system_main']['panes']['billing']['address']['billing_ucxf_iscompany'])){
          $vars['page']['content']['system_main']['panes']['billing']['address']['billing_ucxf_iscompany']['#access'] = false;
      }
      //Pk 11.10.2012 rename the button for signup events
      //$vars['page']['content']['system_main']['actions']['continue']['#value'] = 'Review registration details';
      $mypanes = $vars['page']['content']['system_main']['panes'];
      $description = t('Enter a valid email address for this order or <a href="!url">click here</a> to login with an existing account and return to checkout.', array('!url' => url('user/login', array('query' => drupal_get_destination()))));
      if(strpos($mypanes['customer']['#description'], 'nter a valid ')){
        $description = t('If you already have an account <a href="!url">click here</a> to login. Otherwise enter a valid email address to create your account and continue.', array('!url' => url('user/login', array('query' => drupal_get_destination()))));
        $vars['page']['content']['system_main']['panes']['customer']['#description'] = $description;
      }
      if(isset($vars['page']['content']['system_main']['panes']['iskouk'])){
        //Pk 11.10.2012 point next button to pane after iskouk
        $tmpNextC = $vars['page']['content']['system_main']['panes']['customer']['next']['#attributes']['onclick'];
        $tmpNextI = $vars['page']['content']['system_main']['panes']['iskouk']['next']['#attributes']['onclick'];
        $tmpAr1 = explode(',', $tmpNextC);
        $tmpAr2 = explode(',', $tmpNextI);
        $vars['page']['content']['system_main']['panes']['customer']['next']['#attributes']['onclick'] = str_replace($tmpAr1[1], $tmpAr2[1], $tmpNextC);
        $tmpNext2 = $vars['page']['content']['system_main']['panes']['customer']['next']['#attributes']['onclick'];

        //Pk 11.10.2012 put Company as first field now
        $vars['iskouk'] = true;
        $tmp_var = $vars['page']['content']['system_main']['panes']['billing']['address'];
        $tmp_var['billing_ucxf_iscompany']['#checked'] = true;
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_ucxf_iscompany']['#checked'] = true;
        //Pk 11.10.2012 kick out iskouk pane, used as flag only
        unset($vars['page']['content']['system_main']['panes']['iskouk']);
      }else{
        //$vars['page']['content']['system_main']['panes']['billing']['address']['billing_first_name']['#title'] = 'First Name';
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_first_name']['#weight'] = -7;
        //$vars['page']['content']['system_main']['panes']['billing']['address']['billing_last_name']['#title'] = 'Last Name';
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_last_name']['#weight'] = -6;
        //$vars['page']['content']['system_main']['panes']['billing']['address']['billing_company']['#title'] = 'CompanyName';
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_company']['#weight'] = -5;
      }
    }
  }
}
//function ___Pk_iskouk_preprocess_page(&$vars) {
function __iskouk_preprocess_page(&$vars) {
  // Move secondary tabs into a separate variable.
  $vars['tabs2'] = array(
    '#theme' => 'menu_local_tasks',
    //'#secondary' => $vars['tabs']['#secondary'],
  );
  //Pk 19.08.2012 hack into address display
  if(isset($vars['page']) && isset($vars['page']['content']) && isset($vars['page']['content']['system_main']) && isset($vars['page']['content']['system_main']['panes'])){
    if(isset($vars['page']['content']['system_main']['panes']['iskouk'])){
        $vars['iskouk'] = true;
        $vars['page']['content']['system_main']['panes']['billing']['address']['#value'] = 'isko';
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_first_name']['#title'] = 'Contact Firstname';
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_first_name']['#weight'] = -5;
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_last_name']['#title'] = 'Contact Lastname';
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_last_name']['#weight'] = -6;
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_company']['#title'] = 'CompanyName';
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_company']['#weight'] = -7;
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_company']['#required'] = 1;

    }else{
        //$vars['page']['content']['system_main']['panes']['billing']['address']['billing_first_name']['#title'] = 'First Name';
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_first_name']['#weight'] = -7;
        //$vars['page']['content']['system_main']['panes']['billing']['address']['billing_last_name']['#title'] = 'Last Name';
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_last_name']['#weight'] = -6;
        //$vars['page']['content']['system_main']['panes']['billing']['address']['billing_company']['#title'] = 'CompanyName';
        $vars['page']['content']['system_main']['panes']['billing']['address']['billing_company']['#weight'] = -5;
    }
  }
  if(isset($vars['tabs']['#secondary'])){
      $vars['tabs2']['#secondary'] = $vars['tabs']['#secondary'];
      unset($vars['tabs']['#secondary']);
  }
  //$processor_function($variables, $hook_clone);
  $processor_function = 'garland_preprocess_page';
  //$processor_function($vars, 'page');
  //return;
  //theme('garland_preprocess_page', $vars);
  //unset($vars['tabs']['#secondary']);

  if (isset($vars['main_menu'])) {
    $vars['primary_nav'] = theme('links__system_main_menu', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'class' => array('links', 'inline', 'main-menu'),
      ),
      'heading' => array(
        'text' => t('Main menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
    ));
  }
  else {
    $vars['primary_nav'] = FALSE;
  }
  if (isset($vars['secondary_menu'])) {
    $vars['secondary_nav'] = theme('links__system_secondary_menu', array(
      'links' => $vars['secondary_menu'],
      'attributes' => array(
        'class' => array('links', 'inline', 'secondary-menu'),
      ),
      'heading' => array(
        'text' => t('Secondary menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
    ));
  }
  else {
    $vars['secondary_nav'] = FALSE;
  }

  // Prepare header.
  $site_fields = array();
  if (!empty($vars['site_name'])) {
    $site_fields[] = $vars['site_name'];
  }
  if (!empty($vars['site_slogan'])) {
    $site_fields[] = $vars['site_slogan'];
  }
  $vars['site_title'] = implode(' ', $site_fields);
  if (!empty($site_fields)) {
    $site_fields[0] = '<span>' . $site_fields[0] . '</span>';
  }
  $vars['site_html'] = implode(' ', $site_fields);

  // Set a variable for the site name title and logo alt attributes text.
  $slogan_text = $vars['site_slogan'];
  $site_name_text = $vars['site_name'];
  $vars['site_name_and_slogan'] = $site_name_text . ' ' . $slogan_text;
}

/**
 * Override or insert variables into the node template.
 */
function ___iskouk_preprocess_node(&$vars) {
  $vars['submitted'] = $vars['date'] . ' — ' . $vars['name'];
}

/**
 * Override or insert variables into the comment template.
 */
function ___iskouk_preprocess_comment(&$vars) {
  $vars['submitted'] = $vars['created'] . ' — ' . $vars['author'];
}

/**
 * Override or insert variables into the block template.
 */
function ___iskouk_preprocess_block(&$vars) {
  $vars['title_attributes_array']['class'][] = 'title';
  $vars['classes_array'][] = 'clearfix';
}

/**
 * Override or insert variables into the page template.
 */
function ___iskouk_process_page(&$vars) {
  // Hook into color.module
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
}

/**
 * Override or insert variables into the region template.
 */
function ___iskouk_preprocess_region(&$vars) {
  if ($vars['region'] == 'header') {
    $vars['classes_array'][] = 'clearfix';
  }
}

    /**
 * Pk 19.08.2012 try theme the cart
 */

function iskouk_theme_uc_cart_view_form($variables) {
   $form = $variables['form'];
   drupal_add_css(drupal_get_path('module', 'uc_cart') . '/uc_cart.css');
  $form['items']['#theme'] = 'patzz';
   
 //  print_r($form['items']);
   $output = '<div id="cart-form-products"> XXXX'  
           . drupal_render($form['items']) . 'YYYY</div>';
   
   foreach (element_children($form['items']) as $i) {
     foreach (array('title', 'options', 'remove', 'image', 'qty') as $column) {
       $form['items'][$i][$column]['#printed'] = TRUE;
     }                   
     $form['items'][$i]['#printed'] = TRUE;        
   }
}

function iskouk_uc_cart_checkout_form(&$vars){
  if(isset($vars2)){
    //$vars['form']['panes'];
    //Pk 19.08.2012 hack into address display
    if(isset($vars['form']) && isset($vars['form']['panes']) && isset($vars['form']['panes']['billing']) && isset($vars['form']['panes']['billing']['address'])){
      if(isset($vars['form']['panes']['iskouk'])){
        $vars['form']['panes']['billing']['address']['#value'] = 'isko';
        $vars['form']['panes']['billing']['address']['billing_first_name']['#title'] = 'Contact Firstname';
        $vars['form']['panes']['billing']['address']['billing_first_name']['#weight'] = -5;
        $vars['form']['panes']['billing']['address']['billing_last_name']['#title'] = 'Contact Lastname';
        $vars['form']['panes']['billing']['address']['billing_last_name']['#weight'] = -6;
        $vars['form']['panes']['billing']['address']['billing_company']['#title'] = 'CompanyName';
        $vars['form']['panes']['billing']['address']['billing_company']['#weight'] = -7;
        $vars['form']['panes']['billing']['address']['billing_company']['#required'] = 1;

      }else{
        //$vars['form']['panes']['billing']['address']['billing_first_name']['#title'] = 'First NameFN';
        $vars['form']['panes']['billing']['address']['billing_first_name']['#weight'] = -7;
        //$vars['form']['panes']['billing']['address']['billing_last_name']['#title'] = 'Last NameLN';
        $vars['form']['panes']['billing']['address']['billing_last_name']['#weight'] = -6;
        //$vars['form']['panes']['billing']['address']['billing_company']['#title'] = 'CompanyName';
        $vars['form']['panes']['billing']['address']['billing_company']['#weight'] = -5;
      }
    }
  }
}
function iskouk_preprocess_form(&$vars) {
    $form_id = $vars['element']['form_id']['#value'];
    if($form_id == 'signup_form'){
        $vars['element']['#access'] = false;
        $vars['element']['collapse']['#access'] = false;
    }
  if(isset($vars2)){
    //$vars['element']['panes'];
    if(isset($vars['element']) && isset($vars['element']['panes']) && isset($vars['element']['panes']['billing']) && isset($vars['element']['panes']['billing']['address'])){
      if(isset($vars['element']['panes']['iskouk'])){
        $vars['element']['panes']['billing']['address']['#value'] = 'isko';
        $vars['element']['panes']['billing']['address']['billing_first_name']['#title'] = 'Contact Firstname';
        $vars['element']['panes']['billing']['address']['billing_first_name']['#weight'] = -5;
        $vars['element']['panes']['billing']['address']['billing_last_name']['#title'] = 'Contact Lastname';
        $vars['element']['panes']['billing']['address']['billing_last_name']['#weight'] = -6;
        $vars['element']['panes']['billing']['address']['billing_company']['#title'] = 'CompanyName';
        $vars['element']['panes']['billing']['address']['billing_company']['#weight'] = -7;
        $vars['element']['panes']['billing']['address']['billing_company']['#required'] = 1;
        
      }else{
        //$vars['element']['panes']['billing']['address']['billing_first_name']['#title'] = 'First NameFN';
        $vars['element']['panes']['billing']['address']['billing_first_name']['#weight'] = -7;
        //$vars['element']['panes']['billing']['address']['billing_last_name']['#title'] = 'Last NameLN';
        $vars['element']['panes']['billing']['address']['billing_last_name']['#weight'] = -6;
        //$vars['element']['panes']['billing']['address']['billing_company']['#title'] = 'CompanyName';
        $vars['element']['panes']['billing']['address']['billing_company']['#weight'] = -5;
      }
    }
  }
}
/**
 * Controls the output displayed if this node is closed for signups.
 *
 * @param $variables
 *   An array of variables containing:
 *   - 'node': The fully loaded node object.
 *   - 'current_signup': If the user already signed up, an HTML representation
 *     of their current signup information, otherwise an empty string.
 *
 * @return
 *   Themed output to display for a node with closed signups.
 *
 * @see _signup_node_output()
 * @see _signup_print_current_signup()
 */
//function theme_signup_signups_closed($variables) {
function iskouk_signup_signups_closed($variables) {
  $node = $variables['node'];

  $current_signup = $variables['current_signup'];
  $output = '<h3 class="signup-closed">' . t('Signups closed for this %node_type', array('%node_type' => node_type_get_name($node->type))) . '</h3>';
  $output .= $current_signup;
  return $output;
}

