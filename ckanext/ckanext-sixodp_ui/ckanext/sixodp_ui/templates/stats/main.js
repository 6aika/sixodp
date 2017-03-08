var Dashboard = function () {
  var self = this

  // Init data for pre-rendering
  self.data = {}

  self.config = Config
  self.helpers = Helpers
  self.translations = Translations
  self.timeFormats = TimeFormats
  self.styles = Styles

  self.api = new Api(self)
  self.frontSection = new FrontSection(self)
  self.datasetSection = new DatasetSection(self)
  self.appSection = new AppSection(self)

  // Set d3 locale
  d3.timeFormatDefaultLocale(self.timeFormats[self.config.locale])

  // Get all data for dashboard from APIs
  self.api.getAllData(function () {
    console.log('Fetched all data from APIs:', self.data)

    // General data transformations for the whole dashboard
    self.transformData()

    // Init all data visualizations with the updated data
    self.frontSection.update()
    self.datasetSection.update(true)
    self.appSection.update(true)
  })
}


Dashboard.prototype.transformData = function () {
  var self = this

  // eg. start date of the whole portal = 1.1. on the year of first dataset/app/article
  var firstDateDataset = d3.min(self.data.datasets, function (d) { return d.date_released })
  var firstDateApp = d3.min(self.data.apps, function (d) { return d.metadata_created })

  var firstDate = moment.utc(firstDateDataset < firstDateApp ? firstDateDataset : firstDateApp)

  firstDateVis = moment.utc([firstDate.year(), 0, 1, 0, 0, 0, 0])

  today = moment.utc()
  today = moment.utc([today.year(), today.month(), today.date(), 0, 0, 0, 0])

  self.data.dateRange = [firstDateVis, today]
}


dashboard = new Dashboard()
