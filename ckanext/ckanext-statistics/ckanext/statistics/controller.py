import ckan.plugins.toolkit as tk
import ckan.lib.base as base

render = base.render

class StatisticsController(tk.BaseController):

    def statistics_read(self):
        return render('statistics/statistics_read.html', extra_vars={})

    def reports_read(self):
        return render('statistics/reports_read.html', extra_vars={})