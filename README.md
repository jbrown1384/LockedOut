<p align="center">LockedOut</p>

<p align="center">
<a href="http://18.222.232.116/?tiles=10" alt="Build Status">Working Instance</a>
</p>

## Stack

- AWS t.2 micro
- Ubuntu 18.04 (https://help.ubuntu.com/lts/installation-guide/)
- nginx version: nginx/1.14.0 (https://www.nginx.com/resources/wiki/start/topics/tutorials/install/)
- PHP 7.4.1 
- Laravel Framework 6.10.1

## Deployment

- Checkout this repo into the instance root directory
- run sudo ./scripts/deploy.sh 
    - this will run composer, npm, and will rebuild app.js/app.css

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.
