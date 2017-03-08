function AttentionNumbers (element, numbers) {
  this.element = element
  this.element.classed('attention-numbers', true)
  this.numbers = {}
  for (i in numbers) {
    number = numbers[i]
    numberObj = {}
    numberObj.element = element.append('div')

    numberObj.valueElement = numberObj.element.append('div')
    numberObj.valueElement.html(0)

    numberObj.textElement = numberObj.element.append('div')
    numberObj.textElement.html(number.text)

    this.numbers[number.id] = numberObj
  }
}

AttentionNumbers.prototype.update = function (data) {
  for (id in data) {
    value = data[id]
    this.numbers[id].valueElement.html(value)
  }
}
