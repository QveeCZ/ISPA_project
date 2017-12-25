<?php

namespace ApiBundle\Controller;

use CourseBundle\Entity\Registration;
use CourseBundle\Entity\Ride;
use SchoolBundle\Entity\Car;
use SchoolBundle\Entity\Lector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    public function apiAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $hash = $request->request->get('hash');
        $stamp = $request->request->get('stamp');
        $client = $request->request->get('client');
        $payload = $request->request->get('payload');

        $repoUser = $em->getRepository('UserBundle:User');

        /**
         * @var User $user
         */
        $user = $repoUser->findOneBy(array("username" => $client));

        $currTime = time();
        $stampAgrees = false;

        for ($i = 0; $i < 11; $i++){
            if($user && $stamp == hash_hmac('ripemd160', $currTime, $user->getSecret())){
                $stampAgrees = true;
                break;
            }
        }

        if(!$stampAgrees){
            $headers = array('Content-Type' => 'application/json');
            $response = new Response(json_encode(array('result' => "Bad request.")), 500, $headers);
            return $response;
        }

        $expected = hash_hmac('ripemd160', $payload, $user->getSecret());

        if(!hash_equals($expected, $hash)){
            $headers = array('Content-Type' => 'application/json');
            $response = new Response(json_encode(array('result' => "Forbidden.")), 403, $headers);
            return $response;
        }

        $payloadArray = json_decode($payload);

        /**
         * @var Registration $registration
         * @var Lector $lector
         * @var Car $car
         */
        $repoRegistration = $em->getRepository('CourseBundle:Registration');
        $registration = $repoRegistration->find($payloadArray["registration"]);
        $repoLector = $em->getRepository('Schoolbundle:Lector');
        $lector = $repoLector->findBy(array("email" => $payloadArray["lector"]));
        $repoCar = $em->getRepository('Schoolbundle:Car');
        $car = $repoCar->findBy(array("spz" => $payloadArray["car"]));
        $length = intval($payloadArray["length"]);

        if(!$registration || !$lector || !$car || !$length || $registration->getCourse()->getSchool()->getId() != $user->getSchool()->getId()){
            $headers = array('Content-Type' => 'application/json');
            $response = new Response(json_encode(array('result' => "Bad request.")), 500, $headers);
            return $response;
        }

        $date = new \DateTime();

        $ride = new Ride();
        $ride->setCourseRegistration($registration);
        $ride->setLector($lector);
        $ride->setCar($car);
        $ride->setLength($length);
        $ride->setDateRide($date);

        $repoRide = $em->getRepository('CourseBundle:Ride');;

        if(count($repoRide->findBy(array("registration" => $registration->getId(), "dateRide" => $date))) > 2){
            $headers = array('Content-Type' => 'application/json');
            $response = new Response(json_encode(array('result' => "Rides for " . $date->format("dd.mm.YYYY") . " are already set.")), 500, $headers);
            return $response;
        }

        $em->persist($ride);
        $em->flush();

        $headers = array('Content-Type' => 'application/json');
        $response = new Response(json_encode(array('result' => "OK")), 200, $headers);
        return $response;
    }
}
