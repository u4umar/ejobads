<script context="module">
  import Search from './Search/Search.svelte';
  import Filter from './Filter.svelte';
  import Loading from './Loading.svelte';
  import { pageSize, activeTab } from './stores';

  export { Search, Filter };
</script>

<script>
  import { setContext } from 'svelte';

  const { Drupal } = window;

  export let loading = false;
  export let page = 0;
  export let pageIndex = 0;
  export let rows;
  export let labels = {
    empty: Drupal.t('No modules found'),
    loading: Drupal.t('Loading data'),
  };

  $: filteredRows = rows;
  $: visibleRows = filteredRows
    ? filteredRows.slice(pageIndex, pageIndex + $pageSize)
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
</script>

<!--Aligns Category filter and Grid cards side by side-->
<slot name="head" />
<div class="project-browser__container">
  <aside class="project-browser__aside">
    <slot name="left" />
  </aside>
  <div class="project-browser__main">
    <div class="projects-container">
      {#if loading}
        <Loading />
      {:else if visibleRows.length === 0}
        <div>{@html labels.empty}</div>
      {:else}
        <ul
          class="projects-list"
          id={$activeTab}
          role="tabpanel"
          tabindex="0"
          aria-labelledby={$activeTab}
        >
          <slot rows={visibleRows} />
        </ul>
      {/if}
      <slot name="foot" />
    </div>
  </div>
</div>

<slot name="bottom" />

<style>
  .projects-list {
    display: flex;
    flex-wrap: wrap;
    list-style-type: none;
    margin: 0;
  }

  .project-browser__aside {
    flex: 1;
  }
  .project-browser__main {
    flex: 4;
  }

  @media (min-width: 700px) {
    .project-browser__container {
      display: flex;
    }
  }
  /* Stack cards below category filter on smaller screens */
  @media screen and (max-width: 900px) {
    .project-browser__container {
      flex-direction: column;
    }
  }
</style>
