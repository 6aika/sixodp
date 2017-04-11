var AppSection = function (params) {
  var self = this
  self._texts = params.texts

  self._element = d3.select('.js-statistics-apps-section')

  self.totalsTimeline = new TotalsTimeline({
    id: 'appCount',
    element: self._element.select('.js-app-totals-timeline'),
    texts: {
      title: self._texts.timelineTitle,
      amount: self._texts.amount,
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

  d3.select('.js-statistics-apps-section-title')
    .text(self._texts.sectionTitle)
}


AppSection.prototype.setData = function (data) {
  var self = this
  self.totalsTimeline.setData(data)
}


// Filter all the visualizations in this section by the given dates
AppSection.prototype.setDateRange = function (dates) {
  var self = this
  self.totalsTimeline.setDateRange(dates)
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
}
