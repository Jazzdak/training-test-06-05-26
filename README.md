# Sensio Events

A Symfony 7.4 application for managing conferences, organizations and volunteering.

## Requirements

- PHP 8.2 or higher
- [Symfony CLI](https://symfony.com/download)
- Composer (provided by the Symfony CLI)

## Installation

### 1. Create your `.env.local`

Create a `.env.local` file at the root of the project with the following content:

```dotenv
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data_%kernel.environment%.db"
#CONFERENCES_API_KEY=""
APP_MAINTENANCE=0
DEFAULT_PASSWORD="password"
```

| Variable | Default | Description |
| --- | --- | --- |
| `DATABASE_URL` | `sqlite:///%kernel.project_dir%/var/data_%kernel.environment%.db` | Doctrine connection string. Defaults to a per-environment SQLite file under `var/`. |
| `CONFERENCES_API_KEY` | *(empty / commented out)* | API key used by the external conferences search client. Leave commented to fall back to the database search. |
| `APP_MAINTENANCE` | `0` | Toggles maintenance mode. Set to `1` to enable. |
| `DEFAULT_PASSWORD` | `password` | Password assigned to fixture users. |

### 2. Run the full setup

```bash
make start
```

This runs `install`, `db` and `serve` in sequence.

## Available Make targets

| Target | Description |
| --- | --- |
| `make install` | Install Composer dependencies via the Symfony CLI. |
| `make db` | Run migrations and load fixtures into the SQLite database. Requires `.env.local`. |
| `make serve` | Start the Symfony dev server. |
| `make start` | Run `install`, `db` and `serve` in sequence. |
| `make help` | List all available targets. |

## Fixture users

Loading the fixtures creates the following accounts (password: value of `DEFAULT_PASSWORD`):

- `user@sensioevents.com` — `ROLE_USER`
- `website@sensioevents.com` — `ROLE_WEBSITE`
- `organizer@sensioevents.com` — `ROLE_ORGANIZER`
- `admin@sensioevents.com` — `ROLE_ADMIN`
