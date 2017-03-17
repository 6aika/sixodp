// Currently selected language (fi / sv / en)
var Config = {
  locale: 'fi',
  api: {},
}
Config.api.domain = 'https://' + window.location.hostname + '/'
Config.api.baseUrl = Config.api.domain + 'data/api/3/action/'
