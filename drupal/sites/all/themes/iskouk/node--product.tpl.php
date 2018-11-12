<?php //kdevel_print_object($content);
    //$tmp = $content['field_registration_and_fees'];
    $tcontext = array('context' => 'checkout');
    $substext = t('subscription', array(), $tcontext);
    if(!(strpos($model, $substext) === FALSE)){
        //$foundsubscr = true;
        //$content['add_to_cart']['#form']['actions']['submit']['#value'] = 'Application';
        $tcontext = array('context' => 'role');
        $content['add_to_cart']['#form']['actions']['submit']['#value'] = //t('Application', array(), $tcontext);
                t($content['add_to_cart']['#form']['actions']['submit']['#value'], array(), $tcontext);
    }
    if(property_exists($node, 'signup_status')){
        $content['add_to_cart']['#form']['actions']['submit']['#value'] = 'Register';
      if($node->signup_status){
        //if($node->signup_status){
        unset($content['book_navigation']);
        //unset($content['signup']);
      }else{
        unset($content['field_registration_and_fees']);//signup signup_list
        unset($content['signup']);
        unset($content['signup_list']);//book_navigation nodehierarchy_children nodehierarchy_children_links
      }
    }
    print render($content);
?>
