#!/bin/bash
./vendor/bin/phpcs --standard=Drupal --extensions=php,module,theme,install,inc,yml --ignore=node_modules,vendor,core,contrib,sites ./web

# Check if there were any errors
if [ $? -eq 0 ]
then
  echo "PHPCS ✅"
else
  # Prevent any following commands from running
  exit 1
fi
