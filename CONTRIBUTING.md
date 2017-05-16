# CONTRIBUTING

This bundle was originally created for M6Web projects purpose. As we strongly believe in open source, we share it to you.

If you want to learn more about our opinion on open source, you can read the [OSS article](http://tech.m6web.fr/oss/) on our website.

## Developing

The features available for now are only those we need, but you're welcome to open an issue or pull-request if you need more.

### Download dev dependencies

```bash
composer install
```

### Lint

To ensure good code quality, we use our awesome tool "[coke](https://github.com/M6Web/Coke)" to check there is no coding standards violations. 
We use [Symfony2 coding standards](https://github.com/M6Web/Symfony2-coding-standard).

```bash
make lint
# or run 'vendor/bin/coke' if you don't have 'make'
```

### Test

This bundle is tested with [atoum](https://github.com/atoum/atoum).

```bash
make test
# or run 'vendor/bin/atoum' if you don't have 'make'
```

## Pull-request

If you are currently reading this section, you are a really good guy who share our vision about open source.

So, we don't want to harass you with tons of constraints. There is only 2 things we care about :
  * testing
  * coding standards
