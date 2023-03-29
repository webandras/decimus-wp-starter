# Decimus Theme Admin Plugin

**TODO: migrate to Vue.js version 3**, because version 2 will reach its end-of-life at the end of this year!

## admin/

The theme settings dashboard's code. It is built with Vue.js.

Run local dev server:

```bash
npm run serve
```

It generally runs at `http://localhost:8081` because 8080 is already in use. if not, change in the 
`Guland\DecimusAdmin\Core\AdminPage` trait's `add_admin_scripts()` method!

Build for production (also change `NODE_ENV` in `.env` to production!):

```bash
npm run build
```

Currently, the production bundles have hashes in the filenames, so you need to change it in the
`Guland\DecimusAdmin\Core\AdminPage` trait's `add_admin_scripts()` method!

TODO 2: Vue.js 3 should have a vue.config.js file having **`filenameHashing` set to false** in order to disable the hashes.
This setup currently does not have a manual config.


### Add new sub-settings page (frontend):

1. Register a new route in `app-routes.js`!
2. Add a new navigation list item in `src/components/global/AdminNavigation.vue`!
3. Add a new page in `src/pages/`!
4. Create a new "Service" for that page in `src/services/` and use the `BaseServiceMixin`!

## backend/

The PHP code is here (OOP and fully namespaced).

### Add new sub-settings page (backend):

#### API routes

1. Create controller class in Admin and in Frontend sub-folders in the `backend/API/` folder!
2. The controllers should extend the `\Guland\DecimusAdmin\API\ParentController` class!
3. Frontend controllers should only contain GET endpoints (for non-sensitive data)!
4. Add property and use dependency injection for the new controllers in the constructor in `Guland\DecimusAdmin\DecimusAdmin` class!
5. In `register_all_endpoints()` method register the new routes / endpoints!

#### Database updates, seeding

1. For database updates, create a "migration" trait in the `backend/Database/Migration/` folder!
2. For seeding with new values, add a seeder trait in the `backend/Database/Seeder/` folder!
3. Use these traits in `Guland\DecimusAdmin\DecimusAdmin` class:
4. Add a condition in the DB_VERSION for loop (_from line 176_) when your migrations and seeds should run like this:
  
```php
if ( !array_search(1002, $migrations_run_already, true) ) {
    // call the trait method to run the migration (if you have one)
    // ...
    // call the trait method to run the seeder (if you have one)
    self::seed_theme_options_table_003();

    // update migrations run array (it stores the version numbers for which the migrations, seeders have already completed.
    self::update_migrations_run(1002);
}
```

5. **Increment `DB_VERSION` class constant before running the plugin's code!**


