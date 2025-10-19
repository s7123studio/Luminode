<?php

namespace Luminode\Core\Validation;

class Validator {
    // 定义一个私有属性来存储错误信息
    private $errors = [];
    
    // 验证数据的方法，接收数据和规则两个参数
    public function validate(array $data, array $rules) {
        // 遍历规则数组
        foreach ($rules as $field => $validations) {
            // 将每个字段的验证规则按'|'分割成多个规则
            foreach (explode('|', $validations) as $rule) {
                // 应用每个规则
                $this->applyRule($field, $data[$field] ?? null, $rule);
            }
        }
        // 返回是否有错误信息，如果没有错误则返回true，否则返回false
        return empty($this->errors);
    }

    // 应用单个验证规则的方法
    private function applyRule($field, $value, $rule) {
        // 根据规则类型进行不同的验证
        switch ($rule) {
            case 'required':
                // 如果字段为空，则添加错误信息
                if (empty($value)) $this->errors[$field][] = "必填字段";
                break;
            case 'email':
                // 如果字段不是有效的邮箱格式，则添加错误信息
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) 
                    $this->errors[$field][] = "无效的邮箱格式";
                break;
            // 更多验证规则...
        }
    }

    public function errors() {
        return $this->errors;
    }
}
