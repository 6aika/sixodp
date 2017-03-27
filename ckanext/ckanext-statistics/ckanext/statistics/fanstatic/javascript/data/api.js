var Api = function (dashboard) {
  var self = this
  self.dashboard = dashboard
}

Api.prototype.getAllData = function (callback) {
  var self = this

  self.get('organizations',
  // 'organization_list?all_fields=true&include_extras=true',
  'group_tree',
  function () {

    self.get('categories',
    // 'organization_list?all_fields=true&include_extras=true',
    'group_list?all_fields=true',
    function () {

      // Get all data from APIs, one after another
      self.get('datasets', 'current_package_list_with_resources?limit=1000', function () {

        self.get('apps', 'ckanext_showcase_list', callback)
      })
    })
  })
}


// url = api + 'package_search?rows=20'
Api.prototype.get = function (dataId, endPoint, callback) {
  var self = this
  url = self.dashboard.config.api.baseUrl + endPoint
  d3.json(url)
  // .header('Authorization', config.api.key)
  .get(function (error, response) {
    if (error) {
      console.log('[ERROR] in dashboard fetching API, ', dataId, ' url:', url, 'error:', error)
      self.dashboard.data[dataId] = []
      throw error
    } else {
      // console.log('Dashboard api.get', dataId, 'url:', url, 'response:', response)
      self.dashboard.data[dataId] = response.result
    }
    callback()
  })
}

// https://developer.wordpress.org/rest-api/reference/posts/
// wp-json/wp/v2/posts
