var AppSection = function (dashboard) {
  var self = this
  self.dashboard = dashboard

  self.element = d3.select('.js-app-section')

  self.totalsTimeline = new TotalsTimeline(
    self.dashboard,
    self.element.select('.js-app-totals-timeline'),
    self.dashboard.translations.datasetsOpenedTitle,
    {
      nameField: 'name',
      dateField: 'metadata_created',
      skip: function (dataset) {
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
  self.totalsTimeline.updateAll(self.dashboard.data.apps, firstDataLoad)
}
