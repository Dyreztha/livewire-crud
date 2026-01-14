# Masuk ke folder project
cd C:\laragon\www\absensi

# Pull update
git pull origin main

# Install dependencies
composer install
npm install

# Migrate database
php artisan migrate

# Clear cache
php artisan optimize:clear

# Jalankan
npm run dev
php artisan serve

runningnya di localhost:8000/register

