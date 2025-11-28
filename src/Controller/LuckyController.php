<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger
    )
    {
        $this->logger->info("Attention! Watch the log-files to see the additional information");
    }

    /**
     * @throws RandomException
     */
    #[Route('/lucky/number')]
    public function number(): Response
    {
        $this->logger->debug("LuckyController::number: enter");

        $number = random_int(0, 100);

        $this->logger->debug("LuckyController::number: got number $number");

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }
}
