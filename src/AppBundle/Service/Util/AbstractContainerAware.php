<?php
/**
 * Created by PhpStorm.
 * User: TheMa
 * Date: 17/02/2018
 * Time: 16:03
 */

namespace AppBundle\Service\Util;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class AbstractContainerAware implements ContainerAwareInterface
{
    use ContainerAwareTrait;
}