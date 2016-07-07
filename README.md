#  Class Loader

### Description
Load class file automatically with namespace.
Based on Symfony Psr4ClassLoader.

### Usage
If directory is as below:

- src
    - TestClass
        - Test1.php (Class Test1, namespace TestClass)
        - Sub
            - Test2.php (Class Test2, namespace TestClass\Sub
            
```
$register_path =[
    ['TestClass', 'TestClass', true], //[prefix, path, recursive], directory with first letter upper case will be loaded recursively.
];
$loader = new \ClassLoader\Loader(*path_to_src*, $register_path);
$loader->autoload();
// Class Test1 and Test2 do not need require
$test1 = new \TestClass\Test1();
$test2 = new \TestClass\Sub\Test2();
```

