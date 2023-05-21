<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientsType;
use App\Repository\ClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use Psr\Log\LoggerInterface;

/**
 * @Route("/api", name="api_")
 */

class ClientController extends AbstractController
// { private $logger;
    {
    /**
     * @Route("/add", name="create_clients", methods={"POST"})
     */
    public function createClients(Request $request, EntityManagerInterface $em, ClientsRepository $clientsRepository, UserPasswordEncoderInterface $encoder): Response

    // {$this->logger = $logger;
        {
    $request = $this->transformJsonBody($request);
    if (!$request) {
        return $this->respondValidationError('Please provide a valid request');
    }
    if (!$request->get('email')) {
        return $this->respondValidationError('Please provide a valid email');
    }


    $clients = new Clients;

    $clients->setUsername($request->get('username'));
    $clients->setEmail($request->get('email'));
    $clients->setPassword($request->get('password'));
    $clients->setRoles(["ROLE_CLIENT"]);
    $clients->setEtat("actif");
    $hash=$encoder->encodePassword($clients, $clients->getPassword());
    $clients->setPassword($hash);
    $em->persist($clients);
    $em->flush();

    return $this->json($clientsRepository->transform($clients));
}
/**
* @Route("/list", name="list_clients", methods={"GET"})
*/
public function list(clientsRepository $clientsRepository)
{
    $clients = $clientsRepository->transformAll();
    return $this->json($clients);
}/**
 * @Route("/clients/{id}", name="update_clients", methods={"PUT"})
 */
public function update($id, Request $request, ClientsRepository $clientsRepository)
{
    $clients = $clientsRepository->findOneBy(['id' => $id]);

    if (!$clients) {
        return $this->json(['message' => 'Client not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    empty($data['username']) ? true : $clients->setUsername($data['username']);
    empty($data['email']) ? true : $clients->setEmail($data['email']);
    empty($data['password']) ? true : $clients->setPassword($data['password']);

    $updateClients = $clientsRepository->updateClients($clients);
    $data = [
        'id' => $clients->getId(),
        'username' => $clients->getUsername(),
        'email' => $clients->getEmail(),
        'password' => $clients->getPassword(),
    ];
    return $this->json($data);
    return $this->json($updateClients);
}
/**
 * @Route("/clients/{id}", name="delete_clients", methods={"DELETE"})
 */
public function delete($id, EntityManagerInterface $entityManager)
{
    $client = $entityManager->getRepository(Clients::class)->findOneBy(['id' => $id]);
    $entityManager->remove($client);
    $entityManager->flush();
    return new JsonResponse(['status' => 'client deleted']);
}



// transformer le body de la requette en un objet JSON
    private function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        // $this->logger->info($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }

    private function respondValidationError($message)
    {
        return new JsonResponse(['message' => $message, 'status' => 'error'], 400);
    }
}
