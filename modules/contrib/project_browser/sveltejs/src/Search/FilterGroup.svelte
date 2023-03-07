<script>
  import { filters } from '../stores';

  export let filterTitle;
  export let filterData;
  export let changeHandler;
  export let filterType;
</script>

<div
  role="group"
  aria-labelledby={filterTitle.replace(/\s+/g, '')}
  class="filter-group"
>
  <div class="filter-group__title-wrapper" id={filterTitle.replace(/\s+/g, '')}>
    {filterTitle}:
  </div>
  <div class="filter-group__filter-options-wrapper">
    <div class="filter-group__filter-options">
      {#each Object.entries(filterData) as [id, label]}
        <div class="filter-group__filter-option">
          <input
            type="radio"
            name={filterType}
            id={filterType + id}
            class="filter-group__radio"
            bind:group={$filters[filterType]}
            on:change={changeHandler}
            value={id}
          />
          <slot class="filter-group__label-slot" name="label" {id} {label}>
            <label class="filter-group__option-label" for={filterType + id}>
              {label}
            </label>
          </slot>
        </div>
      {/each}
    </div>
  </div>
</div>

<style>
  .filter-group__radio {
    width: 20px;
    height: 20px;
    position: absolute;
    top: 2px;
    inset-inline-start: 5px;
  }
  .filter-group__radio:hover {
    cursor: pointer;
  }

  .filter-group {
    flex-wrap: wrap;
    flex-direction: column;
    display: inline-grid;
    position: relative;
    margin-bottom: 20px;
    margin-top: 15px;
    padding-inline-start: 15px;
    padding-top: 15px;
    justify-content: flex-start;
  }

  .filter-group__title-wrapper {
    font-size: 18px;
    margin-inline-end: 10px;
  }

  .filter-group__filter-options {
    display: flex;
    flex-wrap: wrap;
    margin-top: 10px;
    flex-direction: column;
  }

  .filter-group__filter-option {
    margin-bottom: 10px;
    line-height: 25px;
    position: relative;
  }

  @media only screen and (max-width: 72rem) {
    .filter-group {
      display: table;
    }
  }
</style>
