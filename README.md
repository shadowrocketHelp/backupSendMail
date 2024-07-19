# backupSendMail
## 宝塔数据库自动备份并发送到指定邮箱 宝塔数据库自动备份
## aapanel PHP 使用 phpmailer 发送电子邮件 以及封装方法参考

步骤特别简单，仔细看。
### 1 宝塔新建站点 建议直接就是宝塔服务器的ip:端口（记得开发端口）

### 2 网站-目录-关闭 防跨站攻击(open_basedir) 因为我们要读取其他目录文件，开启的话无法读取其他目录

### 3 进入目录站点,删除原有所有文件，然后 clone 代码
```
git clone https://github.com/shadowrocketHelp/backupSendMail.git
```

### 4 添加定时任务1
```
任务类型-备份数据库 备份所有数据库 时间等其他，你自己按照需求配置
```
### 5手动执行定时任务1，看一下备份文件在哪个目录，一般在
```
/www/backup/database/mysql/crontab_backup/
```
### 6 编辑index.php文件，里面有写怎么编辑


### 7 添加定时任务2
```
任务类型-访问url 
这里的url就填写 http://200.200.200.200:8981/?pwd=qqqqqq 
ip和域名就是第一个步骤添加的，保持一致
qqqqqq是在index.php里面配置的
```

手动执行一下定时任务2，看看是否能发送成功，发送成功证明没有问题
