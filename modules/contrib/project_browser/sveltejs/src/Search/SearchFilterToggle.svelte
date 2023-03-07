<script>
  import { FULL_MODULE_PATH } from '../constants';

  const { Drupal } = window;

  // eslint-disable-next-line import/prefer-default-export
  export let isOpen;

  /* When the user clicks on the button,
 toggle between hiding and showing the dropdown content */
  function openDropdown() {
    isOpen = !isOpen;
  }
</script>

<div class="search__filter__toggle-container">
  <section aria-label={Drupal.t('Filter settings')}>
    <button
      type="button"
      class="search__filter__toggle form-element"
      class:is_open={isOpen}
      aria-controls="filter-dropdown"
      aria-label={isOpen ? Drupal.t('Close Filter') : Drupal.t('Open Filter')}
      aria-expanded={isOpen.toString()}
      on:click={() => openDropdown()}
      ><img
        src="{FULL_MODULE_PATH}/images/advanced-filter-icon.svg"
        alt="advanced filter icon"
        class="search__filter__toggle-img"
      />Filters
    </button>
  </section>
</div>

<style>
  .search__filter__toggle-container {
    display: flex;
    margin-inline-end: 1em;
  }

  .search__filter__toggle {
    background-color: #d3d4d9;
    color: black;
    padding-left: 16px;
    padding-right: 16px;
    font-size: 16px;
    border-radius: 2px;
    width: fit-content;
    margin-inline-start: 15px;
    text-decoration: underline;
  }
  .search__filter__toggle.is_open {
    background-color: #adaeb3;
  }
  .search__filter__toggle:hover {
    cursor: pointer;
  }

  .search__filter__toggle-img {
    width: 20px;
    height: 14px;
    padding: 0 0.25rem;
    margin-bottom: -2px;
    margin-inline-end: 4px;
  }
  @media (forced-colors: active) {
    .search__filter__toggle {
      border: 1px solid;
    }
    @media (prefers-color-scheme: dark) {
      .search__filter__toggle-img {
        filter: invert(1);
      }
    }
  }
</style>
