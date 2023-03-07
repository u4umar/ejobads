<script>
  // eslint-disable-next-line import/prefer-default-export
  export let click = () => {};
</script>

<button
  on:click={click}
  class="button button--primary project_button--primary"
  {...$$restProps}
>
  <slot />
</button>

<style>
  .project_button--primary {
    margin: 0 !important;
    border-width: 0 !important;
    box-shadow: none;
    color: #fff;
    height: 24px;
    font-size: 12.65px;
    line-height: 19px;
    display: flex;
    align-items: center;
    text-align: center;
    justify-content: center;
    white-space: nowrap;
  }
  @media (forced-colors: active) {
    .project_button--primary {
      border: 1px solid !important;
    }
  }
</style>
