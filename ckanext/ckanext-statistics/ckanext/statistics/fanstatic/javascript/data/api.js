var Api = function (params) {
  var self = this;

  self._defaultEndDate = new Date();
  self._defaultStartDate = new Date(new Date().getFullYear(), 0, 1);
  self._baseUrl = params.baseUrl;
  self._width = params.width;
  self._texts = params.texts;
  self._elem = {};
  self._elem.container = $('.js-statistics-loading');
  self._elem.stepContainer = d3.select('.js-statistics-loading-steps');
  self._elem.loaded = d3.select('.js-statistics-loading-loaded');
  self._elem.text = d3.select('.js-statistics-loading-text');
};

Api.prototype.getAllData = function (callback, delay) {
  var self = this;
  var data = {};
  self._elem.stepContainer.style('display', 'block');
  var width = self._elem.loaded.style('width');
  self._elem.loaded.style('animation', 'none');
  self._elem.loaded.style('width', width);
  self._stepLoaded(self._texts.loadOrganizations, 66.1);

  return Promise.all([
    self.get('group_tree'),
    self.get('group_list?all_fields=true&include_extras=true'),
    self.get('get_all_public_datasets'),
    self.get('ckanext_showcase_list?include_private=false')
  ])
  .spread(function(organizations, categories, datasets, apps) {
    data.organizations = organizations;
    data.categories = categories;
    data.apps = apps;
    data.datasets = datasets;

    /*
    return Promise.map(data.apps, function(app, index) {
      return self.get('ckanext_showcase_package_list?showcase_id=' + app.id)
      .then(function(result) {
        data.apps[index].datasets = result || [];
      });
    });
     */
  })
  .then(function() {
    data = self._preprocess(data);
    callback(data);
    setTimeout(function () {
      self._stepLoaded(self._texts.loadDone, 100.0);
      self._elem.container.fadeOut(200, function () {
        self._elem.container.remove();
      });
    }, delay);
  })
  .catch(function(err) {
    console.error(err);
  });
};

Api.prototype.get = function(endPoint) {
  var self = this;
  var url = self._baseUrl + endPoint;
  return new Promise(function(resolve, reject) {
    d3.json(url).get(function (error, response) {
      if (error) {
        return reject(error);
      }
      return resolve(response.result || []);
    });
  });
};


// Adds related app data to each dataset, and creates a list of all existing file formats and app categories
Api.prototype._preprocess = function (data) {

  // Add data about related apps into each dataset on the dataset listing
  // First add empty container for each dataset's related apps
  for (iDataset in data.datasets) {
    data.datasets[iDataset].apps = []
  }
  // Go through all apps
  for (iApp in data.apps) {
    // Go through each dataset that is related to the inspected app
    for (iAppsDataset in data.apps[iApp].datasets) {
      var idOfAppsDataset = data.apps[iApp].datasets[iAppsDataset].id

      // Go through all datasets in the dataset listing to find the dataset found on the app's data structure
      for (iDatasetList in data.datasets) {
        if (data.datasets[iDatasetList].id === idOfAppsDataset) {
          // When the correct dataset in the dataset listing is found, add the app to the dataset's list of apps.
          data.datasets[iDatasetList].apps.push({
            // Just id and title are needed when using apps in visualizations
            id: data.apps[iApp].id,
            title: data.apps[iApp].title,
          })
          break
        }
      }
    }
  }

  // Create file formats list
  data.formats = []
  // Go through all resources of all listed datasets
  for (iDataset in data.datasets) {
    var dataset = data.datasets[iDataset]
    for (iResource in dataset.resources) {
      var resource = dataset.resources[iResource]
      // Add the file format of the resource into the list of formats if it's not there yet
      if (resource.format && data.formats.indexOf(resource.format) === -1) {
        data.formats.push(resource.format)
      }
    }
  }
  function alphabeticsOrder (a, b) {
    if (a === '') {
      return true
    }
    if (b === '') {
      return false
    }
    return a > b
  }
  data.formats.sort(alphabeticsOrder)

  // Create app category list. Separate list of each language because of the structure of the api response.
  data.appCategories = {}
  // Go through all apps
  for (iApp in data.apps) {
    var app = data.apps[iApp]
    // Go through all extra fields of the app, because category information is there
    for (iExtra in app.extras) {
      var extra = app.extras[iExtra]
      // Only use the 'category' extra fields
      if (extra.key === 'category') {
        // The value of category is saved as a string of JS array statement
        eval('var categoryLists = ' + extra.value)
        // Add categories of each language's list
        for (lang in categoryLists) {
          data.appCategories[lang] = data.appCategories[lang]|| []
          for (iCategory in categoryLists[lang]) {
            var categoryName = categoryLists[lang][iCategory]
            if (data.appCategories[lang].indexOf(categoryName) === -1) {
              data.appCategories[lang].push(categoryName)
            }
          }
        }
      }
    }
  }
  for (lang in data.appCategories) {
    data.appCategories[lang].sort(alphabeticsOrder)
  }
  return data
}


Api.prototype._stepLoaded = function (id, percentage) {
  var self = this
  self._elem.loaded.transition().duration(600).style('width', (percentage / 100 * self._width) + 'px')
  self._elem.text.text(id[0].toUpperCase() + id.substring(1).toLowerCase())
}
