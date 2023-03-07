<script>
  import { createEventDispatcher, getContext } from 'svelte';
  import { sort, sortCriteria } from '../stores';

  export let sortText;
  export let refresh;

  const { Drupal } = window;
  const dispatch = createEventDispatcher();
  const stateContext = getContext('state');

  async function onSort(event) {
    const state = stateContext.getState();
    const detail = {
      originalEvent: event,
      page: state.page,
      pageIndex: state.pageIndex,
      pageSize: state.pageSize,
      rows: state.filteredRows,
      sort: $sort,
    };

    dispatch('sort', detail);
    stateContext.setPage(0, 0);
    stateContext.setRows(detail.rows);
    sortText = $sortCriteria.find((option) => option.id === $sort).text;
    refresh();
  }
</script>

<div class="search__sort">
  <label for="pb-sort">{Drupal.t('Sort by:')}</label>
  <select
    name="pb-sort"
    id="pb-sort"
    bind:value={$sort}
    on:change={onSort}
    class="search__sort-select form-select form-element form-element--type-select"
  >
    {#each $sortCriteria as opt}
      <option value={opt.id}>
        {opt.text}
      </option>
    {/each}
  </select>
</div>

<style>
  .search__sort {
    z-index: 2;
  }

  .search__sort-select {
    border: none;
    background-color: #d3d4d9;
  }
  .search__sort-select:hover {
    cursor: pointer;
  }

  @media screen and (max-width: 855px) {
    .search__sort {
      margin-bottom: 10px;
      margin-top: 10px;
    }
  }
  @media (forced-colors: active) {
    #pb-sort {
      border: 1px solid;
    }
  }
</style>
