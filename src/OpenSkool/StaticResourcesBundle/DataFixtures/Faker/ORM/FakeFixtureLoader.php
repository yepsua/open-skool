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
            
            __DIR__ . '\..\..\..\Resources\fixtures\lov\lov_group.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\lov\lov.yml',
            // FAKE DATA
            __DIR__ . '\..\..\..\Resources\fixtures\faker\instituto.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\titulo.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\mencion.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\carrera.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\facultad.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\asignatura.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\periodo.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\turno.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\seccion.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\pensum.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\etapa.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\grupo.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\pensum_asignatura.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\plan_estudios.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\etapa_plan_estudios.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\grupo_etapa_plan_estudios.yml',
            __DIR__ . '\..\..\..\Resources\fixtures\faker\asignatura_etapa_plan_estudios.yml',
        );
    }
}