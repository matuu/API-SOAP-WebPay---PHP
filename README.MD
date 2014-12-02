#[Integración Webpay API SOAP] 
##por Daniel Gutiérrez , en noviembre 2014.

Mail: <DannielGutierrez90@Gmail.com>
Web : <http://www.digitalrevolution.cl>
Web : <http://www.danniel.co>

Sigueme en @DannielWhatever

##Primero que nada, genera el certificado
---------------------------------------

1. Generalo tú! Por ahorrarte tiempo y usar el certificado que te pasarón, puedes perder más tiempo aún por culpa de un certificado mal generado.

2. Para generar los certificados no uses Windows (opcional, yo tuve problemas usando openssl en windows).

3. Crea la llave privada. El nombre del archvo no debe ser necesariamente "oneclick", pero a veces es mejor seguirle el amen a la documentación.
Te pedirá una clave, no la omitas y anotala en lo posible.
    
    openssl genrsa -des3 -out oneclick.key 2048
 
5. Crear requerimiento de certificado. De estos campos no sé bien cuáles son obligatorios, yo siempre pongo el país "CL", la ciudad "VALPARAISO" por ejemplo, lo que si debes poner si o si es la clave y el Common Name, que correspoonde a tú Código de Comercio. Si estás en fase de integración, usa el código de comercio de integración.
    
    openssl req -new -key oneclick.key -out oneclick.csr

6. Respalda el archivo de la llave privada
    
    cp oneclick.key oneclick.key2
    
7. Y aquí hace el cifrado rsa
    
    openssl rsa -in oneclick.key2 -out oneclick.key

8. Crear certificado auto-firmado
    
    openssl x509 -req -days 365 -in oneclick.csr -signkey oneclick.key -out oneclick.crt


##Configura el archvo de constantes
---------------------------------------

1. Copia tus certificados a la carpeta del sitio, puede ser en la carpeta "/include/keys"

2. Define el valor de la variable PRIVATE_KEY como la ruta de tu clave privada

3. Define el valor de la vvariable CERT_FILE que corresponde al certificado que enviarás a Tbk.

4. Define el valor de la variable SERVER_CERT, ruta del certificado público de Tbk.

5. Define la ruta de tu sitio en SITIO_CERT


##Ahora, envía y prueba tus certificados
---------------------------------------

1. Envía comprimido el archivo .crt a la gente de Transbank para que ellos lo carguen en su servidor.

2. Una vez hayan cargado el certificado, puedes probar tu página, el webservice ya debierá aceptar tus consultas firmadas. Y tú podrás validar que las respuestas efectiivamente son de transbank gracias a su certificado público. Si llegasé haber un problema revisa el log. Si tienes un error <!-- Null Key(263) --> es que algo no salio del todo bien con los certificados.

##Genera un JKS y prueba con SoapUI 
---------------------------------------

Si no tienes problemas de comunicación con el WS puedes omitir este paso, si los tienes es útil usar una herramienta como SoapUI para detectarlos.






