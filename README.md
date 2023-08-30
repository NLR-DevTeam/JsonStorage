<h1 align=center>JsonStorage</h1>
<p align=center>快速，简单，高效</p>

## 介绍
**感谢大家支持** ```JsonStorage``` ！，没什么可介绍的，```JsonStorage``` 是一个为不想使用SQL类数据库软件的开发者提供的键值对数据库服务，支持存储您的数组，以及多数据库调用的情况。

## 调用方法
在使用此工具之前，您需要 ```require``` 此工具的地址，例：
```
require("{$_SERVER['DOCUMENT_ROOT']}/{ToolPath}/jsonStorage.php");
```
十分不建议您更改程序名称，后续添加其他功能时可能会导致程序错误

## 工具的使用
```JsonStorage``` 使用了对象化编程，您可以不用担心先前项目的代码冲突问题，所以在使用工具之前，**您需要检查设置是否完成，您应当检查 ```jsonStorage.php``` 的头部 ```$systemVariable``` 变量信息是否填写完整** ，如果填写完整，您可以使用以下代码初始化 JsonStorage（配置检查工具与初始化）：
```
$jsonStorage_Initialzation = new Check();
$jsonStorage_Initialzation->Initialzation_Check();
```
至此，您就完成了对于程序的基本初始化，下一步，您需要初始化主程序：
```
$jsonStorage = new jsonStorage_Main();
```
### 我们提供了以下功能：
#### 1.创建一个新的数据库：
使用我们的工具来创建数据库：
```
$jsonStorage->createTable($name, $description, $content, $token);
```
> 在上方函数中，```$name```代表数据库名称，```$description```代表数据库的描述，```$content```表示数据库内容（必须为 ```Array```），```$token```用来当作数据库密钥，执行重要操作时需要用到

如果成功创建，此函数会返回 ```{"Status": "OK"}```

#### 2.删除数据库：
使用我们的工具来删除数据库：
```
$jsonStorage->deleteTable($name, $token, $password);
```
> 在上方函数中，```$name``` 为数据库名称，```$token``` 为您创建数据库时使用的数据库密钥， ```$password``` 则是您程序的密码，在 ```$systemVariable``` 中可以设置 ```confirmPassword```

#### 3.获取数据：
使用我们的工具来获取数据库内容：
```
$jsonStorage->getTable($name, $token);
```
用来读已存储的 ```$content```

#### 4.覆盖数据库（可以在修改数据库的时候用到）：
使用我们的工具来覆盖数据库内容：
```
$jsonStorage->coverTable($name, $token, $content);
```
覆盖不可撤销！请您注意！

**感谢您的使用！**
