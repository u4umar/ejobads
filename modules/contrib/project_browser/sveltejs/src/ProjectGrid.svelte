<script context="module">
  import Search from './Search.svelte';
  import Filter from './Filter.svelte';
  import Loading from './Loading.svelte';

  export { Search, Filter };
</script>

<script>
  import { createEventDispatcher, setContext } from 'svelte';

  const dispatch = createEventDispatcher();
  const { Drupal } = window;

  export let loading = false;
  export let page = 0;
  export let pageIndex = 0;
  export let pageSize = 12;
  export let rows;
  export let labels = {
    empty: Drupal.t('No records available'),
    loading: Drupal.t('Loading data'),
  };

  $: filteredRows = rows;
  $: visibleRows = filteredRows
    ? filteredRows.slice(pageIndex, pageIndex + pageSize)
    : [];

  setContext('state', {
    getState: () => ({
      page,
      pageIndex,
      pageSize,
      rows,
      filteredRows,
    }),
    setPage: (_page, _pageIndex) => {
      page = _page;
      pageIndex = _pageIndex;
    },
    setRows: (_rows) => {
      filteredRows = _rows;
    },
  });

  function onSearch(event) {
    dispatch('search', event.detail);
  }

  function onSelectCategory(event) {
    dispatch('selectCategory', event.detail);
  }
</script>

<!--Aligns Category filter and Grid cards side by side-->

<div class="container-1">
  <div class="box-1">
    <slot name="left">
      <svelte:component this={Filter} on:selectCategory={onSelectCategory} />
    </slot>
  </div>
  <div class="box-2">
    <slot name="top">
      <div class="slot-top">
        <svelte:component this={Search} on:search={onSearch} />
      </div>
    </slot>
    <div class={`${$$props.class}`}>
      <slot name="head" />
      {#if loading}
        <Loading />
      {:else if visibleRows.length === 0}
        <div>{@html labels.empty}</div>
      {:else}
        <ul>
          <slot rows={visibleRows} />
        </ul>
      {/if}
      <slot name="foot" />
    </div>
  </div>
</div>

<slot name="bottom" />

<style>
  ul {
    display: flex;
    flex-wrap: wrap;
    list-style-type: none;
    margin: 0;
  }
  .slot-top,
  .slot-bottom {
    width: 100%;
    margin-top: 1em;
  }
  @media (min-width: 700px) {
    .container-1 {
      display: flex;
    }
  }
  .container-1 div {
    padding: 10px;
  }
  .box-1 {
    flex: 1;
  }
  .box-2 {
    flex: 4;
  }
  /* Stack cards below category filter on smaller screens */
  @media screen and (max-width: 900px) {
    .container-1 {
      flex-direction: column;
    }
  }
</style>
