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
        $this->call(UsersTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(LikesTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(FollowersTableSeeder::class);
    }
}
