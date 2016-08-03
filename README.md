# Users

Users is a mass-mail web app built with laravel 5.2

# How to use it

1) Create database "users"

2) Change database credentials in the ".env" file

3) Run "php artisan migrate"

4) Create tables: "emails", "send"

# Table "emails" structure

id	int(11)	AUTO_INCREMENT

email	varchar(255) utf8_unicode_ci

# Table "send" structure

id	int(11)	AUTO_INCREMENT

from	varchar(255) utf8_unicode_ci

to	varchar(255) utf8_unicode_ci

subject TEXT utf8_unicode_ci

body TEXT utf8_unicode_ci

5) Set up cron to send emails

You also need to change the database credentials in the "send.php" file & the e-mail credentials!

Example: 0 */2 * * * user php {laravel_dir}/cron/send.php
