import 'package:campusfood/pages/login_page.dart';
import 'package:flutter/material.dart';

class ProfilePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Perfil'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            CircleAvatar(
              radius: 60,
              backgroundImage: AssetImage('assets/perfil.jpeg'),
            ),
            SizedBox(height: 20),
            Text(
              'CODE5',
              style: TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
              ),
            ),
            SizedBox(height: 8),
            Text(
              'code5@desarrolladores.com',
              style: TextStyle(
                fontSize: 16,
                color: Colors.grey,
              ),
            ),
            SizedBox(height: 10),
            ElevatedButton(
              onPressed: () {
                Navigator.pushReplacement(
                  context,
                  MaterialPageRoute(builder: (_) => LoginPage()),
                );
              },
              child: Text('Cerrar Sesión'),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.redAccent,
              ),
            ),

            SizedBox(height: 20),
            ListTile(
              leading: Icon(Icons.history),
              title: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Este proyecto no solo es una herramienta práctica, sino también una muestra de nuestro compromiso con la innovación tecnológica y la mejora continua en los servicios de nuestra institución.',
                    textAlign: TextAlign.justify,
                    style: TextStyle(fontSize: 14.0), // Ajusta el tamaño del texto si es necesario
                  ),
                ],
              ),
            ),

          ],
        ),
      ),
    );
  }
}
