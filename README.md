# Laravel Redis Queue Notification

A practical **Laravel 12 demo project** showing how Redis can power multiple backend systems including **queue processing, real-time notifications, Pub/Sub messaging, caching, sessions, and task scheduling** — all inside a single dashboard application.

## Dashboard Preview
 ![Dashboard](https://github.com/AliHossain09/laravel-redis-queue-notification/blob/main/laravel-redis-queue-notification.png?raw=true)

This project focuses on **real backend infrastructure concepts**, combining asynchronous jobs, websocket broadcasting, Redis Pub/Sub, and Laravel Scheduler in a way that is easy to review from both the **codebase and UI**.

---

## Repository Description

Laravel 12 demo using Redis Queue, Pub/Sub, Cache, Session, Scheduler, Reverb, and Echo for real-time notifications.

---

## Project Overview

The application demonstrates how Redis can integrate with Laravel to power several backend features:

- Post creation, editing, and deletion
- Queue-based notification processing
- Real-time notification updates
- Redis Pub/Sub messaging
- Cached message storage
- Scheduler-based background tasks
- Interactive dashboard for monitoring events

---

## Key Features

### Post Management
- Create, update, and delete posts
- Automatic cache clearing when posts change
- Inline dashboard editing

### Queue-Based Notifications
- Post creation dispatches a Redis queue job
- Notifications processed asynchronously
- Notification history available in dashboard

### Real-Time Notification System
- Notification bell with live updates
- Laravel Reverb websocket broadcasting
- Laravel Echo frontend listener
- Polling fallback when websocket is unavailable

### Redis Pub/Sub Messaging
- Publish messages from dashboard
- Custom Artisan subscriber command
- Messages cached and displayed in UI
- Edit and delete cached messages

### Task Scheduling
- Custom Artisan command scheduled through Laravel Scheduler
- Demonstrates automated background reporting

---

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

---

## System Flow

### Notification Flow

1. User creates a post
2. Post stored in MySQL
3. Redis queue job is dispatched
4. Notification record created
5. Broadcast event sent via Reverb
6. Frontend notification bell updates

### Redis Pub/Sub Flow

1. Message published from dashboard
2. Redis receives message on a channel
3. Subscriber command listens to channel
4. Message stored in cache
5. Event broadcast updates frontend

---

## Important Files

### Backend

```
app/Http/Controllers/PostController.php
app/Jobs/SendPostNotification.php
app/Console/Commands/ListenRedisChannel.php
app/Console/Commands/DailyReport.php
app/Events/NotificationSent.php
app/Events/RedisMessageReceived.php
```

### Frontend

```
resources/views/posts/index.blade.php
resources/js/bootstrap.js
resources/js/app.js
```

### Configuration

```
config/queue.php
config/database.php
config/broadcasting.php
config/reverb.php
routes/web.php
routes/console.php
```

---

## Installation

### Clone the repository

```bash
git clone https://github.com/AliHossain09/laravel-redis-queue-notification.git
cd laravel-redis-queue-notification
```

### Install dependencies

```bash
composer install
npm install
```

### Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with:

- MySQL credentials
- Redis host and port

### Run migrations

```bash
php artisan migrate
```

### Build frontend assets

```bash
npm run build
```

For development:

```bash
npm run dev
```

---

## Running the Project

Run these commands in separate terminals.

### Start Laravel server

```bash
php artisan serve
```

### Start queue worker

```bash
php artisan queue:work
```

### Start Reverb websocket server

```bash
php artisan reverb:start
```

### Start Redis subscriber

```bash
php artisan app:redis-subscribe demo-channel
```

### Run scheduler

```bash
php artisan schedule:work
```

---

## Demo Commands

```
php artisan app:daily-report
php artisan app:redis-subscribe demo-channel
php artisan queue:work
php artisan reverb:start
php artisan schedule:list
```

---

## Notes

- Redis must be running for queue, cache, session, and Pub/Sub features.
- Reverb must run for real-time websocket notifications.
- Redis `MISCONF` errors occur when persistence fails — restarting Redis usually resolves it.
- Laravel Horizon is not included because it requires `pcntl` and `posix`, which are not available in standard Windows PHP environments.

---

## Why This Project Matters

This project demonstrates practical experience with:

- Asynchronous job processing
- Redis integration beyond caching
- Real-time event broadcasting
- Background task automation
- Backend and frontend coordination in Laravel
- Building infrastructure-focused developer demos

---

## Suggested GitHub Topics

```
laravel
php
redis
queue
pubsub
reverb
laravel-echo
blade
mysql
vite
```

---

## License

This project is open-sourced under the **MIT License**.
