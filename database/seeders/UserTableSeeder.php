<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->disableForeignKeys();

        // Add the master administrator, user id of 1
        User::create([
            'first_name'              => 'Admin',
            'last_name'              => 'Istrator',
            'email'             => 'admin@admin.com',
            'contact_number'    => '09123456789',
            'birthdate'     => now(),
            'password'          => Hash::make('secret'),
        ]);

        User::create([
            'first_name'              => 'One',
            'last_name'              => 'Leader',
            'email'             => 'leader@leader.com',
            'contact_number'    => '09123432788',
            'birthdate'     => now(),
            'password'          => Hash::make('secret'),
        ]);

        User::create([
            'first_name'              => 'One',
            'last_name'              => 'Collector',
            'email'             => 'collector@collector.com',
            'contact_number'    => '09123566788',
            'birthdate'     => now(),
            'password'          => Hash::make('secret'),
        ]);

        User::create([
            'first_name'              => 'Default',
            'last_name'              => 'User',
            'email'             => 'user@user.com',
            'contact_number'    => '09123456787',
            'birthdate'     => now(),
            'password'          => Hash::make('secret'),
        ]);

        $this->enableForeignKeys();
    }
}
