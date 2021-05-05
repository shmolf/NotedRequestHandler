# Note'd Hydrator

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)

The [note'd app](https://note-d.app) is/will be designed to allow the user to store notes
on their own self-hosted server.  
This library is meant for said user to install in their PHP application, to help simplify
the communication between the website and their hosting service.

Since a self-hosted data-repo (your PHP application storing notes) could reference an old iteration of this library,
the note application will attempt to query the host as to which schema version it accepts, and then try to comply.

For more information, please visit the [note'd Github repo](https://github.com/shmolf/noted).

A [helpful website](https://www.jsonschemavalidator.net/) to self-check schema and data validity.

## Structure

```
src/
tests/
```


## Install

Via Composer

``` bash
$ composer require shmolf/noted-hydrator
```

## Usage

``` php
// Still in the works
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.  
_This file will **eventually** be auto-generated._

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email [the author](mailto:shmolf@gmail.com) instead of using the issue tracker.

## Credits

- [Nicholas Browning][link-author]
- [All Contributors][link-contributors]

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/shmolf/noted-hydrator&style=flat-square
[ico-license]: https://img.shields.io/github/license/shmolf/noted-hydrator?style=flat-square

[link-packagist]: https://packagist.org/packages/shmolf/noted-hydrator
[link-author]: https://github.com/shmolf
[link-contributors]: ../../contributors
