/**
 * HSN Notifications
 * Sistema de notificações usando Argon Dashboard
 */

class HSNNotifications {
  constructor(options = {}) {
    this.defaultOptions = {
      type: 'info', // success, info, warning, danger
      placement: {
        from: 'top',
        align: 'right',
      },
      offset: {
        x: 20,
        y: 20,
      },
      delay: 3000,
      animate: {
        enter: 'animated fadeInDown',
        exit: 'animated fadeOutUp',
      },
      ...options,
    };
  }

  /**
   * Mostra notificação
   * @param {string} message
   * @param {object} options
   */
  show(message, options = {}) {
    const config = { ...this.defaultOptions, ...options };

    if (typeof $.notify === 'function') {
      // Usando plugin do Argon
      $.notify(
        {
          icon: config.icon || this._getIcon(config.type),
          message: message,
        },
        {
          type: config.type,
          placement: config.placement,
          offset: config.offset,
          delay: config.delay,
          animate: config.animate,
        }
      );
    } else {
      // Fallback para console
      console.log(`[${config.type.toUpperCase()}] ${message}`);
    }
  }

  /**
   * Notificação de sucesso
   * @param {string} message
   * @param {object} options
   */
  success(message, options = {}) {
    this.show(message, { ...options, type: 'success' });
  }

  /**
   * Notificação de informação
   * @param {string} message
   * @param {object} options
   */
  info(message, options = {}) {
    this.show(message, { ...options, type: 'info' });
  }

  /**
   * Notificação de aviso
   * @param {string} message
   * @param {object} options
   */
  warning(message, options = {}) {
    this.show(message, { ...options, type: 'warning' });
  }

  /**
   * Notificação de erro
   * @param {string} message
   * @param {object} options
   */
  error(message, options = {}) {
    this.show(message, { ...options, type: 'danger', delay: 5000 });
  }

  /**
   * Obtém ícone baseado no tipo
   * @param {string} type
   * @private
   */
  _getIcon(type) {
    const icons = {
      success: 'ni ni-check-bold',
      info: 'ni ni-bell-55',
      warning: 'ni ni-support-16',
      danger: 'ni ni-fat-remove',
    };

    return icons[type] || icons.info;
  }

  /**
   * Notificação de erro de API
   * @param {object} error
   */
  apiError(error) {
    let message = 'Erro ao processar requisição';

    if (error.message) {
      message = error.message;
    }

    if (error.errors && typeof error.errors === 'object') {
      const errorList = Object.values(error.errors).flat();
      message += ':\n' + errorList.join('\n');
    }

    this.error(message);
  }
}

// Instância global
const notify = new HSNNotifications();

// Export para uso em módulos
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { HSNNotifications, notify };
}
