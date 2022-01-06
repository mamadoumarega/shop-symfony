<?php

namespace App\Services;

use App\Entity\EmailModel;
use App\Entity\User;
use Mailjet\Client;
use Mailjet\Resources;

class EmailSender
{

    public function sendEmailNotificationMailJet(User $user, EmailModel $email)
    {
        $mj = new Client(getenv($_ENV['MJ_APIKEY_PUBLIC']), getenv($_ENV['MJ_APIKEY_PRIVATE']), true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "hamshakour93@gmail.com",
                        'Name' => "AliExprass Contact"
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' => $user->getFullName()
                        ]
                    ],
                    'TemplateID' => 3475676,
                    'TemplateLanguage' => true,
                    'Subject' => $email->getSubject(),
                    'Variables' => [
                        'title' => $email->getTitle(),
                        'content' => $email->getContent()
                    ]
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        //$response->success() && var_dump($response->getData());

    }

}
