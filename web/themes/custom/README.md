
# Guide to Install a custom theme using Emulsify 5 in Drupal

This document outlines the steps required to install and configure Emulsify 5 as a theme in a Drupal project, including the installation of dependencies and additional tools.

---

## Installation Steps

### 3. Initialize the theme
Generate a custom theme by running:
```bash
lando drush emulsify [theme_name]
```
This will create a folder in `themes/custom`.

### 4. Enable the theme as default
Activate the theme and set it as the site's default theme:
```bash
lando drush theme:enable [theme_name] && lando drush config:set system.theme default [theme_name]
```

### 5. Install theme dependencies
Navigate to the newly created custom theme folder and run:
```bash
nvm use && npm install
```
### 6. Configure an Emulsify system
To use a predefined Emulsify system, install it by running:
```bash
emulsify system install compound
```
If the system is not available, list the available options with:
```bash
emulsify system list
```

### 7. Install the components
Add all required components to the theme with (optional):
```bash
emulsify component install --all
```
If you want to add specific components based on the project's requirements, you can view the list of available components with:
```bash
emulsify component list
```
To install a specific component, use the following command:
```bash
emulsify component install [component_name]
```

### 8. Run Storybook
Start Storybook to work with visual components by running:
```bash
npm run develop
```
---

## References
For more details, check the official documentation:
[Emulsify (Drupal) • Emulsify Docs](https://www.emulsify.info/docs/emulsify-drupal)
