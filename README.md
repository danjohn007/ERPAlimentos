# ERP Quesos - Sistema de Gestión para Fábrica de Quesos

Un sistema integral de gestión empresarial (ERP) especializado para fábricas de quesos, desarrollado con PHP puro, MySQL y Bootstrap.

## 🧀 Características Principales

### Módulos Implementados
- **🏭 Gestión de Producción**: Recetas, lotes, control de tiempos de maduración
- **🥛 Materias Primas**: Control de leche, insumos, proveedores certificados
- **🎯 Control de Calidad**: Análisis, trazabilidad completa, alertas de caducidad
- **📦 Inventarios**: Control por ubicación, lotes, fechas de caducidad
- **💰 Ventas y Distribución**: Clientes, órdenes, facturación electrónica
- **🛒 Compras**: Órdenes de compra, recepción, cuentas por pagar
- **💹 Finanzas**: Contabilidad automática, reportes financieros
- **👥 Recursos Humanos**: Empleados, nómina, capacitaciones
- **📊 Reportes e BI**: KPIs, dashboards interactivos
- **⚖️ Cumplimiento Normativo**: NOM-251, HACCP, trazabilidad

### Tecnologías Utilizadas
- **Backend**: PHP 7+ (puro, sin framework)
- **Base de Datos**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Gráficas**: Chart.js
- **Iconos**: Font Awesome 6
- **Autenticación**: Sesiones seguras con password_hash()

## 🚀 Instalación Rápida

### Requisitos del Sistema
- Apache 2.4+ con mod_rewrite activado
- PHP 7.4+ con extensiones: PDO, PDO_MySQL, mbstring, json
- MySQL 5.7+ o MariaDB 10.2+

### Paso 1: Clonar el Repositorio
```bash
git clone https://github.com/danjohn007/ErpQuesos.git
cd ErpQuesos
```

### Paso 2: Configurar Permisos
```bash
chmod 755 uploads logs
chmod 644 config/config.php
```

### Paso 3: Configurar Apache
Asegúrese de que mod_rewrite esté activado:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Paso 4: Configurar Base de Datos
1. Abra `config/config.php` y ajuste las credenciales de base de datos:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'erp_quesos');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### Paso 5: Instalar el Sistema
1. Navegue a `http://tu-servidor/ErpQuesos/test_connection.php`
2. Verifique que todas las configuraciones estén correctas
3. Vaya a `http://tu-servidor/ErpQuesos/install.php`
4. Haga clic en "Instalar Base de Datos"

### Paso 6: Acceder al Sistema
Navegue a `http://tu-servidor/ErpQuesos/` y use una de estas credenciales:

**Administrador:**
- Usuario: `admin`
- Contraseña: `admin123`

**Supervisor:**
- Usuario: `supervisor1`
- Contraseña: `supervisor123`

**Operador:**
- Usuario: `operador1`
- Contraseña: `operador123`

## 🔧 Configuración Avanzada

### URL Base Automática
El sistema detecta automáticamente la URL base, pero puede configurarla manualmente en `config/config.php`:
```php
define('BASE_URL', 'https://tu-dominio.com/ErpQuesos');
```

### Configuración de Timezone
```php
define('DEFAULT_TIMEZONE', 'America/Mexico_City');
```

### Configuración de Archivos
```php
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf']);
```

## 📁 Estructura del Proyecto

```
ErpQuesos/
├── app/
│   ├── controllers/      # Controladores MVC
│   ├── models/          # Modelos de datos
│   ├── views/           # Vistas y plantillas
│   └── core/            # Clases del núcleo
├── config/              # Archivos de configuración
├── public/              # Recursos públicos (CSS, JS, imágenes)
├── uploads/             # Archivos subidos
├── logs/                # Archivos de log
├── .htaccess           # Configuración Apache
├── index.php           # Punto de entrada principal
├── install.php         # Script de instalación
├── install.sql         # Esquema de base de datos
└── test_connection.php # Test de configuración
```

## 🗄️ Base de Datos

### Tablas Principales
- `usuarios` - Gestión de usuarios del sistema
- `proveedores` - Proveedores de materias primas
- `clientes` - Clientes y distribuidores
- `productos` - Catálogo de productos (quesos)
- `materias_primas` - Leche, cuajo, sal, cultivos
- `recetas` - Fórmulas de producción
- `lotes_produccion` - Control de lotes de producción
- `inventario` - Control de existencias

### Datos de Ejemplo Incluidos
- 3 usuarios con diferentes roles
- 3 proveedores certificados
- 3 clientes (mayorista, minorista, distribuidor)
- 4 productos (frescos, semicurados, curados)
- 4 materias primas básicas
- 3 recetas de ejemplo
- Lotes de producción e inventario inicial

## 🌐 URLs del Sistema

### Acceso Principal
- `/` - Dashboard principal
- `/login` - Página de inicio de sesión
- `/dashboard` - Panel de control

### Módulos Principales
- `/produccion` - Gestión de producción
- `/materias-primas` - Materias primas y proveedores
- `/calidad` - Control de calidad
- `/inventario` - Gestión de inventarios
- `/ventas` - Ventas y clientes
- `/compras` - Compras y abastecimiento
- `/finanzas` - Finanzas y contabilidad
- `/rrhh` - Recursos humanos
- `/reportes` - Reportes e inteligencia de negocio
- `/admin` - Administración del sistema

## 🔒 Seguridad

### Características de Seguridad Implementadas
- Hash seguro de contraseñas con Argon2ID
- Protección contra inyección SQL con PDO
- Validación de entrada y escape de salida
- Protección CSRF para formularios
- Control de acceso basado en roles
- Sesiones seguras
- Archivos de configuración protegidos

### Roles de Usuario
- **Admin**: Acceso completo al sistema
- **Supervisor**: Gestión de producción y calidad
- **Operador**: Operaciones básicas de producción
- **Vendedor**: Módulo de ventas y clientes
- **Contabilidad**: Módulo financiero y reportes

## 📊 Reportes y KPIs

### Dashboards Disponibles
- Producción diaria y semanal
- Inventario en tiempo real
- Productos próximos a vencer
- Ventas del mes
- Lotes en proceso

### KPIs Principales
- Rendimiento de leche → queso
- Producción por tipo de queso
- Mermas y desperdicios
- Costos de producción vs precio de venta
- Ventas por cliente/región

## 🛠️ Desarrollo y Personalización

### Agregar Nuevos Módulos
1. Crear controlador en `app/controllers/`
2. Crear modelo en `app/models/`
3. Crear vistas en `app/views/`
4. Agregar rutas en `index.php`

### Personalizar Estilos
Los estilos están en `app/views/layouts/main.php` y pueden personalizarse modificando las variables CSS:
```css
:root {
    --primary-color: #2E8B57;
    --secondary-color: #FFD700;
    /* ... más variables */
}
```

## 🐛 Resolución de Problemas

### Problemas Comunes

**Error 500 - Error interno del servidor**
- Verificar permisos de archivos y directorios
- Revisar logs de Apache en `/var/log/apache2/error.log`
- Verificar que mod_rewrite esté activado

**No se puede conectar a la base de datos**
- Verificar credenciales en `config/config.php`
- Asegurar que MySQL esté ejecutándose
- Verificar permisos del usuario de base de datos

**Las rutas no funcionan (404)**
- Verificar que mod_rewrite esté activado
- Comprobar que el archivo `.htaccess` tenga permisos correctos
- Verificar configuración de Apache AllowOverride

### Logs del Sistema
Los logs se guardan en el directorio `logs/`:
- `system.log` - Logs generales del sistema
- `error.log` - Errores de la aplicación
- `access.log` - Log de accesos

## 🗺️ Roadmap

### Versión 1.1 (Próxima)
- [ ] Módulo de mantenimiento de equipos
- [ ] Integración con balanzas automáticas
- [ ] Notificaciones push en tiempo real
- [ ] API REST para integraciones

### Versión 1.2
- [ ] Módulo de trazabilidad avanzada
- [ ] Integración con COFEPRIS
- [ ] E-commerce integrado
- [ ] App móvil para operadores

### Versión 2.0
- [ ] Migración a framework moderno
- [ ] Arquitectura de microservicios
- [ ] Inteligencia artificial para predicciones
- [ ] IoT para sensores de temperatura

## 📄 Licencia

Este proyecto está licenciado bajo la [MIT License](LICENSE).

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:
1. Fork el proyecto
2. Cree una rama para su feature (`git checkout -b feature/AmazingFeature`)
3. Commit sus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abra un Pull Request

## 📞 Soporte

Para soporte técnico o consultas:
- Email: soporte@erpquesos.com
- Issues: [GitHub Issues](https://github.com/danjohn007/ErpQuesos/issues)
- Documentación: [Wiki del proyecto](https://github.com/danjohn007/ErpQuesos/wiki)

---

**ERP Quesos v1.0** - Sistema de Gestión para Fábrica de Quesos
© 2024 - Desarrollado con ❤️ para la industria quesera
