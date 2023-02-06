<?php

spl_autoload_register('load');
function load($classname): void
{
  $file = str_replace('GeekBrains\\LevelTwo\\', 'src\\', $classname) . '.php';
  $load = str_replace('\\', '/', $file);
  // Нужно src/Person/Name.php
  if (file_exists($load)) {
    include_once $load;
  }
}

spl_autoload_register(function ($classname) {
  $file = str_replace('GeekBrains\\LevelTwo\\', 'src\\', $classname);
  $load = str_replace('\\', DIRECTORY_SEPARATOR, $file) . '.php';;
  if (file_exists($load)) {
    require $load;
  }
});
