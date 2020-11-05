<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

/**
 * Class UserRoleTableSeeder.
 */
class UserRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        User::find(1)->roles()->attach(1);
        User::find(2)->roles()->attach(4);
        User::find(3)->roles()->attach(3);
        User::find(4)->roles()->attach(2);

        $this->enableForeignKeys();
    }
}
