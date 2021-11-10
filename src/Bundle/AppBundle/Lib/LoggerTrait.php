<?php

namespace App\Bundle\AppBundle\Lib;

use Psr\Log\LoggerInterface;

/**
 * Trait LoggerAwareTrait
 *
 * NOTE: Do not use this in your services, just inject `LoggerInterface` to
 *       service where you need it. This trait is just for quick debug purposes
 *       and nothing else.
 *
 *  {
 *  use \App\Util\LoggerTrait;
 *
 *  public function fooBar(): void
 *  {
 *  $this->logger->info('some log message');
 *  }
 *  }
 */
trait LoggerTrait
{
    /**
     * @var LoggerInterface
     */
    protected  $logger;

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }
}
