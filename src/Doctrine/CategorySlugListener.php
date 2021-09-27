<?php

namespace App\Doctrine;

use App\Entity\Category;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySlugListener
{
    public function __construct(protected SluggerInterface $slugger){

    }

    public function prePersist(Category $category){
        $category->setSlug(strtolower($this->slugger->slug($category->getName())));
    }
}