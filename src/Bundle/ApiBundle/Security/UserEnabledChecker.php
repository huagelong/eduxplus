<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/14 22:22
 */

namespace App\Bundle\ApiBundle\Security;

use App\Entity\BaseUser;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEnabledChecker implements UserCheckerInterface
{
    /**
     * Checks the user account before authentication.
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof BaseUser) {
            return;
        }

        if ($user->getIsLock()) {
            throw new DisabledException();
        }
    }

    /**
     * Checks the user account after authentication.
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user)
    {
        //检查权限等
//        throw new AccessDeniedException();
    }
}
