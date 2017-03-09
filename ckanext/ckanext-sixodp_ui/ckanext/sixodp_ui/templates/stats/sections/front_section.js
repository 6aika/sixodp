var FrontSection = function (dashboard) {
  var self = this
  self.dashboard = dashboard

  self.element = d3.select('.js-front-section')

  self.attentionNumbers = new AttentionNumbers(
    self.element.select('.js-front-attention-numbers'),
    [
      {
        id: 'datasets',
        text: self.dashboard.translations.datasetsOpened[self.dashboard.config.locale]
      },
      {
        id: 'users',
        text: self.dashboard.translations.users[self.dashboard.config.locale]
      },
      {
        id: 'apps',
        text: self.dashboard.translations.apps[self.dashboard.config.locale]
      },
    ]
  )
}

FrontSection.prototype.update = function () {
  var self = this
  self.attentionNumbers.update({
    datasets: self.dashboard.data.datasets.length,
    users: '-', // self.data.users,
    apps: self.dashboard.data.apps.length
  })
}
