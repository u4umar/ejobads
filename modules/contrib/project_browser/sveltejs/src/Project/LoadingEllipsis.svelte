<script>
  const { Drupal } = window;
  // eslint-disable-next-line import/prefer-default-export
  export let message = Drupal.t('Installing');
</script>

<span class="loading-ellipsis">{message}</span>

<style>
  .loading-ellipsis {
    position: relative;
  }

  .loading-ellipsis:after {
    position: absolute;
    overflow: hidden;
    display: inline-block;
    vertical-align: bottom;
    -webkit-animation: ellipsis steps(4, end) 900ms infinite;
    animation: ellipsis steps(4, end) 900ms infinite;
    content: '\2026'; /* ascii code for the ellipsis character */
    width: 0;
  }

  @keyframes ellipsis {
    to {
      width: 20px;
    }
  }

  @-webkit-keyframes ellipsis {
    to {
      width: 20px;
    }
  }
</style>
