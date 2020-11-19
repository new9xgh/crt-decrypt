# SecureCRT 密码找回工具web版

通过上传配置文件或使用配置中Password密文进行解密。

[立即使用](http://crt-decrypt.xproject.tech)


## 安装

### 环境要求

- php >= 7.2
- python >= 3.6

### composer
```
composer install --prefer-dist -vvv
```

### python module
```
pip install pycryptodome
```

### 修改配置
```
# python 路径
vi environments/dev/common/config/params-local.php
```

### 初始化
```
init
```

## 运行
```
yii serve --docroot="frontend/web" --port=8888
```

## 使用
访问http://localhost:8888


## License
GPL-3.0