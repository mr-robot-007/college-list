<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $users = User::where("type", 'Admin')->first();
        if(!$users)
        {
        	// Seeder Query to add default admin user attached with default company
        	$userID = DB::table('users')->insertGetId(['first_name'=> 'Anuj','last_name' => 'Gusain', 'email'=> 'anujgusain108@gmail.com', 'username'=> 'anujgusain108', 'password'=> Hash::make('p@ssw0rd1111'),  "type"=> 'Admin', "status"=> 'Active', 'created_at'=> Carbon::now()->toDateTimeString()]);

        }

    }
}
