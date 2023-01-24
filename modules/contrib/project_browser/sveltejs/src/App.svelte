<script>
  import ProjectBrowser from './ProjectBrowser.svelte';
  import ModulePage from './ModulePage.svelte';
  import Loading from './Loading.svelte';
  import { searchString, activeTab } from './stores';
  import { ORIGIN_URL } from './constants';

  const matches = window.location.pathname.match(
    /\/admin\/modules\/browse\/([^/]+)/,
  );
  const moduleName = matches ? matches[1] : null;

  let loading = true;
  let data;
  let project = [];
  let projectExists = false;
  async function load(url) {
    loading = true;
    const res = await fetch(url);
    if (res.ok) {
      data = await res.json();
      Object.entries(data).forEach((item) => {
        const [source, result] = item;
        if (result.totalResults !== 0) {
          $activeTab = source;
          [project] = result.list;
          projectExists = true;
        }
      });
    }
    loading = false;
    if (!projectExists) {
      $searchString = moduleName;
    }
    return project;
  }

  // Removes initial loader if it exists.
  const initialLoader = document.getElementById('initial-loader');
  if (initialLoader) {
    initialLoader.remove();
  }
</script>

{#if !moduleName}
  <ProjectBrowser />
{:else}
  {#await load(`${ORIGIN_URL}/drupal-org-proxy/project?machine_name=${moduleName}`)}
    {#if loading}
      <Loading />
    {/if}
  {:then project}
    {#if projectExists}
      <ModulePage {project} />
    {:else}
      <ProjectBrowser />
    {/if}
  {/await}
{/if}
