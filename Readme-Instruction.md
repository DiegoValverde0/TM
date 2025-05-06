git clone https://github.com/DiegoValverde0/TM.git
cd TM
composer install
cp .env.example .env
"editas en archivo .env y guardás los cambios en visual code"
php artisan key:generate
php artisan migrate
npm install
npm run dev
npm run build
php artisan serve