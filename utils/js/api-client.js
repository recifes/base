/**
 * HSN API Client
 * Cliente para fazer requisições a APIs do ecossistema HSN
 */

class HSNApiClient {
  constructor(baseUrl = '') {
    this.baseUrl = baseUrl;
    this.headers = {
      'Content-Type': 'application/json',
    };
  }

  /**
   * Define header customizado
   * @param {string} key
   * @param {string} value
   */
  setHeader(key, value) {
    this.headers[key] = value;
  }

  /**
   * Remove header
   * @param {string} key
   */
  removeHeader(key) {
    delete this.headers[key];
  }

  /**
   * Requisição genérica
   * @param {string} endpoint
   * @param {object} options
   * @returns {Promise}
   */
  async request(endpoint, options = {}) {
    const url = this.baseUrl + endpoint;

    const config = {
      method: options.method || 'GET',
      headers: { ...this.headers, ...options.headers },
    };

    if (options.body) {
      config.body = JSON.stringify(options.body);
    }

    try {
      const response = await fetch(url, config);
      const data = await response.json();

      if (!response.ok) {
        throw {
          status: response.status,
          message: data.message || 'Erro na requisição',
          errors: data.errors || null,
        };
      }

      return data;
    } catch (error) {
      console.error('API Error:', error);
      throw error;
    }
  }

  /**
   * GET request
   * @param {string} endpoint
   * @param {object} options
   */
  async get(endpoint, options = {}) {
    return this.request(endpoint, { ...options, method: 'GET' });
  }

  /**
   * POST request
   * @param {string} endpoint
   * @param {object} body
   * @param {object} options
   */
  async post(endpoint, body, options = {}) {
    return this.request(endpoint, { ...options, method: 'POST', body });
  }

  /**
   * PUT request
   * @param {string} endpoint
   * @param {object} body
   * @param {object} options
   */
  async put(endpoint, body, options = {}) {
    return this.request(endpoint, { ...options, method: 'PUT', body });
  }

  /**
   * DELETE request
   * @param {string} endpoint
   * @param {object} options
   */
  async delete(endpoint, options = {}) {
    return this.request(endpoint, { ...options, method: 'DELETE' });
  }

  /**
   * Login via API local
   * @param {string} email
   * @param {string} password
   */
  async login(email, password) {
    return this.post('/api/auth.php?action=login', { email, password });
  }

  /**
   * Login via Zimbros
   * @param {string} email
   * @param {string} password
   */
  async loginZimbros(email, password) {
    return this.post('/api/auth.php?action=zimbros-login', { email, password });
  }

  /**
   * Logout
   */
  async logout() {
    return this.post('/api/auth.php?action=logout');
  }

  /**
   * Verifica sessão
   */
  async checkSession() {
    return this.get('/api/auth.php?action=check');
  }
}

// Export para uso em módulos
if (typeof module !== 'undefined' && module.exports) {
  module.exports = HSNApiClient;
}
