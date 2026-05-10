# St. Joseph's Church Catena Aurea Reading Group

A medieval-themed website for our parish reading group studying the Catena Aurea by St. Thomas Aquinas.

**Live at [https://sunflower.family](https://sunflower.family)**

## Site Pages

| Page | Route | Description |
|------|-------|-------------|
| **Home** | `/` | Welcome page with announcements, questionnaire, and featured blessing |
| **Session Notes** | `/sessions` | Notes and reflections from each session |
| **Commentators** | `/commentators` | Bios of the Church Fathers |
| **Discussions** | `/discussions` | Group members share answers to discussion questions by chapter |
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
templates/        — PHP templates (layout, home, discussions, community, etc.)
config/
  chapters.php    — List of chapters available in the Discussions dropdown
  questions.php   — Current discussion questions (editable)
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
- **Discussion answers** — Group members can share their answers to discussion questions, organized by chapter. Questions are stored with each answer so old responses stay intact if questions change.
- **CSRF protection** — All form submissions are protected with CSRF tokens

## Adding Chapters and Questions

- **New chapter**: Add a line to `config/chapters.php` — e.g. `['slug' => 'matthew-4', 'label' => 'Matthew 4'],`
- **Change questions**: Edit `config/questions.php`. Old answers keep their original questions; new submissions use the updated list.
