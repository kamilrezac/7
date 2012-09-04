<?php

/**
 * @file
 * Customize the display of a complete webform.
 *
 * This file may be renamed "webform-form-[nid].tpl.php" to target a specific
 * webform on your site. Or you can leave it "webform-form.tpl.php" to affect
 * all webforms on your site.
 *
 * Available variables:
 * - $form: The complete form array.
 * - $nid: The node ID of the Webform.
 *
 * The $form array contains two main pieces:
 * - $form['submitted']: The main content of the user-created form.
 * - $form['details']: Internal information stored by Webform.
 */
?>

<?php if (isset($_GET['error'])) { ?>
         <p class="error">Formulář obsahuje chyby.</p>
<?php } else if (isset($_GET['sid'])) { ?>
         <p class="ok">Váš dotaz byl úspěšně odeslán.</p>
<?php  } ?>

<?php
  // If editing or viewing submissions, display the navigation at the top.
  if (isset($form['submission_info']) || isset($form['navigation'])) {
    print drupal_render($form['navigation']);
    print drupal_render($form['submission_info']);
  }

  // Print out the main part of the form.
  // Feel free to break this up and move the pieces within the array.
  print '<div class="kontakt-form">';
  print '<h3>Napište nám</h3>';
  $form['submitted']['name']['#theme_wrappers'] = array();
  unset($form['submitted']['name']['#size']);
  unset($form['submitted']['name']['#maxlength']);
  print "<div>";
  print "<label for='edit-submitted-name'>Jméno</label>";
  print drupal_render($form['submitted']['name']);
  if (isset($_POST['submitted']['name']) && $_POST['submitted']['name'] == "") echo '<p class="error">Vyplňte prosím jméno.</p>';
  print "</div>";

  $form['submitted']['email']['#theme_wrappers'] = array();
  unset($form['submitted']['email']['#size']);
  unset($form['submitted']['email']['#maxlength']);
  print "<div>";
  print "<label for='edit-submitted-email'>E-mail</label>";
  print drupal_render($form['submitted']['email']);
  if (isset($_POST['submitted']['email']) && $_POST['submitted']['email'] != "" && !valid_email_address($_POST['submitted']['email'])) {
    echo '<p class="error">Vyplňte prosím e-mail ve správném tvaru.<p>';
  } else if (isset($_POST['submitted']['email']) && $_POST['submitted']['email'] == "") {
    echo '<p class="error">Vyplňte prosím e-mail.<p>';
  }
  print "</div>";


  $form['submitted']['message']['#theme_wrappers'] = array();
  $form['submitted']['message']['#resizable'] = false;
  unset($form['submitted']['message']['#rows']);
  unset($form['submitted']['message']['#cols']);
//  var_dump($form['submitted']['name']);exit;
  print "<div>";
  print "<label for='edit-submitted-message'>Váš vzkaz</label>";
  print drupal_render($form['submitted']['message']);
  if ((isset($_POST['submitted']['message']) && $_POST['submitted']['message'] == "")){
      echo '<p class="error">Vyplňte prosím vzkaz.<p>';
  }
  print "</div>";

  if (empty($_POST['submitted']['mail'])) {
      $form['submitted']['mail']['#attributes'] = array('style' => 'display:none');
  }
  $form['submitted']['mail']['#theme_wrappers'] = array();
  unset($form['submitted']['mail']['#size']);
  unset($form['submitted']['mail']['#maxlength']);
  print "<div>";
  print drupal_render($form['submitted']['mail']);
  if (!empty($_POST['submitted']['mail'])) {
      echo '<p class="error">Toto políčko prosím nevyplňujte.<p>';
  }
  print "</div>";

  $form['actions']['submit']['#attributes']['class'][] = 'sub2';
//  var_dump($form['actions']['submit']);exit;
  print "<div>";
  print drupal_render($form['actions']['submit']);
  print "</div>";

  // Always print out the entire $form. This renders the remaining pieces of the
  // form that haven't yet been rendered above.
  print drupal_render_children($form);
  print '</div>';

  // Print out the navigation again at the bottom.
  if (isset($form['submission_info']) || isset($form['navigation'])) {
    unset($form['navigation']['#printed']);
    print drupal_render($form['navigation']);
  }
