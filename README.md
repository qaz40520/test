## Installation

<pre><code>cp .env.example .env
</code></pre>

edit your .env file, put facebook, line info
like : 

<pre><code>// Facebook redirect url must use SSL
// in this case, I use LaraDock
// LaraDock's .env file has 443 port option in Nginx , remove comment then you got https
FB_CLIENT_ID={fb_client_id}
FB_CLIENT_SECRET={fb_client_secret}
FB_REDIRECT={your_domain}/user/auth/fb-callback
FB_VERSION=v7.0

LINE_CHANNEL_ID={line_channel_id}
LINE_CHANNEL_SECRET={line_channel_secret}
LINE_REDIRECT={your_domain}/user/auth/line-callback
</code></pre>

Install Laravel

<pre><code>composer install
php artisan key:generate</code></pre>

Install auth
<pre><code>php artisan ui vue --auth
npm install && npm run dev
</code></pre>

## Socail Login

go to `/login` page will see two button, fb and line login

click button will go to their auth page then callback to login

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
