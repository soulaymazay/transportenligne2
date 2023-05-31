<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\ClientType;
use App\Repository\ClientRepository;
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
 * @Route("/api/client", name="api_")
 */

class ClientController extends AbstractController
// { private $logger;
    {
    /**
     * @Route(name="create_client", methods={"POST"})
     */
    public function createclient(Request $request, EntityManagerInterface $em, ClientRepository $clientRepository, UserPasswordEncoderInterface $encoder): Response

    // {$this->logger = $logger;
        {
    $request = $this->transformJsonBody($request);
    if (!$request) {
        return $this->respondValidationError('Please provide a valid request');
    }
    if (!$request->get('email')) {
        return $this->respondValidationError('Please provide a valid email');
    }


    $client = new Client;
    $user=new User;
    $user->setName($request->get('username'));
    $user->setEmail($request->get('email'));
    $user->setPassword($request->get('password'));
    $user->setRoles(["ROLE_CLIENT"]);
    $client->setEtat("actif");
    $hash=$encoder->encodePassword($user, $user->getPassword());
    $user->setPassword($hash);
    $client->setUser($user);
    $em->persist($user);

    $em->persist($client);
    $em->flush();

    return $this->json($clientRepository->transform($client));
}
/**
* @Route(name="list_client", methods={"GET"})
*/
public function list(clientRepository $clientRepository)
{
    $client = $clientRepository->transformAll();
    return $this->json($client);
}/**
 * @Route("/{id}", name="update_client", methods={"PUT"})
 */
public function update($id, Request $request, ClientRepository $clientRepository)
{
    /** @var Client $client **/
     $client = $clientRepository->findOneBy(['user_id' => $id]);
    if (!$client) {
        return $this->json(['message' => 'Client not found'], 404);
    }
    $data = json_decode($request->getContent(), true);

    empty($data['etat']) ? true : $client->setEtat($data['etat']);


    $updateClient = $clientRepository->updateClient($client);
    $data = [
        'id' => $client->getId(),
        'etat' => $client->getEtat(),
    
    ];
    return $this->json($updateClient);
}
/**
 * @Route("/{id}", name="delete_client", methods={"DELETE"})
 */
public function delete($id, EntityManagerInterface $entityManager)
{
    $client = $entityManager->getRepository(client::class)->findOneBy(['user_id' => $id]);
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
