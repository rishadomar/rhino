Project:
===

Read an xls file and validate data

Developer's notes:
---

1) For live:
ssh root@206.189.250.161 // Droplet on Digital Ocean
http://e39854b2.ngrok.io/

Used ngrok to make public:

 * nohup ./ngrok http 80 & # run in background
 * curl localhost:4040/status | grep http # find the url to publish
 * http://e39854b2.ngrok.io/

2) On development
---

```
cd ~/src/laravel/rhino
php artisan serve
Load on browser: http://127.0.0.1:8000
```

```
composer require maatwebsite/excel
composer require giggsey/libphonenumber-for-php
php artisan vendor:publish
```

