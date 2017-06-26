var Statistics = function () {
  var self = this

  // Basic styles for data visualizations
  self._styles = {
    contentWidth: parseInt(d3.select('.statistics-section-content:first-child').style('width')),
    visMargins: {top: 15, right: 50, bottom: 30, left: 15},
    visMarginsWithTop: {top: 50, right: 15, bottom: 30, left: 15},
    visHeight: 360,
  }
  self._styles.visHeight = self._getVisHeight(self._styles.contentWidth)

  // Schemas for different data types
  self._schemas = {
    datasets: {
      nameField: 'title_translated',
      dateField: 'date_released',
      skip: function (dataset) {
        return !!dataset.private
      },
    },
    apps: {
      nameField: 'name',
      dateField: 'metadata_created',
      skip: function (app) {
        return false
      },
    },
  }

  // Which filters are set (globally or for some sections of the dashboard)
  self._state = {
    dateRange: [undefined, undefined],
    organization: '',
    category: '',
  }

  // All data goes here
  self._data = {}

  // Settings
  self._localeData = Locales
  self._config = Config()
  self._config.locale = 'fi' // This gets updated after data is loaded
  d3.timeFormatDefaultLocale(self._localeData.timeFormats[self._config.locale])

  // Some clean up
  d3.select('#js-bootstrap-offcanvas, .container').style('display', 'none')

  self._loadDataToPage()
}


// Create the navigation bar for the statistics page
Statistics.prototype._createNav = function () {
  var self = this
  self._nav = new StatisticsNav({
    element: $('.statistics-nav'),
    items: [
      {
        id: 'summary',
        title: self._localeData.summarySectionTitle[self._config.locale],
      },
      {
        id: 'datasets',
        title: self._localeData.datasetSectionTitle[self._config.locale],
      },
      {
        id: 'apps',
        title: self._localeData.appSectionTitle[self._config.locale],
      },
      // {
      //   id: 'articles',
      //   title: self._localeData.articleSectionTitle[self._config.locale],
      // },
    ],
    texts: {
      allPublishers: self._localeData.allPublishers[self._config.locale],
      allCategories: self._localeData.allCategories[self._config.locale],
      wholeDatespan: self._localeData.wholeDatespan[self._config.locale],
    },

    // Disable scroll events for a moment when scrolling automatically
    setAutoscrolling: function (value) {
      if (value) {
        window.onscroll = undefined
      } else {
        setTimeout(function () {
          window.onscroll = self._onScroll
        }, 100)
      }
    },

    // Change the hash (and fire hash change event to change system state)
    broadcastHashState: function (hash) {
      hash = '#' + hash
      if(history.replaceState) {
          history.replaceState(null, null, hash)
      }
      else {
        window.onhashchange = undefined
        window.location.hash = hash
        setTimeout(function () {
          window.onhashchange = self._onHashChange
        }, 100)
      }
    },

    broadcastDateRange: (function (dates) {
      var self = this
      self._state.dateRangeFilter = dates
      self._summarySection.setDateRange(dates)
      self._datasetSection.setDateRange(
        dates,
        self._createCategoryDatasets(
          self._data.filtered.datasets,
          self._data.filtered.categories,
          self._state.dateRangeFilter
        ),
        self._createFormatDatasets(
          self._data.filtered.datasets,
          self._data.filtered.formats,
          self._state.dateRangeFilter
        ),
        self._createOrganizationDatasets(
          self._data.filtered.datasets,
          self._data.filtered.organizations,
          self._state.dateRangeFilter
        )
      )
      var locale = 'fi'
      if (self._config && self._config.locale) {
        locale = self._config.locale
      }
      self._appSection.setDateRange(
        dates,
        self._createCategoryApps(
          self._data.filtered.apps,
          self._data.filtered.appCategories[locale],
          self._state.dateRangeFilter
        )
      )
      // self.articleSection.setDateRange(dates)
    }).bind(self),

    broadcastOrganization: function(value) {
      self._state.organization = value
      self._data.filtered = self._filterAllData(self._data.all)
      self._datasetSection.setOrganization(self._state.organization)
      self._datasetSection.setData(
        self._data.filtered.datasets,
        self._createCategoryDatasets(
          self._data.filtered.datasets,
          self._data.filtered.categories,
          self._state.dateRangeFilter
        ),
        self._createFormatDatasets(
          self._data.filtered.datasets,
          self._data.filtered.formats,
          self._state.dateRangeFilter
        ),
        self._createOrganizationDatasets(
          self._data.filtered.datasets,
          self._data.filtered.organizations,
          self._state.dateRangeFilter
        )
      )
    },

    broadcastCategory: function(value) {
      self._state.category = value
      self._data.filtered = self._filterAllData(self._data.all)
      self._datasetSection.setCategory(self._state.category)
      self._datasetSection.setData(
        self._data.filtered.datasets,
        self._createCategoryDatasets(
          self._data.filtered.datasets,
          self._data.filtered.categories,
          self._state.dateRangeFilter
        ),
        self._createFormatDatasets(
          self._data.filtered.datasets,
          self._data.filtered.formats,
          self._state.dateRangeFilter
        ),
        self._createOrganizationDatasets(
          self._data.filtered.datasets,
          self._data.filtered.organizations,
          self._state.dateRangeFilter
        )
      )
    },
  })
}


Statistics.prototype._createSections = function () {
  var self = this

  // Create summary section
  self._summarySection = new SummarySection({
    element: d3.select('.js-statistics-summary-section'),
    texts: {
      datasetsTitle: self._localeData.datasetsOpened[self._config.locale],
      appsTitle: self._localeData.apps[self._config.locale],
      detailText: self._localeData.summarySectionDetailText[self._config.locale],
      detailTextUnit: self._localeData.summarySectionDetailTextUnit[self._config.locale],
    },
    datasetSchema: self._schemas.datasets,
    appSchema: self._schemas.apps,
  })

  // Create dataset section
  self._datasetSection = new DatasetSection({
    texts: {
      sectionTitle: self._localeData.datasetSectionTitle[self._config.locale],
      timelineTitle: self._localeData.datasetsOpenedTitle[self._config.locale],
      amount: self._localeData.amount[self._config.locale],
      topPublishersTitle: self._localeData.topPublishersTitle[self._config.locale],
      categoriesTitle: self._localeData.categoriesTitle[self._config.locale],
      formatsTitle: self._localeData.formatsTitle[self._config.locale],
      usedInApp: self._localeData.usedInApp[self._config.locale],
      notUsedInApp: self._localeData.notUsedInApp[self._config.locale],
    },
    width: self._styles.contentWidth,
    visMargins: self._styles.visMargins,
    visHeight: self._styles.visHeight,
    schema: self._schemas.datasets,
    locale: self._config.locale,
  })

  // Create app section
  self._appSection = new AppSection({
    texts: {
      sectionTitle: self._localeData.appSectionTitle[self._config.locale],
      timelineTitle: self._localeData.appsPublishedTitle[self._config.locale],
      categoriesTitle: self._localeData.categoriesTitle[self._config.locale],
      amount: self._localeData.amount[self._config.locale],
    },
    width: self._styles.contentWidth,
    visMargins: self._styles.visMargins,
    visHeight: self._styles.visHeight,
    schema: self._schemas.apps,
    locale: self._config.locale,
  })
}


Statistics.prototype._bindEvents = function () {
  var self = this

  // Scroll event: Tell nav to update its state
  self._onScroll = $.throttle(300, function () {
    var y = $(window).scrollTop()
    self._nav.onScroll(y)
  })
  window.onscroll = self._onScroll

  // Hash change event: Scroll to correct section on page
  self._onHashChange = function (e) {
    if (e && e.preventDefault) {
      e.preventDefault()
    }
    var hash = location.hash.substring(1)
    self._nav.onHashChange(hash)
    return false
  }
  window.onhashchange = self._onHashChange

  // Resize elements on window resize
  window.onresize = function () {
    self._nav.onResize()

    var newWidth = parseInt(d3.select('.statistics-section-content:first-child').style('width'))
    self._styles.visHeight = self._getVisHeight(self._styles.contentWidth)
    if (newWidth !== self._styles.contentWidth) {
      self._styles.contentWidth = newWidth
      self._summarySection.onContentResize(self._styles.contentWidth)
      self._datasetSection.onContentResize(self._styles.contentWidth, self._styles.visHeight)
      self._appSection.onContentResize(self._styles.contentWidth, self._styles.visHeight)
    }
  }
}


// Get all data for statistics from APIs and send it to sub items
Statistics.prototype._loadDataToPage = function () {
  var self = this
  self._api = new Api({
    baseUrl: self._config.api.baseUrl,
    width: self._styles.contentWidth,
    texts: {
      loading: self._localeData.loading[self._config.locale],
      loadWebpage: self._localeData.loadWebpage[self._config.locale],
      loadOrganizations: self._localeData.loadOrganizations[self._config.locale],
      loadCategories: self._localeData.loadCategories[self._config.locale],
      loadDatasets: self._localeData.loadDatasets[self._config.locale],
      loadApps: self._localeData.loadApps[self._config.locale],
      loadAppDatasetRelations: self._localeData.loadAppDatasetRelations[self._config.locale],
      loadPreprocessing: self._localeData.loadPreprocessing[self._config.locale],
      loadRendering: self._localeData.loadRendering[self._config.locale],
      loadDone: self._localeData.loadDone[self._config.locale],
    },
  })
  self._api.getAllData(function (result) {
    // Update language
    self._config = Config()

    // Build the page contents
    d3.select('.js-statistics-main-title')
      .text(self._localeData.statisticsMainTitle[self._config.locale])

    self._createNav()
    self._createSections()
    self._bindEvents()

    // Get maximum possible date range for unfiltered data
    self._state.maxDateRange = self._getMaxDateRange(result)

    // Initially use max start or end date
    self._state.dateRangeFilter = self._state.maxDateRange

    // General data transformations for the whole statistics
    self._data.all = result

    // Apply global filters
    self._data.filtered = self._filterAllData(self._data.all)

    // Update summary section (show non-filtered counts)
    self._summarySection.setDateRange(self._state.dateRangeFilter)
    self._summarySection.setData({
      datasets: self._data.filtered.datasets,
      apps: self._data.filtered.apps,
    })

    // Update dataset section
    self._datasetSection.setMaxDateRange(self._state.maxDateRange)
    self._datasetSection.setDateRange(self._state.dateRangeFilter)
    self._datasetSection.setOrganization(self._state.organization)
    self._datasetSection.setCategory(self._state.category)
    self._datasetSection.setData(
      self._data.filtered.datasets,
      self._createCategoryDatasets(
        self._data.filtered.datasets,
        self._data.filtered.categories,
        self._state.dateRangeFilter
      ),
      self._createFormatDatasets(
        self._data.filtered.datasets,
        self._data.filtered.formats,
        self._state.dateRangeFilter
      ),
      self._createOrganizationDatasets(
        self._data.filtered.datasets,
        self._data.filtered.organizations,
        self._state.dateRangeFilter
      )
    )

    // Update app section
    self._appSection.setMaxDateRange(self._state.maxDateRange)
    self._appSection.setDateRange(self._state.dateRangeFilter)

    var locale = 'fi'
    if (self._config && self._config.locale) {
      locale = self._config.locale
    }
    self._appSection.setData(
      self._data.filtered.apps,
      self._createCategoryApps(
        self._data.filtered.apps,
        self._data.filtered.appCategories[locale],
        self._state.dateRangeFilter
      )
    )

    // Update nav scroll positions and filters etc.
    self._nav.dataLoaded({
      organizations: self._data.filtered.organizations,
      categories: self._data.filtered.categories,
      dateRange: self._state.dateRangeFilter,
      maxDateRange: self._state.maxDateRange,
      hash: location.hash.substring(1),
    })

  }, 400)
}


Statistics.prototype._filterAllData = function (data) {
  var self = this
  var result = {}

  result.all = data
  result.organizations = data.organizations
  result.categories = data.categories
  result.formats = data.formats
  result.appCategories = data.appCategories

  // Filter out datasets from wrong organization or category
  result.datasets = self._filterItems({
    data: data.datasets,
    schema: self._schemas.datasets,
    organization: true,
    organizations: result.organizations,
    category: true,
  })

  // Filter out apps outside date range
  result.apps = self._filterItems({
      data: data.apps,
      schema: self._schemas.apps,
      organization: false,
      category: false,
  })
  return result
}


Statistics.prototype._getMaxDateRange = function (allData) {
  var self = this

  // eg. start date of the whole portal = 1.1. on the year of first dataset/app/article
  var firstDateDataset = d3.min(allData.datasets, function (d) { return d.date_released })
  var firstDateApp = d3.min(allData.apps, function (d) { return d.metadata_created })

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

  return [firstDateVis, today]
}


// Filters items
Statistics.prototype._filterItems = function (params) {
  var self = this
  var result = []

  // Returns all child and grand child organizations plus the given organization
  function findOrganizationChildren (searchedOrganizationId, branch, isChildOfSelected) {
    var self = this
    if (typeof isChildOfSelected === 'undefined') {
      isChildOfSelected = false
    }
    var result = []
    // Each org in this branch
    for (i in branch) {
      // This is the selected?
      var organization = branch[i]
      if (isChildOfSelected || searchedOrganizationId === organization.id) {
        result.push(organization.id)
        if (organization.children.length > 0) {
          result = result.concat(findOrganizationChildren(searchedOrganizationId, organization.children, true))
        }
      } else {
        if (organization.children.length > 0) {
          result = result.concat(findOrganizationChildren(searchedOrganizationId, organization.children, false))
        }
      }
    }
    return result
  }

  var selectedOrganizations = undefined
  if (self._state.organization !== '') {
    selectedOrganizations = findOrganizationChildren(self._state.organization, params.organizations)
  }

  // Add each item to its creation date
  params.data.forEach(function(item) {
    var itemDate = moment.utc(item[params.schema.dateField]).format('YYYY-MM-DD')

    if (
      // Skip item for customized reason?
      !params.schema.skip(item) &&

      // Organization filtered out?
      (!params.organization || self._state.organization === '' ||
      selectedOrganizations.indexOf(item.organization.id) !== -1) &&

      // Category filtered out?
      (!params.category || self._state.category === '' || item.groups.find(function (group) {
        return group.id === self._state.category
      }))
    ) {
      result.push(item)
    }
  })

  return result
}


Statistics.prototype._getVisHeight = function (contentWidth) {
  if (contentWidth < 720) {
    return 240
  } else {
    return 360
  }
}


// Also filters by date
Statistics.prototype._createOrganizationDatasets = function (datasets, allOrganizations, dateRange) {
  var self = this

  function recursive (selectedOrganization, organizations, allOrganizations) {
    // Remains false if the currently active organization has no children
    var children = undefined
    var result = false

    // No org selected?
    if (!selectedOrganization) {
      children = organizations

    // Some org is selected?
    } else {
      // Each of the orgs on the current recursion level
      for (var i in organizations) {

        // This is the active org?
        if (selectedOrganization === organizations[i].id) {

          // Active org has children?
          if (organizations[i].children.length > 0) {
            // Create org datasets for this item's children
            children = organizations[i].children

          // Active org has no children?
          } else {
            // Create org datasets from the selected org only
            children = [organizations[i]]
          }

          break

        // This is not the active org?
        } else {
          if (organizations[i].children.length > 0) {
            result = recursive(selectedOrganization, organizations[i].children, allOrganizations)

            // Result was created deeper in this branch?
            if (result) {
              break
            }
          }
        }
      }
    }

    // Result was created deeper in these given organizations, or there was no result at all from this branch (result=false)
    if (typeof children === 'undefined') {
      return result

    // Set of children to show on screen was found on this level of recursion?
    } else {
      // Create result from these children
      result = []

      // Zero number first for each org
      for (iChild in children) {
        // Create a result organization item for this shown child
        var resultItem = {
          id: children[iChild].id,
          name: children[iChild].title,
          all: 0,
          specific: 0, // Datasets with apps
          // allRight: 0, // User counts
          // specificRight: 0, // Users who downloaded
          // icon:
        }

        // Go through all datasets
        for (iDataset in datasets) {
          var releaseDate = moment.utc(datasets[iDataset][self._schemas.datasets.dateField], 'YYYY-MM-DD')
          if (
            releaseDate.isBefore(dateRange[0]) ||
            releaseDate.isAfter(dateRange[1])
          ) {
            continue
          }

          // Organization and its parents that this dataset belongs to
          var parentChain = findParentChain(datasets[iDataset].organization.id, allOrganizations)

          // The result org item is the org or parent org of this dataset?
          if (parentChain.indexOf(resultItem.id) !== -1) {
            resultItem.all ++

            // The dataset has one or more apps also?
            if (datasets[iDataset].apps.length > 0) {
              resultItem.specific ++
            }
          }
        }


        result.push(resultItem)
      }

      return result
    }
  }

  function findParentChain (searchedOrganizationId, branch) {
    var self = this
    var result = []
    // Each org in this branch
    for (i in branch) {
      var organization = branch[i]
      // This is the selected?
      if (searchedOrganizationId === organization.id) {
        result.push(organization.id)

      // This is not the selected
      } else {
        if (organization.children.length > 0) {
          // Add if any child is
          selectedChild = findParentChain(searchedOrganizationId, organization.children)

          if (selectedChild.length > 0) {
            result = selectedChild
            result.push(organization.id)
          }
        }
      }
    }
    return result
  }

  var result = recursive(self._state.organization, allOrganizations, allOrganizations)

  return result
}


// Also filters by date
Statistics.prototype._createCategoryDatasets = function (datasets, categories, dateRange) {
  var self = this
  var result = []
  for (iCategory in categories) {
    var resultItem = {
      id: categories[iCategory].id,
      name: categories[iCategory].title_translated[self._config.locale],
      all: 0,
      specific: 0, // Datasets with apps
      // allRight: 0, // User counts
      // specificRight: 0, // Users who downloaded
      // icon:
    }

    // Go through all datasets
    for (iDataset in datasets) {
      var releaseDate = moment.utc(datasets[iDataset][self._schemas.datasets.dateField], 'YYYY-MM-DD')
      if (
        releaseDate.isBefore(dateRange[0]) ||
        releaseDate.isAfter(dateRange[1])
      ) {
        continue
      }

      // The category is the category of this dataset?
      for (iDatasetCategory in datasets[iDataset].groups) {
        if (datasets[iDataset].groups[iDatasetCategory].id === categories[iCategory].id) {
          resultItem.all ++
          // The dataset has one or more apps also?
          if (datasets[iDataset].apps.length > 0) {
            resultItem.specific ++
          }
          break
        }
      }
    }
    result.push(resultItem)
  }
  return result
}


// Also filters by date
Statistics.prototype._createFormatDatasets = function (datasets, formats, dateRange) {
  var self = this
  var result = []
  for (iFormat in formats) {
    var resultItem = {
      id: formats[iFormat],
      name: formats[iFormat],
      all: 0,
      specific: 0, // Datasets with apps
      // allRight: 0, // User counts
      // specificRight: 0, // Users who downloaded
      // icon:
    }

    // Go through all datasets
    for (iDataset in datasets) {
      var releaseDate = moment.utc(datasets[iDataset][self._schemas.datasets.dateField], 'YYYY-MM-DD')
      if (
        releaseDate.isBefore(dateRange[0]) ||
        releaseDate.isAfter(dateRange[1])
      ) {
        continue
      }
      for (iResource in datasets[iDataset].resources) {
        if (datasets[iDataset].resources[iResource].format === formats[iFormat]) {
          resultItem.all ++
          // The dataset has one or more apps also?
          if (datasets[iDataset].apps.length > 0) {
            resultItem.specific ++
          }
          break
        }
      }
    }
    result.push(resultItem)
  }
  return result
}


// Also filters by date
Statistics.prototype._createCategoryApps = function (apps, categories, dateRange) {
  var self = this
  var locale = 'fi'
  if (self._config && self._config.locale) {
    locale = self._config.locale
  }

  var result = []
  for (iCategory in categories) {
    var resultItem = {
      id: categories[iCategory],
      name: categories[iCategory],
      all: 0,
      specific: 0,
    }
    for (iApp in apps) {
      var releaseDate = moment.utc(apps[iApp][self._schemas.apps.dateField], 'YYYY-MM-DD')
      if (
        releaseDate.isBefore(dateRange[0]) ||
        releaseDate.isAfter(dateRange[1])
      ) {
        continue
      }

      for (iExtra in apps[iApp].extras) {
        var extra = apps[iApp].extras[iExtra]
        if (extra.key === 'category') {
          eval('var categoryLists = ' + extra.value)
          if (categoryLists[locale] && categoryLists[locale].indexOf(categories[iCategory]) !== -1) {
            resultItem.all ++
            resultItem.specific ++
          }
          break
        }
      }
    }
    result.push(resultItem)
  }
  return result
}


// 1 second margin time to wait that the ckan language setting is updated
$(document).ready(function () {
  setTimeout(function () {
    statistics = new Statistics()
  }, 10)
})
