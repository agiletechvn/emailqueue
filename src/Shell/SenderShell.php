<?php
namespace EmailQueue\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\Network\Exception\SocketException;
use Cake\ORM\TableRegistry;
use EmailQueue\Model\Table\EmailQueueTable;

/**
 * Sender shell command.
 */
class SenderShell extends Shell
{

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->description('Sends queued emails in a batch')
            ->addOption('limit', [
                'short' => 'l',
                'help' => 'How many emails should be sent in this batch?',
                'default' => 50,
            ])
            ->addOption('template', [
                'short' => 't',
                'help' => 'Name of the template to be used to render email',
                'default' => 'default',
            ])
            ->addOption('layout', [
                'short' => 'w',
                'help' => 'Name of the layout to be used to wrap template',
                'default' => 'default',
            ])
            ->addOption('stagger', [
                'short' => 's',
                'help' => 'Seconds to maximum wait randomly before proceeding (useful for parallel executions)',
                'default' => false,
            ])
            ->addOption('config', [
                'short' => 'c',
                'help' => 'Name of email settings to use as defined in email.php',
                'default' => 'default',
            ])
            ->addSubCommand('clearLocks', [
                'help' => 'Clears all locked emails in the queue, useful for recovering from crashes',
            ]);

        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main()
    {
        $this->out($this->OptionParser->help());
        if ($this->params['stagger']) {
            sleep(rand(0, $this->params['stagger']));
        }

        Configure::write('App.baseUrl', '/');
        $emailQueue = TableRegistry::get('EmailQueue', ['className' => EmailQueueTable::class]);
        $emails = $emailQueue->getBatch($this->params['limit']);

        $count = count($emails);
        foreach ($emails as $e) {
            $configName = $e->config === 'default' ? $this->params['config'] : $e->config;
            $template = $e->template === 'default' ? $this->params['template'] : $e->template;
            $layout = $e->layout === 'default' ? $this->params['layout'] : $e->layout;
            $headers = empty($e->headers) ? [] : (array)$e->headers;
            $theme = empty($e->theme) ? '' : (string)$e->theme;
            $helpers = ['Html', 'Text', 'Number', 'Url'];
            $fromEmail = null;
            $fromName = null;

            try {
                $email = $this->_newEmail($configName);
                if (!empty($e->fromEmail) && !empty($e->fromName)) {
                    $email->from($e->fromEmail, $e->fromName);
                }

                $transport = $email->transport();
                if ($transport && $transport->config('additionalParameters')) {
                    $from = key($email->from());
                    $transport->config(['additionalParameters' => "-f $from"]);
                }
                $sent = $email
                    ->to($e->emailTo)
                    ->subject($e->subject)
                    ->template($template, $layout)
                    ->emailFormat($e->format)
                    ->addHeaders($headers)
                    ->theme($theme)
                    ->helpers($helpers)
                    ->viewVars($e->template_vars)
                    ->messageId(false)
                    ->returnPath($email->from());
                if ($e->emailCc) {
                    $sent->addCc(explode(',', $e->emailCc));
                }
                if ($e->emailBcc) {
                    $sent->addBcc(explode(',', $e->emailBcc));
                }
                if (get_class($transport) === 'Cake\Mailer\Transport\SmtpTransport') {
                    $fromEmail = $fromName = $transport->config()['username'];
                } else {
                    foreach ($sent->from() as $k => $v) {
                        $fromEmail = $k;
                        $fromName = $v;
                    }
                }
                if ($e->emailReplyTo) {
                    $sent->replyTo(explode(',', $e->emailReplyTo));
                } else {
                    $sent->replyTo($fromEmail, $fromName);
                }
                $sent = $sent->send();
            } catch (SocketException $exception) {
                $this->err($exception->getMessage());
                $sent = false;
            }
            if ($sent) {
                $emailQueue->success($e->id, $fromEmail, $fromEmail);
                $this->out('<success>Email ' . $e->id . ' was sent</success>');
            } else {
                $emailQueue->fail($e->id, $fromEmail, $fromEmail);
                $this->out('<error>Email ' . $e->id . ' was not sent</error>');
            }
        }
        if ($count > 0) {
            $emailQueue->releaseLocks(collection($emails)->extract('id')->toList());
        }
        return $send;
    }
    /**
     * Clears all locked emails in the queue, useful for recovering from crashes.
     * @return void
     **/
    public function clearLocks()
    {
        TableRegistry::get('EmailQueue', ['className' => EmailQueueTable::class])->clearLocks();
    }

    /**
     * Returns a new instance of CakeEmail.
     * @param array $config config
     * @return Email
     **/
    protected function _newEmail($config)
    {
        return new Email($config);
    }
}
