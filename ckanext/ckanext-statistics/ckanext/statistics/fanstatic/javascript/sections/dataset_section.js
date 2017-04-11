var DatasetSection = function (params) {
  var self = this
  self._texts = params.texts

  self._element = d3.select('.js-statistics-datasets-section')

  self.totalsTimeline = new TotalsTimeline({
    id: 'datasetCount',
    element: self._element.select('.js-dataset-totals-timeline'),
    texts: {
      title: self._texts.timelineTitle,
      amount: self._texts.amount,
    },
    width: params.width,
    height: params.visHeight,
    margin: params.visMargins,
    schema: params.schema,
    settings: {
      organizations: true
    },
    locale: params.locale,
  })

  d3.select('.js-statistics-datasets-section-title')
    .text(self._texts.sectionTitle)

  // self.organizationDatasets = new TopHistogram({
  //   id: 'organizationDatasets',
  //   element: self._element.select('.js-organization-dataset-counts'),
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

DatasetSection.prototype.setData = function (data) {
  var self = this
  self.totalsTimeline.setData(data)
  // self.organizationDatasets.setData(self.statistics.data.organizations)
}


DatasetSection.prototype.onContentResize = function (width, height) {
  if (!height)
    height = undefined
  var self = this
  self.totalsTimeline.resize(width, height)
  // self.organizationDatasets.resize(width, height))
}


// Filter all the visualizations in this section by the given dates
DatasetSection.prototype.setDateRange = function (dates) {
  var self = this
  self.totalsTimeline.setDateRange(dates)
  // self.organizationDatasets.setDateFilter(dates)
}

DatasetSection.prototype.setMaxDateRange = function (dates) {
  var self = this
  self.totalsTimeline.setMaxDateRange(dates)
  // self.organizationDatasets.setMaxDateRange(dates)
}

// Filter all the visualizations in this section by the given organization
DatasetSection.prototype.setOrganization = function (organization) {
  var self = this
}

// Filter all the visualizations in this section by the given category
DatasetSection.prototype.setCategory = function (category) {
  var self = this
}
