# WordPress | Bedrock - Local development environment with Docker

Images used for the services:

- Wordpress: **wordpress:php8.0-fpm**
- Nginx: **nginx:stable-alpine**
- MariaDB: **mariadb:10.2**
- PhpMyAdmin: **phpmyadmin/phpmyadmin**

`wp-cli` and `xdebug` support included.

**For custom domain and ssl support, use `feature/ssl_custom_domain` branch. See the readme on that branch for user
guide.**

## Setup

1. Create `.env` file in root folder. See the example. Change the app name only!

2. Create `wordpress` folder in root. Bedrock will be installed here.

3. Run `docker-compose up --build`. There are useful shell commands in `bin` folder.

4. Create a new Bedrock project:

`bin/composer create-project roots/bedrock`

5. Unfortunately, Bedrock will create the project into a subfolder inside the `wordpress` folder, so you need to
   copy the subfolder content into the `wordpress` folder. And delete the subfolder afterwards.

6. Make sure the `.env` credentials are correct (in wordpress folder) and match with the variable defined in `.env` in
   the root folder
   You can customize the DB_PREFIX to increase security (See "Change table prefixes" section further below).

## WP CLI

Run `bin/wp` to enter the `wordpress` container. Simply run `wp`.
The wp-cli is run by the www-data user, not by root (otherwise, it would be a huge security risk).
## DB

Add your sql dump files into the db folder to be imported.

**Recommended sql import:**

Copy the sql file inside the mysql container:

`docker cp ./db/dump.sql container_id:/var/dump.sql`

After that, import the sql to the database (inside the mysql container):

`mysql -u wordpress -p wordpress  < /var/dump.sql`

Replace urls:

`wp search-replace 'https://example.com' 'http://localhost:8080/wp' --skip-columns=guid`

Or use PhpMyAdmin for a small database.

## PHP 8.0, 8.1

There are deprecation and other warnings!

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

*TODO: More work on these issues:*
You may need to change permissions for wordpress folder. There are still permission issues.

However, for production, the folders use the permission 755, and 644 for the files! 777 is a very bad and dangerous idea for production.


## Change Language

In composer.json changer these packages:
```
"koodimonni-language/hu_hu": "*",
"koodimonni-plugin-language/woocommerce-hu_hu": "6.7.0",
```
to your language and versions.
See more at: https://wp-languages.github.io/ and https://discourse.roots.io/t/install-update-wordpress-languages-with-composer/2021

Copy the needed files to `wordpress/app/languages`. Also create themes and plugins subfolders, and copy the right files in the appropriate place.

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




    
