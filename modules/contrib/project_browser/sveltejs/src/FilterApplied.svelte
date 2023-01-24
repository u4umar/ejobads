<script>
  import { ALL_VALUES_ID, FULL_MODULE_PATH } from './constants';

  export let id;
  export let label;
  export let clickHandler;

  const { Drupal } = window;
</script>

{#if id !== ALL_VALUES_ID}
  <p class="filters-applied">
    <span class="filter-label">{label}</span>
    <button type="button" on:click={clickHandler} class="close-button">
      {#if label}
        <span class="visually-hidden"
          >{Drupal.t('Remove @filter', { '@filter': label })}</span
        >
      {/if}
      <img src="{FULL_MODULE_PATH}/images/white-close-icon.svg" alt="" />
    </button>
  </p>
{/if}

<style>
  p {
    display: inline-block;
    word-wrap: break-word;
    font-size: 0.875rem;
    color: white;
  }
  .filters-applied {
    display: inline-flex;
    align-items: center;
    border-radius: 25px;
    background: #013cc5;
    padding: 10px;
    margin-right: 10px;
    width: auto;
    height: auto;
  }
  .close-button {
    display: inline-flex;
    padding: 0;
    margin-left: 10px;
    outline: none;
    box-shadow: none;
    border: none;
    background: none;
    cursor: pointer;
  }
</style>
