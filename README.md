Trans_SID (Session IDs in URLs) for Laravel 5 Projects
======================================================

This module adds support for keeping session IDs (as normally stored in session cookies) to all URLs.
This is especially useful when adding a Javascript to an Iframe as some browser block cookies in those.

Installation
------------

1. Install `imi/laravel-transsid` via composer.
2. In your `config/app.php` at `providers` replace 
    `'Illuminate\Session\SessionServiceProvider'` with `iMi\LaravelTransSid\SessionServiceProvider` 
3. add `iMi\LaravelTransSid\UrlServiceProvider` in the providers array

About Us
========

[iMi digital GmbH](http://www.imi.de/) offers Laravel related open source modules. If you are confronted with any bugs, you may want to open an issue here.

In need of support or an implementation of a modul in an existing system, [free to contact us](mailto:digital@iMi.de). In this case, we will provide full service support for a fee.

Of course we provide development of closed-source moduls as well.