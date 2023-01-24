<script>
  import { createEventDispatcher, getContext, onMount } from 'svelte';
  import {
    moduleCategoryFilter,
    moduleCategoryVocabularies,
    activeTab,
  } from './stores';
  import MediaQuery from './MediaQuery.svelte';
  import { normalizeOptions, shallowCompare } from './util';
  import { ORIGIN_URL } from './constants';

  const { Drupal } = window;
  const dispatch = createEventDispatcher();
  const stateContext = getContext('state');

  async function onSelectCategory(event) {
    const state = stateContext.getState();
    const detail = {
      originalEvent: event,
      category: $moduleCategoryFilter,
      page: state.page,
      pageIndex: state.pageIndex,
      pageSize: state.pageSize,
      rows: state.filteredRows,
    };
    dispatch('selectCategory', detail);
    stateContext.setPage(0, 0);
    stateContext.setRows(detail.rows);
  }

  async function fetchAllCategories() {
    const response = await fetch(`${ORIGIN_URL}/drupal-org-proxy/categories`);
    if (response.ok) {
      return response.json();
    }
    return [];
  }

  const apiModuleCategory = fetchAllCategories();
  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export async function setModuleCategoryVocabulary() {
    apiModuleCategory.then((value) => {
      const normalizedValue = normalizeOptions(value[$activeTab]);
      const storedValue = $moduleCategoryVocabularies;
      if (
        storedValue === null ||
        !shallowCompare(normalizedValue, storedValue)
      ) {
        moduleCategoryVocabularies.set(normalizedValue);
      }
    });
  }
  onMount(async () => {
    await setModuleCategoryVocabulary();
  });
</script>

<MediaQuery query="(min-width: 901px)" let:matches>
  <form>
    <details class="pb-categories" open={matches}>
      <summary hidden={matches}>
        <h2>{Drupal.t('Categories')}</h2>
      </summary>
      <fieldset>
        <legend class:visually-hidden={!matches}
          ><span class="visually-hidden">{Drupal.t('Filter by module ')}</span
          >{Drupal.t('Categories')}</legend
        >
        {#await apiModuleCategory then categoryList}
          {#each categoryList[$activeTab] as dt}
            <label class="checkbox-label">
              <input
                type="checkbox"
                id={dt.id}
                bind:group={$moduleCategoryFilter}
                on:change={onSelectCategory}
                value={dt.id}
              />{dt.name}</label
            >
          {/each}
        {/await}
      </fieldset>
    </details>
  </form>
</MediaQuery>

<style>
  summary {
    padding: 0;
    cursor: pointer;
  }
  [open] summary {
    padding-bottom: 0.95rem;
  }
  h2 {
    margin-top: 0;
  }
  summary > h2 {
    display: inline;
    font-size: 1rem;
    padding: 0 0.5rem;
  }
  .checkbox-label {
    display: block;
    padding: 5px 0;
  }
  input {
    margin-right: 10px;
  }
  fieldset {
    border: none;
  }
  legend {
    margin: 1rem 0 0.75rem;
    font-size: 1.802rem;
    font-weight: bold;
    line-height: 1.3;
  }
  label:hover,
  input:hover {
    cursor: pointer;
  }
</style>
