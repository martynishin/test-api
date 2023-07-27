## The Project built on Laravel, PostgreSQL and Docker
Below you will find all the necessary instructions.

## The first environment setup (macOS)

#### 1. Since it's a test project, just create .env file from .env.example including DB config
`cp .env.example .env`

#### 2. Install Composer dependencies
This command uses a small Docker container containing PHP and Composer to install the application's dependencies:
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

#### 3. Build and run a Docker environment
`./vendor/bin/sail up -d`

#### 4. Run migrations and seeders
`./vendor/bin/sail artisan migrate:fresh --seed`

<br>

#### You can check my own code in the following files

```
app/Http/Controllers/EventController.php
app/Http/Controllers/TokenController.php

app/Models/*.php

app/Rules/ExceedDate.php

app/Services/TokenService.php

database/factories/*.php
database/migrations/*.php
database/seeders/DatabaseSeeder.php

tests/Feature/Controllers/*.php
tests/Feature/Services/*.php
```

<br>

## Testing

#### 1. Manually

If you want to try it manually, feel free to do it via `Postman`.

All necessary endpoints you can find in `routes/api.php`

#### To get Bearer Token just call this endpoint:

`POST /api/tokens`

Use any email from `Database/Seeders/DatabaseSeeder.php`, e.g. `sony@example.com`

Password for all organizations is `password`

Request payload example:
```
{
    "email": "sony@example.com",
    "password": "password"
}
```

<br>

#### 2. Automated
Just execute this command in your terminal:

`./vendor/bin/sail artisan test`

If you want to execute a specific test case:

`./vendor/bin/sail artisan test --filter test_it_can_update_one_column`

Testing Database config you can find in `.env.testing`
