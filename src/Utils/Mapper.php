<?php

namespace App\Utils;

class Mapper
{
    /**
     * Map un objet source vers une classe cible (Instanciation automatique)
     */
    public static function map(object $source, string $targetClass): object
    {
        $targetReflection = new \ReflectionClass($targetClass);
        // Crée l'objet cible sans exécuter le constructeur (évite les bugs d'initialisation)
        $targetObject = $targetReflection->newInstanceWithoutConstructor();

        return Mapper::mapToObject($source, $targetObject);
    }

    /**
     * Map un objet source vers un objet cible DEJA INSTANCIÉ (Utile pour les updates)
     */
    public static function mapToObject(object $source, object $targetObject): object
    {
        $sourceReflection = new \ReflectionClass($source);
        $targetReflection = new \ReflectionClass($targetObject);

        // 1. On parcourt les propriétés de l'objet cible
        foreach ($targetReflection->getProperties() as $targetProperty) {
            $propertyName = $targetProperty->getName();

            // 💡 RÈGLE MAGIQUE : Mapping flexible des ID (id <-> classNameId)
            $sourcePropertyName = $propertyName;
            if ($propertyName === 'id') {
                $shortClassName = strtolower($sourceReflection->getShortName());
                $potentialIdName = $shortClassName . 'Id'; // ex: bookId
                if ($sourceReflection->hasProperty($potentialIdName)) {
                    $sourcePropertyName = $potentialIdName;
                }
            } elseif (str_ends_with($propertyName, 'Id')) {
                if ($sourceReflection->hasProperty('id')) {
                    $sourcePropertyName = 'id';
                }
            }

            $getterName = 'get' . ucfirst($sourcePropertyName);
            $hasGetter = $sourceReflection->hasMethod($getterName);
            $hasProperty = $sourceReflection->hasProperty($sourcePropertyName);

            if ($hasGetter || $hasProperty) {
                if ($hasGetter) {
                    $method = $sourceReflection->getMethod($getterName);
                    $value = $method->invoke($source);
                }else{
                    $sourceProperty = $sourceReflection->getProperty($sourcePropertyName);
                    $sourceProperty->setAccessible(true);
                    if (!$sourceProperty->isInitialized($source)) {
                        continue;
                    }
                    $value = $sourceProperty->getValue($source);
                }

                if ($value !== null) {
                    $reflectionType = $targetProperty->getType();
                    $targetType = null;
                    if ($reflectionType instanceof \ReflectionNamedType) {
                        // Cas classique : un seul type (ex: DateTime)
                        $targetType = $reflectionType->getName();
                    } elseif ($reflectionType instanceof \ReflectionUnionType) {
                        // Cas de l'union (ex: string|\DateTime|null)
                        // On boucle sur les types de l'union pour trouver celui qui nous intéresse (pas null)
                        foreach ($reflectionType->getTypes() as $type) {
                            if (!$type->isBuiltin() || $type->getName() !== 'null') {
                                $targetType = $type->getName();
                                // Optionnel : on break dès qu'on trouve la classe principale (ex: DateTime)
                                if (class_exists($targetType)) {
                                    break;
                                }
                            }
                        }
                    }

                    $isDateTimeTarget = $targetType === \DateTimeImmutable::class ||
                        $targetType === \DateTime::class ||
                        $targetType === \DateTimeInterface::class;

                    // Cas 1 : string -> Objet Date (ex: DTO vers Entité)
                    if ($isDateTimeTarget && is_string($value)) {

                        // Par défaut, on instancie un DateTimeImmutable (recommandé dans Symfony)
                        if ($targetType === \DateTime::class) {
                            $value = new \DateTime($value);
                        } else {

                            // Par défaut (DateTimeImmutable ou DateTimeInterface)
                            $value = new \DateTime($value);

                            //  dd($targetType,$value);
                        }
                    }
                    elseif ($value instanceof \DateTimeImmutable && $targetType === \DateTime::class) {
                        $value = \DateTime::createFromImmutable($value);
                        $value = $value->format('Y-m-d H:i:s');

                    }
                    elseif ($value instanceof \DateTime && $targetType === \DateTimeImmutable::class) {
                        $value = \DateTimeImmutable::createFromMutable($value);

                    }
                    elseif ($value instanceof \DateTimeImmutable && ($targetType === \DateTime::class || $targetType === \DateTimeInterface::class)) {
                        // Option de sécurité : Si vous utilisez massivement des Date mutables dans vos entités,
                        // forcez le passage en mutable pour \DateTimeInterface.
                        $value = \DateTime::createFromImmutable($value);

                    }
                    elseif ($value instanceof \DateTime && $targetType === \DateTimeImmutable::class) {
                        $value = \DateTimeImmutable::createFromMutable($value);

                    }
                    // Cas 2 : string -> DateTime (Alternative)
                    elseif ($targetType === \DateTime::class && is_string($value)) {
                        $value = new \DateTime($value);

                    }
                    // Cas 3 : DateTime -> string (ex: Entité vers DTO de réponse)
                    elseif ($targetType === 'string' && $value instanceof \DateTimeInterface) {
                        $value = $value->format('Y-m-d H:i:s');
                    }
                }
                // Injecter la valeur dans la cible
                $targetProperty->setAccessible(true);
                $targetProperty->setValue($targetObject, $value);
            }
        }

        return $targetObject;
    }
}
