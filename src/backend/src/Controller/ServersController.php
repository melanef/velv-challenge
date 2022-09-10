<?php

namespace App\Controller;

use App\Param\ServerFilterParams;
use App\Repository\ServerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServersController extends AbstractController
{
    public function list(Request $request, ServerRepository $repository): Response
    {
        $params = new ServerFilterParams();

        if ($request->query->has('hdd_min')) {
            $params->hddMin = $request->query->get('hdd_min');
        }

        if ($request->query->has('hdd_max')) {
            $params->hddMax = $request->query->get('hdd_max');
        }

        if ($request->query->has('ram_options')) {
            $params->ramOptions = $request->query->all()['ram_options'];
        }

        if ($request->query->has('hdd_type')) {
            $params->hddType = $request->query->get('hdd_type');
        }

        if ($request->query->has('location')) {
            $params->location = $request->query->get('location');
        }

        return new JsonResponse($repository->fetch($params));
    }
}
