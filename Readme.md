# Symfony MMO

## How to install and run (Linux)

Requirements:

- docker engine installed
- Symfony Web Server installed

Then:

1. if building a development environment, create new file **compose.override.yaml** in the root dir and paste
    ```
    services:
        mercure:
            command: /usr/bin/caddy run --config /etc/caddy/dev.Caddyfile
    ```

2. run

    ```
    git clone git@github.com:Gabs496/symfony-mmo.git
    cd symfony-mmo
    symfony server:start -d
    bin/console doctrine:database:create
    bin/console doctrine:migrations:migrate
    ```
    IMPORTANT: the command `symfony server:start -d` execute at the same time:
    - `sudo docker-compose up -d`
    - a few workers  
   For more information, read the `symfony.local.yaml` file
3.connect to http://localhost:8000 (or create your own proxy domain with Symfony Web Server)
   

After you need to manually create (this will be made automatically in future):

- a record into security_user table
- a new record into data_item_bag
- a new record into data_player_character
