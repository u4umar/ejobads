import { FULL_MODULE_PATH, ORIGIN_URL } from './constants';
// cspell:ignore dont

export const copyCommand = (cmd, project) =>  {
  const getCopyElements = () => {
    const getCopyElement = (suffix) => document.querySelector(`#${project.project_machine_name}-${suffix}`)
    const action = ['Download', 'Install'].includes(cmd) ? cmd.toLowerCase() : 'install-drush';
    return [
      getCopyElement(`${action}-command`),
      getCopyElement(`copied-${action}`),
    ]
  }
  const [copiedCommand, copyReceipt] = getCopyElements();

  copiedCommand.select();
  // For mobile devices.
  copiedCommand.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copiedCommand.value);
  copyReceipt.style.opacity = '1';
  setTimeout(() => {
    copyReceipt.style.transition = 'opacity 0.3s';
    copyReceipt.style.opacity = '0';
  }, 1000);
};

export const getCommandsPopupMessage = (project) => {
  const download = Drupal.t('Download');
  const composerText = Drupal.t(
    'The !use_composer_open recommended way!close to download any Drupal module is with !get_composer_open Composer!close.',
    {
      '!close': '</a>',
      '!use_composer_open':
        '<a href="https://www.drupal.org/docs/develop/using-composer/using-composer-to-install-drupal-and-manage-dependencies#managing-contributed" target="_blank" rel="noreferrer noopener">',
      '!get_composer_open':
        '<a href="https://getcomposer.org/" target="_blank" rel="noopener noreferrer">',
    },
  );
  const composerExistsText = Drupal.t(
    "If you already manage your Drupal application dependencies with Composer, run the following from the command line in your application's Composer root directory",
  );
  const infoText = Drupal.t('This will download the module to your codebase.');
  const composerDontWorkText = Drupal.t(
    "Didn't work? !learn_open Learn how to troubleshoot Composer!close",
    {
      '!learn_open':
        '<a href="https://getcomposer.org/doc/articles/troubleshooting.md" target="_blank" rel="noopener noreferrer">',
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
    'Go to the !module_page_open Extend page!close (admin/modules), check the box next to each module you wish to enable, then click the Install button at the bottom of the page.',
    {
      '!module_page_open': `<a href="${ORIGIN_URL}/admin/modules" target="_blank" rel="noopener noreferrer">`,
      '!close': '</a>',
    },
  );
  const drushText = Drupal.t(
    'Alternatively, you can use !drush_openDrush!close to install it via the command line',
    {
      '!drush_open': '<a href="https://www.drush.org/latest/" target="_blank" rel="noopener noreferrer">',
      '!close': '</a>',
    },
  );
  const installDrush = Drupal.t(
    'If Drush is not installed, this will add the tool to your codebase',
  );
  const copied = Drupal.t('Copied!');
  const downloadAlt = Drupal.t('Copy the download command');
  const installAlt = Drupal.t('Copy the install command');
  const drushAlt = Drupal.t('Copy the install Drush command');
  const copyIcon = `${FULL_MODULE_PATH}/images/copy-icon.svg`;
  const makeButton = (altText, action) => `<button id="${action}-btn"><img src="${copyIcon}" alt="${altText}"/></button>
                <div id="${project.project_machine_name}-copied-${action}" class="copied-action">${copied}</div>`
  const downloadCopyButton = navigator.clipboard ? makeButton(downloadAlt, 'download') : '';
  const installCopyButton = navigator.clipboard  ? makeButton(installAlt, 'install') : '';
  const installDrushCopyButton = navigator.clipboard ? makeButton(drushAlt, 'install-drush') : '';

  const div = document.createElement('div');
  div.classList.add('window');
  div.innerHTML = `<h3>1. ${download}</h3>
              <p>${composerText}</p>
              <p>${composerExistsText}:</p>
              <div class="command-box">
                <input id="${project.project_machine_name}-download-command" value="composer require ${project.composer_namespace}" readonly/>
                ${downloadCopyButton}
              </div>

              <p>${infoText}</p>
              <p>${composerDontWorkText}.</p>
              <p>${downloadModuleText}.</p>
              <h3>2. ${install}</h3>
              <p>${installText}</p>
              <p>${drushText}:</p>
              <div class="command-box">
                <input id="${project.project_machine_name}-install-command" value="drush pm:install ${project.project_machine_name}" readonly/>
                ${installCopyButton}
              </div>
              </div>

              <p>${installDrush}:</p>
              <div class="command-box">
                <input id="${project.project_machine_name}-install-drush-command" value="composer require drush/drush" readonly/>
                ${installDrushCopyButton}
              </div>
              <style>
                .action-link {
                  margin: 0 2px;
                  padding: 0.25rem 0.25rem;
                  border: 1px solid;
                }
              </style>`;
  if (navigator.clipboard) {
    [['download', 'Download'], ['install', 'Install'], ['install-drush', 'Drush']].forEach(([id, command]) => {
      div.querySelector(`#${id}-btn`).addEventListener('click', () => {
        copyCommand(command, project);
      });
    });
  }
  return div;
};

export const openPopup = (getMessage, project) => {
  const message = typeof getMessage === 'function' ? getMessage() : getMessage;
  const popupModal = Drupal.dialog(message, {
    title: project.title,
    dialogClass: 'project-browser-popup',
    width: '50rem',
  });
  popupModal.showModal();
};
