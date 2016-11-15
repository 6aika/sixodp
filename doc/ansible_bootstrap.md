
To deploy the server, you need a linux user that can ssh in with a key and can sudo without password prompt:

    nano /home/youruser/.ssh/authorized_keys

    sudo visudo
    youruser ALL=(ALL) NOPASSWD: ALL
