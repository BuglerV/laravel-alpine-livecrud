<?php

namespace Buglerv\LaravelAlpineLivecrud;

use Illuminate\View\Component;

abstract class AlpineCrudAbstractComponent extends Component
{
    /**
     * �������� �����.
     *
     * @var string
     */
	public $thingName = 'product';
	
    /**
     * ��䨪� �ᯮ������ �� ᮧ����� ��� � ����஫����.
     *
     * @var string
     */
	public $routePrefix = 'api/';
	
    /**
     * ������� ���� ������, ᮤ�ঠ�� ��������.
     *
     * @var string
     */
	public $titleColumn = 'title';
	
    /**
     * ����᪠�� �� ��⮬���᪮� ���������� �� ���樠����樨.
     *
     * @var boolean
     */
	public $autoload = true;
	
    /**
     * �������� ���������, �⮡ࠦ��饣� ᯨ᮪ ��饩.
     *
     * @var string
     */
	public $listComponentName = 'product-view-list';
	
    /**
     * ���ᨢ ������� ���� ������.
     *
     * @var array
     */
	public $columns = [];
	
    /**
     * ���ᨢ ᮮ�饭��.
     *
     * @var array
     */
	public $messages = [];
	
    /**
     * ��ॢ����� �� ���ᨢ ᮮ�饭�� � �������� �����.
     *
     * @var boolean
     */
	protected $isTranslate = true;
	
    /**
     * �⮡ࠦ���� � ����� ���⮩ ���.
     *
     * @var array
     */
	public $emptyThing = [];
	
    /**
     * ������� ������� �����.
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
     * ��ॢ���� ���ᨢ �⮫�殢 ���� ������.
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
     * ��ॢ���� ���ᨢ ᮮ�饭��.
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
     * �����頥� ��� �⮡ࠦ����� ���������.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('livecrud::components.things.index');
    }
}
