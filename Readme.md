# Symfony MMO
![Schermata del 2025-03-17 18-30-22](https://github.com/user-attachments/assets/7a9e7420-128a-4869-93c8-f298ba6829bc)

Please, if you like this project, follow me on Patreon: your follow will be a motivation for me 😃
Link: [Click here to follow](https://patreon.com/user?u=99509619)

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
    composer install
    docker-compose up -d
    bin/console doctrine:database:create
    bin/console doctrine:migrations:migrate
    bin/console doctrine:fixtures:load
    ```
3. launch app with `symfony server:start -d`
4. connect to http://localhost:8000 (or create your own proxy domain with Symfony Web Server)
5. login with user `dev@dev.org` and password `devpassword`