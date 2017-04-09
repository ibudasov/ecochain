<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;

class DefaultController extends Controller
{
    /**
     * @Get("/", name="root")
     * @View()
     */
    public function rootAction(): array
    {
        return [];
    }
}
