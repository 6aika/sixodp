from ckanext.showcase.controller import ShowcaseController

import logging

log = logging.getLogger(__name__)

class Sixodp_ShowcaseController(ShowcaseController):

    def new(self, data=None, errors=None, error_summary=None):
        log.info("In sixodp showcase controller")
        return super(Sixodp_ShowcaseController, self).new(data=data, errors=errors,
                                                          error_summary=error_summary)
