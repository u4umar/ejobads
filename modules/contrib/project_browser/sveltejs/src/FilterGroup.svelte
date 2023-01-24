<script>
  export let filterTitle;
  export let filterData;
  export let changeHandler;
  export let filterType;

  import { filters } from './stores';
</script>

<div
  role="group"
  aria-labelledby={filterTitle.replace(/\s+/g, '')}
  class="filter-group"
>
  <div class="filter-title" id={filterTitle.replace(/\s+/g, '')}>
    {filterTitle}:
  </div>
  <div class="filter-options-wrapper">
    <div class="filter-options">
      {#each Object.entries(filterData) as [id, label]}
        <div class="filter-option">
          <input
            type="radio"
            name={filterType}
            id={filterType + id}
            bind:group={$filters[filterType]}
            on:change={changeHandler}
            value={id}
          />
          <slot name="label" {id} {label}>
            <label class="radio-label" for={filterType + id}>
              {label}
            </label>
          </slot>
        </div>
      {/each}
    </div>
  </div>
</div>

<style>
  input[type='radio'] {
    width: 20px;
    height: 20px;
  }
  .radio-label {
    font-weight: normal;
    padding-left: 25px;
  }
  .filter-group {
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    margin-bottom: 20px;
    margin-top: 15px;
    padding-left: 15px;
  }

  .filter-title {
    font-size: 18px;
    margin-right: 10px;
    width: 24%;
    display: inline-block;
  }

  .filter-options-wrapper {
    width: 74%;
  }

  .filter-options {
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
  }

  .filter-options input:hover {
    cursor: pointer;
  }

  .filter-option {
    flex: 0 0 50%;
    margin-bottom: 10px;
    line-height: 25px;
    position: relative;
  }

  @media only screen and (min-width: 72rem) {
    .filter-option {
      flex: 0 0 33%;
    }
  }

  @media only screen and (min-width: 42rem) {
    .filter-options {
      flex-direction: row;
    }
  }

  @media only screen and (max-width: 54rem) {
    .filter-group {
      flex-direction: column;
    }
  }

  .filter-option input[type='radio'] {
    position: absolute;
    top: 2px;
    left: 5px;
  }

  @media screen and (max-width: 855px) {
    .filter-title {
      display: contents;
    }
  }
</style>
