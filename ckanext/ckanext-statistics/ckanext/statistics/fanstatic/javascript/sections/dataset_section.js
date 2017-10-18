var DatasetSection = function (params) {
  var self = this
  self._texts = params.texts

  self._element = d3.select('.js-statistics-datasets-section')

  d3.select('.js-statistics-datasets-section-title')
    .text(self._texts.sectionTitle);

  self.mostVisitedDatasets = new TopHistogram({
    id: 'mostVisitedDatasets',
    element: self._element.select('.js-most-visited-datasets'),
    texts: {
      title: self._texts.mostVisitedDatasetsTitle,
      amount: self._texts.amount
    },
    legend: [],
    limit: 10, // Before show more button is used

    width: params.width,
    margin: params.visMargins,
    schema: {
      labelField: 'name',
      valueField: 'all',
      valueSpecificField: 'specific',
    }
  });

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
  });

  self.categoryDatasets = new TopHistogram({
    id: 'categoryDatasets',
    element: self._element.select('.js-category-dataset-counts'),
    texts: {
      title: self._texts.categoriesTitle,
      amount: self._texts.amount,
    },
    legend: [
      {
        title: self._texts.usedInApp,
      },
      {
        title: self._texts.notUsedInApp,
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

  self.formatDatasets = new TopHistogram({
    id: 'formatDatasets',
    element: self._element.select('.js-format-dataset-counts'),
    texts: {
      title: self._texts.formatsTitle,
      amount: self._texts.amount,
    },
    legend: [
      {
        title: self._texts.usedInApp,
      },
      {
        title: self._texts.notUsedInApp,
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
      amount: self._texts.amount,
    },
    legend: [
      {
        title: self._texts.usedInApp,
      },
      {
        title: self._texts.notUsedInApp,
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

DatasetSection.prototype.updateSectionData = function(context, dateRange) {
  var self = this;

  if (!dateRange) {
    dateRange = [new Date(new Date().getFullYear(), 0, 1), new Date()];
  }

  var dateQuery = '?start_date=' + moment(dateRange[0]).format('DD-MM-YYYY') + '&end_date=' + moment(dateRange[1]).format('DD-MM-YYYY');

  return Promise.all([
    context.api.get('most_visited_packages' + dateQuery)
    .then(function(response) {
      var datasets = response.packages;
      var result = [];
      for (var index in datasets) {
        var resultItem = {
          name: datasets[index].package_name,
          all: datasets[index].visits,
          specific: 0
        };

        result.push(resultItem);
      }
      self.mostVisitedDatasets.setData(result);
    }),
    // Add rest of the data when implemented with promises
  ]);
};

DatasetSection.prototype.setData = function (context, datasets, categoryDatasets, formatDatasets, organizationDatasets) {
  var self = this

  self.totalsTimeline.setData(datasets);
  self.categoryDatasets.setData(categoryDatasets);
  self.formatDatasets.setData(formatDatasets);
  self.organizationDatasets.setData(organizationDatasets);

  // Update rest of the data implemented with promises
  self.updateSectionData(context);
}


DatasetSection.prototype.onContentResize = function (width, height) {
  if (!height)
    height = undefined
  var self = this
  self.mostVisitedDatasets.resize(width)
  self.totalsTimeline.resize(width, height)
  self.categoryDatasets.resize(width)
  self.formatDatasets.resize(width)
  self.organizationDatasets.resize(width)
}


// Filter all the visualizations in this section by the given dates
DatasetSection.prototype.setDateRange = function (context, dates, categoryDatasets, formatDatasets, organizationDatasets) {
  var self = this

  self.totalsTimeline.setDateRange(dates)

  self.categoryDatasets.setDateRange(dates)
  if (categoryDatasets) {
    self.categoryDatasets.setData(categoryDatasets)
  }

  self.formatDatasets.setDateRange(dates)
  if (formatDatasets) {
    self.formatDatasets.setData(formatDatasets)
  }

  self.organizationDatasets.setDateRange(dates)
  if (organizationDatasets) {
    self.organizationDatasets.setData(organizationDatasets)
  }

  // Update rest of the data implemented with promises
  self.updateSectionData(context, dates);
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
