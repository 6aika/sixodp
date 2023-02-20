"use strict";

ckan.module('statistics', function($){
  return {
    initialize: function () {

      this.options.styles.visHeight = this.getVisHeight(this.options.styles.contentWidth);
      this.options.api = new Api({
        baseUrl: ckan.SITE_ROOT + '/api/action/',
        width: this.options.styles.contentWidth,
        texts: {
          loading: this._("Loading"),
          loadWebpage: this._("Loading web page"),
          loadOrganizations: this._("Loading organizations"),
          loadCategories: this._('Loading categories'),
          loadDatasets: this._('Loading data'),
          loadApps: this._('Loading apps'),
          loadAppDatasetRelations: this._('Loading relations'),
          loadPreprocessing: this._('Preprocessing'),
          loadRendering: this._('Rendering'),
          loadDone: this._("Done")
        }
      });

      d3.timeFormatDefaultLocale(this.localeData.timeFormats[this.options.locale]);
      this.loadDataToPage()
    },

    options: {
      styles: {
        contentWidth: parseInt(d3.select('.statistics-section-content:first-child').style('width')),
        visMargins: {top: 15, right: 50, bottom: 30, left: 15},
        visMarginsWithTop: {top: 50, right: 15, bottom: 30, left: 15},
        visHeight: 360
      },
      schemas: {
        datasets: {
          nameField: 'title_translated',
          dateField: 'date_released',
          skip: function (dataset) {
            return false
          }
        },
        apps: {
          nameField: 'name',
          dateField: 'metadata_created',
          skip: function (app) {
            return false
          }
        }
      },
      locale: jQuery('html').attr('lang').split('_')[0],
      onScroll: null
    },
    state: {
      dateRange: [new Date(new Date().getFullYear(), 0, 1), new Date()],
      organization: '',
      category: ''
    },

    data: {},

    localeData: Locales,
    api: null,
    nav: null,
    sections: {
      summarySection: null,
      datasetSection: null,
      appSection: null
    },

    getVisHeight: function (contentWidth) {
      if (contentWidth < 720) {
        return 240
      } else {
        return 360
      }
    },

    createNav: function () {
      this.nav = new StatisticsNav({
        element: $('.statistics-nav'),
        items: [
          {
            id: 'summary',
            title: this._('Summary')
          },
          {
            id: 'datasets',
            title: this._('Datas')
          },
          {
            id: 'apps',
            title: this._('Apps')
          }
        ],
        texts: {
          allPublishers: this._('All publishers'),
          allCategories: this._('All categories'),
          wholeDatespan: this._('All')
        },
        locale: this.options.locale,

        // Disable scroll events for a moment when scrolling automatically
        setAutoscrolling: function (value) {
          if (value) {
            window.onscroll = undefined
          } else {
            setTimeout(function () {
              window.onscroll = self._onScroll
            }, 100)
          }
        },

        // Change the hash (and fire hash change event to change system state)
        broadcastHashState: function (hash) {
          hash = '#' + hash
          if(history.replaceState) {
            history.replaceState(null, null, hash)
          }
          else {
            window.onhashchange = undefined
            window.location.hash = hash
            setTimeout(function () {
              window.onhashchange = self.onHashChange
            }, 100)
          }
        },

        broadcastDateRange: (function (dates) {
          var self = this;
          self.state.dateRangeFilter = dates;
          self.sections.summarySection.setDateRange(dates);

          self.sections.datasetSection.setDateRange(
            self.options,
            dates,
            self.createCategoryDatasets(
              self.data.filtered.datasets,
              self.data.filtered.categories,
              self.state.dateRangeFilter
            ),
            self.createFormatDatasets(
              self.data.filtered.datasets,
              self.data.filtered.formats,
              self.state.dateRangeFilter
            ),
            self.createOrganizationDatasets(
              self.data.filtered.datasets,
              self.data.filtered.organizations,
              self.state.dateRangeFilter
            )
          );

          self.sections.appSection.setDateRange(
            dates,
            self.createCategoryApps(
              self.data.filtered.apps,
              self.data.filtered.appCategories[self.options.locale],
              self.state.dateRangeFilter
            )
          )
          // self.articleSection.setDateRange(dates)
        }).bind(this),

        broadcastOrganization: (function(value) {
          var self = this;
          self.state.organization = value;
          self.data.filtered = self.filterAllData(self.data.all);
          self.sections.datasetSection.setOrganization(self.state.organization);
          self.sections.datasetSection.setData(
            self.options,
            self.data.filtered.datasets,
            self.createCategoryDatasets(
              self.data.filtered.datasets,
              self.data.filtered.categories,
              self.state.dateRangeFilter
            ),
            self.createFormatDatasets(
              self.data.filtered.datasets,
              self.data.filtered.formats,
              self.state.dateRangeFilter
            ),
            self.createOrganizationDatasets(
              self.data.filtered.datasets,
              self.data.filtered.organizations,
              self.state.dateRangeFilter
            )
          )
        }).bind(this),

        broadcastCategory: (function(value) {
          var self = this;
          self.state.category = value;
          self.data.filtered = self.filterAllData(self.data.all);
          self.sections.datasetSection.setCategory(self.state.category);
          self.sections.datasetSection.setData(
            self.options,
            self.data.filtered.datasets,
            self.createCategoryDatasets(
              self.data.filtered.datasets,
              self.data.filtered.categories,
              self.state.dateRangeFilter
            ),
            self.createFormatDatasets(
              self.data.filtered.datasets,
              self.data.filtered.formats,
              self.state.dateRangeFilter
            ),
            self.createOrganizationDatasets(
              self.data.filtered.datasets,
              self.data.filtered.organizations,
              self.state.dateRangeFilter
            )
          )
        }).bind(this)
      })
    },

    createSections: function () {
      // Create summary section
      this.sections.summarySection = new SummarySection({
        element: d3.select('.js-statistics-summary-section'),
        texts: {
          datasetsTitle: this._('Data opened'),
          appsTitle: this._('Apps created'),
          detailText: this._('Last three months'),
          detailTextUnit: this._('pcs')
        },
        datasetSchema: this.options.schemas.datasets,
        appSchema: this.options.schemas.apps
      });

      // Create dataset section
      this.sections.datasetSection = new DatasetSection({
        texts: {
          sectionTitle: this._('Datas'),
          noDataText: this._('No data available'),
          mostVisitedDatasetsTitle: this._('Most visited'),
          timelineTitle: this._('Timeline'),
          amount: this._('pcs'),
          topPublishersTitle: this._('Publishers'),
          categoriesTitle: this._('Categories'),
          formatsTitle: this._('File formats'),
          usedInApp: this._('Used in an app'),
          notUsedInApp: this._('Not used in any app')
        },
        width: this.options.styles.contentWidth,
        visMargins: this.options.styles.visMargins,
        visHeight: this.options.styles.visHeight,
        schema: this.options.schemas.datasets,
        locale: this.options.locale
      });

      // Create app section
      this.sections.appSection = new AppSection({
        texts: {
          sectionTitle: this._('Apps'),
          noDataText: this._('No data available'),
          timelineTitle: this._('Timeline'),
          categoriesTitle: this._('Categories'),
          amount: this._('pcs')
        },
        width: this.options.styles.contentWidth,
        visMargins: this.options.styles.visMargins,
        visHeight: this.options.styles.visHeight,
        schema: this.options.schemas.apps,
        locale: this.options.locale
      })
    },

    createCategoryDatasets: function (datasets, categories, dateRange) {
      var self = this
      var result = []
      for (var iCategory in categories) {
        var resultItem = {
          id: categories[iCategory].id,
          name: categories[iCategory].title_translated[self.options.locale],
          category: 'category',
          all: 0,
          specific: 0, // Datasets with apps
          // allRight: 0, // User counts
          // specificRight: 0, // Users who downloaded
          // icon:
        }

        // Go through all datasets
        for (var iDataset in datasets) {
          var releaseDate = moment.utc(datasets[iDataset][self.options.schemas.datasets.dateField], 'YYYY-MM-DD');
          if (
            releaseDate.isBefore(dateRange[0]) ||
            releaseDate.isAfter(dateRange[1])
          ) {
            continue
          }

          // The category is the category of this dataset?
          for (var iDatasetCategory in datasets[iDataset].groups) {
            if (datasets[iDataset].groups[iDatasetCategory].id === categories[iCategory].id) {
              resultItem.all ++
              resultItem.specific ++
              // The dataset has one or more apps also?
              //if (datasets[iDataset].apps.length > 0) {
              //  resultItem.specific ++
              //}
              break
            }
          }
        }
        result.push(resultItem)
      }
      return result
    },

    bindEvents: function () {
      var self = this;

      // Scroll event: Tell nav to update its state
      this.options.onScroll = $.throttle(300, function () {
        var y = $(window).scrollTop();
        self.nav.onScroll(y)
      });

      window.onscroll = this.options.onScroll;

      // Hash change event: Scroll to correct section on page
      self.onHashChange = function (e) {
        if (e && e.preventDefault) {
          e.preventDefault()
        }
        var hash = location.hash.substring(1);
        self.nav.onHashChange(hash);
        return false
      };
      window.onhashchange = self.onHashChange;

      // Resize elements on window resize
      window.onresize = function () {
        self.nav.onResize();

        var newWidth = parseInt(d3.select('.statistics-section-content:first-child').style('width'));
        self.options.styles.visHeight = self.getVisHeight(self.options.styles.contentWidth);
        if (newWidth !== self.options.styles.contentWidth) {
          self.options.styles.contentWidth = newWidth;
          self.sections.summarySection.onContentResize(self.options.styles.contentWidth);
          self.sections.datasetSection.onContentResize(self.options.styles.contentWidth, self.options.styles.visHeight);
          self.sections.appSection.onContentResize(self.options.styles.contentWidth, self.options.styles.visHeight)
        }
      }
    },

    getMaxDateRange: function (allData) {
      // eg. start date of the whole portal = 1.1. on the year of first dataset/app/article
      var firstDateDataset = d3.min(allData.datasets, function (d) { return d.date_released });
      var firstDateApp = d3.min(allData.apps, function (d) { return d.metadata_created });

      // Default value if no data exists
      var firstDate = moment.utc().year() + '-01-01';

      if (typeof firstDateDataset !== 'undefined') {
        firstDate = firstDateDataset
      }
      if (typeof firstDateApp !== 'undefined' && firstDateApp < firstDateDataset) {
        firstDate = firstDateApp
      }
      firstDate = moment.utc(firstDate)
      var firstDateVis = moment.utc([firstDate.year(), 0, 1])

      var lastDateDataset = d3.max(allData.datasets, function (d) { return d.date_released });
      var lastDateApp = d3.max(allData.apps, function (d) { return d.metadata_created });

      var today = moment.utc()

      var lastDate = today;

      if (typeof lastDateDataset !== 'undefined') {
        lastDate = lastDateDataset
      }
      if (typeof lastDateApp !== 'undefined' && lastDateApp > lastDateDataset) {
        lastDate = lastDateApp
      }

      lastDate = moment.utc(lastDate)
      var lastDateVis = moment.utc([lastDate.year(), lastDate.month(), lastDate.date()])



      return [firstDateVis, lastDateVis]
    },

    filterAllData: function (data) {
      var result = {};

      result.all = data;
      result.organizations = data.organizations;
      result.categories = data.categories;
      result.formats = data.formats;
      result.appCategories = data.appCategories;

      // Filter out datasets from wrong organization or category
      result.datasets = this.filterItems({
        data: data.datasets,
        schema: this.options.schemas.datasets,
        organization: true,
        organizations: result.organizations,
        category: true
      });

      // Filter out apps outside date range
      result.apps = this.filterItems({
        data: data.apps,
        schema: this.options.schemas.apps,
        organization: false,
        category: false
      });
      return result
    },

    filterItems: function (params) {
      var self = this;
      var result = [];

      // Returns all child and grand child organizations plus the given organization
      function findOrganizationChildren (searchedOrganizationId, branch, isChildOfSelected) {
        if (typeof isChildOfSelected === 'undefined') {
          isChildOfSelected = false
        }
        var result = [];
        // Each org in this branch
        for (i in branch) {
          // This is the selected?
          var organization = branch[i];
          if (isChildOfSelected || searchedOrganizationId === organization.id) {
            result.push(organization.id);
            if (organization.children.length > 0) {
              result = result.concat(findOrganizationChildren(searchedOrganizationId, organization.children, true))
            }
          } else {
            if (organization.children.length > 0) {
              result = result.concat(findOrganizationChildren(searchedOrganizationId, organization.children, false))
            }
          }
        }
        return result
      }

      var selectedOrganizations = undefined;
      if (this.state.organization !== '') {
        selectedOrganizations = findOrganizationChildren(this.state.organization, params.organizations)
      }

      // Add each item to its creation date
      params.data.forEach(function(item) {
        var itemDate = moment.utc(item[params.schema.dateField]).format('YYYY-MM-DD')

        if (
          // Skip item for customized reason?
        !params.schema.skip(item) &&

        // Organization filtered out?
        (!params.organization || self.state.organization === '' ||
        selectedOrganizations.indexOf(item.organization.id) !== -1) &&

        // Category filtered out?
        (!params.category || self.state.category === '' || item.groups.find(function (group) {
          return group.id === self.state.category
        }))
        ) {
          result.push(item)
        }
      });

      return result
    },

    createFormatDatasets: function (datasets, formats, dateRange) {
      var result = [];
      for (var iFormat in formats) {
        var resultItem = {
          id: formats[iFormat],
          name: formats[iFormat],
          category: 'format',
          all: 0,
          specific: 0, // Datasets with apps
          // allRight: 0, // User counts
          // specificRight: 0, // Users who downloaded
          // icon:
        }

        // Go through all datasets
        for (var iDataset in datasets) {
          var releaseDate = moment.utc(datasets[iDataset][this.options.schemas.datasets.dateField], 'YYYY-MM-DD');
          if (
            releaseDate.isBefore(dateRange[0]) ||
            releaseDate.isAfter(dateRange[1])
          ) {
            continue
          }
          for (var iResource in datasets[iDataset].resources) {
            if (datasets[iDataset].resources[iResource].format === formats[iFormat]) {
              resultItem.all ++
              resultItem.specific ++
              // The dataset has one or more apps also?
              //if (datasets[iDataset].apps.length > 0) {
              //  resultItem.specific ++
              //}
              break
            }
          }
        }
        result.push(resultItem)
      }
      return result
    },

    createOrganizationDatasets: function (datasets, allOrganizations, dateRange) {
      var self = this;
      function recursive (selectedOrganization, organizations, allOrganizations) {
        // Remains false if the currently active organization has no children
        var children = undefined;
        var result = false;

        // No org selected?
        if (!selectedOrganization) {
          children = organizations

          // Some org is selected?
        } else {
          // Each of the orgs on the current recursion level
          for (var i in organizations) {

            // This is the active org?
            if (selectedOrganization === organizations[i].id) {

              // Active org has children?
              if (organizations[i].children.length > 0) {
                // Create org datasets for this item's children
                children = organizations[i].children

                // Active org has no children?
              } else {
                // Create org datasets from the selected org only
                children = [organizations[i]]
              }

              break

              // This is not the active org?
            } else {
              if (organizations[i].children.length > 0) {
                result = recursive(selectedOrganization, organizations[i].children, allOrganizations);

                // Result was created deeper in this branch?
                if (result) {
                  break
                }
              }
            }
          }
        }

        // Result was created deeper in these given organizations, or there was no result at all from this branch (result=false)
        if (typeof children === 'undefined') {
          return result

          // Set of children to show on screen was found on this level of recursion?
        } else {
          // Create result from these children
          result = [];

          // Zero number first for each org
          for (var iChild in children) {
            // Create a result organization item for this shown child
            var resultItem = {
              id: children[iChild].id,
              name: children[iChild].title,
              category: 'organization',
              all: 0,
              specific: 0, // Datasets with apps
              // allRight: 0, // User counts
              // specificRight: 0, // Users who downloaded
              // icon:
            }

            // Go through all datasets
            for (var iDataset in datasets) {
              var releaseDate = moment.utc(datasets[iDataset][self.options.schemas.datasets.dateField], 'YYYY-MM-DD');
              if (
                releaseDate.isBefore(dateRange[0]) ||
                releaseDate.isAfter(dateRange[1])
              ) {
                continue
              }

              // Organization and its parents that this dataset belongs to
              var parentChain = findParentChain(datasets[iDataset].organization.id, allOrganizations);

              // The result org item is the org or parent org of this dataset?
              if (parentChain.indexOf(resultItem.id) !== -1) {
                resultItem.all ++;
                resultItem.specific ++
                // The dataset has one or more apps also?
                //if (datasets[iDataset].apps.length > 0) {
                //  resultItem.specific ++
                //}
              }
            }


            result.push(resultItem)
          }

          return result
        }
      }

      function findParentChain (searchedOrganizationId, branch) {
        var result = [];
        // Each org in this branch
        for (var i in branch) {
          var organization = branch[i];
          // This is the selected?
          if (searchedOrganizationId === organization.id) {
            result.push(organization.id)

            // This is not the selected
          } else {
            if (organization.children.length > 0) {
              // Add if any child is
              var selectedChild = findParentChain(searchedOrganizationId, organization.children);

              if (selectedChild.length > 0) {
                result = selectedChild
                result.push(organization.id)
              }
            }
          }
        }
        return result
      }

      return recursive(this.state.organization, allOrganizations, allOrganizations);
    },

    createCategoryApps: function (apps, categories, dateRange) {
      var self = this;

      var result = [];
      for (var iCategory in categories) {
        var resultItem = {
          id: categories[iCategory],
          name: categories[iCategory],
          category: 'app_category',
          all: 0,
          specific: 0
        };

        for (var iApp in apps) {
          var releaseDate = moment.utc(apps[iApp][self.options.schemas.apps.dateField], 'YYYY-MM-DD');
          if (
            releaseDate.isBefore(dateRange[0]) ||
            releaseDate.isAfter(dateRange[1])
          ) {
            continue
          }

          for (var iExtra in apps[iApp].extras) {
            var extra = apps[iApp].extras[iExtra];
            if (extra.key === 'category') {
              var categoryLists = JSON.parse(extra.value);
              if (categoryLists[self.options.locale] && categoryLists[self.options.locale].indexOf(categories[iCategory]) !== -1) {
                resultItem.all ++
                resultItem.specific ++
              }
              break
            }
          }
        }
        result.push(resultItem)
      }
      return result
    },

    loadDataToPage: function () {
      var self = this;
      this.options.api.getAllData(function (result) {

        // Build the page contents
        d3.select('.js-statistics-main-title')
          .text(self._('Site analytics'));

        self.createNav();
        self.createSections();
        self.bindEvents();

        // Get maximum possible date range for unfiltered data
        self.state.maxDateRange = self.getMaxDateRange(result);

        // Initially use max start or end date
        self.state.dateRangeFilter = self.state.maxDateRange;

        // General data transformations for the whole statistics
        self.data.all = result;

        // Apply global filters
        self.data.filtered = self.filterAllData(self.data.all);

        // Update summary section (show non-filtered counts)
        self.sections.summarySection.setDateRange(self.state.dateRangeFilter);
        self.sections.summarySection.setData({
          datasets: self.data.filtered.datasets,
          apps: self.data.filtered.apps
        });

        // Update dataset section
        self.sections.datasetSection.setMaxDateRange(self.state.maxDateRange);
        self.sections.datasetSection.setDateRange(self.options, self.state.dateRangeFilter);
        self.sections.datasetSection.setOrganization(self.state.organization);
        self.sections.datasetSection.setCategory(self.state.category);
        self.sections.datasetSection.setData(
          self.options,
          self.data.filtered.datasets,
          self.createCategoryDatasets(
            self.data.filtered.datasets,
            self.data.filtered.categories,
            self.state.dateRangeFilter
          ),
          self.createFormatDatasets(
            self.data.filtered.datasets,
            self.data.filtered.formats,
            self.state.dateRangeFilter
          ),
          self.createOrganizationDatasets(
            self.data.filtered.datasets,
            self.data.filtered.organizations,
            self.state.dateRangeFilter
          )
        )

        // Update app section
        self.sections.appSection.setMaxDateRange(self.state.maxDateRange);
        self.sections.appSection.setDateRange(self.state.dateRangeFilter);


        self.sections.appSection.setData(
          self.data.filtered.apps,
          self.createCategoryApps(
            self.data.filtered.apps,
            self.data.filtered.appCategories[self.options.locale],
            self.state.dateRangeFilter
          )
        )

        // Update nav scroll positions and filters etc.
        self.nav.dataLoaded({
          organizations: self.data.filtered.organizations,
          categories: self.data.filtered.categories,
          dateRange: self.state.dateRangeFilter,
          maxDateRange: self.state.maxDateRange,
          hash: location.hash.substring(1),
        })

      }, 400)
    }

  }
});
