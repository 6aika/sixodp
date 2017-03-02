// LOGIN AS ADMIN
// https://10.106.10.10/data/user/login

// https://generic-qa.dataportaali.com/data/api/action/package_list

// https://generic-qa.dataportaali.com/


/////////////////////////7
// TODO NEXT:
/*
- Change timespan: https://bl.ocks.org/mbostock/4015254
- Move timespan by dragging the graph with mouse click: https://bl.ocks.org/mbostock/4015254, https://github.com/d3/d3-zoom
- Graphic design

- Axis design
- Grid

- Moving ball

- Get deleted datasets
- Histogram
- Change organization
- Download buttons
- Animation effects
- Smoothen the curve?
*/
// https://bl.ocks.org/mbostock/3883245
// http://bl.ocks.org/d3noob/b3ff6ae1c120eea654b5


/////////////////////////////////////////////////////7
// STATS PAGE MAIN

// url = api + 'package_list'
// 'group_list'

// Edits
// 'action/recently_changed_packages_activity_list?limit=1000'

// package_search
// http://docs.ckan.org/en/latest/api/#ckan.logic.action.get.package_search


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
