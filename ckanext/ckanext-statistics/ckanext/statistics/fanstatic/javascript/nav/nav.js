// Class for navigation/filter tool on the statistics dashboard
function StatisticsNav (params) {
  var self = this
  self.element = params.element
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
    datespanFilter: $('.js-statistics-filter-datespan'),
  }

  self._autoScrolling = params.autoScrolling
  self._setHashState = params.setHashState

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

StatisticsNav.prototype.dataLoaded = function () {
  var self = this
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
  var margin = 100 + self.height
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

StatisticsNav.prototype.setOrganizations = function (organizations) {

}

StatisticsNav.prototype.dateRange = function (dates) {

}

StatisticsNav.prototype.setCategories = function (categories) {

}

StatisticsNav.prototype._scroll = function (item) {
  var self = this
  self._updateActiveSection(item)
  self._autoScrolling(true)
  $('html, body').stop().animate({scrollTop: item.position}, 500, 'swing', function() {
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
