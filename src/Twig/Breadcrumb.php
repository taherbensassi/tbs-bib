<?php


namespace App\Twig;

use Symfony\Component\VarDumper\VarDumper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Breadcrumb extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('breadcrumb', [$this, 'breadcrumb']),
        ];
    }

    public function breadcrumb($path)
    {
        $dirs = explode('/', $path);
        if(!empty($dirs[3])){
            return ucwords(str_replace ("-", " ", $dirs[3]));
        }
        return "";
    }

}