<?php

declare(strict_types=1);

namespace TWOH\TwohMongodbDriver\Converter;

class ArrayToModelConverter
{
    /**
     * Maps array values to object setters
     *
     * @deprecated is not used anymore, because array handling is faster as object handling
     * @param array $array
     * @param object $object
     * @return object
     */
    public static function mapArray(array $array, object $object): object
    {
        $class = \get_class($object);
        $methods = get_class_methods($class);
        foreach ($methods as $method) {
            preg_match('/^(set)(.*?)$/i', $method, $results);
            $pre = $results[1] ?? '';
            $k = $results[2] ?? '';
            if ($k === '') {
                continue;
            }
            $k = strtolower($k[0]) . substr($k, 1);
            if ($pre === 'set' && !empty($array[$k])) {
                $object->$method($array[$k]);
            }
        }
        return $object;
    }
}
