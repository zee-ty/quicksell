# QuickSell – C2C E-commerce Platform
## Setup

1. Import `quicksell_schema.sql` into MySQL (phpMyAdmin or CLI).
2. Drop the `code/` folder into your web server (XAMPP / cPanel public_html).
3. Open in your browser: `http://yourdomain/` or hosted URL.

## Default admin login (after you reset the password hashes)

- Email: `admin@quicksell.co.za`
- Password: re-hash with `password_hash('your_pass', PASSWORD_DEFAULT)`

## Folder structure

```
code/
├── db.php              # DB connection + session helpers
├── index.php           # Public homepage
├── register.php
├── login.php
├── logout.php
├── listing.php         # Single listing view
├── sell.php            # Create a listing (sellers only)
├── admin/
│   ├── index.php       # Admin dashboard (RBAC protected)
│   └── users.php       # User CRUD with role management
└── assets/
    ├── style.css
    └── main.js
```

## Roles (RBAC)

| Role         | Permissions                                  |
|--------------|----------------------------------------------|
| super_admin  | All access incl. roles                       |
| admin        | Manage users, listings, categories           |
| moderator    | Review listings/reports                      |
| seller       | Post and edit own listings                   |
| buyer        | Browse and purchase                          |

## Hosting
