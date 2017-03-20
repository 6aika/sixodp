var Statistics = function () {
  var self = this
  self.styles = {}
  self.initStyles()
  self.updateFullWidthBackground()

  self.data = {}

  self.config = Config
  self.helpers = Helpers
  self.translations = Translations
  self.timeFormats = TimeFormats

  self.api = new Api(self)
  self.frontSection = new FrontSection(self)
  self.datasetSection = new DatasetSection(self)
  self.appSection = new AppSection(self)

  // Set d3 locale
  d3.timeFormatDefaultLocale(self.timeFormats[self.config.locale])

  // Get all data for statistics from APIs
  self.api.getAllData(function () {
    console.log('Fetched all data from APIs:', self.data)

    // General data transformations for the whole statistics
    self.transformData()

    // Init all data visualizations with the updated data
    self.frontSection.update()
    self.datasetSection.update(true)
    self.appSection.update(true)
  })


  window.onresize = function () {
    var contentWidthChanged = self.updateFullWidthBackground()
    if (contentWidthChanged) {
      self.frontSection.onContentResize()
      self.datasetSection.onContentResize()
      self.appSection.onContentResize()
    }
  }
}


Statistics.prototype.transformData = function () {
  var self = this

  // eg. start date of the whole portal = 1.1. on the year of first dataset/app/article
  var firstDateDataset = d3.min(self.data.datasets, function (d) { return d.date_released })
  var firstDateApp = d3.min(self.data.apps, function (d) { return d.metadata_created })

  // Default value if no data exists
  var firstDateDatavis = moment.utc([moment.utc().year(), 0, 1])
  if (firstDateDataset !== undefined || firstDateApp !== undefined) {
    var firstDate = firstDateDataset
    if (
      (firstDateDataset == undefined && firstDateApp !== undefined)
      || firstDateDataset > firstDateApp
    ) {
      firstDate = firstDateApp
    }
    firstDate = moment.utc(firstDate)
    firstDateVis = moment.utc([firstDate.year(), 0, 1])
  }
  var today = moment.utc()
  today = moment.utc([today.year(), today.month(), today.date()])

  self.data.dateRange = [firstDateVis, today]
}


Statistics.prototype.updateFullWidthBackground = function () {
  var self = this
  var previousContentWidth = self.styles.contentWidth

  d3.select('#content').style('width', undefined)
  self.styles.contentWidth = d3.select('#content').style('width')
  console.log('Update contentwidth to', self.styles.contentWidth)

  if (self.styles.contentWidth != previousContentWidth) {
    d3.selectAll('.statistics-section-content').style('width', self.styles.contentWidth)
  }

  d3.select('#content')
    .style('margin', 0)
    .style('padding', 0)
    .style('width', 'auto')

  return self.styles.contentWidth != previousContentWidth
}


Statistics.prototype.initStyles = function () {
  var self = this
  d3.select('#js-bootstrap-offcanvas')
    .style('display', 'none')
}


statistics = new Statistics()
