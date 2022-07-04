---
layout: default
title: Collection
parent: Support
nav_order: 1
---
# Collection

- [# Introduction](#-introduction)
- [# Basic Collection](#-basic-collection)
- - [# Creating Basic Collection](#-creating-basic-collection)
- - [# Passing array to Collection](#-passing-array-to-collection)
- [# Index Collection](#-index-collection)
- - [# Creating Index Collection](#-creating-index-collection)
- [# Lazy Collection](#-lazy-collection)
- - [# Creating Lazy Collection](#-creating-lazy-collection)
- [# Object Collection](#-object-collection)
- - [# Creating Object Collection](#-creating-object-collection)
- [# Iterating Over Collection](#-iterating-over-collection)
- [# Serialize and Unserialize Collection](#-serialize-and-unserialize-collection)
- - [# JSON Serialize](#-json-serialize)
- [# Method Listing](#-method-listing)
- - [# add](#-add)
- - [# all](#-all)
- - [# chunk](#-chunk)
- - [# collapse](#-collapse)
- - [# combine](#-combine)
- - [# contains](#-contains)
- - [# count](#-count)
- - [# differenceAssoc](#-differenceassoc)
- - [# differenceKeys](#-differencekeys)
- - [# differenceValues](#-differencevalues)
- - [# duplicated](#-duplicated)
- - [# each](#-each)
- - [# every](#-every)
- - [# except](#-except)
- - [# filter](#-filter)
- - [# get](#-get)
- - [# getSize](#-getsize)
- - [# isset](#-isset)
- - [# map](#-map)
- - [# merge](#-merge)
- - [# mergeRecursive](#-mergerecursive)
- - [# only](#-only)
- - [# pad](#-pad)
- - [# partition](#-partition)
- - [# pop](#-pop)
- - [# push](#-push)
- - [# pluck](#-pluck)
- - [# random](#-random)
- - [# replace](#-replace)
- - [# reject](#-reject)
- - [# reverse](#-reverse)
- - [# search](#-search)
- - [# shuffle](#-shuffle)
- - [# serialize](#-serialize)
- - [# set](#-set)
- - [# setSize](#-setsize)
- - [# shift](#-shift)
- - [# slice](#-slice)
- - [# splice](#-splice)
- - [# toJSON](#-tojson)
- - [# unique](#-unique)
- - [# unset](#-unset)
- - [# unshift](#-unshift)
- - [# walk](#-walk)

## # Introduction

Collection is a wrapper for creating and managing list of data like arrays, objects, files etc.

FireHub offers lots of different collection types you can work with. Some of them are more focused
on speed,  some on memory consumption and some are specialized to handle special data types like
objects, files etc.

Once you instantiate `Collection` class you will be presented with a couple of static method that
represents different collection types.

All collections currently in FireHub are considered to be _lazy_, means that function in `Collection`
static method will not fill collection entities until you actually need them or ask for them.
Our collections in examples bellow won't produce any results until we ask for collection items or try to do
some other function on top of our initial function.
***

## # Basic Collection

Basic Collection type is collection that has main focus of performance and doesn't concern
itself about memory consumption
This collection can hold any type of data.
***

### # Creating Basic Collection

```php
> Collection::create(callable $source):\FireHub\Support\Collections\Types\Array_Type
```

Basic Collection, or sometime called Array Collection can be instantiated when calling `create` static
method.
`create` method accepts only one argument, anonymous or arrow function.

Thing to remember is that anonymous function requested by the `create` method must always return array.

Let's try to create Basic Collection from list of numbers.


```php
use FireHub\Support\Collections\Collection;

$collection = Collection::create(function ():array {
    for($i = 0; $i < 1_000_000; $i++) {
        $list[$i] = $i++;
    }
    return $list ?? [];
});
```
***

### # Passing array to Collection

If you already have an array that you just want to pass it to collection and use collection features
on it, you can do it like on the example bellow.

This is good example if you have small array, and you just need to have all the features that collection
offers, but if you have large array it is always better to try to create array inside the `create`
method.

```php
use FireHub\Support\Collections\Collection;

$example_array = [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
];

// anonymous style collection
$collection = Collection::create(function () use ($example_array):array {
    return $example_array;
});

// arrow function style collection
$collection = Collection::create(fn ():array => $example_array);
```
***

## # Index Collection

Index Collection allows only integers as keys, but it is faster and uses less memory than
basic collection.  
This collection type must be resized manually and allows only integers within the range
as indexes.
***

### # Creating Index Collection

```php
> Collection::index(callable $source):\FireHub\Support\Collections\Types\Index_Type
```

Index Collection can be instantiated when calling `index` static method.  
`index` method accepts two arguments, anonymous or arrow function and size argument.

Anonymous function requested by the `index` method should not return any results.
Inside out anonymous function parameter `$items` represents [SplFixedArray](https://www.php.net/manual/en/class.splfixedarray),
which you can type-hint to get more support from your IDE.  
Adding more data to you Index Collection is like adding to any kind of normal PHP array using `$items[$key] = $value`.

Size argument is required and lets you change the size of an array to the new size of size. If size is less than the current array size,
any values after the new size will be discarded. If size is greater than the current array size,
the array will be padded with null values.

Let's try to create Index Collection from list of numbers.


```php
use FireHub\Support\Collections\Collection;

$collection = Collection::index(function ($items):void {
    for($i = 0; $i < 1_000_000; $i++) {
        $items[$i] = $i;
    }
}, size: 1_000_000);
```
***

## # Lazy Collection

Lazy Collection uses to power of [PHP Generators](https://www.php.net/manual/en/language.generators.overview.php)
and allow you to work with very large datasets while keeping memory usage low.

While it will keep memory usage low at any array size, it will take a performance hit while
doing so.
***

### # Creating Lazy Collection

```php
> Collection::lazy(callable $source):\FireHub\Support\Collections\Types\Lazy_Type
```

Lazy Collection can be instantiated when calling `lazy` static method.  
`index` method accepts two arguments, anonymous or arrow function and size argument.

Anonymous function requested by the `lazy` method should return PHP Generator.

Let's try to create Lazy Collection from list of numbers.


```php
use FireHub\Support\Collections\Collection;

$collection = Collection::lazy(function ():Generator {
    for($i = 0; $i < 1_000_000; $i++) {
        yield $i;
    }
});
```
***

## # Object Collection

While any collection can store objects, Object collection is specialized to store large amount
of them.
***

### # Creating Object Collection

```php
> Collection::object(callable $source):\FireHub\Support\Collections\Types\Object_Type
```

Object Collection can be instantiated when calling `object` static method.  
`index` method accepts two arguments, anonymous or arrow function and size argument.
Adding more data to you Object Collection is like adding to any kind of normal PHP array using `$items[$key] = $value`.

Anonymous function requested by the `object` method should not return any results.
Inside out anonymous function parameter `$items` represents [SplObjectStorage](https://www.php.net/manual/en/class.splobjectstorage),
which you can type-hint to get more support from your IDE.

Let's try to create Object Collection from list of numbers.


```php
use FireHub\Support\Collections\Collection;

$collection = Collection::object(function ($items):void {
    for($i = 0; $i < 1_000; $i++) {
        $items[new class {}] = $i;
    }
});
```
***

## # Iterating Over Collection

Since our collections are _lazy_ and don't produce any results while we crete them, one way to invoking
them is to iterate over them.

You can iterate over any collection just like you would with any other normal PHP array,
using loops `foreach`, `for`, `while` etc.

```php
use FireHub\Support\Collections\Collection;

$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

foreach ($collection as $key => $value) {
    echo "key = $key, value = $value; ";
}

// result:
// key = firstname, value = John; key = lastname, value = Doe; key = age, value = 25; 
```
***

## # Serialize and Unserialize Collection

All Collection have ability to create a string containing a byte-stream representation of any value that
can be stored in PHP called _serialize_, and ability to recreate the original variable values
called _unserialize_.

> note: both _serialize_ and _unserialize_ only work with actual data stored inside Collection,
so you don't need to worry about any other data leaking out from them.

```php
use FireHub\Support\Collections\Collection;

$collection = Collection::create(fn ():array => [1,2,3]);

foreach ($collection as $key => $value) {
    echo "key = $key, value = $value; ";
}

// result:
// key = 0, value = 1; key = 1, value = 2; key = 2, value = 3;

$serialize = serialize($collection);

echo $serialize;

// result:
// O:44:"FireHub\Support\Collections\Types\Array_Type":3:{i:0;i:1;i:1;i:2;i:2;i:3;}

$unserialize_collection = unserialize($serialize);

foreach ($unserialize_collection as $key => $value) {
    echo "key = $key, value = $value; ";
}

// result:
// key = 0, value = 1; key = 1, value = 2; key = 2, value = 3; 
```
***

### # JSON Serialize

Collection can be serialized to JSON with `json_encode` function.

```php
use FireHub\Support\Collections\Collection;

$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$json_serialize = json_encode($collection);

echo $json_serialize;

// result:
// {"firstname":"John","lastname":"Doe","age":25}
```
***

## # Method Listing

Bellow is a list of all available methods you can use on the collections.

Not all collection types will have available all these methods, so we will list all collection that
can use each method in separate table.
***

### # add

```php
> add(int|string $key, mixed $value):void // basic
> add(object $key, mixed $info):void // object
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | yes

Adds an item at the collection.

If key already exist, method will throw error.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->add('height', '190cm');

print_r($collection->all());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [height] => 190cm )
```
***

### # all

```php
> all():array
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Method all gives you ability to read underlying array represented of the collection.

This method is discouraged to use in production because it will revert your collection
back into normal PHP array, and you will get performance hit out of it.  
Instead, you can use this method to debug your collection.

```php
$collection = Collection::create(fn ():array => [1,2,3]);

$result = $collection->all();

print_r($result);

// result:
// Array ( [0] => 1 [1] => 2 [2] => 3 ) 
```
***

### # chunk

```php
> chunk(int $size, callable $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Breaks this collection into smaller collections and applies user function on each
collection items.

First parameter is size of each collection, and the second parameter is callable function
which will be applied to each item on each collection.

Each $collection parameter inside callable function is instance of new collection.
Means that after chunking, you can apply any collection method to it.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25,
    'height' => '190cm',
    'gender' => 'male'
]);

$collection->chunk(2, function ($collection):void {
    $collection->add('info', 'more info');
    print_r($collection->all());
});

// result:
// Array ( [firstname] => John [lastname] => Doe [info] => more info )
// Array ( [age] => 25 [height] => 190cm [info] => more info ) 
// Array ( [gender] => male [info] => more info ) 
```
***

### # collapse

```php
> collapse():self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Collapses a collection of arrays into a single, flat collection.

```php
$collection = Collection::create(fn ():array => [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
]);

$collapse = $collection->collapse();

print_r($collection->all());

// result:
// Array ( [0] => 1 [1] => 2 [2] => 3 [3] => 4 [4] => 5 [5] => 6 [6] => 7 [7] => 8 [8] => 9 )
```
***

### # combine

```php
> combine(self|array $values):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Creates a collection by using one collection or array for keys and another for its values.  
Parameter `$values` can be new collection instance or normal PHP array.

> note: Original collection values, the one that was used as keys for combined collection,
> need to be either strings or integers.

> note: Current and combined collection need to have the same number of items.

```php
$keys = Collection::create(fn ():array => [
    'firstname', 'lastname', 'age'
]);

$values = Collection::create(fn ():array => [
    'John', 'Doe', 25
]);

$combine = $collection->combine($values);

print_r($combine->all());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 ) 
```

You can also use normal array to combine.

```php
$keys = Collection::create(fn ():array => [
    'firstname', 'lastname', 'age'
]);

$combine = $collection->combine(['John', 'Doe', 25]);

print_r($combine->all());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 ) 
```
***

### # contains

```php
> contains(mixed $search):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Determines whether the collection contains a given item.

```php
$collection = Collection::create(fn ():array => [1,2,3]);

$contains = $collection->contains(function ($key, $value):bool {
    return $value > 2;
});

var_dump($contains);

// result:
// true
```

Other than calling method with function, you can do it with any kind of data type.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$contains = $collection->contains('Doe');

var_dump($contains);

// result:
// true
```

Example how to call method with Index Collection.

```php
$collection = Collection::index(function ($items):void {
    $items[0] = 'one';
    $items[1] = 'two';
    $items[2] = 'three';
}, size: 3);

$contains = $collection->contains(function ($value):bool {
    return $value === 'one';
});

var_dump($contains);

// result:
// true
```

Example how to call method with Object Collection.

```php
$collection = Collection::object(function ($items):void {
    $items[new class{}] = 'first class';
    $items[new class{}] = 'second class';
    $items[new class{}] = 'third class';
});

$contains = $collection->contains(function ($object, $info):bool {
    return $info === 'third class';
});
var_dump($contains);

// result:
// true
```
***

### # count

```php
> count():int
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Count method counts all items inside collection.

You can count items in two different ways:

- using count method

```php
$collection = Collection::create(fn ():array => [1,2,3]);

echo $collection->count();

// result:
// 3
```

- using count function

```php
$collection = Collection::create(fn ():array => [1,2,3]);

echo count($collection);

// result:
// 3
```
***

### # differenceAssoc

```php
> differenceAssoc(self|array ...$compares):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Computes the difference of collections or arrays with additional index check.

Compare collections or arrays against collection or array and returns the difference.  
Unlike [differenceValues](#-differencevalues), the keys are also used in the comparison.

> note: method accepts boot collections and PHP arrays.

> note: you can put as many as you like collections or arrays in this method.

```php
$collection = Collection::create(fn ():array => ["a" => "green", "b" => "brown", "c" => "blue", "red"]);

$new_collection = Collection::create(fn ():array => ["a" => "green", "yellow", "red"]);

$diff = $collection->differenceAssoc($new_collection);

print_r($diff->all());

// result:
Array ( [b] => brown [c] => blue [0] => red ) 
```
***

### # differenceKeys

```php
> differenceKeys(self|array ...$compares):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Computes the difference of collections or arrays using keys for comparison.

Compares the keys from array against the keys from collection or array and returns the difference.  
This method is like [differenceValues](#-differencevalues), except the comparison is done on the keys instead of the values.

> note: method accepts boot collections and PHP arrays.

> note: you can put as many as you like collections or arrays in this method.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$new_collection = Collection::create(fn ():array => [
    'myfirstname' => 'John',
    'mylastname' => 'Doe',
    'age' => 25
]);

$diff = $collection->differenceKeys($new_collection);

print_r($diff->all());

// result:
// Array ( [firstname] => John [lastname] => Doe ) 
```
***

### # differenceValues

```php
> differenceValues(self|array ...$compares):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Computes the difference of collections or arrays.

Compares existing collection against one or more other collection or array
and returns the values in the new collection that are not present in any of the other collections.

> note: method accepts boot collections and PHP arrays.

> note: you can put as many as you like collections or arrays in this method.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$new_collection = Collection::create(fn ():array => [3,4,5,6,7]);

$diff = $collection->differenceValues($new_collection);

print_r($diff->all());

// result:
// Array ( [0] => 1 [1] => 2 ) 
```
***

### # duplicated
```php
> duplicated():self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Removes unique values from an array.

> note: method validates only values, and ignores keys.

```php
$collection = Collection::create(fn ():array => [2,3,3,3,5,6,6]);

$duplicates = $collection->duplicates();

print_r($duplicates);

// result:
// Array ( [2] => 3 [3] => 3 [6] => 6 ) 
```
***

### # each

```php
> each(callable $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Perform function on each item from collection.

> note: if you are working with large collections, it is better internal loop like `foreach`,
> `while`, `for` etc. because of the performance benefits.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->each(function ($key, $value) {
    echo "I'm key: $key, with value: $value";
});

// result:
// I'm key: firstname, with value: John
// I'm key: lastname, with value: Doe
// I'm key: age, with value: 25
```

You can do all kind of evaluating expressions on `each` method.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->each(function ($key, $value) {
    if ($key !== 'age') {
        echo "I'm key: $key, with value: $value";
    }
   
});

// result:
// I'm key: firstname, with value: John
// I'm key: lastname, with value: Doe
```

You can break the loop at any time by returning false.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->each(function ($key, $value) {
    if ($key === 'lastname') {
        return false;
    }
    echo "I'm key: $key, with value: $value";
});

// result:
// I'm key: firstname, with value: John
```

If you are using this method on fixed collection callable only required value parameter for
`each` method.

```php
$collection = Collection::index(function ($items):void {
    $items[0] = 'one';
    $items[1] = 'two';
    $items[2] = 'three';
}, size: 3);

$collection->each(function ($value) {
    echo "I'm value: $value";
});

// result:
// I'm value: one
// I'm value: two
// I'm value: three
```
***

### # every

```php
> every(callable $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Perform function on each item from collection.

> note: if you are working with large collections, it is better internal loop like `foreach`,
> `while`, `for` etc. because of the performance benefits.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$every = $collection->every(function ($key, $value):bool {
    return is_int($value);
});

echo $every;

// result:
// true
```

Example in object collection.

```php
$collection = Collection::object(function ($items):void {
    $items[new class{}] = 'first class';
    $items[new class{}] = 'second class';
    $items[new class{}] = 'third class';
});

$every = $collection->every(function ($object, $info):bool {
    return is_object($object);
});

echo $every;

// result:
// true
```
***

### # except

```php
> except(mixed ...$keys):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Get all items in the collection except for those with the specified keys.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$except = $collection->except('age', 'lastname');

print_r($except->all());

// result:
// Array ( [firstname] => John ) 
```
***

### # filter

```php
> filter(callable $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Filter elements of the Collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$filter = $collection->filter(function ($key, $value):bool {
    return $key === 'lastname';
});

print_r($filter->all());

// result:
// Array ( [lastname] => Doe ) 
```

Example filtering object in object collection.

```php
$collection = Collection::object(function ($items):void {
    $items[new class{}] = 'first class';
    $items[new class{}] = 'second class';
    $items[new class{}] = 'third class';
});

$filter = $collection->filter(function ($object, $info):bool {
    return $info === 'second class';
});
```
***

### # get

```php
> get(int|string $key):self // basic
> get(int $key):self // index
> get(object $key):self // object
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Gets item from collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

echo $collection->get('age');

// result:
// 25 
```

You can also use short PHP function.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

echo $collection['age'];

// result:
// 25 
```
***

### # getSize

```php
> getSize():int
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> no | yes | no | no

Gets the size of the array.

```php
$collection = Collection::index(function ($items):void {
    $items[0] = 0;
    $items[1] = 1;
    $items[2] = 2;
}, size: 3);

echo $collection->getSize();

// result:
// 3 
```
***

### # isset

```php
> isset(int|string $key):bool // basic
> isset(int $key):bool // index
> isset(object $key):bool // object
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Checks if item exist in the collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

echo $collection->isset('age');

// result:
// true 
```

You can also use short PHP isset function.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

echo isset($collection['age']);

// result:
// true 
```
***

### # map

```php
> map(callable $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Applies the callback to the collection items.

This method will create new collection.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$multiplied = $collection->map(function ($key, $value) {
    return $value * 2;
});
print_r($multiplied->all());

// result:
// Array ( [0] => 2 [1] => 4 [2] => 6 [3] => 8 [4] => 10 )
```
***

### # merge

```php
> merge(callable $callback):this
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Merge new collection with original one.

> note: If there are same keys on both collections, keys from new collection
will replace keys from original collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25,
    'gender' => 'female'
]);

$merge = $collection->merge(fn ():array => [
    'height' => '190cm',
    'gender' => 'male'
]);

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [gender] => male [height] => 190cm ) 
```

Merging with index collection.

Here second parameter `counter` represents first available key for merging collection,
and any subsequent key should increase by 1.

```php
$collection = Collection::index(function ($items):void {
    $items[0] = 0;
    $items[1] = 1;
    $items[2] = 2;
}, size: 3);

$merge = $collection->merge(function ($items, $counter):void {
    $items[$counter] = 0;
    $items[++$counter] = 1;
}, 2);

// result:
// Array ( [0] => 0 [1] => 1 [2] => 2 [3] => 0 [4] => 1 ) 
```
***

### # mergeRecursive

```php
> mergeRecursive(callable $callback):this
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Merges the elements of one or more arrays together so that the values of one are appended 
to the end of the previous one.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25,
    'gender' => 'female'
]);

$merge = $collection->mergeRecursive(fn ():array => [
    'height' => '190cm',
    'gender' => 'male'
]);

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [gender] => Array ( [0] => female [1] => male ) [height] => 190cm )  
```
***

### # only

```php
> only(mixed ...$keys):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Get all items in the collection with the specified keys.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$only = $collection->only('age', 'lastname');

print_r($only->all());

// result:
// Array ( [lastname] => Doe [age] => 25 ) Array ( [firstname] => John ) 
```
***

### # pad

```php
> pad(int $size, mixed $value):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Pad array to the specified length with a value.

You will get a copy of the input padded to size specified by pad_size with value pad_value.   
If pad_size is positive then the array is padded on the right, if it's negative then on the left.  
If the absolute value of pad_size is less than or equal to the length of the input then no padding takes place.

```php
$collection = Collection::create(fn ():array => ['one', 'two', 'three']);

$pad = $collection->pad(5, 'padded value');

print_r($pad->all());

// result:
// Array ( [0] => one [1] => two [2] => three [3] => padded value [4] => padded value ) 
```
***

### # partition

```php
> partition(callable $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Separate elements that pass a given truth test from those that do not.

> note: new partitioned collection will contain two child collections inside.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

[$passed, $failed] = $collection->partition(function ($key, $value):bool {
    return $value > 3;
});

print_r($passed->all());
print_r($failed->all());

// result:
// Array ( [3] => 4 [4] => 5 )
// Array ( [0] => 1 [1] => 2 [2] => 3 ) 
```
***

### # pop

```php
> pop():void
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | no

Removes an item at the end of the collection.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$collection->pop();

print_r($collection->all());

// result:
// Array ( [0] => 1 [1] => 2 [2] => 3 [3] => 4 ) 
```
***

### # push

```php
> push(mixed ...$values):void
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | no

Push an item at the end of the collection.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$collection->push(6,7,8);

print_r($collection->all());

// result:
// Array ( [0] => 1 [1] => 2 [2] => 3 [3] => 4 [4] => 5 [5] => 6 [6] => 7 [7] => 8 ) 
```
***

### # pluck

```php
> pluck(int|string $column, int|string|null $key = null):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Get the values from given key.

```php
$collection = Collection::create(fn ():array => [
    ['user_id' => 100, 'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    ['user_id' => 101, 'firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 22]
]);

$plucked = $collection->pluck('firstname');

print_r($plucked->all());

// result:
// Array ( [0] => John [1] => Jane ) 
```

Second parameter can be used as key index for plunked array.

```php
$collection = Collection::create(fn ():array => [
    ['user_id' => 100, 'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    ['user_id' => 101, 'firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 22]
]);

$plucked = $collection->pluck('firstname', 'user_id');

print_r($plucked->all());

// result:
// Array ( [100] => John [101] => Jane ) 
```

If duplicated key exist, the last matching element will be inserted.

```php
$collection = Collection::create(fn ():array => [
    ['user_id' => 100, 'firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    ['user_id' => 101, 'firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 22]
]);

$plucked = $collection->pluck('firstname', 'lastname');

print_r($plucked->all());

// result:
// Array ( [Doe] => Jane ) 
```
***

### # random

```php
> random(int $number = 1, bool $preserve_keys = false):mixed // basic
> random(int $number = 1):mixed // index
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | no

Pick one or more random values out of the collection.

```php
$collection = Collection::create(fn ():array => [
    "Neo", "Morpheus", "Trinity", "Cypher", "Tank"
]);

$random = $collection->random();

// result (random value):
// Morpheus
```

You can get more than one item from collection with first parameter.  
Then your result will be an array.

```php
$collection = Collection::create(fn ():array => [
    "Neo", "Morpheus", "Trinity", "Cypher", "Tank"
]);

$random = $collection->random(3);

// result (random value):
// Array ( [0] => Morpheus [1] => Trinity [2] => Tank )
```

You can also preserve your original keys with second parameter.

```php
$collection = Collection::create(fn ():array => [
    "Neo", "Morpheus", "Trinity", "Cypher", "Tank"
]);

$random = $collection->random(3, true);

// result (random value):
// Array ( [2] => Trinity [3] => Cypher [4] => Tank ) 
```

Our next example is index collection.  
Here there is no `$preserve_keys` parameter, because index collection is not key - value storage.

```php
$collection = Collection::index(function ($items):void {
    $items[0] = "Neo";
    $items[1] = "Morpheus";
    $items[2] = "Trinity";
    $items[3] = "Cypher";
    $items[4] = "Tank";
}, size: 5);

$random = $collection->random(2);

// result (random value):
// Array ( [0] => Cypher [1] => Morpheus ) 
```
***

### # replace

```php
> replace(int|string $key, mixed $value):void // basic
> replace(int $key, mixed $value):void // index
> replace(object $key, mixed $value):void // object
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Replaces an item at the collection.

If key doesn't exist, it will throw error.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->replace('firstname', 'Jane');

print_r($collection->all());

// result:
// Array ( [firstname] => Jane [lastname] => Doe [age] => 25 ) 
```
***

### # reject

```php
> reject(callable $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Remove elements of the Collection.

This method is reverse from filter method.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$filter = $collection->reject(function ($key, $value):bool {
    return $key === 'lastname';
});

print_r($filter->all());

// result:
// Array ( [firstname] => John [age] => 25 ) 
```

Example rejecting object in object collection.

```php
$collection = Collection::object(function ($items):void {
    $items[new class{}] = 'first class';
    $items[new class{}] = 'second class';
    $items[new class{}] = 'third class';
});

$filter = $collection->reject(function ($object, $info):bool {
    return $info === 'second class';
});
```
***

### # reverse

```php
> reverse(bool $preserve_keys = false):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Reverse the order of collection items.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$reversed = $collection->reverse();

print_r($reversed->all());

// result:
// Array ( [age] => 25 [lastname] => Doe [firstname] => John ) 
```
***

### # search

```php
> search(mixed $value, int|string|false $second_dimension_column = false):int|string|false // basic
> search(mixed $value):int|false // index
> search(object $value):mixed // object
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Searches the collection for a given value and returns the first corresponding key if successful.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);
$search = $collection->search('John');

echo $search;

// result:
// firstname
```

On basic collection you can search second dimension on multidimensional array.

```php
$collection = Collection::create(fn ():array => [
    100 => ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    101 => ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 22]
]);

$search = $collection->search('John', 'firstname');

echo $search;

// result:
// 100
```
***

### # shuffle

```php
> shuffle(bool $preserve_keys = false):bool
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Searches the collection for a given value and returns the first corresponding key if successful.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->shuffle();

print_r($collection->all());

// result (random value):
// Array ( [0] => 25 [1] => John [2] => Doe ) 
```

If you want to preserve keys from original collection set shuffle parameter to true.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->shuffle(true);

print_r($collection->all());

// result (random value):
// Array ( [age] => 25 [firstname] => John [lastname] => Doe )  
```
***

### # serialize

```php
> serialize():string
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Serialize generates a storable representation of the collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$serialize = $collection->serialize();

echo $serialize;

// result:
// O:44:"FireHub\Support\Collections\Types\Array_Type":3:{s:9:"firstname";s:4:"John";s:8:"lastname";s:3:"Doe";s:3:"age";i:25;}
```
***

### # set

```php
> set(int|string $key, mixed $value):void // basic
> set(int $key, mixed $value):void // index
> set(object $key, mixed $value):void // object
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Sets an item at the collection.

If key already exists, it will replace the original value.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->set('height', '190cm');

print_r($collection->all());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [height] => 190cm )
```

You can also use short function to set the item.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection['height'] = '190cm';

print_r($collection->all());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [height] => 190cm )
```
***

### # setSize

```php
> setSize(int $size):bool
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> no | yes | no | no

setSize you change the size of the Index Collection to the new size. If size is less than the current array size,
any values after the new size will be discarded. If size is greater than the current array size,
the array will be padded with null values.

```php
$collection = Collection::index(function ($items):void {
    $items[0] = 0;
    $items[1] = 1;
    $items[2] = 2;
}, size: 3);

echo count($collection);

// result:
// 3

$collection->setSize(10);

echo count($collection);

// result:
// 10

print_r($collection->all());

// result:
// Array ( [0] => 0 [1] => 1 [2] => 2 [3] => [4] => [5] => [6] => [7] => [8] => [9] => ) 
```
***

### # shift

```php
> shift():void
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Removes an item at the beginning of the collection.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$collection->shift();

print_r($collection->all());

// result:
// Array ( [0] => 2 [1] => 3 [2] => 4 [3] => 5 ) 
```
***

### # slice

```php
> slice(int $offset, int $length = null, bool $preserve_keys = false):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Extract a slice of the collection.

> note: If offset is non-negative, the sequence will start at that offset in the collection.

> note: If offset is negative, the sequence will start that far from the end of the collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$slice = $collection->slice(1);

print_r($slice->all());

// result:
// Array ( [lastname] => Doe [age] => 25 ) 
```

Second optional parameter is length of the sliced collection.

> note: If length is given and is positive, then the sequence will have that many elements in it.

> note: If length is given and is negative then the sequence will stop that many elements from the end of the collection.

> note: If it is omitted, then the sequence will have everything from offset up until the end of the collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$slice = $collection->slice(1, 1);

print_r($slice->all());

// result:
// Array ( [lastname] => Doe ) 
```

Third optional parameter is preserve_keys.

> note: `slice` method will reorder and reset the integer array indices by default.
> This behaviour can be changed by setting preserve_keys to true.
> String keys are always preserved, regardless of this parameter.

$ar = array('a'=>'apple', 'b'=>'banana', '42'=>'pear', 'd'=>'orange');
print_r(array_slice($ar, 0, 3));
print_r(array_slice($ar, 0, 3, true));
```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25,
     10 => 'Male'
]);

$slice = $collection->slice(0, 4);

print_r($slice->all());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [0] => Male ) 

$slice = $collection->slice(0, 4, true);

print_r($slice->all());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [10] => Male ) 
```
***

### # splice

```php
> splice(int $offset, ?int $length = null, array $replacement = []):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Remove a portion of the array and replace it with something else.

> note: If offset is positive then the start of removed portion is at that offset from the beginning of the input collection.

> note: If offset is negative then it starts that far from the end of the input collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$splice = $collection->splice(1);

print_r($splice->all());

// result:
// Array ( [firstname] => John ) 
```

Second optional parameter is length, means number of elements that will be removed.

> note: If length is omitted, removes everything from offset to the end of the collection.

> note: If length is specified and is positive, then that many elements will be removed.

> note: If length is specified and is negative then the end of the removed portion will be that many elements from the end of the collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$splice = $collection->splice(1, 1);

print_r($splice->all());

// result:
// Array ( [firstname] => John [age] => 25 ) 
```

Third optional parameter is replacement array or collection.

> note: If replacement array is specified, then the removed elements are replaced with elements from this collection.

> note: If offset and length are such that nothing is removed, then the elements from the replacement array or collection are inserted in the place specified by the offset.

> note: Keys in replacement array are not preserved.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$splice = $collection->splice(1, 2, ['male', 'tall']);

print_r($splice->all());

// result:
// Array ( [firstname] => John [0] => male [1] => tall ) 
```
***

### # toJSON

```php
> toJSON():string|false
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Generates a JSON representation of the collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$json_serialize = $collection->toJSON();

echo $json_serialize;

// result:
// {"firstname":"John","lastname":"Doe","age":25}
```
***

### # unique
```php
> unique():self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Removes duplicate values from an array.

> note: method validates only values, and ignores keys.

```php
$collection = Collection::create(fn ():array => [2,3,3,3,5,6,6]);

$unique = $collection->unique();

print_r($unique);

// result:
// Array ( [0] => 2 [1] => 3 [4] => 5 [5] => 6 )  
```
***

### # unset

```php
> unset(int|string $key):void // basic
> unset(int $key):void // index
> unset(object $key):void // object
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Removes an item at the collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->unset('age');

print_r($collection->all());

// result:
// Array ( [firstname] => John [lastname] => Doe ) 
```

You can also use short PHP unset function.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

unset($collection['age']);

print_r($collection->all());

// result:
// Array ( [firstname] => John [lastname] => Doe ) 
```
***

### # unshift

```php
> unshift(mixed ...$values):void
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Push an item at the beginning of the collection.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$collection->unshift(6,7,8);

print_r($collection->all());

// result:
// Array ( [0] => 6 [1] => 7 [2] => 8 [3] => 1 [4] => 2 [5] => 3 [6] => 4 [7] => 5 ) 
```
***

### # walk

```php
> walk(callable $callback):this
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Apply a user supplied function to every collection item.

This method will modify your existing collection.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$collection->walk(function ($key, $value) {
    return $value * 2;
});
print_r($collection->all());

// result:
// Array ( [0] => 2 [1] => 4 [2] => 6 [3] => 8 [4] => 10 )
```
***