<?php

/**
 * @file
 * Post update functions for Project Browser.
 */

/**
 * Set a default value for disable_add_new_module setting.
 */
function project_browser_post_update_default_for_disable_add_new_module(&$sandbox) {
  \Drupal::configFactory()
    ->getEditable('project_browser.admin_settings')
    ->set('disable_add_new_module', TRUE)
    ->save(TRUE);
}
