<?php
function uc_pdfinvoice_rules_action_info() {
  $order_arg = array(
    'type' => 'uc_order',//'uc_pdfinvoice',//'uc_order',
    'label' => t('Order'),
  );

  $actions['uc_pdfinvoice_email_invoice_pdf'] = array(
    'label' => t('Email an order invoice with pdf'),
    'group' => t('Order'),
    'base' => 'uc_pdfinvoice_action_email_invoice',
    'parameter' => array(
      'order' => $order_arg,
      'from' => array(
        'type' => 'text',
        'label' => t('Sender'),
        'description' => t("Enter the 'From' email address, or leave blank to use your store email address. You may use order tokens for dynamic email addresses."),
        'optional' => TRUE,
      ),
      'addresses' => array(
        'type' => 'text',
        'label' => t('Recipients'),
        'description' => t('Enter the email addresses to receive the invoice, one on each line. You may use order tokens for dynamic email addresses.'),
      ),
      'subject' => array(
        'type' => 'text',
        'label' => t('Subject'),
        'translatable' => TRUE,
      ),
      'template' => array(
        'type' => 'text',
        'label' => t('Invoice template'),
        'options list' => 'uc_order_template_options',
        'restriction' => 'input',
      ),
      'attaname' => array(
        'type' => 'text',
        'label' => t('Attachment name'),
        'translatable' => TRUE,
        'description' => t('Enter the pdf invoice attachment name. You may use order tokens for dynamic names. The name must not exceed 28 characters after replacement.'),
      ),
      'pdftemplate' => array(
        'type' => 'text',
        'label' => t('Invoice pdf template'),
        'options list' => 'uc_order_template_options',
        'restriction' => 'input',
      ),
      'view' => array(
        'type' => 'text',
        'label' => t('Included information'),
        'options list' => 'uc_order_action_email_invoice_view_options',
        'restriction' => 'input',
      ),
    ),
  );

  return $actions;
}

/**
 * Emails an invoice.
 *
 * The 'Sender', 'Subject' and 'Addresses' fields take order token replacements.
 */
function uc_pdfinvoice_email_invoice($order, $from, $addresses, $subject, $template, $pdftemplate, $view) {
//  uc_pdfinvoice_action_email_invoice($order, $from, $addresses, $subject, $template, $pdftemplate, $view);
}

/**
 * Emails an invoice.
 *
 * The 'Sender', 'Subject' and 'Addresses' fields take order token replacements.
 */
function uc_pdfinvoice_action_email_invoice($order, $from, $addresses, $subject, $template, $attaname, $pdftemplate, $view) {
  $settings = array(
    'from' => $from,
    'addresses' => $addresses,
    'subject' => $subject,
    'template' => $template,
    'attaname' => $attaname,
    'pdftemplate' => $pdftemplate,
    'view' => $view,
  );
  // Token replacements for the from, subject and body
  $settings['replacements'] = array(
    'uc_order' => $order,
  );

  // Apply token replacements to the 'from' e-mail address.
  $from = token_replace($settings['from'], $settings['replacements']);
  if (empty($from)) {
    $from = uc_store_email_from();
  }

  // Apply token replacements to the 'attachment name' field.
  $attaname = token_replace($settings['attaname'], $settings['replacements']);
  if (empty($attaname)) {
    $attaname = substr($settings['subject'], 0, 28);
  }
  $settings['headers']['attaname'] = $attaname;

  // Apply token replacements to 'recipient' e-mail addresses.
  $addresses = token_replace($settings['addresses'], $settings['replacements']);
  // Split up our recipient e-mail addresses.
  $recipients = array();
  foreach (explode("\n", $addresses) as $address) {
    $address = trim($address);
    // Remove blank lines
    if (!empty($address)) {
      $recipients[] = $address;
    }
  }

  $settings['message'] = theme('uc_order', array('order' => $order, 'op' => $settings['view'], 'template' => $settings['template']));
  //$altbody = theme('uc_order', array('order' => $order, 'op' => $settings['view'], 'template' => 'isko-cusstomer_body'));
  $altbody = theme('uc_order', array('order' => $order, 'op' => $settings['view'], 'template' => $settings['pdftemplate']));

  //$settings['message']['altbody'] = theme('uc_order', array('order' => $order, 'op' => $settings['view'], 'template' => $settings['template'].'_body'));
  //$settings['message']['headers']['altbody'] = $altbody;
  $settings['headers']['altbody'] = $altbody;
  //$settings['altbody'] = $altbody;


  foreach ($recipients as $email) {
    $sent = drupal_mail('uc_order', 'action-mail', $email, uc_store_mail_recipient_language($email), $settings, $from);
    if (!$sent['result']) {
      watchdog('uc_order', 'Attempt to e-mail invoice for order @order_id to @email failed.', array('@email' => $email, '@order_id' => $order->order_id), WATCHDOG_ERROR);
    }
  }
}


