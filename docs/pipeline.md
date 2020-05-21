# Pipeline
Reading the articles of the Resources section first can get the clear concept.

## The Pipeline class  
Let's describe the Pipeline first:
1. The Pipeline accepts a **passable** thing, **sent** by the developer.
2. The passable thing is bound to be modified **through** a series of logics, which are called **pipes**.
3. Once the last pipe runs, only **then** you will receive the end result.

```php
$result = app(\Illuminate\Pipeline\Pipeline::class)
    ->send()
    ->through([])
    ->then(function($v) {
        return $v;
    });
```

### Starting the Pipeline
Using the app() helper and pointing out the Pipeline class.
```php
$result = app(\Illuminate\Pipeline\Pipeline::class);
```

### Sending a passable
- It can be a string, an array, a Collection, any object instance, whatever. 
- That objects are **passed by reference**.
```php
$result = app(\Illuminate\Pipeline\Pipeline::class)
    ->send('this should be correctly formatted');
```
Now that we have our "passable" inside the Pipeline instance.

### Through the pipes
To set the pipes, we can use an array of pipes as sole argument.  
These pipes can be:
- A Class name to be resolved and instanced using the Service Container.
- A Closure or invokable Class.
- An object Instance.
```php
$result = app(\Illuminate\Pipeline\Pipeline::class)
    ->send('this should be correctly formatted')
    ->through([
        AddPunctuation::class,
        InvokableClassToRemoveDuplicatedWords::class,
        'image',
        function ($passable, $next) {
          return $next(ucfirst($passable));
        },
        new RemoveDoubleSpacing(),
     ]);
```

#### The handle method
When you are using Classes as pipes, being one that instanced ourselves or resolved via de Service Container, these must have a common public method.  
By default, this method is handle(), that will receive and return the **passable**.
```php
class AddPunctuation
{
    public function handle($passable, \Closure $next)
    {
        return $next($passable);
    }
}
```

#### Via a handle (optionally)
If we are already using the handle for other logic, we can change the name of method by the via().
```php
$result = app(\Illuminate\Pipeline\Pipeline::class)
    ->send('this should be correctly formatted')
    ->through([...])
    ->via('modifyString');
```

```php
class AddPunctuation
{
    public function modifyString($passable, \Closure $next)
    {
        return $next($passable);
    }
}
```

### Then receive the result
The final step is to execute the Pipeline, and this is done using then() or thenReturn(). The Pipeline will be sleeping until you retrieve the result.
- then()  
This method accepts a Closure that receives the end result (what the last pipe returns) as only parameter. You can modify the resulting data in the last instance without needing to push another pipe. 
```php
$result = app(\Illuminate\Pipeline\Pipeline::class)
    ->send($query)
    ->through([...])
    ->then(function($query) {
        return $query->get();
    });
```

- thenReturn()  
This method just returns the end result as it goes out from the Pipeline.
```php
$result = app(\Illuminate\Pipeline\Pipeline::class)
    ->send('this should be correctly formatted')
    ->through([...])
    ->thenReturn();
```

## The Pipe
### Handling the data
When the Pipeline calls each Pipe, it will receive two arguments: the data being passed, and a Closure.
```php
public function handle($passable, Closure $next)
{
    // Your logic
}
```
You can do whatever you want inside the handling method to modify the content.
```php
public function handle($passable, Closure $next)
{
    $passable = ucfirst($passable);
}
```

The next step is to call the Closure that represents the next pipe in line and pass our modified data as the argument, otherwise everything will stop here.
```php
public function handle($passable, Closure $next)
{
    $passable = ucfirst($passable);
    return $next($passable);
}
```
This must be done in all the pipes, even if it is the last one, otherwise it won't advance.

### Jumping to the end
One of the magics tricks of a pipe is that it can automatically **jump** to the end, this will execute our code after the pipeline reaches the end result.
```php
public function handle($passable, Closure $next)
{
    $result = $next($passable);
    // Do something with the result
    return $result;
}
```

### Magic of pipe arguments
You can use arguments in each pipe by these ways: 

#### [Namespace\Class:argument1,argument2...](/tests/Unit/PipelineQuery1Test.php)  
Using a string with the syntax [Namespace\Class::argument1,argument2](https://github.com/laravel/framework/blob/5.8/src/Illuminate/Pipeline/Pipeline.php#L179). This will force the pipe to be instanced through the Service Container (even if it doesn't use any service of it) and pass them from the third argument onwards.
```php
$result = app(Pipeline::class)
    ->send(Post::query())
    ->through([
        WhereLike::class.':title,a',
        WhereLike::class.':content,b',
    ])
    ->then(function ($query) {
        return $query->get();
    });
```

Then, we can use the arguments in our [pipe class](/app/Services/Pipelines/Queries/Query1):
```php
public function handle($query, \Closure $next, ...$args)
{
    dd($args);
    return $next($query);
}
```
If the Class was registered in the Service Container with a friendly name, we can use that name instead of the Class:
```php
$result = app(\Illuminate\Pipeline\Pipeline::class)
    ->send('this should be correctly formatted')
    ->through([
        'replacer:should,formatted'
     ]);
```

#### [Instantiated Object](/Services/Pipelines/Queries/Query2)  
If the pipe is already an object Pipeline will just make a callable and pass it to the pipe as-is. There is no need to do any extra parsing and formatting since the object we're given was already a fully instantiated object.

[See](https://github.com/laravel/framework/blob/5.8/src/Illuminate/Pipeline/Pipeline.php#L155)

Just use the constructor.
```php 
$result = app(Pipeline::class)
    ->send(Post::query())
    ->through([
        new WhereLike('title', 'a'),
        new WhereLike('content', 'b'),
    ])
    ->then(function ($query) {
        return $query->get();
    });
```

In our pipe class you won't get data in the handle method.
```php
public function __construct($column, $content)
{
    $this->column = $column;
    $this->content = $content;
}

public function handle($query, \Closure $next)
{
    if ($this->content) {
        $query = $query->where($this->column, 'like', "%{$this->content}%");
    }
    
    return $next($query);
}
```

## Run 
```shell
vendor\bin\phpunit --group=pipeline
```

## Resources
- [Laravel 6 Advanced - e6 - Pipelines](https://www.youtube.com/watch?v=7XqEJO-wt7s&list=PLpzy7FIRqpGD5pN3-Y66YDtxJCYuGumFO&index=6)
- Laravel: The hidden Pipeline
    - [Part 1](https://medium.com/swlh/laravel-the-hidden-pipeline-part-1-a4ae91fc55a4)
    - [Part 2](https://medium.com/@DarkGhostHunter/laravel-the-hidden-pipeline-part-2-2c837e17a41e)
    - [Part 3](https://medium.com/@DarkGhostHunter/laravel-the-hidden-pipeline-part-3-e9b3f9a76c5c)
