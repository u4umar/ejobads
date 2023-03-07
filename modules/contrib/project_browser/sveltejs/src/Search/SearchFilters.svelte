<script>
  import { slide } from 'svelte/transition';
  import FilterGroup from './FilterGroup.svelte';
  import ProjectIcon from '../Project/ProjectIcon.svelte';
  import {
    COVERED_ID,
    MAINTENANCE_OPTIONS,
    DEVELOPMENT_OPTIONS,
    SECURITY_OPTIONS,
  } from '../constants';

  const { Drupal } = window;

  export let onAdvancedFilter;
  export let isOpen;
</script>

{#if isOpen}
  <div class="search__filters" transition:slide>
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
        class="search__checkbox-label"
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
        class="search__checkbox-label"
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
        class="search__checkbox-label"
        for={`securityCoverage${id}`}
      >
        {label}
        {#if id === COVERED_ID}
          <span class="small-icons">
            <ProjectIcon type="status" />
          </span>
        {/if}
      </label>
    </FilterGroup>
  </div>
{/if}

<style>
  @media only screen and (min-width: 1200px) {
    .small-icons {
      margin-top: -3px;
      margin-inline-end: 1px;
    }
    .search__checkbox-label {
      font-weight: normal;
      padding-inline-start: 35px;
      display: flex;
    }
    .search__checkbox-label {
      font-weight: normal;
      padding-inline-start: 35px;
      display: flex;
      margin-right: unset;
    }
  }
</style>
