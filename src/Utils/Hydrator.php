<?php
namespace App\Utils;

class Hydrator
{
    /**
     * Hydrate un objet avec un tableau de données, même les propriétés privées.
     */
    public static function hydrate(object $object, array|object $data): object
    {
        $hydrator = function($data) {
            foreach ($data as $property => $value) {
                if (property_exists($this, $property)) {
                    $this->$property = $value;
                }
            }
        };

        // On lie temporairement la fonction à la portée de l'objet pour accéder au scope privé
        $hydrator->call($object, $data);

        return $object;
    }
}
