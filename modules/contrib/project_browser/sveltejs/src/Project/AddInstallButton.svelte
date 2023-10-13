<script>
  import { openPopup } from '../popup';
  import { MODULE_STATUS, ORIGIN_URL, PM_VALIDATION_ERROR } from '../constants';
  import ProjectButtonBase from './ProjectButtonBase.svelte';
  import { isPackageManagerRequired } from '../stores';

  export let project;
  export let loading;
  export let projectInstalled;
  export let projectDownloaded;
  export let showStatus;
  // We must keep track of modules that were added but not installed as there
  // could me modules added (but not installed) prior to installing Project
  // Browser.
  export let alreadyAdded = false;
  const { Drupal } = window;

  const handleError = async (errorResponse) => {
    // If an error occurred, set loading to false so the UI no longer reports
    // the download/install as in progress.
    loading = false;

    // The error can take on many shapes, so it should be normalized.
    let err = '';
    if (typeof errorResponse === 'string') {
      err = errorResponse;
    } else {
      err = await errorResponse.text();
    }
    try {
      // See if the error string can be parsed as JSON. If not, the block
      // is exited before the `err` string is overwritten.
      const parsed = JSON.parse(err);
      err = parsed;
    } catch (error) {
      // The catch behavior is established before the try block.
    }
    const errorMessage = err.message || err;

    // The popup function expects an element, so a div containing the error
    // message is created here for it to display in a modal.
    const div = document.createElement('div');
    if (err.unlock_url && err.unlock_url !== '') {
      div.innerHTML += `<p>${errorMessage} <a href="${
        err.unlock_url
      }&destination=admin/modules/browse">${Drupal.t(
        'Unlock Install Stage',
      )}</a></p>`;
    } else {
      div.innerHTML += `<p>${errorMessage}</p>`;
    }
    openPopup(div, {
      ...project,
      title: `Error while installing ${project.title}`,
    });
  };

  /**
   * Installs an already downloaded module.
   */
  async function installModule() {
    loading = true;
    const url = `${ORIGIN_URL}/admin/modules/project_browser/activate-module/${project.project_machine_name}`;
    const installResponse = await fetch(url);
    if (!installResponse.ok) {
      handleError(installResponse);
      loading = false;
      return;
    }
    let responseContent = await installResponse.text();
    try {
      const parsedJson = JSON.parse(responseContent);
      responseContent = parsedJson;
    } catch (err) {
      handleError(installResponse);
    }
    if (responseContent.status === 0) {
      MODULE_STATUS[project.project_machine_name] = 1;
      projectInstalled = true;
      loading = false;
    }
  }

  /**
   * Uses package manager to download a module using Composer.
   *
   * @param {boolean} install
   *   If true, the module will be installed after it is downloaded.
   */
  function downloadModule(install = false) {
    showStatus(true);

    /**
     * Performs the requests necessary to download a module via Package Manager.
     *
     * @return {Promise<void>}
     *   No return, but is technically a Promise because this function is async.
     */
    async function doRequests() {
      loading = true;
      const beginInstallUrl = `${ORIGIN_URL}/admin/modules/project_browser/install-begin/${project.composer_namespace}`;
      const beginInstallResponse = await fetch(beginInstallUrl);
      if (!beginInstallResponse.ok) {
        await handleError(beginInstallResponse);
      } else {
        const beginInstallJson = await beginInstallResponse.json();
        const stageId = beginInstallJson.stage_id;

        // The process of adding a module is separated into four stages, each
        // with their own endpoint. When one stage completes, the next one is
        // requested.
        const installSteps = [
          `${ORIGIN_URL}/admin/modules/project_browser/install-require/${project.composer_namespace}/${stageId}`,
          `${ORIGIN_URL}/admin/modules/project_browser/install-apply/${project.composer_namespace}/${stageId}`,
          `${ORIGIN_URL}/admin/modules/project_browser/install-post_apply/${project.composer_namespace}/${stageId}`,
          `${ORIGIN_URL}/admin/modules/project_browser/install-destroy/${project.composer_namespace}/${stageId}`,
        ];

        // eslint-disable-next-line no-restricted-syntax,guard-for-in
        for (const step in installSteps) {
          // eslint-disable-next-line no-await-in-loop
          const stepResponse = await fetch(installSteps[step]);
          if (!stepResponse.ok) {
            // eslint-disable-next-line no-await-in-loop
            const errorMessage = await stepResponse.text();
            // eslint-disable-next-line no-console
            console.warn(
              `failed request to ${installSteps[step]}: ${errorMessage}`,
              stepResponse,
            );
            // eslint-disable-next-line no-await-in-loop
            await handleError(errorMessage);
            return;
          }
        }

        // If this line is reached, then every stage of the download process
        // was completed without error and we can consider the module
        // downloaded and the process complete.
        MODULE_STATUS[project.project_machine_name] = 0;
        projectDownloaded = true;
        loading = false;

        // If install is true, install the module before conveying the process
        // is complete to the UI.
        if (install === true) {
          installModule();
        }
      }
    }
    // Begin the install process, which is contained in the doRequests()
    // function so it can be async without its parent function having to be.
    doRequests();
  }
</script>

<ProjectButtonBase
  click={() => {
    if (alreadyAdded) {
      installModule();
    } else {
      downloadModule(true);
    }
  }}
  disabled={PM_VALIDATION_ERROR && $isPackageManagerRequired}
>
  {alreadyAdded ? Drupal.t('Install') : Drupal.t('Add and Install')}<span
    class="visually-hidden">{project.title}</span
  >
</ProjectButtonBase>
