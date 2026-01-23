<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
    <img src="https://github.com/jao241/taskflow-laravel/actions/workflows/ci.yml/badge.svg" alt="CI">
    <img src="https://img.shields.io/badge/API%20Docs-Scribe-blueviolet" alt="API Docs - Scribe">
    <img src="https://img.shields.io/badge/coverage-41%25-brightgreen" alt="Coverage">
    <img src="https://img.shields.io/badge/PHP-8.4-blue" alt="PHP">
    <img src="https://img.shields.io/badge/Laravel-12-red" alt="Laravel">
    <img src="https://img.shields.io/badge/docker-ready-blue" alt="Docker">
</p>

## About Taskflow Laravel

Taskflow Laravel is a task assignment API designed to coordinate and store task-related data.

It includes: 
- A token-based authentication using Laravel Sanctum;
- Endpoints to manage tasks (CRUD);
- Endpoints to manage users (CRUD);
- Login route using email and password (use the seed user credentials for first access: email->dev@email.com, password->password)
- Feature tests covering the main modules;
- API documentation generated with Scribe;

The project is covered by automated tests, includes code coverage reports, and provides a Docker-based development environment for consistency and ease of setup.

## Stack
<a href="https://laravel.com/">Laravel</a>
<a href="https://laravel.com/docs/12.x/sanctum">Sanctum</a>
<a href="https://pestphp.com">Pest</a>
<a href="https://docker.com">Docker</a>
<a href="https://github.com/knuckleswtf/scribe">Scribe</a>
<a href="https://www.postgresql.org/">PostgreSQL</a>
<a href="https://nginx.org/">Nginx</a>

## Makefile commands

Taskflow provides a Makefile with helper commands to simplify common tasks such as:

- Running the application with Docker

- Running migrations and seeders

- Rolling back migrations

- Running tests and generating coverage reports

If your Docker installation requires elevated permissions, use <b>sudo</b> before the <b>make</b> command.

Makefile command example:

`make up`

or

`sudo make up`

This command will start the Docker Compose environment.

## Running in a local environment

To run the application locally, you need:

- PostgreSQL installed and running

- A database named <b>taskflow</b>

Then run:

`php artisan migrate --seed `

Start the server:

`php artisan serve`

## API Documentation

This project uses Scribe to generate interactive API documentation.

Generate the docs:

`php artisan scribe:generate`

Access the documentation at:

`http://localhost:8000/docs`

## CI implementation

The CI pipeline is implemented using GitHub Actions and is triggered on:

- Pushes to the main branch

- Manual execution via GitHub Actions

The pipeline runs the test suite to ensure application stability and validate readiness for production deployment.

## Architecture Overview

```mermaid
flowchart TD
    Client[Client / Frontend]
    Routes[API Routes]
    Middleware[Auth Middleware Sanctum]
    Controller[Controller]
    Policy[Policy]
    Model[Model / Eloquent]
    DB[(PostgreSQL)]

    Client --> Routes
    Routes --> Middleware
    Middleware --> Controller
    Controller --> Policy
    Controller --> Model
    Model --> DB
```

## Future Improvements

The following features are planned for future iterations of the API:

- [ ] Create an organization module to support multi-tenant usage.
- [ ] Extend the tasks table with additional fields, such as `responsible_user(s)` and `observations`.
- [ ] Extend the users table with additional fields, such as `profile_image`, contact information, and access type.
- [ ] Create enums to manage available task statuses.
- [ ] Create enums to manage user access types and roles.
- [ ] Implement a password reset flow using email notifications.
- [ ] Implement file uploads using AWS services (e.g. S3).
- [ ] Add user registration and authentication via third-party providers (e.g. Google, Microsoft).
