# REON

REON is a service replacement for Nintendo's [Mobile Adapter GB](https://bulbapedia.bulbagarden.net/wiki/Mobile_Game_Boy_Adapter) service for the Game Boy Color and Advance. This service aims to target all officially released games, as well as potentially allow homebrew games to use the service.

# Services

This repository holds various folders for the service, and each has its own README.md which should be checked out.

- `mail/` holds the Node.js mail server by @Matze167435
- `app/` holds various non-web applications, such as the Pokémon Trade Corner program by @thomasnet-mc
- `html/` holds the web-facing section of the service; this includes scripts for various games.
- `dns/` holds a forked copy of [Sylverant's PSO DNS application](https://github.com/Sylverant/pso_dns) with a path modification.

An important note here is that this is **the service** repository: web scripts for other things such as account management are not in this repository.

# Server Setup

1. Install Apache2, PHP, MySQL
2. Continue these steps later once the production server is setup.


# Licenses

- The `dns` folder holds a modified copy of Sylverant's PSO DNS code; this code is licenced under [the BSD-2 "Simplified" License](https://github.com/Sylverant/pso_dns/blob/master/COPYING)

[Clear up what license we'd like to use!]