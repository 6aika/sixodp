var datavis1 = {}

datavis1.element = d3.select('.js-datavis-1')
datavis1.data = {}
datavis1.outputs = {
  datasets: datavis1.element.select('.js-datavis-1-datasets'),
  users: datavis1.element.select('.js-datavis-1-users'),
  apps: datavis1.element.select('.js-datavis-1-apps'),
}

datavis1.init = function (datasets, apps) {
  self = this
  self.curate(datasets, apps)
  self.render()
}

datavis1.curate = function (datasets, apps) {
  self.data.datasets = datasets.length
  self.data.users = '-'
  self.data.apps = apps.length
}

datavis1.render = function () {
  var self = this
  self.outputs.datasets.html(self.data.datasets)
  self.outputs.users.html(self.data.users)
  self.outputs.apps.html(self.data.apps)


}
