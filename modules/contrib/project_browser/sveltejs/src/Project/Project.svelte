<script>
  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let project;
  export let toggleView;
  import ActionButton from './ActionButton.svelte';
  import Image from './Image.svelte';
  import Categories from './Categories.svelte';
  import ProjectIcon from './ProjectIcon.svelte';
  import { focusedElement } from '../stores';
  import { FULL_MODULE_PATH, ORIGIN_URL } from '../constants';

  const { Drupal } = window;
</script>

<li class="project project--{toggleView.toLowerCase()}">
  <div class="project__logo">
    <Image sources={project.logo} class="project__logo-image" />
  </div>
  <div class="project__main">
    <div class="project__middle">
      <h3
        on:click={() => {
          $focusedElement = `${project.project_machine_name}_title`;
        }}
        class="project__title"
      >
        <a
          id="{project.project_machine_name}_title"
          class="project__link"
          href="{ORIGIN_URL}/admin/modules/browse/{project.project_machine_name}"
          rel="noreferrer">{project.title}</a
        >
      </h3>
      <div class="project__body">{@html project.body.summary}</div>
      <Categories {toggleView} moduleCategories={project.module_categories} />
    </div>
  </div>
  <div
    class="project__icons"
    class:warnings={project.warnings && project.warnings.length > 0}
  >
    {#if project.is_covered}
      <span class="project__status-icon">
        <ProjectIcon type="status" />
        <!-- Show the security policy description if it is accompanied by warnings,
             since those also have descriptions.  -->
        {#if project.warnings && project.warnings.length > 0}
          <small>{Drupal.t('Covered by the security advisory policy')}</small>
        {/if}
      </span>
    {/if}
    {#if toggleView === 'Grid' && project.project_usage_total !== -1}
      <div class="project__install-count-container">
        <span class="project__install-count"
          >{Drupal.t('@count installs ', {
            '@count': project.project_usage_total.toLocaleString(),
          })}</span
        >
      </div>
    {/if}
    {#if project.warnings && project.warnings.length > 0}
      {#each project.warnings as warning}
        <span class="project__status-icon">
          <img src="{FULL_MODULE_PATH}/images/triangle-alert.svg" alt="" />
          <small>{@html warning}</small>
        </span>
      {/each}
    {/if}
    {#if toggleView === 'List' && project.project_usage_total !== -1}
      <div class="project__project-usage-container">
        <div class="project__image">
          <ProjectIcon type="usage" variant="project-listing" />
        </div>
        <div class="project__active-installs-text">
          {project.project_usage_total.toLocaleString()} Active Installs
        </div>
      </div>
    {/if}
    <!--If there are no warnings, there is space to include the action button
        in the icons container -->
    {#if !project.warnings || project.warnings.length === 0}
      <ActionButton {project} />
    {/if}
  </div>
  <!--If there are warnings, the action button needs to be moved out of the
      icons container to provide space for the warning descriptions. -->
  {#if project.warnings && project.warnings.length > 0}
    <ActionButton {project} />
  {/if}
</li>

<style>
  /* Small devices (portrait tablets and large phones, 600px and up) */
  @media only screen and (min-width: 53.75rem) {
    .project--grid .project__logo {
      overflow: hidden;
      display: flex;
      align-items: center;
    }
    .project--list .project__logo {
      margin-top: -20px;
      display: flex;
      align-items: center;
    }
    .project--list.project {
      padding: 0 2rem;
    }
  }

  /* One column card view */
  @media screen and (max-width: 75rem) {
    .project--grid.project {
      width: 100%;
    }
  }

  /* Two column card view (laptops/desktops, 1200px and up) */
  @media only screen and (min-width: 75rem) {
    .project--grid.project {
      width: 49%;
    }
    .project--list.project {
      width: 100%;
      padding: 0 2rem;
    }
  }

  @media only screen and (min-width: 87.5rem) {
    .project--grid.project {
      width: 32%;
    }
  }

  .project {
    position: relative;
  }
  .project--grid.project {
    display: flex;
    flex-direction: column;
    margin-bottom: 2em;
    border: 1px solid rgba(212, 212, 218, 0.8);
    border-radius: 2px;
    box-shadow: 0 4px 10px rgb(0 0 0 / 10%);
    margin-inline-end: 0.5vw;
  }

  .project--grid .project__title {
    margin-top: 0.25em;
    text-align: center;
  }

  .project__link {
    text-decoration: none;
    color: black;
  }
  .project__link:hover {
    color: #003ecc;
    text-decoration: underline;
  }

  .project--grid .project__main {
    display: flex;
    background: white;
    position: relative;
    padding: 1em 1em;
    flex-direction: column;
  }

  .project--grid .project__body {
    text-align: center;
  }

  .project--grid .project__icons {
    display: flex;
    margin-top: auto;
    padding: 1em 1em;
    height: 3em;
  }
  .project__body {
    font-size: 15px;
  }
  .project--list.project {
    display: grid;
    grid-template-columns: 20% 1fr;
    grid-template-rows: 1fr;
    grid-template-areas:
      'aside main'
      'aside footer';
  }

  .project--list .project__image {
    padding-inline-start: 4em;
  }

  .project--list .project__icons {
    grid-area: footer;
  }

  .project--list .project__logo {
    grid-area: aside;
    padding-top: 2rem;
    padding-bottom: 2rem;
    padding-inline: 0 2rem;
  }
  .project--list .project__main {
    grid-area: main;
  }
  .project--list .project__title {
    padding-top: 10px;
  }
  .project--list.project {
    margin-bottom: 2em;
    background: #ffffff;
    border: 0.5px solid #a4a2a2;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  }
  .project--list .project__icons {
    display: flex;
    padding-top: 1rem;
    padding-bottom: 1rem;
    padding-inline: 0 1em;
  }
  .project__icons :global(p) {
    display: inline;
  }
  .project__icons.warnings {
    display: block;
  }
  .project__icons.warnings span {
    display: list-item;
  }
  .project__icons.warnings img {
    display: inline;
    width: 1.2rem;
    position: relative;
    bottom: -0.25rem;
  }
  .warnings + :global(.action) {
    margin-inline-end: 1em;
    margin-bottom: 1em;
  }
  .project__project-usage-container {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .project__status-icon {
    width: 2.4em;
    margin-inline-end: 0.5em;
    display: block;
  }
  .project__install-count {
    margin-inline-start: 10px;
    font-size: 13px;
  }
  .project__active-installs-text {
    font-size: 13px;
  }
</style>
