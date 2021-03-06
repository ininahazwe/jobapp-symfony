<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HoneyPotSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $honeyPotLogger;
    private RequestStack $requestStack;

    public function __construct(
        LoggerInterface $honeyPotLogger,
        RequestStack $requestStack
    )
    {
        $this->honeyPotLogger = $honeyPotLogger;
        $this->requestStack = $requestStack;
    }

    /**
     * @return array string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'checkHoneyJar'
        ];
    }
    public function checkHoneyJar(FormEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if(!$request){
            return;
        }

        $data = $event->getData();

        if(!array_key_exists('phone', $data) || !array_key_exists('faxNumber', $data)){
            throw new HttpException(400, "Don't touch my form");
        }
        [
            'phone' => $phone,
            'faxNumber' => $faxNumber
        ] = $data;

        if($phone !== "" || $faxNumber !== ""){
            $this->honeyPotLogger->info("Une potentielle tentative de robot spammeur ayant l'adresse IP suivante '{$request->getClientIp()}' a eu lieu. Le champ phone contenait '{$phone}' et le champ faxNumber contenait '{$faxNumber}'.");
            throw new HttpException(403, "Go away dirty bot");
        }
    }
}
