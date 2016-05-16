<?php
use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    /**
     * up function
     * @return void
     */
    public function up()
    {
        $table = $this->table('email_queue');
        $table
            ->addColumn('from_email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('from_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('email_to', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('email_cc', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('email_bcc', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('email_reply_to', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('subject', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('config', 'string', [
                'default' => 'default',
                'limit' => 30,
                'null' => false,
            ])
            ->addColumn('template', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('layout', 'string', [
                'default' => 'default',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('theme', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('format', 'string', [
                'default' => 'html',
                'limit' => 5,
                'null' => false,
            ])
            ->addColumn('template_vars', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('headers', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('sent', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('locked', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('send_tries', 'integer', [
                'default' => 0,
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('send_at', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();
    }

    /**
     * down function
     * @return void
     */
    public function down()
    {
        $this->dropTable('email_queue');
    }
}
