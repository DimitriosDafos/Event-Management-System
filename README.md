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
- When no event is active: displays the last past event with a **thank-you message** to attendees, including the DJ line-up
- Announcements and news are shown below
- No login required for visitors

### Announcements & News
- Admins and marketing members can create free-text announcement blocks (title + body)
- Shown on the public homepage below the last event
- Each announcement can be activated/deactivated and sorted independently
- Full audit trail: records who created each entry and when

### Newsletter
A fully self-hosted newsletter system — no external service required:

**Public sign-up:**
- Sign-up form linked in the footer of every public page
- Name (optional) + email address
- Automatic HTML confirmation email sent on sign-up
- 5-second success message, then redirect to homepage

**Sending campaigns (Admin & Marketing):**
- Compose newsletters with subject line and free text
- Live character counter and **preview** before sending
- Confirmation dialog showing recipient count before dispatch
- Sends to all active subscribers via SMTP
- Each recipient receives a personalized HTML email with the brand name, body text and a footer with unsubscribe notice

**Admin management:**
- Full subscriber list with search and filter (active / inactive)
- Activate, deactivate or delete individual subscribers
- Export all active subscribers as CSV — or select specific ones for export
- **Send history:** every campaign is stored with subject, body, sender and timestamp
- **Per-recipient log:** for each campaign, every email address is recorded with delivery status (success / error) and error message if applicable

### DJ Line-Up Management
- Assign DJs per event with set times and a sortable order
- Line-up is shown on the public page once the event is published

### Bar Shift Scheduling
- Assign team members to bar shifts with time slots
- Sortable shift order within each event

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

### Branding & White-Label
Customize the entire public-facing site without touching any code — all managed through the admin panel:

| Setting | Description |
|---|---|
| **Logo** | Upload PNG, JPG, SVG or WebP (transparent background recommended) |
| **Brand name** | Appears in the header and browser tab |
| **Tagline** | Short subtitle shown next to the logo |
| **Footer text** | Bottom line of every public page |

Live preview is shown directly on the branding settings page. When no logo is uploaded, the brand name is displayed as styled text.

### Role-Based Access Control
Four roles with layered permissions:

| Role | Access |
|---|---|
| `admin` | Full access — all features, user management, branding |
| `marketing` | Create & manage events, shifts, todos, announcements, newsletters |
| `dj` | View events and own line-up slots |
| `member` | View-only access |

Users can hold multiple roles simultaneously.

### User Management (Admin)
- Create, edit and deactivate team members
- Assign one or more roles per user
- Deactivated users can no longer log in

### Dashboard
- Quick overview of upcoming events and recent tasks
- One-click access to newsletter compose, send history and subscriber list
- Direct links to branding settings and announcements

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
| Email | SMTP (any provider — configured via `.env`) |
| Deployment | Nginx · PHP-FPM · Let's Encrypt SSL |

---

## Installation

```bash
git clone https://github.com/DimitriosDafos/Event-Management-System.git
cd Event-Management-System
composer install
cp .env.example .env
php artisan key:generate
# Configure your database and mail settings in .env
php artisan migrate
php artisan db:seed
php artisan storage:link
```

**Default admin credentials after seeding:**  
Username: `admin` · Password: `password` — change immediately after first login.

---

## Configuration

### Database & App

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

### Email / SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

Any SMTP-compatible provider works (e.g. Mailgun, Brevo, your own mail server).

---

## Branding Setup

After installation, log in as admin and navigate to **Admin → Branding & Erscheinungsbild** to:

1. Upload your logo (PNG or SVG with transparent background recommended)
2. Set your organization name and optional tagline
3. Customize the footer text

All branding is stored in the database — no code changes required.

---

## License

Proprietary — © 2026 Dimitrios Dafos. All rights reserved.  
Not licensed for redistribution or commercial use without written permission.
