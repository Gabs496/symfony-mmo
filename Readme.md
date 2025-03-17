# Symfony MMO

## How to install and run (Linux)

Requirements:

- docker engine installed
- symfony-cli installed

Then:

1. if creating a development environment, create new file **compose.override.yaml** in the root dir and paste
    ```
    services:
        mercure:
            command: /usr/bin/caddy run --config /etc/caddy/dev.Caddyfile
    ```

2. run

    ```
    git clone git@github.com:Gabs496/symfony-mmo.git
    cd symfony-mmo
    docker compose up -d
    bin/console doctrine:database:create
    bin/console doctrine:migrations:migrate
    bin/console messenger:consume async scheduler_game_task
    ```

After you need to manually create (this will be made automatically in future):

- a record into security_user table
- a new record into data_item_bag
- a new record into data_player_character
