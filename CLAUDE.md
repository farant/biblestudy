# Claude Code Guidelines for This Project

## Who Works on This Project

Amanda is usually the one working on this project. She has never built a web app before and is not a programmer. Fran (her husband) set up the initial infrastructure and handles technical decisions when needed.

## How to Communicate

- Explain things in plain, non-technical language. Avoid jargon.
- If Amanda asks for a feature, just build it — don't make her configure things or run commands.
- If there are technical concerns about an idea (e.g., security risks, performance problems, or something that could break existing functionality), explain the concern clearly so she can decide whether to rethink the approach.
- If there is an important technical decision that you are not capable of making on Amanda's behalf (e.g., choosing a hosting plan, domain configuration, third-party service integration, or architectural changes that affect cost/security), suggest that she ask Fran what he thinks.

## Protecting the Database

This is critical. Real user data lives in the PostgreSQL database — posts, comments, admin accounts, and settings.

- **Never write migrations that DROP or DELETE existing tables or columns.** Always use additive migrations (add new columns/tables, don't remove old ones).
- **Never overwrite or truncate data.** If a feature changes how data is structured, migrate the old data to the new structure.
- **Always use the migration system** (`migrations/` folder with numbered .sql files). Never modify the database schema outside of migrations.
- **Test any database changes carefully.** If a migration could fail partway through, consider wrapping it in a transaction.
- Migration filenames must be numbered sequentially (e.g., `005_add_something.sql`).

## Project Architecture

- **PHP 8.3** — plain PHP, no framework. Keep it simple.
- **PostgreSQL** — database, provided by Railway via `DATABASE_URL`.
- **Nginx + PHP-FPM** — served via Docker on Railway.
- All routes go through `public/index.php` (front controller pattern).
- Templates are in `templates/` — `layout.php` wraps every page.
- Shared helpers are in `src/helpers.php`.
- Static files (CSS, images) are in `public/`.

## Code Style

- Keep the PHP simple and readable — Amanda may look at it.
- Comment any non-obvious logic.
- Use prepared statements for all database queries (PDO with `?` placeholders).
- Escape all output with the `e()` helper function.
- All forms that perform destructive actions (delete) must include CSRF tokens.

## Config Files

Two simple config files control the Discussion Answers feature:

- **`config/chapters.php`** — Lists which chapters appear in the dropdown. To add a new chapter, just add a line like `['slug' => 'matthew-4', 'label' => 'Matthew 4'],` to the array.
- **`config/questions.php`** — The current discussion questions. If you change or add questions, new submissions will use the updated list. Old answers keep whatever questions they were originally asked — the question text is saved alongside each answer in the database.

## Deployment

The site deploys automatically via Railway when code is pushed. Migrations run automatically on startup via `start.sh`. The site is live at https://sunflower.family.
