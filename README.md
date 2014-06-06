IbrowsDeployBundle
==================

Configuration

```yaml
ibrows_deploy:
    server: %deploy_server%
    environment: %deploy_environment%
    basic_auth_users:
        - { user: ibrows, pass: 4EmbOAwVyiOSFFLI }
        - { user: projectname, pass: projectpass }
    server_environments:
        localhost_dev:
            cacheclear:
                - {priority: 1, args: { symfonyEnv: dev }}
            assetsinstall:
                - {priority: 2}
            asseticdump:
                - {priority: 3, args: { symfonyEnv: dev }}
            doctrineschemaupdate:
                - {priority: 4, args: { symfonyEnv: dev, force: true, complete: true, dumpSql: false }}
        atrila_dev:
            cacheclear:
                - {priority: 1, args: { symfonyEnv: dev }}
                - {priority: 2, args: { symfonyEnv: prod }}
            assetsinstall:
                - {priority: 3}
            asseticdump:
                - {priority: 4, args: { symfonyEnv: dev }}
                - {priority: 5, args: { symfonyEnv: prod }}
            doctrineschemaupdate:
                - {priority: 6, args: { symfonyEnv: dev, force: true, complete: true, dumpSql: false }}
        atrila_test:
            mysqldump:
                - {priority: 1, args: { path: ~/backup/database }}
            cacheclear:
                - {priority: 2, args: { symfonyEnv: dev }}
                - {priority: 3, args: { symfonyEnv: prod }}
            assetsinstall:
                - {priority: 4}
            asseticdump:
                - {priority: 5, args: { symfonyEnv: dev }}
                - {priority: 6, args: { symfonyEnv: prod }}
            doctrineschemaupdate:
                - {priority: 7, args: { symfonyEnv: dev, force: true, complete: true, dumpSql: false }}
        atrila_production:
            mysqldump:
                - {priority: 1, args: { path: ~/backup/database }}
            opcachereset:
                - {priority: 2, args: { host: 'integration.projectname.atri.ibrows.ch', user: ibrows, pass: 4EmbOAwVyiOSFFLI }}
            cacheclear:
                - {priority: 3, args: { symfonyEnv: dev }}
                - {priority: 4, args: { symfonyEnv: prod }}
            assetsinstall:
                - {priority: 5}
            asseticdump:
                - {priority: 6, args: { symfonyEnv: dev }}
                - {priority: 7, args: { symfonyEnv: prod }}
        atrila_*:
            writebasicauthusersfile:
                - {priority: 0}
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

Parameters (DO NOT SET DEFAULT VALUES HERE!) If you set default server to 'localhost' and environment to 'dev' it's verly likely that a production server will get those parameters as well and start to schema update with --force --complete for example

```yaml
deploy_server: ~
deploy_environment: ~
```

composer.json

```json
"require": {
    "ibrows/deploy-bundle": "1.*"
},
"scripts": {
    "post-install-cmd": [
        "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters",
        "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::buildBootstrap",
        "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installRequirementsFile",
        "Ibrows\\DeployBundle\\Composer\\ScriptHandler::deploy"
    ],
    "post-update-cmd": [
        "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters",
        "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::buildBootstrap",
        "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installRequirementsFile",
        "Ibrows\\DeployBundle\\Composer\\ScriptHandler::deploy"
    ]
},
"repositories": [
    {
        "type": "vcs",
        "url": "git@codebasehq.com:ibrows/ibrowsch/ibrowsdeploybundle.git"
    }
]
```

AppKernel

```php
new Ibrows\DeployBundle\IbrowsDeployBundle()
```