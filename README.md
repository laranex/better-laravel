# Better Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laranex/better-laravel.svg?style=flat-square)](https://packagist.org/packages/laranex/better-laravel)
[![Tests](https://github.com/laranex/better-laravel/actions/workflows/tests.yml/badge.svg)](https://github.com/laranex/better-laravel/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/laranex/better-laravel.svg?style=flat-square)](https://packagist.org/packages/laranex/better-laravel)
[![License](https://img.shields.io/packagist/l/laranex/better-laravel.svg?style=flat-square)](LICENSE.md)

Modular architecture for Laravel: modules for controllers, features and operations, and domains for jobs, with generators for each. Built for humans and AI agents.

As a Laravel app grows, controllers tend to collect validation, queries, mail and queue calls in one place. Better Laravel splits every request into small units with one responsibility each: a controller serves a feature, the feature runs jobs and operations, and jobs do the work. Controllers, features and operations live in modules; jobs and requests live in domains that every module shares.

## Why Better Laravel

- **One class, one responsibility.** Controllers, features, operations, requests and jobs each do one thing, so code is easy to find, read and test.
- **Reuse without duplication.** Jobs and requests in `app/Domains` can be used from any module, and an operation groups jobs that several features run.
- **A generator for every unit.** `better:route`, `better:controller`, `better:feature`, `better:request`, `better:operation` and `better:job`, with stubs you can publish and change.
- **Safe generators.** Every name is checked before a file is written, so a name can never inject code or write outside its directory.
- **Route files that load themselves.** Every file under `routes/web` and `routes/api` is loaded with its middleware group and prefix, and nothing is scanned once routes are cached.
- **Queues built in.** Extend `QueueableJob` and call `runInQueue()`, on the default queue or a named one.
- **Plain Laravel underneath.** Units are dispatched through Laravel's bus and requests are form requests, so there is nothing new to learn.

Prefer to keep jobs and requests inside each module? See [Next Laravel](https://laranex.vercel.app/next-laravel/introduction).

## Installation

Requires PHP 8.1+ and Laravel 10 (10.48.29 or later), 11, 12 or 13.

```bash
composer require laranex/better-laravel
```

## Documentation

The full documentation lives at **[laranex.vercel.app/better-laravel](https://laranex.vercel.app/better-laravel)**:

- [Better Laravel introduction](https://laranex.vercel.app/better-laravel/introduction): why use it and what it does
- [Better Laravel principles](https://laranex.vercel.app/better-laravel/principles): the request lifecycle and the responsibility of each unit
- [Installing Better Laravel](https://laranex.vercel.app/better-laravel/installation): Composer, the generator commands, naming rules and the facade
- [Better Laravel AI agent skill](https://laranex.vercel.app/better-laravel/ai-agent-skill): the skill for Claude Code, Codex, Cursor and other coding agents
- [Better Laravel configuration](https://laranex.vercel.app/better-laravel/configuration): route loading, route prefixes, views and stubs
- [Upgrading Better Laravel](https://laranex.vercel.app/better-laravel/upgrading): from v2 to v4
- [Generating routes](https://laranex.vercel.app/better-laravel/usage/route): route files loaded from `routes/web` and `routes/api`
- [Generating controllers](https://laranex.vercel.app/better-laravel/usage/controller): controllers that serve features
- [Generating features](https://laranex.vercel.app/better-laravel/usage/feature): features that validate requests and run jobs and operations
- [Generating requests](https://laranex.vercel.app/better-laravel/usage/request): form requests that validate and authorize
- [Generating operations](https://laranex.vercel.app/better-laravel/usage/operation): reusable groups of jobs
- [Generating jobs](https://laranex.vercel.app/better-laravel/usage/job): synchronous and queueable jobs

## License

MIT. See [LICENSE.md](LICENSE.md).
