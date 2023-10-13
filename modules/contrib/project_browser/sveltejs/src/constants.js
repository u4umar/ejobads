export const MAINTENANCE_OPTIONS =
  drupalSettings.project_browser.maintenance_options;
export const SECURITY_OPTIONS = drupalSettings.project_browser.security_options;
export const DEVELOPMENT_OPTIONS =
  drupalSettings.project_browser.development_options;
export const SORT_OPTIONS = drupalSettings.project_browser.sort_options;
export const ACTIVELY_MAINTAINED_ID =
  drupalSettings.project_browser.special_ids.maintenance_status.id;
export const COVERED_ID =
  drupalSettings.project_browser.special_ids.security_coverage.id;
export const ALL_VALUES_ID =
  drupalSettings.project_browser.special_ids.all_values;
export const DEFAULT_SOURCE_ID =
  drupalSettings.project_browser.default_plugin_id;
export const CURRENT_SOURCES_KEYS =
  drupalSettings.project_browser.current_sources_keys;
export const ORIGIN_URL = drupalSettings.project_browser.origin_url;
export const MODULE_STATUS = drupalSettings.project_browser.modules;
export const FULL_MODULE_PATH = `${ORIGIN_URL}/${drupalSettings.project_browser.module_path}`;
export const ALLOW_UI_INSTALL = drupalSettings.project_browser.ui_install;
export const DARK_COLOR_SCHEME =
  matchMedia('(forced-colors: active)').matches &&
  matchMedia('(prefers-color-scheme: dark)').matches;
export const PM_VALIDATION_ERROR = drupalSettings.project_browser.pm_validation;
export const ACTIVE_PLUGINS = drupalSettings.project_browser.active_plugins;
