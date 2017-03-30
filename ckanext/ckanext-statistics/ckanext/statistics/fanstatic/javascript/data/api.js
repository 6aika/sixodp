var Api = function (params) {
  var self = this
  self._baseUrl = params.baseUrl
}

Api.prototype.getAllData = function (callback) {
  var self = this
  var data = {}

  self.get('group_tree', function (result) {
    data.organizations = result

    self.get('group_list?all_fields=true', function (result) {
      data.categories = result

      self.get('current_package_list_with_resources?limit=1000', function (result) {
        data.datasets = result

        self.get('ckanext_showcase_list', function (result) {
          data.apps = result

          callback(data)
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

// https://developer.wordpress.org/rest-api/reference/posts/
// wp-json/wp/v2/posts
