<script>
  import { createEventDispatcher, getContext, onMount } from 'svelte';
  import { slide } from 'svelte/transition';
  import FilterGroup from './FilterGroup.svelte';
  import FilterApplied from './FilterApplied.svelte';
  import { normalizeOptions, shallowCompare } from './util';
  import {
    filters,
    rowsCount,
    filtersVocabularies,
    moduleCategoryFilter,
    moduleCategoryVocabularies,
    sort,
    searchString,
    sortCriteria,
  } from './stores';
  import {
    COVERED_ID,
    ACTIVELY_MAINTAINED_ID,
    MAINTENANCE_OPTIONS,
    DEVELOPMENT_OPTIONS,
    SECURITY_OPTIONS,
    ALL_VALUES_ID,
    FULL_MODULE_PATH,
  } from './constants';

  const { Drupal } = window;
  const dispatch = createEventDispatcher();
  const stateContext = getContext('state');

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

  let isOpen = false;
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

  async function onSearch(event) {
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
  }

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
  }

  async function onAdvancedFilter(event) {
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
  }

  /* When the user clicks on the button,
  toggle between hiding and showing the dropdown content */
  function openDropdown() {
    isOpen = !isOpen;
  }

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
</script>

<form class="views-exposed-form">
  <div
    class="js-form-item form-item js-form-type-textfield form-type--textfield search-bar-wrapper"
  >
    <label for="pb-text" class="form-item__label"
      >{Drupal.t('Search for modules')}</label
    >
    <div class="search-bar">
      <input
        class="searchTerm search form-text form-element form-element--type-text form-element--api-textfield"
        type="search"
        title={labels.placeholder}
        placeholder={labels.placeholder}
        id="pb-text"
        name="text"
        bind:value={$searchString}
        on:keyup={Drupal.debounce(onSearch, 250, false)}
      />
      <img
        id="search-icon"
        src="{FULL_MODULE_PATH}/images/search-icon.svg"
        alt=""
      />
    </div>
  </div>
  <div
    class="grid-container views-exposed-form__item js-form-item js-form-type-select form-type--select js-form-item-type form-item--type"
  >
    <div class="grid--1">
      <output>
        {$rowsCount &&
          $rowsCount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')}
        {Drupal.t('Results')}
        <span class="visually-hidden"
          >{Drupal.t('Sorted by @sortText', { '@sortText': sortText })}</span
        >
      </output>
      {#if $filters.developmentStatus}
        <FilterApplied
          id={$filters.developmentStatus}
          label={$filtersVocabularies.developmentStatus[
            $filters.developmentStatus
          ]}
          clickHandler={() => removeFilter('developmentStatus')}
        />
      {/if}

      {#if $filters.maintenanceStatus}
        <FilterApplied
          id={$filters.maintenanceStatus}
          label={$filtersVocabularies.maintenanceStatus[
            $filters.maintenanceStatus
          ]}
          clickHandler={() => removeFilter('maintenanceStatus')}
        />
      {/if}

      {#if $filters.securityCoverage}
        <FilterApplied
          id={$filters.securityCoverage}
          label={$filtersVocabularies.securityCoverage[
            $filters.securityCoverage
          ]}
          clickHandler={() => removeFilter('securityCoverage')}
        />
      {/if}

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
          on:click={() => {
            $filters.maintenanceStatus = ALL_VALUES_ID;
            $filters.developmentStatus = ALL_VALUES_ID;
            $filters.securityCoverage = ALL_VALUES_ID;
            $filters = $filters;
            $moduleCategoryFilter = [];
            onAdvancedFilter();
            onSelectCategory();
          }}
        >
          {Drupal.t('Clear filters')}
        </button>
      {/if}
      {#if !($filters.maintenanceStatus === ACTIVELY_MAINTAINED_ID && $filters.securityCoverage === COVERED_ID && $filters.developmentStatus === ALL_VALUES_ID && $moduleCategoryFilter.length === 0)}
        <button
          on:click={() => {
            $filters.maintenanceStatus = ACTIVELY_MAINTAINED_ID;
            $filters.securityCoverage = COVERED_ID;
            $filters.developmentStatus = ALL_VALUES_ID;
            $moduleCategoryFilter = [];
            $filters = $filters;
            onAdvancedFilter();
            onSelectCategory();
          }}
        >
          {Drupal.t('Recommended filters')}
        </button>
      {/if}
    </div>

    <div class="grid--2">
      <label for="pb-sort">{Drupal.t('Sort by:')}</label>
      <select
        name="pb-sort"
        id="pb-sort"
        bind:value={$sort}
        on:change={onSort}
        class="form-select form-element form-element--type-select"
      >
        {#each $sortCriteria as opt}
          <option value={opt.id}>
            {opt.text}
          </option>
        {/each}
      </select>
    </div>
    <div class="grid--3">
      <button
        type="button"
        class="advanced-filter-btn form-element"
        aria-controls="dropdown"
        aria-label={isOpen ? Drupal.t('Close Filter') : Drupal.t('Open Filter')}
        aria-expanded={isOpen.toString()}
        on:click={() => openDropdown()}
        ><img
          src="{FULL_MODULE_PATH}/images/advanced-filter-icon.svg"
          alt="advanced filter icon"
        />Filters
      </button>
    </div>
  </div>
  <div class="search dropdown-filters" id="dropdown">
    {#if isOpen}
      <div class="filters" transition:slide>
        <FilterGroup
          filterTitle={Drupal.t('Development Status')}
          filterData={DEVELOPMENT_OPTIONS}
          filterType="developmentStatus"
          changeHandler={onAdvancedFilter}
          let:id
          let:label
        >
          <label
            slot="label"
            class="checkbox-label"
            for={`developmentStatus${id}`}
          >
            {label}
          </label>
        </FilterGroup>
        <FilterGroup
          filterTitle={Drupal.t('Maintenance Status')}
          filterData={MAINTENANCE_OPTIONS}
          filterType="maintenanceStatus"
          changeHandler={onAdvancedFilter}
          let:id
          let:label
        >
          <label
            slot="label"
            class="checkbox-label"
            for={`maintenanceStatus${id}`}
          >
            {label}
          </label>
        </FilterGroup>
        <FilterGroup
          filterTitle={Drupal.t('Security Advisory Coverage')}
          filterData={SECURITY_OPTIONS}
          filterType="securityCoverage"
          changeHandler={onAdvancedFilter}
          let:id
          let:label
        >
          <label
            slot="label"
            class="checkbox-label"
            for={`securityCoverage${id}`}
          >
            {label}
            {#if id === COVERED_ID}
              <img
                class="small-icons"
                id="security"
                src="{FULL_MODULE_PATH}/images/blue-security-shield-icon.svg"
                alt=""
              />
            {/if}
          </label>
        </FilterGroup>
      </div>
    {/if}
  </div>
</form>

<style>
  .search-bar-wrapper {
    margin-top: 0;
  }

  .views-exposed-form__item.views-exposed-form__item {
    max-width: 100%;
  }
  .views-exposed-form.views-exposed-form {
    display: inherit;
    flex-wrap: wrap;
    margin: 0;
    padding: 0 0 1.5rem;
    border: transparent;
    border-radius: 2px;
    background-color: #fff;
  }
  .views-exposed-form {
    margin-top: 2.375rem;
  }
  .search-bar .search {
    width: 80%;
    height: 50px;
    position: relative;
    display: flex;
  }
  .search-bar .searchTerm {
    width: 100%;
    outline: none;
  }
  .search-bar {
    height: 50px;
    text-align: center;
    color: #fff;
    cursor: pointer;
    font-size: 20px;
    position: relative;
    border: 1px solid #919297;
    border-radius: 2px;
  }

  #search-icon {
    position: absolute;
    bottom: 12px;
    right: 30px;
  }

  ::placeholder {
    font-family: sans-serif;
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 150%;
    display: flex;
    align-items: center;
  }

  .advanced-filter-btn > img {
    width: 20px;
    height: 14px;
    margin-bottom: -2px;
    margin-right: 4px;
  }

  .advanced-filter-btn[aria-expanded='true'] {
    background-color: #adaeb3;
  }

  .dropdown-filters {
    position: relative;
    border: 3px solid #f3f4f9;
    z-index: 1;
  }

  .small-icons {
    position: relative;
    margin-top: -3px;
    margin-left: 1px;
    align-self: baseline;
  }

  .checkbox-label {
    font-weight: normal;
    padding-left: 35px;
    display: flex;
  }

  .form-select:hover,
  .advanced-filter-btn:hover {
    cursor: pointer;
  }

  output {
    display: inline-block;
    font-family: sans-serif;
    font-style: normal;
    font-weight: 700;
    font-size: 14px;
    line-height: 21px;
    margin-left: 20px;
  }

  .grid-container {
    display: grid;
    height: auto;
    grid-template-columns: 5fr auto auto;
    grid-gap: 20px;
    background: #f3f4f9;
    padding: 5px;
    align-items: center;
  }

  select {
    border: none;
    background-color: #d3d4d9;
  }

  .grid--2 {
    z-index: 2;
  }

  .advanced-filter-btn {
    background-color: #d3d4d9;
    color: black;
    padding-left: 16px;
    padding-right: 16px;
    font-size: 16px;
    border-radius: 2px;
    width: fit-content;
  }

  button {
    padding: 0 0.25rem;
    background: none;
    border: none;
    color: #013cc5;
    text-decoration: underline;
  }

  .grid--1 > button {
    cursor: pointer;
  }

  .grid--3 {
    display: flex;
    margin-right: 1em;
  }

  .advanced-filter-btn:focus,
  .advanced-filter-btn:focus:after {
    border-color: var(--color-lightninggreen);
  }

  @media only screen and (min-width: 1200px) {
    .checkbox-label {
      font-weight: normal;
      padding-left: 35px;
      display: flex;
      margin-right: unset;
    }
  }

  @media screen and (max-width: 855px) {
    .grid-container {
      display: block;
    }
    .grid--2 {
      margin-bottom: 10px;
      margin-top: 10px;
    }
  }
</style>
