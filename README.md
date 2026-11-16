# De Bright College of Health Science and Technology website

Laravel site with a sliding homepage banner, course pages, admissions info, gallery, news, an enquiry form and an admin panel.
It uses plain CSS and JavaScript, so there is no `npm` or build step. That keeps it simple on shared hosting.

## Run it on your computer

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed      # prints the admin password once
php artisan serve
```

Open http://localhost:8000. The admin panel is at http://localhost:8000/admin.

To choose your own admin login, set `ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env` **before** running `migrate --seed`.

Run the tests with `php artisan test`.

## Where to change things

| What | Where |
|---|---|
| Phone numbers, address, motto, entry requirements, WhatsApp number, email | `config/school.php` |
| Homepage sliding images, gallery photos | Admin → Slider / Gallery |
| Courses, news posts | Admin → Courses / News |
| Messages from the Contact page | Admin → Enquiries |
| Colours and layout | `public/css/site.css` |
| About page text | `resources/views/pages/about.blade.php` |

## Put it on live hosting (cPanel)

1. On your computer run `composer install --no-dev --optimize-autoloader`, then upload the whole project to a folder **above** `public_html` (for example `desky-app`).
2. Copy everything inside the project's `public/` folder into `public_html`.
3. Open `public_html/index.php` and change the two paths so they point to the app folder:
   `__DIR__.'/../desky-app/vendor/autoload.php'` and `__DIR__.'/../desky-app/bootstrap/app.php'`
   (also the `storage/framework/maintenance.php` line).
4. In cPanel create a MySQL database and user. Copy `.env.example` to `.env` inside `desky-app` and set:
   `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://yourdomain`, `DB_CONNECTION=mysql` and the `DB_*` values.
5. Run `php artisan key:generate` and `php artisan migrate --seed --force` (cPanel Terminal or SSH).
6. Make sure `storage`, `bootstrap/cache` and `public_html/uploads` are writable (permission 775).
7. Log in at `/admin` and change the admin password.

Uploaded slider and gallery images are saved in `public/uploads` and are not overwritten when you update the code.
