var DatasetSection = function (statistics) {
  var self = this
  self.statistics = statistics

  self.element = d3.select('.js-statistics-datasets-section')

  self.totalsTimeline = new TotalsTimeline({
    id: 'datasetCount',
    element: self.element.select('.js-dataset-totals-timeline'),
    texts: {
      title: self.statistics.translations.datasetsOpenedTitle[self.statistics.config.locale],
      amount: self.statistics.translations.amount[self.statistics.config.locale],
    },
    width: parseInt(self.statistics.styles.contentWidth),
    height: 360,
    margin: {top: 15, right: 50, bottom: 30, left: 1},
    schema: {
      nameField: 'title_translated',
      dateField: 'date_released',
      skip: function (dataset) {
        return !!dataset.private
      },
    },
    settings: {
      organizations: true
    },
    locale: self.statistics.config.locale,
  })

  // self.organizationDatasets = new TopHistogram({
  //   id: 'organizationDatasets',
  //   element: self.element.select('.js-organization-dataset-counts'),
  //   texts: {
  //     title: self.statistics.translations.topPublishersTitle[self.statistics.config.locale],
  //   },
  //   width: parseInt(self.statistics.styles.contentWidth),
  //   height: 360,
  //   margin: {top: 15, right: 50, bottom: 30, left: 1},
  //   schema: {
  //     labelField: 'title',
  //     valueField: 'dataset_count',
  //   }
  // })
}

DatasetSection.prototype.update = function () {
  var self = this
  self.totalsTimeline.setDateFilter(self.statistics.data.dateRange)
  self.totalsTimeline.setData(self.statistics.data.datasets, self.statistics.data.organizations)
  // self.organizationDatasets.setData(self.statistics.data.organizations)
}


DatasetSection.prototype.onContentResize = function (content) {
  var self = this
  self.totalsTimeline.resize(parseInt(self.statistics.styles.contentWidth))
  // self.organizationDatasets.resize(parseInt(self.statistics.styles.contentWidth))
}


// Filter all the visualizations in this section by the given dates
DatasetSection.prototype.setDateRange = function (dates) {
  var self = this
  self.totalsTimeline.setDateFilter(dates)
  // self.organizationDatasets.setDateFilter(dates)
}


// Filter all the visualizations in this section by the given organization
DatasetSection.prototype.setOrganization = function (organization) {
  var self = this
  self.totalsTimeline.setOrganizationFilter(organization)
}

// Filter all the visualizations in this section by the given category
DatasetSection.prototype.setCategory = function (category) {
  var self = this
  self.totalsTimeline.setCategoryFilter(category)
}
