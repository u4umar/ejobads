<script>
  import { FULL_MODULE_PATH } from '../constants';

  export async function fetchEntity(uri) {
    let data;
    const response = await fetch(`${uri}.json`);
    if (response.ok) {
      data = await response.json();
      return data;
    }
    throw new Error('Could not load entity');
  }

  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let sources;
  export let index = 0;

  const normalizedSources = sources ? [sources].flat() : [];
  const { Drupal } = window;
  const fallbackImage = `${FULL_MODULE_PATH}/images/puzzle-piece-placeholder.svg`;
  const showFallback = (ev) => {
    ev.target.src = fallbackImage;
  };
</script>

{#if normalizedSources.length}
  {#if normalizedSources[index].file.resource === 'image'}
    <img
      src={normalizedSources[index].file.uri}
      alt=""
      on:error={showFallback}
      class={$$props.class}
    />
  {:else if (normalizedSources[index].file.resource = 'file')}
    <!-- Keeping this block for compatibility with the mockapi. -->
    {#await fetchEntity(normalizedSources[index].file.uri)}
      <img
        src={fallbackImage}
        alt={Drupal.t('Placeholder')}
        class={$$props.class}
      />
    {:then file}
      <img
        src={file.url}
        alt=""
        on:error={showFallback}
        class={$$props.class}
      />
    {:catch error}
      <span style="color: red">{error.message}</span>
    {/await}
  {:else}
    <img
      src={fallbackImage}
      alt={Drupal.t('Placeholder')}
      class={$$props.class}
    />
  {/if}
{:else}
  <img
    src={fallbackImage}
    alt={Drupal.t('Placeholder')}
    class={$$props.class}
  />
{/if}

<style>
  img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%;
    object-fit: scale-down;
  }
  /* Small devices (portrait tablets and large phones, 600px and up) */
  @media only screen and (min-width: 600px) {
    img {
      display: block;
      width: auto;
      border-radius: 5px;
      height: 100px;
      margin-top: 20px;
    }
  }
</style>
