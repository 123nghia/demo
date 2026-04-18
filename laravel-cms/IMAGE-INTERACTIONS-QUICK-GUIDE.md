# Image Interactions - Quick Reference Guide

## Directory Structure
```
HOVI CMS
├── public/uploads/
│   ├── projects/          ← Project covers & detail galleries
│   ├── videos/            ← Video thumbnails
│   ├── blogs/             ← Blog thumbnails
│   ├── home/              ← Hero, profile, highlights
│   └── settings/          ← Logos, favicon, OG images
└── resources/views/
    ├── site/
    │   ├── projects/detail.blade.php    [MAIN GALLERY VIEW]
    │   ├── projects/show.blade.php      [PROJECT CARDS]
    │   ├── pages/home.blade.php         [HOME HIGHLIGHTS - LIGHTBOX]
    │   └── video/show.blade.php         [VIDEO THUMBNAILS]
    └── admin/
        ├── projects/detail-pages/_form.blade.php  [UPLOAD & ACTION CONFIG]
        └── home-content/index.blade.php            [HOME IMAGE MANAGEMENT]
```

---

## Image Interaction Flows

### 1. Project Detail Gallery
```
Database (ProjectDetailPage)
    ├── thumbnail_image: string
    ├── thumbnail_click_action: 'link' | 'lightbox'
    └── gallery_images: array

View (detail.blade.php)
    └── Display Gallery
        ├── Lazy load images
        ├── Fallback chain: gallery → thumbnail → cover
        └── CSS class: detail-gallery__item
```

### 2. Home Page Highlights with Lightbox
```
Database (ProjectDetailPage)
    ├── thumbnail_image
    └── thumbnail_click_action

Home Page Logic
    ├── Check action type
    ├── If 'lightbox' → url = null, action = 'lightbox'
    ├── If 'link' → resolve URL from slug
    └── Render card with action attribute

Frontend (Needs Implementation)
    └── Listen to action attribute
        ├── If 'link' → Navigate
        └── If 'lightbox' → Open modal
```

### 3. Admin Image Upload & Preview
```
User Selects File
    ↓
JavaScript Event: input.addEventListener('change')
    ↓
Preview Generation: URL.createObjectURL(file)
    ↓
Display Preview
    ├── Show thumbnail
    ├── Display file info (size, dimensions)
    └── Enable/disable form submit

Form Submit
    ↓
Server-side Processing (ProjectDetailPageController)
    ├── Validate: image, max 5120KB
    ├── Generate filename: {prefix}-{timestamp}-{random}.{ext}
    ├── Move to public/uploads/projects/
    └── Store path in database
```

---

## Upload Handlers by Entity

| Entity | Controller | Path | Prefix | Max Size |
|--------|-----------|------|--------|----------|
| **Project Detail** | ProjectDetailPageController | `/uploads/projects/` | `project-detail-{thumbnail\|gallery}` | 5120 KB |
| **Video** | VideoController | `/uploads/videos/` | `video-thumb-` | 4096 KB |
| **Blog** | BlogController | `/uploads/blogs/` | `blog-thumb-` | 5120 KB |
| **Project** | ProjectController | `/uploads/projects/` | `project-cover-` | 4096 KB |
| **Home Content** | HomeContentController | `/uploads/home/` | `home-{profile-slider\|highlight}` | - |
| **Settings** | SettingController | `/uploads/settings/` | Various | - |

---

## Click Action Types

### Link Action (Default)
```
User clicks card
    ↓
Navigate to URL
    ├── Option 1: Detail page (from slug)
    └── Option 2: Custom URL
```

### Lightbox Action
```
User clicks card
    ↓
Open Image Modal
    ├── Show full-size image
    ├── Keep user on current page
    └── Show navigation/close button
```

**Database Field:** `thumbnail_click_action` (enum: 'link', 'lightbox')

---

## JavaScript Event Listeners

### Thumbnail Preview
```javascript
fileInput.addEventListener('change', function() {
    1. Get file from input
    2. Create object URL
    3. Set image src
    4. Read dimensions on load
    5. Display metadata
})
```

### Gallery Preview
```javascript
galleryInput.addEventListener('change', function() {
    1. Clear previous previews
    2. Get all selected files
    3. For each file:
        - Create object URL
        - Create preview DOM element
        - Append to grid
    4. Show/hide grid container
})
```

### Home Content Rows
```javascript
addRowButton.addEventListener('click', function() {
    1. Clone row template
    2. Clear input values
    3. Append to form
})

removeButton.addEventListener('click', function() {
    1. Remove row from DOM
})

actionSelect.addEventListener('change', function() {
    1. Check if action = 'lightbox'
    2. Toggle link settings visibility
    3. Clear link fields if lightbox
})
```

---

## CSS Hover & Transform Classes

### Admin Components
```css
.thumb-behavior-option:hover       → Border & shadow effect
.thumb-behavior-option.is-selected → Blue highlight
.table-hover tbody tr:hover         → Row highlight
```

### Frontend Components
```css
.video-player__link:hover  → Background color change
.detail-gallery__item      → (Hover effects defined)
.video-detail-cover        → Image styling
```

---

## Image Validation

### File Type
- Format: `image` (mime type validation)
- Accepted: jpeg, jpg, png, gif, webp

### File Size
- Project thumbnails: **5120 KB** (5 MB)
- Project cover: **4096 KB** (4 MB)
- Video thumbnails: **4096 KB** (4 MB)
- Gallery images: **5120 KB** (5 MB) each, **80 max** items

### Filename Generation
```
Pattern: {prefix}-{YmdHis}-{random8}.{ext}
Example: project-detail-gallery-20260417142530-aBcDeF12.jpg
```

---

## Filename Prefixes Reference

| Prefix | Purpose | Entity |
|--------|---------|--------|
| `project-detail-thumbnail` | Detail page main image | ProjectDetailPage |
| `project-detail-gallery` | Gallery images in detail | ProjectDetailPage |
| `project-cover` | Project main image | Project |
| `video-thumb` | Video thumbnail | ProjectVideo |
| `blog-thumb` | Blog thumbnail | Blog |
| `home-profile-slider` | Profile section slider | SiteSetting |
| `home-highlight` | Project highlight card | SiteSetting |

---

## Database Field Types

### Image Paths
- **Varchar(255)**: Single image → `thumbnail_image`, `cover_image`
- **Text (JSON)**: Multiple images → `gallery_images` array

### Action Configuration
- **Varchar**: `thumbnail_click_action` → 'link' | 'lightbox'

### Example Stored Data
```php
// Thumbnail image
'thumbnail_image' => '/uploads/projects/project-detail-gallery-20260417142530-aBcDeF12.jpg'

// Gallery images (JSON array)
'gallery_images' => [
    '/uploads/projects/project-detail-gallery-20260417142530-aBcDeF12.jpg',
    '/uploads/projects/project-detail-gallery-20260417142531-XyZ98765.jpg',
    'hovi-custom-001.jpg'  // External theme asset
]

// Click action
'thumbnail_click_action' => 'lightbox'
```

---

## Image URL Resolution Examples

### Input → Output
```
'' (empty)
  → Use fallback (project cover or theme default)

'/uploads/projects/image.jpg'
  → /uploads/projects/image.jpg (as-is)

'http://example.com/image.jpg'
  → http://example.com/image.jpg (as-is, external)

'hovi-012.jpg'
  → /theme/assets/hovi/gallery/hovi-012.jpg (theme asset)

null
  → Use next fallback in chain
```

---

## Frontend Implementation TODO

### Lightbox Modal
- [ ] Implement lightbox library (e.g., GLightbox, Fancybox)
- [ ] Listen to click events with `data-action="lightbox"`
- [ ] Open modal with full-size image
- [ ] Support keyboard navigation (arrow keys, ESC)
- [ ] Support swipe gestures on mobile

### Image Hover Transforms
- [ ] Scale animation on hover
- [ ] Rotate effect
- [ ] Brightness/filter adjustment
- [ ] Apply to `.detail-gallery__item`
- [ ] Apply to project card images

### Gallery Navigation
- [ ] Keyboard arrow navigation
- [ ] Swipe between images on mobile
- [ ] Show slide counter (1/5)
- [ ] Previous/Next buttons

---

## Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| Image not showing | Wrong path in DB | Use image resolution function |
| Upload fails silently | File too large | Check max size validation |
| Preview doesn't load | File not accessible | Verify file permissions in public/ |
| Gallery empty | JSON array malformed | Check parseGalleryImages() function |
| Lightbox doesn't work | No JS library loaded | Integrate lightbox library |

---

## Testing Checklist

- [ ] Upload single image → verify in public/uploads/
- [ ] Upload multiple gallery images → verify JSON array
- [ ] Change thumbnail action to lightbox → verify DB update
- [ ] Change thumbnail action to link → verify URL resolves
- [ ] Test image fallback chain (gallery → thumbnail → cover)
- [ ] Test responsive images on mobile
- [ ] Test lazy loading (images load on scroll)
- [ ] Test image preview in admin form
- [ ] Test editor image upload integration
- [ ] Verify no broken image links

---

## Performance Considerations

1. **Lazy Loading**: Enabled on all gallery images except first
2. **Aspect Ratio**: CSS `aspect-ratio` used for consistent sizing
3. **Image Optimization**: Consider adding:
   - WebP format support
   - Image compression on upload
   - Responsive srcset attributes
   - CDN integration

4. **File I/O**: 
   - Images moved to public/, not stored in DB
   - Proper mime type validation
   - Random filename to prevent collisions

---

## API Routes Referenced

| Route | Purpose |
|-------|---------|
| `admin.editor.upload-image` | TinyMCE editor image upload |
| (Others auto-generated by Laravel) | CRUD operations |

