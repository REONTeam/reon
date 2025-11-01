# REON

REON is a service replacement for Nintendo's [Mobile Adapter GB](https://bulbapedia.bulbagarden.net/wiki/Mobile_Game_Boy_Adapter) service for the Game Boy Color and Advance. This service aims to target all officially released games, as well as potentially allow homebrew games to use the service.

# Services

This repository holds various folders for the service, and each has its own README.md which should be checked out.

- `mail/` holds the Node.js mail server by [Matze](https://github.com/Sudel-Matze)
- `app/` holds various non-web applications, such as the Pokémon Trade Corner program by [thomasnet](https://github.com/thomasnet-mc)
- `web/` holds the web-facing section of the service; this includes scripts for various games.

# Server Setup

1. Install Apache2, PHP, MySQL
2. Place the files in Apache2's (or the vhost's if used) website root folder
3. Run tables.sql in MySQL
4. Continue these steps later once the production server is setup.

# Docker Setup

A `docker-compose.yml` is provided which will run all the required services. To get started, be sure to have docker available on your system.

1. Copy `config.example.json` to `config.json`
2. Copy `example.env` to `.env` (note the `.` at the start of the file name!)
3. Update the values in both files as needed, ensuring that the mysql options match
   - The included compose file exposes the MySQL port, so make sure you choose secure passwords
   - Do NOT change the `mysql_host` option in `config.json` unless you plan to use an external MySQL database
   - MYSQL_ROOT_PASSWORD is only needed for the `db` container and is not used by the other services
4. Run `docker compose up -d` to start the services
5. Run `docker compose exec -w /var/www/reon/web/scripts web php add_user.php` to create an account without having to configure email delivery

Your REON server should now be accessible at http://localhost/.

# License

This code is licenced under MIT.
