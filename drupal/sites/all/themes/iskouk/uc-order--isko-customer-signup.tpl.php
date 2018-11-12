<?php

/**
 * @file
 * This file is the default customer invoice template for Ubercart.
 *
 * Available variables:
 * - $products: An array of product objects in the order, with the following
 *   members:
 *   - title: The product title.
 *   - model: The product SKU.
 *   - qty: The quantity ordered.
 *   - total_price: The formatted total price for the quantity ordered.
 *   - individual_price: If quantity is more than 1, the formatted product
 *     price of a single item.
 *   - details: Any extra details about the product, such as attributes.
 * - $line_items: An array of line item arrays attached to the order, each with
 *   the following keys:
 *   - line_item_id: The type of line item (subtotal, shipping, etc.).
 *   - title: The line item display title.
 *   - formatted_amount: The formatted amount of the line item.
 * - $shippable: TRUE if the order is shippable.
 *
 * Tokens: All site, store and order tokens are also available as
 * variables, such as $site_logo, $store_name and $order_first_name.
 *
 * Display options:
 * - $op: 'view', 'print', 'checkout-mail' or 'admin-mail', depending on
 *   which variant of the invoice is being rendered.
 * - $business_header: TRUE if the invoice header should be displayed.
 * - $shipping_method: TRUE if shipping information should be displayed.
 * - $help_text: TRUE if the store help message should be displayed.
 * - $email_text: TRUE if the "do not reply to this email" message should
 *   be displayed.
 * - $store_footer: TRUE if the store URL should be displayed.
 * - $thank_you_message: TRUE if the 'thank you for your order' message
 *   should be displayed.
 *
 * @see template_preprocess_uc_order()
 */
?>
<?php
$tmp=$uc_addresses_billing_ucxf_payment_behalf;
?>
<?php
$tmp=$uc_addresses_billing_ucxf_payment_behalf;
$product_title = 'Event';
$myproduct = '';
foreach ($products as $product){
	$product_title = $product->title;
	$myproduct = $product;
	break;
}
$signup = db_query("SELECT sl.*, n.title, u.name FROM {signup_log} sl INNER JOIN {node} n ON sl.nid = n.nid INNER JOIN {users} u ON sl.uid = u.uid WHERE sl.uid = :uid AND sl.nid = :nid", array(':uid' => $order_uid, ':nid' => $product->nid))->fetchObject();
if($signup){
  $signup_token = signup_get_token($signup->sid, 'cancel');
}
?>

<table width="95%" border="0" cellspacing="0" cellpadding="1" align="center" bgcolor="#006699" style="font-family: verdana, arial, helvetica; font-size: small;">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="5" align="center" bgcolor="#FFFFFF" style="font-family: verdana, arial, helvetica; font-size: small;">
        <?php if ($business_header): ?>
        <tr valign="top">
          <td>
            <table width="100%" style="font-family: verdana, arial, helvetica; font-size: small;">
              <tr>
                <td>
                  <?php print $site_logo; ?>
                </td>
                <td width="98%">
                  <div style="padding-left: 1em;">
                  <span style="font-size: large;"><?php print $store_name; ?></span><br />
                  <?php print $site_slogan; ?>
                  </div>
                </td>
                <td nowrap="nowrap">
                  <?php print $store_address; ?><br /><?php print $store_phone; ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <?php endif; ?>
        <?php //print "signup/cancel/$signup->sid/$signup_token"; ?>
        <?php //print l("cancel", "signup/cancel/$signup->sid/$signup_token", array('absolute' => TRUE)); ?>

        <tr valign="top">
          <td>

            <p><b><?php print t('Thank you for your registration  "!product_title", !order_first_name. You will find details of your registration in the attached document, which also serves as an invoice where appropriate.', array('!order_first_name' => $order_first_name, '!product_title' => $product_title)); ?></b></p>
            <p><?php print t('This document may also be printed or downloaded if needed, from your Order History page on the "!siteName" site at !order_link', array('!order_link' => $order_link, '!siteName' => $store_name )); ?></p>
            <?php if (isset($signup) && isset($signup_token)): ?>
		      <p><?php print t('To cancel your Registration click on !cancel_link', array('!cancel_link' => l("cancel", "signup/cancel/$signup->sid/$signup_token", array('absolute' => TRUE)))); ?></p>
            <?php endif; ?>

            <?php if (isset($order->data['new_user'])): ?>
            <p><b><?php print t('An account has been created for you with the following details:'); ?></b></p>
            <p><b><?php print t('Username:'); ?></b> <?php print $order_new_username; ?><br />
            <b><?php print t('Password:'); ?></b> <?php print $order_new_password; ?></p>
            <?php endif; ?>

            <p><b><?php print t('Regards,'); ?></b><br />
            <p><b><?php print t('ISKO UK Events Team'); ?></b><br />
            
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

