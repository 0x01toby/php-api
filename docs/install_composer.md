## 安装 php composer 

```bash

# 下载composer-setup.php
 wget -O composer-setup.php  http://getcomposer.org/installer
# 安装 composer 使用php执行命令的时候，需要注意设置代理
 php composer-setup.php --install-dir=/usr/local/bin/ --filename=composer
# 设置composer的仓库配置
composer config -g repo.packagist composer https://packagist.phpcomposer.com
```