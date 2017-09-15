// Currently selected language (fi / sv / en)
var Config = function () {
  var result = {
    //locale: window.ckan.i18n.options.locale_data.ckan[''].lang.split('_')[0],
    api: {},
  }

  result.api.domain = 'https://' + window.location.hostname + '/'
  result.api.baseUrl = result.api.domain + 'data/api/3/action/'

  return result
}
