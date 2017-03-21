var AppSection = function (statistics) {
  var self = this
  self.statistics = statistics

  self.element = d3.select('.js-app-section')

  self.totalsTimeline = new TotalsTimeline(
    self.statistics,
    self.element.select('.js-app-totals-timeline'),
    self.statistics.translations.appsPublishedTitle,
    {
      nameField: 'name',
      dateField: 'metadata_created',
      skip: function (app) {
        return false
      },
    },
    {
      organizations: false
    }
  )
}

AppSection.prototype.update = function (firstDataLoad = false) {
  var self = this
  self.totalsTimeline.updateAll(self.statistics.data.apps, firstDataLoad)
}

AppSection.prototype.onContentResize = function () {
  var self = this
  self.totalsTimeline.onAreaResize()
}
