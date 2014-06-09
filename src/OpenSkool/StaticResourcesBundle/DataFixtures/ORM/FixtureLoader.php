<?php

namespace OpenSkool\StaticResourcesBundle\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class FixtureLoader extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return  array(
            // LOV BUNDLE
            __DIR__ . '\..\..\Resources\fixtures\lov\lov_group.yml',
            __DIR__ . '\..\..\Resources\fixtures\lov\lov.yml',
            // SECURITY BUNDLE
            __DIR__ . '\..\..\Resources\fixtures\security\role.yml',
            __DIR__ . '\..\..\Resources\fixtures\security\group.yml',
        );
    }
}