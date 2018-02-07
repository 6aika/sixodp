var AppSection = function (params) {
  var self = this
  self._texts = params.texts

  self._element = d3.select('.js-statistics-apps-section')

  d3.select('.js-statistics-apps-section-title')
    .text(self._texts.sectionTitle)

  self.totalsTimeline = new TotalsTimeline({
    id: 'appCount',
    element: self._element.select('.js-app-totals-timeline'),
    texts: {
      title: self._texts.timelineTitle,
      amount: self._texts.amount,
      noDataText: self._texts.noDataText
    },
    width: params.width,
    height: params.visHeight,
    margin: params.visMargins,
    schema: params.schema,
    settings: {
      organizations: false
    },
    locale: params.locale,
  })

  self.categoryApps = new TopHistogram({
    id: 'categoryApps',
    element: self._element.select('.js-category-app-counts'),
    texts: {
      title: self._texts.categoriesTitle,
      amount: self._texts.amount,
      noDataText: self._texts.noDataText
    },
    legend: [
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


AppSection.prototype.setData = function (data, categoryApps) {
  var self = this
  self.totalsTimeline.setData(data)
  self.categoryApps.setData(categoryApps)
}


// Filter all the visualizations in this section by the given dates
AppSection.prototype.setDateRange = function (dates, categoryApps) {
  var self = this
  self.totalsTimeline.setDateRange(dates)

  self.categoryApps.setDateRange(dates)
  if (categoryApps) {
    self.categoryApps.setData(categoryApps)
  }
}


AppSection.prototype.setMaxDateRange = function (dates) {
  var self = this
  self.totalsTimeline.setMaxDateRange(dates)
}


AppSection.prototype.onContentResize = function (width, height) {
  if (!height)
    height = undefined
  var self = this
  self.totalsTimeline.resize(width, height)
  self.categoryApps.resize(width)
}
