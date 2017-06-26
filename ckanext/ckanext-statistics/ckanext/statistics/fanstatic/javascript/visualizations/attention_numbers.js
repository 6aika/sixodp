function AttentionNumbers (element, numbers) {
  var self = this;

  self._element = element;
  self._element.classed('attention-numbers', true);

  self._numbers = {};
  for (var i in numbers) {
    var number = numbers[i];
    var numberObj = {};
    numberObj.element = element.append('div')
      .classed('attention-number', true);

    numberObj.titleElement = numberObj.element.append('div')
      .classed('attention-number-title', true)
      .html(number.title) + ':';

    numberObj.valueElement = numberObj.element.append('div')
      .classed('attention-number-value', true)
      .html(0);

    numberObj.detailElement = numberObj.element.append('div')
      .classed('attention-number-detail', true);

    numberObj.detailTextElement = numberObj.detailElement.append('span')
      .classed('attention-number-detail-text', true)
      .html(number.detailText + ': ');

    numberObj.detailValueElement = numberObj.detailElement.append('span')
      .classed('attention-number-detail-value', true)
      .html('');

    numberObj.detailTextUnit = number.detailTextUnit;
    numberObj.schema = number.schema;

    self._numbers[number.id] = numberObj
  }

  self._data = {};
  self._state = {
    dateRange: [moment.utc(), moment.utc()]
  }
}


AttentionNumbers.prototype.setData = function (data) {
  var self = this;
  self._data = data;
  self._updateView(self._data);
};


AttentionNumbers.prototype.setDateRange = function (dates) {
  var self = this;
  self._state.dateRange = dates;
  self._updateView(self._data);
};


AttentionNumbers.prototype._updateView = function (data) {
  var self = this;
  for (var id in self._numbers) {
    var numberDataset = data[id];
    var numberObj = self._numbers[id];

    var lastDate = moment.utc();
    if (self._state.dateRange[1].isBefore(lastDate)) {
      lastDate = self._state.dateRange[1]
    }
    var tresholdDate = moment.utc(lastDate).subtract(3, 'months');
    var mainValue = 0;
    var detailValue = 0;
    for (var i in numberDataset) {
      var itemDate = moment.utc(numberDataset[i][numberObj.schema.dateField], 'YYYY-MM-DD');
      if (
        itemDate.isSameOrAfter(self._state.dateRange[0]) &&
        itemDate.isSameOrBefore(self._state.dateRange[1])
      ) {
        mainValue ++;
        if (moment.utc(itemDate).isSameOrAfter(tresholdDate)) {
          detailValue ++
        }
      }
    }
    numberObj.valueElement.html(mainValue);
    numberObj.detailValueElement.html(detailValue + ' ' + numberObj.detailTextUnit);
  }
};
