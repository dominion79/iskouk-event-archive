<?php
/**
 * @file
 * Displays the address edit form
 *
 * Available variables:
 * - $form: The complete address edit form array, not yet rendered.
 * - $req: A span for required fields:
 *   <span class="form-required">*</span>
 *
 * @see template_preprocess_uc_addresses_form()
 *
 * @ingroup themeable
 */
?>
<div class="address-pane-table byPk">
  <table>
    <?php
      // Pk 21.08.2012 reorder fields when company address:
      if(isset($form['billing_ucxf_iscompany']) && isset($form['billing_ucxf_iscompany']['#checked'])
              && $form['billing_ucxf_iscompany']['#checked']){
        $form['billing_first_name']['#title'] = 'Contact ' . $form['billing_first_name']['#title'];
        $form['billing_first_name']['#weight'] = -5;
        $form['billing_last_name']['#title'] = 'Contact ' . $form['billing_last_name']['#title'];
        $form['billing_last_name']['#weight'] = -6;
        $form['billing_company']['#title'] .= ' Name';
        $form['billing_company']['#weight'] = -7;
        $form['billing_company']['#required'] = 1;
        $form['#sorted'] = false;
      }else{
        //$form['billing_first_name']['#title'] = 'XFirst Name';//'Contact ' . $form['billing_first_name']['#title'];
        $form['billing_first_name']['#weight'] = -7;
        //$form['billing_last_name']['#title'] = 'XLast Name';//'Contact ' . $form['billing_last_name']['#title'];
        $form['billing_last_name']['#title'] = str_replace('Contact ', '', $form['billing_last_name']['#title']);
        $form['billing_last_name']['#weight'] = -6;
        //$form['billing_company']['#title'] = 'Company'; //.= ' Name';
        $form['billing_company']['#weight'] = -5;
        $form['billing_company']['#required'] = 0;
        $form['#sorted'] = false;
      }
      ?>
    <?php foreach (element_children($form) as $fieldname): ?>
      <?php
        // Skip fields with:
        // - #access == FALSE
        // - #type == value
        // - #type == hidden for fields without a label.
        if (
          (isset($form[$fieldname]['#access']) && $form[$fieldname]['#access'] == FALSE)
          || (isset($form[$fieldname]['#type']) && $form[$fieldname]['#type'] == 'value')
          || (isset($form[$fieldname]['#type']) && $form[$fieldname]['#type'] == 'hidden' && empty($form[$fieldname]['#title']))
        ) {
          continue;
        }
      ?>
      <tr class="field-<?php print $fieldname; ?>">
        <?php if (!empty($form[$fieldname]['#title'])): ?>
          <td class="field-label">
            <?php if ($form[$fieldname]['#required']): ?>
              <?php print $req; ?>
            <?php endif; ?>
            <?php print $form[$fieldname]['#title']; ?>:
          </td>
        <?php unset($form[$fieldname]['#title']); ?>
        <?php else: ?>
          <td class="field-label"></td>
        <?php endif; ?>
        <td class="field-field"><?php print drupal_render($form[$fieldname]); ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<div class="address-form-bottom"><?php print drupal_render_children($form); ?></div>