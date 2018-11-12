<?php //kdevel_print_object($content);
    //$tmp = $content['field_registration_and_fees'];
    //if(property_exists($node, 'signup_status')){
      if($node->type == '--dc_event'){
        unset($content['book_navigation']);
        //unset($content['signup']);
      }
    //}
	//echo "nodeType: $node->type";
	//kpr($node);
    print render($content);
?>
