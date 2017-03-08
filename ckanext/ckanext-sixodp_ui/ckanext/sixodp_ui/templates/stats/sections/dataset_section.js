var DatasetSection = function (dashboard) {
  var self = this
  self.dashboard = dashboard

  self.element = d3.select('.js-dataset-section')

  self.totalsTimeline = new TotalsTimeline(
    self.dashboard,
    self.element.select('.js-dataset-totals-timeline'),
    self.dashboard.translations.datasetsOpenedTitle,
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
}

DatasetSection.prototype.update = function (firstDataLoad = false) {
  var self = this
  self.totalsTimeline.updateAll(self.dashboard.data.datasets, firstDataLoad)
}
