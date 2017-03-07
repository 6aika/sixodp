var stats = {
  downloads: {},
}

stats.init = function () {
  var self = this

  self.download(
    'datasets',
    'current_package_list_with_resources?limit=1000',
    function () {
      self.download(
        'organizations',
        'organization_list?all_fields=true&include_extras=true',
        function () {
          self.download(
            'apps',
            'ckanext_showcase_list',
            function () {
              console.log('self.downloads', self.downloads)
              // Init all data visualizations
              datavis1.init(self.downloads.datasets, self.downloads.apps)
              datavis2.init(self.downloads.datasets, self.downloads.organizations)
            }
          )
        }
      )}
  )
}

// url = api + 'package_search?rows=20'

stats.download = function (name, endPoint, callback) {
  var self = this

  url = api + endPoint
  d3.json(url)
  // .header('Authorization', apiKey)
  .get(function (error, response) {
    console.log('Received JSON from URL', url, 'error:', error, 'response:', response)
    if (error) {
      console.log('Error in CKAN API call', url)
      self.downloads[name] = []
      throw error
      callback()
    }
    self.downloads[name] = response.result
    callback()
  })
}

stats.init()
