<?php

namespace Empiriq\Contracts;

use React\Promise\PromiseInterface;

interface EnvironmentInterface //todo RunableInterface
{
    public function run(): PromiseInterface;
}
