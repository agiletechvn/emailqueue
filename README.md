# EmailQueue plugin for CakePHP 3

## Note

This plugin base on [Lorenzo cakephp-email-queue](https://github.com/lorenzo/cakephp-email-queue) but with little bit different:

	- Sent to `one|multiple` people
	- CC to `none|one|multiple` people
	- BCC to `none|one|multiple` people
	- Included helpers `Html, Text, Number`

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

### The recommended way to install composer packages is:

```
composer require crabstudio/email-queue
```

### Then load this plugin by type in your command line:

```
bin/cake plugin load EmailEnqueue --bootstrap
```
or paste this line to the end of `config/bootstrap.php`
```
Plugin::load('EmailQueue', ['bootstrap' => true]);
```
## Usage

### Call `enqueue` function anywhere you want to store new email in the queue.

```
/**
 * Stores a new email message in the queue.
 *
 * @param mixed|array $to           email or array of emails as recipients
 * @param array $data    associative array of variables to be passed to the email template
 * @param array $options list of options for email sending.
 * @param null|mixed|array $cc           null or email or array of emails as cc
 * @param null|mixed|array $bcc          null or email or array of emails as bcc
 * @param null|mixed|array $reply_to     null or email or array of emails as reply_to
 *
 * $options Possible keys:
 * - subject : Email's subject
 * - send_at : date time sting representing the time this email should be sent at (in UTC)
 * - template :  the name of the element to use as template for the email message
 * - layout : the name of the layout to be used to wrap email message
 * - format: Type of template to use (html, text or both)
 * - config : the name of the email config to be used for sending
 *
 * @return bool
 */
enqueue($to, array $data, array $options = [], $cc = null, $bcc = null, $reply_to = null)
```

### Schedule task

#### Linux:

Open `crontab` then setup cronjob like this:
```
*       *       *       *       *       cd /var/www/your_project && bin/cake EmailQueue.sender
```

#### Windows:

Open `Task Scheduler` then follow [this tutorial](http://www.digitalcitizen.life/how-create-task-basic-task-wizard)

### Report

You can look at `logs/email-queue.log` file.