<?php

namespace SchoolBundle\Controller;
require_once 'mpdf/vendor/autoload.php';

use CourseBundle\Entity\Registration;
use CourseBundle\Form\Model\CourseRegistration;
use CourseBundle\Form\Type\RegistrationType;
use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query\ResultSetMapping;
use Dompdf\Dompdf;
use mPDF;
use SchoolBundle\Admin\LectorAdmin;
use SchoolBundle\Entity\Lector;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class LectorExportController extends Controller
{

    //OP FUNKCE V KONCTOLERU
    public function prevedCisloNaNulu($cislo)
    {
        if($cislo <0)
        {
            $cislo = 0;
        }

        return $cislo;
    }

    /**
     * @param null $courseId
     * @return Response
     */
    //prikald jak se to delat nema :D
    public function pdfAction($courseId = null)
    {
        $conn = $this->getDoctrine()->getConnection();
        $RAW_QUERY = 'SELECT * FROM lectors where id = '.$courseId;//SQL INJECTION
        $statement = $conn->prepare($RAW_QUERY);
        $statement->execute();

        $lekor = $statement->fetchAll();

        $RAW_QUERY = 'SELECT COUNT(l.id) FROM lectors as l inner join course_ride as cr on l.id = cr.lector_id WHERE l.id = '.$courseId.' group by l.id';
        $statement = $conn->prepare($RAW_QUERY);
        $statement->execute();
        $pocetHodin = $statement->fetchAll();
        //:'(
        $pocetHodin = $pocetHodin[0]['COUNT(l.id)'];
        //nebdu klikat jizdy rucne...
        $pocetHodin = $pocetHodin + 100;


        $datum = date("d.m.Y");
        $obdovi = date("Y-m", strtotime("-1 month", time()));

        $hruba_mzda = $pocetHodin*$lekor[0]['hodinova_mzda'];
        $procentu_hrube_mzdy = round(($pocetHodin*$lekor[0]['hodinova_mzda'])/100);
        $socialni_poj = $procentu_hrube_mzdy * 25;
        $zdravptmo_poj = $procentu_hrube_mzdy * 9;
        $super_hruba_mzda = $hruba_mzda + $socialni_poj + $zdravptmo_poj;
        $zaloha_na_dan = round(($super_hruba_mzda/100)* 15);
        $sleva_na_poplatnika = 2070;

        $pojisteni_zamestnanec_socialni = round($procentu_hrube_mzdy* 6.5);
        $pojisteni_zamestnanec_zdravotni = round($procentu_hrube_mzdy* 4.5);


        $sleva_na_detech = 0;

        for($i = 0;$i < $lekor[0]['pocet_deti'];$i++)
        {
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
     
                      <h1>Výplatní list </h1>
                    Rok: 2017  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                     &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;IČO: 1235468790
                       &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                     &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Datum: ".$datum."
                        <div style='    border-bottom: 2px solid black;'>

                        </div>
                    Jméno:  ".$lekor[0]['name']." ".$lekor[0]['surname']."         <br />
                    Datum narození: ".date('d.m.Y',strtotime($lekor[0]['date_medical']))."<br />
                    Období: ".$obdovi."      <br />
                    
                    Hodinová mzda: ".$lekor[0]['hodinova_mzda']."  <br />
                    
                  Děti: ".$lekor[0]['pocet_deti']."
                    
                    <div style='border-bottom: 1px solid black;'>
                          &nbsp;
                     </div>
                     Počet hodin: ".$pocetHodin."
                     <br />
                     Hrubá mzda:  ".$hruba_mzda."
                        <div style='border-bottom: 1px solid black;'>
                          &nbsp;
                     </div>
                              Zdanitelný příjem
                                 <br />
                     Socialní:  ".$socialni_poj."
                     <br />
                     Zdravotní:  ".$zdravptmo_poj."
                     <br />
                     Superhrubá mzda: ".$super_hruba_mzda."
                       <div style='border-bottom: 1px solid black;'>
                          &nbsp;
                     </div>
                      Záloha na daň před slevou:  ".$zaloha_na_dan."
                              <br />
                      Sleva na poplatníka: ".$sleva_na_poplatnika."
                                <br />
                      Sleva na dětech: ".$sleva_na_detech."
                                                      <br />
                      
                      Záloha na dani: ".$this->prevedCisloNaNulu($zaloha_na_dan - $sleva_na_detech - $sleva_na_poplatnika)."
                      
                      <div style='border-bottom: 1px solid black;'>
                           &nbsp; 
                     </div>
                              
                  Pojištění zaměstnance:
                   <br />
                     Socialní:  ".$pojisteni_zamestnanec_socialni."
                     <br />
                     Zdravotní:  ".$pojisteni_zamestnanec_zdravotni."
                     <br />
                     <br />
                    <h3>Čistá mzda: ".($hruba_mzda - $pojisteni_zamestnanec_socialni - $pojisteni_zamestnanec_zdravotni - $this->prevedCisloNaNulu($zaloha_na_dan - $sleva_na_detech - $sleva_na_poplatnika))." </h3>
                    
                    


      </body>
          </html>      ";
//        $html =  mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
//        $html = iconv('WINDOWS-1250', 'UTF-8//TRANSLIT', $html);

        $mpdf = new Mpdf(['mode' => 'c']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('vyplata.pdf', 'D');


        $response = new Response();

        $response->setContent($courseId);

        return $response;
    }


}