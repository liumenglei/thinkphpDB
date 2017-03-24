# 这是一个php5.2可以使用的thinkphp 数据库类

config.php为数据库配置文件，修改一下config.php里面的数据库配置，目前只实现了mysql数据库的操作

引入init.php即可，使用方法为thinkphp 一样的用法，如
$M = M('user');
$result = $M -> select();
print_r($result);
只有 M方法，没有 D方法
使用 M() -> _sql();来查看上次执行的语句
自定义sql
	查询使用  
		$resutl = $M -> query($sql);
	非查询使用
		$resutl = $M -> execute($sql);
可能有些不常用的方法不支持

执行失败不会抛出异常，只会返回false,数据库连接失败也是返回false
