<p align="center">LockedOut</p>

## Stack

- AWS t.2 micro
- Ubuntu 18.04 (https://help.ubuntu.com/lts/installation-guide/)
- nginx v1.14.0 (https://www.nginx.com/resources/wiki/start/topics/tutorials/install/)
- PHP v7.4.1 
- Laravel Framework v6.10.1
- composer v1.9.1
- node v8.10.0
    - npm v3.5.2
- git v2.17.1


## Deployment
- Checkout this repo into the instance root directory
- composer and nodejs/npm can be installed individually or you can run the deploy script which will auto install and create the dependencies 
    - To run the deploy script: sudo bash ./public/scripts/deploy.sh 
    - this will install and run composer and npm

## Quick Deployment commands for setting up full environment
- sudo apt update
- sudo apt install nginx -y
- sudo apt install software-properties-common -y
- sudo add-apt-repository ppa:ondrej/php -y
- sudo apt update
- sudo apt install php7.4-fpm php7.4-common php7.4-mysql php7.4-xml php7.4-xmlrpc php7.4-curl php7.4-gd php7.4-imagick php7.4-cli php7.4-dev php7.4-imap php7.4-mbstring php7.4-opcache php7.4-soap php7.4-zip unzip -y
- sudo nano /etc/php/7.4/fpm/php.ini
- sudo service php7.4-fpm restart
- sudo nano /etc/nginx/sites-available/lockedOut

### Enter this configuration into the lockOut file, remember to update your public IP into the server_name field
```
server {
    listen 80;
    listen [::]:80;
    root /var/www/html/LockedOut/public;
    index  index.php index.html index.htm;
    server_name {ENTER_PUBLIC_IP_HERE};

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
       include snippets/fastcgi-php.conf;
       fastcgi_pass             unix:/var/run/php/php7.4-fpm.sock;
       fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}

```
- sudo ln -s /etc/nginx/sites-available/lockedOut /etc/nginx/sites-enabled/
- sudo service nginx restart
- cd /var/www/html
- sudo apt install git -y
- sudo git clone https://github.com/jbrown1384/LockedOut.git
- cd LockedOut/
- sudo chown -R www-data:www-data /var/www/html/LockedOut/
- sudo chmod -R 755 /var/www/html/LockedOut/
- sudo bash ./public/scripts/deploy.sh
- sudo cp .env.example .env

## Application
### Auto-generating the tiles
- On page load, tiles will be randomly generated and a possible solution will attempt to be found. 
- The default number of tiles is 3 but can be changed through the URL get parameter

### Manual Entering Tiles
- The "Manual Input Toggle" allows the user to enter individual tiles. Each row should contain both colors eg(blue, green). The add row will allow another tile to be generated and added. 
- Upon submitting the add tile, the tiles will be displayed on the previous screen along with a possible solution.
