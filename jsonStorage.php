<link rel="stylesheet" href="https://cdn.arkpowered.cn/css/page/offical/jsonStorage.css">
<?php

// 方块盒子 Json-Storage
// Alpha v0.0.1
// 23w01a

$Setting = array(
    "confirmPassword" => "abc111222333", //尽量不要设置默认值，进行重要操作时需要的密码（例如：删除数据库）
    "stopJsonStorage" => false, //默认为false，启用时将禁止一切对数据库的控制
    "checkUpdate" => false, //默认为false，当您要检查更新时启用
);



$systemVariable = array(
    "root" => $_SERVER["DOCUMENT_ROOT"],
    "runroot" => getcwd() . "jsonStorage.php/",
    "rootLib" => array(
        "TablePath" => getcwd() . "/jsonStorage/Tablelist.json",
        "logoutPath" => getcwd() . "/jsonStorage/log.out",
    ),
    "versionLib" => array(
        "version" => 1,
        "versionOut" => "Alpha 0.0.1",
        "annualSerial" => "23w01a"
    ),
);


class Basic_Function
{
    protected function f_get($url)
    {
        $get_array = file_get_contents($url);
        $out_array = json_decode($get_array, true);
        return $out_array;
    }

    protected function f_put($path, $variable)
    {
        file_put_contents($path, json_encode($variable, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}


class Check
{
    public function Initialzation_Check()
    {
        global $systemVariable;

        //运行前检查与设置
        if (chmod($systemVariable["runroot"], 0755)) {
            if (!is_dir("{$systemVariable["runroot"]}jsonStorage")) {
                mkdir("{$systemVariable["runroot"]}jsonStorage");
            }
            if (!file_exists($systemVariable["rootLib"]["TablePath"])) {
                if (!fopen($systemVariable["rootLib"]["TablePath"], "w+")) {
                    echo "<br><notice>[JsonStorage基础系统]</notice> 创建数据库表文件(TableJSON,fopen [w+])权限不足，请您提高根目录的权限";
                    exit();
                }
            }
            if (!file_exists($systemVariable["rootLib"]["logoutPath"])) {
                if (!fopen($systemVariable["rootLib"]["logoutPath"], "w+")) {
                    echo "<br><notice>[JsonStorage基础系统]</notice> 创建日志文件(Log,fopen [w+])权限不足，请您提高根目录的权限";
                    exit();
                }
            }
        } else {
            echo "<br><notice>[JsonStorage基础系统]</notice> 创建文件夹权限不足，请您提高根目录的权限";
            exit();
        }
    }
}

class jsonStorage_Main extends Basic_Function
{
    public function createTable($name, $description, $content, $token)
    {
        global $systemVariable, $Setting;

        if (!$Setting["stopJsonStorage"]) {

            $Basic = new Basic_Function();
            $Table = $Basic->f_get($systemVariable["rootLib"]["TablePath"]);

            if (isset($name, $description, $content, $token)) {
                if (!is_numeric($name)) {
                    if (is_array($content)) {
                        $newTableArray = array(
                            $name => array(
                                "Description" => $description,
                                "CreateTime" => time(),
                                "LastChangeTime" => time(),
                                "Token" => $token,
                                "Content" => $content
                            )
                        );

                        if (isset($Table)) {
                            if (!isset($Table[$name])) {

                                $Table = array_merge($newTableArray, $Table);
                                $Basic->f_put($systemVariable["rootLib"]["TablePath"], $Table);

                                $output = array(
                                    "Status" => "OK"
                                );
                            } else {
                                $output = array(
                                    "Status" => "NO",
                                    "msg" => "已存在相同名称的数据库"
                                );
                            }
                        } else {
                            $output = array(
                                "Status" => "NO",
                                "msg" => "读取/写入数据库表文件失败！请使用 Check 函数"
                            );
                        }
                    } else {
                        $output = array(
                            "Status" => "NO",
                            "msg" => "通常的，jsonStorage初始数据库内容应该输入一个数组而不是其他数据类型"
                        );
                    }
                } else {
                    $output = array(
                        "Status" => "NO",
                        "msg" => "数据库名称不允许为纯数字"
                    );
                }
            } else {
                $output = array(
                    "Status" => "NO",
                    "msg" => "函数调用不完整，信息未输入"
                );
            }
        } else {
            $output = array(
                "Status" => "NO",
                "msg" => "拒绝访问"
            );
        }

        return $output;
    }

    public function deleteTable($name, $token, $password)
    {
        global $systemVariable, $Setting;

        if (!$Setting["stopJsonStorage"]) {

            $Basic = new Basic_Function();
            $Table = $Basic->f_get($systemVariable["rootLib"]["TablePath"]);

            if (isset($name, $token)) {
                if (isset($Table[$name])) {
                    if ($Table[$name]["Token"] == $token && $Setting["confirmPassword"] == $password) {
                        unset($Table[$name]);
                        $output = array(
                            "Status" => "OK"
                        );
                    } else {
                        $output = array(
                            "Status" => "NO",
                            "msg" => "Token/Password 不正确"
                        );
                    }
                } else {
                    $output = array(
                        "Status" => "NO",
                        "msg" => "数据库不存在"
                    );
                }
            } else {
                $output = array(
                    "Status" => "NO",
                    "msg" => "函数调用不完整，信息未输入"
                );
            }
        } else {
            $output = array(
                "Status" => "NO",
                "msg" => "拒绝访问"
            );
        }

        return $output;
    }
}

class jsonStorage_Main_Change extends Basic_Function
{
    public function getTable($name, $token)
    {
        global $systemVariable, $Setting;

        if (!$Setting["stopJsonStorage"]) {

            $Basic = new Basic_Function();
            $Table = $Basic->f_get($systemVariable["rootLib"]["TablePath"]);

            if (isset($name, $token)) {
                if (isset($Table[$name])) {
                    if ($Table[$name]["Token"] == $token) {
                        $output = array(
                            "Status" => "OK",
                            "dataName" => $name,
                            "dataContent" => $Table[$name]
                        );
                    } else {
                        $output = array(
                            "Status" => "NO",
                            "msg" => "Token 不正确"
                        );
                    }
                } else {
                    $output = array(
                        "Status" => "NO",
                        "msg" => "数据库不存在"
                    );
                }
            } else {
                $output = array(
                    "Status" => "NO",
                    "msg" => "函数调用不完整，信息未输入"
                );
            }
        } else {
            $output = array(
                "Status" => "NO",
                "msg" => "拒绝访问"
            );
        }

        return $output;
    }

    public function coverTable($name, $token, $content)
    {
        global $systemVariable, $Setting;

        if (!$Setting["stopJsonStorage"]) {

            $Basic = new Basic_Function();
            $Table = $Basic->f_get($systemVariable["rootLib"]["TablePath"]);

            if (isset($name, $token)) {
                if (isset($Table[$name])) {
                    if ($Table[$name]["Token"] == $token) {
                        if (is_array($content)) {
                            $Table[$name]["Content"] = $content;
                            $output = array(
                                "Status" => "OK"
                            );
                        }
                    } else {
                        $output = array(
                            "Status" => "NO",
                            "msg" => "Token/Password 不正确"
                        );
                    }
                } else {
                    $output = array(
                        "Status" => "NO",
                        "msg" => "数据库不存在"
                    );
                }
            } else {
                $output = array(
                    "Status" => "NO",
                    "msg" => "函数调用不完整，信息未输入"
                );
            }
        } else {
            $output = array(
                "Status" => "NO",
                "msg" => "拒绝访问"
            );
        }

        return $output;
    }
}
