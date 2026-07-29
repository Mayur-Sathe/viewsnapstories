<?php
class Validator {
    private array $errors = [];

    public function validate(array $data, array $rules): bool {
        $this->errors = [];
        foreach ($rules as $field => $ruleSet) {
            $value = $data[$field] ?? null;
            foreach (explode('|', $ruleSet) as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$rule, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                }
                $method = 'rule' . ucfirst($rule);
                if (method_exists($this, $method)) {
                    $this->$method($field, $value, $params);
                }
            }
        }
        return empty($this->errors);
    }

    public function errors(): array { return $this->errors; }

    private function addError(string $field, string $message): void {
        $this->errors[$field][] = $message;
    }

    private function ruleRequired(string $field, mixed $value, array $params): void {
        if ($value === null || (is_string($value) && trim($value) === '')) {
            $this->addError($field, ucfirst($field) . ' is required');
        }
    }

    private function ruleMin(string $field, mixed $value, array $params): void {
        $min = (int)($params[0] ?? 0);
        if (is_string($value) && strlen(trim($value)) < $min) {
            $this->addError($field, ucfirst($field) . ' must be at least ' . $min . ' characters');
        }
    }

    private function ruleMax(string $field, mixed $value, array $params): void {
        $max = (int)($params[0] ?? 0);
        if (is_string($value) && strlen(trim($value)) > $max) {
            $this->addError($field, ucfirst($field) . ' must not exceed ' . $max . ' characters');
        }
    }

    private function ruleEmail(string $field, mixed $value, array $params): void {
        if (is_string($value) && !filter_var(trim($value), FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'Invalid email address');
        }
    }

    private function ruleUsername(string $field, mixed $value, array $params): void {
        if (is_string($value) && !preg_match('/^[a-z0-9._-]+$/i', trim($value))) {
            $this->addError($field, 'Username can only contain letters, numbers, dots, underscores, and hyphens');
        }
    }
}
