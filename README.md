cd ~/src/laravel/rhino
php artisan serve

(on browser: http://127.0.0.1:8000)


Ref: https://laravel-excel.maatwebsite.nl/3.1/getting-started/installation.html
composer require maatwebsite/excel
php artisan vendor:publish
#composer require phpoffice/phpspreadsheet


php artisan make:controller UploadXlsController