<?php

namespace Buglerv\LaravelAlpineLivecrud;

use Illuminate\View\Component;

abstract class AlpineCrudAbstractComponent extends Component
{
    /**
     * Название ресурса.
     *
     * @var string
     */
	public $thingName = 'product';
	
    /**
     * Префикс используется при создании пути к контроллеру.
     *
     * @var string
     */
	public $routePrefix = 'api/';
	
    /**
     * Колонка базы данных, содержащая название.
     *
     * @var string
     */
	public $titleColumn = 'title';
	
    /**
     * Запускать ли автоматическое обновление при инициализации.
     *
     * @var boolean
     */
	public $autoload = true;
	
    /**
     * Название компонента, отображающего список вещей.
     *
     * @var string
     */
	public $listComponentName = 'product-view-list';
	
    /**
     * Массив колонок базы данных.
     *
     * @var array
     */
	public $columns = [];
	
    /**
     * Массив сообщений.
     *
     * @var array
     */
	public $messages = [];
	
    /**
     * Переводить ли массив сообщений и названия полей.
     *
     * @var boolean
     */
	protected $isTranslate = true;
	
    /**
     * Отображение в видах пустой вещи.
     *
     * @var array
     */
	public $emptyThing = [];
	
    /**
     * Создает экземпляр класса.
     *
     * @return void
     */
    public function __construct()
    {
		if($this->isTranslate){
			$this->translateMessages();
			$this->translateColumns();
		}
		
		$this->setEmptyThing();
    }
	
	protected function setEmptyThing()
	{
		$columns = array_map(function($column){
			return "'{$column}':''";
		},array_keys($this->columns));
		
		$this->emptyThing = '{' . join(',',$columns) . '}';
	}
	
    /**
     * Переводим массив столбцов базы данных.
     *
     * @return array
     */
	protected function translateColumns()
	{
		foreach($this->columns as $key => $column){
			$this->columns[$key] = __($column);
		}
	}
	
    /**
     * Переводим массив сообщений.
     *
     * @return array
     */
	protected function translateMessages()
	{
		foreach($this->messages as $key => $message){
			$this->messages[$key] = __($message);
		}
	}

    /**
     * Возвращает вид отображаения компонента.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('livecrud::components.things.index');
    }
}
