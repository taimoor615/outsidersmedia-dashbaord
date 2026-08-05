# OutsiderMedia — Project Scope & Architecture

> Social media content management platform for an agency and its clients.
> Team drafts posts → clients review/approve via a public portal → admins approve & schedule → posts auto-publish on schedule.

Last documented: 2026-07-29

---

## 1. Overview

**OutsiderMedia** is a Laravel web application that lets a marketing/social-media agency manage content for multiple client brands end-to-end:

- The **agency team** creates social media posts (per platform captions, media, scheduling info) for each client.
- **Clients** review those posts through a **public, token-based portal** (no login) — they approve, request changes, suggest dates, or edit captions.
- **Admins** give final approval and schedule the post for a specific date/time.
- A scheduled command **auto-publishes** posts when their time arrives (publishing to real social networks is currently stubbed — see §11).

Brand name in code/UI is **OutsiderMedia** (some legacy seeder data still uses `mixbloom.com` emails).

---

## 2. Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 (PHP `^8.2`) |
| Frontend | Blade templates, Tailwind CSS 4, Vite 7, Alpine.js (via CDN), FullCalendar |
| Database | **MySQL** (runs under XAMPP; `.env.example` says sqlite but migrations use MySQL-only enum ALTERs) |
| Auth | Laravel session auth + custom role middleware |
| Queue | `database` driver (`jobs`/`failed_jobs` tables) |
| Cache / Sessions | `database` driver |
| Mail | SMTP (actual `.env`); mailables for invitations, client welcome, password reset |
| File storage | `local` + `public` disk (`storage/app/public`, needs `php artisan storage:link`) |
| Dev tooling | Laravel Pint, Pail, Sail, PHPUnit, Faker |

**Key commands** (from `composer.json`):
- `composer dev` — runs server + queue listener + logs + Vite concurrently
- `composer setup` — install, key generate, migrate, npm build
- `composer test` — config clear + phpunit

---

## 3. Architecture & Design

- **Pattern:** Standard Laravel MVC. Controllers grouped by area (`Admin/`, `Auth/`, `Team/`, root). Business logic for publishing lives in a service (`SocialPublishService`).
- **App config:** Uses the Laravel 11/12 slim skeleton — no `Kernel.php`; middleware and routing are wired in `bootstrap/app.php`. Console commands & scheduler defined inline in `routes/console.php`.
- **3-role system:** `admin`, `team`, `client` (on `users.role`). Clients don't authenticate — they use the token portal.
- **Layouts:** `layouts/admin.blade.php` (admin shell), `layouts/team.blade.php` (team shell), `layouts/guest.blade.php` (auth pages). Views pick a layout dynamically, e.g. `@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')`.
- **Soft deletes:** `Post` and `Client` use `SoftDeletes`.

---

## 4. Roles & Access Control

Three roles on `users.role`: **admin**, **team**, **client**.

### Middleware (`app/Http/Middleware/`, aliased in `bootstrap/app.php`)
| Alias | Middleware | Rule |
|---|---|---|
| `admin` | `AdminMiddleware` | Auth + `isAdmin()` + active status |
| `team` | `TeamMiddleware` | Auth + `canManageClients()` (**admin OR team**) + active — the shared gate; admins pass it too |
| `check.status` | `CheckUserStatus` | Globally appended to `web`; logs out any user whose status ≠ active |
| — | `ClientMiddleware` | No-op (clients use token portal, don't authenticate) |

### Who can do what
- **Team:** create/edit posts, submit to client, resubmit after changes, manage clients & notes. Team dashboard scoped to their own posts. **Cannot** delete, approve, or schedule posts.
- **Admin:** everything team can, **plus** approve, schedule, return-to-client, delete posts/clients/notes, manage team members, view analytics. Fine-grained admin-only actions are additionally guarded inline via `if (!auth()->user()->isAdmin()) abort(403)`.
- **Client (no login):** via portal token — approve, request changes/reject, suggest publish date, edit captions, add/edit notes. Only on `pending_client` posts.

> Note: `ClientController` & `ClientNotesController` live in the `Admin\` namespace but are routed under the shared `['auth','team']` group — they are **not** admin-only.

---

## 5. Data Model

### Core entities
| Model | Represents | Key relationships |
|---|---|---|
| `User` | admin/team/client accounts | `clients()`, `feedback()`, `posts` (created_by/approved_by) |
| `Client` | an agency client / brand profile | `creator()`, `posts()`, `client_notes` |
| `Post` | a social media post | `client()`, `creator()`, `approver()`, `media()`, `feedback()` |
| `PostMedia` | images/videos on a post | `post()` |
| `PostFeedback` | feedback/revision log per post | `post()`, `user()` |
| `ClientNote` | notes thread on a client (team ↔ client) | `client()`, `addedBy()` |

### Relationships (summary)
```
users 1─* clients          (created_by)
clients 1─* posts
posts 1─* post_media
posts 1─* post_feedback
users 1─* posts            (created_by / approved_by)
clients 1─* client_notes
users 1─* client_notes     (added_by; NULL = note came from client portal)
```

### `Post` highlights
- **Fields:** `client_id`, `created_by`, `post_type` (standard/carousel/video), `caption`, `webpage_url`, per-platform captions (`facebook_message`, `instagram_message`, `linkedin_message`, `twitter_message`, `tiktok_message`, `youtube_message`), Google Business fields (`google_*`, `offer_*`, `event_*`), `platforms` (JSON), `scheduled_at`, `published_at`, `status`, `approval_status`, `approved_by`, `approved_at`.
- **Helpers:** `canEdit()`, `canDelete()` (**returns `true` for all statuses** — admins can delete any post), `getStatusBadgeAttribute()`, `getStatusLabelAttribute()`, plus status scopes.

### `Client` highlights
- Full brand brief: descriptions, target audience, goals, tone, content types/avoid, keywords, competitors, brand assets, posting days/times, networks & links, content mix, plan.
- Auto-generates a random 16-char `share_token` on creation (powers the public portal). `getShareUrlAttribute()` → `route('client.view', token)`.
- **Plans** (hardcoded): starter = 8 posts / 2 networks / $359; business = 12 / 4 / $539; scale = 16 / 2 / $659.

### Database tables (migrations in `database/migrations/`)
`users` (+ role/status/profile fields), `password_reset_tokens`, `sessions`, `cache`, `jobs`/`job_batches`/`failed_jobs`, `clients`, `posts`, `post_media`, `post_feedback`, `notifications`, `client_notes`. Later migrations add posting times, network links, content mix, note author fields, and enum expansions.

**Seeders:** `UserSeeder` creates 1 admin (`admin@mixbloom.com` / `password`), 2 active team members, 1 inactive. **Factories:** only `UserFactory`.

---

## 6. Features by Area

### Posts (`PostController`, `resources/views/posts/`)
- List with filter (status/client/date), search (caption/message), sort, pagination.
- Create/edit with per-platform captions, media upload (images or MP4/MOV video, carousel up to 10 images), Google Business Profile subform, scheduling.
- Detail page (`show`) with feedback thread and workflow buttons.
- Media streaming via `VideoStreamController` (HTTP Range support for seeking).

### Clients (`Admin\ClientController`, `resources/views/admin/clients/`)
- CRUD (admin + team), toggle status, update content mix.
- On creation: welcome email to client (portal link) + admin notification email.

### Team management (`Admin\TeamController`, admin-only)
- CRUD team members, resend verification, toggle status. New members activate via email token and set their own password (`VerificationController`).

### Dashboards
- **Admin** (`Admin\DashboardController`): KPIs, activity feed, charts.
- **Team** (`Team\DashboardController`): own posts stats, changes-requested queue, upcoming scheduled, mini calendar.
- **Analytics** (`Admin\AnalyticsController`, admin-only): KPIs, charts, funnels.

### Calendar (`CalendarController`)
- FullCalendar view; events served as JSON. Admin sees all posts; team sees own.

### Notifications (`NotificationController`)
- Laravel database notifications (`PostActivityNotification`): `client_approved`, `client_requested_changes`, `admin_approved`, `admin_scheduled`. Sent via database + mail.

### Profile (`ProfileController`)
- Edit profile, update password, upload/delete profile image.

---

## 7. Post Lifecycle (Status State Machine)

**`status` values:** `draft`, `pending_client`, `changes_requested`, `pending_approval`, `approved`, `rejected`*, `scheduled`, `published`, `failed`.
**`approval_status` values:** `pending`, `approved`, `changes_requested`, `rejected`.

```
[Team]   create ─────────────► draft
[Team]   submit to client ───► pending_client
[Client] approve ───────────► pending_approval      (notifies admins)
[Client] request changes ───► changes_requested     (notifies creator)
[Team]   resubmit ──────────► pending_client
[Admin]  approve ───────────► approved              (notifies creator)
[Admin]  return to client ──► pending_client
[Admin]  schedule ──────────► scheduled             (notifies creator)
[Cron]   time passes ───────► published  (or failed on error)
```

- **Client actions** (approve / request changes / suggest date / edit caption) are only allowed while a post is `pending_client`.
- `canEdit()` allows editing in draft, pending_client, changes_requested, pending_approval, approved (not scheduled/published/failed/rejected).
- \*`rejected` exists in the enum but no code path assigns it — a client "reject" maps to `changes_requested`.

---

## 8. Client Portal (public, token-based)

- **URL:** `route('client.view', token)` → `resources/views/client/portal.blade.php`. Routes under `Route::prefix('client')` with **no auth middleware**.
- **Access model:** each request resolves the client by `Client::where('share_token', $token)->firstOrFail()` (404 on bad token), then checks `$post->client_id === $client->id` (else 403). Possession of the token = access; no password.
- **Actions:** approve, reject/request-changes, suggest-date, update-post (captions), store/update notes.
- **Visibility rule:** portal shows posts that are `published` OR have `scheduled_at <= now() + 4 weeks` (published + near-term work), plus the client's notes thread.
- Client actions write `post_feedback` (`is_client_feedback = true`) and/or `client_notes` (`added_by = null`), and trigger admin/creator notifications.

---

## 9. Scheduled Publishing

- **Command:** `posts:publish-scheduled` (defined inline in `routes/console.php`) — finds posts where `status = scheduled` and `scheduled_at <= now()`, runs each through `SocialPublishService::publish()`.
- **Schedule:** `Schedule::command('posts:publish-scheduled')->everyMinute()`. Requires an OS-level cron running `php artisan schedule:run` (and a queue worker for queued mail) to actually fire in production.
- **`SocialPublishService`:** on success sets `status = published` + `published_at`; on exception sets `status = failed`. **Per-platform publishing is currently stubbed (logs only) — no real social API calls yet.**

---

## 10. Configuration & Integrations

- **Database:** runtime **MySQL** (`.env` `DB_CONNECTION=mysql`). Sessions, cache, queue all use the database driver.
- **Mail:** actual `.env` uses `smtp`. Mailables: `TeamMemberInvitation`, `ClientWelcome`, `NewClientNotification`, password reset.
- **Filesystem:** `local` + `public` disk. Post media → `storage/app/public/uploads/posts/{id}`. Profile images → `public_path('uploads/profiles')`.
- **Social APIs:** **none configured** — Facebook/Instagram/LinkedIn/Twitter/TikTok/YouTube/Google integration is scaffolded (platform selection + per-platform methods) but not implemented.

---

## 11. Known Gaps / TODO

- 🔴 **Social publishing not implemented** — `SocialPublishService` only logs; posts are marked "published" without actually posting to networks. Real integration needs per-client OAuth tokens.
- ⚠️ **`users.verification_token`** column is used in code but has **no migration** creating it (commented out) — added out-of-band; add a proper migration.
- ⚠️ **DB engine mismatch** — `.env.example` says sqlite but MySQL-only enum ALTERs require MySQL.
- ⚠️ **`rejected` status** defined but never assigned.
- ⚠️ **`PublicClientController@viewByToken`** renders a non-existent `client.view` blade — appears to be dead/legacy code (live portal is `ClientPortalController`).
- ⚠️ **Seeder** still uses `mixbloom.com` emails though product is branded OutsiderMedia.

---

## 12. Git & Deployment Workflow

### Repository
- **Remote:** `origin` → `https://github.com/taimoor615/outsidersmedia-dashbaord.git`
- **Auth:** GitHub CLI (`gh`) logged in as `taimoor615` over HTTPS; credentials cached in Windows Credential Manager. Token scopes include `repo` + `workflow`. Push/pull both work without re-entering credentials.

### Branches (3-tier promotion flow)
| Branch | Purpose |
|---|---|
| `local` | Active development (current working branch) |
| `staging` | Pre-production / review |
| `main` | Live / production |

**Promotion flow:** commit on `local` → PR `local → staging` → merge → PR `staging → main` → merge.

### How push/pull works here
- Day-to-day work happens on `local`. Commits are made on `local` and pushed to `origin/local`.
- Changes are promoted upward via **Pull Requests** (created with the `gh` CLI), reviewed, and merged **by the user**.
- Nothing is auto-merged; the user reviews and merges every PR personally.

### Working agreement (automation rules)
- Claude handles the mechanics: staging changed files, commit messages, `git push`, and PR creation via `gh`.
- **Claude pushes / opens PRs only on explicit instruction** (e.g. "push kro"). After code changes, Claude waits.
- Only files Claude itself modified are staged — ad-hoc user files (`*.docx`, scratch `*.py`, etc.) are never committed.
- After pushing `local`, Claude opens the `local → staging` PR and stops; the `staging → main` PR is opened only after the user confirms staging is merged.

### Typical push sequence (when told to push)
```bash
git add <files Claude changed>
git commit -m "<message>"
git push origin local
gh pr create --base staging --head local --title "..." --body "..."
```
