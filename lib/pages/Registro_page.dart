import 'package:flutter/material.dart';
import 'package:firebase_auth/firebase_auth.dart';
import 'package:cloud_firestore/cloud_firestore.dart';
import 'login_page.dart';

class RegisterPage extends StatelessWidget {
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _confirmPasswordController = TextEditingController();

  // Instancia de FirebaseAuth y Firestore
  final FirebaseAuth _auth = FirebaseAuth.instance;
  final FirebaseFirestore _firestore = FirebaseFirestore.instance;

  RegisterPage({super.key});

  // Para registrar un usuario
  Future<void> _register(BuildContext context) async {

    final email = _emailController.text;
    final password = _passwordController.text;
    final confirmPassword = _confirmPasswordController.text;

    // Validar que las contraseñas coincidan
    if (password != confirmPassword) {
      _showErrorDialog(context, "Las contraseñas no coinciden.");
      return;
    }

    try {
      // Para crear un nuevo usuario con Firebase Authentication
      UserCredential userCredential = await _auth.createUserWithEmailAndPassword(
        email: email,
        password: password,
      );
      String uid = userCredential.user!.uid;

      // Guardar la información del usuario en Firestore
      await _firestore.collection('usuarios').doc(uid).set({
        'email': email,
        'created_at': Timestamp.now(),
      });

      // para verificar que el registro a sido exitoso
      _showSuccessDialog(context);
    } on FirebaseAuthException catch (e) {
      _showErrorDialog(context, e.message ?? "Error desconocido");
    }
  }
  void _showSuccessDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Registro Exitoso'),
          content: Text('Bienvenido a UNIFOOD.'),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
                Navigator.pushReplacement(
                  context,
                  MaterialPageRoute(builder: (_) => LoginPage()),
                );
              },
              child: Text('Aceptar'),
            ),
          ],
        );
      },
    );
  }
  void _showErrorDialog(BuildContext context, String message) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Error'),
          content: Text(message),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: Text('Aceptar'),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            colors: [Colors.blueGrey.shade800, Colors.blueGrey.shade400],
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
          ),
        ),
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 32.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Hero(
                  tag: 'logo',
                  child: Material(
                    color: Colors.transparent,
                    child: Image.asset(
                      'assets/logo.png',
                      height: 120,
                      color: Colors.white,
                    ),
                  ),
                ),
                SizedBox(height: 50),
                TextField(
                  controller: _emailController,
                  decoration: InputDecoration(
                    hintText: 'Email',
                    hintStyle: TextStyle(color: Colors.white70),
                    filled: true,
                    fillColor: Colors.white.withOpacity(0.1),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(30.0),
                      borderSide: BorderSide.none,
                    ),
                    contentPadding: EdgeInsets.symmetric(vertical: 16.0, horizontal: 20.0),
                    prefixIcon: Icon(Icons.email, color: Colors.white70),
                  ),
                  style: TextStyle(color: Colors.white, fontSize: 16),
                ),
                SizedBox(height: 16),
                TextField(
                  controller: _passwordController,
                  decoration: InputDecoration(
                    hintText: 'Contraseña',
                    hintStyle: TextStyle(color: Colors.white70),
                    filled: true,
                    fillColor: Colors.white.withOpacity(0.1),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(30.0),
                      borderSide: BorderSide.none,
                    ),
                    contentPadding: EdgeInsets.symmetric(vertical: 16.0, horizontal: 20.0),
                    prefixIcon: Icon(Icons.lock, color: Colors.white70),
                  ),
                  obscureText: true,
                  style: TextStyle(color: Colors.white, fontSize: 16),
                ),
                SizedBox(height: 16),
                // Campo de Confirmar Contraseña estilizado
                TextField(
                  controller: _confirmPasswordController,
                  decoration: InputDecoration(
                    hintText: 'Confirmar Contraseña',
                    hintStyle: TextStyle(color: Colors.white70),
                    filled: true,
                    fillColor: Colors.white.withOpacity(0.1),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(30.0),
                      borderSide: BorderSide.none,
                    ),
                    contentPadding: EdgeInsets.symmetric(vertical: 16.0, horizontal: 20.0),
                    prefixIcon: Icon(Icons.lock_outline, color: Colors.white70),
                  ),
                  obscureText: true,
                  style: TextStyle(color: Colors.white, fontSize: 16),
                ),
                SizedBox(height: 30),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.white,
                      padding: EdgeInsets.symmetric(vertical: 16),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(30),
                      ),
                      elevation: 10,
                    ),
                    onPressed: () => _register(context),
                    child: Text(
                      'Registrarse',
                      style: TextStyle(
                        color: Colors.blueGrey.shade800,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
                SizedBox(height: 30),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      "¿Ya tienes cuenta? ",
                      style: TextStyle(color: Colors.white70),
                    ),
                    TextButton(
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (_) => LoginPage()),
                        );
                      },
                      child: Text(
                        "Iniciar Sesión",
                        style: TextStyle(
                          color: Colors.greenAccent,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
