import datetime
import collections

from ckan.common import OrderedDict, _
from ckanext.report import lib
import ckan.plugins as p
from ckan.plugins import toolkit

def publisher_activity(organization, include_sub_organizations=False):
    """
    Contains information about the datasets a specific organization has
    released in this and last quarter (calendar year). This is needed by
    departments for their quarterly transparency reports.
    """
    import datetime

    now = datetime.datetime.now()

    if organization:
        quarters = get_quarter_dates(now)

        created, modified = _get_activity(
            organization, include_sub_organizations, quarters)

        datasets = []
        for quarter_name in quarters:
            datasets += sorted(created[quarter_name], key=lambda x: x[1])
            datasets += sorted(modified[quarter_name], key=lambda x: x[1])
        columns = ('Dataset name', 'Dataset title', 'Dataset notes', 'Modified or created', 'Quarter', 'Timestamp', 'Author', 'Published')

        quarters_iso = dict(
            [(last_or_this, [date_.isoformat() for date_ in q_list])
             for last_or_this, q_list in quarters.iteritems()])

        datasets_with_title = []
        for dataset in datasets:
            package_dict = toolkit.get_action('package_show')({}, {'id': dataset[0]})
            dataset = (dataset[1], package_dict.get('title_translated')['fi'], dataset[3], dataset[4], dataset[5],
                       dataset[6], dataset[7], dataset[8])
            datasets_with_title.append(dataset)

        return {'table': datasets_with_title, 'columns': columns,
                'quarters': quarters_iso}
    else:
        # index
        periods = get_quarter_dates_merged(now)

        stats_by_org = []
        totals = collections.defaultdict(int)
        import ckan.model as model
        all_orgs = model.Session.query(model.Group). \
            filter(model.Group.type=='organization'). \
            filter(model.Group.state=='active').order_by('name'). \
            all()
        for organization in add_progress_bar(all_orgs):
            created, modified = _get_activity(
                organization.name, include_sub_organizations, periods)
            created_names = [dataset[1] for dataset in created.values()[0]]
            modified_names = [dataset[1] for dataset in modified.values()[0]]
            num_created = len(created_names)
            num_modified = len(modified_names)
            num_total = len(set(created_names) | set(modified_names))
            stats_by_org.append(OrderedDict((
                ('organization name', organization.name),
                ('organization title', organization.title),
                ('num created', num_created),
                ('num modified', num_modified),
                ('total', num_total),
            )))
            if not include_sub_organizations:
                totals['num created'] += num_created
                totals['num modified'] += num_modified
                totals['total'] += num_total

        period_iso = [date_.isoformat()
                      for date_ in periods.values()[0]]

        stats_by_org.sort(key=lambda x: -x['total'])

        return {'table': stats_by_org,
                'totals': totals,
                'period': period_iso}

def get_quarter_dates(datetime_now):
    '''Returns the dates for this (current) quarter and last quarter. Uses
    calendar year, so 1 Jan to 31 Mar etc.'''
    now = datetime_now
    month_this_q_started = (now.month - 1) // 3 * 3 + 1
    this_q_started = datetime.datetime(now.year, month_this_q_started, 1)
    this_q_ended = datetime.datetime(now.year, now.month, now.day)
    last_q_started = datetime.datetime(
        this_q_started.year + (this_q_started.month-3)/12,
        (this_q_started.month-4) % 12 + 1,
        1)
    last_q_ended = this_q_started - datetime.timedelta(days=1)
    return {'this': (this_q_started, this_q_ended),
            'last': (last_q_started, last_q_ended)}


def get_quarter_dates_merged(datetime_now):
    '''Returns the dates for the period including this (current) quarter and
    the last quarter. Uses calendar year, so 1 Jan to 31 Mar etc.'''
    now = datetime_now
    month_this_q_started = (now.month - 1) // 3 * 3 + 1
    this_q_started = datetime.datetime(now.year, month_this_q_started, 1)
    this_q_ended = datetime.datetime(now.year, now.month, now.day)
    last_q_started = datetime.datetime(
        this_q_started.year + (this_q_started.month-3)/12,
        (this_q_started.month-4) % 12 + 1,
        1)
    last_q_ended = this_q_started - datetime.timedelta(days=1)
    return {'this_and_last': (last_q_started, this_q_ended)}

def _get_activity(organization_name, include_sub_organizations, periods):
    import ckan.model as model
    from paste.deploy.converters import asbool

    created = dict((period_name, []) for period_name in periods)
    modified = dict((period_name, []) for period_name in periods)

    # These are the authors whose revisions we ignore, as they are trivial
    # changes. NB we do want to know about revisions by:
    # * harvest (harvested metadata)
    # * dgu (NS Stat Hub imports)
    # * Fix national indicators
    system_authors = ('autotheme', 'co-prod3.dh.bytemark.co.uk',
                      'Date format tidier', 'current_revision_fixer',
                      'current_revision_fixer2', 'fix_contact_details.py',
                      'Repoint 410 Gone to webarchive url',
                      'Fix duplicate resources',
                      'fix_secondary_theme.py',
                      )
    system_author_template = 'script%'  # "%" is a wildcard

    if organization_name:
        organization = model.Group.by_name(organization_name)
        if not organization:
            raise p.toolkit.ObjectNotFound()

    if not organization_name:
        pkgs = model.Session.query(model.Package) \
            .all()
    else:
        pkgs = model.Session.query(model.Package)
        pkgs = lib.filter_by_organizations(pkgs, organization,
                                           include_sub_organizations).all()

    for pkg in pkgs:
        created_ = model.Session.query(model.PackageRevision) \
            .filter(model.PackageRevision.id == pkg.id) \
            .order_by("revision_timestamp asc").first()

        pr_q = model.Session.query(model.PackageRevision, model.Revision) \
            .filter(model.PackageRevision.id == pkg.id) \
            .filter_by(state='active') \
            .join(model.Revision) \
            .filter(~model.Revision.author.in_(system_authors)) \
            .filter(~model.Revision.author.like(system_author_template))
        rr_q = model.Session.query(model.Package, model.ResourceRevision, model.Revision) \
            .filter(model.Package.id == pkg.id) \
            .filter_by(state='active') \
            .join(model.ResourceRevision,
                  model.Package.id == model.ResourceRevision.package_id) \
            .join(model.Revision) \
            .filter(~model.Revision.author.in_(system_authors)) \
            .filter(~model.Revision.author.like(system_author_template))
        pe_q = model.Session.query(model.Package, model.PackageExtraRevision, model.Revision) \
            .filter(model.Package.id == pkg.id) \
            .filter_by(state='active') \
            .join(model.PackageExtraRevision,
                  model.Package.id == model.PackageExtraRevision.package_id) \
            .join(model.Revision) \
            .filter(~model.Revision.author.in_(system_authors)) \
            .filter(~model.Revision.author.like(system_author_template))

        for period_name in periods:
            period = periods[period_name]
            # created
            if period[0] < created_.revision_timestamp < period[1]:
                published = not asbool(pkg.extras.get('unpublished'))
                created[period_name].append(
                    (created_.id, created_.name, created_.title, lib.dataset_notes(pkg),
                     'created', period_name,
                     created_.revision_timestamp.isoformat(),
                     created_.revision.author, published))

            # modified
            # exclude the creation revision
            period_start = max(period[0], created_.revision_timestamp)
            prs = pr_q.filter(model.PackageRevision.revision_timestamp > period_start) \
                .filter(model.PackageRevision.revision_timestamp < period[1])
            rrs = rr_q.filter(model.ResourceRevision.revision_timestamp > period_start) \
                .filter(model.ResourceRevision.revision_timestamp < period[1])
            pes = pe_q.filter(model.PackageExtraRevision.revision_timestamp > period_start) \
                .filter(model.PackageExtraRevision.revision_timestamp < period[1])
            authors = ' '.join(set([r[1].author for r in prs] +
                                   [r[2].author for r in rrs] +
                                   [r[2].author for r in pes]))
            dates = set([r[1].timestamp.date() for r in prs] +
                        [r[2].timestamp.date() for r in rrs] +
                        [r[2].timestamp.date() for r in pes])
            dates_formatted = ' '.join([date.isoformat()
                                        for date in sorted(dates)])
            if authors:
                published = not asbool(pkg.extras.get('unpublished'))
                modified[period_name].append(
                    (pkg.id, pkg.name, pkg.title, lib.dataset_notes(pkg),
                     'modified', period_name,
                     dates_formatted, authors, published))
    return created, modified

def add_progress_bar(iterable, caption=None):
    try:
        # Add a progress bar, if it is installed
        import progressbar
        bar = progressbar.ProgressBar(widgets=[
            (caption + ' ') if caption else '',
            progressbar.Percentage(), ' ',
            progressbar.Bar(), ' ', progressbar.ETA()])
        return bar(iterable)
    except ImportError:
        return iterable

def publisher_activity_combinations():
    for org in lib.all_organizations(include_none=True):
        for include_sub_organizations in (False, True):
            yield {'organization': org,
                   'include_sub_organizations': include_sub_organizations}

publisher_activity_report_info = {
    'name': 'publisher-activity',
    'title': 'Publisher activity',
    'description': 'A quarterly list of datasets created and edited by a publisher.',
    'option_defaults': OrderedDict((('organization', None),
                                    ('include_sub_organizations', False),
                                    )),
    'option_combinations': publisher_activity_combinations,
    'generate': publisher_activity,
    'template': 'report/publisher_activity.html',
}