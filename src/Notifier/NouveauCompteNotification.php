<?php


namespace App\Notifier;

use App\Entity\User;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;

class NouveauCompteNotification
{

    /**
     * @Route("/invoice/create")
     */
    public function create(NotifierInterface $notifier, User $user)
    {
        $notification = (new Notification('New Invoice', ['email']))
            ->content('Un compte a été créé');

        // The receiver of the Notification
        $recipient = new Recipient(
            $user->getEmail()
        );

        // Send the notification to the recipient
        $notifier->send($notification, $recipient);

        // ...
    }
}