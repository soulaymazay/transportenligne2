<?php

namespace App\Controller;

use App\Entity\Lesmoyens;
use App\Repository\LesmoyensRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api", name="api_")
 */
class TouslesmoyensController extends AbstractController
{
    /**
     * @Route("/addmoyen", name="create_moyen", methods={"POST"})
     */
    public function createMoyen(Request $request, EntityManagerInterface $em, LesmoyensRepository $lesmoyensRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->respondValidationError('Invalid JSON data.');
        }

        $lesmoyens = new Lesmoyens();
        $lesmoyens->setNom($data['nom']);
        $lesmoyens->setMarque($data['marque']);
        $lesmoyens->setCouleur($data['couleur']);
        $lesmoyens->setAnnee($data['annee']);
        $lesmoyens->setRoles(["ROLE_moyens"]);
        $lesmoyens->setEtat("actif");

        $em->persist($lesmoyens);
        $em->flush();

        return $this->json($lesmoyensRepository->transform($lesmoyens));
    }
    /**
* @Route("/listmoyen", name="list_moyens", methods={"GET"})
*/
public function list(lesmoyensRepository $lesmoyensRepository)
{
    $lesmoyens = $lesmoyensRepository->transformAll();
    return $this->json($lesmoyens);
}
/**
 * @Route("/moyen/{id}", name="update_moyen", methods={"PUT"})
 */
public function update($id, Request $request, LesmoyensRepository $lesmoyensRepository)
{
    $lesmoyens = $lesmoyensRepository->findOneBy(['id' => $id]);

    if (!$lesmoyens) {
        return $this->json(['message' => 'Client not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    empty($data['nom']) ? true : $lesmoyens->setNom($data['nom']);
    empty($data['marque']) ? true : $lesmoyens->setMarque($data['marque']);
    empty($data['couleur']) ? true : $lesmoyens->setCouleur($data['couleur']);
    empty($data['annee']) ? true : $lesmoyens->setAnnee($data['annee']);

    $updateLesmoyens = $lesmoyensRepository->updateLesmoyens($lesmoyens);
    $data = [
        'id' => $lesmoyens->getId(),
        'nom' => $lesmoyens->getNom(),
        'marque' => $lesmoyens->getMarque(),
        'couleur' => $lesmoyens->getCouleur(),
        'annee' => $lesmoyens->getAnnee(),
    ];
    return $this->json($data);
    return $this->json($updateLesmoyens);
}
/**
 * @Route("/moyen/{id}", name="delete_moyen", methods={"DELETE"})
 */
public function delete($id, EntityManagerInterface $entityManager)
{
    $lesmoyens = $entityManager->getRepository(lesmoyens::class)->findOneBy(['id' => $id]);
    $entityManager->remove($lesmoyens);
    $entityManager->flush();
    return new JsonResponse(['status' => 'moyen deleted']);
}


    private function respondValidationError($message)
    {
        return new JsonResponse(['message' => $message, 'status' => 'error'], 400);
    }
}
