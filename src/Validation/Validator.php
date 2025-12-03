<?php
/*
 * @Author: 7123
 * @Date: 2025-03-09 22:43:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:18:26
 */

namespace Luminode\Core\Validation;

class Validator {
    private array $errors = [];
    
    public function validate(array $data, array $rules): bool
    {
        $this->errors = []; // Reset errors on new validation
        
        foreach ($rules as $field => $validations) {
            $value = $data[$field] ?? null;

            // If a field is not required and is empty, no need to run other validations on it.
            if (strpos($validations, 'required') === false && empty($value)) {
                continue;
            }

            foreach (explode('|', $validations) as $ruleString) {
                $parts = explode(':', $ruleString, 2);
                $ruleName = $parts[0];
                $parameter = $parts[1] ?? null;
                
                $this->applyRule($field, $value, $ruleName, $parameter, $data, $validations);
            }
        }
        return empty($this->errors);
    }

    private function applyRule($field, $value, $ruleName, $parameter, array $data, string $allRules): void
    {
        switch ($ruleName) {
            case 'required':
                if (empty($value)) $this->addError($field, '必填字段');
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) $this->addError($field, '无效的邮箱格式');
                break;
            case 'min':
                if (str_contains($allRules, 'numeric')) {
                    if ($value < $parameter) $this->addError($field, "数值不能小于 {$parameter}");
                } else {
                    if (strlen($value) < $parameter) $this->addError($field, "长度不能少于 {$parameter} 个字符");
                }
                break;
            case 'max':
                if (str_contains($allRules, 'numeric')) {
                    if ($value > $parameter) $this->addError($field, "数值不能大于 {$parameter}");
                } else {
                    if (strlen($value) > $parameter) $this->addError($field, "长度不能超过 {$parameter} 个字符");
                }
                break;
            case 'between':
                [$min, $max] = explode(',', $parameter);
                if ($value < $min || $value > $max) $this->addError($field, "数值必须在 {$min} 和 {$max} 之间");
                break;
            case 'numeric':
                if (!is_numeric($value)) $this->addError($field, '必须是数字');
                break;
            case 'alpha':
                if (!ctype_alpha($value)) $this->addError($field, '只能包含字母');
                break;
            case 'alpha_num':
                if (!ctype_alnum($value)) $this->addError($field, '只能包含字母和数字');
                break;
            case 'date':
                if (strtotime($value) === false) $this->addError($field, '无效的日期格式');
                break;
            case 'url':
                if (filter_var($value, FILTER_VALIDATE_URL) === false) $this->addError($field, '无效的URL格式');
                break;
            case 'ip':
                if (filter_var($value, FILTER_VALIDATE_IP) === false) $this->addError($field, '无效的IP地址格式');
                break;
            case 'confirmed':
                $confirmationField = $field . '_confirmation';
                if (!isset($data[$confirmationField]) || $value !== $data[$confirmationField]) {
                    $this->addError($field, '确认字段不匹配');
                }
                break;
        }
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
