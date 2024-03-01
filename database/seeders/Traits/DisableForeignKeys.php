<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait DisableForeignKeys {
    public function disableForeignKeyChecks() {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
    }

    public function enableForeignKeyChecks() {
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}