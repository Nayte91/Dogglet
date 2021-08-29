<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;
}