IbrowsDeployBundle
==================

Configuration

```yaml
ibrows_deploy:
    server: %deploy_server%
    environment: %deploy_environment%
    server_environments:
        localhost_dev:
            cacheclear:
                - {priority: 1, args: { symfonyEnv: dev }}
            doctrineschemaupdate:
                - {priority: 2, args: { symfonyEnv: dev, force: true, complete: true, dumpSql: false }}
            assetsinstall:
                - {priority: 3}
            asseticdump:
                - {priority: 4, args: { symfonyEnv: dev }}
        atrila_dev:
            cacheclear:
                - {priority: 1, args: { symfonyEnv: dev }}
                - {priority: 2, args: { symfonyEnv: prod }}
            assetsinstall:
                - {priority: 3}
            asseticdump:
                - {priority: 4, args: { symfonyEnv: dev }}
                - {priority: 5, args: { symfonyEnv: prod }}
        atrila_test:
            cacheclear:
                - {priority: 1, args: { symfonyEnv: dev }}
                - {priority: 2, args: { symfonyEnv: prod }}
            assetsinstall:
                - {priority: 3}
            asseticdump:
                - {priority: 4, args: { symfonyEnv: dev }}
                - {priority: 5, args: { symfonyEnv: prod }}
        atrila_production:
            mysqldump:
                - {priority: 1, args: { path: ~/backup/database }}
            opcachereset:
                - {priority: 2, args: { host: 'integration.projectname.atri.ibrows.ch' }}
            cacheclear:
                - {priority: 3, args: { symfonyEnv: dev }}
                - {priority: 4, args: { symfonyEnv: prod }}
            assetsinstall:
                - {priority: 5}
            asseticdump:
                - {priority: 6, args: { symfonyEnv: dev }}
                - {priority: 7, args: { symfonyEnv: prod }}
```

Routing (for OpCache Reset)

```yaml
# IbrowsDeployBundle (for OpCache Reset)
ibrows_deploy:
    resource: "@IbrowsDeployBundle/Controller/"
    type:     annotation
    prefix:   /
```

Security (for OpCache Reset)

```yaml
security:
    access_control:
        - { path: ^/ibrows/deploy/opcache/reset, role: IS_AUTHENTICATED_ANONYMOUSLY }
```

Parameters (DO NOT SET DEFAULT VALUES HERE!) If you set default server to 'localhost' and environment to 'dev' it's verly likely that a production server will get those parameters as well and start to schema update with --force --complete as example

```yaml
deploy_server: ~
deploy_environment: ~
database_backup_path: ~
```