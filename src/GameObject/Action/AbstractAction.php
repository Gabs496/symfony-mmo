<?php

namespace App\GameObject\Action;

use App\GameObject\AbstractGameObject;

readonly abstract class AbstractAction extends AbstractGameObject
{
    public function __construct(
        protected string $verb,
    )
    {
        parent::__construct();
    }

    public function getVerb(): string
    {
        return $this->verb;
    }

    public abstract function execute(array $whos, object $on);
}