<?php
	define('ROOT',str_replace('\\','/',substr(dirname(__FILE__),0)));

	include_once ROOT.'/Model.class.php';
	/**
	 * 获取和设置配置参数 支持批量定义
	 * @param string|array $name 配置变量
	 * @param mixed $value 配置值
	 * @param mixed $default 默认值
	 * @return mixed
	 */
	function C($name=null, $value=null,$default=null) {
		static $_config = array();
		// 无参数时获取所有
		if (empty($name)) {
			return $_config;
		}
		// 优先执行设置获取或赋值
		if (is_string($name)) {
			if (!strpos($name, '.')) {
				$name = strtoupper($name);
				if (is_null($value))
					return isset($_config[$name]) ? $_config[$name] : $default;
				$_config[$name] = $value;
				return null;
			}
			// 二维数组设置和获取支持
			$name = explode('.', $name);
			$name[0]   =  strtoupper($name[0]);
			if (is_null($value))
				return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
			$_config[$name[0]][$name[1]] = $value;
			return null;
		}
		// 批量设置
		if (is_array($name)){
			$_config = array_merge($_config, array_change_key_case($name,CASE_UPPER));
			return null;
		}
		return null; // 避免非法参数
	}

	/**
	 * 抛出异常处理
	 * @param string $msg 异常消息
	 * @param integer $code 异常代码 默认为0
	 * @throws Think\Exception
	 * @return void
	 */
	function E($msg, $code=0) {
		throw new Exception($msg, $code);
	}

	/**
	 * 实例化一个没有模型文件的Model
	 * @param string $name Model名称 支持指定基础模型 例如 MongoModel:User
	 * @param string $tablePrefix 表前缀
	 * @param mixed $connection 数据库连接信息
	 * @return Think\Model
	 */
	function M($name='', $tablePrefix='',$connection='') {
		static $_model  = array();
		if(strpos($name,':')) {
			list($class,$name)    =  explode(':',$name);
		}else{
			$class      =   'Model';
		}
		$guid           =   (is_array($connection)?implode('',$connection):$connection).$tablePrefix . $name . '_' . $class;
		if (!isset($_model[$guid]))
			$_model[$guid] = new $class($name,$tablePrefix,$connection);
		return $_model[$guid];
	}
	/**
	 * 解析 __TABLE等成带前缀的表名
	 */
	function getFullName($match){
    	return M()->tablePrefix.strtolower($match[1]);
	}
	/**
	 * 字符串命名风格转换
	 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
	 * @param string $name 字符串
	 * @param integer $type 转换类型
	 * @return string
	 */
	function parse_name($name, $type=0) {
		if ($type) {
			return ucfirst(preg_replace_callback('/_([a-zA-Z])/', 'getString', $name));
		} else {
			return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
		}
	}
	function getString($matches) {
		return strtoupper($matches[1]);
	}
	$_config = include_once ROOT.'/config.php';
	C($_config);

	include_once ROOT.'/Db.class.php';
	include_once ROOT.'/Db/Driver.class.php';
