import ckan.plugins as plugins
import ckan.plugins.toolkit as toolkit
from ckanext.report.interfaces import IReport


class StatisticsPlugin(plugins.SingletonPlugin):
    plugins.implements(plugins.IConfigurer)
    plugins.implements(plugins.IRoutes, inherit=True)

    # IConfigurer

    def update_config(self, config_):
        toolkit.add_template_directory(config_, 'templates')
        toolkit.add_resource('fanstatic', 'statistics')

    # IRoutes

    def before_map(self, map):
        map.connect('/statistics',
                    controller='ckanext.statistics.controller:StatisticsController',
                    action='statistics_read')

        map.connect('/statistics/internal',
                    controller='ckanext.statistics.controller:StatisticsController',
                    action='reports_read')

        return map


class PublisherActivityReportPlugin(plugins.SingletonPlugin):
    plugins.implements(IReport)
    plugins.implements(plugins.ITemplateHelpers)

    # IReport
    def register_reports(self):
        import reports
        return [reports.publisher_activity_report_info]

    # ITemplateHelpers
    def get_helpers(self):

        return {
            "report_match_rows": report_match_rows
        }

def report_match_rows(rows, type_, quarter):
    return [row for row in rows if (row[3]==type_ and row[4]==quarter)]