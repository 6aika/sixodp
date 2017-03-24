var FrontSection = function (statistics) {
  var self = this
  self.statistics = statistics

  self.element = d3.select('.js-statistics-summary-section')

  self.attentionNumbers = new AttentionNumbers(
    self.element.select('.js-front-attention-numbers'),
    [
      {
        id: 'datasets',
        text: self.statistics.translations.datasetsOpened[self.statistics.config.locale]
      },
      // {
      //   id: 'users',
      //   text: self.statistics.translations.users[self.statistics.config.locale]
      // },
      {
        id: 'apps',
        text: self.statistics.translations.apps[self.statistics.config.locale]
      },
    ]
  )
}

FrontSection.prototype.update = function () {
  var self = this
  self.attentionNumbers.update({
    datasets: self.statistics.data.datasets.length,
    // users: '-', // self.data.users,
    apps: self.statistics.data.apps.length
  })
}

FrontSection.prototype.onContentResize = function () {

}
