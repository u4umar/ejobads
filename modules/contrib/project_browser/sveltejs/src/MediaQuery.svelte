<!-- Media query component based on
 https://svelte.dev/repl/26eb44932920421da01e2e21539494cd?version=3.48.0 -->
<script>
  import { onMount } from 'svelte';

  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let query;

  let mql;
  let mqlListener;
  let wasMounted = false;
  let matches = false;

  // eslint-disable-next-line no-shadow
  function addNewListener(query) {
    mql = window.matchMedia(query);
    mqlListener = (v) => {
      matches = v.matches;
    };
    mql.addEventListener('change', mqlListener);
    matches = mql.matches;
  }

  function removeActiveListener() {
    if (mql && mqlListener) {
      mql.removeListener(mqlListener);
    }
  }

  onMount(() => {
    wasMounted = true;
    return () => {
      removeActiveListener();
    };
  });

  $: {
    if (wasMounted) {
      removeActiveListener();
      addNewListener(query);
    }
  }
</script>

<slot {matches} />
