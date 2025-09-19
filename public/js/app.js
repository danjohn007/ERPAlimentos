/**
 * JavaScript principal para ERP Quesos
 */

// Configuración global
const ErpQuesos = {
    baseUrl: window.location.origin + window.location.pathname.replace(/\/[^/]*$/, ''),
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
    
    // Inicializar aplicación
    init: function() {
        this.setupEventListeners();
        this.setupAjax();
        this.setupValidations();
        this.setupTooltips();
    },
    
    // Event listeners globales
    setupEventListeners: function() {
        // Confirmación de eliminación
        document.addEventListener('click', function(e) {
            if (e.target.matches('.btn-delete, .delete-btn')) {
                e.preventDefault();
                const message = e.target.dataset.message || '¿Está seguro de que desea eliminar este elemento?';
                if (confirm(message)) {
                    if (e.target.tagName === 'A') {
                        window.location.href = e.target.href;
                    } else if (e.target.form) {
                        e.target.form.submit();
                    }
                }
            }
        });
        
        // Auto-hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert.alert-success, .alert.alert-info');
            alerts.forEach(alert => {
                if (alert.classList.contains('fade')) {
                    alert.classList.remove('show');
                } else {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
        
        // Loading state para formularios
        document.addEventListener('submit', function(e) {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                ErpQuesos.showLoading(submitBtn);
            }
        });
        
        // Toggle sidebar en móvil
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('show');
            });
        }
    },
    
    // Configuración AJAX
    setupAjax: function() {
        // Headers por defecto para AJAX
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            options.headers = options.headers || {};
            if (ErpQuesos.csrfToken) {
                options.headers['X-CSRF-Token'] = ErpQuesos.csrfToken;
            }
            options.headers['X-Requested-With'] = 'XMLHttpRequest';
            return originalFetch(url, options);
        };
    },
    
    // Validaciones de formularios
    setupValidations: function() {
        // Validación de campos numéricos
        document.addEventListener('input', function(e) {
            if (e.target.matches('.numeric-only')) {
                e.target.value = e.target.value.replace(/[^0-9.]/g, '');
            }
            
            if (e.target.matches('.integer-only')) {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            }
            
            if (e.target.matches('.currency')) {
                let value = e.target.value.replace(/[^0-9.]/g, '');
                if (value.includes('.')) {
                    const parts = value.split('.');
                    value = parts[0] + '.' + parts[1].substring(0, 2);
                }
                e.target.value = value;
            }
        });
        
        // Validación de email
        document.addEventListener('blur', function(e) {
            if (e.target.type === 'email' && e.target.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(e.target.value)) {
                    e.target.classList.add('is-invalid');
                } else {
                    e.target.classList.remove('is-invalid');
                }
            }
        });
    },
    
    // Configurar tooltips
    setupTooltips: function() {
        // Inicializar tooltips de Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },
    
    // Utilidades
    showLoading: function(button, text = 'Procesando...') {
        button.disabled = true;
        button.dataset.originalText = button.innerHTML;
        button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${text}`;
    },
    
    hideLoading: function(button) {
        button.disabled = false;
        if (button.dataset.originalText) {
            button.innerHTML = button.dataset.originalText;
        }
    },
    
    showAlert: function(message, type = 'info') {
        const alertContainer = document.querySelector('.alert-container') || document.body;
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        alertContainer.insertBefore(alert, alertContainer.firstChild);
        
        // Auto-hide después de 5 segundos para success e info
        if (type === 'success' || type === 'info') {
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }, 5000);
        }
    },
    
    formatMoney: function(amount, currency = '$') {
        return currency + parseFloat(amount).toLocaleString('es-MX', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    },
    
    formatNumber: function(number, decimals = 2) {
        return parseFloat(number).toLocaleString('es-MX', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    },
    
    formatDate: function(date, format = 'dd/mm/yyyy') {
        const d = new Date(date);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        
        return format
            .replace('dd', day)
            .replace('mm', month)
            .replace('yyyy', year);
    },
    
    // AJAX helpers
    ajax: function(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        
        if (this.csrfToken) {
            defaultOptions.headers['X-CSRF-Token'] = this.csrfToken;
        }
        
        const finalOptions = { ...defaultOptions, ...options };
        
        return fetch(url, finalOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                this.showAlert('Error en la comunicación con el servidor', 'danger');
                throw error;
            });
    },
    
    get: function(url) {
        return this.ajax(url);
    },
    
    post: function(url, data) {
        return this.ajax(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    },
    
    put: function(url, data) {
        return this.ajax(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },
    
    delete: function(url) {
        return this.ajax(url, {
            method: 'DELETE'
        });
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    ErpQuesos.init();
});

// Funciones globales para compatibilidad
function confirmarEliminacion(mensaje = '¿Está seguro de que desea eliminar este elemento?') {
    return confirm(mensaje);
}

function showLoading(button, text = 'Procesando...') {
    ErpQuesos.showLoading(button, text);
}

function hideLoading(button) {
    ErpQuesos.hideLoading(button);
}