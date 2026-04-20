# Local Development Setup

## Prerequisites

- [Local by Flywheel](https://localwp.com/) — WordPress local dev environment
- [Git](https://git-scm.com/)
- A code editor (VS Code recommended)

## First-Time Setup

### 1. Clone the repo

```bash
git clone https://github.com/Bruddas-Industries/Sterling-Properties.git
cd Sterling-Properties
```

### 2. Create a Local site

1. Open **Local** and click **+** to create a new site
2. Name it (e.g. `sterling-properties` or `rivergate-bordentown`)
3. Choose **Preferred** environment (PHP 8.x, MySQL, nginx)
4. Complete setup — Local will create the WordPress install

### 3. Link the theme

Copy or symlink the theme folder into your Local site's `wp-content/themes/` directory.

**Option A — Copy (simpler):**
```bash
cp -r sterling-properties/theme/sterling-properties \
  ~/Local\ Sites/sterling-properties/app/public/wp-content/themes/
```

**Option B — Symlink (recommended for active dev, changes reflect instantly):**
```bash
ln -s /path/to/repo/sterling-properties/theme/sterling-properties \
  ~/Local\ Sites/sterling-properties/app/public/wp-content/themes/sterling-properties
```

Adjust paths to match your Local sites directory.

### 4. Activate the theme

1. Log into WordPress admin (`http://sterling-properties.local/wp-admin`)
2. Go to **Appearance → Themes** and activate **Sterling Properties**

### 5. Import the Elementor kit (optional)

1. In WordPress admin go to **Elementor → Tools → Import / Export Kit**
2. Import `sterling-properties/elementor-kit/sterling-properties-kit.json`

---

## Working on Static Previews

The `preview-1/` folders are plain HTML/CSS — no build step required.

```bash
# Open directly in browser, or use VS Code Live Server extension
open rivergate-bordentown/preview-1/index.html
```

The previews reference the theme's CSS via a relative path:
```
../theme/rivergate-bordentown/assets/css/global.css
```

Keep that path intact when moving files.

---

## Deploying to Production

See [CONTRIBUTING.md](CONTRIBUTING.md) for the full deployment workflow.

Short version:
1. Push changes to `main`
2. Use **All-in-One WP Migration** or **Cloudways Migrator** to push from Local to the live server
3. Run URL search-replace: `wp search-replace 'yoursite.local' 'yourdomain.com'`
