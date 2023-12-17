<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Email;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/mailcheck', name: 'app_msg')]
    public function msg(MailerInterface $mailer): Response
    {
        // Obtenez l'utilisateur actuel
       if($this->getUser()){
            $user = $this->getUser();
            $username = $user ? $user->getUsername() : null;
            $email = $user ? $user->getEmail() : null;

                // Chemin absolu vers le fichier PDF dans le dossier public
                $pdfPath = $this->getParameter('kernel.project_dir') . '/public/f1.pdf';

            // Composez et envoyez l'e-mail
            $email = (new TemplatedEmail())
                ->from('your@email.com')
                ->to($email)
                ->subject("Bienvenue $username !")
                ->htmlTemplate('home/email.html.twig') // Fichier Twig pour le texte de l'e-mail
                ->attachFromPath($pdfPath, 'f1.pdf', 'application/pdf')
                ->context([
                    'username' => $username,
                    // Autres variables que vous souhaitez passer au modèle Twig
                ]);
            $mailer->send($email);
        }else{
            $username= null;
        }

        return $this->render('home/msg.html.twig', [
            // 'username' => $username,
        ]);
    }

    #[Route("/telecharger-pdf", name:"f1")]
    public function telechargerPdf(): Response
    {
        // Le chemin complet vers le fichier PDF dans le dossier public
        $pdfPath = $this->getParameter('kernel.project_dir') . '/public/f1.pdf';

        // Crée la réponse avec le fichier PDF
        $response = new Response(file_get_contents($pdfPath));

        // Configure le type de contenu et l'en-tête de téléchargement
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="Monformulaire.pdf"');

        return $response;
    }
}
