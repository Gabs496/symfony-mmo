# ⚔️ Symfony MMO: Build Your Legend in a Player-Driven Persistent World

Dive into **Symfony MMO**, an ambitious open-source video game currently in development that masterfully blends the Massively Multiplayer Online genre with deep management elements. Using **PHP and Symfony**, we're creating a dynamic universe where player specialization and interaction are at the heart of the experience.

In Symfony MMO, your progression is tied to your ability to master various skills. Choose your path:

-   **Combat:** Hone your techniques and become a formidable warrior.
-   **Resource Economy:** Dedicate yourself to **Gathering** precious lumber, ores, fibers, and metals.
-   **Sustenance:** Cultivate fields through **Farming** or tend to livestock with **Animal Husbandry**.
-   **Craftsmanship:** Become a master in **Cooking**, **Potion/Drug Preparation**, or **Crafting** essential weapons, armor, and tools.
-   **Maintenance:** Ensure the longevity of items through **Equipment Repair** and **Material Recycling** from broken objects.

A crucial aspect of the game is its player-driven economy: **all weapons are crafted and sold by players themselves** in various local markets. Weapon wear adds a strategic layer, making repair a costly but essential choice for rarer equipment.

Explore a vast and varied world, with **distinct cities and locations**, each characterized by unique resources, specific mobs, interactive NPCs, and dynamic markets that reflect the local economy.

**Symfony MMO is more than just a game; it's a collaborative project.** Being open-source, we aim to create an environment where anyone can contribute to the evolution of mechanics, the enrichment of the story, and the expansion of content. If you're a developer, a designer, a storyteller, or simply an enthusiast with ideas, join us on this journey and help forge the future of Symfony MMO!
![Schermata del 2025-03-17 18-30-22](https://github.com/user-attachments/assets/7a9e7420-128a-4869-93c8-f298ba6829bc)

Please, if you like this project, follow me on Patreon: your follow will be a motivation for me 😃
Link: [Click here to follow](https://patreon.com/user?u=99509619)

## 🚶 Roadmap
Link to the discussion: [https://github.com/Gabs496/symfony-mmo/discussions/6#discussion-8411611](https://github.com/Gabs496/symfony-mmo/discussions/6#discussion-8411611)

## 🟢 Live demo
You can try the game in a live demo at ---> [http://37.114.41.235](http://37.114.41.235)  
Username: `dev@dev.org`  
Password: `devpassword`

## How to install and run locally (Linux)

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
