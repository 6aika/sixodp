var Statistics = function () {
  var self = this
  self.styles = {
    contentWidth: d3.select('.statistics-section-content:first-child').style('width')
  }
  self.initStyles()

  self.data = {}
  self.updatingHash = false

  self.config = Config
  self.helpers = Helpers
  self.translations = Translations
  self.timeFormats = TimeFormats

  self.api = new Api(self)

  // Set d3 locale
  d3.timeFormatDefaultLocale(self.timeFormats[self.config.locale])

  self.frontSection = new FrontSection(self)
  self.datasetSection = new DatasetSection(self)
  self.appSection = new AppSection(self)


  // Scroll event
  self.onScroll = $.throttle(300, function () {
    var y = $(window).scrollTop()
    self.nav.onScroll(y)
  })
  window.onscroll = self.onScroll

  // Hash change event
  self.onHashChange = function (e) {
    if (e && e.preventDefault) {
      e.preventDefault()
    }
    var hash = location.hash.substring(1)
    self.nav.onHashChange(hash)
    return false
  }
  window.onhashchange = self.onHashChange

  // Resize elements on window resize
  window.onresize = function () {
    self.styles.contentWidth = d3.select('.statistics-section-content:first-child').style('width')
    console.log('Window resized to', self.styles.contentWidth)

    self.nav.onResize()
    self.frontSection.onContentResize()
    self.datasetSection.onContentResize()
    self.appSection.onContentResize()
  }

  self.nav = new StatisticsNav({
    element: $('.statistics-nav'),
    items: [
      {
        id: 'summary',
        title: self.translations.frontSectionTitle[self.config.locale],
      },
      {
        id: 'datasets',
        title: self.translations.datasetSectionTitle[self.config.locale],
      },
      {
        id: 'apps',
        title: self.translations.appSectionTitle[self.config.locale],
      },
      {
        id: 'articles',
        title: self.translations.articleSectionTitle[self.config.locale],
      },
    ],
    texts: {
      allPublishers: self.translations.allPublishers[self.config.locale],
      allCategories: self.translations.allCategories[self.config.locale],
      wholeDatespan: self.translations.wholeDatespan[self.config.locale],
      lastYear: self.translations.lastYear[self.config.locale],
      last3months: self.translations.last3months[self.config.locale],
      lastMonth: self.translations.lastMonth[self.config.locale],
    },
    setHashState: function (hash) {
      hash = '#' + hash
      if(history.replaceState) {
          history.replaceState(null, null, hash)
      }
      else {
        window.onhashchange = undefined
        window.location.hash = hash
        setTimeout(function () {
          window.onhashchange = self.onHashChange
        }, 100)
      }
    },
    autoScrolling: function (value) {
      if (value) {
        window.onscroll = undefined
      } else {
        setTimeout(function () {
          window.onscroll = self.onScroll
        }, 100)
      }
    },
    onDateRangeUpdate: (function (dates) {
      var self = this
      self.datasetSection.setDateRange(dates)
      self.appSection.setDateRange(dates)
      // self.articleSection.setDateRange(value)
    }).bind(self),
    onOrganizationUpdate: function(value) {
      self.datasetSection.setOrganization(value)
    },
    onCategoryUpdate: function(value) {
      self.datasetSection.setCategory(value)
    },
  })

  self.onHashChange()

  // Get all data for statistics from APIs
  self.api.getAllData(function () {
    console.log('Fetched all data from APIs:', self.data)

    // General data transformations for the whole statistics
    self._transformData()

    // Init all data visualizations with the updated data
    self.frontSection.update()
    self.datasetSection.update()
    self.appSection.update()

    self.nav.updateData(self.data.dateRange, self.data.organizations, self.data.categories)
  })
}


Statistics.prototype._transformData = function () {
  var self = this

  // eg. start date of the whole portal = 1.1. on the year of first dataset/app/article
  var firstDateDataset = d3.min(self.data.datasets, function (d) { return d.date_released })
  var firstDateApp = d3.min(self.data.apps, function (d) { return d.metadata_created })

  // Default value if no data exists
  var firstDate = moment.utc().year() + '-01-01'

  if (typeof firstDateDataset !== 'undefined') {
    firstDate = firstDateDataset
  }
  if (typeof firstDateApp !== 'undefined' && firstDateApp < firstDateDataset) {
    firstDate = firstDateApp
  }
  firstDate = moment.utc(firstDate)
  firstDateVis = moment.utc([firstDate.year(), 0, 1])

  var today = moment.utc()
  today = moment.utc([today.year(), today.month(), today.date()])

  self.data.dateRange = [firstDateVis, today]
}


Statistics.prototype.initStyles = function () {
  var self = this
  d3.select('#js-bootstrap-offcanvas, .container')
    .style('display', 'none')
}


statistics = new Statistics()
