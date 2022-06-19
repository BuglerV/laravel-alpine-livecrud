<?php

namespace Buglerv\Livecrud\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use LogicException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Storage;

class MakeLivecrudCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:livecrud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new livecrud component';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'component';

    /**
     * Suffix for class names.
     *
     * @var array
     */
    protected $suffixes = [
	    'component' => 'Component',
	    'model' => '',
	    'controller' => 'Controller',
	    'policy' => 'Policy',
	];

    /**
     * Array of namespaces.
     *
     * @var array
     */
    protected $namespaces = [
	    'component' => '\\View\\Components\\Livecrud',
	    'model' => '',
	    'controller' => '\\Http\\Controllers\\Livecrud',
	    'policy' => '\\Policies',
	];
	
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
		$this->createComponent();

        if ($this->option('all')) {
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('policy', true);
            $this->input->setOption('model', true);
        }

        if ($this->option('controller')) {
            $this->type = 'controller';
			parent::handle();
        }

        if ($this->option('policy')) {
            $this->type = 'policy';
			parent::handle();
        }

        if ($this->option('model')) {
            $this->type = 'model';
			parent::handle();
        }
		
        if ($this->option('migration')) {
            $this->createMigration();
        }
    }
	
    /**
     * @return void
     */
    protected function publishConcreteView($view)
    {
		$pathFrom = $this->resolveStubPath("/stubs/livecrud/view-{$view}.stub");
		
		$name = parent::getNameInput();
		$pathTo = resource_path("views/components/{$name}/view-{$view}.blade.php");
		
		$this->makeDirectory($pathTo);
		
		$this->files->copy($pathFrom,$pathTo);
		
		$this->info("View '{$name}.view-{$view}' created successfully.");
    }
	
    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createComponent()
    {	
		$views = $this->choice(
			'Wich view do you prefer to publish?',
			['all', 'list', 'grid', 'none'],
			0,
			$maxAttempts = null,
			$allowMultipleSelections = true
		);
	    
	    	parent::handle();
		
		if(in_array('none',$views)){
			return;
		}
		
		if(array_intersect($views,['all','list'])){
			$this->publishConcreteView('list');
		}
		
		if(array_intersect($views,['all','grid'])){
			$this->publishConcreteView('grid');
		}
    }
	
    /**
     * Create a migration file for a livecrud component.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename(parent::getNameInput())));

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->replaceUserNamespace(
            parent::buildClass($name)
        );
		
        $model = Str::studly(parent::getNameInput());

        return $model ? $this->replaceModel($stub, $model) : $stub;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
		return parent::getNameInput() . $this->suffixes[$this->type];
    }

    /**
     * Replace the User model namespace.
     *
     * @param  string  $stub
     * @return string
     */
    protected function replaceUserNamespace($stub)
    {
        $model = $this->userProviderModel();

        if (! $model) {
            return $stub;
        }

        return str_replace(
            $this->rootNamespace().'User',
            $model,
            $stub
        );
    }

    /**
     * Get the model for the guard's user provider.
     *
     * @return string|null
     *
     * @throws \LogicException
     */
    protected function userProviderModel()
    {
        $config = $this->laravel['config'];

        $guard = $this->option('guard') ?: $config->get('auth.defaults.guard');

        if (is_null($guardProvider = $config->get('auth.guards.'.$guard.'.provider'))) {
            throw new LogicException('The ['.$guard.'] guard is not defined in your "auth" configuration file.');
        }

        if (! $config->get('auth.providers.'.$guardProvider.'.model')) {
            return 'App\\Models\\User';
        }

        return $config->get(
            'auth.providers.'.$guardProvider.'.model'
        );
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @param  string  $model
     * @return string
     */
    protected function replaceModel($stub, $model)
    {
        $model = str_replace('/', '\\', $model);

        if (Str::startsWith($model, '\\')) {
            $namespacedModel = trim($model, '\\');
        } else {
            $namespacedModel = $this->qualifyModel($model);
        }

        $model = class_basename(trim($model, '\\'));

        $dummyUser = class_basename($this->userProviderModel());

        $dummyModel = Str::camel($model) === 'user' ? 'model' : $model;

        $replace = [
            '{{ namespacedModel }}' => $namespacedModel,
            '{{ model }}' => $model,
            '{{ modelVariable }}' => Str::camel($dummyModel),
			'{{ modelVariablePlural }}' => Str::plural(Str::camel($dummyModel)),
            '{{ user }}' => $dummyUser,
            '$user' => '$'.Str::camel($dummyUser),
        ];

        $stub = str_replace(
            array_keys($replace), array_values($replace), $stub
        );

        return preg_replace(
            vsprintf('/use %s;[\r\n]+use %s;/', [
                preg_quote($namespacedModel, '/'),
                preg_quote($namespacedModel, '/'),
            ]),
            "use {$namespacedModel};",
            $stub
        );
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath("/stubs/livecrud/{$this->type}.stub");
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
                        ? $customPath
                        : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
		if($this->type != 'model'){
			return $rootNamespace.$this->namespaces[$this->type];
		}
		
        return is_dir(app_path('Models')) ? $rootNamespace.'\\Models' : $rootNamespace;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, policy, controller and model for a livecrud component'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for a livecrud component'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for a livecrud component'],
            ['model', 'l', InputOption::VALUE_NONE, 'Create a new model for a livecrud component'],
            ['policy', 'p', InputOption::VALUE_NONE, 'Create a new policy for a livecrud component'],
            ['guard', 'g', InputOption::VALUE_OPTIONAL, 'The guard that the policy relies on'],
            ['force', null, InputOption::VALUE_NONE, 'Create the component even if a livecrud component already exists'],
        ];
    }
	
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the component'],
        ];
    }
}
