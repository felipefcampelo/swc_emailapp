<?php

use Phinx\Seed\AbstractSeed;

class SentEmailsSeeder extends AbstractSeed
{
    public function getDependencies(): array
    {
        return [
            'UsersSeeder'
        ];
    }

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
                'user_id'       => 1,
                'email_content' => 'Hello John, this is a test email.',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'       => 2,
                'email_content' => 'Hi Jane, this is another test email.',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('sent_emails')->insert($data)->save();
    }
}
