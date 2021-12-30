<?php

namespace Eduxplus\EduxBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Eduxplus\EduxBundle\DataFixtures\Fixtures\MenuFixtures;
use Eduxplus\EduxBundle\DataFixtures\Fixtures\OptionsFixtures;
use Doctrine\Persistence\ObjectManager;

class InstallFixtures extends Fixture
{
    protected $menuFixtures;
    protected $optionsFixtures;

    public function __construct(
        MenuFixtures $menuFixtures,
        OptionsFixtures $optionsFixtures
    ) {
        $this->optionsFixtures =$optionsFixtures;
        $this->menuFixtures =$menuFixtures;
    }


   public function load(ObjectManager $manager)
    {
         $this->optionsFixtures->load();
         $this->menuFixtures->load(1);
    }

}
