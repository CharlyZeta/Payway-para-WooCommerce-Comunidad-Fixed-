# Payway Payment Gateway for WordPress
Plugin de integración con Payway (anteriormente Decidir) para WooCommerce.

## Importante
> **No utilizar directamente en un entorno Productivo**.
>
> En cambio, utilizar un entorno de desarrollo/integración/UAT, y realizar las pruebas necesarias confirmando el correcto funcionamiento del plugin y su comportamiento con las personalizaciones y Theme que tiene en su sitio.

Todas las instalaciones de WordPress varían en sus características, dependiendo de los plugins, tema activo (theme), personalizaciones, caché, etc. que tengan implementadas. Es por ello que se recomienda enfáticamente realizar pruebas en un entorno previo (siendo este una copia fiel de su actual sitio), para confirmar que ninguno de las características mencionadas afecten el comportamiento de este plugin.

## Requisitos
* Wordpress `>= 5.8.3`
* WooCommerce `>= 6.0`
* PHP `>= 7.4`
* MySQL `>= 5.7` || MariaDb `>= 10.2`
* **Compatibilidad**: Compatible con **HPOS** (High Performance Order Storage).
* **Checkout**: Requiere el **Checkout Clásico (Shortcode)**. No es compatible con el nuevo Checkout basado en Bloques de Gutenberg.

## Instalación
Para la instalación puede optar por subir el plugin a través del Administrador. O bien, copiar el plugin manualmente dentro de la instalación de WordPress.

### Vía Administrador
1. Ingrese al panel de Administración de WordPress.
2. Ingrese a la sección _Plugins > Agregar nuevo_.
3. Haga click en el botón _Subir plugin_.
4. Seleccione el archivo `.zip` del plugin.
5. Haga click en _Instalar ahora_ y luego en _Activar_.

### Copia Manual
1. Descomprimir el archivo `wc-gateway-payway.zip`.
2. Copiar la carpeta `wc-gateway-payway` a `wp-content/plugins/`.
3. Ingrese al panel de Administración y active el plugin.

## Configuración
### Credenciales y Datos del Comercio

1. Vaya a _WooCommerce -> Ajustes -> Pagos_.
2. Active **Medio de Pago Payway** y presione **Gestionar**.
3. Complete las credenciales (Sandbox para pruebas o Producción para ventas reales).

| Campo | Descripción |
|------|------|
| Status | Habilitar o deshabilitar el medio de pago. |
| Título | Título que se visualizará en el Checkout. |
| Descripción | Descripción a presentar en el Checkout. |
| Modo Sandbox | Activa el entorno de pruebas. |
| Usar Cybersource | Habilita la protección antifraude (Recomendado). |
| Modo Debug | Genera logs detallados en _WooCommerce > Estado > Registros_. |
| Site Id / Keys | Credenciales proporcionadas por Payway para cada entorno. |

## Mejoras de la Versión 0.4.x
Esta versión introduce cambios críticos de seguridad y precisión:

### 🛡️ Seguridad 3D Secure Real
Se ha eliminado el código de simulación (MOCK). El plugin ahora realiza una validación criptográfica real con la API de Payway tras el desafío bancario, garantizando que solo los pagos autenticados sean aprobados.

### 💰 Precisión en Montos (Argentina)
Se ha refactorizado la lógica de conversión. El plugin ahora convierte matemáticamente el total a centavos (enteros), eliminando errores por separadores decimales (punto/coma) y permitiendo configurar WooCommerce con **0 decimales** de forma segura.

### 📱 Diseño Responsivo
El formulario de checkout ha sido rediseñado para ser 100% responsivo, con agrupación inteligente de campos (Expiración, CVV, DNI) en filas, mejorando la experiencia en dispositivos móviles.

### 🗺️ Mapeo de Provincias
Implementado el mapeo automático de códigos de provincia de WooCommerce a los caracteres exigidos por CyberSource (ej: AR-C a C), evitando rechazos por formato inválido.

## Consideraciones importantes
- **Checkout de Bloques**: Si su sitio muestra un error de incompatibilidad, cambie la página de Checkout al sistema clásico usando el shortcode `[woocommerce_checkout]`.
- **Moneda**: Solo soporta **ARS** y **USD**.
- **Promociones**: El plugin no se mostrará si no hay al menos una promoción activa y vigente para el día y la hora actual.

## Menú lateral
En el menú "Payway" podrá configurar:
- **Bancos**: Entidades emisoras.
- **Tarjetas**: Tipos de tarjeta (Visa, Master, etc.) con sus IDs de Payway.
- **Promociones**: Reglas de financiación por banco/tarjeta, días de la semana y coeficientes de interés.

---

# Changelog

## 0.4.3
- **Feature**: Marcador visual destacado (escudo🛡️) para pedidos protegidos por 3DS en el administrador.
- **Feature**: Persistencia de estado de autenticación 3DS para auditoría.

## 0.4.2
- **UX/UI**: Corregida desalineación de etiquetas y campos en el checkout.
- **UX/UI**: Agrupación optimizada de campos de Dirección y Altura (50/50).

## 0.4.1
- **Feature**: Sistema de detección de "Checkout por Bloques" con aviso automático.
- **Feature**: Compatibilidad declarada con HPOS.
- **Enhancement**: Mapeo de provincias de Argentina para CyberSource.
- **Fix**: Mejorada la consulta de promociones (WP Local Time y búsqueda robusta).

## 0.4.0
- **Security**: Validación real de 3D Secure (Eliminado MOCK).
- **Precision**: Redondeo matemático robusto para montos enteros (centavos).
- **UX/UI**: Formulario 100% responsivo y moderno.
- **Robustness**: Eliminado el scraping del DOM para obtener el total.

## 0.3.3
- Mejoras en la funcionalidad de descripción del formulario.

## 0.1.0
- Versión inicial.
