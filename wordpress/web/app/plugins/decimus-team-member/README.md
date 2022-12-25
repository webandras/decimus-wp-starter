# Decimus Team Members plugin for WordPress

The plugin handles team members in a custom table, CRUD functionality implemented.

## You can paste in the table or list of the members using the shortcode

- `[decimus_team_members]`

The default view is list, but you can change it with the `type` attribute:

- `[decimus_team_members type="table"]`, or
- `[decimus_team_members type="list"]`

You can also enable or disable specific fields to be shown:

`[decimus_team_members type="list" email="false" works_since="true"]`


The full list of available options with their default values:

````
'type'              => 'list' / 'table',
'name'              => true,
'first_name_first'  => false,
'photo'             => true,
'phone'             => true,
'email'             => true,
'position'          => true,
'department'        => false,
'works_since'       => false,
````

The `first_name_first` attribute determines whether the first name will come before the last name, or the other way around.
In some languages the last name (family name) is written before the first name.

TODO list:
- create a widget to be used in sidebars
- improve form validation in admin menu



                    


