<?php

namespace App\Helpers\Routes;
class RouteHelper {
    public static function importRouteFiles ($routesPath) {
        $dirIterator = new \RecursiveDirectoryIterator($routesPath);

        /** @var \RecursiveDirectoryIterator | \RecursiveIteratorIterator $it */
        $it = new \RecursiveIteratorIterator($dirIterator);

        while ($it->valid()) {
            if(!$it->isDot() &&
                $it->isFile() &&
                $it->isReadable() &&
                $it->current()->getExtension() === 'php'){
                    require $it->key();
                    // require $it->current()->getPathname();
            }
            $it->next();
        }
    }
}