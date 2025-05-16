# CKAN

CKAN is installed from source via ansible. Current version is 2.9.8. The installed ckan is modified with bugfixes or other needs not yet present in the current release. These are available as patch files in [https://github.com/6aika/sixodp/tree/master/ansible/roles/ckan-install-patches](https://github.com/6aika/sixodp/tree/master/ansible/roles/ckan-install-patches). When CKAN is upgraded, installation of these patches most likely will fail. The developer needs to go through them and port necessary ones to new CKAN.

## Extensions

Most of the actual customization is done via ckan extensions. Some of them are implemented specifically for 6aika data portal, some are built by other with various degrees of modifications done in 6aika data portal. Below are lists of used extensions divided by responsible party of the extension.

### Maintained by 6aika developers:

* [Sixodp](https://github.com/6aika/sixodp/tree/master/ckanext/ckanext-sixodp), Most of the custom theme, routing and schema changes.
* [Sixodp\_showcase](https://github.com/6aika/sixodp/tree/master/ckanext/ckanext-sixodp_showcase), extends showcase extension with customized meta model schema and UI.
* [Sixodp\_showcasesubmit](https://github.com/6aika/sixodp/tree/master/ckanext/ckanext-sixodp_showcasesubmit), provides anonymous submitting of showcases to 6aika showcase schema.
* [Collection](https://github.com/6aika/ckanext-collection), creates a duplicate feature of groups, so that there are similar collections. Groups are used as categories.
* [Reminder](https://github.com/6aika/ckanext-reminder), enables sending notifications from dataset updates to anonymous subscribers and reminders to dataset maintainers when they have to update their datasets.
* [Googleanalytics](https://github.com/6aika/ckanext-googleanalytics), heavily modified from original google analytics extension.
* [Datasetcopy](https://github.com/6aika/ckanext-datasetcopy), enables creating new datasets from existing ones by filling the form with existing values.
* [Statistics](https://github.com/6aika/sixodp/tree/master/ckanext/ckanext-statistics), creates visualization on the browser from the ckan api. The visualizations are implemented with d3.js and probably has scaling issues when amount of datasets or organizations grow.
* [Rating](https://github.com/6aika/ckanext-rating), enables anonymous ratings of datasets and showcases. Currently not in use as it has issues with bot crawlers.
* [Editor](https://github.com/6aika/ckanext-editor), provides user interface to modify multiple datasets at the same time with the same values.
* [Datasubmitter](https://github.com/6aika/sixodp/tree/master/ckanext/ckanext-datasubmitter), similar to showcase submit, enables submitting datasets anonymously.

### Maintained by others but might have modification in 6aika dataportal

* [Showcase](https://github.com/6aika/ckanext-showcase), provides showcasing appilications.
* [Report](https://github.com/6aika/ckanext-report), provides reporting infrastructure.
* [Geoview](https://github.com/6aika/ckanext-geoview), implements data previews for geoservers.
* [QA](https://github.com/6aika/ckanext-qa), provides quality analysis for the uploaded data.
* [Archiver](https://github.com/6aika/ckanext-archiver), required by QA, mostly used for checking data link validity.
* [Disqus](https://github.com/ckan/ckanext-disqus), implements commenting datasets via disqus.
* [Scheming](https://github.com/6aika/ckanext-scheming), enables customizing dataset schemas via json files.
* [Fluent](https://github.com/ckan/ckanext-fluent), adds multilingual capabilities to scheming.
* [Hierarchy](https://github.com/6aika/ckanext-hierarchy), adds hierarchies to organizations.
* [Cloudstorage](https://github.com/6aika/ckanext-cloudstorage), enables storing dataset data to AWS S3.
