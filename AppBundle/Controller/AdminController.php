<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Images;
use AppBundle\Entity\Partenaire;
use AppBundle\Entity\Soldat;
use AppBundle\Entity\Conflit;
use AppBundle\Entity\Parametre;
use AppBundle\Form\SoldatType;

class AdminController extends Controller
{

    /**
     * @Route("/admin/import", name="import")
     */
    //fonction qui permet d'importer une image
    public function importAction(Request $request)
    {
        $message = '';
        $fileName = '';
        $erreur = '';
        $fiche = '';
        $soldat = new Soldat();
        $form = $this->createForm(SoldatType::class, $soldat);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {

            // $file stores the uploaded file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $soldat->getPortrait();

            //Si l'image n'a pas la bonne extension
            if ($file->guessExtension() != 'jpeg' && $file->guessExtension() != 'png') {
                $erreur = "Extension de l'image  incorrect!";
                return $this->render('@App/import.html.twig', array(
                    'form' => $form->createView(), 'message' => $message, 'image' => $fileName, 'erreur' => $erreur, 'fiche' => $fiche
                ));
            }

            $fileName = $file->getClientOriginalName();

            //Vérification de l'existence de l'association entre un soldat et un portrait
            $img = $entityManager->createQuery(
                'SELECT s
						FROM AppBundle:Soldat s
						WHERE s.portrait= :portrait'
            )->setParameter('portrait', $fileName);
            $img->execute();
            $res = $img->getResult();

            if (empty($res) == false) {

                $fiche = $res;

                // moves the file to the directory
                $file->move(
                    $this->getParameter('import_img'),
                    $fileName
                );

                //message
                $message = "succes de l'import";
            } else {
                $erreur = "Image non associé à un soldat";
            }
        }

        return $this->render('@App/import.html.twig', array(
            'form' => $form->createView(), 'message' => $message, 'image' => $fileName, 'erreur' => $erreur, 'fiche' => $fiche
        ));
    }

    /**
     * @Route("/admin/importcsv", name="csv")
     */
    //fonction d'import d'un fichier csv dans la bdd
    public function csvAction(Request $request)
    {
        $erreur = '';
        $valeur = 100;
        $i = 0;
        $ligne = 0;
        $message = '';
        $ConflitTab = [];
        $csv = new Soldat();
        //création formulaire
        $form = $this->createFormBuilder($csv)
            ->add('csv', FileType::class, array('label' => 'Import de Soldat en CSV'))
            ->add('enregistrer', SubmitType::class, array('attr' => array('onclick' => 'barre()')))
            ->getForm();

        $form->handleRequest($request);

        //Validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $csv->getCsv();
            if ($file->guessExtension() != 'txt') {
                $erreur = 'Extension incorrect!';
                return $this->render('@App/csv.html.twig', array(
                    'form' => $form->createView(), 'message' => $message, 'erreur' => $erreur, 'valeur' => $valeur
                ));
            }
            $filename = "Soldat.csv";
            $file->move(
                $this->getParameter('import_csv'),
                $filename
            );

            //décoder le contenu CSV et le mettre dans un tableau à deux dimensions
            $delimiter = ",";
            $header = NULL;
            $result = array();

            if (($handle = fopen('csv/' . $filename, 'r')) !== FALSE) {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    if (!$header) {
                        $header = $row;
                    } else {
                        $result[] = array_combine($header, $row);
                    }
                }
                fclose($handle);
            }

            $ligne = count($result);

            $entityManager = $this->getDoctrine()->getManager();

            //changement du lot actif
            $param = $entityManager->getRepository(Parametre::class)->find(1);
            if ($entityManager->getRepository(Soldat::class)->findAll() == Array()) {
                $numlot = $param->getLotActif();
            } else {
                $numlot = $param->getLotActif() + 1;
                $param->setLotActif($numlot);
                $entityManager->flush();
            }

            //test colonne
            if (isset($result[0]['ACTIF']) &&
                isset($result[0]['NOM']) &&
                isset($result[0]['PRENOM']) &&
                isset($result[0]['GRADE']) &&
                isset($result[0]['MATRICULE']) &&
                isset($result[0]['CORPS']) &&
                isset($result[0]['DATE NAISSANCE']) &&
                isset($result[0]['DEPARTEMENT NAISSANCE']) &&
                isset($result[0]['COMMUNE NAISSANCE']) &&
                isset($result[0]['DATE DECES']) &&
                isset($result[0]['PAYS DECES']) &&
                isset($result[0]['DEPARTEMENT DECES']) &&
                isset($result[0]['COMMUNE DECES']) &&
                isset($result[0]['COMPLEMENT DECES']) &&
                isset($result[0]['CONDITION DECES']) &&
                isset($result[0]['DERNIERE RESIDENCE']) &&
                isset($result[0]['ADRESSE']) &&
                isset($result[0]['PORTRAIT']) &&
                isset($result[0]['COMPLEMENT INFORMATION']) &&
                isset($result[0]['COMPLEMENT IMG1']) &&
                isset($result[0]['COMPLEMENT IMG2']) &&
                isset($result[0]['COMPLEMENT IMG3']) &&
                isset($result[0]['ABREVIATION'])) {


                //insertion
                foreach ($result as $row) {
                    set_time_limit(200);
                    $xls = $i + 2;
                    $soldat = new Soldat();
                    if ($row['ACTIF'] == 1) {
                        $soldat->setActif(true);
                    } else {
                        $soldat->setActif(false);
                    }
                    if ($row['NOM'] == '') {
                        $erreur .= " nom manquant ligne " . $xls;
                    } else {
                        $soldat->setNom($row['NOM']);
                    }

                    if ($row['PRENOM'] == '') {
                        $erreur .= " prénom manquant ligne " . $xls;
                    } else {
                        $soldat->setPrenom($row['PRENOM']);
                    }
                    $soldat->setGrade($row['GRADE']);
                    $soldat->setMatricule($row['MATRICULE']);
                    $soldat->setCorps($row['CORPS']);

                    $d = \DateTime::createFromFormat('Y/m/d', $row['DATE NAISSANCE']);
                    //gestion des cases non renseigner
                    if ($d != null) {
                        $soldat->setDateNaissance($d);
                    }

                    $soldat->setDepartementNaissance($row['DEPARTEMENT NAISSANCE']);
                    $soldat->setCommuneNaissance($row['COMMUNE NAISSANCE']);

                    $a = \DateTime::createFromFormat('Y/m/d', $row['DATE DECES']);
                    //gestion des cases non renseigner
                    if ($a != null) {
                        $soldat->setDateDeces($a);
                    }

                    $soldat->setDepartementDeces($row['DEPARTEMENT DECES']);
                    $soldat->setCommuneDeces($row['COMMUNE DECES']);
                    $soldat->setPaysDeces($row['PAYS DECES']);
                    $soldat->setComplementDeces($row['COMPLEMENT DECES']);
                    $soldat->setConditionDeces($row['CONDITION DECES']);
                    $soldat->setDerniereResidence($row['DERNIERE RESIDENCE']);
                    $soldat->setAdresse($row['ADRESSE']);
                    $soldat->setPortrait($this->suppr_accents($row['PORTRAIT']));
                    $soldat->setComplementInfo($row['COMPLEMENT INFORMATION']);
                    $soldat->setComplementImg1($row['COMPLEMENT IMG1']);
                    $soldat->setComplementImg2($row['COMPLEMENT IMG2']);
                    $soldat->setComplementImg3($row['COMPLEMENT IMG3']);

                    //identification
                    $num = $row['DATE NAISSANCE'];
                    $nom = $row['NOM'];
                    $prenom = $row['PRENOM'];
                    $id = $num . $nom . $prenom;
                    $id = $this->suppr_accents($id);
                    $id = strtoupper($id);
                    $id = str_replace("/", "", $id);
                    $id = str_replace(" ", "", $id);
                    $soldat->setIdentification($id);

                    //gestion conflit
                    //si abreviation est dans le tab
                    if (array_key_exists($row['ABREVIATION'], $ConflitTab)) {
                        $soldat->setIdConflit($ConflitTab[$row['ABREVIATION']]);
                    } //sinon ajout de l'abréviation
                    elseif ($conflitfind = $this->findConflit($row['ABREVIATION'])) {
                        $ConflitTab[$row['ABREVIATION']] = $conflitfind;
                        $soldat->setIdConflit($ConflitTab[$row['ABREVIATION']]);
                    } else {
                        $erreur .= ' Abréviation inconnue ou ligne incorrecte ' . $xls;
                    }

                    //mise à jour lot
                    $soldat->setLot($numlot);


                    $i++;

                    if ($erreur == '') {
                        $entityManager->persist($soldat);
                        /* ancien code */
                        /*if ($i % 20 == 0) {
                            $entityManager->flush();
                        }*/
                    }
                }
                if ($erreur == '') {
                    $message = "succes de l'import";
                    $this->saveBackup();
                    $supp = $entityManager->createQuery(
                        'DELETE FROM AppBundle:Soldat s'
                    );
                    $supp->execute();
                    $entityManager->flush();
                }
                /* ancien code */
                //supprimer ancien lot
                /*if ($numlot != 1) {
                    $supp = $entityManager->createQuery(
                        'DELETE AppBundle:Soldat s
						WHERE s.lot= :lot'
                    )->setParameter('lot', $numlot - 1);

                    $supp->execute();
                }*/
            } else {
                $erreur = 'Veuillez vérifier votre fichier CSV, il manque une colonne obligatoire.';
            }
        }

        return $this->render('@App/csv.html.twig', array(
            'form' => $form->createView(), 'message' => $message, 'erreur' => $erreur, 'valeur' => $valeur, 'ligne' => $ligne
        ));
    }

    /**
     * @Route("/admin/backup", name="backup")
     */
    //fonction pour retourner a l'ancienne version de la table soldat
    public function backupPage()
    {
        $message = '';
        $erreur = '';
        $valeur = 100;
        $ligne = 0;
        if (file_exists('csv/soldat-backup.csv')) {
            $info = 'Il existe une ancienne sauvegarde de la table soldat.';
        } else {
            $info = 'Il n\'y a pas encore d\'ancienne sauvegarde de la table soldat.';
        }
        return $this->render('@App/backup.html.twig', array(
            'message' => $message, 'erreur' => $erreur, 'valeur' => $valeur, 'ligne' => $ligne, 'info' => $info
        ));
    }

    /**
     * @Route("/admin/backupcsv", name="backupcsv")
     */
    //fonction pour retourner a l'ancienne version de la table soldat
    public function backupAction()
    {
        $info = '';
        $erreur = '';
        $valeur = 100;
        $i = 0;
        $message = '';
        $ConflitTab = [];
        $filename = "soldat-backup.csv";
        $delimiter = ",";
        $header = NULL;
        $result = array();

        if (($handle = fopen('csv/' . $filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    $result[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        $ligne = count($result);

        $entityManager = $this->getDoctrine()->getManager();

        //changement du lot actif
        $param = $entityManager->getRepository(Parametre::class)->find(1);
        if ($entityManager->getRepository(Soldat::class)->findAll() == Array()) {
            $numlot = $param->getLotActif();
        } else {
            $numlot = $param->getLotActif() + 1;
            $param->setLotActif($numlot);
            $entityManager->flush();
        }

        //test colonne
        if (isset($result[0]['ACTIF']) &&
            isset($result[0]['NOM']) &&
            isset($result[0]['PRENOM']) &&
            isset($result[0]['GRADE']) &&
            isset($result[0]['MATRICULE']) &&
            isset($result[0]['CORPS']) &&
            isset($result[0]['DATE NAISSANCE']) &&
            isset($result[0]['DEPARTEMENT NAISSANCE']) &&
            isset($result[0]['COMMUNE NAISSANCE']) &&
            isset($result[0]['DATE DECES']) &&
            isset($result[0]['PAYS DECES']) &&
            isset($result[0]['DEPARTEMENT DECES']) &&
            isset($result[0]['COMMUNE DECES']) &&
            isset($result[0]['COMPLEMENT DECES']) &&
            isset($result[0]['CONDITION DECES']) &&
            isset($result[0]['DERNIERE RESIDENCE']) &&
            isset($result[0]['ADRESSE']) &&
            isset($result[0]['PORTRAIT']) &&
            isset($result[0]['COMPLEMENT INFORMATION']) &&
            isset($result[0]['COMPLEMENT IMG1']) &&
            isset($result[0]['COMPLEMENT IMG2']) &&
            isset($result[0]['COMPLEMENT IMG3']) &&
            isset($result[0]['ABREVIATION'])) {


            //insertion
            foreach ($result as $row) {
                set_time_limit(200);
                $xls = $i + 2;
                $soldat = new Soldat();
                if ($row['ACTIF'] == 1) {
                    $soldat->setActif(true);
                } else {
                    $soldat->setActif(false);
                }
                if ($row['NOM'] == '') {
                    $erreur .= " nom manquant ligne " . $xls;
                } else {
                    $soldat->setNom($row['NOM']);
                }

                if ($row['PRENOM'] == '') {
                    $erreur .= " prénom manquant ligne " . $xls;
                } else {
                    $soldat->setPrenom($row['PRENOM']);
                }
                $soldat->setGrade($row['GRADE']);
                $soldat->setMatricule($row['MATRICULE']);
                $soldat->setCorps($row['CORPS']);

                $d = \DateTime::createFromFormat('Y/m/d', $row['DATE NAISSANCE']);
                //gestion des cases non renseigner
                if ($d != null) {
                    $soldat->setDateNaissance($d);
                }

                $soldat->setDepartementNaissance($row['DEPARTEMENT NAISSANCE']);
                $soldat->setCommuneNaissance($row['COMMUNE NAISSANCE']);

                $a = \DateTime::createFromFormat('Y/m/d', $row['DATE DECES']);
                //gestion des cases non renseigner
                if ($a != null) {
                    $soldat->setDateDeces($a);
                }

                $soldat->setDepartementDeces($row['DEPARTEMENT DECES']);
                $soldat->setCommuneDeces($row['COMMUNE DECES']);
                $soldat->setPaysDeces($row['PAYS DECES']);
                $soldat->setComplementDeces($row['COMPLEMENT DECES']);
                $soldat->setConditionDeces($row['CONDITION DECES']);
                $soldat->setDerniereResidence($row['DERNIERE RESIDENCE']);
                $soldat->setAdresse($row['ADRESSE']);
                $soldat->setPortrait($this->suppr_accents($row['PORTRAIT']));
                $soldat->setComplementInfo($row['COMPLEMENT INFORMATION']);
                $soldat->setComplementImg1($row['COMPLEMENT IMG1']);
                $soldat->setComplementImg2($row['COMPLEMENT IMG2']);
                $soldat->setComplementImg3($row['COMPLEMENT IMG3']);

                //identification
                $num = $row['DATE NAISSANCE'];
                $nom = $row['NOM'];
                $prenom = $row['PRENOM'];
                $id = $num . $nom . $prenom;
                $id = $this->suppr_accents($id);
                $id = strtoupper($id);
                $id = str_replace("/", "", $id);
                $id = str_replace(" ", "", $id);
                $soldat->setIdentification($id);

                //gestion conflit
                //si abreviation est dans le tab
                if (array_key_exists($row['ABREVIATION'], $ConflitTab)) {
                    $soldat->setIdConflit($ConflitTab[$row['ABREVIATION']]);
                } //sinon ajout de l'abréviation
                elseif ($conflitfind = $this->findConflit($row['ABREVIATION'])) {
                    $ConflitTab[$row['ABREVIATION']] = $conflitfind;
                    $soldat->setIdConflit($ConflitTab[$row['ABREVIATION']]);
                } else {
                    $erreur .= ' Abréviation inconnue ou ligne incorrecte ' . $xls;
                }

                //mise à jour lot
                $soldat->setLot($numlot);


                $i++;

                if ($erreur == '') {
                    $entityManager->persist($soldat);
                }
            }
            if ($erreur == '') {
                $message = "succes du retour en arriere";
                $supp = $entityManager->createQuery(
                    'DELETE FROM AppBundle:Soldat s'
                );
                $supp->execute();
                $entityManager->flush();
            }
        } else {
            $erreur = 'Veuillez vérifier votre fichier CSV, il manque une colonne obligatoire.';
        }

        return $this->render('@App/backup.html.twig', array(
            'message' => $message, 'erreur' => $erreur, 'valeur' => $valeur, 'ligne' => $ligne, 'info' => $info
        ));
    }

    public function saveBackup()
    {
        $connection = $this->getDoctrine()->getManager()->getConnection();
        $sql = 'SELECT soldat.*, conflit.abrevation FROM soldat JOIN conflit ON soldat.idConflit = conflit.nom';
        $statement = $connection->prepare($sql);
        $statement->execute();
        $rows = array();
        //nom de colonnes
        $data = array(
            'ACTIF',
            'NOM',
            'PRENOM',
            'GRADE',
            'MATRICULE',
            'CORPS',
            'DATE NAISSANCE',
            'DEPARTEMENT NAISSANCE',
            'COMMUNE NAISSANCE',
            'DATE DECES',
            'PAYS DECES',
            'DEPARTEMENT DECES',
            'COMMUNE DECES',
            'COMPLEMENT DECES',
            'CONDITION DECES',
            'DERNIERE RESIDENCE',
            'ADRESSE',
            'PORTRAIT',
            'COMPLEMENT INFORMATION',
            'COMPLEMENT IMG1',
            'COMPLEMENT IMG2',
            'COMPLEMENT IMG3',
            'ABREVIATION'
        );
        $res = $statement->fetchAll();
        array_push($rows, implode(',', $data));
        foreach ($res as $r) {
            $naiss = '';
            $deces = '';
            //Formatage du texte en date
            if ($r["dateNaissance"] != "") {
                $naiss = date('Y/m/d', strtotime($r["dateNaissance"]));
            }
            if ($r["dateDeces"] != "") {
                $deces = date('Y/m/d', strtotime($r["dateDeces"]));
            }
            //données
            $data = array($r["actif"], $r["nom"], $r["prenom"],
                $r["grade"], $r["matricule"], $r["corps"],
                $naiss, $r["departementNaissance"], $r["CommuneNaissance"],
                $deces, $r["paysDeces"], $r["departementDeces"],
                $r["communeDeces"], $r["complementDeces"], $r["conditionDeces"],
                $r["derniereResidence"], $r["adresse"], $r["portrait"],
                $r["complementInfo"], $r["complementImg1"], $r["complementImg2"],
                $r["complementImg3"], $r["abrevation"]);
            array_push($rows, implode(',', $data));
        }
        $content = implode("\n", $rows);
        if (($handle = fopen('csv/soldat-backup.csv', 'w+')) !== FALSE) {
            fwrite($handle, $content, strlen($content));
            fclose($handle);
        }
    }

    public function findConflit($abr)
    {
        $soldat = $this->getDoctrine()->getRepository(Conflit::class)->findOneByAbrevation($abr);
        return $soldat;

    }

    function suppr_accents($str, $encoding = 'utf-8')
    {
        // transformer les caracteres accentues en entites HTML
        $str = htmlentities($str, ENT_NOQUOTES, $encoding);

        // remplacer les entites HTML pour avoir juste les premiers caracteres non accentues
        // Exemple : "&ecute;" => "e", "&Ecute;" => "E", "à" => "a" ...
        $str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);

        // Remplacer les ligatures tel que : , Æ ...
        // Exemple "œ" => "oe"
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        // Supprimer tout le reste
        $str = preg_replace('#&[^;]+;#', '', $str);

        return $str;
    }

    /**
     * @Route("/admin/export", name="export")
     */
    //fonction d'export de complet de la BDD
    public function exportAction(Request $request)
    {
        $connection = $this->getDoctrine()->getManager()->getConnection();
        $sql = 'SELECT soldat.*, conflit.abrevation FROM soldat JOIN conflit ON soldat.idConflit = conflit.nom';
        $statement = $connection->prepare($sql);
        $statement->execute();
        $rows = array();
        //nom de colonnes
        $data = array(
            'ACTIF',
            'NOM',
            'PRENOM',
            'GRADE',
            'MATRICULE',
            'CORPS',
            'DATE NAISSANCE',
            'DEPARTEMENT NAISSANCE',
            'COMMUNE NAISSANCE',
            'DATE DECES',
            'PAYS DECES',
            'DEPARTEMENT DECES',
            'COMMUNE DECES',
            'COMPLEMENT DECES',
            'CONDITION DECES',
            'DERNIERE RESIDENCE',
            'ADRESSE',
            'PORTRAIT',
            'COMPLEMENT INFORMATION',
            'COMPLEMENT IMG1',
            'COMPLEMENT IMG2',
            'COMPLEMENT IMG3',
            'ABREVIATION'
        );
        $res = $statement->fetchAll();
        array_push($rows, implode(',', $data));
        foreach ($res as $r) {
            $naiss = '';
            $deces = '';
            //Formatage du texte en date
            if ($r["dateNaissance"] != "") {
                $naiss = date('Y/m/d', strtotime($r["dateNaissance"]));
            }
            if ($r["dateDeces"] != "") {
                $deces = date('Y/m/d', strtotime($r["dateDeces"]));
            }
            //données
            $data = array($r["actif"], $r["nom"], $r["prenom"],
                $r["grade"], $r["matricule"], $r["corps"],
                $naiss, $r["departementNaissance"], $r["CommuneNaissance"],
                $deces, $r["paysDeces"], $r["departementDeces"],
                $r["communeDeces"], $r["complementDeces"], $r["conditionDeces"],
                $r["derniereResidence"], $r["adresse"], $r["portrait"],
                $r["complementInfo"], $r["complementImg1"], $r["complementImg2"],
                $r["complementImg3"], $r["abrevation"]);
            array_push($rows, implode(',', $data));
        }
        $content = implode("\n", $rows);
        $response = new  Response($content);
        //Header
        $response->headers->set('Content-Disposition', 'filename="soldat.csv"');
        $response->headers->set('Content-Type', 'text/csv');
        return $response;
    }

    /**
     * @Route("/admin/export2", name="decaux")
     */
    //fonction d'import pour Decaux avec colonnes choisit
    public function decauxAction(Request $request)
    {
        echo 'Je suis là';
        $entityManager = $this->getDoctrine()->getManager();
        $s = $entityManager->createQuery(
            'SELECT s FROM AppBundle:Soldat s WHERE s.actif=:actif'
        );
        $s->setParameter(':actif', 1);
        $s->execute();
        $res = $s->getResult();
        $rows = array();
        $i = 0;
        foreach ($res as $r) {
            $naiss = '';
            $deces = '';
            //Formatage du texte en date
            if ($r->getDateNaissance() != "") {
                $naiss = $r->getDateNaissance()->format('Y/m/d');
            }
            if ($r->getDateDeces() != "") {
                $deces = $r->getDateDeces()->format('Y/m/d');
            }
            //nom de colonnes
            if ($i == 0) {
                $data = array('Guerre', 'Nom', 'Prénoms', 'Corps', 'Date de naissance', 'Departement de naissance', 'Commune de naissance', 'Date de décès', 'Pays de décès', 'Département de décès', 'Commune de décès');
            } //données
            else {
                $data = array($r->getIdConflit(), $r->getNom(), $r->getPrenom(), $r->getCorps(), $naiss, $r->getDepartementNaissance(), $r->getCommuneNaissance(), $deces, $r->getPaysDeces(), $r->getDepartementDeces(), $r->getCommuneDeces());
            }
            $rows[] = implode(';', $data);
            $i++;
        }
        $content = implode("\n", $rows);
        $response = new  Response($content);
        //Header
        $response->headers->set('Content-Disposition', 'filename="soldat-decaux.csv"');
        $response->headers->set('Content-Type', 'text/csv');
        return $response;
    }

}
