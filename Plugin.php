<?php namespace LeMaX10\ExtendedSortable;

use LeMaX10\ExtendedSortable\Behaviors\SortableController;
use LeMaX10\ExtendedSortable\Behaviors\SortableModel;
use LeMaX10\ExtendedSortable\Console\AddSortableColumnCommand;
use System\Classes\PluginBase;

/**
 * ExtendedSortable Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'ExtendedSortable',
            'description' => 'Плагин реализующий возможность расширять плагины сторонних разработчиков, добавляя возможность сортировки',
            'author'      => 'VladimirPyankov',
            'icon'        => 'icon-leaf'
        ];
    }

    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->registerConsoleCommand(
                'lemax10.extendedsortable.create_sortable_column',
                AddSortableColumnCommand::class
            );
        }
    }
}
