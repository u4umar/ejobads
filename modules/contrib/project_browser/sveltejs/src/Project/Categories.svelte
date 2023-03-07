<script>
  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let moduleCategories;
  export let toggleView;

  const extraCategories = moduleCategories.splice(3);
  const { Drupal } = window;
</script>

<div class="categories" data-label="Categories">
  {#if typeof moduleCategories !== 'undefined' && moduleCategories.length}
    <ul
      class:categories__list--grid={toggleView === 'Grid'}
      class="categories__list"
    >
      {#each moduleCategories || [] as category}
        <li class="categories__category">
          {category.name}
        </li>
      {/each}
      {#if extraCategories.length}
        <li class="categories__category categories__category--extra">
          {Drupal.t('+ @count more', { '@count': extraCategories.length })}
        </li>
      {/if}
    </ul>
  {/if}
</div>

<style>
  .categories {
    clear: both;
    margin-top: 1em;
    position: center;
  }
  .categories__list {
    margin: 0.25em 0 0.25em 0;
    padding: 0;
    display: inline-block;
    width: 100%;
    height: 20px;
  }
  .categories__category {
    list-style: none;
    display: inline-block;
    margin-top: 2px;
    margin-bottom: 2px;
    padding: 2px 9px;
    border-radius: 25px;
    background-color: #e5e5e5;
    font-size: 0.9em;
    font-weight: 600;
    color: #4f4f4f;
  }
  .categories__category:not(:first-child, .categories__category--extra) {
    margin-inline-start: 4px;
  }
  .categories .categories__category--extra {
    background-color: transparent;
    border: 1px solid #bbb;
  }
  .categories__list--grid {
    text-align: center;
  }
  @media (forced-colors: active) {
    .categories__category {
      border: 1px solid;
    }
  }
</style>
