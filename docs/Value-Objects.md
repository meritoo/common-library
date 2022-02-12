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
2. `$accountNumber` - number of bank's account

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

### Human

##### Namespace

`Meritoo\Common\ValueObject\Human`

##### Info

Represents human. Based on `\Meritoo\Common\Traits\ValueObject\HumanTrait` trait. Contains properties same
as `HumanTrait` trait:

1. `$firstName` - first name
2. `$lastName` - last name
3. `$email` - email address
4. `$birthDate` - birth date

##### New instance

New instance can be created using constructor:

```php
new Human('John', 'Scott', 'john@scott.com', new \DateTime('2001-01-01'));
```

##### Methods

Has getters for each property, e.g. `getFirstName()`, `getEmail()` etc.

##### Conversion to string (the `__toString()` method)

Instance of `Human` may be represented as string that contains first name, last name and email address (if provided).

Example:

```php
$human1 = new Human('John', 'Scott');
$asString1 = (string)$human1; // "John Scott"

$human2 = new Human('John', 'Scott', 'john@scott.com', new \DateTime('2001-01-01'));
$asString2 = (string)$human2; // "John Scott <john@scott.com>"
```

### Size

##### Namespace

`Meritoo\Common\ValueObject\Size`

##### Info

Size, e.g. of image. Contains properties:

1. `width` - the width
2. `height` - the height
3. `unit` - unit used when width or height should be returned with unit, default: `"px"`
4. `separator` - separator used when converting to string, default: `" x "`

##### New instance

New instance can be created using static methods:

1. `fromArray()` - creates new instance from given array

    ```php
    // Using default "px" unit
    Size::fromArray([200, 100]);

    // With custom "mm" unit
    Size::fromArray([200, 100], 'mm');
    ```

2. `fromString()` - creates new instance from given string

    ```php
    // Using default "px" unit and default " x " separator
    Size::fromString('200 x 100');

	// With custom "mm" unit and " X " separator
    Size::fromString('200 X 100', 'mm', ' X ');
    ```

##### Methods

Has:

- getters and setters for `width` and `height` properties.
- setter for `separator` property
- `toString()` and `toArray()` methods that returns size represented as string and array

##### Conversion to string (using `__toString()` method)

Instance of `Size` may be represented as string that contains width and height separated by separator (default: `" x "`)
.

Example:

```php
$size = Size::fromArray([200, 100]);

// With default separator
$asString1 = (string)$size; // "200 x 100"

// With custom separator
$size->setSeparator('X');
$asString2 = (string)$size; // "200X100"
```

### Template

##### Namespace

`Meritoo\Common\ValueObject\Template`

##### Info

Template with placeholders that may be filled by real data. Contains properties:

1. `$content` - raw string with placeholders (content of the template)

##### New instance

New instance can be created using constructor:

```php
new Template('First name: %first_name%');
```

Each placeholder should be wrapped by `%` character, e.g. `%first_name%`. If content of template is an empty string or
does not contain 1 placeholder at least, an `Meritoo\Common\Exception\ValueObject\Template\InvalidContentException`
exception will be thrown.

Examples of invalid content of template:

```php
new Template(''); // An empty string
new Template('test'); // Without placeholders
new Template('This is %test'); // With starting tag only (invalid placeholder)
```

##### Methods

Has 1 public method: `fill(array $values)`. Returns content of the template filled with given values (by replacing
placeholders with their proper values).

Example of usage:

```php
$template = new Template('My name is %name% and I am %profession%');
$result = $template->fill([
	'name'       => 'Jane',
    'profession' => 'photographer',
]); // "My name is Jane and I am photographer"
```

Throws an `Meritoo\Common\Exception\ValueObject\Template\NotEnoughValuesException` exception if there is not enough
values (iow. more placeholders than values).

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
    1. `fromArray(array $version)` - creates new instance using given version as array

   ```php
   Version::fromArray([1, 0, 2]);
   ```

    2. `fromString(string $version)` - creates new instance using given version as string:

   ```php
   Version::fromString('1.0.2');
   ```

##### Methods

Has getters for each property: `getMajorPart()`, `getMinorPart()`, `getPatchPart()`.

##### Conversion to string (using `__toString()` method)

Instance of `Version` may be represented as string that contains all properties separated by `.` (`$majorPart`
.`$minorPart`.`$patchPart`).

Example:

```php
$version = new Version(1, 0, 2);
$asString = (string)$version; // "1.0.2"
```

# More

1. [Base test case (with common methods and data providers)](Base-test-case.md)
2. [Collection of elements](Collection/BaseCollection.md)
3. [Templates](Collection/Templates.md)
4. [Exceptions](Exceptions.md)
5. [Static methods](Static-methods.md)
    1. [Arrays](Static-methods/Arrays.md)
    2. [Regex](Static-methods/Regex.md)
    3. [Uri](Static-methods/Uri.md)
6. [**Value Objects**](Value-Objects.md)

[&lsaquo; Back to `Readme`](../README.md)
