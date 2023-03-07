<script>
  import { FULL_MODULE_PATH } from './constants';

  import Image from './Project/Image.svelte';

  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let sources;

  const { Drupal } = window;
  let index = 0;

  const missingAltText = () => !!sources.filter((src) => !src.alt).length;

  /**
   * Props for a slide next/previous button.
   *
   * @param {string} dir
   *   The direction of the button.
   * @return {{disabled: boolean, class: string}}
   *   The slide props.
   */
  const buttonProps = (dir) => ({
    class: `image-carousel__slide-btn image-carousel__slide-btn--${dir}`,
    disabled: dir === 'right' ? index === sources.length - 1 : index === 0,
  });

  /**
   * Props for a slide next/previous button image.
   *
   * @param {string} dir
   *   The direction of the button
   * @return {{src: string, alt: *}}
   *   The slide button Props
   */
  const imgProps = (dir) => ({
    src: `${FULL_MODULE_PATH}/images/slide-icon.svg`,
    alt: dir === 'right' ? Drupal.t('Slide right') : Drupal.t('Slide left'),
  });
</script>

<!-- svelte-ignore a11y-missing-attribute -->
<div class="image-carousel__carousel" aria-hidden={missingAltText()}>
  {#if sources.length}
    <button
      on:click={() => {
        index = (index + sources.length - 1) % sources.length;
      }}
      {...buttonProps('left')}><img {...imgProps('left')} /></button
    >
  {/if}
  <Image {sources} {index} class="image-carousel__slider-image" />
  {#if sources.length}
    <button
      on:click={() => {
        index = (index + 1) % sources.length;
      }}
      {...buttonProps('right')}><img {...imgProps('right')} /></button
    >
  {/if}
</div>

<style>
  .image-carousel__carousel {
    display: flex;
    align-items: center;
    width: 100%;
    height: 400px;
  }
  :global(.image-carousel__slider-image) {
    min-width: 650px;
    min-height: 400px;
    margin: 10px;
  }

  .image-carousel__slide-btn--right {
    transform: rotate(180deg);
  }
  .image-carousel__slide-btn {
    margin: 0 10px;
    cursor: pointer;
  }
  .image-carousel__slide-btn:disabled {
    opacity: 0.5;
    cursor: inherit;
  }
  .image-carousel__slide-btn > img {
    background: transparent;
    border: none;
    width: 50px;
    height: 59px;
  }
  @media only screen and (max-width: 600px) {
    :global(.image-carousel__slider-image) {
      min-width: 60%;
      min-height: 60%;
    }
  }
</style>
