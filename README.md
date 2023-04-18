# Gallo Pinto based project
This repository is based on a custom upstream created by ParallelDevs.


## Getting Started
This projects uses [Lando](https://docs.lando.dev/getting-started/), if you are pulling this project from pantheon, it is a good idea to run the following commands in order to sync your local environment with the live environment.

To start the site:
```shell
  lando start
```
Clear the cache of the dev site (using a `drush cr` via terminus) and create a new backup. Then download the database, and import it using:
```shell
  lando db-import PATH_TO_THE_FILE
```
### Note:
If you are not pulling this project from Pantheon.io, after importing the database, run:
```
  lando composer install
```
Then, you might need to build the theme. To do so, we recommend using [nvm](https://github.com/nvm-sh/nvm) to have an standard node version for the project.
```shell
  cd web/themes/custom/gallopinto && nvm use
```
Once you're using the recommended node version, run:
```shell
  npm run build-storybook && lando drush cr
```

### Important:
It is necessary to create a `settings.local.php` file with the following line:
```php
  $config['config_split.config_split.local']['status'] = TRUE;
```
That line lets the config split module know that this is a local environment and should use the config overrides.

---

## Local development

### Code standards
We follow Drupal best practices. These are enforced using PHP_CodeSniffer via PHPCS. To run the code checks, execute the following command:
```shell
sh ./scripts/check-phpcs.sh
```

### XDebug
To enable XDebug, execute:
```shell
lando xdebug-on
```
Once you have finished using XDebug, you can disable it by running:
```shell
lando xdebug-off
```

---

## Development workflow
This section describes the commands required to start development for work that is part of the normal release cycle.

1. Start clean from the upstream's integration branch

    ```shell
      git checkout main
      git fetch origin
      git rebase origin/main
    ```

2. Create a new feature branch from `main`

    ```shell
      git checkout -b ABC-000-feature-branch
    ```

3. If you have not already, start the environment

   ```shell
   lando start
   ```
### To Create a Pull Request

1. After you make changes inside your local drupal site, export your configuration from the database to your configuration.
   Export your drupal config changes if you have them.

   ```shell
   lando drush cex
   ```

2. Commit your changes.

   ```shell
   git status
   git add -p
   git commit -m "ABC-000: Committing new changes to site."
   ```

3. Pull the latest changes from integration branch.

   ```shell
   git pull --rebase origin main
   ```

4. Push your branch to the repository.

   ```shell
   git push -u origin ABC-000-feature-branch
   ```

5. Go to the site repository on GitHub, and create a new pull request with the new changes.
