<?php

namespace App\Controller;

use App\Entity\Fruit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsController]
class FruitController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private MailerInterface $mailer)
    {
    }

    public function __invoke(Fruit $fruit)
    {
        $this->entityManager->persist($fruit);
        $this->entityManager->flush();
        $email = (new Email())
            ->from('hello@example.com')
            ->to('test@gmail.com')
            ->subject('New Fruit Created Successfully!')
            ->text('New Fruit Created Successfully!')
            ->html('<p>New Fruit Created Successfully!</p>');
        
        $this->mailer->send($email);
        return $fruit;
    }
}
