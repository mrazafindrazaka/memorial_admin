<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Soldat;
use AppBundle\Entity\Partenaire;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="accueil")
     */
	 //fonction qui affiche l'accueil
    public function accueilAction(Request $request)
    {
        return $this->render('@App/accueil.html.twig');
    }
	
	/**
     * @Route("/liste", name="soldat")
     */
	 //Fonction qui affiche la liste de tous les soldats qui sont actifs
		//Affiche la liste différemment s'il y a une recherche via la barre de recherche des nom et prénom
	public function listeAction(Request $request)
    {
		$tab='';
		$entityManager = $this->getDoctrine()->getManager();
		//S'il y a une recherche, on affiche en fonction des soldat actif qui corresponde à la recherche.
		if(isset($_GET["recherche"])){
			$soldat = $entityManager->createQuery(
							'SELECT s
							FROM AppBundle:Soldat s
							WHERE s.actif= :actif 
                            AND s.nom LIKE :key
							OR s.prenom LIKE :key 
                            OR s.matricule LIKE :key'
						)->setParameter('actif',true)
						->setParameter('key','%'.$_GET["recherche"].'%');		
			$soldat->execute();
			$tab=$soldat->getResult();
		}
		//Liste générale 
		else{
			$soldat = $entityManager->createQuery(
							'SELECT s
							FROM AppBundle:Soldat s
							WHERE s.actif= :actif'
						)->setParameter('actif',true);	
			$soldat->execute();
			$tab=$soldat->getResult();
		}
        
        //Liste des conflits
        $conflitrq = $entityManager->createQuery(
                        'SELECT c
                        FROM AppBundle:Conflit c'
                    );	
        $conflitrq->execute();
        $tabconflit=$conflitrq->getResult();
        
        return $this->render('@App/liste.html.twig', array('soldat'=>$tab, 'tabconflit' => $tabconflit));
    }
	
	/**
     * @Route("/liste/{conflit}", name="conflit")
     */
	 //Fonction qui affiche la liste des soldats avec un filtre par conflit
	public function conflitAction(Request $request, $conflit)
    {
		$tab='';
		$entityManager = $this->getDoctrine()->getManager();
		//Liste avec filtre par conflit et recherche par nom ou prénom
		if(isset($_GET["recherche"])){
			$soldat = $entityManager->createQuery(
							'SELECT s
							FROM AppBundle:Soldat s
							WHERE s.idConflit= :conflit 
                            AND s.actif=:actif 
                            AND s.nom LIKE :key
							OR s.prenom LIKE :key
                            OR s.matricule LIKE :key'
						)->setParameter('conflit',$conflit)
						->setParameter('actif',true)
						->setParameter('key','%'.$_GET["recherche"].'%');
			$soldat->execute();
			$tab=$soldat->getResult();
		//Liste avec filtre par conflit	
		}else{
			$soldat = $entityManager->createQuery(
							'SELECT s
							FROM AppBundle:Soldat s
							WHERE s.idConflit= :conflit AND s.actif=:actif'
						)->setParameter('conflit',$conflit)
						->setParameter('actif',true);	
			$soldat->execute();
			$tab=$soldat->getResult();
		}
        
        //Liste des conflits
        $conflitrq = $entityManager->createQuery(
                        'SELECT c
                        FROM AppBundle:Conflit c'
                    );	
        $conflitrq->execute();
        $tabconflit=$conflitrq->getResult();
        
        return $this->render('@App/liste.html.twig', array('soldat'=>$tab,'conflit'=>$conflit, 'tabconflit' => $tabconflit));
    }
	
	/**
     * @Route("/projet", name="projet")
     */
	 //Affichage de la page du projet de la porte Désilles
	 public function projetAction(Request $request)
    {
        return $this->render('@App/projet.html.twig');
    }
	
	/**
     * @Route("/comite_scientifique", name="comite")
     */
	 //fonction qui affiche la liste du comité scientifique
	 public function comiteAction(Request $request)
    {
		$entityManager = $this->getDoctrine()->getManager();
		$comite = $entityManager->createQuery(
						'SELECT c
						FROM AppBundle:Partenaire c'
					);	
		$comite->execute();
		$tab=$comite->getResult();
        return $this->render('@App/comite.html.twig', array('comite'=>$tab));
    }
	
	/**
     * @Route("/contact", name="contact")
     */
	 //Affiche la page de contact
	 public function contactAction(Request $request)
    {
        return $this->render('@App/contact.html.twig');
    }
	
	/**
     * @Route("/detail/{id}", name="detail")
     */
	 //Affiche la fiche détaillée d'un soldat qui à était choisit. 
	 public function detailAction(Request $request, $id)
    {
		$entityManager = $this->getDoctrine()->getManager();
		$soldat = $entityManager->createQuery(
						'SELECT s
						FROM AppBundle:Soldat s
						WHERE s.id= :id'
					)->setParameter('id',$id);	
		$soldat->execute();
		$tab=$soldat->getResult();

        return $this->render('@App/detail.html.twig', array('detail'=>$tab));
    }
	
	/**
     * @Route("/random", name="random")
     */
	 //Fonction qui porte sur le bouton pour découvrir un soldat, qui choisit un soldat au hasard et qui affiche sa fiche
	 public function randomAction(Request $request)
    {
		$repository = $this->getDoctrine()->getRepository(Soldat::class);
		
			$r = $repository->createQueryBuilder('s')
				->where('s.actif =:actif')
				->setParameter('actif', true)
				->orderBy('RAND()')
				->getQuery();
				
			$res=$r->getResult();
        return $this->render('@App/detail.html.twig', array('detail'=>$res));
    }
	
	/**
     * @Route("/search", name="search")
     */
	 //fonction qui affiche la liste des soldats selon la recherche de la barre de recherche uniquement
	 public function searchAction(Request $request)
	 {
		$entityManager = $this->getDoctrine()->getManager();
		$soldat = $entityManager->createQuery(
						'SELECT s
						FROM AppBundle:Soldat s
						WHERE s.nom LIKE :key
						OR s.prenom LIKE :key AND s.actif=:actif'
					)->setParameter('key','%'.$_GET["recherche"].'%')
						->setParameter('actif',true);						
		$soldat->execute();
		$tab=$soldat->getResult();
        
        //Liste des conflits
        $conflitrq = $entityManager->createQuery(
                        'SELECT c
                        FROM AppBundle:Conflit c'
                    );	
        $conflitrq->execute();
        $tabconflit=$conflitrq->getResult();
        
        return $this->render('@App/liste.html.twig', array('soldat'=>$tab, 'tabconflit' => $tabconflit));
	 }
	
}
