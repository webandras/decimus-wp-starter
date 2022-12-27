# WordPress | Bedrock - Local development environment with Docker

Images used for the services:

- Wordpress: **wordpress:php8.0-fpm**
- Nginx: **nginx:stable-alpine**
- MariaDB: **mariadb:10.2**
- PhpMyAdmin: **phpmyadmin/phpmyadmin**

`wp-cli` and `xdebug` support included.

**For custom domain and ssl support, you will find the instructions at the end of this readme file.**

## Setup

1. Create `.env` file in root folder. See the example. Change the app name only!

2. Create `wordpress` folder in root. Bedrock will be installed here.

3. Run `set- a; source .env; docker-compose up --build`.

4. Create a new Bedrock project:

`bin/composer create-project roots/bedrock`

5. Unfortunately, Bedrock will create the project into a subfolder inside the `wordpress` folder, so you need to
   have the sub-folder content in the `wordpress` folder.

6. Make sure the `.env` credentials are correct (in wordpress folder) and match with the variable defined in `.env` in
   the root folder.

You can also customize the DB_PREFIX to increase security.
See "Change table prefixes" section further below to change prefix for tables in an existing wp database.

## WP CLI

Run `bin/wp` to enter the `wordpress` container. Simply run `wp` here.
The wp-cli is run by the www-data user, not by root (otherwise, it would be a security risk).

After testing some wp commands, it turns out that the user creation (wp user create ...) is not working and produces an
error.  TODO: need to find cause. Maybe caused by Bedrock.

*Note: There are other useful shell commands in `bin` folder as well.*

## DB

Add your sql dump files into the db folder to be imported.

**Recommended sql import:**

Copy the sql file inside the mysql container:

`docker cp ./db/dump.sql container_id:/var/dump.sql`

After that, import the sql to the database inside the mysql container:

`mysql -u wordpress -p wordpress < /var/dump.sql`

Replace urls:

`wp search-replace 'https://example.com' 'http://localhost:8080/wp' --skip-columns=guid`

Or use PhpMyAdmin for a small database.

*Note: `bin/mysql-import` is not working.*

## PHP 8.0/8.1

There are deprecation and other warnings which will be eventually fixed in future WordPress and Bedrock releases...

## Change table prefixes

Generate queries for all tables:

```sql
SET
@database  = "wordpress";
SET
@oldprefix = "wp_";
SET
@newprefix = "customwp_";

SELECT concat(
               "RENAME TABLE ",
               TABLE_NAME,
               " TO ",
               replace(TABLE_NAME, @oldprefix, @newprefix),
               ';'
           ) AS "SQL"
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = @database;
```

The code above resulted a list of sql queries. Run those:

```sql
RENAME
TABLE wp_actionscheduler_actions TO customwp_...
```

Very important: make sure to replace the prefixes everywhere needed (for example, `wp_user_roles`
-> `customwp_user_roles`)

```sql
UPDATE `customwp_usermeta`
SET meta_key = REPLACE(meta_key, 'wp_', 'customwp_')
WHERE meta_key LIKE 'wp_%';

SELECT *
FROM `customwp_options`
WHERE option_name LIKE 'wp_%';
```

## Other

This works better for Docker:
`wordpress/config/environments/development.php` -> `Config::define('FS_METHOD', 'direct');`

You may need to change permissions for wordpress folder. However, for production, use permission 755 for folders, and
644 for the files! 777 is a dangerous idea for production.

### Docker notes

For Linux, you may have to use sudo if your Docker is not configured to be used as a non-root user.
There are useful bash scripts in bin folder (bin/start, bin/down, etc.) which are useful.

## Change Language

This is a bit tricky for Bedrock, but it works.

In composer.json changer these packages with your preferred language set like this:

```
"koodimonni-language/hu_hu": "*",
"koodimonni-plugin-language/woocommerce-hu_hu": "6.7.0",
```

to your language and versions.
See more at: https://wp-languages.github.io/
and https://discourse.roots.io/t/install-update-wordpress-languages-with-composer/2021

Copy the needed files to `wordpress/app/languages`. Also create themes and plugins sub-folders, and copy the right files
in the appropriate place.

`wordpress/vendor/koodimonni-language`

`wordpress/vendor/koodimonni-plugin-language`

`wordpress/vendor/koodimonni-theme-language`

## Useful resources

- Create dummy data for testing:
  https://wpattire.com/tips/how-to-add-dummy-data-in-woocommerce/

- The WP theme used:
  https://github.com/bootscore

- Auto create woocommerce pages
  https://quadlayers.com/create-woocommerce-pages/

## Custom domain and ssl certificate

Steps:

1. Set up your .env variables in root-folder .env

2. Create SSL certificate for your `APP_DOMAIN`

First:

You may need a different executable for **mkcert**. Copy your mkcert executable into `.docker/nginx` folder. Links:

- Windows: https://github.com/FiloSottile/mkcert#windows
- Mac: https://github.com/FiloSottile/mkcert#macos
- Linux: https://github.com/FiloSottile/mkcert#linux or use the pre-build binaries (
  recommended): https://github.com/FiloSottile/mkcert/releases

Then run `bin/setup-ssl`.

3. Add domain alias for `127.0.0.1` (e.g. in `/etc/hosts`)

4. Make sure `server_name` (there is 2 of them), ssl_certificate, and ssl_certificate_key is correct
   in `.docker/nginx/default-ssl.conf`! Although, the script will replace these values with the `APP_DOMAIN` if the
   default values are `wordpress.local` `.docker/nginx/default-ssl.conf`.

5. Now you can build the docker project:

`(set -a;source .env;docker-compose -f docker-compose-ssl.yml up --build)`

Continue with step #4 at the **Setup section**.

    
