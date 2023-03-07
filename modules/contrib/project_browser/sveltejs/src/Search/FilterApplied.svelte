<script>
  import { ALL_VALUES_ID, FULL_MODULE_PATH } from '../constants';

  export let id;
  export let label;
  export let clickHandler;

  const { Drupal } = window;
</script>

{#if id !== ALL_VALUES_ID}
  <p class="filter-applied">
    <span class="filter-applied__label">{label}</span>
    <button
      type="button"
      on:click={clickHandler}
      class="filter-applied__button-close"
    >
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
  .filter-applied {
    word-wrap: break-word;
    font-size: 0.875rem;
    color: white;
    display: inline-flex;
    align-items: center;
    border-radius: 25px;
    background: #013cc5;
    padding: 10px;
    margin-inline-end: 10px;
    width: auto;
    height: auto;
  }
  .filter-applied__button-close {
    display: inline-flex;
    padding: 0;
    margin-inline-start: 10px;
    outline: none;
    box-shadow: none;
    border: none;
    background: none;
    cursor: pointer;
  }
  .filter-applied__button-close > img {
    transform: scale(1.4);
    height: 20px;
    width: 20px;
  }

  @media (forced-colors: active) {
    .filter-applied {
      border: 1px solid;
    }
    @media (prefers-color-scheme: light) {
      .filter-applied__button-close > img {
        filter: invert(1);
      }
    }
  }
</style>
