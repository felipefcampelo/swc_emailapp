<?php

use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [
            [
                'name'       => 'John Doe',
                'email'      => 'john@example.com',
                'phone'      => '123-456-7890',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Jane Smith',
                'email'      => 'jane@example.com',
                'phone'      => '987-654-3210',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('users')->insert($data)->save();
    }
}
