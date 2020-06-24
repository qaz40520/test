## Prepare

go to below sites to apply social login service

https://developers.facebook.com/products/facebook-login/

https://developers.line.biz/zh-hant/

## Installation

<pre><code>cp .env.example .env
</code></pre>

edit your .env file, put facebook, line info
like : 

<pre><code>// Facebook redirect url must use SSL
// in this case, I use LaraDock
// Laradock/nginx/sites/{your_config_file} **#For https** this section, remove comment then you got https
FB_CLIENT_ID={fb_client_id}
FB_CLIENT_SECRET={fb_client_secret}
FB_REDIRECT={your_domain}/user/auth/fb-callback

LINE_CHANNEL_ID={line_channel_id}
LINE_CHANNEL_SECRET={line_channel_secret}
LINE_REDIRECT={your_domain}/user/auth/line-callback
</code></pre>

Install Laravel

<pre><code>composer install
php artisan key:generate</code></pre>

Install auth
<pre><code>php artisan ui vue --auth  //question reply no
npm install && npm run dev
php artisan migrate
</code></pre>

## Socail Login

go to `/login` page will see two button, fb and line login

click button will go to their auth page then callback to login

check users table, should be:

|id|name|email|email_verfied_at|password|remember_token|created_at|updated_at|facebook_id|line_id|
|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|
|1|JasonYang|example1@gmail.com|NULL|password_hash|NULL|2020-06-23 01:01:01|2020-06-23 01:01:02|11111|NULL|
|2|Foo|example2@gmail.com|NULL|password_hash|NULL|2020-06-23 01:01:01|2020-06-23 01:01:02|NULL|22222|
|3|Bar|example2@gmail.com|NULL|password_hash|NULL|2020-06-23 01:01:01|2020-06-23 01:01:02|123456789|987654321|
|4|Test|example3@gmail.com|NULL|password_hash|NULL|2020-06-23 01:01:01|2020-06-23 01:01:02|NULL|NULL|
|...|...|...|...|...|...|...|...|...|...|...|


## Weather Crawler

use command `php artisan weather:get` will catch `https://www.cwb.gov.tw/V8/C/W/week.html` this page content about one week weather and save in weather、weather_detail these two table

weather table
|id|region|
|:-:|:-:|
|1|基隆市|
|2|臺北市|
|...|...|

weather_detail table
|id|region_id|date|temperature|overview|
|:-:|:-:|:-:|:-:|:-:|
|1|1|2020/06/23|28 - 35|多雲時晴|
|2|1|2020/06/24|29 - 34|晴午後短暫雷陣雨|
|...|...|...|...|...|

## Some Issue

some issue from Laradock

there's a issue about mysql

PLEASE notice Laradock's .env file , their mysql section defined default root user, default user, 

when you clone a laravel package, I have some suggestion to your mysql section in laravel .env file 
<pre><code>
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE={your_db}
DB_USERNAME={your_db_user}
DB_PASSWORD={your_db_user_pw}

// replace DB_HOST to

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE={your_db}
DB_USERNAME={your_db_user}
DB_PASSWORD={your_db_user_pw}</code></pre> 

and make sure your db user can access to db
