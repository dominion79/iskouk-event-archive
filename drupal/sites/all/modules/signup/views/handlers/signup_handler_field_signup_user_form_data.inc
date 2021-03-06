<?php
/**
 * Field handler to display custom (site-specific) signup form data.
 */
class signup_handler_field_signup_user_form_data extends views_handler_field_prerender_list {
  function option_definition() {
    $options = parent::option_definition();

    $options['form_data_fieldname'] = array('default' => '');

    return $options;
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['form_data_fieldname'] = array(
      '#type' => 'textfield',
      '#title' => t('Field Name'),
      '#description' => t('If you want to display a single field from the signup form (see theme/signup_form.inc), enter its name here. Otherwise, all fields will be displayed.'),
      '#default_value' => isset($this->options['form_data_fieldname']) ? $this->options['form_data_fieldname'] : '',
    );
  }

  /**
   * Set each field value to "$key: $value" when rendering all fields.
   *
   * @see theme_signup_custom_data_field_text()
   */
  function pre_render(&$values) {
    if (!$this->options['form_data_fieldname']) {
      foreach ($values as $key => $result) {
        $form_data = unserialize($result->{$this->field_alias});
        if ($form_data) {
          foreach ($form_data as $key => $value) {
            $this->items[$result->{$this->field_alias}][$key] = check_plain(theme('signup_custom_data_field_text', array('key' => $key, 'value' => $value)));
          }
        }
      }
    }
  }

  function render($values) {
    if ($this->options['form_data_fieldname']) {
      $form_data = unserialize($values->{$this->field_alias});
      return check_plain($form_data[$this->options['form_data_fieldname']]);
    }
    else {
      $field = $this->get_value($values);
      if (!empty($field)) {
        if ($this->options['type'] == 'separator') {
          return implode($this->sanitize_value($this->options['separator']), $field);
        }
        else {
          return theme('item_list', array('items' => $field, 'title' => NULL, 'type' => $this->options['type']));
        }
      }
    }
  }
}

