var AppSection = function (statistics) {
  var self = this
  self.statistics = statistics

  self.element = d3.select('.js-statistics-apps-section')

  self.totalsTimeline = new TotalsTimeline({
    id: 'appCount',
    element: self.element.select('.js-app-totals-timeline'),
    texts: {
      title: self.statistics.translations.appsPublishedTitle[self.statistics.config.locale],
      amount: self.statistics.translations.amount[self.statistics.config.locale],
    },
    width: parseInt(self.statistics.styles.contentWidth),
    height: 360,
    margin: {top: 15, right: 50, bottom: 30, left: 1},
    schema: {
      nameField: 'name',
      dateField: 'metadata_created',
      skip: function (app) {
        return false
      },
    },
    settings: {
      organizations: false
    },
    locale: self.statistics.config.locale,
  })
}

AppSection.prototype.update = function () {
  var self = this
  self.totalsTimeline.setData(self.statistics.data.apps)
}

AppSection.prototype.onContentResize = function () {
  var self = this
  self.totalsTimeline.resize()
}

AppSection.prototype.setDateFilter = function (dates) {
  var self = this
  self.totalsTimeline.setDateFilter(dates)
}


// Filter all the visualizations in this section by the given dates
AppSection.prototype.setDateRange = function (dates) {
  var self = this
  self.totalsTimeline.setDateFilter(dates)
}
