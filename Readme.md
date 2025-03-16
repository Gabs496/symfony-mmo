# Symfony MMO
## How to install and run (Linux)


Requirements:
- docker engine installed
- symfony-cli installed

Run:

    git clone git@github.com:Gabs496/symfony-mmo.git
    cd symfony-mmo
    docker compose up
    bin/console d:d:c
    bin/console d:m:m

Install Mercure and then run:

    bin/console messenger:consume async scheduler_game_task

After you need to manually create (this will be made automatically in future):

- a record into security_user tabel
- a new record into data_item_bag
- a new record into data_player_character
 

