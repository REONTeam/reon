# Database Migrations

This directory contains database migrations managed by [Phinx](https://phinx.org/).

## Setup

1. **Install dependencies** (if not already done):
   ```bash
   cd web
   composer install
   ```

2. **Ensure config.json exists**:
   ```bash
   # From the project root
   cp config.example.json config.json
   # Edit config.json with your database credentials
   ```

## Usage

All Phinx commands should be run from the **project root** directory (not the `web` directory).

### Check Migration Status

```bash
./web/vendor/bin/phinx status
```

This shows which migrations have been run and which are pending.

### Run Migrations

To apply all pending migrations:

```bash
./web/vendor/bin/phinx migrate
```

### Rollback Migrations

To rollback the last migration:

```bash
./web/vendor/bin/phinx rollback
```

To rollback to a specific version:

```bash
./web/vendor/bin/phinx rollback -t 20250101000000
```

### Create a New Migration

```bash
./web/vendor/bin/phinx create MyNewMigration
```

This creates a new migration file in `db/migrations/` with a timestamp prefix.

## Initial Migration

The initial migration (`20250101000000_initial_schema.php`) represents the baseline schema from commit `0d308b92127df05420a1417eb7c635ead8348b9a`. This is the schema currently deployed on the production server.

**Important**: If you already have the database tables created via `tables.sql`, you should mark the initial migration as complete without running it:

```bash
./web/vendor/bin/phinx migrate -t 20250101000000 --fake
```

The `--fake` flag records the migration as run without actually executing it.

## Migration Best Practices

1. **Always test migrations** on a development database before running on production
2. **Write both up and down migrations** to ensure rollbacks work correctly
3. **Never edit a migration** after it has been run on production
4. **Use descriptive names** for migrations that explain what they do
5. **Keep migrations focused** - one migration should do one logical thing
6. **Commit migrations to git** so all team members stay in sync

## Directory Structure

```
db/
├── migrations/       # Migration files (versioned, committed to git)
│   └── 20250101000000_initial_schema.php
├── seeds/           # Seed files for test data (optional)
└── README.md        # This file
```

## Configuration

The Phinx configuration is in `phinx.php` at the project root. It automatically reads database credentials from `config.json`.

## Troubleshooting

### "config.json not found" error
Make sure you're running commands from the project root and that `config.json` exists.

### "Connection refused" error
Check that your MySQL database is running and the credentials in `config.json` are correct.

### Migration table
Phinx creates a `phinxlog` table in your database to track which migrations have been run. Don't modify this table manually.

