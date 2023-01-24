<script>
  import { FULL_MODULE_PATH } from './constants';

  import Image from './Project/Image.svelte';

  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let sources;

  const { Drupal } = window;
  let index = 0;

  const enableDisableButton = () => {
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    prevBtn.disabled = index === 0;
    nextBtn.disabled = index === sources.length - 1;
  };
  const next = () => {
    index = (index + 1) % sources.length;
    enableDisableButton();
  };
  const prev = () => {
    index = (index + sources.length - 1) % sources.length;
    enableDisableButton();
  };
  const missingAltText = () => !!sources.filter((src) => !src.alt).length;
</script>

<div
  class="carousel"
  aria-hidden={missingAltText()}
  on:load={enableDisableButton}
>
  {#if sources.length}
    <button id="prev-btn" class="slide-btn" on:click={prev} disabled="false"
      ><img
        class="slide-btn"
        src="{FULL_MODULE_PATH}/images/slide-icon.svg"
        alt={Drupal.t('Slide left')}
      /></button
    >
  {/if}
  <Image {sources} {index} class="slider-img" />
  {#if sources.length}
    <button id="next-btn" class="slide-btn slide-right" on:click={next}
      ><img
        class="slide-btn"
        src="{FULL_MODULE_PATH}/images/slide-icon.svg"
        alt={Drupal.t('Slide right')}
      /></button
    >
  {/if}
</div>

<style>
  .carousel {
    display: flex;
    align-items: center;
    width: 100%;
    height: 400px;
  }
  :global(.slider-img) {
    min-width: 650px;
    min-height: 400px;
    margin: 10px;
  }
  .slide-btn {
    background: transparent;
    border: none;
    width: 50px;
    height: 59px;
  }
  .slide-right {
    transform: rotate(180deg);
  }
  button {
    margin: 0 10px;
    cursor: pointer;
  }
  .slide-btn:disabled {
    opacity: 0.5;
    cursor: inherit;
  }
  @media only screen and (max-width: 600px) {
    :global(.slider-img) {
      min-width: 60%;
      min-height: 60%;
    }
  }
</style>
