<?php
use Cake\Database\Type;

Type::map('email_queue.json', 'EmailQueue\Database\Type\JsonType');
Type::map('email_queue.serialize', 'EmailQueue\Database\Type\SerializeType');
