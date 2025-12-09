/**
 * HSN Storage
 * Utilitário para gerenciar localStorage e sessionStorage
 */

class HSNStorage {
  constructor(type = 'local') {
    this.storage = type === 'session' ? sessionStorage : localStorage;
  }

  /**
   * Define valor no storage
   * @param {string} key
   * @param {any} value
   */
  set(key, value) {
    try {
      const serialized = JSON.stringify(value);
      this.storage.setItem(key, serialized);
      return true;
    } catch (error) {
      console.error('Storage set error:', error);
      return false;
    }
  }

  /**
   * Obtém valor do storage
   * @param {string} key
   * @param {any} defaultValue
   * @returns {any}
   */
  get(key, defaultValue = null) {
    try {
      const item = this.storage.getItem(key);

      if (item === null) {
        return defaultValue;
      }

      return JSON.parse(item);
    } catch (error) {
      console.error('Storage get error:', error);
      return defaultValue;
    }
  }

  /**
   * Remove item do storage
   * @param {string} key
   */
  remove(key) {
    this.storage.removeItem(key);
  }

  /**
   * Limpa todo o storage
   */
  clear() {
    this.storage.clear();
  }

  /**
   * Verifica se key existe
   * @param {string} key
   * @returns {boolean}
   */
  has(key) {
    return this.storage.getItem(key) !== null;
  }

  /**
   * Obtém todas as keys
   * @returns {string[]}
   */
  keys() {
    return Object.keys(this.storage);
  }

  /**
   * Define valor com expiração
   * @param {string} key
   * @param {any} value
   * @param {number} expiresIn Tempo em segundos
   */
  setWithExpiry(key, value, expiresIn) {
    const now = new Date();

    const item = {
      value: value,
      expiry: now.getTime() + expiresIn * 1000,
    };

    this.set(key, item);
  }

  /**
   * Obtém valor com verificação de expiração
   * @param {string} key
   * @param {any} defaultValue
   * @returns {any}
   */
  getWithExpiry(key, defaultValue = null) {
    const item = this.get(key);

    if (!item) {
      return defaultValue;
    }

    const now = new Date();

    if (now.getTime() > item.expiry) {
      this.remove(key);
      return defaultValue;
    }

    return item.value;
  }

  /**
   * Incrementa valor numérico
   * @param {string} key
   * @param {number} amount
   */
  increment(key, amount = 1) {
    const current = this.get(key, 0);
    this.set(key, current + amount);
    return current + amount;
  }

  /**
   * Decrementa valor numérico
   * @param {string} key
   * @param {number} amount
   */
  decrement(key, amount = 1) {
    return this.increment(key, -amount);
  }

  /**
   * Adiciona item a um array
   * @param {string} key
   * @param {any} item
   */
  push(key, item) {
    const arr = this.get(key, []);

    if (!Array.isArray(arr)) {
      console.error('Storage value is not an array');
      return false;
    }

    arr.push(item);
    this.set(key, arr);
    return true;
  }

  /**
   * Remove item de um array
   * @param {string} key
   * @param {Function} predicate
   */
  pull(key, predicate) {
    const arr = this.get(key, []);

    if (!Array.isArray(arr)) {
      console.error('Storage value is not an array');
      return false;
    }

    const filtered = arr.filter((item) => !predicate(item));
    this.set(key, filtered);
    return true;
  }
}

// Instâncias globais
const storage = new HSNStorage('local');
const session = new HSNStorage('session');

// Export para uso em módulos
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { HSNStorage, storage, session };
}
