# RealWorld API – Laravel

Backend implementation of the [RealWorld](https://realworld-docs.netlify.app/docs/specs/backend/endpoints) specification built with Laravel 12 and JWT authentication.

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ (for Newman / optional asset tooling)
- SQLite (default) or PostgreSQL 14+

## Quick Start

```bash
make install        # install dependencies, copy .env, generate key, migrate & seed
make serve          # serve API at http://127.0.0.1:8001
```

The API exposes the standard RealWorld endpoints under `/api/*`. Authentication uses `Authorization: Token <jwt>` headers.

## Common Tasks

| Command            | Description |
|--------------------|-------------|
| `make migrate`     | Run outstanding database migrations |
| `make seed`        | Seed the reference RealWorld dataset |
| `make test`        | Execute the PHPUnit feature suite |
| `make cs`          | Run Laravel Pint (PSR-12 styling) |
| `make stan`        | Run PHPStan (level 8) |
| `make postman`     | Boot API locally and run the official Newman collection |

Corresponding Composer scripts are available (`composer app:test`, `composer app:stan`, etc.) for CI environments.

## Database

SQLite is the default (`DB_CONNECTION=sqlite`). To use PostgreSQL, update `.env`:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=realworld
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

Then recreate the schema:

```bash
php artisan migrate:fresh --seed
```

## Seeding

`php artisan db:seed` loads a sample dataset with:

- Users: `jake`, `jane`, `john` (password: `demo1234`)
- Articles, tags, favorites, follows, and comments matching the RealWorld examples

## Quality Gates & Testing

- **Feature tests:** `make test`
- **Static analysis:** `make stan`
- **Code style:** `make cs`
- **API contract (Newman):** `make postman`

`make postman` starts a throwaway `php artisan serve` on port `8001`, waits until it is reachable, runs `npx newman` against the official Conduit collection (`tests/Postman/Conduit.postman_collection.json`), and then terminates the server.

## Error Handling & CORS

- All errors follow the RealWorld format: `{ "errors": [ { "message": string, "code": int } ] }` or validation errors grouped by field.
- CORS is configured per spec: `Access-Control-Allow-Origin: *`, allowed headers `Content-Type, Authorization`, and allowed methods `GET, POST, PUT, DELETE, OPTIONS`.

## JWT Auth

JWTs are issued via `/api/users/login` and `/api/users`. Tokens must be supplied as `Authorization: Token <jwt>`.

## Project Structure

- `app/Domain` – Application actions, repositories, DTOs, and services (organized by bounded context)
- `tests/Feature` – Full RealWorld coverage (auth, profiles, articles, comments, tags, error formatting, seeding)
- `Makefile` – Developer ergonomics & CI entrypoints

## API Reference

Consult the [RealWorld API specification](https://docs.realworld.show/specifications/backend/endpoints/) for detailed endpoint payloads. This implementation mirrors those responses (including pagination, enveloped resources, and follow/favorite flags).

## License

MIT
