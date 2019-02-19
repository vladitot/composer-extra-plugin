## What is composer-extra-plugin?

It is custom plugin, which can get settings from json files, included via composer.json. Plugin correctly works with **wikimedia/composer-merge-plugin**. You can storage your settings in included non-tracked file, for example.

In additional, composer-extra-plugin can be used for automatisation long commads, like **docker-compose**.

## How to install

    composer install vladitot/composer-extra-plugin
    
### If you just deployed project, which contains this plugin for managing docker, and cant start it without plugin, it is recommended to do:
    composer install --ignore-platofrm-reqs
    
        instead of
        
    composer install

Plugin will be installed and then you can use it correctly.

## How to use?

#### You can store settings in your composer.json file.

Like this:

     "extra": {
        "XDEBUG_CONFIG": {
          "remote_host": "192.168.1.47",
          "remote_port": "9001",
          "remote_mode": "req",
          "remote_handler": "dbgp",
          "idekey": ""
        },
        "sshkey": {
          "sshKeyPath": "/home/username/.ssh/id_rsa"
        }
    }

Then you can get params like this:
    
    composer extra-get "sshkey-sshKeyPath"
    composer extra-get "sshkey"
    

####What about commands? Use this

    "extra": {
        "sshkey": {
          "sshKeyPath": "/home/username/.ssh/id_rsa"
        },
        "extracommands": {
          "@7%": "cd laradock",
          "@8%": "cd ../",
          "@9%": "echo Building... && docker-compose build workspace php-fpm nginx laravel-horizon",
    
          "up":  "@7% && @9% && docker-compose up -d workspace laravel-horizon nginx redis memcached portainer redis-commander selenoid selenoid-ui && @8%",
          "restart-all":  "@7% && docker-compose restart workspace nginx redis memcached portainer laravel-horizon selenoid selenoid-ui redis-commander && @8%",
          "restart":  "@7% && docker-compose restart @params% && @8%",
          }
       }
       
Then you can use it like this:

    composer runt up
   
    or
    
    composer runt down 
    
on your host machine, and it will run command, as alias.

#### P.S. Getting variables from your PHP application

In additional, you can get settings from json inside your application.
Use class **\ExtraPlugin\StaticHelper** for this