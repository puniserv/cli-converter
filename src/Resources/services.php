<?php
declare(strict_types=1);

use AdamDmitruczukRekrutacjaHRTec\Command\Csv;
use AdamDmitruczukRekrutacjaHRTec\Common\App;
use AdamDmitruczukRekrutacjaHRTec\Common\Manager;
use AdamDmitruczukRekrutacjaHRTec\Content\Factory;
use AdamDmitruczukRekrutacjaHRTec\Content\Provider\HttpXmlProvider;
use AdamDmitruczukRekrutacjaHRTec\Converters\Modifiers\RowModifier;
use AdamDmitruczukRekrutacjaHRTec\Converters\SimpleXmlToCsv;
use AdamDmitruczukRekrutacjaHRTec\Converters\ValueModifiers\DateTransform;
use AdamDmitruczukRekrutacjaHRTec\Converters\ValueModifiers\HtmlRemover;
use AdamDmitruczukRekrutacjaHRTec\Converters\ValueModifiers\LinkRemover;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

return function (ContainerConfigurator $container) {
    $services = $container->services();
    $services->set('SymfonyApp')
        ->class(Application::class);
    $services->set('Manager')
        ->class(Manager::class);
    $services->set('App')
        ->class(App::class)
        ->args([
            ref('SymfonyApp'),
            ref('Manager'),
        ])
        ->property('config'/** @uses App::$config */, [
            'timezone' => 'Europe/Warsaw',
            'locale' => [
                'pl_PL',
                'pl',
            ],
        ])
        ->property('commands'/** @uses App::$commands */, [
            ref('CSVCommandSimple'),
            ref('CSVCommandExtended'),
        ]);
    $services->set('CSVCommandSimple')
        ->class(Csv::class)
        ->property(
            'extend' /** @uses Csv::$extend */,
            false
        )
        ->args([
            'csv:simple',
            'Transform feed http link into csv file and overwrite existing file',
            ref('XmlFeedToCsvConverter'),
            ref('HttpXmlProvider')
        ]);
    $services->set('CSVCommandExtended')
        ->class(Csv::class)
        ->property(
            'extend' /** @uses Csv::$extend */,
            true
        )
        ->args([
            'csv:extended',
            'Transform feed http link into csv file and extend existing file',
            ref('XmlFeedToCsvConverter'),
            ref('HttpXmlProvider')
        ]);
    $services->set('XmlFeedToCsvConverter')
        ->class(SimpleXmlToCsv::class)
        ->args([
            ref('ContentFactory')
        ])
        ->property('columns'/** @uses SimpleXmlToCsv::$columns */, [
            'title' => 'title',
            'description' => 'description',
            'link' => 'link',
            'pubDate' => 'pubDate',
            'creator' => 'creator',
        ])
        ->property(
            'xpathDataToAppend'/** @uses SimpleXmlToCsv::$xpathDataToAppend */,
            '//channel//item'
        )
        ->property('modifiers'/** @uses SimpleXmlToCsv::$modifiers */, [
            ref('FeedItemModifier')
        ]);
    $services->set('ContentFactory')
        ->class(Factory::class);
    $services->set('FeedItemModifier')
        ->class(RowModifier::class)
        ->property('modifiers' /** @uses RowModifier::$modifiers */, [
            'description'=> [
                ref('ValueModifierHtmlRemover'),
                ref('ValueModifierLinkRemover'),
            ],
            'pubDate' => [
                ref('ValueModifierDateTransform'),
            ]
        ]);
    $services->set('ValueModifierHtmlRemover')
        ->class(HtmlRemover::class);
    $services->set('ValueModifierLinkRemover')
        ->class(LinkRemover::class);
    $services->set('ValueModifierDateTransform')
        ->class(DateTransform::class);
    $services->set('HttpXmlProvider')
        ->class(HttpXmlProvider::class)
        ->args([
            ref('Http')
        ]);
    $services->set('Http')
        ->class(GuzzleHttp\Client::class)
        ->args([

        ]);
};
