import {FULL_MODULE_PATH, ORIGIN_URL} from './constants';

export const copyCommand = (cmd, project) =>  {
  const copiedCommand = document.getElementById(
    cmd === 'Download'
      ? `${project.project_machine_name}-download-command`
      : `${project.project_machine_name}-install-command`,
  );
  copiedCommand.select();
  // For mobile devices.
  copiedCommand.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copiedCommand.value);
  const copyReceipt = document.getElementById(
    cmd === 'Download'
      ? `${project.project_machine_name}-copied-download`
      : `${project.project_machine_name}-copied-install`,
  );
  copyReceipt.style.opacity = '1';
  setTimeout(() => {
    copyReceipt.style.transition = 'opacity 0.3s';
    copyReceipt.style.opacity = '0';
  }, 1000);
}

export const getCommandsPopupMessage = (project) =>  {
  const download = Drupal.t('Download');
  const composerText = Drupal.t(
    'The !use_composer_open recommended way to download any Drupal module!close is with !get_composer_open Composer!close.</a>',
    {
      '!close': '</a>',
      '!use_composer_open':
        '<a href="https://www.drupal.org/docs/develop/using-composer/using-composer-to-install-drupal-and-manage-dependencies#managing-contributed" target="_blank" rel="noreferrer">',
      '!get_composer_open':
        '<a href="https://getcomposer.org/" target="_blank">',
    },
  );
  const composerExistsText = Drupal.t(
    "If you already manage your Drupal application dependencies with Composer, run the following from the command line in your application's Composer root directory",
  );
  const infoText = Drupal.t(
    'This will download the module to your codebase.',
  );
  const composerDontWorkText = Drupal.t(
    "Didn't work? !learn_open Learn how to troubleshoot Composer!close",
    {
      '!learn_open':
        '<a href="https://getcomposer.org/doc/articles/troubleshooting.md" target="_blank" rel="noreferrer">',
      '!close': '</a>',
    },
  );
  const downloadModuleText = Drupal.t(
    'If you cannot use Composer, you may !dl_manually_open download the module manually through your browser!close',
    {
      '!dl_manually_open':
        '<a href="https://www.drupal.org/docs/user_guide/en/extend-module-install.html#s-using-the-administrative-interface" target="_blank" rel="noreferrer">',
      '!close': '</a>',
    },
  );
  const install = Drupal.t('Install');
  const installText = Drupal.t(
    'To use the module you must next install it. Visit the !module_page_open modules page!close to install the module using your web browser!close',
    {
      '!module_page_open': `<a href="${ORIGIN_URL}/admin/modules#module-${project.project_machine_name}" target="_blank" rel="noreferrer">`,
      '!close': '</a>',
    },
  );
  const drushText = Drupal.t(
    'Alternatively, you can use !drush_openDrush!close to install it via the command line',
    {
      '!drush_open':
        '<a href="https://www.drush.org/latest/" target="_blank">',
      '!close': '</a>',
    },
  );
  const copied = Drupal.t('Copied!');
  const downloadCopyButton = navigator.clipboard ? `<button id="download-btn"><img src="${FULL_MODULE_PATH}/images/copy-icon.svg" alt="${Drupal.t('Copy the download command')}"/></button>
                <div id="${project.project_machine_name}-copied-download" class="copied-download">${copied}</div>` : '';
  const installCopyButton = navigator.clipboard ? `<button id="install-btn"><img src="${FULL_MODULE_PATH}/images/copy-icon.svg" alt="${Drupal.t('Copy the install command')}"/></button>
                <div id="${project.project_machine_name}-copied-install" class="copied-install">${copied}</div>` : '';

  const div = document.createElement('div');
  div.classList.add('window');
  div.innerHTML = `<h3>1. ${download}</h3>
              <p>${composerText}</p>
              <p>${composerExistsText}:</p>
              <div id="download-cmd">
                <input id="${project.project_machine_name}-download-command" value="composer require ${project.composer_namespace}" readonly/>
                ${downloadCopyButton}
              </div>
              <p>${infoText}</p>
              <p>${composerDontWorkText}.</p>
              <p>${downloadModuleText}.</p>
              <h3>2. ${install}</h3>
              <p>${installText}.</p>
              <p>${drushText}:</p>
              <div id="install-cmd">
                <input id="${project.project_machine_name}-install-command" value="drush pm:install ${project.project_machine_name}" readonly/>
                ${installCopyButton}
              </div>`;
  if (navigator.clipboard) {
    div.querySelector('#download-btn').addEventListener('click', () => {
      copyCommand('Download', project);
    });
    div.querySelector('#install-btn').addEventListener('click', () => {
      copyCommand('Install', project);
    });
  }
  return div;
}

export const openPopup = (getMessage, project) =>  {
  const message =
    typeof getMessage === 'function' ? getMessage() : getMessage;
  const popupModal = Drupal.dialog(message, {
    title: project.title,
    dialogClass: 'project-browser-popup',
    width: '50rem',
  });
  popupModal.showModal();
}
