# Op Return analysis framework

This is a quick application built with the Laravel framework that watches the last 30 days of blockchain transactions in order to determine the fees paid for every transaction, and if the average fees paid for different kinds of transactions are different.

# Installation Instructions
To install on your server, clone this git repo to a folder and edit your apache or nginx config to point to the public directory

Then edit your crontab file to call
* * * * * php /var/www/websitefolder/artisan schedule:run >> /dev/null 2>&1

This will cause the app to keep itself updated.
