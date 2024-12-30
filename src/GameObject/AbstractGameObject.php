<?php

namespace App\GameObject;

use App\GameElement\GameElementInterface;
use ReflectionClass;

readonly abstract class AbstractGameObject
{
    protected GameElementInterface $element;

    public function __construct()
    {
        $reflectionClass = new ReflectionClass($this);
        $attributes = $reflectionClass->getAttributes();
        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();
            if ($attributeInstance instanceof GameElementInterface) {
                $this->element = $attributeInstance;
                break;
            }
        }
    }

    public function getElement(): GameElementInterface
    {
        return $this->element;
    }

    abstract public static function getId(): string;
}