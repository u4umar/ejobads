<script>
  import { onMount } from 'svelte';
  import { withPrevious } from 'svelte-previous';
  import ProjectGrid, { Search, Filter } from './ProjectGrid.svelte';
  import Pagination from './Pagination.svelte';
  import Project from './Project/Project.svelte';
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
    uiCapabilities,
    sortCriteria,
    preferredView,
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
  } from './constants';

  const { Drupal } = window;

  let data;
  let rows = [];
  let sources = [];
  let dataArray = [];
  const pageIndex = 0; // first row
  const pageSize = 12;

  let loading = true;
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
      limit: pageSize,
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
   * Queries an endpoint to get information regarding a site's ability to
   * handle UI installs.
   *
   * @todo this probably does not need to be done client side. Moving this to
   * logic that updates drupalSettings in BrowserController can potentially do
   * this with less complexity. The one hurdle is adding the conditions for
   * adding this in a manner that does not fail if Package Manager isn't
   * installed.
   *
   * @return {Promise}
   *   So this can be awaited.
   */
  function setUiCapabilites() {
    return new Promise((accept, reject) => {
      const url = `${ORIGIN_URL}/admin/modules/project_browser/install-readiness`;
      fetch(url)
        .then(async (response) => {
          if (!response.ok) {
            if (response.status === 404) {
              return Promise.reject(Drupal.t(`unable to reach ${url}`));
            }
            const responseContent = await response.text();
            try {
              const responseAsJson = JSON.parse(responseContent);
              return Promise.reject(responseAsJson);
            } catch (error) {
              return Promise.reject(responseContent);
            }
          }
          return response.json();
        })
        .then((json) => {
          uiCapabilities.set({
            stage_available: json.stage_available,
            pm_validation_error: json.pm_validation,
          });
          accept(true);
        })
        .catch((err) => {
          uiCapabilities.set({
            stage_available: false,
            pm_validation_error: err,
          });
          reject(err);
        });
    });
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

    if (ALLOW_UI_INSTALL) {
      try {
        await setUiCapabilites();
      } catch (e) {
        // Errors already reported elsewhere;
      }
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

  async function toggleRows(val) {
    filterComponent.setModuleCategoryVocabulary();
    $categoryCheckedTrack[$activeTab] = $moduleCategoryFilter;
    $moduleCategoryFilter = [];
    $activeTab = val;
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
  <ProjectGrid {loading} {rows} {pageIndex} {pageSize} let:rows>
    <div slot="top">
      <Search
        on:search={onSearch}
        on:sort={onSort}
        on:advancedFilter={onAdvancedFilter}
        on:selectCategory={onSelectCategory}
        {searchText}
      />
      {#if matches}
        <div class="toggle-buttons">
          <button
            class:selected={toggleView === 'List'}
            class="toggle list-button"
            value="List"
            on:click={(e) => {
              toggleView = 'List';
              onToggle(e.target.value);
            }}
          >
            <img src="{FULL_MODULE_PATH}/images/list.svg" alt="" />
            {Drupal.t('List')}
          </button>
          <button
            class:selected={toggleView === 'Grid'}
            class="toggle grid-button"
            value="Grid"
            on:click={(e) => {
              toggleView = 'Grid';
              onToggle(e.target.value);
            }}
          >
            <img src="{FULL_MODULE_PATH}/images/grid-fill.svg" alt="" />
            {Drupal.t('Grid')}
          </button>
        </div>
      {/if}
      {#if dataArray.length >= 2}
        <div class="plugin-tabs">
          {#each dataArray as dataValue}
            <button
              class:selected={$activeTab === dataValue.pluginId}
              class="toggle plugin-tab"
              value={dataValue.pluginId}
              on:click={(e) => {
                toggleRows(e.target.value);
              }}
            >
              {dataValue.pluginLabel}
              {dataValue.totalResults}
              {Drupal.t('Results')}
            </button>
          {/each}
        </div>
      {/if}
      <!-- If UI installs are enabled, but the site configuration does not them,
           display a message informing the user what must be changed for UI
           installs to work. -->
      {#if $uiCapabilities.pm_validation_error && typeof $uiCapabilities.pm_validation_error === 'string' && MODULE_STATUS.package_manager && ALLOW_UI_INSTALL}
        <div class="install-warning">
          <p class="warning-header">
            <strong>{Drupal.t('Unable to download modules via the UI')}</strong>
          </p>
          <p><em>{@html $uiCapabilities.pm_validation_error}</em></p>
        </div>
      {/if}
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
        {pageSize}
        count={$rowsCount}
        serverSide={true}
        on:pageChange={onPageChange}
      />
    </div>
  </ProjectGrid>
</MediaQuery>

<style>
  .toggle {
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

  .toggle img {
    pointer-events: none;
  }
  .toggle:first-child {
    margin-left: auto;
  }
  .toggle-buttons {
    display: flex;
    margin-right: 25px;
  }
  .toggle.list-button {
    margin-right: 5px;
    border-radius: 2px 0 0 2px;
    cursor: pointer;
  }
  .toggle.grid-button {
    border-radius: 0 2px 2px 0;
    cursor: pointer;
  }
  .selected {
    background-color: #adaeb3;
  }
  .plugin-tabs {
    display: flex;
  }
  .plugin-tab {
    margin-right: 5px;
    width: 33%;
    height: auto;
    min-height: 30px;
    cursor: pointer;
  }
  .plugin-tabs .toggle {
    margin-left: 0;
  }
  .install-warning {
    border: 1px solid red;
    padding: 1em;
  }
  .install-warning p {
    margin: 0.5em 0;
  }
  .warning-header {
    color: red;
  }
</style>
