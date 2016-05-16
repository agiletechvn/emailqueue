[![Build Status](https://travis-ci.org/crabstudio/emailqueue.svg?branch=master)](https://travis-ci.org/crabstudio/emailqueue) [![Latest Stable Version](https://poser.pugx.org/crabstudio/email-queue/v/stable)](https://packagist.org/packages/crabstudio/email-queue) [![Total Downloads](https://poser.pugx.org/crabstudio/email-queue/downloads)](https://packagist.org/packages/crabstudio/email-queue) [![Latest Unstable Version](https://poser.pugx.org/crabstudio/email-queue/v/unstable)](https://packagist.org/packages/crabstudio/email-queue) [![License](https://poser.pugx.org/crabstudio/email-queue/license)](https://packagist.org/packages/crabstudio/email-queue)
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

## Create required table

2 way to do it

### Use Migration tool

```
bin/cake migrations migrate --plugin EmailQueue
```

### Load sql file into your database

```
sql file located at: config/schema/email_queue.sql
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
 *
 * $options Possible keys:
 * - subject : Email's subject
 * - send_at : date time sting representing the time this email should be sent at (in UTC)
 * - template :  the name of the element to use as template for the email message
 * - layout : the name of the layout to be used to wrap email message
 * - format: Type of template to use (html, text or both)
 * - config : the name of the email config to be used for sending
 * @param null|mixed|array $cc           null or email or array of emails as cc
 * @param null|mixed|array $bcc          null or email or array of emails as bcc
 * @param null|mixed|array $reply_to     null or email or array of emails as reply_to
 *
 * @return bool
 */
enqueue($to, array $data, array $options = [], $cc = null, $bcc = null, $reply_to = null)
```

Example

```
// In src/PostsController.php

publuc function send_email($id) {
	$post = $this->Posts->get($id);
	$result = enqueue(
		'customer@crabstudio.info',
		[
			'post' => $post,
			'request' => $this->request
		],
		[
			'subject' => __('New post notification'),
			'format' => 'html',
			'template' => 'Post/new_post_notification',  //template located here src/Template/Email/html/Post/new_post_notification.ctp
			'layout' => 'notification' //layout located here src/Template/Layout/Email/html/notification.ctp
			'config' => 'default',

		],
		'cc_to_me@crabstudio.info',
		'bcc_to_you@crabstudio.info',
		'reply_to_support@crabstudio.info'
	);
	if ($result) {
		$this->Flash->success(__('Enqueue email ok'));
	} else {
		$this->Flash->error(__('Enqueue email not ok'));
	}
}
```

### Schedule task

#### Linux:

Open `crontab` then setup cronjob like this:
```
*       *       *       *       *       cd /var/www/your_project && bin/cake EmailQueue.sender
```

#### Windows:

Open `Task Scheduler` then follow [this tutorial](http://www.digitalcitizen.life/how-create-task-basic-task-wizard)