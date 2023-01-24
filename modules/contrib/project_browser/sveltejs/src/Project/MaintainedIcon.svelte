<script>
  import { FULL_MODULE_PATH } from '../constants';

  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let maintained;

  const { Drupal } = window;
</script>

{#if maintained}
  <img
    src="{FULL_MODULE_PATH}/images/green-maintained-wrench-icon.svg"
    alt=""
    title={Drupal.t('This project is actively maintained')}
  />
{/if}

<style>
  img {
    width: 3em;
  }
</style>
