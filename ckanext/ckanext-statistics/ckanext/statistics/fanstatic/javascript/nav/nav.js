// Class for navigation/filter tool on the statistics dashboard
function StatisticsNav (params) {
  var self = this;
  self._texts = params.texts;
  self._locale = params.locale;

  self._props = {
    dateFormatMoment: 'YYYY-MM-DD', // 'D.M.YYYY',
    dateFormatBootstrap: 'yyyy-mm-dd' // 'd.m.yyyy',
  };

  self._elem = {};
  self._elem.container = params.element;
  self._elem.navItems = self._elem.container.find('.statistics-nav-items');

  self._sections = params.items;
  self._sections.forEach(function (section) {
    section.element = $('.js-statistics-' + section.id + '-section');
    section.navLink = self._elem.navItems.find('a[href="#' + section.id + '"]');
    section.navLink.html(section.title);
    section.navLink.click(function () {
      if (section.id == self._state.selectedSectionId) {
        self._scrollToSection(section)
      }
    })
  });
  self._sections.unshift({
    id: '',
    element: $('body'),
    navLink: self._sections[0].navLink
  });

  // State
  self._state = {};
  self._state.selectedSectionId = self._sections[0].id;
  self._state.height = self._elem.container.outerHeight(true);
  self._state.filters = {
    dateRange: undefined,
    organizations: undefined,
    categories: undefined
  };

  // Set up input elements
  self._elem.inputs = {
    organizationFilter: $('.js-statistics-filter-organization'),
    categoryFilter: $('.js-statistics-filter-category'),
    startDateFilter: $('.js-statistics-filter-start-date'),
    endDateFilter: $('.js-statistics-filter-end-date')
  };
  self._elem.inputsD3 = {};
  for (var key in self._elem.inputs) {
    self._elem.inputsD3[key] = d3.select(self._elem.inputs[key].get(0));
  }

  self._elem.inputs.organizationFilter.change(function () {
    var selectedOrganizationId = self._elem.inputsD3.organizationFilter.select(':checked').node().value;
    self._callbacks.broadcastOrganization(selectedOrganizationId);
    self._setOrganizations(self._state.filters.organizations, selectedOrganizationId);
  });
  self._elem.inputs.categoryFilter.change(function () {
    self._callbacks.broadcastCategory(self._elem.inputsD3.categoryFilter.select(':checked').node().value);
  });
  self._elem.inputs.startDateFilter.on('dp.change', function (e) { self._broadcastDateInputValues() });
  self._elem.inputs.endDateFilter.on('dp.change', function (e) { self._broadcastDateInputValues() });

  // Callbacks to main
  self._callbacks = {};
  self._callbacks.setAutoscrolling = params.setAutoscrolling;
  self._callbacks.broadcastHashState = params.broadcastHashState;
  self._callbacks.broadcastOrganization = params.broadcastOrganization;
  self._callbacks.broadcastCategory = params.broadcastCategory;
  self._callbacks.broadcastDateRange = params.broadcastDateRange;
}

// Called by main when browser hash is changed. Scrolls to the given section and selects it in nav
StatisticsNav.prototype.onHashChange = function (hash) {
  var self = this;

  var section = self._getSectionByHash(hash);
  if (!section) {
    return false;
  }

  self._scrollToSection(section);
  self._state.selectedSectionId = section.id
};

// Called from main when the data is loaded
StatisticsNav.prototype.dataLoaded = function (params) {
  var self = this;
  self._state.maxDateRange = params.maxDateRange;

  self._setDateRange(params.dateRange);
  self._updateDateRangeQuicklinks(params.maxDateRange);

  self._state.filters.organizations = params.organizations;
  self._setOrganizations(params.organizations);
  self._setCategories(params.categories);

  // After new data on screen, scroll positions of sections may have changed
  self._updateSectionPositions();

  // Get section by browser's hash, scroll to it, highlight it, update filter visibility and select the section
  self.onHashChange(params.hash)
};


StatisticsNav.prototype.onResize = function () {
  var self = this;
  self._state.height = self._elem.container.outerHeight(true);
  self._updateSectionPositions();
};


// Highlight the active nav link + update url #hashtag
StatisticsNav.prototype.onScroll = function (y) {
  var self = this;
  var margin = 100 + self._state.height;
  var newActiveSection = self._sections[0];
  self._sections.forEach(function (section, i) {
    var useMargin = margin;
    if (i == 0) {
      useMargin = 0;
    }
    if (section.position < y + useMargin) {
      newActiveSection = section;
    }
    else {
      return false;
    }
  });

  if (self._state.selectedSectionId !== newActiveSection.id) {
    self._state.selectedSectionId = newActiveSection.id;
    self._callbacks.broadcastHashState(newActiveSection.id)
  }
};

StatisticsNav.prototype._getSectionByHash = function (hash) {
  var self = this;
  return self._sections.find(function (section) {
    return section.id == hash;
  });
};

// Overwrite date input texts and broadcast values
StatisticsNav.prototype._setDateRange = function (dates) {
  var self = this;
  self._elem.inputs.startDateFilter.val(dates[0].format(self._props.dateFormatMoment));
  self._elem.inputs.endDateFilter.val(dates[1].format(self._props.dateFormatMoment));
  self._highlightDateQuicklink();
  self._callbacks.broadcastDateRange(dates);
};

// Broadcast input values if they are valid
StatisticsNav.prototype._broadcastDateInputValues = function () {
  var self = this;
  var dates = [
    moment.utc(self._elem.inputs.startDateFilter.val(), self._props.dateFormatMoment),
    moment.utc(self._elem.inputs.endDateFilter.val(), self._props.dateFormatMoment)
  ];
  if (
    !dates[0].isValid()
    || !dates[1].isValid()
    || dates[0].isBefore(self._state.maxDateRange[0])
    || dates[0].isAfter(self._state.maxDateRange[1])
    || dates[1].isBefore(dates[0])
  ) {
    return false
  } else {
    self._highlightDateQuicklink();
    self._callbacks.broadcastDateRange(dates);
  }
};

StatisticsNav.prototype._updateDateRangeQuicklinks = function (maxDateRange) {
  var self = this;
  var thisYear = maxDateRange[1].year();
  var firstYear = maxDateRange[0].year();

  self._quicklinks = {
    all: {
      elem: self._elem.container.find('.js-statistics-filter-datespan-all'),
      id: '.js-statistics-filter-datespan-all',
      title: self._texts.wholeDatespan,
      dates: maxDateRange
    },
    thisYear: {
      elem: self._elem.container.find('.js-statistics-filter-datespan-this-year'),
      id: '.js-statistics-filter-datespan-this-year',
      title: thisYear,
      dates: [
        moment.utc([thisYear, 0, 1]),
        moment.utc([thisYear, 11, 31])
      ]
    },
    back1year: {
      elem: self._elem.container.find('.js-statistics-filter-datespan-back-1-year'),
      id: '.js-statistics-filter-datespan-back-1-year',
      title: thisYear - 1,
      dates: [
        moment.utc([thisYear - 1, 0, 1]),
        moment.utc([thisYear - 1, 11, 31])
      ]
    },
    back2years: {
      elem: self._elem.container.find('.js-statistics-filter-datespan-back-2-years'),
      id: '.js-statistics-filter-datespan-back-2-years',
      title: thisYear - 2,
      dates: [
        moment.utc([thisYear - 2, 0, 1]),
        moment.utc([thisYear - 2, 11, 31])
      ]
    }
  };

  for (var id in self._quicklinks) {
    var quicklink = self._quicklinks[id];
    if (quicklink.title === self._texts.wholeDatespan || firstYear <= quicklink.title) {
      quicklink.elem.click({dates: quicklink.dates}, function (e) {
        self._setDateRange(e.data.dates);
      });

      var quicklinkElement = d3.select(quicklink.id)
        .append('div').attr('class', 'radio');

      var label = quicklinkElement.append('label');

      label.append('input')
        .attr('type', 'radio')
        .attr('name', 'quicklink-radio')
        .attr('value', quicklink.title)
        .property('checked', function () { return quicklink.title === self._texts.wholeDatespan; });

      label.append('span')
        .attr('class', 'radio-label')
        .text(quicklink.title);
    }
    else {
      quicklink.elem.remove();
    }
  }

  self._highlightDateQuicklink();
};

StatisticsNav.prototype._highlightDateQuicklink = function () {
  var self = this;
  if (!self._quicklinks) {
    return
  }
  var startDate = self._elem.inputs.startDateFilter.val();
  var endDate = self._elem.inputs.endDateFilter.val();

  for (id in self._quicklinks) {
    var quicklink = self._quicklinks[id];
    if (
      quicklink.dates[0].format(self._props.dateFormatMoment) === startDate
      && quicklink.dates[1].format(self._props.dateFormatMoment) === endDate
    ) {
      quicklink.elem.addClass('statistics-active')
    } else {
      quicklink.elem.removeClass('statistics-active')
    }
  }
};

StatisticsNav.prototype._setOrganizations = function (organizations, selectedOrganizationId) {
  var self = this;

  var optionData = [{
    value: '',
    label: self._texts.allPublishers
  }].concat(self.getOrganizationsBySelection(organizations, selectedOrganizationId));

  self._elem.inputsD3.organizationFilter.selectAll('.radio').remove();
  var options = self._elem.inputsD3.organizationFilter.selectAll('.radio')
    .data(optionData)
    .enter()
    .append('div')
    .attr('class', 'radio');

  var label = options.append('label');

  label.append('input')
    .attr('type', 'radio')
    .attr('name', 'organization-radio')
    .attr('value', function (d) { return d.value })
    .property('checked', function (d) {
      return selectedOrganizationId ? selectedOrganizationId === d.value : d.value === '';
    });

  label.append('span')
    .attr('class', 'radio-label')
    .text(function (d) { return d.label });
};

StatisticsNav.prototype.getOrganizationsBySelection = function (organizations, selectedOrganizationId) {
  var self = this;
  var result = [];
  var topLevelOrganization = self.getTopLevelOrganization(selectedOrganizationId, organizations);

  organizations.forEach(function(organization) {
    result.push({
      value: organization.id,
      label: organization.title
    });

    if(topLevelOrganization && organization.id === topLevelOrganization.id || selectedOrganizationId === organization.id) {
      result = result.concat(self.getOrganizationsBySelection(organization.children, selectedOrganizationId))
    }
  });

  return result;
};

StatisticsNav.prototype.getTopLevelOrganization = function(selectedOrganizationId, organizations) {
  var self = this;
  return organizations.find(function(organization) {
    return organization.id === selectedOrganizationId ? organization : self.getTopLevelOrganization(selectedOrganizationId, organization.children);
  });
};

StatisticsNav.prototype._setCategories = function (categories) {
  var self = this;
  self._state.filters.categories = categories;

  var optionData = [{
    id: '',
    title: self._texts.allCategories,
  }].concat(self._state.filters.categories);

  self._elem.inputsD3.categoryFilter.selectAll('.radio').remove();
  var options = self._elem.inputsD3.categoryFilter.selectAll('.radio')
    .data(optionData)
    .enter()
    .append('div')
    .attr('class', 'radio');

  var label = options.append('label');

  label.append('input')
    .attr('type', 'radio')
    .attr('name', 'category-radio')
    .attr('value', function (d) { return d.id })
    .property('checked', function (d) { return d.id === ''; });

  label.append('span')
    .attr('class', 'radio-label')
    .text(function (d) {
      if ( d.title_translated === undefined){
        return d.title
      }
      return d.title_translated[self._locale]
    });
};

StatisticsNav.prototype._scrollToSection = function (section) {
  var self = this;
  self._callbacks.setAutoscrolling(true);
  $('html, body').stop().animate({scrollTop: section.position - self._state.height}, 500, 'swing', function() {
    self._callbacks.setAutoscrolling(false)
  })
};

// Update positions of each section on page
StatisticsNav.prototype._updateSectionPositions = function () {
  var self = this;

  $('.js-summary-section').css('margin-top', self._state.height + 'px');

  // Section positions
  self._sections.forEach(function (section, i) {
    if (i == 0) {
      section.position = 0;
    }
    else {
      section.position = section.element.offset().top;
    }
  })
};

// https://tc39.github.io/ecma262/#sec-array.prototype.find
if (!Array.prototype.find) {
  Object.defineProperty(Array.prototype, 'find', {
    value: function(predicate) {
      // 1. Let O be ? ToObject(this value).
      if (this == null) {
        throw new TypeError('"this" is null or not defined');
      }

      var o = Object(this);

      // 2. Let len be ? ToLength(? Get(O, "length")).
      var len = o.length >>> 0;

      // 3. If IsCallable(predicate) is false, throw a TypeError exception.
      if (typeof predicate !== 'function') {
        throw new TypeError('predicate must be a function');
      }

      // 4. If thisArg was supplied, let T be thisArg; else let T be undefined.
      var thisArg = arguments[1];

      // 5. Let k be 0.
      var k = 0;

      // 6. Repeat, while k < len
      while (k < len) {
        // a. Let Pk be ! ToString(k).
        // b. Let kValue be ? Get(O, Pk).
        // c. Let testResult be ToBoolean(? Call(predicate, T, « kValue, k, O »)).
        // d. If testResult is true, return kValue.
        var kValue = o[k];
        if (predicate.call(thisArg, kValue, k, o)) {
          return kValue;
        }
        // e. Increase k by 1.
        k++;
      }

      // 7. Return undefined.
      return undefined;
    }
  });
}

$(document).ready(function() {
  // Show fixed filters nav after user scroll beneath specific anchor
  $(window).on('scroll', function () {
    var anchorOffset = $('#filters-nav-trigger').offset().top;
    var mainNavWrapper = $('.nav-wrapper');
    var filtersNavWrapper = $('#statistics-filters-navbar');
    var statisticsFilters = $('#statistics-filters');

    // User has scrolled past the anchor
    if ($(window).scrollTop() > anchorOffset) {
      statisticsFilters.detach().appendTo(mainNavWrapper);
    }
    // User scrolls back up, move filters to original position
    else if (!$("#statistics-filters-navbar #statistics-filters").length) {
      statisticsFilters.detach().appendTo(filtersNavWrapper);
    }
  });
});