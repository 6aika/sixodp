var Api = function (params) {
  var self = this
  self._baseUrl = params.baseUrl
  self._width = params.width
  self._texts = params.texts
  self._elem = {}
  self._elem.container = $('.js-statistics-loading')
  self._elem.stepContainer = d3.select('.js-statistics-loading-steps')
  self._elem.loaded = d3.select('.js-statistics-loading-loaded')
  self._elem.text = d3.select('.js-statistics-loading-text')
}

Api.prototype.getAllData = function (callback, delay) {
  var self = this
  var data = {}
  self._elem.stepContainer.style('display', 'block')
  var width = self._elem.loaded.style('width')
  self._elem.loaded.style('animation', 'none')
  self._elem.loaded.style('width', width)
  self._stepLoaded(self._texts.loadOrganizations, 66.1) // 33.4) // 21.3

  self.get('group_tree', function (result) {
    data.organizations = result
    self._stepLoaded(self._texts.loadCategories, 73.8) // 48.5)

    self.get('group_list?all_fields=true', function (result) {
      data.categories = result
      self._stepLoaded(self._texts.loadDatasets, 81.8) // 64.2)

      self.get('current_package_list_with_resources?limit=1000', function (result) {
        data.datasets = result
        self._stepLoaded(self._texts.loadApps, 88.8) // 77.9)

        self.get('ckanext_showcase_list', function (result) {
          data.apps = result
          self._stepLoaded(self._texts.loadRendering, 99)

          // https://developer.wordpress.org/rest-api/reference/posts/
          // wp-json/wp/v2/posts

          callback(data)

          // Hide loading screen
          setTimeout(function () {
            self._stepLoaded(self._texts.loadDone, 100)
            self._elem.container.fadeOut(200, function () {
              self._elem.container.remove()
            })
          }, delay)
        })
      })
    })
  })
}


// url = api + 'package_search?rows=20'
Api.prototype.get = function (endPoint, callback) {
  var self = this
  url = self._baseUrl + endPoint
  d3.json(url)
  // .header('Authorization', config.api.key)
  .get(function (error, response) {
    var result = []
    if (error) {
      console.log('[ERROR] in fetching API for statistics:', url, 'error:', error)
      throw error
    } else {
      result = response.result
    }
    callback(result)
  })
}


Api.prototype._stepLoaded = function (id, percentage) {
  var self = this
  self._elem.loaded.transition().duration(600).style('width', (percentage / 100 * self._width) + 'px')
  self._elem.text.text(id[0].toUpperCase() + id.substring(1).toLowerCase())
}
