import 'package:flutter/material.dart';

class SettingsPage extends StatefulWidget {
  @override
  _SettingsPageState createState() => _SettingsPageState();
}

class _SettingsPageState extends State<SettingsPage> {
  bool _notificationsEnabled = true; // Controla el estado de notificaciones
  bool _darkMode = false; // Controla el estado del tema

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Ajustes'),
      ),
      body: ListView(
        children: [
          ListTile(
            leading: Icon(Icons.notifications),
            title: Text('Notificaciones'),
            subtitle: Text(_notificationsEnabled ? 'Activadas' : 'Desactivadas'),
            trailing: Switch(
              value: _notificationsEnabled,
              onChanged: (value) {
                setState(() {
                  _notificationsEnabled = value;
                });
              },
            ),
          ),
          ListTile(
            leading: Icon(Icons.dark_mode_sharp),
            title: Text('Modo Oscuro'),
            subtitle: Text(_darkMode ? 'Activado' : 'Desactivado'),
            trailing: Switch(
              value: _darkMode,
              onChanged: (value) {
                setState(() {
                  _darkMode = value;
                });
                // Implementar lógica para cambiar el tema aquí
              },
            ),
          ),
          ListTile(
            leading: Icon(Icons.language),
            title: Text('Idioma'),
            subtitle: Text('Español'), // Cambiar dinámicamente según el idioma seleccionado
            onTap: () {
              // Navegar a una página para selección de idioma (opcional)
            },
          ),
          ListTile(
            leading: Icon(Icons.privacy_tip),
            title: Text('Privacidad y Seguridad'),
            onTap: () {
              // Navegar a una página de políticas de privacidad
              showDialog(
                context: context,
                builder: (context) {
                  return AlertDialog(
                    title: Text('Privacidad y Seguridad'),
                    actions: [
                      TextButton(
                        onPressed: () => Navigator.of(context).pop(),
                        child: Text('Cerrar'),
                      ),
                    ],
                  );
                },
              );
            },
          ),
          ListTile(
            leading: Icon(Icons.info),
            title: Text('Acerca de UniFood'),
            onTap: () {
              showAboutDialog(
                context: context,
                applicationName: 'UniFood',
                applicationVersion: 'version: 1.0.0',
                applicationLegalese: '© 2024 UniFood App '
                    'es proyecto universidad del valle sede norte del cauca',
              );
            },
          ),
        ],
      ),
    );
  }
}
