<script>
  // eslint-disable-next-line import/prefer-default-export
  export let positionAbsolute = false;
</script>

<div
  class="loading__ajax-progress ajax-progress--fullscreen"
  class:absolute={positionAbsolute}
>
  <div class="ajax-progress__throbber ajax-progress__throbber--fullscreen">
    &nbsp;
  </div>
</div>

<style>
  .loading__ajax-progress {
    display: inline-block;
  }

  .absolute {
    position: absolute !important;
  }

  .ajax-progress__throbber {
    box-sizing: border-box;
    width: 1.125rem;
    height: 1.125rem;
    animation: pb-throbber 0.75s linear infinite;
    border: 2px solid #003cc5;
    border-right: 2px dotted transparent;
    border-radius: 50%;
  }

  .ajax-progress--fullscreen {
    position: fixed;
    z-index: 1000;
    top: 50%;
    left: 50%;
    box-sizing: border-box;
    width: 3.5rem; /* 56px */
    height: 3.5rem;
    margin: -1.75rem;
    border: 1px solid rgba(216, 217, 224, 0.8);
    border-radius: 3.5rem;
    background: #fff;
    box-shadow: 0 0.25rem 0.625rem rgba(34, 35, 48, 0.1);
  }

  .ajax-progress__throbber--fullscreen {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 1.75rem; /* 28px */
    height: 1.75rem; /* 28px */
    margin: -0.875rem;
    content: '';
    border: 3px solid #003cc5;
    border-right: 3px dotted transparent;
  }

  @keyframes pb-throbber {
    0% {
      transform: rotateZ(0);
    }
    100% {
      transform: rotateZ(360deg);
    }
  }
</style>
