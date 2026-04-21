# Gallo Pinto based project
This repository is based on a custom upstream created by ParallelDevs.


## New project setup (do this once per Pantheon instance)

When creating a new site from this upstream, the Pantheon site will have a different UUID than the one stored in `config/sync/system.site.yml`. You need to sync it once so all developers inherit the correct UUID automatically.

**1. Get the UUID from Pantheon:**
```shell
terminus drush <site-name>.dev -- config-get system.site uuid
```

**2. Update `config/sync/system.site.yml` with the UUID from above:**
```yaml
uuid: <uuid-from-pantheon>
```

**3. Delete orphan Shortcuts on Pantheon:**
```shell
terminus drush <site-name>.dev -- eval "\Drupal::entityTypeManager()->getStorage('shortcut_set')->load('default')->delete();"
terminus drush <site-name>.dev -- cim -y
```

**4. Commit and push the UUID fix:**
```shell
git add config/sync/system.site.yml
git commit -m "Fix site UUID to match Pantheon instance"
git push
```

From this point on, no developer will need to worry about the UUID or shortcuts on Pantheon again.

---

## Getting Started

This project uses [DDEV](https://ddev.readthedocs.io/en/stable/) for local development.

### Requirements
- [DDEV](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/)
- [Terminus](https://docs.pantheon.io/terminus/install) (authenticated with your Pantheon account)

### First time setup

**1. Start DDEV**
```shell
ddev start
```

**2. Download and import the database from Pantheon**
```shell
terminus backup:get gallopinto-test.dev --element=db --to=/tmp/pantheon-db.sql.gz
ddev import-db --file=/tmp/pantheon-db.sql.gz
```

**3. Delete orphan Shortcuts**
```shell
ddev drush eval "\Drupal::entityTypeManager()->getStorage('shortcut_set')->load('default')->delete();"
```

**4. Import configuration**
```shell
ddev drush cim -y
```

**5. Clear cache**
```shell
ddev drush cr
```

Your site is now available at **https://gallopinto-test.ddev.site**

---

### Theme setup

To build the theme, use [nvm](https://github.com/nvm-sh/nvm) to ensure the correct Node version:
```shell
cd web/themes/custom/gallopinto && nvm use
npm run build-storybook
ddev drush cr
```

### settings.local.php

Create a `settings.local.php` file in `web/sites/default/` with the following:
```php
$config['config_split.config_split.local']['status'] = TRUE;
```
This tells config_split to use the local environment overrides.

---

## Local development

### Code standards
We follow Drupal best practices enforced via PHPCS. To run code checks:
```shell
sh ./scripts/check-phpcs.sh
```

### XDebug
```shell
ddev xdebug on
ddev xdebug off
```

---

## Development workflow

1. Start clean from the upstream's integration branch

    ```shell
    git checkout main
    git fetch origin
    git rebase origin/main
    ```

2. Create a new feature branch

    ```shell
    git checkout -b ABC-000-feature-branch
    ```

3. Start the environment

    ```shell
    ddev start
    ```

### To create a Pull Request

1. Export your Drupal config changes:

    ```shell
    ddev drush cex
    ```

2. Commit your changes:

    ```shell
    git status
    git add -p
    git commit -m "ABC-000: Committing new changes to site."
    ```

3. Pull the latest changes from the integration branch:

    ```shell
    git pull --rebase origin main
    ```

4. Push your branch and open a Pull Request on GitHub.

    ```shell
    git push -u origin ABC-000-feature-branch
    ```
