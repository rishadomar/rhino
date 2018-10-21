Project:
===

Read an xls file and validate data

A) Live:
---
ssh root@206.189.250.161 // Droplet on Digital Ocean
http://e39854b2.ngrok.io/

Used ngrok to make public:

 * nohup ./ngrok http 80 & # run in background
 * curl localhost:4040/status | grep http # find the url to publish
 * http://e39854b2.ngrok.io/

B) On development
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

C) Email sent to Rhino:
---

1) Try it out:
http://e39854b2.ngrok.io/

2) Code on github:
https://github.com/rishadomar/rhino.git  
If a problem accessing let me know and I will supply a zip

3) Code summary - it may be more interesting to just look at the following files (see attached):
 - routes/web.php
 - app/Rhino/User.php
 - app/Rhino/Contact.php
 - app/Http/Controllers/UploadXlsController.php
 - resources/views/welcome.blade.php
 
Implementation Notes:
---
 - I tried out Laravel and found it pretty nifty. I'm sure I've just scratched the surface and am very curious to learn about Vue and Laravel's other features.
 - Decided to show all invalid cells in red.
 - Added a checkbox to each row - could be useful for a user to fix and tick off as the user progresses through the list.

Improvements I can think of:
---
 - [] Handle refresh button more elegantly
 - [] Handle Exceptions more elegantly
 - [] Enable button only once a file has been selected
 - [] Use Laravel's Form validation to show only support of xlsx files
 - [] Clear list of users when new file is selected
 - [] Better use of style classes
 - [] Progress bar


