IbrowsDeployBundle
==================

```sh
composer install # --> local installation and cache:clear, schema:update, etc.
php app/console ibrows:deploy test # --> Tag current version in GIT with test_X (X is calculated automatically)
php app/console ibrows:deploy test --immediate="atrila" # --> Same as above, but connects afterwards over ssh to atrila and execute "ibrows_deploy" so you dont have to wait
```

Configuration

```yaml
ibrows_deploy:
    server: %deploy_server%
    environment: %deploy_environment%
    basic_auth_users:
        - { user: ibrows, pass: 4EmbOAwVyiOSFFLI }
        - { user: projectname, pass: projectpass }
    immediate_process_strategies:
        atrila:
            serviceid: ibrows_deploy.server.immediateprocessstrategy.atrila
            options:
                user: __SSH_USER__
                passphrase: %deploy_atrila_ssh_passphrase%
                publicKeyFile: %deploy_atrila_ssh_publickeyfile%
                privateKeyFile: %deploy_atrila_ssh_publickeyfile%
                config: %deploy_atrila_ssh_config%
    server_environments:
        localhost_dev:
            cacheclear:
                - {priority: 1, args: { symfonyEnv: dev }}
            assetsinstall:
                - {priority: 2, args: { symlink: true }}
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
            #opcachereset: # - not needed on atrila server because switch_env command on shell will also clear opcache
                #- {priority: 2, args: { host: 'integration.projectname.atri.ibrows.ch', user: ibrows, pass: 4EmbOAwVyiOSFFLI }}
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

Routing (for OpCache Reset) - not needed on atrila server because switch_env command on shell will also clear opcache

```yaml
# IbrowsDeployBundle (for OpCache Reset)
ibrows_deploy:
    resource: "@IbrowsDeployBundle/Controller/"
    type:     annotation
    prefix:   /
```

Security (for OpCache Reset) - not needed on atrila server because switch_env command on shell will also clear opcache

```yaml
security:
    access_control:
        - { path: ^/ibrows/deploy/opcache/reset, role: IS_AUTHENTICATED_ANONYMOUSLY }
```

parameters.yml.dist (DO NOT SET DEFAULT VALUES ON deploy_server OR deploy_environment!) If you set default server to 'localhost' and environment to 'dev' it's verly likely that a production server will get those parameters as well and start to schema update with --force --complete for example

```yaml
deploy_server: ~
deploy_environment: ~
deploy_atrila_ssh_passphrase: ASK
deploy_atrila_ssh_publickeyfile: ~
deploy_atrila_ssh_privatekeyfile: ~
deploy_atrila_ssh_config: ~
```

composer.json

```json
"require": {
    "ibrows/deploy-bundle": "2.*"
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