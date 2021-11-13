<?php

namespace App\Controller;

use App\Entity\Wallet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WalletController extends AbstractController
{
    public function __invoke(Wallet $data)
    {
        return $data->getCategories();
    }
}
