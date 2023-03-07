<script>
  import { FULL_MODULE_PATH, DARK_COLOR_SCHEME } from '../constants';

  const { Drupal } = window;
  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let type = '';
  export let variant = false;
  export let classes = false;

  const typeToImg = {
    status: {
      path: 'blue-security-shield-icon',
      alt: Drupal.t('Security Advisory Coverage'),
    },
    usage: {
      path: 'project-usage-icon',
      alt: Drupal.t('Project Usage'),
    },
    compatible: {
      path: 'compatible-icon',
      alt: Drupal.t('Compatible'),
    },
  };
</script>

<img
  src="{FULL_MODULE_PATH}/images/{typeToImg[type].path}{DARK_COLOR_SCHEME
    ? '--dark-color-scheme'
    : ''}.svg"
  class={`pb-icon pb-icon--${variant} pb-icon--${type} ${classes}`}
  alt={typeToImg[type].path.alt}
/>

<style>
  :global(.pb-icon--project-listing) {
    width: 2.4em;
  }
  :global(.pb-icon--module-details) {
    width: 25px;
  }

  @media (forced-colors: active) and (prefers-color-scheme: dark) {
    :global(.pb-icon--usage) {
      filter: invert(1);
    }
  }
</style>
