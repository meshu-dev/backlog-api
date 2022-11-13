# Running tests

Use the following commands in the terminal to generate table, seeed test data

**Create database**

`php bin/console --env=test doctrine:database:create`

**Run migrations**

`php bin/console --env=test doctrine:schema:create`

**Run fixtures to add test data**

`php bin/console --env=test doctrine:fixtures:load`

**Drop database**

`php bin/console --env=test doctrine:database:drop`
