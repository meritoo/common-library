# Meritoo Common Library

Common and useful classes, methods, exceptions etc.

# Value Objects

Located in `Meritoo\Common\ValueObject` namespace and in `src/ValueObject/` directory.

### Address

##### Namespace

`Meritoo\Common\ValueObject\Address`

##### Info

Represents address of company, institution, user etc. Contains properties:
1. `$street` - the street
2. `$buildingNumber` - the number of building
3. `$flatNumber` - the number of flat
4. `$zipCode` - the zip code
5. `$city` - the city, location

##### New instance

New instance can be created using constructor

```php
new Address('New York', '00123', '4th Avenue', '10', '200');
```

##### Methods

Has getters for each property, e.g. `getFlatNumber()` or `getZipCode()`, and 1 extra method:

```php
getFullStreet()
```

that returns name of street with related numbers (building & flat number).

Example:

```php
$address = new Address('New York', '00123', '4th Avenue', '10', '200');
$fullStreet = $address->getFullStreet(); // "4th Avenue 10/200"
```

##### Conversion to string (the `__toString()` method)

Instance of `Address` may be represented as string that contains all non-empty properties separated by `, `.

Example:

```php
$address = new Address('New York', '00123', '4th Avenue', '10', '200');
$asString = (string)$address; // "4th Avenue 10/200, 00123, New York"
```

### BankAccount

##### Namespace

`Meritoo\Common\ValueObject\BankAccount`

##### Info

Represents bank account. Contains properties:
1. `$bankName` - name of bank
2. $accountNumber` - number of bank's account

##### New instance

New instance can be created using constructor

```php
new BankAccount('Bank of America', '1234567890')
```

##### Methods

Has getters for each property `getBankName()` and `getAccountNumber()`.

##### Conversion to string (the `__toString()` method)

Instance of `BankAccount` may be represented as string that contains all non-empty properties separated by `, `.

Example:

```php
$bank = new BankAccount('Bank of America', '1234567890');
$asString = (string)$bank; // "Bank of America, 1234567890"
```

### Company

##### Namespace

`Meritoo\Common\ValueObject\Company`

##### Info

Represents a company. Contains properties:
1. `$name` - name of company
2. `$address` - address of company
3. `$bankAccount` - bank account of company

##### New instance

New instance can be created using constructor:

```php
new Company(
    'Test 1',
    new Address('New York', '00123', '4th Avenue', '10', '200'),
    new BankAccount('Bank 1', '12345')
);
```

##### Methods

Has getters for each property `getName()`, `getAddress()` and `getBankAccount()`.

##### Conversion to string (the `__toString()` method)

Instance of `Company` may be represented as string that contains all non-empty properties separated by `, `.

Example:

```php
$company = new Company(
    'Test 1',
    new Address('New York', '00123', '4th Avenue', '10', '200'),
    new BankAccount('Bank 1', '12345')
);

$asString = (string)$company; // "Test 1, 4th Avenue 10/200, 00123, New York, Bank 1, 12345"
```

### Version

##### Namespace

`Meritoo\Common\ValueObject\Version`

##### Info

Represents version of software. Contains properties:
1. `$majorPart` - the "major" part of version
2. `$minorPart` - the "minor" part of version
3. `$patchPart` - the "patch" part of version

##### New instance

New instance can be created using:

1. Constructor:

	```php
    new Version(1, 0, 2);
    ```

2. Static methods:
	1. `fromArray()` - creates new instance using given version as array

	```php
	Version::fromArray([1, 0, 2]);
	```

	2. `fromString()` - creates new instance using given version as string:

	```php
    Version::fromString('1.0.2');
    ```

# More

1. [Base test case (with common methods and data providers)](Base-test-case.md)
2. [Collection of elements](Collection-of-elements.md)
3. [Exceptions](Exceptions.md)
4. [Static methods](Static-methods.md)
   1. [Arrays](Static-methods/Arrays.md)
   2. [Regex](Static-methods/Regex.md)
5. [**Value Objects**](Value-Objects.md)

[&lsaquo; Back to `Readme`](../README.md)
