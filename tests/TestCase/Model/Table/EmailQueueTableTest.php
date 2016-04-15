<?php
namespace EmailQueue\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use EmailQueue\Model\Table\EmailQueueTable;

/**
 * EmailQueue\Model\Table\EmailQueueTable Test Case
 */
class EmailQueueTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \EmailQueue\Model\Table\EmailQueueTable
     */
    public $EmailQueue;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.email_queue.email_queue'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EmailQueue') ? [] : ['className' => 'EmailQueue\Model\Table\EmailQueueTable'];
        $this->EmailQueue = TableRegistry::get('EmailQueue', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EmailQueue);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
