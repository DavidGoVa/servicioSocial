# Fixtures

## Fixtures to simulate users behavior

> If you want to avoid deleting specific tables in your database, just add them with the following parameter *--purge-exclusions=table_name* when you load the fixtures by executing:

```shell
 php bin/console doctrine:fixtures:load --purge-exclusions=carrera --purge-exclusions=rol_aragon --purge-exclusions=tipo_programa --purge-exclusions=config
```