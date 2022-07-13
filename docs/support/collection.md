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
- [# Predefined collections](#-predefined-collections)
- - [# fill](#-fill)
- - [# fillAssoc](#-fillassoc)
- - [# fillKeys](#-fillkeys)
- - [# range](#-range)
- [# Method Listing](#-method-listing)
- - [# add](#-add)
- - [# all](#-all)
- - [# chunk](#-chunk)
- - [# collapse](#-collapse)
- - [# combine](#-combine)
- - [# contains](#-contains)
- - [# count](#-count)
- - [# countValues](#-countvalues)
- - [# difference](#-difference)
- - [# differenceAssoc](#-differenceassoc)
- - [# differenceKeys](#-differencekeys)
- - [# duplicated](#-duplicated)
- - [# each](#-each)
- - [# every](#-every)
- - [# except](#-except)
- - [# filter](#-filter)
- - [# first](#-first)
- - [# firstKey](#-firstkey)
- - [# flip](#-flip)
- - [# get](#-get)
- - [# getSize](#-getsize)
- - [# intersect](#-intersect)
- - [# intersectAssoc](#-intersectassoc)
- - [# intersectKey](#-intersectkey)
- - [# isAssociative](#-isassociative)
- -  [# isEmpty](#-isempty)
- - [# isMultiDimensional](#-ismultidimensional)
- - [# isset](#-isset)
- - [# keys](#-keys)
- - [# last](#-last)
- - [# lastKey](#-lastkey)
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
- - [# reduce](#-reduce)
- - [# replace](#-replace)
- - [# reject](#-reject)
- - [# reverse](#-reverse)
- - [# search](#-search)
- - [# shuffle](#-shuffle)
- - [# serialize](#-serialize)
- - [# set](#-set)
- - [# setSize](#-setsize)
- - [# shift](#-shift)
- - [# skip](#-skip)
- - [# skipUntil](#-skipuntil)
- - [# skipWhile](#-skipwhile)
- - [# slice](#-slice)
- - [# sort](#-sort)
- - [# sortBy+](#-sortby)
- - [# sortByKey](#-sortbykey)
- - [# sortByMany](#-sortbymany)
- - [# sortKeyBy](#-sortkeyby)
- - [# splice](#-splice)
- - [# take](#-take)
- - [# takeUntil](#-takeuntil)
- - [# takeWhile](#-takewhile)
- - [# tap](#-tap)
- - [# toArray](#-toarray)
- - [# toJSON](#-tojson)
- - [# union](#-union)
- - [# unique](#-unique)
- - [# unless](#-unless)
- - [# unset](#-unset)
- - [# unshift](#-unshift)
- - [# values](#-values)
- - [# walk](#-walk)
- - [# when](#-when)
- - [# where](#-where)
- - [# whereBetween](#-wherebetween)
- - [# whereContains](#-wherecontains)
- - [# whereDoesntContain](#-wheredoesntcontain)
- - [# whereNot](#-wherenot)
- - [# whereNotBetween](#-wherenotbetween)

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

## # Predefined collections

There are number of predefined collections made for quickly creating some basic collection shapes.

All of these methods bellow will have a choice to create some collection type as next method in
chain, and you will have new collection ready for you.

***

### # fill

```php
> fill (mixed $value, int $length):Fill
```

> Available for collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Fill the collection with values.

First parameter is value to use for filling.

Second parameter is number of elements to insert.

> note: When filling Object Collection, first parameter ($value) need to be class fully qualified name.

```php
$collection = Collection::fill('bananas', 5)->asBasic();

print_r($collection->toArray());

// result:
// Array ( [0] => bananas [1] => bananas [2] => bananas [3] => bananas [4] => bananas ) 
```
***

### # fillAssoc

```php
> fillAssoc (array $keys, array $values):FillAssoc
```

> Available for collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Fill the collection with keys and values.

```php
$collection = Collection::FillAssoc([1,2,3,4], ['a','b','c','d'])->asBasic();

print_r($collection->toArray());

// result:
// Array ( [1] => a [2] => b [3] => c [4] => d ) 
```
***

### # fillKeys

```php
> fillKeys (array $keys, mixed $value):fillKeys
```

> Available for collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | yes | no

Fill an array with values, specifying keys.

```php
$collection = Collection::FillKeys([1,2,3,4], 'bananas')->asBasic();

print_r($collection->toArray());

// result:
// Array ( [1] => bananas [2] => bananas [3] => bananas [4] => bananas ) 
```
***

### # range

```php
> range (string|int|float $start, string|int|float $end, int|float $step = 1):Range
```

> Available for collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | no

Creates the collection containing a range of items.

```php
$collection = Collection::range(1, 5)->asBasic();

print_r($collection->toArray());

// result:
// Array ( [0] => 1 [1] => 2 [2] => 3 [3] => 4 [4] => 5 ) 
```

There is an optional third parameter where you can specify step,
and it will be used as the increment between elements in the sequence.

```php
$collection = Collection::range('a', 'z', 5)->asBasic();

print_r($collection->toArray());

// result:
// Array ( [0] => a [1] => f [2] => k [3] => p [4] => u [5] => z ) 
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

print_r($collection->toArray());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [height] => 190cm ) 
```
***

### # all

```php
> all():array // basic
> all():SplFixedArray // index
> all():Generator // lazy
> all():SplObjectStorage // object
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Method all gives you ability to read underlying items represented of the collection.

```php
$collection = Collection::create(fn ():array => [1,2,3]);

$result = $collection-all();

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
    print_r($collection->toArray());
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

print_r($collapse->toArray());

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

$combine = $keys->combine($values);

print_r($combine->toArray());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 ) 
```

You can also use normal array to combine.

```php
$keys = Collection::create(fn ():array => [
    'firstname', 'lastname', 'age'
]);

$combine = $keys->combine(['John', 'Doe', 25]);

print_r($combine->toArray());

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
> count(bool $multi_dimensional = false):int // basic
> count():int // index, lazy, object
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

You can also count items in multidimensional collection using second parameter.

```php
$collection = Collection::create(fn ():array => [
    ['firstname' => ['name' => 'John', 'nickname' => 'Joe'], 'lastname' => 'Doe', 'age' => 25],
    ['firstname' => ['name' => 'Jane', 'nickname' => 'Jan'], 'lastname' => 'Doe', 'age' => 21],
    ['firstname' => ['name' => 'Richard', 'nickname' => 'Ricky'], 'lastname' => 'Roe', 'age' => 27]
]);

echo $collection->count(true);

// result:
// 18
```
***

### # countValues

```php
> countValues(null|int|string $key = null):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Count values from collection.

```php
$collection = Collection::create(fn ():array => [1,1,2,2,2,3,4,5,5,5,5]);

$countValues = $collection->countValues();

print_r($countValues->toArray());

// result:
// Array ( [1] => 2 [2] => 3 [3] => 1 [4] => 1 [5] => 4 ) 
```

You can also count individual items from multidimensional collection.

```php
$collection = Collection::create(fn ():array => [
    ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
    ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
]);

$countValues = $collection->countValues('lastname');

print_r($countValues->toArray());

// result:
// Array ( [Doe] => 2 [Roe] => 1 ) 
```
***

### # difference

```php
> difference(self|array ...$compares):self
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

print_r($diff->toArray());

// result:
// Array ( [0] => 1 [1] => 2 ) 
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

print_r($diff->toArray());

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

print_r($diff->toArray());

// result:
// Array ( [firstname] => John [lastname] => Doe ) 
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
> each(callable $callback):this
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

print_r($except->toArray());

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

print_r($filter->toArray());

// result:
// Array ( [lastname] => Doe ) 
```

Example filtering 2-dimensional collection.

```php
$collection = Collection::create(fn ():array => [
    ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
    ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
]);

$filter = $collection->filter(function ($key, $value):bool {
    return $value['age'] >= '25';
});

print_r($filter->toArray());

// result:
// Array ( [0] => Array ( [firstname] => John [lastname] => Doe [age] => 25 ) [2] => Array ( [firstname] => Richard [lastname] => Roe [age] => 27 ) ) 
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

### # first

```php
> first(Closure $callback = null):mixed
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Get first value from collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$first = $collection->first();

echo $first;

// result:
// John 
```

With optional parameter you can use function to get first value that passed truth test.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$first = $collection->first(function ($key):bool {
    return $key === 'lastname';
});

echo $first;

// result:
// Doe 
```
***

### # firstKey

```php
> firstKey(Closure $callback = null):null|int|string
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Get first key from collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$firstKey = $collection->firstKey();

echo $firstKey;

// result:
// firstname 
```

With optional parameter you can use function to get first key that passed truth test.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$firstKey = $collection->firstKey(function ($value):bool {
    return $value === 'Doe';
});

echo $firstKey;

// result:
// lastname 
```
***

### # flip

```php
> flip():self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Exchanges all keys with their associated values in collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$flip = $collection->flip();

print_r($flip->toArray());

// result:
// Array ( [John] => firstname [Doe] => lastname [25] => age ) 
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

### # intersect

```php
> intersect(self|array ...$collections):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> Yes | no | no | no

Computes the intersection of collections that contains the values in original collection
whose values exist in all collections from parameter.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'gender' => 'male',
    'age' => 25
]);

$intersected = $collection->intersect(
    ['firstname' => 'Richard', 'lastname' => 'Roe', 'gender' => 'male', 'age' => 27],
    Collection::create(fn ():array => ['name' => 'John', 'surname' => 'Roe', 'sex' => 'male', 'age' => 26])
);

print_r($intersected->toArray());

// result:
// Array ( [gender] => male )
```
***

### # intersectAssoc

```php
> intersectAssoc(self|array ...$collections):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> Yes | no | no | no

Computes the intersection of collections that contains the values in original collection
whose keys and values exist in all collections from parameter.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'gender' => 'male',
    'age' => 25
]);

$intersected = $collection->intersectAssoc(
    ['firstname' => 'Richard', 'lastname' => 'Roe', 'gender' => 'male', 'age' => 27],
    Collection::create(fn ():array => ['firstname' => 'John', 'lastname' => 'Roe', 'gender' => 'male', 'age' => 26])
);

print_r($intersected->toArray());

// result:
// Array ( [gender] => male )
```
***

### # intersectKey

```php
> intersectKey(self|array ...$collections):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> Yes | no | no | no

Computes the intersection of collections that contains all the values in original collection
whose keys exist in all collections from parameter.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'gender' => 'male',
    'age' => 25
]);

$intersected = $collection->intersectKey(
    ['firstname' => 'Richard', 'lastname' => 'Roe', 'gender' => 'male', 'age' => 27],
    Collection::create(fn ():array => ['name' => 'John', 'surname' => 'Roe', 'sex' => 'male', 'age' => 26])
);

print_r($intersected->toArray());

// result:
// Array ( [age] => 25 ) 
```
***

### # isAssociative

```php
> isAssociative():bool
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Checks if collection is associative.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

var_dump($collection->isAssociative());

// result:
// true 

$collection = Collection::create(fn ():array => [1,2,3,4,5]);

var_dump($collection->isAssociative());

// result:
// false 
```
***

### # isEmpty

```php
> isEmpty():bool
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Checks if collection is empty.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

var_dump($collection->isEmpty());

// result:
// false
```
***

### # isMultiDimensional

```php
> isMultiDimensional():bool
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Checks if collection is multidimensional.

> note: Any collection that has at least one item as array will be considered as multidimensional collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

var_dump($collection->isMultiDimensional());

// result:
// false 

$collection = Collection::create(fn ():array => [
    'firstname' => ['name' => 'John', 'nick' => 'Joe'],
    'lastname' => 'Doe',
    'age' => 25
]);

var_dump($collection->isMultiDimensional());

// result:
// true 
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

### # keys

```php
> keys(mixed $filter = null):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | yes | no

Return new collection with keys as values.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$keys = $collection->keys();

print_r($keys->toArray());

// result:
// Array ( [0] => firstname [1] => lastname [2] => age ) 
```

You can use parameter to filter only keys with specific value.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$keys = $collection->keys(25);

print_r($keys->toArray());

// result:
// Array ( [2] => age ) 
```
***

### # last

```php
> last(Closure $callback = null):mixed
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Get last value from collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$last = $collection->last();

echo $last;

// result:
// 25 
```

With optional parameter you can use function to get last value that passed truth test.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$last = $collection->last(function ($key):bool {
    return $key === 'lastname';
});

echo $last;

// result:
// Doe 
```
***

### # lastKey

```php
> lastKey(Closure $callback = null):null|int|string
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Get last key from collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$lastKey = $collection->lastKey();

echo $lastKey;

// result:
// age 
```

With optional parameter you can use function to get last key that passed truth test.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$lastKey = $collection->lastKey(function ($value):bool {
    return $value === 'Doe';
});

echo $lastKey;

// result:
// lastname 
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

print_r($multiplied->toArray());

// result:
// Array ( [0] => 2 [1] => 4 [2] => 6 [3] => 8 [4] => 10 ) 
```
***

### # merge

```php
> merge(callable $callback):this // basic and object
> merge(callable $callback, int $size = 1):this // index
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

print_r($collection->toArray());

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

$collection->merge(function ($items, $counter):void {
    $items[$counter] = 0;
    $items[++$counter] = 1;
}, 2);

print_r($collection->toArray());

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

 $collection->mergeRecursive(fn ():array => [
    'height' => '190cm',
    'gender' => 'male'
]);

print_r($collection->toArray());

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

print_r($only->toArray());

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

print_r($pad->toArray());

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

print_r($passed->toArray());
print_r($failed->toArray());

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

print_r($collection->toArray());

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

print_r($collection->toArray());

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

print_r($plucked->toArray());

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

print_r($plucked->toArray());

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

print_r($plucked->toArray());

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

### # reduce

```php
> reduce(Closure $callback, mixed $initial = null):mixed
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Iteratively reduce the array to a single value using a callback function.

```php
$collection = Collection::create(fn ():array => [1, 2, 3, 4, 5]);

$reduce = $collection->reduce(function ($carry, $key, $value) {
    return $carry + $value;
});

echo $reduce;

// result (because: 1+2+3+4+5):
// 15 

$reduce = $collection->reduce(function ($carry, $key, $value) {
    return $carry * $value;
}, 10);

echo $reduce;

// result (because: 10*1*2*3*4*5):
// 15 
```

The value for $carry on the first iteration is null.  
However, you may specify its initial value by passing a second argument to reduce.

```php
$collection = Collection::create(fn ():array => [1, 2, 3, 4, 5]);

$reduce = $collection->reduce(function ($carry, $key, $value) {
    return $carry + $value;
}, 5);

echo $reduce;

// result (because: 5+1+2+3+4+5):
// 20 
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

print_r($collection->toArray());

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

print_r($filter->toArray());

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

print_r($reversed->toArray());

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

print_r($collection->toArray());

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

print_r($collection->toArray());

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

print_r($collection->toArray());

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

print_r($collection->toArray());

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

print_r($collection->toArray());

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

print_r($collection->toArray());

// result:
// Array ( [0] => 2 [1] => 3 [2] => 4 [3] => 5 ) 
```
***

### # skip

```php
> skip(int $offset):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | no

Remove number of elements from the beginning of the collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$skip = $collection->skip(1);

print_r($skip->toArray());

// result:
// Array ( [lastname] => Doe [age] => 25 ) 
```
***

### # skipUntil

```php
> skipUntil(callable $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | no

Remove number of elements from the beginning of the collection until the given callback returns true.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$skipUntil = $collection->skipUntil(function ($key, $value):bool {
    return $value > 3;
});

print_r($skipUntil->toArray());

// result:
// Array ( [3] => 4 [4] => 5 ) 
```
***

### # skipWhile

```php
> skipWhile(callable $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | no

Remove number of elements from the beginning of the collection while the given callback returns true.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$skipWhile = $collection->skipWhile(function ($key, $value):bool {
    return $value <= 3;
});

print_r($skipWhile->toArray());

// result:
// Array ( [3] => 4 [4] => 5 ) 
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

print_r($slice->toArray());

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

print_r($slice->toArray());

// result:
// Array ( [lastname] => Doe ) 
```

Third optional parameter is preserve_keys.

> note: `slice` method will reorder and reset the integer array indices by default.
> This behaviour can be changed by setting preserve_keys to true.
> String keys are always preserved, regardless of this parameter.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25,
     10 => 'Male'
]);

$slice = $collection->slice(0, 4);

print_r($slice->toArray());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [0] => Male ) 

$slice = $collection->slice(0, 4, true);

print_r($slice->toArray());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [10] => Male ) 
```

You can also slice in the opposite direction to get last records.

```php
$collection = Collection::create(fn ():array => [
'firstname' => 'John',
'lastname' => 'Doe',
'age' => 25,
10 => 'Male'
]);

$slice = $collection->slice(-2, 2, true);

print_r($slice->toArray());

// result:
// Array ( [age] => 25 [10] => Male ) 
```
***

### # sort

```php
> sort(\FireHub\Support\Enums\Order $order = Order::ASC, bool $preserve_keys = false, \FireHub\Support\Collections\Enums\Sort $sort = Sort::SORT_REGULAR):bool
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Sorts collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->sort();

print_r($collection->toArray());

// result:
// Array ( [0] => 25 [1] => Doe [2] => John ) 
```

First parameter is order type.  
You can choose from ascending or descending order.  
It defaults to ascending order - `Order::ASC`.

```php
use FireHub\Support\Enums\Order;

$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->sort(Order::DESC);

print_r($collection->toArray());

// result:
// Array ( [0] => John [1] => Doe [2] => 25 ) 
```

With second parameter you can choose whether you want to preserve original collection keys.  
It defaults to false.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->sort(preserve_keys: true);

print_r($collection->toArray());

// result:
// Array ( [age] => 25 [lastname] => Doe [firstname] => John ) 
```

Third parameter is sorting type.

> SORT_REGULAR - _Compare items normally._  
> SORT_NUMERIC - _Compare items numerically._  
> SORT_STRING - _Compare items as strings._  
> SORT_LOCALE_STRING - _Compare items as strings, based on the current locale. It uses the locale, which can be changed using setlocale()._  
> SORT_NATURAL - _Compare items as strings using "natural ordering" like natsort()._  
> SORT_STRING_FLAG_CASE - _Sort strings case-insensitively._  
> SORT_NATURAL_FLAG_CASE - _Sort natural case-insensitively._

```php
use FireHub\Support\Collections\Enums\SortFlag;

$collection = Collection::create(fn ():array => ["JohnDoe1", "johndoe2", "JohnDoe3", "johndoe21"]);

$collection->sort();

print_r($collection->toArray());

// result:
// Array ( [0] => JohnDoe1 [1] => JohnDoe3 [2] => johndoe2 [3] => johndoe21 )  

$collection->sort(SortFlag::SORT_NATURAL_FLAG_CASE);

print_r($collection->toArray());

// result:
// Array ( [0] => JohnDoe1 [1] => johndoe2 [2] => JohnDoe3 [3] => johndoe21 ) 

$collection->sort(SortFlag::SORT_STRING_FLAG_CASE);

print_r($collection->toArray());

// result:
// Array ( [0] => JohnDoe1 [1] => johndoe2 [2] => johndoe21 [3] => JohnDoe3 ) 
```

With second parameter you can choose whether you want to preserve original collection keys.  
It defaults to false.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->sort(preserve_keys: true);

print_r($collection->toArray());

// result:
// Array ( [age] => 25 [lastname] => Doe [firstname] => John ) 
```
***

### # sortBy
```php
> sortBy(Closure $callback, bool $preserve_keys = false):bool
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Sorts collection by values using a user-defined comparison function.

The comparison function must return an integer less than, equal to,  
or greater than zero if the first argument is considered to be respectively less than,  
equal to, or greater than the second.

```php
$collection = Collection::create(fn ():array => [
    ['firstname' => 'John', 'lastname' => 'Doe'],
    ['firstname' => 'Jane', 'lastname' => 'Doe'],
    ['firstname' => 'Richard', 'lastname' => 'Roe']
]);

$collection->sortBy(function ($a, $b):int {
    return [$a['lastname'], $a['firstname']] <=> [$b['lastname'], $b['firstname']];
});

print_r($collection->toArray());

// result:
// Array (
//  [0] => Array ( [firstname] => Jane [lastname] => Doe )
//  [1] => Array ( [firstname] => John [lastname] => Doe )
//  [2] => Array ( [firstname] => Richard [lastname] => Roe )
// ) 
```

With first parameter you can choose whether you want to preserve original collection keys.  
It defaults to false.

```php
$collection = Collection::create(fn ():array => [
    ['firstname' => 'John', 'lastname' => 'Doe'],
    ['firstname' => 'Jane', 'lastname' => 'Doe'],
    ['firstname' => 'Richard', 'lastname' => 'Roe']
]);

$collection->sortBy(function ($a, $b):int {
    return [$a['lastname'], $a['firstname']] <=> [$b['lastname'], $b['firstname']];
}, true);

print_r($collection->toArray());

// result:
// Array (
//  [1] => Array ( [firstname] => Jane [lastname] => Doe )
//  [0] => Array ( [firstname] => John [lastname] => Doe )
//  [2] => Array ( [firstname] => Richard [lastname] => Roe )
// ) 
```
***

### # sortByKey

```php
> sortByKey(\FireHub\Support\Enums\Order $order = Order::ASC):bool
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Sorts collection by key.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->sortByKey();

print_r($collection->toArray());

// result:
// Array ( [age] => 25 [firstname] => John [lastname] => Doe ) 
```

First parameter is order type.  
You can choose from ascending or descending order.  
It defaults to ascending order - `Order::ASC`.

```php
use FireHub\Support\Enums\Order;

$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->sortByKey(Order::DESC);

print_r($collection->toArray());

// result:
// Array ( [lastname] => Doe [firstname] => John [age] => 25 ) 
```
***

### # sortByMany
```php
> sortByMany(array $fields):bool
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Sorts collection by multiple fields.

```php
use FireHub\Support\Enums\Order;

$collection = Collection::create(fn ():array => [
    ['id' => 1, 'firstname' => 'John', 'lastname' => 'Doe', 'gender' => 'male', 'age' => 25],
    ['id' => 2, 'firstname' => 'Jane', 'lastname' => 'Doe', 'gender' => 'female', 'age' => 23],
    ['id' => 3, 'firstname' => 'Richard', 'lastname' => 'Roe', 'gender' => 'male', 'age' => 27],
    ['id' => 4, 'firstname' => 'Jane', 'lastname' => 'Roe', 'gender' => 'female', 'age' => 22],
    ['id' => 5, 'firstname' => 'John', 'lastname' => 'Roe', 'gender' => 'male', 'age' => 26],
]);

$collection->sortByMany([
    ['lastname', Order::ASC],
    ['gender', Order::ASC],
    ['age', Order::DESC]
]);

print_r($collection->toArray());

// result:
// Array (
//  [0] => Array ( [id] => 2 [firstname] => Jane [lastname] => Doe [gender] => female [age] => 23 )
//  [1] => Array ( [id] => 1 [firstname] => John [lastname] => Doe [gender] => male [age] => 25 )
//  [2] => Array ( [id] => 4 [firstname] => Jane [lastname] => Roe [gender] => female [age] => 22 )
//  [3] => Array ( [id] => 3 [firstname] => Richard [lastname] => Roe [gender] => male [age] => 27 )
//  [4] => Array ( [id] => 5 [firstname] => John [lastname] => Roe [gender] => male [age] => 26 )
// ) 
```
***

### # sortKeyBy

```php
> sortKeyBy(Closure $callback):bool
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Sorts collection by key using a user-defined comparison function.

The callback comparison function. Function cmp_function should accept two parameters which will be filled by pairs of array keys.  
The comparison function must return an integer less than, equal to, or greater than zero if the first argument is considered to be respectively less than, equal to, or greater than the second.

```php
$collection = Collection::create(fn ():array => [
    'a' => 4, 'b' => 2, 'c' => 8, 'd' => 6
]);

$collection->sortKeyBy(function ($a, $b):int {
    if ($a === $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;

});

print_r($collection->toArray());

// result:
// Array ( [a] => 4 [b] => 2 [c] => 8 [d] => 6 ) 
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

print_r($splice->toArray());

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

print_r($splice->toArray());

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

print_r($splice->toArray());

// result:
// Array ( [firstname] => John [0] => male [1] => tall ) 
```
***

### # take

```php
> take(int $offset):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | no

Return new collection with specified number of items.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$take = $collection->take(1);

print_r($take->toArray());

// result:
// Array ( [firstname] => John ) 
```
***

### # takeUntil

```php
> takeUntil(callable $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | no

Return new collection with specified number of items until the given callback returns true.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$takeUntil = $collection->takeUntil(function ($key, $value):bool {
    return $key > 3;
});

print_r($takeUntil->toArray());

// result:
// Array ( [0] => 1 [1] => 2 [2] => 3 [3] => 4 ) 
```
***

### # takeWhile

```php
> takeWhile(callable $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | no

Return new collection with specified number of items while the given callback returns true.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5]);

$takeWhile = $collection->takeWhile(function ($key, $value):bool {
    return $value < 3;
});

print_r($takeWhile->toArray());

// result:
// Array ( [0] => 1 [1] => 2 ) 
```
***

### # tap

```php
> tap(Closure $callback):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | yes | yes

Passes the collection to the given callback, allowing you to "tap" into the collection at a specific point
and do something with the items while not affecting the collection itself.

```php
$collection = Collection::create(fn ():array => [1,2,3,4,5])->take(3)->tap(function ($collection) {
    print_r($collection->toArray());
})->skip(1);

print_r($collection->toArray());

// result:
// Array ( [0] => 1 [1] => 2 [2] => 3 ) 
// Array ( [0] => 2 [1] => 3 ) 
```
***

### # toArray

```php
> toArray():array
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

$result = $collection->toArray();

print_r($result);

// result:
// Array ( [0] => 1 [1] => 2 [2] => 3 ) 
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

### # union

```php
> union(callable $callback):this
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Merge new collection with original one.

> note: If there are same keys on both collections, keys from original collection
will be preferred.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25,
    'gender' => 'female'
]);

$collection->union(fn ():array => [
    'height' => '190cm',
    'gender' => 'male'
]);

print_r($collection->toArray());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [gender] => female [height] => 190cm ) 
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

### # unless

```php
> unless(bool $condition, Closure $condition_meet, ?Closure $condition_not_meet = null):this
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Execute the given callback unless the first argument given to the method evaluates to true.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->unless(false, function (\FireHub\Support\Collections\Types\Array_Type $collection) {
    $collection->add('height', '190cm');
});

print_r($collection->toArray());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [height] => 190cm ) 
```

Third optional parameter is if condition is not meet.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->unless(true, function (\FireHub\Support\Collections\Types\Array_Type $collection) {
    $collection->add('height', '190cm');
}, function (\FireHub\Support\Collections\Types\Array_Type $collection) {
    $collection->add('weight', '88kg');
});

print_r($collection->toArray());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [weight] => 88kg ) 
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

print_r($collection->toArray());

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

print_r($collection->toArray());

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

print_r($collection->toArray());

// result:
// Array ( [0] => 6 [1] => 7 [2] => 8 [3] => 1 [4] => 2 [5] => 3 [6] => 4 [7] => 5 ) 
```
***

### # values

```php
> values():self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | yes | no

Retrieve only values from collection.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
])->values();

print_r($collection->toArray());

// result:
// Array ( [0] => John [1] => Doe [2] => 25 ) 
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

print_r($collection->toArray());

// result:
// Array ( [0] => 2 [1] => 4 [2] => 6 [3] => 8 [4] => 10 )
```
***

### # when

```php
> when(bool $condition, Closure $condition_meet, ?Closure $condition_not_meet = null):this
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | yes | no | yes

Execute the given callback when the first argument given to the method evaluates to true.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->when(true, function (\FireHub\Support\Collections\Types\Array_Type $collection) {
    $collection->add('height', '190cm');
});

print_r($collection->toArray());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [height] => 190cm ) 
```

Third optional parameter is if condition is not meet.

```php
$collection = Collection::create(fn ():array => [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'age' => 25
]);

$collection->when(true, function (\FireHub\Support\Collections\Types\Array_Type $collection) {
    $collection->add('height', '190cm');
});

print_r($collection->toArray());

// result:
// Array ( [firstname] => John [lastname] => Doe [age] => 25 [weight] => 88kg ) 
```
***

### # where

```php
> where(int|string $key, Comparison $operator, mixed $value):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Filters 2-dimensional collection by key and value.

```php
use FireHub\Support\Enums\Operators\Comparison;

$collection = Collection::create(fn ():array => [
    ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
    ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
]);

$where = $collection->where('age', Comparison::LESS_OR_EQUAL, 25);

print_r($where->toArray());

// result:
// Array (
//  [0] => Array ( [firstname] => John [lastname] => Doe [age] => 25 )
//  [1] => Array ( [firstname] => Jane [lastname] => Doe [age] => 21 )
// ) 
```
***

### # whereBetween

```php
> whereBetween(int|string $key, int $greater_or_equal, int $less_or_equal):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Filters 2-dimensional collection by key and value between two values.

```php
$collection = Collection::create(fn ():array => [
    ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
    ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
]);

$whereBetween = $collection->whereBetween('age', 21, 26);

print_r($whereBetween->toArray());

// result:
// Array (
//  [0] => Array ( [firstname] => John [lastname] => Doe [age] => 25 )
//  [1] => Array ( [firstname] => Jane [lastname] => Doe [age] => 21 )
// ) 
```
***

### # whereContains

```php
> whereContains(int|string $key, array $values):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Filters 2-dimensional collection by key and value that contains provider list of values.

```php
$collection = Collection::create(fn ():array => [
    ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
    ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
]);

$whereContains = $collection->whereContains('age', [21, 27]);

print_r($whereContains->toArray());

// result:
// Array (
//  [0] => Array ( [1] => Array ( [firstname] => Jane [lastname] => Doe [age] => 21 ) )
//  [1] => Array ( [2] => Array ( [firstname] => Richard [lastname] => Roe [age] => 27 ) )
// ) 
```
***

### # whereDoesntContain

```php
> whereDoesntContain(int|string $key, array $values):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Filters 2-dimensional collection by key and values that doesn't contain in the list of values.

```php
$collection = Collection::create(fn ():array => [
    ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
    ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
]);

$whereDoesntContain = $collection->whereDoesntContain('age', [21, 27]);

print_r($whereDoesntContain->toArray());

// result:
// Array (
//  [0] => Array ( [firstname] => John [lastname] => Doe [age] => 25 )
// ) 
```
***

### # whereNot

```php
> whereNot(int|string $key, Comparison $operator, mixed $value):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Filters 2-dimensional collection by key and value to reject.

```php
use FireHub\Support\Enums\Operators\Comparison;

$collection = Collection::create(fn ():array => [
    ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
    ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
]);

$whereNot = $collection->whereNot('age', Comparison::LESS_OR_EQUAL, 24);

print_r($whereNot->toArray());

// result:
// Array (
//  [0] => Array ( [firstname] => John [lastname] => Doe [age] => 25 )
//  [2] => Array ( [firstname] => Richard [lastname] => Roe [age] => 27 )
// ) 
```
***

### # whereNotBetween

```php
> whereNotBetween(int|string $key, int $greater_or_equal, int $less_or_equal):self
```

> Available on collection:
>> Basic | Index | Lazy | Object
>> :---:|:---:|:---:|:---:
>> yes | no | no | no

Filters 2-dimensional collection by key and reject value between two values.

```php
$collection = Collection::create(fn ():array => [
    ['firstname' => 'John', 'lastname' => 'Doe', 'age' => 25],
    ['firstname' => 'Jane', 'lastname' => 'Doe', 'age' => 21],
    ['firstname' => 'Richard', 'lastname' => 'Roe', 'age' => 27]
]);

$whereNotBetween = $collection->whereNotBetween('age', 25, 26);

print_r($whereNotBetween->toArray());

// result:
// Array (
//  [1] => Array ( [firstname] => Jane [lastname] => Doe [age] => 21 )
//  [2] => Array ( [firstname] => Richard [lastname] => Roe [age] => 27 )
// ) 
```
***