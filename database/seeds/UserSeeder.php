<?php

use App\Infrastructure\Eloquent\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = new User();
        $user->name = "Tien Pham";
        $user->email = "quoctienphamm@gmail.com";
        $user->phone_number = "0939432055";
        $user->password = Hash::make('123456');
        $user->email_verified_at = '2022-11-10 00:00:00';
        $user->save();
    }
}
