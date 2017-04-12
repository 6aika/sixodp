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

  self.categoryDatasets = new TopHistogram({
    id: 'categoryDatasets',
    element: self._element.select('.js-category-dataset-counts'),
    texts: {
      title: self._texts.categoriesTitle,
      amount :self._texts.amount,
    },
    legend: [
      {
        title: 'A series',
      },
      {
        title: 'B series',
      },
    ],
    limit: 10, // Before show more button is used

    width: params.width,
    margin: params.visMargins,
    schema: {
      labelField: 'name',
      valueField: 'all',
      valueSpecificField: 'specific',
    }
  })

  self.organizationDatasets = new TopHistogram({
    id: 'organizationDatasets',
    element: self._element.select('.js-organization-dataset-counts'),
    texts: {
      title: self._texts.topPublishersTitle,
      amount :self._texts.amount,
    },
    legend: [
      {
        title: 'A series',
      },
      {
        title: 'B series',
      },
    ],
    limit: 10, // Before show more button is used

    width: params.width,
    margin: params.visMargins,
    schema: {
      labelField: 'name',
      valueField: 'all',
      valueSpecificField: 'specific',
    }
  })
}

DatasetSection.prototype.setData = function (datasets, categoryDatasets, organizationDatasets) {
  var self = this
  self.totalsTimeline.setData(datasets)
  self.categoryDatasets.setData(categoryDatasets)
  self.organizationDatasets.setData(organizationDatasets)
}


DatasetSection.prototype.onContentResize = function (width, height = undefined) {
  var self = this
  self.totalsTimeline.resize(width, height)
  self.categoryDatasets.resize(width)
  self.organizationDatasets.resize(width)
}


// Filter all the visualizations in this section by the given dates
DatasetSection.prototype.setDateRange = function (dates, categoryDatasets, organizationDatasets) {
  var self = this
  self.totalsTimeline.setDateRange(dates)
  self.categoryDatasets.setDateRange(dates)
  if (categoryDatasets) {
    self.categoryDatasets.setData(categoryDatasets)
  }
  self.organizationDatasets.setDateRange(dates)
  if (organizationDatasets) {
    self.organizationDatasets.setData(organizationDatasets)
  }
}

DatasetSection.prototype.setMaxDateRange = function (dates) {
  var self = this
  self.totalsTimeline.setMaxDateRange(dates)
}

// Filter all the visualizations in this section by the given organization
DatasetSection.prototype.setOrganization = function (organization) {
  var self = this
}

// Filter all the visualizations in this section by the given category
DatasetSection.prototype.setCategory = function (category) {
  var self = this
}
