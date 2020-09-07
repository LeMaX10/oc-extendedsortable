<?php namespace LeMaX10\ExtendedSortable\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use LeMaX10\ExtendedSortable\Behaviors\SortableModel;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class AddSortableColumnCommand
 * @package RDLTeam\ExtendedSortable\Console
 */
class AddSortableColumnCommand extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'extendedsortable:create';

    /**
     * @var string The console command description.
     */
    protected $description = 'Создание столбца сортировки для модели';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle(): void
    {
        try {;
            $modelClass = $this->option('model');
            if (!class_exists($modelClass)) {
                throw new \Exception('Модель не существует');
            }

            $model = new $modelClass;
            /** @TODO исправить костыль, временный */
            $implements = (array) $model->implement;
            if (!in_array(SortableModel::class, $implements)) {
                throw new \Exception('Модели не присвоен '. SortableModel::class .' behavior');
            }

            \Schema::table($model->getTable(), static function (Blueprint $table) use ($model) {
                $table->integer($model->getSortOrderColumn());
            });

            if ($this->option('convert')) {
                \DB::table($model->getTable())
                    ->update([
                        $model->getSortOrderColumn() =>  \DB::raw($model->getKeyName())
                    ]);
            }

            $this->line('Успешно добавлен столбец '. $model->getSortOrderColumn());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * @return array|array[]
     */
    public function getOptions()
    {
        return [
            ['--model', null, InputOption::VALUE_REQUIRED, 'Модель для создания столбца', null],
            ['--convert', null, InputOption::VALUE_NONE, 'Конвертировать столбец', null]
        ];
    }
}
