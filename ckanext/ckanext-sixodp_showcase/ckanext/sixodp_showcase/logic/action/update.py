import logging

import ckan.lib.uploader as uploader
import ckan.plugins.toolkit as toolkit


log = logging.getLogger(__name__)


def showcase_update(context, data_dict):

    # If get_uploader is available (introduced for IUploader in CKAN 2.5), use
    # it, otherwise use the default uploader.
    # https://github.com/ckan/ckan/pull/2510
    try:
        upload = uploader.get_uploader('showcase', data_dict['icon'])
    except AttributeError:
        upload = uploader.Upload('showcase', data_dict['icon'])

    log.debug(data_dict)
    upload.update_data_dict(data_dict, 'iconl',
                            'icon_upload', 'clear_icon_upload')
    log.debug(data_dict)
    upload.upload(uploader.get_max_image_size())

    pkg = toolkit.get_action('package_update')(context, data_dict)

    return pkg
