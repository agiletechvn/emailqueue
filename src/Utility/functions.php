<?php

/**
 * Stores a new email message in the queue.
 *
 * @param mixed|array $to           email or array of emails as recipients
 * @param array $data    associative array of variables to be passed to the email template
 * @param array $options list of options for email sending.
 * @param null|mixed|array $cc           null or email or array of emails as cc
 * @param null|mixed|array $bcc          null or email or array of emails as bcc
 * @param null|mixed|array $replyTo     null or email or array of emails as replyTo
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
function enqueue($to, array $data, array $options = [], $cc = null, $bcc = null, $replyTo = null)
{
    return \Cake\ORM\TableRegistry::get('EmailQueue.EmailQueue')->enqueue($to, $data, $options, $cc, $bcc, $replyTo);
}
