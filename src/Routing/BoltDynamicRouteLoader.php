<?php

namespace Bolt\Routing;

use Bolt\Configuration\Config;
use Bolt\Controller\Frontend\DetailController;
use Bolt\Controller\Frontend\ListingController;
use Bolt\Controller\Frontend\TaxonomyController;
use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class BoltDynamicRouteLoader implements RouteLoaderInterface
{
    /** @var ContainerInterface $container */
    private $container;
    /** @var Config $config */
    private $config;

    public function __construct(ContainerInterface $container, Config $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    public function loadRoutes(): RouteCollection
    {
        $routes = new RouteCollection();

        $contentTypes = $this->config->get('contenttypes');
        $pluralContentTypeSlugs = $contentTypes->pluck('slug')->implode('|');
        $contentTypeSlugs = $contentTypes->pluck('slug')->concat($contentTypes->pluck('singular_slug'))->unique()->implode('|');

        $taxonomies = $this->config->get('taxonomies');
        $pluralTaxonomySlugs = $taxonomies->pluck('slug')->implode('|');
        $taxonomySlugs = $taxonomies->pluck('slug')->concat($taxonomies->pluck('singular_slug'))->unique()->implode('|');

//        ListingController::
//    /**
//     * @Route(
//     *     "/{contentTypeSlug}",
//     *     name="listing",
//     *     requirements={"contentTypeSlug"="%bolt.requirement.contenttypes%"},
//     *     methods={"GET|POST"})
//     * @Route(
//     *     "/{_locale}/{contentTypeSlug}",
//     *     name="listing_locale",
//     *     requirements={"contentTypeSlug"="%bolt.requirement.contenttypes%", "_locale": "%app_locales%"},
//     *     methods={"GET|POST"})
//     */
        $defaults = [
            '_controller' => ListingController::class . '::listing',
        ];
        $routes->add('listing', new Route('/{contentTypeSlug}', $defaults, [
            'contentTypeSlug' => $contentTypeSlugs
        ]));
        $routes->add('listing_locale', new Route('/{_locale}/{contentTypeSlug}/{slugOrId}', $defaults, [
            'contentTypeSlug' => $contentTypeSlugs,
            '_locale' => '.*',
        ]));

//        DetailController::
//    /**
//     * @Route(
//     *     "/{contentTypeSlug}/{slugOrId}",
//     *     name="record",
//     *     requirements={"contentTypeSlug"="%bolt.requirement.contenttypes%"},
//     *     methods={"GET|POST"})
//     * @Route(
//     *     "/{_locale}/{contentTypeSlug}/{slugOrId}",
//     *     name="record_locale",
//     *     requirements={"contentTypeSlug"="%bolt.requirement.contenttypes%", "_locale": "%app_locales%"},
//     *     methods={"GET|POST"})
//     *
//     * @param string|int $slugOrId
//     */
        $defaults = [
            '_controller' => DetailController::class . '::record',
        ];
        $routes->add('record', new Route('/{contentTypeSlug}/{slugOrId}', $defaults, [
            'contentTypeSlug' => $contentTypeSlugs
        ]));
        $routes->add('record_locale', new Route('/{_locale}/{contentTypeSlug}/{slugOrId}', $defaults, [
            'contentTypeSlug' => $contentTypeSlugs,
            '_locale' => '.*',
        ]));

//    /**
//     * @Route(
//     *     "/{taxonomyslug}/{slug}",
//     *     name="taxonomy",
//     *     requirements={"taxonomyslug"="%bolt.requirement.taxonomies%"},
//     *     methods={"GET|POST"}
//     * )
//     * @Route(
//     *     "/{_locale}/{taxonomyslug}/{slug}",
//     *     name="taxonomy_locale",
//     *     requirements={"taxonomyslug"="%bolt.requirement.taxonomies%", "_locale": "%app_locales%"},
//     *     methods={"GET|POST"}
//     * )
//     */
        $defaults = [
            '_controller' => TaxonomyController::class . '::listing',
        ];
        $routes->add('taxonomy', new Route('/{taxonomyslug}/{slug}', $defaults, [
            'taxonomyslug' => $taxonomySlugs
        ]));
        $routes->add('taxonomy_locale', new Route('/{_locale}/{taxonomyslug}/{slug}', $defaults, [
            'taxonomyslug' => $taxonomySlugs,
            '_locale' => '.*',
        ]));

        // routes for ContentEnditController::editFromSlug are still todo

        return $routes;
    }
}
