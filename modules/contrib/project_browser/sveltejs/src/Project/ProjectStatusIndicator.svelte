<script>
  export let project;
  export let statusText;

  const { Drupal } = window;
</script>

<span class="project_status-indicator">
  <slot />
  <span class="visually-hidden"
    >{Drupal.t('@module is', {
      '@module': `${project.title}`,
    })}</span
  >
  {statusText}
</span>

<style>
  .project_status-indicator {
    font-weight: bold;
    text-decoration: none;
  }
</style>
