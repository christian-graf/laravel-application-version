<?php

declare(strict_types=1);

if (function_exists('app')) {
    if (!function_exists('application_version')) {
        /**
         * @return \Fox\Application\Version\Version
         */
        function application_version(): Fox\Application\Version\Version
        {
            return app(\Fox\Application\Version\VersionManager::class)->getCurrentVersion();
        }
    }
}
