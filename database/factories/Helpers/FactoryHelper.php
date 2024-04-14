<?php

namespace Database\Factories\Helpers;

class FactoryHelper {
    public static function getRandomModelId(string $model) {
        // Get number of records in a model
        $modelCount = $model::count();

        // Generate a random number between 1 & records count
        if($modelCount === 0) {
            return $model::factory(1)->create()->first()->id;
        } else {
            return rand(1, $modelCount);
        }
    }
}