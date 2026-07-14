# Forms catalog CMS + PDF usage visibility

## Goals
1. Edit Forms page PDF links from Admin → Pages → Forms → Edit (no code deploy for link/label changes).
2. Show PDFs used on a page on that page’s edit screen.
3. Show site-wide PDF usage in Media Library (“Used on”).

## Approach
### A. `forms_catalog` section type
- Content shape: `groups[]` with `key`, `title`, `title_ur`, `subtitle`, `subtitle_ur`, `layout` (`list` | `accordion`), `items[]` with `label`, `label_ur`, `path`.
- Default groups match current Forms layout: Group Claim Forms, Participant Services, Individual Claim Forms.
- Frontend `forms.blade.php` renders CMS primary `forms_catalog` when present; otherwise hardcoded fallback.
- Seed via `php artisan cms:seed-page-sections forms --force`.

### B. Page Edit — “PDFs on this page”
- Collect paths from page sections (`pdf`, `pdf_table`, `forms_catalog`, `value_chain`, etc.) + page attachments.
- List label, path, open link, Media Library match when path exists in library.

### C. Media Library — “Used on”
- Scan page section JSON content for asset paths matching each library PDF path.
- Also include direct `page_id` attachments.
- Display page titles (linked to edit) on each PDF card.

## Out of scope
- Changing public Forms visual design beyond CMS-driven content.
- Auto-importing every disk PDF into Media Library (seed may register forms PDFs optionally).
