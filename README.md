
```
# 测试服务器
git remote add myonline root@103.44.236.165:/www/wwwroot/likeshop-linsenguoji



location /mobile {
	proxy_pass  http://localhost:8080/mobile;
}

location / {
	if (!-e $request_filename){
		rewrite  ^(.*)$  /index.php?s=$1  last;   break;
	}
}




```


OVER: VIP会员产品区购物享受7折优惠（报单产品除外）
OVER: 3999达卡成为VIP会员
OVER: 店长零售区全部产品享受6折（后台手动授权）
OVER: 店长（秒杀专区）每单奖励10元
OVER: 积分互转功能

提现
http://localhost:8080/mobile/pages/bundle/user_withdraw/user_withdraw
充值
http://www.like2.net/mobile/pages/bundle/user_payment/user_payment
分类页面
/pages/sort/sortpage?category_id=1
- 安卓包

```shell
keytool.exe -genkey -v -keystore signkey.jks -storetype JKS -keyalg RSA -keysize 2048 -validity 10000 -alias aliaslinsenguoji
# 密码
aliaslinsenguoji888

com.linsen.store
```

- 标题i18n

```js

onReady() {
    uni.setNavigationBarTitle({
        title: this.$t('登录') ,
    });
},

```

```shell
# sql备份
_back="mysqldump -uroot -proot likeshop > /home/mydb.sql"
docker exec -it mysql57_like bash -c "$_back"
docker exec -it mysql57_like bash -c "ls /home"
docker cp mysql57_like:/home/mydb.sql ./
```


OVER: 用户暂停提现开关
OVER: 所有间推都显示
OVER: 提现需要手动审核
OVER: 提现的位置去掉非必要显示
OVER: 今日/本周 4个数据
OVER: 只有余额 一栏

- centos安装高版本docker

```shell
yum install -y yum-utils
yum-config-manager \
    --add-repo \
    https://download.docker.com/linux/centos/docker-ce.repo

yum -y install docker-ce docker-ce-cli containerd.io

yum -y install tmux docker-compose

```
