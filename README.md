# St. Joseph's Church Catena Aurea Reading Group

A medieval-themed website for our parish reading group studying the Catena Aurea by St. Thomas Aquinas.

## Site Pages

| Page | File | What it's for |
|------|------|---------------|
| **Home** | `index.html` | Open-book welcome page with title and featured verse |
| **Resources** | `resources.html` | Study materials, PDFs, charts, timelines from Fran |
| **Quotes** | `quotes.html` | Favorite quotes shared by group members |
| **Questions** | `questions.html` | Difficult passages and Catena Aurea commentary |
| **Community** | `community.html` | Announcements, member directory, Church Father bios, articles |

## How to Edit the Site

### The easy way (on GitHub.com)

1. Go to the repository on GitHub
2. Click on the file you want to edit (e.g., `quotes.html`)
3. Click the pencil icon (edit button) in the top right
4. Make your changes to the text between the HTML tags
5. Scroll down and click **"Commit changes"**
6. The site will update automatically within a minute or two

### How to add a quote

1. Open `quotes.html` for editing
2. Find the section where quotes are listed
3. Copy an existing `<div class="quote-card">` block
4. Replace the quote text, attribution, and submitter name
5. Commit the changes

### How to add a question

1. Open `questions.html` for editing
2. Copy an existing `<div class="question-entry">` block
3. Replace the passage reference, question, and commentary quote
4. Commit the changes

### How to upload a document (for Fran)

1. Go to the `resources/` folder in the repository
2. Click **"Add file" > "Upload files"**
3. Drag and drop your PDF, image, or document
4. Click **"Commit changes"**
5. Open `resources.html` for editing
6. Add a new card with a link to your file: `resources/your-file-name.pdf`
7. Commit the changes

### How to add an image

1. Upload the image to the `images/` folder (same process as above)
2. In the HTML file where you want the image, add:
   ```html
   <img src="images/your-image.jpg" alt="Description of the image" style="max-width: 100%;">
   ```

### How to add an announcement

1. Open `community.html` for editing
2. Find the Announcements section
3. Add a new `<div class="announcement">` block at the top (newest first)
4. Commit the changes

## Folders

- `resources/` — Upload PDFs, documents, charts, and timelines here
- `images/` — Upload photos and pictures here

## Site URL

Once GitHub Pages is enabled, the site will be at:

**`https://farant.github.io/biblestudy/`**

### Enabling GitHub Pages

1. Go to the repository **Settings** on GitHub
2. Click **Pages** in the left sidebar
3. Under "Source," select the `main` branch
4. Click **Save**
5. Wait a minute, then visit the URL above
