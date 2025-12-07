<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Utility;

class CoreUtility
{
    /**
     * @param string $identifier
     * @return string
     */
    public static function toUpperCamelCase(
        string $identifier,
    ): string {
        $parts = explode('_', $identifier);
        $parts = array_map('ucfirst', $parts);

        return implode('', $parts);
    }
}
