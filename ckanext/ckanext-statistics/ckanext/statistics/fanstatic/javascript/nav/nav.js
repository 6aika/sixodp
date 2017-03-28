// Class for navigation/filter tool on the statistics dashboard
function StatisticsNav (params) {
  var self = this
  self.element = params.element
  self.texts = params.texts
  self.navItems = self.element.find('.statistics-nav-items')
  self.items = params.items
  self.items.forEach(function (item) {
    item.element = $('.js-statistics-' + item.id + '-section')
    item.navLink = self.navItems.find('a[href="#' + item.id + '"]')
    item.navLink.html(item.title)
    item.navLink.click(function () {
      if (item.id == self.activeSection) {
        self._scroll(item)
      }
    })
  })
  self.items.unshift({
    id: '',
    element: $('body'),
    navLink: self.items[0].navLink
  })
  self.activeSection = self.items[0].id

  self.inputs = {
    organizationFilter: $('.js-statistics-filter-organization'),
    categoryFilter: $('.js-statistics-filter-category'),
    startDateFilter: $('.js-statistics-filter-start-date'),
    endDateFilter: $('.js-statistics-filter-end-date'),
  }
  self.inputsD3 = {}
  for (key in self.inputs) {
    self.inputsD3[key] = d3.select(self.inputs[key].get(0))
  }

  self.dateRangeQuicklinks = {
    all: [0, 0],
    lastYear: [0, 0],
    last3months: [0, 0],
    lastMonth: [0, 0],
  }
  self.element.find('.js-statistics-filter-datespan-all').click(function () {
    self._setDateRange(self.dateRangeQuicklinks.all)
  })
  self.element.find('.js-statistics-filter-datespan-year').click(function () {
    self._setDateRange(self.dateRangeQuicklinks.lastYear)
  })
  self.element.find('.js-statistics-filter-datespan-3months').click(function () {
    self._setDateRange(self.dateRangeQuicklinks.last3months)
  })
  self.element.find('.js-statistics-filter-datespan-month').click(function () {
    self._setDateRange(self.dateRangeQuicklinks.lastMonth)
  })

  self._autoScrolling = params.autoScrolling
  self._setHashState = params.setHashState
  self._onOrganizationUpdate = params.onOrganizationUpdate
  self._onCategoryUpdate = params.onCategoryUpdate
  self._onDateRangeUpdate = params.onDateRangeUpdate

  self.inputs.organizationFilter.change(function () {
    self._onOrganizationUpdate(self.inputsD3.organizationFilter.node().value)
  })
  self.inputs.categoryFilter.change(function () {
    self._onCategoryUpdate(self.inputsD3.categoryFilter.node().value)
  })
  self.inputs.startDateFilter.change(function () { self._readDates(self) })
  self.inputs.endDateFilter.change(function () { self._readDates(self) })

  self.data = {
    dateRange: undefined,
    organizations: undefined,
    categories: undefined,
  }
  // self._setDateRange([moment.utc().subtract(1, 'years'), moment.utc()])
  self.onResize()
}

StatisticsNav.prototype.onHashChange = function (hash) {
  var self = this
  var item = self.items.find(function (item) {
    return item.id == hash
  })
  if (item) {
    self._scroll(item)
  } else {
    return false
  }
}

StatisticsNav.prototype.updateData = function (dateRange, organizations, categories) {
  var self = this
  self._setDateRange(dateRange)
  self._updateDateRangeQuicklinks(dateRange)
  self._onOrganizationUpdates(organizations)
  self._setCategories(categories)

  self._updateSectionPositions()
}
StatisticsNav.prototype.onResize = function () {
  var self = this
  self._updateSectionPositions()
}

// Highlight the active nav link + update url #hashtag
StatisticsNav.prototype.onScroll = function (y) {
  var self = this
  var newActiveSection = self.items[0]
  var margin = 200 + self.height
  self.items.forEach(function (item, i) {
    var useMargin = margin
    if (i == 0) {
      useMargin = 0
    }
    if (item.position < y + useMargin) {
      newActiveSection = item
    } else {
      return false
    }
  })

  if (self.activeSection !== newActiveSection.id) {
    self._updateActiveSection(newActiveSection)
  }
}

StatisticsNav.prototype._setDateRange = function (dates) {
  var self = this
  self.inputs.startDateFilter.val(dates[0].format('YYYY-MM-DD'))
  self.inputs.endDateFilter.val(dates[1].format('YYYY-MM-DD'))
  self._onDateRangeUpdate(dates)
}

StatisticsNav.prototype._readDates = function (self) {
  var dates = [
    moment.utc(self.inputs.startDateFilter.val(), 'YYYY-MM-DD'),
    moment.utc(self.inputs.endDateFilter.val(), 'YYYY-MM-DD'),
  ]
  if (dates[0].toDate() > dates[1].toDate()) {
    return false
  }
  self._onDateRangeUpdate(dates)
}

StatisticsNav.prototype._updateDateRangeQuicklinks = function (totalDateRange) {
  var self = this
  self.dateRangeQuicklinks.all = totalDateRange

  var lastYear = totalDateRange[1].year() - 1
  self.dateRangeQuicklinks.lastYear = [
    moment.utc({year: lastYear, month: 0, day: 1}),
    moment.utc({year: lastYear, month: 11, day: 31}),
  ]

  var lastMonth = moment.utc(totalDateRange[1]).subtract(1, 'months')
  var lastMonthFirst = moment.utc({year: lastMonth.year(), month: lastMonth.month(), day: 1})
  var lastMonthLast = moment.utc(lastMonthFirst).add(1, 'months').subtract(1, 'days')
  self.dateRangeQuicklinks.lastMonth = [
    lastMonthFirst,
    lastMonthLast,
  ]

  var before3m = moment.utc(totalDateRange[1]).subtract(3, 'months')
  var before3mFirst = moment.utc({year: before3m.year(), month: before3m.month(), day: 1})
  self.dateRangeQuicklinks.last3months = [
    before3mFirst,
    lastMonthLast,
  ]
}

StatisticsNav.prototype._onOrganizationUpdates = function (organizations) {
  var self = this

  // List whole hierarchy as options
  self.data.organizations = organizations
  var optionData = [{
    value: '',
    label: self.texts.allPublishers,
  }].concat(self._addOrganizationsWithChildren(self.data.organizations))

  self.inputsD3.organizationFilter.selectAll('option').remove()
  var options = self.inputsD3.organizationFilter.selectAll('option')
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
  self.data.categories = categories

  var optionData = [{
    id: '',
    display_name: self.texts.allCategories,
  }].concat(self.data.categories)

  self.inputsD3.categoryFilter.selectAll('option').remove()
  var options = self.inputsD3.categoryFilter.selectAll('option')
    .data(optionData)

  options.enter().append('option').text(function (d) {
    return d.display_name
  })
  .attr('value', function (d) {
    return d.id
  })
}

StatisticsNav.prototype._updateActiveSection = function (item) {
  var self = this
  self.navItems.find('a').removeClass('active')
  item.navLink.addClass('active')
  self.activeSection = item.id
  self._setHashState(self.activeSection)

  // Filters
  if (self.activeSection === 'datasets' || self.activeSection === 'summary' || self.activeSection === '') {
    self.inputs.organizationFilter.slideDown() // css('visibility', 'visible')
    self.inputs.categoryFilter.slideDown() // .css('visibility', 'visible')
  } else {
    self.inputs.organizationFilter.slideUp() // .css('visibility', 'hidden')
    self.inputs.categoryFilter.slideUp() // .css('visibility', 'hidden')
  }
}

StatisticsNav.prototype._scroll = function (item) {
  var self = this
  self._updateActiveSection(item)
  self._autoScrolling(true)
  $('html, body').stop().animate({scrollTop: item.position - self.height}, 500, 'swing', function() {
    self._autoScrolling(false)
  })
}

// Update positions of each section on page
StatisticsNav.prototype._updateSectionPositions = function () {
  var self = this

  // Calibrate affix nav
  var heading = $('.statistics-page > .page-heading')
  self.height = self.element.outerHeight(true)
  self.element.affix({
      offset: {
         top: heading.offset().top + heading.outerHeight(false) + parseInt(heading.css('margin-bottom')),
        //  bottom: $('.js-app-section').outerHeight(true) + $('.js-article-section').outerHeight(true) + $('.site-footer').outerHeight(true) + self.height * 2,
     }
   })

   $('.js-front-section').css('margin-top', self.height + 'px')

  // Section positions
  self.items.forEach(function (item, i) {
    if (i == 0) {
      item.position = 0
    } else {
      item.position = item.element.offset().top
    }
  })
  self.onScroll()
}
