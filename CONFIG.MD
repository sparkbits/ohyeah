# CONFIG

## Introduction

This feature allows to manage configuration values in your solution. The configuration is designed using namespace which allows
to store diferent containers / namespaces of configuration.

each namespace can contain several configuration values based on key and value couples. Each key can accept names is a unique
string in the namespace context. The length is 32 chars and accept letters, numbers ans hyphens. The name must start with a
letter.

Internally, the config data is stored in stdClass which contains one stdClass per namespace. Each one can contain several properties
which each value. Values can be strings, numbers or arrays.

## Preparing your code

We assume you already `installed` the packet ohyeah in your project.

if you use an autoloader:

```Php
include "yourautoloader.php";

use ohyeah\config\config as config;
[...]
$config = new Config();
```

if you use the class manually, you must include all files required by config.php
```Php
require_once('/ohyeah/kernel/config/Iserialize.php');
require_once('/ohyeah/kernel/config/Iconfig.php');
require_once('/ohyeah/kernel/config/Configbase.php');
require_once('/ohyeah/kernel/config/Iserializecontract.php');
require_once('/ohyeah/kernel/config/Jsoncontract.php');
require_once('/ohyeah/kernel/config/Config.php');

use ohyeah\config\config as config;
[...]
$config = new Config();
```

## Store / retrieve data
You can select the namespace you want to operate or you can indicate, when you store
or retrieve data, what namespace is used. By default, if there is no namespace created,
the first time is created the namespace main. When you use set, if the ket exists, overwrite
the current value. Otherwise the key is created in the namespace you have choosen. if the key is
not found when you retrive it, the system triiger a RuntimeException.

```php
$config->set("my-superkey-one","a value","mynew-namespace); //store a value in the mynew-namespace
$config->set("my-superkey-one","a value"); //store a value in the current namespace
$config->setNamespace("main");
$config->set("my-superkey-one","a value"); //store value in main namespace.
$a = $config->get("my-superkey-one"); // Retrieve value from main::my-superkey-one
$a = $config->get("my-superkey-one","a value","mynew-namespace); //retrive the key from mynew-namespace
```

## Serialize / unserialize

You can import configuration stored by a class which implements Iserializecontract. And the same way,
you can export the configuration to persistence format for latter usage. How the data is defined is
indicated by the contract class. By default the contract class implements a Json format for your
configuration.

Reading config
```php
$config = new Config();
$config->Unserialize(file_put_contents("myconfig.json"));
```
Store config
```php
$config = new Config();
file_get_contents("myconfig.json",$config->Serialize());
```
You also can modify the contract class in execution time. It allows to use different ways to import/export
your configuration.

```php
$config = new Config();
$config->setContract(new Jsoncontract());
```

# ROADMAP

- [ ] implement merge class to allow combination of two configurations.
- [ ] implement delete method to delete keys.
- [ ] implement Inicontract to export / import ini files.


