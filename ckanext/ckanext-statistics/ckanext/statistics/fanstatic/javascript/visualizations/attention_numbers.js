function AttentionNumbers (element, numbers) {
  var self = this

  self._element = element
  self._element.classed('attention-numbers', true)

  self._numbers = {}
  for (i in numbers) {
    number = numbers[i]
    numberObj = {}
    numberObj.element = element.append('div')
      .classed('attention-number', true)

    numberObj.titleElement = numberObj.element.append('div')
      .classed('attention-number-title', true)
      .html(number.title) + ':'

    numberObj.valueElement = numberObj.element.append('div')
      .classed('attention-number-value', true)
      .html(0)

    numberObj.detailElement = numberObj.element.append('div')
      .classed('attention-number-detail', true)

    numberObj.detailTextElement = numberObj.detailElement.append('span')
      .classed('attention-number-detail-text', true)
      .html(number.detailText + ': ')

    numberObj.detailValueElement = numberObj.detailElement.append('span')
      .classed('attention-number-detail-value', true)
      .html('')

    numberObj.schema = number.schema

    self._numbers[number.id] = numberObj
  }

  self._data = {}
  self._lastDate = moment.utc()
}


AttentionNumbers.prototype.setData = function (data) {
  var self = this
  self._data = data
  self._updateView(self._data)
}


AttentionNumbers.prototype.setLastDate = function (date) {
  var self = this
  self._lastDate = date
  self._updateView(self._data)
}


AttentionNumbers.prototype._updateView = function (data) {
  var self = this
  for (id in self._numbers) {
    var numberDataset = data[id]
    var numberObj = self._numbers[id]

    var tresholdDate = moment.utc(self._lastDate).subtract(1, 'years')
    var mainValue = 0
    var detailValue = 0
    for (i in numberDataset) {
      var itemDate = moment.utc(numberDataset[i][numberObj.schema.dateField], 'YYYY-MM-DD')
      if (itemDate.isSameOrBefore(self._lastDate)) {
        mainValue ++
        if (moment.utc(itemDate).isSameOrAfter(tresholdDate)) {
          detailValue ++
        }
      }
    }
    numberObj.valueElement.html(mainValue)
    numberObj.detailValueElement.html(detailValue)
  }
}
