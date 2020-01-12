<p align="center">LockedOut</p>

<p align="center">
<a target="_blank" href="http://18.222.232.116/?tiles=10" alt="Build Status">Working Instance</a>
</p>

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
- composer and nodejs/npm can be installed individually or you can run the deploy script which will auto install and create the dependancies 
    - To run the deploy script: sudo bash ./public/scripts/deploy.sh 
    - this will install and run composer and npm

## Application
### Auto-generating the tiles
- On page load, tiles will be randomly generated and a possible solution will attempt to be found. 
- The default number of tiles is 3 but can be changed through the URL get parameter

### Manual Entering Tiles
- The "Manual Input Toggle" allows the user to enter individual tiles. Each row should contain both colors eg(blue, green). The add row will allow another tile to be generated and added. 
- Upon submitting the add tile, the tiles will be displayed on the previous screen along with a possible solution. 
