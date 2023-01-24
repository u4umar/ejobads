<script>
  import { FULL_MODULE_PATH } from '../constants';
  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let coverage;

  const { Drupal } = window;
</script>

{#if coverage}
  <img
    src="{FULL_MODULE_PATH}/images/blue-security-shield-icon.svg"
    alt=""
    title={Drupal.t('Covered by Drupal Security Team')}
  />
{/if}

<style>
  img {
    width: 3em;
    display: block;
  }
</style>
