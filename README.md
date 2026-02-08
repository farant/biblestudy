# St. Joseph's Church Catena Aurea Reading Group

A medieval-themed website for our parish reading group studying the Catena Aurea by St. Thomas Aquinas.

**Live at [https://sunflower.family](https://sunflower.family)**

## Site Pages

| Page | Route | Description |
|------|-------|-------------|
| **Home** | `/` | Welcome page with Aquinas portrait and featured blessing |
| **Resources** | `/resources` | Study aids, commentary links, and community study posts |
| **Community** | `/community` | Blog-style posts and comments from group members |
| **Admin** | `/admin` | Admin login for post/comment moderation |

## Tech Stack

- **PHP 8.3** with Nginx (Alpine-based Docker image)
- **PostgreSQL** database
- **Railway** for hosting and deployment
- Dark medieval theme with gold/cream text, quill-and-inkwell imagery

## Project Structure

```
public/
  index.php       — Front controller (routing + request handlers)
  style.css       — All site styles
  images/         — Static images (portrait, icons, ornaments)
  uploads/        — User-uploaded images
templates/        — PHP templates (layout, home, community, resources, admin)
src/
  db.php          — Database connection
  helpers.php     — Shared helper functions
migrations/       — PostgreSQL migration scripts
Dockerfile        — Container build
nginx.conf        — Nginx config
railway.json      — Railway deployment config
start.sh          — Entrypoint: runs migrations, starts nginx + php-fpm
```

## Deployment

The site deploys automatically via Railway when changes are pushed to the `claude/setup-github-pages-site-sALzY` branch.

### Environment Variables (set in Railway)

- `DATABASE_URL` — PostgreSQL connection string
- `ADMIN_USERNAME` / `ADMIN_PASSWORD` — Initial admin credentials (used by migration)

## Features

- **Posts & comments** — Anyone can write posts and leave comments on the Community and Resources pages
- **Image uploads** — Attach images (JPEG, PNG, GIF, WebP, up to 5 MB) to posts
- **Admin moderation** — Admins can log in to delete spam posts/comments
- **CSRF protection** — All form submissions are protected with CSRF tokens
