<script>
  import { createEventDispatcher } from 'svelte';
  import { activeTab } from './stores';

  const { Drupal } = window;
  const dispatch = createEventDispatcher();

  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let dataArray = [];
  let tabButtons;
  // Enable arrow navigation between tabs in the tab list
  function onKeydown(e) {
    // Enable arrow navigation between tabs in the tab list
    let tabFocus;
    let i = 0;
    const aObj = tabButtons.getElementsByTagName('button');
    for (i = 0; i < aObj.length; i++) {
      if (aObj[i].getAttribute('tabindex') === '0') {
        tabFocus = i;
      }
    }

    const tabs = tabButtons.querySelectorAll('[role="tab"]');
    // Move right
    if (e.keyCode === 39 || e.keyCode === 37) {
      tabs[tabFocus].setAttribute('tabindex', -1);
      if (e.keyCode === 39) {
        tabFocus += 1;
        // If we're at the end, go to the start
        if (tabFocus >= tabs.length) {
          tabFocus = 0;
        }
        // Move left
      } else if (e.keyCode === 37) {
        tabFocus -= 1;
        // If we're at the start, move to the end
        if (tabFocus < 0) {
          tabFocus = tabs.length - 1;
        }
      }
      tabs[tabFocus].setAttribute('tabindex', 0);
      tabs[tabFocus].focus();
    }
  }
</script>

<!--Show tabs only if there are 2 or more plugins enabled.-->
{#if dataArray.length >= 2}
  <nav class="tabs-wrapper tabs-wrapper--secondary is-horizontal">
    <div
      on:keydown={onKeydown}
      role="tablist"
      id="plugin-tabs"
      aria-label={Drupal.t('Plugin tabs')}
      bind:this={tabButtons}
      class="tabs--secondary project-browser__plugin-tabs"
    >
      {#each dataArray.map( (item) => ({ ...item, isActive: item.pluginId === $activeTab }), ) as { pluginId, pluginLabel, totalResults, isActive }}
        <button
          role="tab"
          aria-selected={isActive ? 'true' : 'false'}
          aria-controls={pluginId}
          tabindex={isActive ? '0' : '-1'}
          id={pluginId}
          class:project-browser__selected-tab={isActive === 'true'}
          class="tabs__tab project-browser__toggle project-browser__plugin-tab"
          value={pluginId}
          class:is-active={isActive}
          on:click={(event) => {
            dispatch('tabChange', {
              pluginId,
              event,
            });
          }}
        >
          <span class="tabs__link" class:is-active={isActive}>
            {pluginLabel}
            <br />
            {Drupal.formatPlural(totalResults, '1 result', '@count results')}
          </span>
        </button>
      {/each}
    </div>
  </nav>
{/if}

<style>
  .tabs__tab {
    border: 0;
    cursor: pointer;
  }
  .tabs__tab:focus {
    box-shadow: 0 0 0 2px #fff, 0 0 0 5px #26a769;
  }
  .project-browser__plugin-tabs {
    display: flex;
  }
  .project-browser__plugin-tab {
    margin-right: 5px;
    margin-left: 5px;
    height: auto;
    min-height: 30px;
    cursor: pointer;
  }
  .project-browser__plugin-tabs .project-browser__toggle {
    margin-inline-start: 0;
  }
</style>
