#!/bin/bash

# Crear usuario Tomcat
useradd -m -d /opt/tomcat -U -s /bin/false tomcat

# Actualizar la caché del administrador de paquetes e instalar JDK
apt update
apt install -y openjdk-17-jdk

# Descargar e instalar Apache Tomcat (ajusta la versión según tus necesidades)
wget https://dlcdn.apache.org/tomcat/tomcat-10/v10.1.18/bin/apache-tomcat-10.1.18.tar.gz -O tomcat.tar.gz
tar xzvf tomcat.tar.gz -C /opt/tomcat --strip-components=1

# Configurar permisos
chown -R tomcat:tomcat /opt/tomcat/
chmod -R u+x /opt/tomcat/bin
 
#Configurar usuarios administradores, hacemos el cat con la instrucción <<EOF para que considere todo el texto que sigue como entrada estándar hasta que vuelva a encontrar <<EOF. El comando tee escribe la entrada estándar en el archivo que le indicamos en la ruta y -a hace que se añadan las líneas indicadas al final del fichero.  
cat <<EOF | sudo tee -a /opt/tomcat/conf/tomcat-users.xml
<tomcat-users>
    <role rolename="manager-gui"/>
    <user username="manager" password='user1' roles="manager-gui"/>
   <role rolename="admin-gui" />
<user username="admin" password='admin1' roles="manager-gui,admin-gui" />
</tomcat-users>
EOF

#Eliminar restricciones a los administradores comentando la línea en la que vienen los comandos Valve indicados en la guía. Utilizamos la instrucción sed para automatizar el proceso.

# Ruta al archivo context.xml almacenado en la variable archivo
archivo="/opt/tomcat/webapps/manager/META-INF/context.xml"

# Comentar la línea en el archivo
sed -i '/<Valve/,/<\/Valve>/ s/^/<!-- /; s/$/ -->/' "$archivo"



#Eliminar restricciones a los host manager comentando la línea en la que vienen los comandos Valve indicados en la guía. Utilizamos la instrucción sed para automatizar el proceso.

# Ruta al archivo context.xml almacenado en la variable archivo
archivo="/opt/tomcat/webapps/host-manager/META-INF/context.xml"

# Comentar la línea en el archivo
sed -i '/<Valve/,/<\/Valve>/ s/^/<!-- /; s/$/ -->/' "$archivo"

# Captura la ruta del archivo a partir de sudo update-java-alternatives -l y lo almacenamos en la variable java_home para usar la versión correcta
java_home=$(sudo update-java-alternatives -l | awk '{print $3}')

#Hacemos cat para añadir al fichero tomcat.service las siguientes líneas. Con la instrucción <<EOF para que considere todo el texto que sigue como entrada estándar hasta que vuelva a encontrar <<EOF. El comando tee escribe la entrada estándar en el archivo que le indicamos en la ruta.  
cat <<EOF | sudo tee /etc/systemd/system/tomcat.service
[Unit]
Description=Tomcat
After=network.target

[Service]
Type=forking

User=tomcat
Group=tomcat

Environment="JAVA_HOME=$java_home"
Environment="JAVA_OPTS=-Djava.security.egd=file:///dev/urandom"
Environment="CATALINA_BASE=/opt/tomcat"
Environment="CATALINA_HOME=/opt/tomcat"
Environment="CATALINA_PID=/opt/tomcat/temp/tomcat.pid"
Environment="CATALINA_OPTS=-Xms512M -Xmx1024M -server -XX:+UseParallelGC"

ExecStart=/opt/tomcat/bin/startup.sh
ExecStop=/opt/tomcat/bin/shutdown.sh

RestartSec=10
Restart=always

[Install]
WantedBy=multi-user.target
EOF



# Recarga systemd para aplicar los cambios
systemctl daemon-reload


# Reinicia el servicio Tomcat para aplicar la nueva configuración
systemctl start tomcat

#Permitir que tomcat se inicie con el sistema
systemctl enable tomcat

#Permitimos el trafico al puerto 80 para aceptar solicitudes http
ufw allow 8080
