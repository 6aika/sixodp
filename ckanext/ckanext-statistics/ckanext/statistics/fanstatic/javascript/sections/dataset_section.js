var DatasetSection = function (statistics) {
  var self = this
  self.statistics = statistics

  self.element = d3.select('.js-statistics-datasets-section')

  self.totalsTimeline = new TotalsTimeline(
    self.statistics,
    self.element.select('.js-dataset-totals-timeline'),
    self.statistics.translations.datasetsOpenedTitle,
    {
      nameField: 'title_translated',
      dateField: 'date_released',
      skip: function (dataset) {
        return !!dataset.private
      },
    },
    {
      organizations: true
    }
  )

  self.organizationDatasets = new TopHistogram({
    id: 'organizationDatasets',
    element: self.element.select('.js-organization-dataset-counts'),
    translations: {
      title: self.statistics.translations.topPublishersTitle
    },
    locale: self.statistics.config.locale,
    width: parseInt(self.statistics.styles.contentWidth),
    height: 360,
    margin: {top: 15, right: 50, bottom: 30, left: 1},
    schema: {
      labelField: 'title',
      valueField: 'dataset_count',
    }
  })
}

DatasetSection.prototype.update = function (firstDataLoad = false) {
  var self = this
  self.totalsTimeline.updateAll(self.statistics.data.datasets, firstDataLoad)
  self.organizationDatasets.setData(self.statistics.data.organizations)
}

DatasetSection.prototype.onContentResize = function (content) {
  var self = this
  self.totalsTimeline.onAreaResize()
  self.organizationDatasets.resize(parseInt(self.statistics.styles.contentWidth))
}

DatasetSection.prototype.setLocale = function (locale) {
  var self = this
  self.organizationDatasets.setLocale(locale)
}
