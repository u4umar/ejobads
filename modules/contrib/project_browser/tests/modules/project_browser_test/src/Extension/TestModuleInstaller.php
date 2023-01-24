<?php

namespace Drupal\project_browser_test\Extension;

use Drupal\Core\Extension\ModuleInstaller;

/**
 * Conditional Module installer for test.
 *
 * @see \Drupal\Core\Extension\ModuleInstaller::install
 */
class TestModuleInstaller extends ModuleInstaller {

  public function install(array $module_list, $enable_dependencies = TRUE) {
    if (in_array('cream_cheese', $module_list, TRUE)) {
      return TRUE;
    }
    return parent::install($module_list, $enable_dependencies);
  }

}
