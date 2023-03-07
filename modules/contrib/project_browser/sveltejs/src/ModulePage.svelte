<script>
  import { onMount } from 'svelte';
  import ActionButton from './Project/ActionButton.svelte';
  import Image from './Project/Image.svelte';
  import ImageCarousel from './ImageCarousel.svelte';
  import { ORIGIN_URL } from './constants';
  import { moduleCategoryFilter, page } from './stores';
  import ProjectIcon from './Project/ProjectIcon.svelte';

  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let project;
  const { Drupal } = window;

  function filterByCategory(id) {
    $moduleCategoryFilter = [id];
    $page = 0;
    window.location.href = `${ORIGIN_URL}/admin/modules/browse`;
  }

  onMount(() => {
    const anchors = document
      .getElementById('description-wrapper')
      .getElementsByTagName('a');
    for (let i = 0; i < anchors.length; i++) {
      anchors[i].setAttribute('target', '_blank');
    }
  });
</script>

<a
  class="module-page--back-to-browsing action-link"
  href="{ORIGIN_URL}/admin/modules/browse"
>
  <span aria-hidden="true">&#9001&#xA0</span>
  {Drupal.t('Back to Browsing')}
</a>

<div class="module-page__wrapper">
  <div class="module-page__sidebar">
    <Image sources={project.logo} class="module-page__project-logo" />
    <div class="module-page__action-button-wrapper">
      <ActionButton {project} />
    </div>
    <div class="module-page__divider">&nbsp;</div>
    <h4>{Drupal.t('Details')}</h4>
    <div class="module-page__project-data">
      {#if project.module_categories.length}
        <p class="module-page__categories-label" id="categories">
          {Drupal.t('Categories:')}
        </p>
        <ul class="module-page__category-list" aria-labelledby="categories">
          {#each project.module_categories || [] as category}
            <li
              on:click={() => filterByCategory(category.id)}
              class="module-page__category-list-item"
            >
              {category.name}
            </li>
          {/each}
        </ul>
      {/if}
      <div class="module-page__module-details-grid">
        {#if project.is_compatible}
          <ProjectIcon
            type="compatible"
            variant="module-details"
            classes="module-page__module-details-grid__icon"
          />
          <p class="module-page__module-details-grid__description">
            {Drupal.t('Compatible with your Drupal installation')}
          </p>
        {/if}
        {#if project.project_usage_total !== -1}
          <ProjectIcon
            type="usage"
            variant="module-details"
            classes="module-page__module-details-grid__icon"
          />
          <p class="module-page__module-details-grid__description">
            {project.project_usage_total
              .toString()
              .replace(/\B(?=(\d{3})+(?!\d))/g, ',')}{Drupal.t(
              ' sites report using this module',
            )}
          </p>
        {/if}
        {#if project.is_covered}
          <ProjectIcon
            type="status"
            variant="module-details"
            classes="module-page__module-details-grid__icon"
          />
          <p class="module-page__module-details-grid__description">
            {Drupal.t(
              'Stable releases for this project are covered by the security advisory policy',
            )}
          </p>
        {/if}
      </div>
    </div>
  </div>
  <div class="module-page__main">
    <h2 class="module-page__h2">{project.title}</h2>
    <p class="module-page__author">
      {Drupal.t('By ')}{project.author.name}
    </p>
    {#if project.project_images.length}
      <div class="module-page__carousel-wrapper">
        <ImageCarousel sources={project.project_images} />
      </div>
    {/if}
    <div class="module-page__project-description" id="description-wrapper">
      {@html project.body.value}
    </div>
  </div>
</div>

<style>
  .module-page__wrapper {
    display: flex;
    text-align: start;
  }
  .module-page__sidebar {
    flex: 1;
    padding: 40px;
    display: flex;
    flex-direction: column;
  }
  .module-page__main {
    flex: 4;
    padding: 40px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }
  .module-page__action-button-wrapper {
    margin: 20px;
  }
  .module-page__divider {
    border: 1px solid #000000;
  }
  .module-page__categories-label {
    font-weight: bold;
  }
  .module-page__module-details-grid {
    display: grid;
    grid-template-columns: 0.5fr 2fr;
    line-height: 20px;
    margin-top: 10px;
  }

  .module-page__module-details-grid > :global(*:nth-child(odd)) {
    margin: 10% 20% 0 40%;
  }

  .module-page__module-details-grid > :global(*:nth-child(even)) {
    margin: 5px 15px;
  }
  .module-page__category-list {
    margin: 0.25em 0 0.25em 0;
    padding: 0;
    display: inline-block;
    width: 100%;
    height: 20px;
    cursor: pointer;
  }
  .module-page__category-list-item {
    list-style: none;
    display: inline-block;
    margin-top: 5px;
    margin-bottom: 2px;
    margin-inline-start: 7px;
    padding: 2px 9px;
    border-radius: 25px;
    background-color: #e5e5e5;
    font-size: 0.9em;
    font-weight: 600;
    color: #4f4f4f;
  }
  :global(.module-page__project-logo) {
    min-height: 200px;
  }
  .module-page__carousel-wrapper {
    margin: 20px 0;
  }
  .module-page--back-to-browsing {
    text-decoration: none;
  }
  @media only screen and (max-width: 600px) {
    .module-page__wrapper {
      flex-direction: column;
    }
  }
</style>
