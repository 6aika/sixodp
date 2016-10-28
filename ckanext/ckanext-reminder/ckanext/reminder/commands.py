from ckan.lib.cli import CkanCommand
import plugin

class ReminderCommand(CkanCommand):
    '''
    Send notification emails of datasets which have a reminder date set

    Usage:

        paster reminder send
            - Sends emails of all the datasets which have reminder set to current date
    '''

    summary = __doc__.split('\n')[0]
    usage = __doc__
    min_args = 0
    max_args = 1

    def __init__(self, name):
        super(ReminderCommand, self).__init__(name)

    def command(self):
        """
        Parse command line arguments and call appropriate method.
        """
        if not self.args or self.args[0] in ['--help', '-h', 'help']:
            print Report.__doc__
            return

        cmd = self.args[0]
        self._load_config()

        if cmd == 'send':
            self.send()
        else:
            self.log.error('Command "%s" not recognized' % (cmd,))

    def send(self):
        reminders = plugin.ReminderPlugin()
        reminders.send_reminders()