<script>
  import { onMount } from 'svelte';
  import ActionButton from './Project/ActionButton.svelte';
  import Image from './Project/Image.svelte';
  import ImageCarousel from './ImageCarousel.svelte';
  import { FULL_MODULE_PATH, ORIGIN_URL } from './constants';
  import { moduleCategoryFilter, page } from './stores';

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

<ol class="pb-breadcrumb">
  <li class="pb-breadcrumb-item">
    <a href="/admin/modules/browse"> ‹ {Drupal.t('Browse')}</a>
  </li>
  <li class="pb-breadcrumb-item">
    ‹ {project.title}
  </li>
</ol>

<div class="container">
  <div class="box-1">
    <Image sources={project.logo} class="project-logo" />
    <div class="action-btn">
      <ActionButton {project} />
    </div>
    <div class="division" />
    <h4>{Drupal.t('Details')}</h4>
    <div class="project-data">
      {#if project.module_categories.length}
        <p id="categories">{Drupal.t('Categories:')}</p>
        <ul class="category-list" aria-labelledby="categories">
          {#each project.module_categories || [] as category}
            <li on:click={filterByCategory(category.id)} class="category on">
              {category.name}
            </li>
          {/each}
        </ul>
      {/if}
      <div class="module-details">
        {#if project.is_compatible}
          <img
            class="icon"
            src="{FULL_MODULE_PATH}/images/compatible-icon.svg"
            alt={Drupal.t('Compatible')}
          />
          <p>{Drupal.t('Compatible with your Drupal installation')}</p>
        {/if}
        {#if project.project_usage_total !== -1}
          <img
            class="icon"
            src="{FULL_MODULE_PATH}/images/project-usage-icon.svg"
            alt={Drupal.t('Project Usage')}
          />
          <p>
            {project.project_usage_total
              .toString()
              .replace(/\B(?=(\d{3})+(?!\d))/g, ',')}{Drupal.t(
              ' sites report using this module',
            )}
          </p>
        {/if}
        {#if project.is_covered}
          <img
            class="icon"
            src="{FULL_MODULE_PATH}/images/blue-security-shield-icon.svg"
            alt={Drupal.t('Security Advisory Coverage')}
          />
          <p>
            {Drupal.t(
              'Stable releases for this project are covered by the security advisory policy',
            )}
          </p>
        {/if}
      </div>
    </div>
  </div>
  <div class="box-2">
    <h2>{project.title}</h2>
    <p>{Drupal.t('By ')}{project.author.name}</p>
    {#if project.project_images.length}
      <div class="images">
        <ImageCarousel sources={project.project_images} />
      </div>
    {/if}
    <div id="description-wrapper">{@html project.body.value}</div>
  </div>
</div>

<style>
  .container {
    display: flex;
    text-align: left;
  }
  .pb-breadcrumb {
    list-style-type: none;
  }
  .pb-breadcrumb-item {
    display: inline;
  }
  .box-1 {
    flex: 1;
    padding: 40px;
    display: flex;
    flex-direction: column;
  }
  .box-2 {
    flex: 4;
    padding: 40px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }
  .action-btn {
    margin: 20px;
  }
  .division {
    border: 1px solid #000000;
  }
  #categories {
    font-weight: bold;
  }
  .module-details {
    display: grid;
    grid-template-columns: 0.5fr 2fr;
    line-height: 20px;
  }
  .icon {
    width: 25px;
    height: 25px;
    margin: 10% 0 0 40%;
  }
  p {
    margin: 5px 15px;
  }
  .category-list {
    margin: 0.25em 0 0.25em 0;
    padding: 0;
    display: inline-block;
    width: 100%;
    height: 20px;
    cursor: pointer;
  }
  .category.on {
    list-style: none;
    display: inline-block;
    margin-top: 5px;
    margin-bottom: 2px;
    margin-left: 7px;
    padding: 2px 9px;
    border-radius: 25px;
    background-color: #e5e5e5;
    font-size: 0.9em;
    font-weight: 600;
    color: #4f4f4f;
  }
  :global(.project-logo) {
    min-height: 200px;
  }
  .images {
    margin: 20px 0;
  }
  a {
    text-decoration: none;
  }
  @media only screen and (max-width: 600px) {
    .container {
      flex-direction: column;
    }
  }
</style>
