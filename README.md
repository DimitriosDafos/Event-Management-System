# Event Management System

A professional event organization and team management platform built for recurring club nights and live events. Designed for promoters, DJs, bar staff and door teams who need a single source of truth for every event.

**Live demo:** [webapps.dafos.eu/disclosure](https://webapps.dafos.eu/disclosure)  
**Stack:** Laravel 12 · PHP 8.4 · MariaDB · Blade · Alpine.js · Tailwind CSS

---

## Features

### Event Planning
- Create and manage events with title, date, start/end time, location and address
- Three-stage workflow: **Draft → Published → Past**
- Mark special events (e.g. anniversary nights) with a highlight flag
- Upload event flyers (stored via Laravel Storage)
- Rich description editor per event

### Public Landing Page
- Automatically shows the next upcoming published event
- Displays flyer, date, time, location and DJ line-up to the public
- No login required for visitors

### DJ Line-Up Management
- Assign DJs per event with set times and a drag-friendly sort order
- Line-up is shown on the public page once the event is published

### Bar Shift Scheduling
- Assign team members to bar shifts with time slots
- Sortable shift order within each event
- At-a-glance overview per event

### Door Team Assignment
- Separate door crew scheduling independent from bar shifts
- Same sortable, time-slotted structure

### To-Do & Task Tracking
- Create tasks linked to an event with due date and time
- Assign multiple team members to a single task
- Track costs per task (contributes to event expense total)
- Mark tasks done — with timestamp and user recorded

### Financial Overview
- Log income entries per event (description, amount, date)
- Automatic balance calculation: **Income − Task Costs = Balance**
- Full audit trail: every income entry records who created it

### Role-Based Access Control
Four roles with layered permissions:

| Role | Access |
|---|---|
| `admin` | Full access — all features, user management |
| `marketing` | Create & manage events, shifts, todos |
| `dj` | View events and own line-up slots |
| `member` | View-only access |

Users can hold multiple roles simultaneously.

### User Management (Admin)
- Create, edit and deactivate team members
- Assign one or more roles per user
- Deactivated users can no longer log in

### Dashboard
- Quick overview of upcoming events
- Recent to-do items across all events
- Direct links to active event detail pages

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Language | PHP 8.4 |
| Database | MariaDB |
| Frontend | Blade · Alpine.js · Tailwind CSS (CDN) |
| Auth | Laravel Session Auth (username + password) |
| File Storage | Laravel Storage (local disk) |
| Deployment | Nginx · PHP-FPM · Let's Encrypt SSL |

---

## Installation

```bash
git clone https://github.com/DimitriosDafos/Event-Management-System.git
cd Event-Management-System
composer install
cp .env.example .env
php artisan key:generate
# Configure your database in .env
php artisan migrate
php artisan db:seed
php artisan storage:link
```

**Default admin credentials after seeding:**  
Username: `admin` · Password: `password` — change immediately after first login.

---

## Environment Variables

```env
APP_NAME="Event Management System"
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

---

## License

Proprietary — © 2026 Dimitrios Dafos. All rights reserved.  
Not licensed for redistribution or commercial use without written permission.
