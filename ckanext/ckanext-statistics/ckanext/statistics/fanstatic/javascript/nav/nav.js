// Class for navigation/filter tool on the statistics dashboard
function StatisticsNav (params) {
  var self = this
  self._texts = params.texts

  self._props = {
    dateFormatMoment: 'YYYY-MM-DD', // 'D.M.YYYY',
    dateFormatBootstrap: 'yyyy-mm-dd', // 'd.m.yyyy',
  }

  self._elem = {}
  self._elem.container = params.element
  self._elem.navItems = self._elem.container.find('.statistics-nav-items')

  self._sections = params.items
  self._sections.forEach(function (section) {
    section.element = $('.js-statistics-' + section.id + '-section')
    section.navLink = self._elem.navItems.find('a[href="#' + section.id + '"]')
    section.navLink.html(section.title)
    section.navLink.click(function () {
      if (section.id == self._state.selectedSectionId) {
        self._scrollToSection(section)
      }
    })
  })
  self._sections.unshift({
    id: '',
    element: $('body'),
    navLink: self._sections[0].navLink
  })

  // State
  self._state = {}
  self._state.selectedSectionId = self._sections[0].id
  self._state.height = self._elem.container.outerHeight(true)
  self._state.filters = {
    dateRange: undefined,
    organizations: undefined,
    categories: undefined,
  }

  // Set up input elements
  self._elem.inputs = {
    organizationFilter: $('.js-statistics-filter-organization'),
    categoryFilter: $('.js-statistics-filter-category'),
    startDateFilter: $('.js-statistics-filter-start-date'),
    endDateFilter: $('.js-statistics-filter-end-date'),
  }
  self._elem.inputsD3 = {}
  for (key in self._elem.inputs) {
    self._elem.inputsD3[key] = d3.select(self._elem.inputs[key].get(0))
  }

  self._elem.inputs.organizationFilter.change(function () {
    self._callbacks.broadcastOrganization(self._elem.inputsD3.organizationFilter.node().value)
  })
  self._elem.inputs.categoryFilter.change(function () {
    self._callbacks.broadcastCategory(self._elem.inputsD3.categoryFilter.node().value)
  })
  self._elem.inputs.startDateFilter.change(function () { self._broadcastDateInputValues() })
  self._elem.inputs.endDateFilter.change(function () { self._broadcastDateInputValues() })

  self._fixDatepickers()

  // Callbacks to main
  self._callbacks = {}
  self._callbacks.setAutoscrolling = params.setAutoscrolling
  self._callbacks.broadcastHashState = params.broadcastHashState
  self._callbacks.broadcastOrganization = params.broadcastOrganization
  self._callbacks.broadcastCategory = params.broadcastCategory
  self._callbacks.broadcastDateRange = params.broadcastDateRange
}


// Called by main when browser hash is changed. Scrolls to the given section and selects it in nav
StatisticsNav.prototype.onHashChange = function (hash) {
  var self = this

  var section = self._getSectionByHash(hash)
  if (!section) {
    return false
  }

  self._scrollToSection(section)
  self._highlightSection(section)
  self._updateFilterVisibility(section.id)
  self._state.selectedSectionId = section.id
}


// Called from main when the data is loaded
StatisticsNav.prototype.dataLoaded = function (params) {
  var self = this
  self._state.maxDateRange = params.maxDateRange

  self._setDateRange(params.dateRange)
  self._updateDateRangeQuicklinks(params.maxDateRange)

  self._setOrganizations(params.organizations)
  self._setCategories(params.categories)

  // After new data on screen, scroll positions of sections may have changed
  self._updateSectionPositions()

  // Get section by browser's hash, scroll to it, highlight it, update filter visibility and select the section
  self.onHashChange(params.hash)
}


StatisticsNav.prototype.onResize = function () {
  var self = this
  self._state.height = self._elem.container.outerHeight(true)
  self._updateSectionPositions()
}


// Highlight the active nav link + update url #hashtag
StatisticsNav.prototype.onScroll = function (y) {
  var self = this
  var margin = 100 + self._state.height
  var newActiveSection = self._sections[0]
  self._sections.forEach(function (section, i) {
    var useMargin = margin
    if (i == 0) {
      useMargin = 0
    }
    if (section.position < y + useMargin) {
      newActiveSection = section
    } else {
      return false
    }
  })

  if (self._state.selectedSectionId !== newActiveSection.id) {
    self._highlightSection(newActiveSection)
    self._updateFilterVisibility(newActiveSection.id)
    self._state.selectedSectionId = newActiveSection.id
    self._callbacks.broadcastHashState(newActiveSection.id)
  }
}


StatisticsNav.prototype._getSectionByHash = function (hash) {
  var self = this
  var section = self._sections.find(function (section) {
    return section.id == hash
  })
  return section
}


// Overwrite date input texts and broadcast values
StatisticsNav.prototype._setDateRange = function (dates) {
  var self = this
  self._elem.inputs.startDateFilter.val(dates[0].format(self._props.dateFormatMoment))
  self._elem.inputs.endDateFilter.val(dates[1].format(self._props.dateFormatMoment))
  self._highlightDateQuicklink()
  self._callbacks.broadcastDateRange(dates)
}


// Broadcast input values if they are valid
StatisticsNav.prototype._broadcastDateInputValues = function () {
  var self = this
  var dates = [
    moment.utc(self._elem.inputs.startDateFilter.val(), self._props.dateFormatMoment),
    moment.utc(self._elem.inputs.endDateFilter.val(), self._props.dateFormatMoment),
  ]
  if (
    !dates[0].isValid()
    || !dates[1].isValid()
    || dates[0].isBefore(self._state.maxDateRange[0])
    || dates[0].isAfter(self._state.maxDateRange[1])
    || dates[1].isBefore(dates[0])
  ) {
    return false
  } else {
    self._highlightDateQuicklink()
    self._callbacks.broadcastDateRange(dates)
  }
}


StatisticsNav.prototype._updateDateRangeQuicklinks = function (maxDateRange) {
  var self = this
  var thisYear = maxDateRange[1].year()
  var firstYear = maxDateRange[0].year()

  self._quicklinks = {
    all: {
      elem: self._elem.container.find('.js-statistics-filter-datespan-all'),
      title: self._texts.wholeDatespan,
      dates: maxDateRange,
    },
    thisYear: {
      elem: self._elem.container.find('.js-statistics-filter-datespan-this-year'),
      title: thisYear,
      dates: [
        moment.utc([thisYear, 0, 1]),
        moment.utc([thisYear, 11, 31])
      ],
    },
    back1year: {
      elem: self._elem.container.find('.js-statistics-filter-datespan-back-1-year'),
      title: thisYear - 1,
      dates: [
        moment.utc([thisYear - 1, 0, 1]),
        moment.utc([thisYear - 1, 11, 31])
      ],
    },
    back2years: {
      elem: self._elem.container.find('.js-statistics-filter-datespan-back-2-years'),
      title: thisYear - 2,
      dates: [
        moment.utc([thisYear - 2, 0, 1]),
        moment.utc([thisYear - 2, 11, 31])
      ],
    },
  }

  for (id in self._quicklinks) {
    var quicklink = self._quicklinks[id]
    if (
      quicklink.title === self._texts.wholeDatespan
      || firstYear <= quicklink.title
    ) {
      quicklink.elem.text(quicklink.title)
      quicklink.elem.click({dates: quicklink.dates}, function (e) {
        self._setDateRange(e.data.dates)
      })
    } else {
      quicklink.elem.remove()
    }
  }
  self._highlightDateQuicklink()
}


StatisticsNav.prototype._highlightDateQuicklink = function () {
  var self = this
  if (!self._quicklinks) {
    return
  }
  var startDate = self._elem.inputs.startDateFilter.val()
  var endDate = self._elem.inputs.endDateFilter.val()

  for (id in self._quicklinks) {
    var quicklink = self._quicklinks[id]
    if (
      quicklink.dates[0].format(self._props.dateFormatMoment) === startDate
      && quicklink.dates[1].format(self._props.dateFormatMoment) === endDate
    ) {
      quicklink.elem.addClass('statistics-active')
    } else {
      quicklink.elem.removeClass('statistics-active')
    }
  }
}


StatisticsNav.prototype._setOrganizations = function (organizations) {
  var self = this

  // List whole hierarchy as options
  self._state.filters.organizations = organizations
  var optionData = [{
    value: '',
    label: self._texts.allPublishers,
  }].concat(self._addOrganizationsWithChildren(self._state.filters.organizations))

  self._elem.inputsD3.organizationFilter.selectAll('option').remove()
  var options = self._elem.inputsD3.organizationFilter.selectAll('option')
    .data(optionData)

  options.enter().append('option')
    .text(function (d) { return d.label })
    .attr('value', function (d) { return d.value })
}


StatisticsNav.prototype._addOrganizationsWithChildren = function (organizations, parentText = '') {
  var self = this
  var result = []
  organizations.forEach(function(organization) {
    result.push({
      value: organization.id,
      label: parentText + organization.title,
    })
    result = result.concat(self._addOrganizationsWithChildren(organization.children, parentText + organization.title + ' > '))
  })
  return result
}


StatisticsNav.prototype._setCategories = function (categories) {
  var self = this
  self._state.filters.categories = categories

  var optionData = [{
    id: '',
    display_name: self._texts.allCategories,
  }].concat(self._state.filters.categories)

  self._elem.inputsD3.categoryFilter.selectAll('option').remove()
  var options = self._elem.inputsD3.categoryFilter.selectAll('option')
    .data(optionData)

  options.enter().append('option').text(function (d) {
    return d.display_name
  })
  .attr('value', function (d) {
    return d.id
  })
}


StatisticsNav.prototype._highlightSection = function (section) {
  var self = this
  self._elem.navItems.find('a').removeClass('active')
  section.navLink.addClass('active')
}


StatisticsNav.prototype._updateFilterVisibility = function (sectionId) {
  var self = this
  // Filters
  //  || self._state.selectedSectionId === 'summary' || self._state.selectedSectionId === ''
  if (sectionId === 'datasets') {
    self._elem.inputs.organizationFilter.slideDown()
    self._elem.inputs.categoryFilter.slideDown()
  } else {
    self._elem.inputs.organizationFilter.slideUp()
    self._elem.inputs.categoryFilter.slideUp()
  }
}


StatisticsNav.prototype._scrollToSection = function (section) {
  var self = this
  self._callbacks.setAutoscrolling(true)
  $('html, body').stop().animate({scrollTop: section.position - self._state.height}, 500, 'swing', function() {
    self._callbacks.setAutoscrolling(false)
  })
}


// Update positions of each section on page
StatisticsNav.prototype._updateSectionPositions = function () {
  var self = this

  // Calibrate affix nav
  self._elem.container.affix({
    offset: {
      top: $('.statistics-nav').offset().top,
    }
  })

  $('.js-summary-section').css('margin-top', self._state.height + 'px')

  // Section positions
  self._sections.forEach(function (section, i) {
    if (i == 0) {
      section.position = 0
    } else {
      section.position = section.element.offset().top
    }
  })
}


StatisticsNav.prototype._fixDatepickers = function () {
  var self = this
  var container = $('.js-statistics-filter-datespan-fields')

  function fixDatepicker(inputField) {
    inputField.datepicker({
      format: self._props.dateFormatBootstrap,
    })

    var datepicker = $('.datepicker')
    var width = datepicker.css('width')
    if (datepicker.hasClass('datepicker-orient-bottom')) {
      var detached = datepicker.detach()
      detached.appendTo(container)
      detached.css({
        top: inputField.position().top + 18,
        left: inputField.position().left,
        width: width,
      })
    }
  }

  self._elem.inputs.startDateFilter.click(function () {
    fixDatepicker(self._elem.inputs.startDateFilter)
  })

  self._elem.inputs.endDateFilter.click(function () {
    fixDatepicker(self._elem.inputs.endDateFilter)
  })
}
