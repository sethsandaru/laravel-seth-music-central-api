<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        if (count(\App\User::all()) == 0)
        {
            $user = new \App\User;
            $user->email = 'seth@gmail.com';
            $user->name = 'Seth Phat';
            $user->password = \Illuminate\Support\Facades\Hash::make('123456');
            $user->save();
        }
    }
}
