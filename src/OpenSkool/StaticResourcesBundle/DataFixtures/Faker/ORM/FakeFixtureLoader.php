<?php

namespace OpenSkool\StaticResourcesBundle\Faker\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class FakeFixtureLoader extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return  array(
            // FAKE DATA
            __DIR__ . '\..\..\..\Resources\fixtures\faker\instituto.yml',
        );
    }
}