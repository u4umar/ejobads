<?php

namespace Drupal\project_browser\Exception;

use Drupal\package_manager\Exception\StageValidationException;

/**
 * Defines a custom exception for a failure during an install.
 */
class InstallException extends StageValidationException {
}
