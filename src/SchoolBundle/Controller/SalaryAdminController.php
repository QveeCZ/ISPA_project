<?php

namespace SchoolBundle\Controller;
require_once 'mpdf/vendor/autoload.php';

use CourseBundle\Entity\Registration;
use CourseBundle\Form\Model\CourseRegistration;
use CourseBundle\Form\Type\RegistrationType;
use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query\ResultSetMapping;
use mPDF;
use SchoolBundle\Admin\LectorAdmin;
use SchoolBundle\Entity\Lector;
use SchoolBundle\Entity\School;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use UserBundle\Entity\User;


class SalaryAdminController extends CRUDController
{


    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $schools = array();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_STAFF')) {
            $repoSchool = $em->getRepository('SchoolBundle:School');
            $schools = $repoSchool->findAll();
        }

        if ($request->getMethod() == 'POST') {

            $date =$request->request->get('salaryDate');

            /**
             * @var AuthorizationChecker $securityContext
             */
            $securityContext = $this->get('security.authorization_checker');

            if($securityContext->isGranted('ROLE_STAFF')) {
                $school = $repoSchool->find($request->request->get('school'));
            }else{
                /**
                 * @var User $currentUser
                 * @var School $school
                 */
                $currentUser = $this->get('security.token_storage')->getToken()->getUser();
                $school = $currentUser->getSchool();
            }



            return $this->salaryPdf($school, $date);
        }

        return $this->render($this->admin->getTemplate('list'), array(
            'action' => 'list',
            "schools" => $schools,
        ));

    }

    //OP FUNKCE V KONCTOLERU
    public function prevedCisloNaNulu($cislo)
    {
        if ($cislo < 0) {
            $cislo = 0;
        }

        return $cislo;
    }

    /**
     * @param School $school
     * @return Response
     */
    //prikald jak se to delat nema :D
    private function salaryPdf($school, $date)
    {

        if (!isset($school)) {
            return new Response("afsa");
        }

        $securityContext = $this->get('security.authorization_checker');
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        $lectors = $school->getSchoolLectors();

        $html = "
<!DOCTYPE html>
<html>
    <head>
    <title>Výplata</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
  <style>
  body { font-family: DejaVu Sans, sans-serif; }

  </style>
      </head>


  <body>
     
                   
     ";


        $first = true;
        foreach ($lectors as $lector){
            if(!$first){
                $html .= "<pagebreak>";
            }
            $first = false;
            $html .= $this->getLectorBody($lector->getId(), $school, $date);
        }

        $html .= "

      </body>
          </html> ";

        /**
         * @var
         */
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('vyplata.pdf', 'D');


        $response = new Response();

        $response->setContent($school);

        return $response;
    }

    /**
     * @param int $lectorId
     * @param School $school
     * @return string
     */
    private function getLectorBody($lectorId, $school, $date)
    {

        $date = explode(".", $date);
        $year = intval($date[1]);
        $month = intval($date[0]);

        $conn = $this->getDoctrine()->getConnection();
        $RAW_QUERY = 'SELECT * FROM lectors where id = ' . $lectorId;//SQL INJECTION
        $statement = $conn->prepare($RAW_QUERY);
        $statement->execute();

        $lekor = $statement->fetchAll();

        /**
         * @var Statement $statement
         */
        $RAW_QUERY = 'SELECT COUNT(l.id) hours, DATE_FORMAT(date_ride, "%Y-%m") month FROM lectors as l inner join course_ride as cr on l.id = cr.lector_id WHERE YEAR(date_ride) = ' . $year . ' AND MONTH(date_ride) = ' . $month . ' AND l.id = ' . $lectorId . ' group by l.id, DATE_FORMAT(date_ride, "%Y-%m") order by month desc';
        $statement = $conn->prepare($RAW_QUERY);
        $statement->execute();
        $res = $statement->fetchAll();
        $pocetHodin = (!empty($res)) ? $res[0]['hours'] : 0;
        //nebdu klikat jizdy rucne...

        $datum = date("d.m.Y");

        $RAW_QUERY = 'SELECT sum(lc.length) hours, DATE_FORMAT(date_lecture, "%Y-%m") month FROM lectors as l inner join lectures as lc on l.id = lc.lector_id WHERE YEAR(date_lecture) = ' . $year . ' AND MONTH(date_lecture) = ' . $month . ' AND l.id = ' . $lectorId . ' group by l.id, DATE_FORMAT(date_lecture, "%Y-%m") order by month desc';
        $statement = $conn->prepare($RAW_QUERY);
        $statement->execute();
        $res = $statement->fetchAll();
        $pocetHodin += (!empty($res)) ? $res[0]['hours'] : 0;
        $obdovi = date("Y-m", strtotime("-1 month", time()));

        $hruba_mzda = $pocetHodin * $lekor[0]['hodinova_mzda'];
        $procentu_hrube_mzdy = round(($pocetHodin * $lekor[0]['hodinova_mzda']) / 100);
        $socialni_poj = $procentu_hrube_mzdy * 25;
        $zdravptmo_poj = $procentu_hrube_mzdy * 9;
        $super_hruba_mzda = $hruba_mzda + $socialni_poj + $zdravptmo_poj;
        $zaloha_na_dan = round(($super_hruba_mzda / 100) * 15);
        $sleva_na_poplatnika = 2070;

        $pojisteni_zamestnanec_socialni = round($procentu_hrube_mzdy * 6.5);
        $pojisteni_zamestnanec_zdravotni = round($procentu_hrube_mzdy * 4.5);


        $sleva_na_detech = 0;

        for ($i = 0; $i < $lekor[0]['pocet_deti']; $i++) {
            switch ($i) {
                case 0:
                    $sleva_na_detech += 1267;
                    break;

                case 1:
                    $sleva_na_detech += 1617;
                    break;

                default:
                    $sleva_na_detech += 2017;
                    break;

            }


        }


        $html = "    
                           <h1>Výplatní list autoškola " . $school->getName() . "</h1>
                    Rok: " . date("Y") . "  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                     &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;IČO: " . $school->getIco() . "
                       &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                     &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Datum: " . $datum . "
                        <div style='    border-bottom: 2px solid black;'>

                        </div>
                        Jméno:  " . $lekor[0]['name'] . " " . $lekor[0]['surname'] . "         <br />
                    Datum narození: " . date('d.m.Y', strtotime($lekor[0]['date_birth'])) . "<br />
                    Období: " . $obdovi . "      <br />
                    
                    Hodinová mzda: " . $lekor[0]['hodinova_mzda'] . "  <br />
                    
                  Děti: " . $lekor[0]['pocet_deti'] . "
                    
                    <div style='border-bottom: 1px solid black;'>
                          &nbsp;
                     </div>
                     Počet hodin: " . $pocetHodin . "
                     <br />
                     Hrubá mzda:  " . $hruba_mzda . "
                        <div style='border-bottom: 1px solid black;'>
                          &nbsp;
                     </div>
                              Zdanitelný příjem
                                 <br />
                     Socialní:  " . $socialni_poj . "
                     <br />
                     Zdravotní:  " . $zdravptmo_poj . "
                     <br />
                     Superhrubá mzda: " . $super_hruba_mzda . "
                       <div style='border-bottom: 1px solid black;'>
                          &nbsp;
                     </div>
                      Záloha na daň před slevou:  " . $zaloha_na_dan . "
                              <br />
                      Sleva na poplatníka: " . $sleva_na_poplatnika . "
                                <br />
                      Sleva na dětech: " . $sleva_na_detech . "
                                                      <br />
                      
                      Záloha na dani: " . $this->prevedCisloNaNulu($zaloha_na_dan - $sleva_na_detech - $sleva_na_poplatnika) . "
                      
                      <div style='border-bottom: 1px solid black;'>
                           &nbsp; 
                     </div>
                              
                  Pojištění zaměstnance:
                   <br />
                     Socialní:  " . $pojisteni_zamestnanec_socialni . "
                     <br />
                     Zdravotní:  " . $pojisteni_zamestnanec_zdravotni . "
                     <br />
                     <br />
                    <h3>Čistá mzda: " . ($hruba_mzda - $pojisteni_zamestnanec_socialni - $pojisteni_zamestnanec_zdravotni - $this->prevedCisloNaNulu($zaloha_na_dan - $sleva_na_detech - $sleva_na_poplatnika)) . " </h3>
                    
                    
";

        return $html;
    }


}