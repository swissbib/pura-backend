<?php

declare(strict_types=1);

namespace Publisher\Handler;

use Interop\Container\ContainerInterface;
use PuraUserModel\Repository\PuraUserRepositoryInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * ActivatePublisherFactory
 *
 * @category Swissbib_VuFind2
 * @package  ${PACKAGE}
 * @author   ${AUTHOR}
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://vufind.org
 * @link     http://www.swissbib.ch
 */
class RemovePublisherFactory implements FactoryInterface
{

    /**
     * @param \Interop\Container\ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return HomePageHandler|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var PuraUserRepositoryInterface $puraUserRepository */
        $puraUserRepository = $container->get(PuraUserRepositoryInterface::class);

        /** @var SwitchConfig $switchConfig */
        $switchConfig = $container->get('config')['switch_api'];
        return new RemovePublisherHandler($switchConfig, $puraUserRepository);
    }
}