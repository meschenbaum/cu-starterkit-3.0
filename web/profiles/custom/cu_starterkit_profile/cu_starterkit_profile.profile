<?php

/**
 * @file
 * Enables modules and site configuration for a profile.
 */

use Drupal\contact\Entity\ContactForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\UserInterface;
use Drupal\user\EntityInterface;
use Drupal\path_alias\Entity\PathAlias;
use Drupal\pathauto\PathautoState;

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function cu_starterkit_profile_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  // Add a placeholder as example that one can choose an arbitrary site name.
  $form['site_information']['site_name']['#default_value'] = 'CU System Starter Kit: Test Site';

  // Default site email noreply(at)example.com.
  $form['site_information']['site_mail']['#default_value'] = 'noreply@example.com';
  $form['site_information']['site_mail']['#attributes']['style'] = 'width: 25em;';

  // Default user 1 username should be 'admin'.
  $form['admin_account']['account']['name']['#default_value'] = 'admin';
  $form['admin_account']['account']['name']['#attributes']['disabled'] = TRUE;
  $form['admin_account']['account']['mail']['#default_value'] = 'admin@example.com';
  //$form['admin_account']['account']['mail']['#description'] = t('In most cases, and for <a target="_blank" href="@link">CU</a>’s specific use, we recommend this to always be <em>webmaster@cu.edu</em>.', ['@link' => 'https://www.cu.edu']);

}

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function cu_starterkit_profile_page_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  $form['#submit'][] = 'cu_starterkit_profile_form_install_configure_submit';
}

/**
 * Submission handler to sync the contact.form.feedback recipient.
 */
function cu_starterkit_profile_form_install_configure_submit($form, FormStateInterface $form_state) {
  $site_mail = $form_state->getValue('site_mail');
  ContactForm::load('feedback')->setRecipients([$site_mail])->trustData()->save();
}

/*
*  Impliment to give user default role if they are on
*  the system or campus support lists
*/
function cu_starterkit_profile_user_presave(UserInterface $user) {
  // Check to see if this is the first user added. Admin and anonymous always exist
  $query = \Drupal::entityQuery('user');
  $query->accessCheck(FALSE);
  $query->condition('uid', 1, '>');
  $count = $query->count()->execute();
  //$count = 0;
  if ($user->isNew() && $count==0) {
    $user->addRole('site_owner');
    $user->addRole('site_builder');
  }
  // @TODO - We should get this array from a YML file that can be 
  // easily editted outside the PHP
  $support_devs = array('matt.eschenbaum@cu.edu', 'kevin.reynen@cu.edu');
  $support_ces =  array('Matthew.Anderson@cu.edu', 'Laura.Duncan@cu.edu');
  // Check to see if this user is on the list of campus or system support users
  if (in_array($user->getEmail(), $support_devs)) {
    $user->addRole('developer');
  }
  if (in_array($user->getEmail(), $support_ces)) {
    $user->addRole('content_editor');
  }
}

/*
*  Alter the XML Sitemap status based on the whether the page is protected
*  Add -draft to path if published status is changing
*/
function cu_starterkit_profile_entity_presave(Drupal\Core\Entity\EntityInterface $entity) {

  if ($entity->getEntityTypeId()=='node'){
        
    // check for protected page change
    if ($entity->hasField('field_permissions')) {
      $is_protected = $entity->get('field_permissions')->target_id;

      if($is_protected) {
        $entity->xmlsitemap['status'] = 0;
        \Drupal::messenger()->addMessage('Page is protected. Excluding from XML Sitemap.');
      }
    
      // There are several reasons you might want to exclude a page from the sitemap, but users are unlikely to 
      // remember to change the XMLSitemap setting so we alter it when the protection state changes.
      
      if (!empty($entity->original) && $entity->original->hasField('field_permissions')) {
        $was_protected = $entity->original->get('field_permissions')->target_id;

        if ($was_protected && is_null($is_protected)) {
          $entity->xmlsitemap['status'] = 1;
          \Drupal::messenger()->addMessage('Page is no longer protected. Including in XML Sitemap.');
        }
      }
    }

   // Add or remove -draft if node isn't using Pathauto and published state is changing
    if (!$entity->path->pathauto) {
      if (isset($entity->original) && $entity->original->isPublished() != $entity->isPublished()) {

        $current_path = $entity->toArray()['path'][0]['alias'];
        if (!$entity->isPublished()) {
          // add -draft to the end of the URL... but only once
          if (stripos($current_path, 'draft') < 1) {
            $entity->path = [
              'alias' =>  $current_path . '-draft',
            ];  
          }

        }

        if ($entity->isPublished()) {
          // remove -draft to the end of the URL if it exists
          if (stripos($current_path, 'draft')) {
            $new_path = str_replace("-draft", "", $current_path);
          
            $entity->path = [
              'alias' =>  $new_path,
            ];  
          }
        }
      }
    }
  }

}

/**
 * Implements hook_pathauto_pattern_alter().
 *
 * Add -draft if published state is changing
 */
function cu_starterkit_profile_pathauto_pattern_alter(&$pattern, array $context) {
  if (isset($context['data']['node'])) {
    $entity = $context['data']['node'];

    if (isset($entity->original) && $entity->original->isPublished() != $entity->isPublished()) {
      if (!$entity->isPublished()) {
        $new_pattern = $pattern->getPattern() . '-draft';
        $pattern->setPattern($new_pattern);
      }
    
    }
  }
}