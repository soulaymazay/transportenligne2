<?php

namespace App\Controller;

use App\Entity\Chauff;
use App\Entity\Clients;
use App\Entity\User;
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
 * @Route("/api/chauffeur", name="api_")
 */
class ChauffeurController extends AbstractController
{
    /**
     * @Route(name="create_chauffeur", methods={"POST"})
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

    $user=new User;

    $chauff = new Chauff;
    $chauff->setNumpermis($request->get('numpermis'));
    $user->setName($request->get('username'));
    $user->setEmail($request->get('email'));
    $user->setPassword($request->get('password'));
    $user->setRoles(["ROLE_CHAUFFEUR"]);
    $chauff->setEtat("actif");
    $hash=$encoder->encodePassword($user,$user->getPassword());
    $user->setPassword($hash);
    $chauff->setUser($user);
    $em->persist($user);

    $em->persist($chauff);
    $em->flush();

    return $this->json($chauffRepository->transform($chauff));
}
/**
* @Route(name="list_clients", methods={"GET"})
*/
public function list(chauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->transformAll();
    return $this->json($chauff);
}
    /**
* @Route("/getmoyen/{id}", name="get_moyen", methods={"GET"})
*/
public function getMoyen($id,Request $request,ChauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->findOneBy(['user' => $id]);
$lesmoyens=$chauff->getMoyens();
    return $this->json($lesmoyens);
}
    /**
* @Route("/{id}", name="get_moyens", methods={"GET"})
*/
public function getChauffeur($id,Request $request,ChauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->findOneBy(['user' => $id]);
    return $this->json($chauff->getUser());
}
/**
 * @Route("/{id}", name="update_chauffeur", methods={"PUT"})
 */
public function update($id, Request $request, ChauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->findOneBy(['user' => $id]);

    if (!$chauff) {
        return $this->json(['message' => 'Chauffeur not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    empty($data['numpermis']) ? true : $chauff->setNumpermis($data['numpermis']);
   
    empty($data['etat']) ? true : $chauff->setEtat($data['etat']);

    $updateChauff = $chauffRepository->updateChauff($chauff);

    return $this->json($updateChauff);
}
/**
 * @Route("/{id}", name="delete_chauffeur", methods={"DELETE"})
 */
public function delete($id, EntityManagerInterface $entityManager)
{
    $chauff = $entityManager->getRepository(Chauff::class)->findOneBy(['user_id' => $id]);
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
