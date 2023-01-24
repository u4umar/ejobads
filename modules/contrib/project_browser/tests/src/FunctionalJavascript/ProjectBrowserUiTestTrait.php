<?php

namespace Drupal\Tests\project_browser\FunctionalJavascript;

trait ProjectBrowserUiTestTrait {

  /**
   * Searches for a term in the search field.
   *
   * @param string $value
   *   The value to search for.
   * @param bool $bypass_wait
   *   When TRUE, do not wait for a rerender after entering a search string.
   */
  protected function inputSearchField(string $value, bool $bypass_wait = FALSE) {
    $search_field = $this->getSession()->getPage()->find('css', '#pb-text');
    if ($bypass_wait) {
      $search_field->setValue($value);
    }
    else {
      $this->preFilterWait();
      $search_field->setValue($value);
      $this->postFilterWait();
    }
  }

  /**
   * Click an element with waits for Svelte to refresh.
   *
   * @param string $locator
   *   Locator to be used by pressButton().
   * @param string $wait_for_text
   *   When non-empty, wait for this text to be present after click.
   * @param bool $bypass_wait
   *   When TRUE, do not wait for a rerender after entering a search string.
   */
  protected function pressWithWait(string $locator, string $wait_for_text = '', bool $bypass_wait = FALSE) {
    if ($bypass_wait) {
      $this->getSession()->getPage()->pressButton($locator);
    }
    else {
      $this->preFilterWait();
      $this->getSession()->getPage()->pressButton($locator);
      $this->postFilterWait();
    }

    if (!empty($wait_for_text)) {
      $this->assertTrue($this->assertSession()->waitForText($wait_for_text));
    }
  }

  /**
   * Click an element with waits for Svelte to refresh.
   *
   * @param string $css_selector
   *   Selector of element to click.
   * @param string $wait_for_text
   *   When non-empty, wait for this text to be present after click.
   * @param bool $bypass_wait
   *   When TRUE, do not wait for a rerender after entering a search string.
   */
  protected function clickWithWait(string $css_selector, string $wait_for_text = '', bool $bypass_wait = FALSE) {
    if ($bypass_wait) {
      $this->getSession()->getPage()->find('css', $css_selector)->click();
    }
    else {
      $this->preFilterWait();
      $this->getSession()->getPage()->find('css', $css_selector)->click();
      $this->postFilterWait();
    }

    if (!empty($wait_for_text)) {
      $this->assertTrue($this->assertSession()->waitForText($wait_for_text));
    }
  }

  /**
   * Opens the advanced filter element.
   */
  protected function openAdvancedFilter() {
    $filter_icon_selector = $this->getSession()->getPage()->find('css', '.advanced-filter-btn');
    $filter_icon_selector->click();
    $this->assertSession()->waitForElementVisible('css', '.advanced-filter-btn[aria-expanded="true"]');
  }

  /**
   * Changes the sort by field.
   *
   * @param string $value
   *   The value to sort by.
   * @param bool $bypass_wait
   *   When TRUE, do not wait for a rerender after entering a search string.
   */
  protected function sortBy(string $value, bool $bypass_wait = FALSE) {
    if ($bypass_wait) {
      $this->getSession()->getPage()->selectFieldOption('pb-sort', $value);
    }
    else {
      $this->preFilterWait();
      $this->getSession()->getPage()->selectFieldOption('pb-sort', $value);
      $this->postFilterWait();
    }
  }

  /**
   * Add an attribute to a project card that will vanish after filtering.
   */
  protected function preFilterWait() {
    $this->getSession()->executeScript("document.querySelectorAll('.project').forEach((project) => project.setAttribute('data-pre-filter', 'true'))");
  }

  /**
   * Confirm the attribute added in preFilterWait() is no longer present.
   */
  protected function postFilterWait() {
    $this->assertSession()->assertNoElementAfterWait('css', '[data-pre-filter]');
  }

  /**
   * Confirms Svelte initialized and will re-try once if not.
   *
   * In ~1% of DrupalCI tests, Svelte will not initialize. Since this difficulty
   * initializing is specific to DrupalCI and a refresh consistently fixes it,
   * we do an initial check and refresh when it fails.
   *
   * @param string $check_type
   *   The type of check to make (css or text)
   * @param string $check_value
   *   The value to check for.
   * @param int $timeout
   *   Timeout in milliseconds, defaults to 10000.
   */
  protected function svelteInitHelper(string $check_type, string $check_value, int $timeout = 10000) {
    if ($check_type === 'css') {
      if (!$this->assertSession()->waitForElement('css', $check_value, $timeout)) {
        $this->getSession()->reload();
        $this->assertNotNull($this->assertSession()->waitForElement('css', $check_value, $timeout), 'Svelte did not initialize. Markup: ' . $this->getSession()->evaluateScript('document.querySelector("#project-browser").innerHTML'));
      }
    }
    if ($check_type === 'text') {
      if (!$this->assertSession()->waitForText($check_value, $timeout)) {
        $this->getSession()->reload();
        $this->assertTrue($this->assertSession()->waitForText($check_value, $timeout), 'Svelte did not initialize. Markup: ' . $this->getSession()->evaluateScript('document.querySelector("#project-browser").innerHTML'));
      }
    }
  }

  /**
   * Retrieves element text with JavaScript.
   *
   * This is an alternative for accessing element text with `getText()` in PHP.
   * Use this for elements that might become "stale element references" due to
   * re-rendering.
   *
   * @param string $selector
   *   CSS selector of the element.
   *
   * @return string
   *   The trimmed text content of the element.
   */
  protected function getElementText($selector) {
    return trim($this->getSession()->evaluateScript("document.querySelector('$selector').textContent"));
  }

}
