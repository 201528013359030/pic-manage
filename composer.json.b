{
    "name": "yiisoft/yii2-app-basic",
    "description": "Yii 2 Basic Application Template",
    "keywords": ["yii2", "framework", "basic", "application template"],
    "homepage": "http://www.yiiframework.com/",
    "type": "project",
    "license": "BSD-3-Clause",
    "support": {
        "issues": "https://github.com/yiisoft/yii2/issues?state=open",
        "forum": "http://www.yiiframework.com/forum/",
        "wiki": "http://www.yiiframework.com/wiki/",
        "irc": "irc://irc.freenode.net/yii",
        "source": "https://github.com/yiisoft/yii2"
    },
    "minimum-stability": "dev",
    "require": {
        "php": ">=5.4.0",
        "yiisoft/yii2": "*",
        "yiisoft/yii2-bootstrap": "*",
        "yiisoft/yii2-swiftmailer": "*",
        "arturoliveira/yii2-excelview": "*",
        "phpoffice/phpexcel": "dev-master",
    },
    "require-dev": {
        "yiisoft/yii2-codeception": "*",
        "yiisoft/yii2-debug": "*",
        "yiisoft/yii2-gii": "*",
        "yiisoft/yii2-faker": "*"
    },
    "config": {
        "process-timeout": 1800
    },
    "scripts": {
        "post-create-project-cmd": [
            "yii\\composer\\Installer::postCreateProject"
        ]
    },
    "extra": {
        "yii\\composer\\Installer::postCreateProject": {
            "setPermission": [
                {
                    "runtime": "0777",
                    "web/assets": "0777",
                    "yii": "0755"
                }
            ],
            "generateCookieValidationKey": [
                "config/web.php"
            ]
        },
        "asset-installer-paths": {
            "npm-asset-library": "vendor/npm",
            "bower-asset-library": "vendor/bower"
        }
    },
    "repositories": [
        {"type": "composer", "url": "http://pkg.phpcomposer.com/repo/packagist/"},
        {"packagist": false}
    ]
}
