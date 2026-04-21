<?php
/**
 * Setup inicial para nuevas instancias creadas desde el upstream.
 *
 * Resuelve el UUID y elimina los shortcuts antes del primer cim.
 *
 * Uso:
 *   terminus drush <site>.<env> -- php-script scripts/site-setup.php
 *
 * Luego ejecutar:
 *   terminus drush <site>.<env> -- cim -y
 *   terminus drush <site>.<env> -- cim -y
 *   terminus drush <site>.<env> -- cr
 */

// 1. Leer UUID real desde la base de datos de esta instancia.
$db_uuid = \Drupal::config('system.site')->get('uuid');
echo "UUID del sitio: {$db_uuid}\n";

// 2. Escribir ese UUID en el sync storage para que cim no falle.
$sync = \Drupal::service('config.storage.sync');
$site_config = $sync->read('system.site');

if ($site_config === FALSE) {
  echo "ERROR: No se pudo leer system.site desde el sync storage.\n";
  exit(1);
}

$site_config['uuid'] = $db_uuid;
$sync->write('system.site', $site_config);
echo "UUID actualizado en sync storage.\n";

// 3. Eliminar shortcuts para que cim no falle por entidades huérfanas.
$shortcut_storage = \Drupal::entityTypeManager()->getStorage('shortcut_set');
foreach ($shortcut_storage->loadMultiple() as $set) {
  $set->delete();
  echo "Shortcut eliminado: {$set->id()}\n";
}

echo "\n Todo listo. Ahora ejecuta:\n";
echo "  terminus drush <site>.<env> -- cim -y\n";
echo "  terminus drush <site>.<env> -- cim -y\n";
echo "  terminus drush <site>.<env> -- cr\n";
