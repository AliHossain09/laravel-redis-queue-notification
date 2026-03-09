# <p align="center">Laravel Redis Queue Notification</p>

A practical Laravel 12 project that demonstrates how Redis can be used across queue processing, real-time notifications, publish/subscribe messaging, caching, sessions, and scheduling inside a single dashboard-driven application.

This repository is built as a hands-on backend systems demo. Instead of showing only CRUD, it combines asynchronous jobs, websocket broadcasting, Redis Pub/Sub, and scheduler integration in a way that is easy to review from both the code and the UI.

## GitHub Repo Description

Use this short description for the repository:

`Laravel 12 demo using Redis Queue, Pub/Sub, Cache, Session, Scheduler, Reverb, and Echo for real-time notifications.`

## Overview

The application allows a user to:

- create, edit, and delete posts
- dispatch a Redis queue job after creating a post
- generate real-time notifications through Laravel Reverb and Echo
- publish messages to a Redis Pub/Sub channel
- subscribe to Redis messages through a custom Artisan command
- view recent Pub/Sub messages from the dashboard
- edit and delete cached Pub/Sub message entries
- run a scheduled daily report command

## What This Project Demonstrates

- Laravel queue jobs with Redis
- Redis-backed cache and session drivers
- event broadcasting with Laravel Reverb
- frontend live updates with Laravel Echo
- custom Redis Pub/Sub subscriber command
- task scheduling with Laravel Scheduler
- Blade-based dashboard with interactive management features
- graceful fallback when websocket broadcasting is unavailable

## Tech Stack

- Laravel 12
- PHP 8.2
- MySQL
- Redis
- Predis
- Laravel Reverb
- Laravel Echo
- Blade
- Tailwind CSS
- Vite
- Vanilla JavaScript

## Implemented Features

### 1. Post Management

- create posts from the dashboard
- update existing posts inline
- delete posts with confirmation
- automatically clear cached post data when posts change

### 2. Queue-Based Notification Processing

- creating a post dispatches `SendPostNotification`
- notification creation runs through Redis queue workers
- notification history appears in the dashboard bell
- notification count updates through real-time events and polling fallback

### 3. Real-Time Notification Bell

- recent notification list is shown in a bell dropdown
- notification count is synced with the latest valid posts
- Laravel Echo listens for broadcast events from Reverb
- polling fallback keeps the UI updated if websocket delivery is delayed

### 4. Redis Pub/Sub Demo

- messages can be published from the dashboard
- a custom subscriber command listens to a Redis channel
- received Pub/Sub messages are stored in cache for dashboard display
- cached Pub/Sub messages can be edited or deleted from the UI

### 5. Scheduler Demo

- a custom command is scheduled in `routes/console.php`
- the command demonstrates periodic reporting through Laravel Scheduler

## Project Flow

### Post Notification Flow

1. A post is created from the dashboard.
2. Laravel stores it in MySQL.
3. A Redis queue job is dispatched.
4. The job creates a notification log entry.
5. A broadcast event is sent through Reverb.
6. The frontend updates the notification bell.

### Redis Pub/Sub Flow

1. A message is published from the dashboard form.
2. Redis receives the published message on a channel.
3. The subscriber command listens to that channel.
4. The message is cached for dashboard rendering.
5. A real-time event broadcasts the received payload to the browser.

## Important Files

### Backend

- [`app/Http/Controllers/PostController.php`](app/Http/Controllers/PostController.php)
- [`app/Jobs/SendPostNotification.php`](app/Jobs/SendPostNotification.php)
- [`app/Console/Commands/ListenRedisChannel.php`](app/Console/Commands/ListenRedisChannel.php)
- [`app/Console/Commands/DailyReport.php`](app/Console/Commands/DailyReport.php)
- [`app/Events/NotificationSent.php`](app/Events/NotificationSent.php)
- [`app/Events/RedisMessageReceived.php`](app/Events/RedisMessageReceived.php)

### Frontend

- [`resources/views/posts/index.blade.php`](resources/views/posts/index.blade.php)
- [`resources/js/bootstrap.js`](resources/js/bootstrap.js)
- [`resources/js/app.js`](resources/js/app.js)

### Configuration

- [`config/queue.php`](config/queue.php)
- [`config/database.php`](config/database.php)
- [`config/broadcasting.php`](config/broadcasting.php)
- [`config/reverb.php`](config/reverb.php)
- [`routes/web.php`](routes/web.php)
- [`routes/console.php`](routes/console.php)

## Setup Instructions

### 1. Clone the project

```bash
git clone https://github.com/AliHossain09/laravel-redis-queue-notification.git
cd laravel-redis-queue-notification
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` file with your own:

- MySQL database credentials
- Redis host and port
- local app URL if needed

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Build frontend assets

```bash
npm run build
```

For development:

```bash
npm run dev
```

## How to Run the Full Project

Open separate terminals and run:

### Laravel server

```bash
php artisan serve
```

### Queue worker

```bash
php artisan queue:work
```

### Reverb websocket server

```bash
php artisan reverb:start
```

### Redis Pub/Sub subscriber

```bash
php artisan app:redis-subscribe demo-channel
```

### Scheduler

```bash
php artisan schedule:run
```

Or continuously for local testing:

```bash
php artisan schedule:work
```

## Demo Commands

```bash
php artisan app:daily-report
php artisan app:redis-subscribe demo-channel
php artisan queue:work
php artisan reverb:start
php artisan schedule:list
```

## Screenshots

### Current Dashboard

The dashboard currently includes:

- notification bell with live count
- post create/edit/delete section
- Redis Pub/Sub publish form
- subscriber feed with edit/delete actions

## Notes and Limitations

- Redis must be running before queue, session, cache, and Pub/Sub features will work.
- Reverb must be running for live websocket notifications.
- If Redis returns an RDB `MISCONF` error, Redis server persistence needs to be fixed or restarted before queue writes will succeed.
- Laravel Horizon is not included in this Windows/XAMPP environment because Horizon requires `pcntl` and `posix`, which are not available in a standard Windows PHP setup.

## Why This Project Is Valuable for Recruiters

This project shows practical understanding of:

- asynchronous processing
- Redis integration beyond simple caching
- real-time event delivery
- scheduler-based background automation
- backend and frontend coordination in Laravel
- building developer-friendly demos around actual infrastructure concepts

## Suggested GitHub Topics

You can add these topics on GitHub:

- `laravel`
- `php`
- `redis`
- `queue`
- `pubsub`
- `reverb`
- `laravel-echo`
- `blade`
- `mysql`
- `vite`

## License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
