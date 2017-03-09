var Helpers = {
  validDate: function (string) {
    date = moment.utc(string, ['D.M.YYYY', 'DD.MM.YYYY'])

    if (!date.isValid() || date.year() < 1900 || date.year() > moment().year() || string.length < 8) {
      return false
    }
    parts = string.split('.')
    if (parts.length < 2 || parts[1] == '') {
      return false
    }
    for (i in parts) {
      if (!isFinite(parts[i])) {
        return false
      }
    }
    return date
  }
}
