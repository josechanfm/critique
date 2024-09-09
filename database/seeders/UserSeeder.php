<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin=Role::create([
            'name'=>'admin',
            'guard_name'=>'web'
        ]);

        $user=User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
        $user->roles()->attach($roleAdmin);

        $user=User::create([
            'name' => 'Teacher',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password'),
        ]);
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));

        $students=[
            ['name'=>'Member1','email'=>'member1@example.com','password'=>'password'],
            ['name'=>'Member2','email'=>'member2@example.com','password'=>'password'],
            ['name'=>'Member3','email'=>'member3@example.com','password'=>'password'],
            ['name'=>'Member4','email'=>'member4@example.com','password'=>'password'],
            ['name'=>'Member5','email'=>'member5@example.com','password'=>'password'],
            ['name'=>'Member6','email'=>'member6@example.com','password'=>'password'],
        ];
        foreach($students as $student){
            $user=User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make($student['password']),
            ]);
            $user->ownedTeams()->save(Team::forceCreate([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0]."'s Team",
                'personal_team' => true,
            ]));            
        }


        

    }
}
