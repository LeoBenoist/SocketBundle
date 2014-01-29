Getting Started With LeoBenoist SocketBundle
===========================================

A bundle to mange Socket.Io on Symfony 2 with less javascript as possible.

Be Advice this bundle is in developement. Do not use in production.

## Prerequisites

    Symfony 2.1+ 
    FOSUserBundle
    NodeJS (no knowledge required)

## Supported browsers
Desktop

    Internet Explorer 5.5+
    Safari 3+
    Google Chrome 4+
    Firefox 3+
    Opera 10.61+

Mobile

    iPhone Safari
    iPad Safari
    Android WebKit
    WebOs WebKit



## Installation

Installation is a quick 3 step process:

1. Download LeoBenoistSocketBundle using composer
2. Enable the Bundle
3. Configure your config.yml

### Step 1: Download SocketBundle using composer (not yet available, waiting stable version)

Add SocketBundle in your composer.json:

```js
{
    "require": {
        "leobenoist/socketbundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update leobenoist/socketbundle
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new LeoBenoist\SocketBundle\LeoBenoistSocketBundle(),
    );
}
```

### Step 3: Configure your config.yml

``` yaml
# app/config/config.yml

leobenoist_socket:
    client:
        hostname: localhost
        port: 1337
    server:
        hostname: localhost
        port: 1337
```

## Using SocketBundle.

### Client Side
The first step is to establish a connection between you client and socket.io (our real time server).

To do that simply add this code on every page you want to have realtime or directly in you base.html.twig.


``` html+jinja
# app/Ressources/views/base.html.twig

{% if is_granted("IS_AUTHENTICATED_REMEMBERED") %}
  {{ leobenoistSocketConnect()|raw }}
{% endif %}
```

#### Subscribe client to a label
Every update you send will labeled. You havee to say on what page, what update you need.

``` html+jinja
# src/SomeRandomBundle/Ressources/views/base.html.twig

{{ leobenoistSocketRegisterLabel('yourLabel', 'yourJavacriptFunction', 'html-ajax')|raw }}
``` 
or

``` html+jinja
# src/SomeRandomBundle/Ressources/views/base.html.twig

{{ leobenoistSocketRegisterLabel('yourLabel' ~ app.user.id, 'yourJavacriptFunction', 'html-ajax')|raw }}
``` 

##### What's about security
When you are using this code many things happen, Symfony 2 Backend send to you node js the information that an user will subscription to a specific labal and with this uid. The backend generate the corresponding javascript to allow the clien to connect to the real time server. When the client browser receive this javascript it ask to be registered for this label to the real time server. The real time server check if symfony previouly grant this user and if it's ok the user is granted to the label.

### Server Side

Easy as possible :

``` php
// in a controller or a service

//Get the service 
$socket = $this->container->get('leobenoist_socket.service');

// Send a raw basic update
$socket->sendResponseForLabel('yourLabel', '{your data}');


// Send a symfony response object update
$response = $this->render(
    'YourRandomBundle:YourFolde:yourView.html.twig',
    array(
        'data' => $data,
    )
);

$socket->sendResponseForLabel('yourLabel', $response);


``` 

That was easy :) No ?

## Launch the real time server

```
node server.js
```

## Configuration with https and nginx exemple. 

``` conf
server {
    listen      80;
    server_name yourdomain.com www.yourdomain.com;
    rewrite     ^   https://www.yourdomain.com$request_uri? permanent;
}

server {
    listen 443 ssl;
    server_name www.yourdomain.com;
    root /var/www/yourdomain.com/www/web;

    ssl                   on;
    ssl_certificate      /etc/nginx/ssl/yourdomain.com.chained.crt;
    ssl_certificate_key  /etc/nginx/ssl/www.yourdomain.com.key;

    if ($host !~* ^www\.){
        rewrite ^(.*)$ https://www.yourdomain.com$1;
    }

    location / {
        # try to serve file directly, fallback to rewrite
        try_files $uri @rewriteapp;
    }

    location @rewriteapp {
        # rewrite all to app.php
        rewrite ^(.*)$ /app.php/$1 last;
    }

    location /socket.io {
        proxy_pass http://localhost:1337;

        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;

        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto https;

        proxy_redirect off;
    }

    location ~ ^/(app)\.php(/|$) {
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS on;
    }

    error_log /var/log/nginx/www.yourdomain.com_error.log;
    access_log /var/log/nginx/www.yourdomain.com_access.log;
}
```


## TODO:
Better doc, correction improvment, better english

Socket communication between Symfony and Node

Make bundle available thought composer









