<script>
  import { createEventDispatcher, getContext, onMount } from 'svelte';
  import FilterApplied from './FilterApplied.svelte';
  import { normalizeOptions, shallowCompare } from '../util';
  import SearchFilters from './SearchFilters.svelte';
  import SearchFilterToggle from './SearchFilterToggle.svelte';
  import SearchSort from './SearchSort.svelte';
  import {
    filters,
    filtersVocabularies,
    moduleCategoryFilter,
    moduleCategoryVocabularies,
    sort,
    searchString,
    sortCriteria,
  } from '../stores';
  import {
    COVERED_ID,
    ACTIVELY_MAINTAINED_ID,
    MAINTENANCE_OPTIONS,
    DEVELOPMENT_OPTIONS,
    SECURITY_OPTIONS,
    ALL_VALUES_ID,
    FULL_MODULE_PATH,
    DARK_COLOR_SCHEME,
  } from '../constants';
  // cspell:ignore searchterm

  const { Drupal } = window;
  const dispatch = createEventDispatcher();
  const stateContext = getContext('state');

  export let refreshLiveRegion;
  export const filter = (row, text) =>
    Object.values(row).filter(
      (item) =>
        item && item.toString().toLowerCase().indexOf(text.toLowerCase()) > 1,
    ).length > 0;
  export let index = -1;
  export let searchText;
  searchString.subscribe((value) => {
    searchText = value;
  });
  export let labels = {
    placeholder: Drupal.t('Module Name, Keyword(s), etc.'),
  };

  // eslint-disable-next-line prefer-const
  let filtersOpen = false;
  let sortMatch = $sortCriteria.find((option) => option.id === $sort);
  if (typeof sortMatch === 'undefined') {
    $sort = $sortCriteria[0].id;
    sortMatch = $sortCriteria.find((option) => option.id === $sort);
  }
  let sortText = sortMatch.text;

  const updateVocabularies = (vocabulary, value) => {
    const normalizedValue = normalizeOptions(value);
    const storedValue = JSON.parse(localStorage.getItem(`pb.${vocabulary}`));
    if (storedValue === null || !shallowCompare(normalizedValue, storedValue)) {
      $filtersVocabularies[vocabulary] = normalizedValue;
      localStorage.setItem(`pb.${vocabulary}`, JSON.stringify(normalizedValue));
    }
  };

  onMount(() => {
    updateVocabularies('developmentStatus', DEVELOPMENT_OPTIONS);
    updateVocabularies('maintenanceStatus', MAINTENANCE_OPTIONS);
    updateVocabularies('securityCoverage', SECURITY_OPTIONS);
  });

  export async function onSearch(event) {
    const state = stateContext.getState();
    const detail = {
      originalEvent: event,
      filter,
      index,
      searchText,
      page: state.page,
      pageIndex: state.pageIndex,
      pageSize: state.pageSize,
      rows: state.filteredRows,
    };
    dispatch('search', detail);

    if (detail.preventDefault !== true) {
      if (detail.searchText.length === 0) {
        stateContext.setRows(state.rows);
      } else {
        stateContext.setRows(
          detail.rows.filter((r) => detail.filter(r, detail.searchText, index)),
        );
      }
      stateContext.setPage(0, 0);
    } else {
      stateContext.setRows(detail.rows);
    }
    refreshLiveRegion();
  }

  const onAdvancedFilter = async (event) => {
    const state = stateContext.getState();
    const detail = {
      originalEvent: event,
      developmentStatus: $filters.developmentStatus,
      maintenanceStatus: $filters.maintenanceStatus,
      securityCoverage: $filters.securityCoverage,
      page: state.page,
      pageIndex: state.pageIndex,
      pageSize: state.pageSize,
      rows: state.filteredRows,
    };
    dispatch('advancedFilter', detail);
    stateContext.setPage(0, 0);
    stateContext.setRows(detail.rows);
    refreshLiveRegion();
  };

  function onSelectCategory(event) {
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

  function removeFilter(filterType) {
    $filters[filterType] = ALL_VALUES_ID;
    $filters = $filters;
    onAdvancedFilter();
  }

  /**
   * Actions performed when clicking filter resets such as "recommended"
   * @param {string} maintenanceId
   *    ID of the selected maintenance status.
   * @param {string} developmentId
   *   ID of the selected development status.
   * @param {string} securityId
   *   ID of the selected security status.
   */
  const filterResets = (maintenanceId, developmentId, securityId) => {
    $filters.maintenanceStatus = maintenanceId;
    $filters.developmentStatus = developmentId;
    $filters.securityCoverage = securityId;
    $filters = $filters;
    $moduleCategoryFilter = [];
    onAdvancedFilter();
    onSelectCategory();
  };
</script>

<form class="search__form">
  <div
    class="search__form-item js-form-item form-item js-form-type-textfield form-type--textfield"
    role="search"
  >
    <label for="pb-text" class="form-item__label"
      >{Drupal.t('Search for modules')}</label
    >
    <div class="search__search-bar">
      <input
        class="search__searchterm form-text form-element form-element--type-text"
        type="search"
        title={labels.placeholder}
        placeholder={labels.placeholder}
        id="pb-text"
        name="text"
        bind:value={$searchString}
        on:keyup={Drupal.debounce(onSearch, 250, false)}
      />
      <img
        class="search__search-icon"
        id="search-icon"
        src="{FULL_MODULE_PATH}/images/search-icon{DARK_COLOR_SCHEME
          ? '--dark-color-scheme'
          : ''}.svg"
        alt=""
      />
    </div>
  </div>
  <div
    class="search__grid-container js-form-item js-form-type-select form-type--select js-form-item-type form-item--type"
  >
    <section aria-label={Drupal.t('Search results')}>
      <div class="search__results-count">
        {#each ['developmentStatus', 'maintenanceStatus', 'securityCoverage'] as filterType}
          {#if $filters[filterType]}
            <FilterApplied
              id={$filters[filterType]}
              label={$filtersVocabularies[filterType][$filters[filterType]]}
              clickHandler={() => removeFilter(filterType)}
            />
          {/if}
        {/each}

        {#each $moduleCategoryFilter as category}
          <FilterApplied
            id={category}
            label={$moduleCategoryVocabularies[category]}
            clickHandler={() => {
              $moduleCategoryFilter.splice(
                $moduleCategoryFilter.indexOf(category),
                1,
              );
              $moduleCategoryFilter = $moduleCategoryFilter;
              onSelectCategory();
            }}
          />
        {/each}

        {#if $filters.securityCoverage !== ALL_VALUES_ID || $filters.maintenanceStatus !== ALL_VALUES_ID || $filters.developmentStatus !== ALL_VALUES_ID || $moduleCategoryFilter.length}
          <button
            class="search__filter-button"
            on:click|preventDefault={() =>
              filterResets(ALL_VALUES_ID, ALL_VALUES_ID, ALL_VALUES_ID)}
          >
            {Drupal.t('Clear filters')}
          </button>
        {/if}
        {#if !($filters.maintenanceStatus === ACTIVELY_MAINTAINED_ID && $filters.securityCoverage === COVERED_ID && $filters.developmentStatus === ALL_VALUES_ID && $moduleCategoryFilter.length === 0)}
          <button
            class="search__filter-button"
            on:click|preventDefault={() =>
              filterResets(ACTIVELY_MAINTAINED_ID, ALL_VALUES_ID, COVERED_ID)}
          >
            {Drupal.t('Recommended filters')}
          </button>
        {/if}
      </div>
    </section>
    <SearchSort on:sort bind:sortText refresh={refreshLiveRegion} />
    <SearchFilterToggle bind:isOpen={filtersOpen} />
  </div>
  <div class="search__dropdown dropdown-filters" id="filter-dropdown">
    <SearchFilters bind:isOpen={filtersOpen} {onAdvancedFilter} />
  </div>
</form>

<style>
  .search__form-item {
    margin-top: 0;
  }
  .search__form {
    margin-top: 2.375rem;
    display: inherit;
    flex-wrap: wrap;
    padding: 0 0 1.5rem;
  }

  .search__search-bar .search__searchterm {
    width: 100%;
    outline: none;
    height: 50px;
    position: relative;
    display: flex;
  }

  .search__search-bar {
    height: 50px;
    text-align: center;
    color: #fff;
    cursor: pointer;
    font-size: 20px;
    position: relative;
    border: 1px solid #919297;
    border-radius: 2px;
  }

  .search__search-icon {
    position: absolute;
    bottom: 12px;
    inset-inline-end: 30px;
  }

  .search__searchterm::placeholder {
    font-family: sans-serif;
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 150%;
    display: flex;
    align-items: center;
  }

  .dropdown-filters {
    position: relative;
    border: 3px solid #f3f4f9;
    z-index: 1;
  }

  .search__grid-container {
    display: grid;
    height: auto;
    grid-template-columns: 5fr auto auto;
    grid-gap: 20px;
    background: #f3f4f9;
    padding: 5px;
    align-items: center;
    max-width: 100%;
  }

  .search__filter-button {
    padding: 0 0.25rem;
    background: none;
    border: none;
    color: #013cc5;
    text-decoration: underline;
    cursor: pointer;
  }

  @media screen and (max-width: 855px) {
    .search__grid-container {
      display: block;
    }
  }
</style>
