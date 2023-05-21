<?php

namespace App\Controller;

use App\Entity\Chauff;
use App\Entity\Clients;
use App\Repository\ChauffRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api", name="api_")
 */
class ChauffeurController extends AbstractController
{
    /**
     * @Route("/addchauffeur", name="create_chauffeur", methods={"POST"})
     */
    public function createChauffeur(Request $request, EntityManagerInterface $em, ChauffRepository $chauffRepository, UserPasswordEncoderInterface $encoder): Response
    {
    $request = $this->transformJsonBody($request);
    if (!$request) {
        return $this->respondValidationError('Please provide a valid request');
    }
    if (!$request->get('email')) {
        return $this->respondValidationError('Please provide a valid email');
    }


    $chauff = new Chauff;
    $chauff->setNumpermis($request->get('numpermis'));
    $chauff->setUsername($request->get('username'));
    $chauff->setEmail($request->get('email'));
    $chauff->setPassword($request->get('password'));
    $chauff->setRoles(["ROLE_CHAUFFEUR"]);
    $chauff->setEtat("actif");
    $hash=$encoder->encodePassword($chauff,$chauff->getPassword());
    $chauff->setPassword($hash);
    $em->persist($chauff);
    $em->flush();

    return $this->json($chauffRepository->transform($chauff));
}
/**
* @Route("/list", name="list_clients", methods={"GET"})
*/
public function list(chauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->transformAll();
    return $this->json($chauff);
}
/**
 * @Route("/chauffeur/{id}", name="update_chauffeur", methods={"PUT"})
 */
public function update($id, Request $request, ChauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->findOneBy(['id' => $id]);

    if (!$chauff) {
        return $this->json(['message' => 'Client not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    empty($data['numpermis']) ? true : $chauff->setNumpermis($data['numpermis']);
    empty($data['username']) ? true : $chauff->setUsername($data['username']);
    empty($data['email']) ? true : $chauff->setEmail($data['email']);
    empty($data['password']) ? true : $chauff->setPassword($data['password']);

    $updateChauff = $chauffRepository->updateChauff($chauff);
    $data = [
        'id' => $chauff->getId(),
        'numpermis' => $chauff->getNumpermis(),
        'username' => $chauff->getUsername(),
        'email' => $chauff->getEmail(),
        'password' => $chauff->getPassword(),
    ];
    return $this->json($data);
    return $this->json($updateChauff);
}
/**
 * @Route("/delete/{id}", name="delete_chauffeur", methods={"DELETE"})
 */
public function delete($id, EntityManagerInterface $entityManager)
{
    $chauff = $entityManager->getRepository(Chauff::class)->findOneBy(['id' => $id]);
    $entityManager->remove($chauff);
    $entityManager->flush();
    return new JsonResponse(['status' => 'chauffeur deleted']);
}



// transformer le body de la requette en un objet JSON
    private function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

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
