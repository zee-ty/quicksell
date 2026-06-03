# QuickSell

QuickSell is a simple PHP/MySQL C2C marketplace web app built for local development using XAMPP.

## Setup

1. Start XAMPP services:
   - `Apache`
   - `MySQL`

2. Import the database schema:
   - Open phpMyAdmin at `http://localhost/phpmyadmin/`
   - Create or select a database named `quicksell`
   - Import `quicksell_schema.sql` from the repository root

3. Run the app in your browser:
   - `http://localhost/quicksell/quicksell/code/`

4. Confirm database connection settings in `code/db.php`:
   - host: `localhost`
   - db: `quicksell`
   - user: `root`
   - pass: ``

## Notes

- The app entry point is `code/index.php`
- Uploaded listing images are stored in `code/uploads/`
- The web app folder is `code/`
- Use `code/import_quicksell_schema.php` only once to import the schema if needed
- Use `code/add_quicksell_samples.php` to insert sample listings only once

## File layout

- `code/` — application source files
- `code/db.php` — database connection and session helpers
- `code/index.php` — public homepage
- `code/listing.php` — single listing details
- `code/login.php`, `code/register.php`, `code/logout.php`
- `code/sell.php` — create new listing
- `code/admin/` — admin dashboard and user management
- `code/uploads/` — listing image files

## Troubleshooting

- If you receive a 404, make sure the URL matches the actual folder path.
- If images do not display, verify the file exists in `code/uploads/` and the listing `image` field is set in the database.
- If the app cannot connect to MySQL, update `code/db.php` with your local MySQL credentials.
