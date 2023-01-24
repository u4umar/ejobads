import { writable } from 'svelte/store';

import {
  DEFAULT_SOURCE_ID, SORT_OPTIONS,
} from './constants';

// Store for applied advanced filters.
const storedFilters = JSON.parse(sessionStorage.getItem('advancedFilter')) || {
  developmentStatus: '',
  maintenanceStatus: '',
  securityCoverage: ''
};
export const filters = writable(storedFilters);
filters.subscribe((val) => sessionStorage.setItem('advancedFilter', JSON.stringify(val)));

export const rowsCount = writable(0);

export const filtersVocabularies = writable({
  developmentStatus: JSON.parse(localStorage.getItem('pb.developmentStatus')) || [],
  maintenanceStatus: JSON.parse(localStorage.getItem('pb.maintenanceStatus')) || [],
  securityCoverage: JSON.parse(localStorage.getItem('pb.securityCoverage')) || []
});

// Store for applied category filters.
const storedModuleCategoryFilter = JSON.parse(sessionStorage.getItem('categoryFilter')) || [];
export const moduleCategoryFilter = writable(storedModuleCategoryFilter);
moduleCategoryFilter.subscribe((val) => sessionStorage.setItem('categoryFilter', JSON.stringify(val)));

// Store for module category vocabularies.
const storedModuleCategoryVocabularies = JSON.parse(localStorage.getItem('moduleCategoryVocabularies')) || {};
export const moduleCategoryVocabularies = writable(storedModuleCategoryVocabularies);
moduleCategoryVocabularies.subscribe((val) => localStorage.setItem('moduleCategoryVocabularies', JSON.stringify(val)));

// Store used to check if the page has loaded once already.
const storedIsFirstLoad = JSON.parse(sessionStorage.getItem('isFirstLoad')) === false ? JSON.parse(sessionStorage.getItem('isFirstLoad')) : true;
export const isFirstLoad = writable(storedIsFirstLoad);
isFirstLoad.subscribe((val) => sessionStorage.setItem('isFirstLoad', JSON.stringify(val)));

// Store the page the user is on.
const storedPage = JSON.parse(sessionStorage.getItem('page')) || 0;
export const page = writable(storedPage);
page.subscribe((val) => sessionStorage.setItem('page', JSON.stringify(val)));

// Store the selected tab.
const storedActiveTab = JSON.parse(sessionStorage.getItem('activeTab')) || DEFAULT_SOURCE_ID;
export const activeTab = writable(storedActiveTab);
activeTab.subscribe((val) => sessionStorage.setItem('activeTab', JSON.stringify(val)));

// Store the current sort selected.
const storedSort = JSON.parse(sessionStorage.getItem('sort')) || SORT_OPTIONS[storedActiveTab][0].id;
export const sort = writable(storedSort);
sort.subscribe((val) => sessionStorage.setItem('sort', JSON.stringify(val)));

// Store tab-wise checked categories.
const storedCategoryCheckedTrack = JSON.parse(sessionStorage.getItem('categoryCheckedTrack')) || {};
export const categoryCheckedTrack = writable(storedCategoryCheckedTrack);
categoryCheckedTrack.subscribe((val) => sessionStorage.setItem('categoryCheckedTrack', JSON.stringify(val)));

// Store the element that was last focused.
const storedFocus = JSON.parse(sessionStorage.getItem('focusedElement')) || '';
export const focusedElement = writable(storedFocus);
focusedElement.subscribe((val) => sessionStorage.setItem('focusedElement', JSON.stringify(val)));

// Store the search string.
const storedSearchString = JSON.parse(sessionStorage.getItem('searchString')) || '';
export const searchString = writable(storedSearchString);
searchString.subscribe((val) => sessionStorage.setItem('searchString', JSON.stringify(val)));

// Information regarding UI install capabilities. The default values are ones
// that do not allow UI installs. This object must be updated via a
// compatibility check.
// - pm_validation_error can be true, false or a string describing the unmet
//   conditions that must be changed before a site can support UI installs. This
//   must be false for UI install functionality to be available.
// - stage_available must be true for UI install functionality to be available.
//   This property is available for use cases where the site is UI install
//   compatible, but the stage is currently being used by another process. This
//   process could be another module install in progress, or a process from
//   another module such as Automatic Updates.
export const uiCapabilities = writable({
  pm_validation_error: true,
  stage_available: false,
});

// Store for sort criteria.
const storedSortCriteria = JSON.parse(sessionStorage.getItem('sortCriteria')) || SORT_OPTIONS[storedActiveTab];
export const sortCriteria = writable(storedSortCriteria);
sortCriteria.subscribe((val) => sessionStorage.setItem('sortCriteria', JSON.stringify(val)));

// Store the selected toggle view.
const storedPreferredView = JSON.parse(sessionStorage.getItem('preferredView')) || 'Grid';
export const preferredView = writable(storedPreferredView);
preferredView.subscribe((val) => sessionStorage.setItem('preferredView', JSON.stringify(val)));
