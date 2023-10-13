<script>
  import { onMount } from 'svelte';
  import { withPrevious } from 'svelte-previous';
  import ProjectGrid, { Search, Filter } from './ProjectGrid.svelte';
  import Pagination from './Pagination.svelte';
  import Project from './Project/Project.svelte';
  import Tabs from './Tabs.svelte';
  import {
    filters,
    rowsCount,
    moduleCategoryFilter,
    isFirstLoad,
    page,
    sort,
    focusedElement,
    searchString,
    activeTab,
    categoryCheckedTrack,
    sortCriteria,
    preferredView,
    pageSize,
    isPackageManagerRequired,
  } from './stores';
  import MediaQuery from './MediaQuery.svelte';
  import {
    ACTIVELY_MAINTAINED_ID,
    COVERED_ID,
    ALL_VALUES_ID,
    DEFAULT_SOURCE_ID,
    CURRENT_SOURCES_KEYS,
    ORIGIN_URL,
    FULL_MODULE_PATH,
    SORT_OPTIONS,
    MODULE_STATUS,
    ALLOW_UI_INSTALL,
    PM_VALIDATION_ERROR,
    ACTIVE_PLUGINS,
  } from './constants';
  // cspell:ignore tabwise

  const { Drupal } = window;
  const { announce } = Drupal;

  let data;
  let rows = [];
  let sources = [];
  let dataArray = [];
  const pageIndex = 0; // first row

  let loading = true;
  let sortText = $sortCriteria.find((option) => option.id === $sort).text;
  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let searchText;
  searchString.subscribe((value) => {
    searchText = value;
  });
  let toggleView = 'Grid';
  preferredView.subscribe((value) => {
    toggleView = value;
  });
  const [currentPage, previousPage] = withPrevious(0);
  $: $currentPage = $page;
  let element = '';
  focusedElement.subscribe((value) => {
    element = value;
  });
  let filterComponent;
  let searchComponent;

  /**
   * Load data from Drupal.org API.
   *
   * @param {number|string} _page
   *   The page number.
   *
   * @return {Promise<void>}
   *   Empty promise that resolves on content load.*
   */
  async function load(_page) {
    loading = true;
    const searchParams = new URLSearchParams({
      page: _page,
      limit: $pageSize,
      sort: $sort,
      source: $activeTab,
    });
    if (searchText) {
      searchParams.set('search', searchText);
    }
    if ($moduleCategoryFilter && $moduleCategoryFilter.length) {
      searchParams.set('categories', $moduleCategoryFilter);
    }
    if ($filters.developmentStatus && $filters.developmentStatus.length) {
      searchParams.set('development_status', $filters.developmentStatus);
    }
    if ($filters.maintenanceStatus && $filters.maintenanceStatus.length) {
      searchParams.set('maintenance_status', $filters.maintenanceStatus);
    }
    if ($filters.securityCoverage && $filters.securityCoverage.length) {
      searchParams.set('security_advisory_coverage', $filters.securityCoverage);
    }
    if (Object.keys($categoryCheckedTrack).length !== 0) {
      searchParams.set(
        'tabwise_categories',
        JSON.stringify($categoryCheckedTrack),
      );
    }
    const url = `${ORIGIN_URL}/drupal-org-proxy/project?${searchParams.toString()}`;

    const res = await fetch(url);
    if (res.ok) {
      data = await res.json();
      // A list of the available sources to get project data.
      sources = Object.keys(data);
      dataArray = Object.values(data);
      rows = data[$activeTab].list;
      $rowsCount = data[$activeTab].totalResults;
      $isPackageManagerRequired = data[$activeTab].isPackageManagerRequired;
    } else {
      rows = [];
      $rowsCount = 0;
    }
    loading = false;
  }

  async function filterRecommended() {
    // Show recommended projects on initial page load only when no filters are applied.
    if (
      $filters.developmentStatus.length === 0 &&
      $filters.maintenanceStatus.length === 0 &&
      $filters.securityCoverage.length === 0
    ) {
      $filters.maintenanceStatus = ACTIVELY_MAINTAINED_ID;
      $filters.securityCoverage = COVERED_ID;
      $filters.developmentStatus = ALL_VALUES_ID;
    }
    isFirstLoad.set(false);
  }

  /**
   * Load remote data when the Svelte component is mounted.
   */
  onMount(async () => {
    // If current active plugin is disabled, remove storage keys and reload page.
    const settingsActiveTab = JSON.stringify(DEFAULT_SOURCE_ID);
    if (
      $activeTab !== settingsActiveTab &&
      CURRENT_SOURCES_KEYS.indexOf($activeTab) === -1
    ) {
      sessionStorage.removeItem('activeTab');
      sessionStorage.removeItem('categoryFilter');
      sessionStorage.removeItem('categoryCheckedTrack');
      sessionStorage.setItem('activeTab', settingsActiveTab);
      window.location.reload();
    }
    // Only filter by recommended on first page load.
    if ($isFirstLoad) {
      await filterRecommended();
    }

    await load($page);
    const focus = document.getElementById(element);
    if (focus) {
      focus.focus();
      $focusedElement = '';
    }
  });

  function onPageChange(event) {
    page.set(event.detail.page);
    load($page);
  }

  function onPageSizeChange() {
    page.set(0);
    load($page);
  }

  async function onSearch(event) {
    searchText = event.detail.searchText;
    await load(0);
    page.set(0);
  }

  async function onSelectCategory(event) {
    moduleCategoryFilter.set(event.detail.category);
    await load(0);
    page.set(0);
  }
  async function onSort(event) {
    sort.set(event.detail.sort);
    sortText = $sortCriteria.find((option) => option.id === $sort).text;
    await load(0);
    page.set(0);
  }
  async function onAdvancedFilter(event) {
    $filters.developmentStatus = event.detail.developmentStatus;
    $filters.maintenanceStatus = event.detail.maintenanceStatus;
    $filters.securityCoverage = event.detail.securityCoverage;

    await load(0);
    page.set(0);
  }

  async function onToggle(val) {
    if (val !== toggleView) toggleView = val;
    preferredView.set(val);
  }

  async function toggleRows(event) {
    searchComponent.onSearch(event);
    const { target } = event.detail.event;
    const parent = target.parentNode;
    // Remove all current selected tabs
    parent
      .querySelectorAll('[aria-selected="true"]')
      .forEach((t) => t.setAttribute('aria-selected', false));
    // Set this tab as selected
    target.setAttribute('aria-selected', true);
    filterComponent.setModuleCategoryVocabulary();
    $categoryCheckedTrack[$activeTab] = $moduleCategoryFilter;
    $moduleCategoryFilter = [];
    $activeTab = event.detail.pluginId;
    $moduleCategoryFilter =
      typeof $categoryCheckedTrack[$activeTab] !== 'undefined'
        ? $categoryCheckedTrack[$activeTab]
        : [];

    $sortCriteria = SORT_OPTIONS[$activeTab];
    const sortMatch = $sortCriteria.find((option) => option.id === $sort);
    if (typeof sortMatch === 'undefined') {
      $sort = $sortCriteria[0].id;
    }

    // Move to page 0 when switching sources as there's no guarantee the new
    // source has enough results to reach whatever the current page is.
    page.set(0);
    await load(0);
  }

  /**
   * Refreshes the live region after a filter or search completes.
   */
  const refreshLiveRegion = () => {
    if ($rowsCount) {
      // Set announce() to an empty string. This ensures the result count will
      // be announced after filtering even if the count is the same.
      announce('');

      // The announcement is delayed by 210 milliseconds, a wait that is
      // slightly longer than the 200 millisecond debounce() built into
      // announce(). This ensures that the above call to reset the aria live
      // region to an empty string actually takes place instead of being
      // debounced.
      setTimeout(() => {
        announce(
          Drupal.t('@count Results for @active_tab, Sorted by @sortText', {
            '@count': $rowsCount
              .toString()
              .replace(/\B(?=(\d{3})+(?!\d))/g, ','),
            '@sortText': sortText,
            '@active_tab': ACTIVE_PLUGINS[$activeTab],
          }),
        );
      }, 210);
    }
  };

  document.onmouseover = function setInnerDocClickTrue() {
    window.innerDocClick = true;
  };

  document.onmouseleave = function setInnerDocClickFalse() {
    window.innerDocClick = false;
  };

  // Handles back button functionality to go back to the previous page the user was on before.
  window.addEventListener('popstate', () => {
    // Confirm the popstate event was a back button action by checking that
    // the user clicked out of the document.
    if (!window.innerDocClick) {
      page.set($previousPage);
      load($page);
    }
  });

  window.onload = { onSearch };
  // Removes initial loader if it exists.
  const initialLoader = document.getElementById('initial-loader');
  if (initialLoader) {
    initialLoader.remove();
  }
</script>

<MediaQuery query="(min-width: 1200px)" let:matches>
  <ProjectGrid {loading} {rows} {pageIndex} {$pageSize} let:rows>
    <div slot="head">
      <Tabs {dataArray} on:tabChange={toggleRows} />
      <Search
        bind:this={searchComponent}
        on:search={onSearch}
        on:sort={onSort}
        on:advancedFilter={onAdvancedFilter}
        on:selectCategory={onSelectCategory}
        {searchText}
        {refreshLiveRegion}
      />

      <div class="search-results-wrapper">
        <div class="search-results">
          {#each dataArray as dataValue}
            {#if $activeTab === dataValue.pluginId}
              <span id="output">
                {$rowsCount &&
                  $rowsCount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')}
                {Drupal.t('Results')}
              </span>
            {/if}
          {/each}
        </div>

        {#if matches}
          <div class="project-browser__toggle-buttons">
            <button
              class:project-browser__selected-tab={toggleView === 'List'}
              class="project-browser__toggle project-browser__list-button"
              value="List"
              on:click={(e) => {
                toggleView = 'List';
                onToggle(e.target.value);
              }}
            >
              <img
                class="project-browser__list-icon"
                src="{FULL_MODULE_PATH}/images/list.svg"
                alt=""
              />
              {Drupal.t('List')}
            </button>
            <button
              class:project-browser__selected-tab={toggleView === 'Grid'}
              class="project-browser__toggle project-browser__grid-button"
              value="Grid"
              on:click={(e) => {
                toggleView = 'Grid';
                onToggle(e.target.value);
              }}
            >
              <img
                class="project-browser__grid-icon"
                src="{FULL_MODULE_PATH}/images/grid-fill.svg"
                alt=""
              />
              {Drupal.t('Grid')}
            </button>
          </div>
        {/if}
      </div>

      <!-- If Package Manager is required and UI installs are enabled,but the
           site configuration does not support them, display a message
           informing the user what must be changed for UI installs to work. -->
      {#if $isPackageManagerRequired && PM_VALIDATION_ERROR && typeof PM_VALIDATION_ERROR === 'string' && MODULE_STATUS.package_manager && ALLOW_UI_INSTALL}
        <div class="project-browser__install-warning">
          <p class="project-browser__warning-header">
            <strong>{Drupal.t('Unable to download modules via the UI')}</strong>
          </p>
          <p class="project-browser__warning">
            <em>{@html PM_VALIDATION_ERROR}</em>
          </p>
        </div>
      {/if}
      <Pagination
        page={$page}
        count={$rowsCount}
        on:pageChange={onPageChange}
        on:pageSizeChange={onPageSizeChange}
      />
    </div>

    <div slot="left">
      <Filter
        on:selectCategory={onSelectCategory}
        bind:this={filterComponent}
      />
    </div>
    {#each rows as row, index (row)}
      <Project toggleView={!matches ? 'Grid' : toggleView} project={row} />
    {/each}
    <div slot="bottom">
      <Pagination
        page={$page}
        count={$rowsCount}
        on:pageChange={onPageChange}
        on:pageSizeChange={onPageSizeChange}
      />
    </div>
  </ProjectGrid>
</MediaQuery>

<style>
  .project-browser__toggle {
    display: flex;
    justify-content: space-evenly;
    align-items: center;
    margin-bottom: 1.5em;
    font-family: inherit;
    color: #232429;
    background-color: #d3d4d9;
    width: 80.41px;
    height: 30px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
    border: none;
  }

  .project-browser__list-icon,
  .project-browser__grid-icon {
    pointer-events: none;
  }
  .project-browser__toggle:first-child {
    margin-inline-start: auto;
  }
  .project-browser__toggle-buttons {
    display: flex;
    margin-inline-end: 25px;
    font-weight: bold;
  }
  .project-browser__toggle:focus {
    box-shadow: 0 0 0 2px #fff, 0 0 0 5px #26a769;
  }
  .project-browser__toggle.project-browser__list-button {
    margin-inline-end: 5px;
    border-radius: 2px 0 0 2px;
    cursor: pointer;
  }
  .project-browser__toggle.project-browser__grid-button {
    border-radius: 0 2px 2px 0;
    cursor: pointer;
    margin-inline-end: 5px;
  }
  .project-browser__selected-tab {
    background-color: #adaeb3;
  }
  .search-results {
    font-weight: bold;
    margin-inline-start: 10px;
    margin-bottom: 5px;
  }
  .project-browser__install-warning {
    border: 1px solid red;
    padding: 1em;
  }
  .project-browser__warning {
    margin: 0.5em 0;
  }
  .project-browser__warning-header {
    color: red;
  }
  .search-results-wrapper {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #dee2e6;
  }
  #output {
    display: inline-block;
    font-family: sans-serif;
    font-style: normal;
    font-weight: 700;
    font-size: 14px;
    line-height: 21px;
    margin-left: 20px;
  }

  @media (forced-colors: active) {
    .project-browser__toggle {
      border: 1px solid;
    }
    @media (prefers-color-scheme: dark) {
      .project-browser__list-icon {
        filter: invert(1);
      }
      .project-browser__grid-icon {
        filter: invert(1);
      }
    }
  }
</style>
