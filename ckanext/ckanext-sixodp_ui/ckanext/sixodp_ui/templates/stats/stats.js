var stats = {
  downloads: {},
}

stats.init = function () {
  var self = this
  self.downloadAll(function() {
    datavis1.init(self.downloads.data1)
    datavis2.init(self.downloads.data1)
  })
}

stats.downloadAll = function (callback) {
  var self = this
  // url = api + 'package_search?rows=20'
  url = api + 'current_package_list_with_resources?limit=100'
    d3.json(url)
    // .header('Authorization', apiKey)
    .get(function (error, response) {
      console.log('Received JSON from URL', url, 'error:', error, 'response:', response)

      if (error) {
        console.log('Error in CKAN API call', url)

        self.downloads.data1 = []
        throw error
        return false
      }
      self.downloads.data1 = response.result
      callback()
    })
}

  // DATA PREPROCESSING



// render: function () {
//   datavis2.render()
// }


stats.init()
