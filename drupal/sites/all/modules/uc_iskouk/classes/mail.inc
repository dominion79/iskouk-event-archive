<?php

/**
 * @file
 * Utility class definition.
 */

/**
 * Modifies the Drupal mail system to send HTML emails.
 */
class IskoMailSystem implements MailSystemInterface {

  /**
   * Concatenates and wraps the e-mail body for plain-text mails.
   *
   * @param $message
   *   A message array, as described in hook_mail_alter().
   *
   * @return
   *   The formatted $message.
   */
  public function format(array $message) {
    $message['body'] = implode("\n\n", $message['body']);
    return $message;
  }

  /**
   * Sends an e-mail message using Drupal variables and default settings.
   *
   * @param $message
   *   A message array, as described in hook_mail_alter().
   *
   * @return
   *   TRUE if the mail was successfully accepted, otherwise FALSE.
   *
   * @see http://php.net/manual/en/function.mail.php
   * @see drupal_mail()
   */
  public function mail(array $message) {
    $mimeheaders = array();
    foreach ($message['headers'] as $name => $value) {
      if($name == 'Content-Type'){
        $mimeheaders[] = $name . ': ' . mime_header_encode('text/html; charset=utf-8');
      }else{
        $mimeheaders[] = $name . ': ' . mime_header_encode($value);
      }
    }
    //Pk 02.03.2013
    //Pk 08.09.2013 only if needed 
    $default_send = ini_get('sendmail_path');
    //$default_send = variable_get('site_mail', ini_get('sendmail'));
    if(!strstr($default_send, " -f")){
      $extra = '-f' . $message['headers']['Return-Path'];
    }else{
      $extra = '';
    }
    $line_endings = variable_get('mail_line_endings', MAIL_LINE_ENDINGS);
    return mail(
      $message['to'],
      mime_header_encode($message['subject']),
      // Note: e-mail uses CRLF for line-endings. PHP's API requires LF
      // on Unix and CRLF on Windows. Drupal automatically guesses the
      // line-ending format appropriate for your system. If you need to
      // override this, adjust $conf['mail_line_endings'] in settings.php.
      preg_replace('@\r?\n@', $line_endings, $message['body']),
      // For headers, PHP's API suggests that we use CRLF normally,
      // but some MTAs incorrectly replace LF with CRLF. See #234403.
      implode("\n", $mimeheaders),
      $extra
    );
  }
}
