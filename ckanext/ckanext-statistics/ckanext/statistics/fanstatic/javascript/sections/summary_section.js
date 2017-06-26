var SummarySection = function (params) {
  var self = this
  self._texts = params.texts
  self._element = params.element

  self._attentionNumbers = new AttentionNumbers(
    self._element.select('.js-summary-attention-numbers'),
    [
      {
        id: 'datasets',
        title: self._texts.datasetsTitle,
        detailText: self._texts.detailText,
        detailTextUnit: self._texts.detailTextUnit,
        schema: params.datasetSchema,
      },
      {
        id: 'apps',
        title: self._texts.appsTitle,
        detailText: self._texts.detailText,
        detailTextUnit: self._texts.detailTextUnit,
        schema: params.appSchema,
      },
    ]
  )
}

SummarySection.prototype.setData = function (data) {
  var self = this
  self._attentionNumbers.setData(data)
}


SummarySection.prototype.setDateRange = function (dates) {
  var self = this
  self._attentionNumbers.setDateRange(dates)
}


SummarySection.prototype.onContentResize = function (width, height) {
  if (!height)
    height = undefined
}
