<script>
  import { onMount } from 'svelte';
  import {
    MODULE_STATUS,
    ORIGIN_URL,
    ALLOW_UI_INSTALL,
    PM_VALIDATION_ERROR,
  } from '../constants';
  import Loading from '../Loading.svelte';
  import { openPopup, getCommandsPopupMessage } from '../popup';
  import AddInstallButton from './AddInstallButton.svelte';
  import LoadingEllipsis from './LoadingEllipsis.svelte';
  import ProjectButtonBase from './ProjectButtonBase.svelte';
  import ProjectStatusIndicator from './ProjectStatusIndicator.svelte';

  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let project;
  let loading = false;
  let loadingPhase = 'Adding';

  const { drupalSettings, Drupal } = window;

  /**
   * Determine is a project is present in the local Drupal codebase.
   *
   * @param {string} projectName
   *    The project name.
   * @return {boolean}
   *   True if the project is present.
   */
  function projectIsDownloaded(projectName) {
    return (
      typeof drupalSettings !== 'undefined' && projectName in MODULE_STATUS
    );
  }

  /**
   * Determine if a project is installed in the local Drupal codebase.
   *
   * @param {string} projectName
   *   The project name.
   * @return {boolean}
   *   True if the project is installed.
   */
  function projectIsInstalled(projectName) {
    return (
      typeof drupalSettings !== 'undefined' &&
      projectName in MODULE_STATUS &&
      MODULE_STATUS[projectName] === 1
    );
  }

  let projectInstalled = projectIsInstalled(project.project_machine_name);
  let projectDownloaded = projectIsDownloaded(project.project_machine_name);

  /**
   * Checks the download/install status of a project and updates the UI.
   *
   * During an install, this function is repeatedly called to check the status
   * of the download/install operation, and the UI is updated with the stage the
   * process is currently in. This function stops being called when the process
   * successfully completes or stops due to an error.
   *
   * @param {boolean} initiate
   *   When true, begin the install process for the project.
   * @return {Promise<void>}
   *   Return is not used, but is a promise due to this being async.
   */
  const showStatus = async (initiate = false) => {
    const url = `${ORIGIN_URL}/admin/modules/project_browser/install_in_progress/${project.project_machine_name}`;

    //
    /**
     * Gets the current status of the project's download or require process.
     *
     * @return {Promise<any>}
     *   The JSON status response, plus the timestamp of when it was returned.
     */
    const status = async () => {
      const progressCheck = await fetch(url);
      const json = await progressCheck.json();
      return { ...json, time: new Date().getTime() };
    };
    const loadingStatus = await status();

    // We keep track of how many intervals have taken place during the progress
    // check so we can announce progress to every 5-10 seconds.
    let intervals = 0;

    // When a require begins, there may be a delay before the
    // `install_in_progress` endpoint provides the correct status. The
    // initiateLag is how many times the interval below will check for that
    // status before aborting.
    let initiateLag = 4;

    // The initiate variable means a new download or install was requested and
    // the associate process should begin.
    // The loadingStatus checks are for when project browser is loaded and one
    // of the listed projects has a download or install in progress, so the UI
    // conveys this even if the process was initiated in another tab or by a
    // different user.
    if (initiate || (loadingStatus && loadingStatus.status !== 0)) {
      loading = true;
      loadingPhase = loadingStatus.phase
        ? Drupal.t('Adding: @phase', { '@phase': loadingStatus.phase })
        : Drupal.t('Installing');
      const intervalId = setInterval(async () => {
        const currentStatus = await status();
        const notInProgress = currentStatus.status === 0 && !initiate;
        // If the initiateLag is at 0, there's been sufficient time for the
        // install controller to return a valid status. If that has not
        // happened by then, there is likely an underlying issue that won't be
        // addressed by waiting longer. We categorize this attempt to download /
        // install as "initiated but never started" and clear the interval that
        // is repeatedly invoking this function.
        const initiatedButNeverStarted =
          (initiateLag === 0 || !currentStatus.hasOwnProperty('phase')) &&
          initiate &&
          !currentStatus.status === 0;
        if (notInProgress || initiatedButNeverStarted || !loading) {
          // The process has either completed, or encountered a problem that
          // would not benefit from further iterations of this function. The
          // interval is cleared and the UI is updated to indicate nothing is in
          // progress.
          clearInterval(intervalId);
          loading = false;
        } else {
          // During parts of the process where the Package Manager stage is in
          // use, the status includes the phase of the process taking place.
          // Use this when available, otherwise provide a default message.
          loadingPhase = currentStatus.phase || 'In progress';
        }
        initiateLag -= 1;
        if (intervals % 4 === 1) {
          // Clear announce in the interval immediately after a read so if
          // announce is called again it will be conveyed to the screen reader
          // even if the progress message is unchanged.
          Drupal.announce('');
        }
        if (intervals === 0 || intervals % 4 === 0) {
          if (currentStatus.phase) {
            Drupal.announce(
              Drupal.t(
                'Adding module @module, phase @phase in progress',
                {
                  '@module': project.title,
                  '@phase': currentStatus.phase,
                },
                'assertive',
              ),
            );
          } else {
            Drupal.announce(
              Drupal.t(
                'Adding module @module, in progress',
                {
                  '@module': project.title,
                },
                'assertive',
              ),
            );
          }
        }
        intervals += 1;
      }, 1250);
    }
  };

  onMount(() => {
    // If the module is mid-download or mid-install when the page loads, the UI
    // should reflect that by adding a progress spinner and disabling actions.
    // The app will check periodically to see if the status has changed and
    // update the UI.
    if (ALLOW_UI_INSTALL) {
      showStatus();
    }
  });
</script>

<div class="action-button__wrapper">
  {#if !project.is_compatible}
    <ProjectStatusIndicator {project} statusText={Drupal.t('Not compatible')} />
  {:else if projectInstalled}
    <ProjectStatusIndicator {project} statusText={Drupal.t('Installed')}>
      <span class="action-button__unicode" aria-hidden="true">&#10003&#x20</span
      >
    </ProjectStatusIndicator>
  {:else if projectDownloaded}
    <span>
      {#if ALLOW_UI_INSTALL}
        {#if loading}
          <LoadingEllipsis />
          <Loading positionAbsolute={true} />
        {:else}
          <AddInstallButton
            {project}
            bind:loading
            bind:projectInstalled
            bind:projectDownloaded
            {showStatus}
            alreadyAdded={true}
          />
        {/if}
      {:else}
        <a
          href="{ORIGIN_URL}/admin/modules#module-{project.selector_id}"
          target="_blank"
          rel="noreferrer"
          ><ProjectButtonBase>{Drupal.t('Install')}</ProjectButtonBase></a
        >
      {/if}
    </span>
  {:else}
    <span>
      {#if !PM_VALIDATION_ERROR && ALLOW_UI_INSTALL}
        {#if loading}
          <span class="loading-ellipsis">{loadingPhase}</span>
          <Loading positionAbsolute={true} />
        {:else}
          <AddInstallButton
            {project}
            bind:loading
            bind:projectInstalled
            bind:projectDownloaded
            {showStatus}
          />
        {/if}
      {:else}
        <ProjectButtonBase
          click={() => openPopup(getCommandsPopupMessage(project), project)}
          >{Drupal.t('View Commands')}
          <span class="visually-hidden"
            >{Drupal.t(' for ')} {project.title}</span
          >
        </ProjectButtonBase>
      {/if}
    </span>
  {/if}
</div>

<style>
  .action-button__wrapper {
    padding: 0.5em 0;
    margin-inline-start: auto;
  }

  .action-button__unicode {
    color: #228572;
  }
</style>
