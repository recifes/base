/**
 * HSN Form Validator
 * Validação de formulários client-side
 */

class HSNFormValidator {
  constructor(formSelector) {
    this.form = document.querySelector(formSelector);
    this.errors = {};

    if (this.form) {
      this._setupValidation();
    }
  }

  /**
   * Configura validação no submit
   * @private
   */
  _setupValidation() {
    this.form.addEventListener('submit', (e) => {
      if (!this.validate()) {
        e.preventDefault();
        this.showErrors();
      }
    });
  }

  /**
   * Valida campo obrigatório
   * @param {string} fieldName
   * @param {string} message
   */
  required(fieldName, message = null) {
    const field = this.form.querySelector(`[name="${fieldName}"]`);

    if (!field || !field.value.trim()) {
      this._addError(fieldName, message || `${fieldName} é obrigatório`);
      return false;
    }

    return true;
  }

  /**
   * Valida email
   * @param {string} fieldName
   * @param {string} message
   */
  email(fieldName, message = null) {
    const field = this.form.querySelector(`[name="${fieldName}"]`);
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (field && field.value && !emailRegex.test(field.value)) {
      this._addError(fieldName, message || 'Email inválido');
      return false;
    }

    return true;
  }

  /**
   * Valida tamanho mínimo
   * @param {string} fieldName
   * @param {number} min
   * @param {string} message
   */
  minLength(fieldName, min, message = null) {
    const field = this.form.querySelector(`[name="${fieldName}"]`);

    if (field && field.value.length < min) {
      this._addError(
        fieldName,
        message || `${fieldName} deve ter no mínimo ${min} caracteres`
      );
      return false;
    }

    return true;
  }

  /**
   * Valida tamanho máximo
   * @param {string} fieldName
   * @param {number} max
   * @param {string} message
   */
  maxLength(fieldName, max, message = null) {
    const field = this.form.querySelector(`[name="${fieldName}"]`);

    if (field && field.value.length > max) {
      this._addError(
        fieldName,
        message || `${fieldName} deve ter no máximo ${max} caracteres`
      );
      return false;
    }

    return true;
  }

  /**
   * Valida correspondência entre campos
   * @param {string} fieldName
   * @param {string} matchFieldName
   * @param {string} message
   */
  match(fieldName, matchFieldName, message = null) {
    const field = this.form.querySelector(`[name="${fieldName}"]`);
    const matchField = this.form.querySelector(`[name="${matchFieldName}"]`);

    if (field && matchField && field.value !== matchField.value) {
      this._addError(
        fieldName,
        message || `${fieldName} deve corresponder a ${matchFieldName}`
      );
      return false;
    }

    return true;
  }

  /**
   * Valida pattern regex
   * @param {string} fieldName
   * @param {RegExp} pattern
   * @param {string} message
   */
  pattern(fieldName, pattern, message = null) {
    const field = this.form.querySelector(`[name="${fieldName}"]`);

    if (field && field.value && !pattern.test(field.value)) {
      this._addError(fieldName, message || `${fieldName} está em formato inválido`);
      return false;
    }

    return true;
  }

  /**
   * Validação customizada
   * @param {string} fieldName
   * @param {Function} validator
   * @param {string} message
   */
  custom(fieldName, validator, message = null) {
    const field = this.form.querySelector(`[name="${fieldName}"]`);

    if (field && !validator(field.value)) {
      this._addError(fieldName, message || `${fieldName} é inválido`);
      return false;
    }

    return true;
  }

  /**
   * Valida todos os campos
   * @returns {boolean}
   */
  validate() {
    this.errors = {};
    this.clearErrors();

    // Execute suas regras de validação aqui
    // Retorne false se houver erros

    return Object.keys(this.errors).length === 0;
  }

  /**
   * Adiciona erro
   * @param {string} fieldName
   * @param {string} message
   * @private
   */
  _addError(fieldName, message) {
    if (!this.errors[fieldName]) {
      this.errors[fieldName] = [];
    }
    this.errors[fieldName].push(message);
  }

  /**
   * Mostra erros no formulário
   */
  showErrors() {
    Object.keys(this.errors).forEach((fieldName) => {
      const field = this.form.querySelector(`[name="${fieldName}"]`);

      if (field) {
        field.classList.add('is-invalid');

        // Remove feedback anterior
        const existingFeedback = field.parentElement.querySelector('.invalid-feedback');
        if (existingFeedback) {
          existingFeedback.remove();
        }

        // Adiciona novo feedback
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = this.errors[fieldName][0];
        field.parentElement.appendChild(feedback);
      }
    });
  }

  /**
   * Limpa erros do formulário
   */
  clearErrors() {
    this.form.querySelectorAll('.is-invalid').forEach((field) => {
      field.classList.remove('is-invalid');
    });

    this.form.querySelectorAll('.invalid-feedback').forEach((feedback) => {
      feedback.remove();
    });
  }

  /**
   * Obtém dados do formulário
   * @returns {object}
   */
  getData() {
    const formData = new FormData(this.form);
    const data = {};

    formData.forEach((value, key) => {
      data[key] = value;
    });

    return data;
  }
}

// Export para uso em módulos
if (typeof module !== 'undefined' && module.exports) {
  module.exports = HSNFormValidator;
}
