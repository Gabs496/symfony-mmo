<?php

namespace App\GameObject\Action;

use App\GameElement\Action\ActionEngine;
use App\GameObject\AbstractGameObject;
use ReflectionClass;

readonly abstract class AbstractActionEngine extends AbstractGameObject
{
    protected string $actionId;

    public function __construct(
        protected string $verb,
    )
    {
        parent::__construct();
        $reflectionClass = new ReflectionClass($this);
        $actionEngineAttributes = $reflectionClass->getAttributes(ActionEngine::class);
        foreach ($actionEngineAttributes as $actionEngineAttribute) {
            /** @var ActionEngine $actionEngine */
            $actionEngine = $actionEngineAttribute->newInstance();
            $this->actionId = $actionEngine->getId();
            break;
        }
    }

    public function getVerb(): string
    {
        return $this->verb;
    }

    public function getActionId(): string
    {
        return $this->actionId;
    }

    public abstract function run(object $subject, object $directObject);
}