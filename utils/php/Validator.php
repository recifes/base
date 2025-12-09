<?php
/**
 * Classe Validator HSN
 * Utilitário para validação de dados
 */

namespace HSN\Utils;

class Validator
{
    private $errors = [];
    private $data = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Valida campo obrigatório
     *
     * @param string $field
     * @param string $message
     * @return self
     */
    public function required($field, $message = null)
    {
        if (!isset($this->data[$field]) || empty($this->data[$field])) {
            $this->errors[$field][] = $message ?: "O campo {$field} é obrigatório";
        }

        return $this;
    }

    /**
     * Valida email
     *
     * @param string $field
     * @param string $message
     * @return self
     */
    public function email($field, $message = null)
    {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = $message ?: "O campo {$field} deve ser um email válido";
        }

        return $this;
    }

    /**
     * Valida tamanho mínimo
     *
     * @param string $field
     * @param int $min
     * @param string $message
     * @return self
     */
    public function min($field, $min, $message = null)
    {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field][] = $message ?: "O campo {$field} deve ter no mínimo {$min} caracteres";
        }

        return $this;
    }

    /**
     * Valida tamanho máximo
     *
     * @param string $field
     * @param int $max
     * @param string $message
     * @return self
     */
    public function max($field, $max, $message = null)
    {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field][] = $message ?: "O campo {$field} deve ter no máximo {$max} caracteres";
        }

        return $this;
    }

    /**
     * Valida número
     *
     * @param string $field
     * @param string $message
     * @return self
     */
    public function numeric($field, $message = null)
    {
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field][] = $message ?: "O campo {$field} deve ser um número";
        }

        return $this;
    }

    /**
     * Valida se valor está em um array de opções
     *
     * @param string $field
     * @param array $options
     * @param string $message
     * @return self
     */
    public function in($field, $options, $message = null)
    {
        if (isset($this->data[$field]) && !in_array($this->data[$field], $options)) {
            $this->errors[$field][] = $message ?: "O campo {$field} deve ser um dos valores: " . implode(', ', $options);
        }

        return $this;
    }

    /**
     * Valida URL
     *
     * @param string $field
     * @param string $message
     * @return self
     */
    public function url($field, $message = null)
    {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
            $this->errors[$field][] = $message ?: "O campo {$field} deve ser uma URL válida";
        }

        return $this;
    }

    /**
     * Valida correspondência entre campos
     *
     * @param string $field
     * @param string $matchField
     * @param string $message
     * @return self
     */
    public function match($field, $matchField, $message = null)
    {
        if (isset($this->data[$field]) && isset($this->data[$matchField])) {
            if ($this->data[$field] !== $this->data[$matchField]) {
                $this->errors[$field][] = $message ?: "O campo {$field} deve corresponder ao campo {$matchField}";
            }
        }

        return $this;
    }

    /**
     * Valida regex customizado
     *
     * @param string $field
     * @param string $pattern
     * @param string $message
     * @return self
     */
    public function regex($field, $pattern, $message = null)
    {
        if (isset($this->data[$field]) && !preg_match($pattern, $this->data[$field])) {
            $this->errors[$field][] = $message ?: "O campo {$field} está em formato inválido";
        }

        return $this;
    }

    /**
     * Verifica se validação passou
     *
     * @return bool
     */
    public function passes()
    {
        return empty($this->errors);
    }

    /**
     * Verifica se validação falhou
     *
     * @return bool
     */
    public function fails()
    {
        return !$this->passes();
    }

    /**
     * Retorna erros
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Validação rápida (método estático)
     *
     * @param array $data
     * @param array $rules
     * @return array Erros ou array vazio
     */
    public static function make($data, $rules)
    {
        $validator = new self($data);

        foreach ($rules as $field => $fieldRules) {
            $ruleList = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            foreach ($ruleList as $rule) {
                $parts = explode(':', $rule);
                $ruleName = $parts[0];
                $params = isset($parts[1]) ? explode(',', $parts[1]) : [];

                if (method_exists($validator, $ruleName)) {
                    call_user_func_array([$validator, $ruleName], array_merge([$field], $params));
                }
            }
        }

        return $validator->errors();
    }
}
